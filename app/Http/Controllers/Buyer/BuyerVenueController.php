<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use App\Models\Category;
use Illuminate\Http\Request;

class BuyerVenueController extends Controller
{
    // =========================================================================
    // DAFTAR SEMUA VENUE (INDEX)
    // Menampilkan daftar semua venue dengan filter kategori dan informasi jadwal
    // =========================================================================
    /**
     * Display a listing of all venues
     * 
     * @param Request $request - Request object yang berisi parameter filter
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Start query builder untuk mengambil data venue
        $venuesQuery = Venue::with([
            'category',                    // Relasi kategori venue
            'creator',                     // Relasi pembuat/owner venue
            'venueSections' => function($query) {
                $query->with(['venueSchedules' => function($q) {
                    // Hanya ambil jadwal yang tersedia (available = true)
                    // dan tanggalnya dari hari ini ke depan
                    $q->where('available', true)
                      ->where('date', '>=', now()->toDateString());
                }]);
            }
        ]);

        // ====================== FILTER BERDASARKAN KATEGORI ======================
        // Jika ada parameter category_id, filter berdasarkan kategori tersebut
        if ($request->filled('category_id')) {
            $venuesQuery->where('category_id', $request->category_id);
        }

        // ====================== AMBIL DATA VENUE ======================
        // Urutkan dari yang terbaru dibuat
        $venues = $venuesQuery->orderBy('created_at', 'desc')->get();
        
        // Ambil semua kategori untuk ditampilkan di filter
        $categories = Category::all();
        
        // Tampilkan view dengan data venues dan categories
        return view('buyer.venue.index', compact('venues', 'categories'));
    }
    
    // =========================================================================
    // VENUE POPULER
    // Menampilkan daftar venue dengan rating tinggi (>= 4 bintang)
    // =========================================================================
    public function popular()
    {
        // Ambil venue dengan rating minimal 4, diurutkan dari rating tertinggi
        $venues = Venue::with([
                'category',          // Relasi kategori venue
                'venueSections',     // Relasi section/lapangan dalam venue
                'creator'            // Relasi pembuat/owner venue
            ])
            ->where('rating', '>=', 4)        // Hanya venue dengan rating 4 ke atas
            ->orderByDesc('rating')           // Urutkan dari rating tertinggi
            ->get();

        // Ambil semua kategori untuk ditampilkan di filter
        $categories = Category::all();

        // Tampilkan view venue populer
        return view('buyer.venue.popular', compact('venues', 'categories'));
    }

    // =========================================================================
    // DETAIL VENUE (SHOW)
    // Menampilkan detail lengkap dari sebuah venue berdasarkan ID
    // =========================================================================
    /**
     * Display the specified venue
     * 
     * @param  int  $id - ID venue yang akan ditampilkan
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Ambil venue dengan semua relasi yang diperlukan
        // - category: kategori venue
        // - creator: pembuat/owner venue
        // - venueSections: daftar lapangan dalam venue
        //   - venueSchedules: jadwal yang tersedia untuk setiap lapangan
        $venue = Venue::with([
            'category',
            'creator',
            'venueSections' => function($query) {
                $query->with(['venueSchedules' => function($q) {
                    // Hanya ambil jadwal yang tersedia (available = true)
                    // dan tanggal dari hari ini ke depan
                    // Diurutkan dari tanggal terdekat dan waktu mulai tercepat
                    $q->where('available', true)
                      ->where('date', '>=', now()->toDateString())
                      ->orderBy('date', 'asc')
                      ->orderBy('start_time', 'asc');
                }]);
            }
        ])->findOrFail($id);  // findOrFail akan otomatis throw 404 jika tidak ditemukan
        
        // Tampilkan view detail venue
        return view('buyer.venue.show', compact('venue'));
    }

}