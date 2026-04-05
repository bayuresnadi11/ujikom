<?php

namespace App\Http\Controllers\Landowner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VenueSchedule;
use App\Models\VenueSection;
use App\Models\Booking;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Menampilkan daftar pesanan (bookings) yang masuk.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Mengambil semua venue yang dimiliki oleh user ini (melalui relasi section)
        // Tujuan kita adalah menampilkan jadwal yang sudah di-booking (available = 0)
        
        // 1. Mengambil ID Sub Lapangan (Section) khusus untuk landowner ini
        $sectionIds = VenueSection::whereHas('venue', function($q) use ($user) {
            $q->where('created_by', $user->id);
        })->pluck('id');

        // 2. Query Data: Mengambil rentetan jadwal berdasarkan kumpulan ID Sub Lapangan (Section) si pemilik yang tidak tersedia (available = 0)
        // Kita berasumsi available = 0 berarti lapangan telah fully booked
        $bookings = VenueSchedule::whereIn('section_id', $sectionIds)
            ->where('available', 0)
            ->with(['bookings.user', 'section.venue'])
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(10); // Pembatasan baris data per halaman (Pagination)

        return view('landowner.home.booking-list', compact('bookings'));
    }

    /**
     * Menampilkan detail pesanan secara spesifik.
     *
     * @param  int  $id (ID pada tabel bookings)
     * Pada index.blade.php, tautan yang digunakan adalah route('buyer.booking.show', optional($booking)->id)
     * Jadi $id di sini merujuk pada primary key dari tabel `bookings`, bukan tabel `venue_schedules`.
     */
    public function show($id)
    {
        $user = Auth::user();
        
        // Logika Sekuritas Data: Menggunakan whereHas untuk memastikan relasi bersarang (Booking -> Schedule -> Section -> Venue)
        // secara ketat hanya bisa diakses bilamana 'created_by' dari Venue tersebut MATCHES dengan $user->id (Mencegah IDOR attack)
        $booking = Booking::with(['schedule.section.venue', 'user', 'schedule.section'])
            ->whereHas('schedule.section.venue', function($q) use ($user) {
                $q->where('created_by', $user->id);
            })
            ->findOrFail($id);
            
        return view('landowner.home.booking-detail', compact('booking'));
    }
}
