<?php

namespace App\Observers;

use App\Models\Property;
use App\Models\Block;
use App\Models\Zone;
use App\Models\Slot;
use App\Models\Booking;
use Illuminate\Support\Carbon;

class PropertyObserver
{
    public function updating(Property $property): void
    {
        if ($property->isDirty('is_active') && !$property->is_active) {
            $slotIds = Slot::whereHas('zone.block', function ($q) use ($property) {
                $q->where('property_id', $property->id);
            })->pluck('id');

            if ($slotIds->isNotEmpty()) {
                $hasBookings = Booking::whereIn('slot_id', $slotIds)
                    ->whereIn('status', ['confirmed', 'pending'])
                    ->where('end_date', '>=', Carbon::now())
                    ->exists();

                if ($hasBookings) {
                    throw new \Exception('This property cannot be inactivated or deleted because it contains active or upcoming bookings.');
                }
            }
        }
    }

    public function updated(Property $property): void
    {
        if ($property->wasChanged('is_active') && !$property->is_active) {
            // Cascade deactivation to blocks, zones and slots
            $property->blocks()->update(['is_active' => false]);
            Block::where('property_id', $property->id)->each(function (Block $block) {
                $block->zones()->update(['is_active' => false]);
                Zone::where('block_id', $block->id)->each(function (Zone $zone) {
                    $zone->slots()->update(['is_active' => false]);
                });
            });
        }
    }
}

