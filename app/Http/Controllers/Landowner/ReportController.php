<?php

namespace App\Http\Controllers\Landowner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        // Logika Filter Laporan: Menarik semua rekam jejak pesanan yang berstatus 'full' (Lunas Dini)
        // terlepas dari apakah tiket QR Code-nya sudah diklaim (di-scan) ataupun belum oleh pembeli di lapangan.
        $allBookings = Booking::with(['user', 'venue', 'schedule.venueSection'])
            ->where('booking_payment', 'full')
            ->whereHas('venue', fn($q) => $q->where('created_by', auth()->id()))
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('venue', fn($v) => $v->where('venue_name', 'like', "%{$search}%"))
                    ->orWhereHas('schedule.venueSection', fn($s) => $s->where('section_name', 'like', "%{$search}%"));
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Logika Pagination Tampilan: Memecah volume data raksasa menjadi per halaman (10 per hal) 
        // agar browser tidak nge-freeze saat merender baris riwayat.
        $bookings = Booking::with(['user', 'venue', 'schedule.venueSection'])
            ->where('booking_payment', 'full')
            ->whereHas('venue', fn($q) => $q->where('created_by', auth()->id()))
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('venue', fn($v) => $v->where('venue_name', 'like', "%{$search}%"))
                    ->orWhereHas('schedule.venueSection', fn($s) => $s->where('section_name', 'like', "%{$search}%"));
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(5); 

        $allMonths = collect(range(1,12))
            ->mapWithKeys(fn($m) => [Carbon::create(null, $m, 1)->format('M Y') => 0]);

        $monthlyData = $allBookings
            ->filter(fn($b) => $b->schedule) 
            ->groupBy(fn($b) => Carbon::parse($b->schedule->date)->format('M Y')) 
            ->map(fn($bookings) => $bookings->sum(fn($b) => $b->total_paid ?? $b->amount ?? 0));    

        $monthlyData = $allMonths->merge($monthlyData);

        return view('landowner.report.index', compact('bookings', 'allBookings', 'monthlyData', 'search'));
    }

    public function exportPdf(Booking $booking)
    {
        abort_if(
            $booking->venue->created_by !== auth()->id(),
            403
        );

        $pdf = Pdf::loadView(
            'landowner.report.pdf',
            compact('booking')
        );

        return $pdf->download(
            'laporan-booking-' . $booking->id . '.pdf'
        );
    }

    public function exportMonthlyPdf($month)
    {
        $landownerId = auth()->id();

        $bookings = Booking::with(['user','venue','schedule.venueSection','schedule.section'])
            ->where('booking_payment','full')
            ->whereHas('venue', fn($q)=>$q->where('created_by',$landownerId))
            ->whereHas('schedule', function($q) use ($month){
                $q->whereMonth('date', $month);
            })
            ->get();

        $totalPendapatan = $bookings->sum(fn($b)=> $b->total_paid ?? $b->amount ?? 0);

        $totalBooking = $bookings->count();

        $pdf = Pdf::loadView('landowner.report.monthly-pdf', [
            'bookings' => $bookings,
            'monthName' => Carbon::create()->month((int) $month)->format('F'),
            'totalPendapatan' => $totalPendapatan,
            'totalBooking' => $totalBooking
        ]);

        return $pdf->download('laporan-bulanan-'.$month.'.pdf');
    }
}