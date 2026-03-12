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
     * Display a listing of the bookings.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get all venues belonging to this user (via sections)
        // We want to show schedules that have bookings (available = 0 and has bookings)
        
        // 1. Get Section IDs for this landowner
        $sectionIds = VenueSection::whereHas('venue', function($q) use ($user) {
            $q->where('created_by', $user->id);
        })->pluck('id');

        // 2. Get Schedules that are booked
        // We assume 'available' = 0 means booked (or at least unavailable)
        // Adjust query to ensure we only get actual bookings if you have a Booking model relation
        $bookings = VenueSchedule::whereIn('section_id', $sectionIds)
            ->where('available', 0)
            ->with(['bookings.user', 'section.venue'])
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(10); // Pagination

        return view('landowner.home.booking-list', compact('bookings'));
    }

    /**
     * Display the specified booking.
     *
     * @param  int  $id (Booking ID or Schedule ID? User clicked on a booking item which usually wraps a schedule or a booking)
     * In index.blade.php, the link was route('buyer.booking.show', optional($booking)->id)
     * So $id is likely the `bookings` table ID, NOT `venue_schedules` ID.
     */
    public function show($id)
    {
        $user = Auth::user();
        
        // Find the booking and ensure it belongs to a venue owned by this user
        $booking = Booking::with(['schedule.section.venue', 'user', 'schedule.section'])
            ->whereHas('schedule.section.venue', function($q) use ($user) {
                $q->where('created_by', $user->id);
            })
            ->findOrFail($id);
            
        return view('landowner.home.booking-detail', compact('booking'));
    }
}
