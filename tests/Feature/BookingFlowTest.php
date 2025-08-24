<?php

namespace Tests\Feature;

use App\Console\Commands\ExpireBookingHolds;
use App\Models\Booking;
use App\Models\Property;
use App\Models\Block;
use App\Models\Zone;
use App\Models\Slot;
use App\Models\User;
use App\Models\Vessel;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_expire_booking_holds_command(): void
    {
        $user = User::factory()->create();
        $property = Property::create(['name' => 'Test', 'code' => 'T1']);
        $block = Block::create(['property_id' => $property->id, 'name' => 'B1', 'code' => 'B1']);
        $zone = Zone::create(['block_id' => $block->id, 'name' => 'Z1', 'code' => 'Z1']);
        $slot = Slot::create(['zone_id' => $zone->id, 'code' => 'S1', 'location' => 'Dock 1']);

        $vessel = Vessel::create([
            'owner_client_id' => $user->id,
            'name' => 'Test Vessel',
            'registration_number' => 'REG123',
            'is_active' => true,
        ]);

        $start = Carbon::now();
        $end = (clone $start)->addHour();

        $booking = Booking::create([
            'booking_number' => 'BK-' . Str::random(8),
            'user_id' => $user->id,
            'vessel_id' => $vessel->id,
            'slot_id' => $slot->id,
            'start_date' => $start,
            'end_date' => $end,
            'status' => 'on_hold',
            'hold_expires_at' => Carbon::now()->subHour(),
        ]);

        $this->artisan('bookings:expire-holds')
            ->expectsOutput('Released 1 booking holds.')
            ->assertExitCode(0);

        $booking->refresh();
        $this->assertNull($booking->hold_expires_at);
        $this->assertEquals('requested', $booking->status);
    }
}
