<?php

namespace Tests\Feature;

use App\Console\Commands\ExpireBookingHolds;
use App\Models\Booking;
use App\Models\Property;
use App\Models\Resource;
use App\Models\Slot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_expire_booking_holds_command(): void
    {
        $user = User::factory()->create();
        $property = Property::create(['name' => 'Test', 'code' => 'T1']);
        $resource = Resource::create(['property_id' => $property->id, 'name' => 'R1']);
        $start = Carbon::now();
        $end = (clone $start)->addHour();
        $slot = Slot::create(['resource_id' => $resource->id, 'start_at' => $start, 'end_at' => $end]);

        $booking = Booking::create([
            'client_id' => $user->id,
            'property_id' => $property->id,
            'resource_id' => $resource->id,
            'slot_id' => $slot->id,
            'start_at' => $start,
            'end_at' => $end,
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
