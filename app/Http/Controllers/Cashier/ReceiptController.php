<?php
// app/Http/Controllers/Cashier/ReceiptController.php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReceiptController extends Controller
{
    public function show($transactionCode)
    {
            try {
        \Illuminate\Support\Facades\Log::info('Receipt Show called with code: ' . $transactionCode);

        // Cari semua booking dengan ticket_code yang sama
        $bookings = Booking::with([
            'user', 
            'schedule.venueSection.venue', 
            // 'schedule.venueSection.category', // Removed invalid relationship
            'venue.category'
        ])
        ->where('ticket_code', $transactionCode)
        ->get();
        
        \Illuminate\Support\Facades\Log::info('Bookings found: ' . $bookings->count());
        
        if ($bookings->isEmpty()) {
            abort(404, 'Transaksi tidak ditemukan');
        }
        
        $firstBooking = $bookings->first();
        
        // Buat object transaksi virtual dari booking
        $transaction = (object)[
            'transaction_code' => $transactionCode,
            'customer' => $firstBooking->user,
            'cashier_name' => auth()->user()->name ?? 'Kasir',
            'total_amount' => $bookings->sum('amount'),
            'payment_method' => $firstBooking->method,
            'cash_received' => $firstBooking->paid_amount,
            'change_amount' => $firstBooking->getChangeAttribute(),
            'community' => $firstBooking->community,
            'created_at' => $firstBooking->created_at,
            'bookings' => $bookings->map(function($booking) {
                $schedule = $booking->schedule;
                $venue = $booking->venue;
                // Use venueSection instead of section
                $section = $schedule ? ($schedule->venueSection ?? $schedule->section) : null;
                
                return (object)[
                    'id' => $booking->id,
                    'venue_name' => $venue ? $venue->venue_name : 'Venue',
                    'section_name' => $section ? $section->section_name : 'Section',
                    'section' => $section,
                    'start_time' => $schedule ? $schedule->start_time : '00:00',
                    'end_time' => $schedule ? $schedule->end_time : '00:00',
                    'duration' => $schedule ? $schedule->rental_duration : 0,
                    'price' => $booking->amount,
                    'amount' => $booking->amount,
                    'booking_date' => $schedule ? $schedule->date : $booking->created_at->format('Y-m-d'),
                    'created_at' => $booking->created_at,
                    'booking_payment' => $booking->booking_payment
                ];
            })
        ];
            
            // Ambil pengaturan aplikasi
            $appSettings = Setting::first();
            
            // Jika ingin auto print, tambahkan parameter
            $autoPrint = request()->has('autoprint');
            $autoClose = request()->has('autoclose');
            
            return view('cashier.queue.receipt', compact('transaction', 'appSettings', 'autoPrint', 'autoClose'));
            
        } catch (\Exception $e) {
            // Buat view error sederhana
            return response()->view('errors.receipt-error', [
                'message' => 'Gagal memuat struk: ' . $e->getMessage()
            ], 500);
        }
    }
}