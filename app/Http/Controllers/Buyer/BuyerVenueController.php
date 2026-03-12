<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use App\Models\Category;
use Illuminate\Http\Request;

class BuyerVenueController extends Controller
{
    /**
     * Display a listing of all venues
     * 
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Start query builder
        $venuesQuery = Venue::with(['category', 'creator', 'venueSections' => function($query) {
             $query->with(['venueSchedules' => function($q) {
                 $q->where('available', true)
                   ->where('date', '>=', now()->toDateString());
             }]);
        }]);

        // Filter by category if a category is selected
        if ($request->filled('category_id')) {
            $venuesQuery->where('category_id', $request->category_id);
        }

        // Get the venues
        $venues = $venuesQuery->orderBy('created_at', 'desc')->get();
        
        // Ambil semua kategori untuk filter
        $categories = Category::all();
        
        return view('buyer.venue.index', compact('venues', 'categories'));
    }
    
    public function popular()
    {
        $venues = Venue::with([
                'category',
                'venueSections',
                'creator'
            ])
            ->where('rating', '>=', 4)
            ->orderByDesc('rating')
            ->get();

        $categories = Category::all();

        return view('buyer.venue.popular', compact('venues', 'categories'));
    }

    /**
     * Display the specified venue
     * 
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Ambil venue dengan semua relasi yang diperlukan
        $venue = Venue::with([
            'category',
            'creator',
            'venueSections' => function($query) {
                $query->with(['venueSchedules' => function($q) {
                    $q->where('available', true)
                      ->where('date', '>=', now()->toDateString())
                      ->orderBy('date', 'asc')
                      ->orderBy('start_time', 'asc');
                }]);
            }
        ])->findOrFail($id);
        
        return view('buyer.venue.show', compact('venue'));
    }


}