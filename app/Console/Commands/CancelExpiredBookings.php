<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;

class CancelExpiredBookings extends Command
{
    protected $signature = 'bookings:cancel-expired';
    protected $description = 'Cancel expired unpaid bookings';

    public function handle()
    {
        Log::info('⏰ CancelExpiredBookings started at '.now());

        $bookings = Booking::where('status', 'pending')
            ->where('payment_status', 'pending')
            ->whereNotNull('payment_expired_at')
            ->where('payment_expired_at', '<', now())
            ->get();

        Log::info('🔍 Expired bookings found: '.$bookings->count());

        foreach ($bookings as $booking) {
            Log::info('❌ Cancel booking ID '.$booking->id);

            $booking->update([
                'status' => 'canceled',
                'payment_status' => 'expired',
            ]);

            // balikin jadwal
            if ($booking->schedule) {
                $booking->schedule->update(['available' => true]);
            }
        }

        $this->info('Canceled '.$bookings->count().' expired bookings');

        Log::info('✅ CancelExpiredBookings finished');
    }
}
