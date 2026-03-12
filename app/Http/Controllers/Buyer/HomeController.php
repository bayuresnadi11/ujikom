<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Venue;
use App\Models\Community;
use App\Models\User;
use App\Models\Booking;
use App\Models\Category;

class HomeController extends Controller
{
    /**
     * Display the home page for penyewa
     */
    public function index()
    {
        $user = Auth::user();

        // Fetch available venues
        $venues = Venue::with('category')->limit(10)->get();

        // Fetch available communities
        $communities = Community::with('category', 'creator')->limit(10)->get();

        $categories = Category::all();

        // Fetch dynamic stats
        $stats = [
            'venues_count' => Venue::count(),
            'users_count' => User::count(),
            'bookings_count' => Booking::count(),
            'total_rating' => Venue::sum('rating')
        ];

        return view('buyer.home.index', [
            'title' => 'Dashboard',
            'user' => $user,
            'venues' => $venues,
            'communities' => $communities,
            'categories' => $categories,
            'stats' => $stats
        ]);
        
    }

    /**
     * Display the profile page for penyewa
     */
    public function profile()
    {
        $user = Auth::user();
        
        return view('buyer.home.profile', [
            'title' => 'Profile',
            'user' => $user
        ]);
    }
}
