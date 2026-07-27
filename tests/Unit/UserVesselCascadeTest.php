<?php

namespace Tests\Unit;

use App\Models\{User, Vessel};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;

class UserVesselCascadeTest extends TestCase
{
    use RefreshDatabase;

    public function test_deactivating_client_inactivates_vessels(): void
    {
        $user = User::factory()->create(['user_type' => 'client', 'is_active' => true]);
        $vessel = Vessel::create([
            'owner_client_id' => $user->id,
            'name' => 'V1',
            'registration_number' => 'RN-' . Str::random(4),
            'is_active' => true,
        ]);

        $user->update(['is_active' => false]);

        $this->assertFalse($vessel->fresh()->is_active);
    }
}

