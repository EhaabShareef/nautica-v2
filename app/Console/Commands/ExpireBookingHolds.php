<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpireBookingHolds extends Command
{
    protected $signature = 'bookings:expire-holds';

    protected $description = 'Release expired booking holds';

    public function handle(): int
    {
        $count = Booking::where('status', 'on_hold')
            ->whereNotNull('hold_expires_at')
            ->where('hold_expires_at', '<', Carbon::now())
            ->update(['status' => 'requested', 'hold_expires_at' => null]);

        $this->info("Released {$count} booking holds.");

        return Command::SUCCESS;
    }
}
