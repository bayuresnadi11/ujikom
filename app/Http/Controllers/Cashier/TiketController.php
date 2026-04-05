<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Venue;
use App\Models\VenueSection;
use Carbon\Carbon;

class TiketController extends Controller
{
    /**
     * Halaman Kelola Tiket
     */

public function index()
{
    $today = Carbon::today();

    $bookings = Booking::with([
            'user',
            'venue',
            'schedule',
            'playTogether'
        ])
        ->whereHas('schedule', function ($q) use ($today) {
            $q->whereDate('date', '>=', $today);
        })
        ->orderBy('created_at', 'desc')
        ->get();

    $sections = VenueSection::all();
    $venues   = Venue::all();

    return view('cashier.ticket.ticket', compact('bookings', 'sections', 'venues'));
}


    /**
     * Tampilkan QR Tiket
     */
    public function showQr($id)
    {
        $booking = Booking::findOrFail($id);

        return view('cashier.ticket.qr', compact('booking'));
    }

    /**
     * Update status scan tiket
     */
    public function scan($id)
    {
        $booking = Booking::findOrFail($id);

        // optional: tolak kalau belum bayar
        if (!$booking->isPaid()) {
            return back()->with('warning', 'Tiket belum dibayar');
        }

        if ($booking->scan_status === 'belum_scan') {
            $booking->update([
                'scan_status' => 'masuk_venue',
                'scan_time'   => now(),
            ]);

            return back()->with('success', 'Scan berhasil: Masuk venue');
        }

        if ($booking->scan_status === 'masuk_venue') {
            $booking->update([
                'scan_status' => 'masuk_lapang',
            ]);

            return back()->with('success', 'Scan berhasil: Masuk lapangan');
        }

        return back()->with('info', 'Tiket sudah digunakan');
    }

}
