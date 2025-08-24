<?php

namespace App\Observers;

use App\Models\Block;
use App\Models\Zone;
use App\Models\Slot;
use App\Models\Booking;
use Illuminate\Support\Carbon;

class BlockObserver
{
    public function updating(Block $block): void
    {
        if ($block->isDirty('is_active') && !$block->is_active) {
            $slotIds = Slot::whereHas('zone', function ($q) use ($block) {
                $q->where('block_id', $block->id);
            })->pluck('id');

            if ($slotIds->isNotEmpty()) {
                $hasBookings = Booking::whereIn('slot_id', $slotIds)
                    ->whereIn('status', ['confirmed', 'pending'])
                    ->where('end_date', '>=', Carbon::now())
                    ->exists();

                if ($hasBookings) {
                    throw new \Exception('This block cannot be inactivated or deleted because it contains active or upcoming bookings.');
                }
            }
        }
    }

    public function updated(Block $block): void
    {
        if ($block->wasChanged('is_active') && !$block->is_active) {
            $block->zones()->update(['is_active' => false]);
            Zone::where('block_id', $block->id)->each(function (Zone $zone) {
                $zone->slots()->update(['is_active' => false]);
            });
        }
    }
}

