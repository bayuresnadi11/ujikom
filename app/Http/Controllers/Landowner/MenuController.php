<?php

namespace App\Http\Controllers\Landowner;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use App\Models\VenueSection;

class MenuController extends Controller
{
    public function index()
    {
        // Hitung total lapangan milik user
        $totalVenue = Venue::where('created_by', auth()->id())->count();
        
        // Hitung total sections dari lapangan milik user
        $totalSections = VenueSection::whereHas('venue', function($query) {
            $query->where('created_by', auth()->id());
        })->count();

        // Kembalikan view dengan data minimal
        return view('landowner.menu.index', compact(
            'totalVenue',
            'totalSections'
        ));
    }
}