<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use App\Models\VenueSection;
use App\Models\Schedule;
use Carbon\Carbon;

class DisplayController extends Controller
{
    /**
     * ===============================
     * DASHBOARD INDEX
     * ===============================
     */
    public function index()
    {
        $today = Carbon::today()->format('Y-m-d');

        $totalVenues   = Venue::count();
        $totalSections = VenueSection::count();

        $todaysSchedules = Schedule::whereDate('date', $today)->count();

        $recentVenues = Venue::with([
            'owner', // Eager load owner
            'venueSections.schedules' => function ($q) use ($today) {
                $q->whereDate('date', $today);
            }
        ])
        ->whereHas('venueSections.schedules', function ($q) use ($today) {
            $q->whereDate('date', $today);
        })
        ->take(4)
        ->get();

        $recentSections = VenueSection::with([
            'venue.owner', // Eager load owner
            'schedules' => function ($q) use ($today) {
                $q->whereDate('date', $today)
                  ->orderBy('start_time');
            }
        ])
        ->whereHas('schedules', function ($q) use ($today) {
            $q->whereDate('date', $today);
        })
        ->take(6)
        ->get();

        return view('cashier.schedule.index', compact(
            'totalVenues',
            'totalSections',
            'todaysSchedules',
            'recentVenues',
            'recentSections'
        ));
    }

    /**
     * ===============================
     * LIST VENUE
     * ===============================
     */
    public function venues()
    {
        $today = Carbon::today()->format('Y-m-d');

        $venues = Venue::with([
            'owner', // Eager load owner
            'venueSections.schedules' => function ($q) use ($today) {
                $q->whereDate('date', $today);
            }
        ])
        ->whereHas('venueSections.schedules', function ($q) use ($today) {
            $q->whereDate('date', $today);
        })
        ->orderBy('venue_name')
        ->get();

        return view('cashier.schedule.venue-list', compact('venues'));
    }

    /**
     * ===============================
     * LIST SECTION
     * ===============================
     */
    public function sections()
    {
        $today = Carbon::today()->format('Y-m-d');

        $sections = VenueSection::with([
            'venue.owner', // Eager load owner
            'schedules' => function ($q) use ($today) {
                $q->whereDate('date', $today)
                  ->orderBy('start_time');
            }
        ])
        ->whereHas('schedules', function ($q) use ($today) {
            $q->whereDate('date', $today);
        })
        ->orderBy('section_name')
        ->get();

        return view('cashier.schedule.sections', compact('sections'));
    }

    /**
     * ===============================
     * DETAIL SECTION
     * ===============================
     */
    public function sectionDetail(VenueSection $section)
    {
        $today = Carbon::today()->format('Y-m-d');

        $section->load([
            'venue.owner',
            'schedules' => function ($q) use ($today) {
                $q->whereDate('date', $today)
                  ->orderBy('start_time')
                  ->with(['booking.user', 'booking.playTogether.community']);
            }
        ]);

        $schedules = $section->schedules->map(function ($schedule) {
            $start = Carbon::parse($schedule->start_time);
            $end   = Carbon::parse($schedule->end_time);

            $booking = $schedule->booking;
            $isPaid = $booking && $booking->payment_status === 'paid';

            return [
                'id' => $schedule->id,
                'start_time' => $start->format('H:i'),
                'end_time'   => $end->format('H:i'),
                'time_range' => $start->format('H:i').' - '.$end->format('H:i'),

                'customer_name' =>
                    optional($booking)->user?->name
                    ?? optional($booking)->playTogether?->community?->name
                    ?? 'KOSONG',

                'is_booked' => $booking !== null,
                'is_paid' => $isPaid,
                'scan_status' => ($booking && !empty($booking->scan_status)) ? $booking->scan_status : ($booking ? 'belum_scan' : null),
                'is_now'    => now()->between($start, $end),
            ];
        });

        return view('cashier.schedule.section', [
            'section' => $section,
            'schedules' => $schedules,
            'date' => Carbon::today()->format('d M Y'),
            'now'  => Carbon::now()->format('H:i'),
        ]);
    }

    /**
     * ===============================
     * DISPLAY SEMUA SECTION DALAM 1 VENUE
     * ===============================
     */
   public function venueSections(Venue $venue)
{
    $today = Carbon::today()->format('Y-m-d');
    $now = Carbon::now();
    $venue->load([
        'owner', // Eager load owner for avatar
        'venueSections.schedules' => function ($q) use ($today) {
            $q->whereDate('date', $today)
            ->orderBy('start_time')
            ->with([
                'booking.user',
                'booking.playTogether.community'
            ]);
        }
    ]);


    $sectionsSlides = collect();
    $gridSchedules = collect();

    foreach ($venue->venueSections as $section) {
        if ($section->schedules->isEmpty()) continue;

        $sectionSchedules = collect();

        foreach ($section->schedules as $schedule) {
            $start = Carbon::parse($schedule->start_time);
            $end   = Carbon::parse($schedule->end_time);

            $booking = $schedule->booking;
            $isPaid = $booking && $booking->payment_status === 'paid';

            $data = [
                'id' => $schedule->id,
                'section_name' => $section->section_name,
                'customer_name' =>
                    $booking?->user?->name
                    ?? $booking?->community?->name
                    ?? 'LAPANGAN KOSONG',

                'start_time' => $start->format('H:i'),
                'end_time'   => $end->format('H:i'),

                'is_booked' => $booking !== null,
                'is_paid' => $isPaid,
                'scan_status' => $isPaid ? $booking->scan_status : null,

                'is_current' => $now->between($start, $end),
                'is_passed' => $end->lt($now),
                'start_datetime' => $start,
            ];

            // GRID (jadwal mendatang)
            if (!$data['is_passed'] && !$data['is_current']) {
                $gridSchedules->push($data);
            }

            // SLIDE (jadwal aktif)
            if ($data['is_current']) {
                $sectionSchedules->push($data);
            }
        }

        if ($sectionSchedules->isNotEmpty()) {
            $sectionsSlides->push([
                'section_name' => $section->section_name,
                'schedules' => $sectionSchedules->values(),
            ]);
        }
    }

    return view('cashier.schedule.venue', [
        'venue' => $venue,
        'sectionsSlides' => $sectionsSlides->values(),
        'gridSchedules' => $gridSchedules
            ->sortBy('start_datetime')
            ->take(11)
            ->values(),
    ]);
}

}
