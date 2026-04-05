<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Daftarkan command custom
     */
    protected $commands = [
        // kalau pakai Laravel 9+ biasanya BOLEH kosong
    ];

    /**
     * Schedule command
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            app(BookingController::class)->expireRegularBookings();
        })->everyMinute();
    }

    /**
     * Load semua command
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
