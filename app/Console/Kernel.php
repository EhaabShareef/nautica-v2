<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Register the application's scheduled tasks.
     *
     * Uses the framework scheduler to register the `bookings:expire-holds` Artisan command
     * to run hourly.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('bookings:expire-holds')->hourly();
    }

    /**
     * Register the application's custom Artisan command classes.
     *
     * Loads command classes from the app/Console/Commands directory so they are
     * discovered by Artisan and available to the console kernel (including scheduling).
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}
