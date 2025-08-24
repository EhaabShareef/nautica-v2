<?php

namespace Tests\Unit;

use App\Models\{Property, Block, Zone, Slot, Booking, User, Vessel};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PropertyCascadeTest extends TestCase
{
    use RefreshDatabase;

    public function test_property_deactivation_cascades_to_children(): void
    {
        $property = Property::create(['name' => 'P', 'code' => 'P1']);
        $block = Block::create(['property_id' => $property->id, 'name' => 'B', 'code' => 'B1']);
        $zone = Zone::create(['block_id' => $block->id, 'name' => 'Z', 'code' => 'Z1']);
        $slot = Slot::create(['zone_id' => $zone->id, 'code' => 'S1', 'location' => 'L1']);

        $property->update(['is_active' => false]);

        $this->assertFalse($block->fresh()->is_active);
        $this->assertFalse($zone->fresh()->is_active);
        $this->assertFalse($slot->fresh()->is_active);
    }

    public function test_property_deactivation_blocked_with_active_bookings(): void
    {
        $property = Property::create(['name' => 'P', 'code' => 'P1']);
        $block = Block::create(['property_id' => $property->id, 'name' => 'B', 'code' => 'B1']);
        $zone = Zone::create(['block_id' => $block->id, 'name' => 'Z', 'code' => 'Z1']);
        $slot = Slot::create(['zone_id' => $zone->id, 'code' => 'S1', 'location' => 'L1']);

        $user = User::factory()->create();
        $vessel = Vessel::create([
            'owner_client_id' => $user->id,
            'name' => 'V1',
            'registration_number' => 'RN-' . Str::random(4),
            'is_active' => true,
        ]);

        Booking::create([
            'booking_number' => 'BK-' . Str::random(8),
            'user_id' => $user->id,
            'vessel_id' => $vessel->id,
            'slot_id' => $slot->id,
            'start_date' => Carbon::now()->addDay(),
            'end_date' => Carbon::now()->addDays(2),
            'status' => 'confirmed',
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('cannot be inactivated');
        $property->update(['is_active' => false]);
    }
}

