<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Vessel;
use App\Services\VesselService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class VesselServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_with_valid_owner(): void
    {
        $owner = User::factory()->create([
            'user_type' => 'client',
            'is_active' => true,
            'is_blacklisted' => false,
        ]);

        $service = new VesselService();
        $vessel = $service->create([
            'owner_client_id' => $owner->id,
            'name' => 'Test Vessel',
            'registration_number' => 'REG-001',
            'is_active' => true,
        ]);

        $this->assertInstanceOf(Vessel::class, $vessel);
        $this->assertDatabaseHas('vessels', [
            'id' => $vessel->id,
            'owner_client_id' => $owner->id,
        ]);
    }

    public function test_create_with_inactive_owner_fails(): void
    {
        $owner = User::factory()->create([
            'user_type' => 'client',
            'is_active' => false,
        ]);

        $this->expectException(ValidationException::class);

        (new VesselService())->create([
            'owner_client_id' => $owner->id,
            'name' => 'Test Vessel',
            'registration_number' => 'REG-002',
        ]);
    }

    public function test_assigning_blacklisted_renter_fails(): void
    {
        $owner = User::factory()->create([
            'user_type' => 'client',
            'is_active' => true,
        ]);
        $renter = User::factory()->create([
            'user_type' => 'client',
            'is_active' => true,
            'is_blacklisted' => true,
        ]);

        $this->expectException(ValidationException::class);

        (new VesselService())->create([
            'owner_client_id' => $owner->id,
            'renter_client_id' => $renter->id,
            'name' => 'Test Vessel',
            'registration_number' => 'REG-003',
        ]);
    }

    public function test_renter_cannot_be_same_as_owner(): void
    {
        $owner = User::factory()->create([
            'user_type' => 'client',
            'is_active' => true,
        ]);

        $this->expectException(ValidationException::class);

        (new VesselService())->create([
            'owner_client_id' => $owner->id,
            'renter_client_id' => $owner->id,
            'name' => 'Test Vessel',
            'registration_number' => 'REG-004',
        ]);
    }
}
