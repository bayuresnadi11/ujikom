<?php

namespace App\Http\Controllers\Landowner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\Venue;
use App\Models\VenueSection;
use App\Models\VenueSchedule;
use App\Models\Booking;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Display the home page for pemilik
     */
    public function index()
    {
        $user = Auth::user();
        
        // Debug info
        Log::info('Pemilik Home Accessed', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'temp_role' => Session::get('temp_role'),
            'session_id' => Session::getId()
        ]);
        
        $isTemporaryAccess = Session::get('temp_role') === 'landowner';
        
        // PERBAIKAN: Sesuai dengan model Venue, kolom yang digunakan adalah 'created_by'
        $venues = Venue::where('created_by', $user->id)
            ->with(['photos', 'category']) // Eager load photos and category
            ->withCount('venueSections')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Debug untuk melihat data venue
        Log::info('Venues Data', [
            'user_id' => $user->id,
            'venues_count' => $venues->count(),
            'query_used' => 'created_by = ' . $user->id
        ]);
        
        // Hitung statistik
        $totalBookingHariIni = 0;
        $pendapatanBulanIni = 0;
        $totalField = 0;
        
        if ($venues->isNotEmpty()) {
            // Ambil semua section ID dari venue pemilik
            $venueIds = $venues->pluck('id')->toArray();
            $sectionIds = VenueSection::whereIn('venue_id', $venueIds)
                ->pluck('id')
                ->toArray();
                
            // Hitung statistik
            $totalBookingHariIni = VenueSchedule::whereIn('section_id', $sectionIds)
                ->whereDate('date', Carbon::today())
                ->where('available', 0)
                ->count();
                
            $pendapatanBulanIni = VenueSchedule::whereIn('section_id', $sectionIds)
                ->whereMonth('date', Carbon::now()->month)
                ->whereYear('date', Carbon::now()->year)
                ->where('available', 0)
                ->sum('rental_price');
                
            $totalField = count($sectionIds); // Jumlah total lapangan (sections)
            
            // PERBAIKAN: Mengubah 'booking' menjadi 'bookings' (plural)
            // Juga tambahkan relasi 'section' jika belum ada di model VenueSchedule
            $bookingTerbaru = VenueSchedule::whereIn('section_id', $sectionIds)
                ->where('available', 0)
                ->with(['bookings.user', 'section.venue']) // PERBAIKAN DI SINI: 'bookings' bukan 'booking'
                ->orderBy('date', 'desc')
                ->orderBy('start_time', 'desc')
                ->limit(5)
                ->get();
                
            // Debug booking data
            Log::info('Booking Stats', [
                'total_booking_hari_ini' => $totalBookingHariIni,
                'pendapatan_bulan_ini' => $pendapatanBulanIni,
                'total_field' => $totalField,
                'booking_terbaru_count' => $bookingTerbaru->count()
            ]);
        } else {
            $bookingTerbaru = collect();
            
            // Debug jika tidak ada venue
            Log::info('No Venues Found', [
                'user_id' => $user->id,
                'message' => 'User tidak memiliki venue yang terdaftar'
            ]);
        }

        return view('landowner.home.index', [
            'title' => 'Dashboard',
            'user' => $user,
            'isTemporaryAccess' => $isTemporaryAccess,
            'venues' => $venues,
            'totalVenues' => $venues->count(),
            'totalField' => $totalField,
            'totalBookingHariIni' => $totalBookingHariIni,
            'pendapatanBulanIni' => $pendapatanBulanIni,
            'bookingTerbaru' => $bookingTerbaru
        ]);
    }
    
    /**
     * Dashboard pemilik
     */
    public function dashboard()
    {
        $user = Auth::user();
        $isTemporaryAccess = Session::get('temp_role') === 'landowner';
        
        return view('landowner.dashboard.index', [
            'title' => 'Dashboard Pemilik - Kelola Lapangan',
            'user' => $user,
            'isTemporaryAccess' => $isTemporaryAccess
        ]);
    }
}