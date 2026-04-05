<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;

class ExpireBookings extends Command
{
    protected $signature = 'bookings:expire';
    protected $description = 'Update booking pending yang sudah lewat expired_at menjadi expired';

    public function handle()
    {
        Booking::where('type', 'regular')
            ->where('booking_payment', 'pending')
            ->whereNotNull('expired_at')
            ->where('expired_at', '<', now())
            ->chunk(50, function ($bookings) {
                foreach ($bookings as $booking) {
                    $booking->update([
                        'booking_payment' => 'expired',
                        'status' => 'canceled',
                    ]);

                    // Optional: update jadwal jadi available
                    optional($booking->schedule)->update(['available' => true]);

                    Log::info("Booking expired auto-update", ['booking_id' => $booking->id]);
                }
            });

        $this->info('Expired bookings updated successfully.');
    }
}