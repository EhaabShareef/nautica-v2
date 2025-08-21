<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\Block;
use App\Models\Zone;
use App\Models\Slot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HierarchyTest extends TestCase
{
    use RefreshDatabase;

    public function test_property_block_zone_slot_relationships(): void
    {
        $property = Property::create(['name' => 'P1', 'code' => 'P1']);
        $block = $property->blocks()->create(['name' => 'B1', 'code' => 'B1']);
        $zone = $block->zones()->create(['name' => 'Z1', 'code' => 'Z1']);
        $slot = $zone->slots()->create(['code' => 'S1', 'location' => 'Dock 1']);

        $this->assertTrue($property->blocks->contains($block));
        $this->assertTrue($block->zones->contains($zone));
        $this->assertTrue($zone->slots->contains($slot));
    }
}
