<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\BookingEligibility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingEligibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_user_can_book(): void
    {
        $user = User::factory()->create([
            'user_type' => 'client',
            'is_active' => true,
            'is_blacklisted' => false,
        ]);

        $service = new BookingEligibility();
        $this->assertTrue($service->canBook($user));
    }

    public function test_inactive_user_cannot_book(): void
    {
        $user = User::factory()->create([
            'user_type' => 'client',
            'is_active' => false,
        ]);

        $service = new BookingEligibility();
        $this->assertFalse($service->canBook($user));
    }

    public function test_blacklisted_user_cannot_book(): void
    {
        $user = User::factory()->create([
            'user_type' => 'client',
            'is_active' => true,
            'is_blacklisted' => true,
        ]);

        $service = new BookingEligibility();
        $this->assertFalse($service->canBook($user));
    }
}
