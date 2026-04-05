<?php

namespace App\Http\Controllers\Landowner;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use App\Models\VenueSection;

class MenuController extends Controller
{
    public function index()
    {
        // Logika Agregasi: Menghitung total pangkalan (Venue) dan sub-lapangan (Sections) milik user untuk ringkasan menu
        $totalVenue = Venue::where('created_by', auth()->id())->count();
        
        // Menghitung total sections dengan filter relasi venue milik landowner yang sedang login
        $totalSections = VenueSection::whereHas('venue', function($query) {
            $query->where('created_by', auth()->id());
        })->count();

        // Mengirimkan data statistik ke view menu
        return view('landowner.menu.index', compact(
            'totalVenue',
            'totalSections'
        ));
    }
}