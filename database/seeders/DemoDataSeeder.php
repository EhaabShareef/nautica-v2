<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Property;
use App\Models\Block;
use App\Models\Zone;
use App\Models\Slot;
use App\Models\User;
use App\Models\Vessel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create users
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => Hash::make('password')]
        );
        $admin->assignRole('admin');

        $client = User::updateOrCreate(
            ['email' => 'client@example.com'],
            ['name' => 'Client', 'password' => Hash::make('password')]
        );
        $client->assignRole('client');

        // Property hierarchy
        $property = Property::updateOrCreate(
            ['code' => 'MAR1'],
            ['name' => 'Demo Marina']
        );

        $block = Block::updateOrCreate(
            ['property_id' => $property->id, 'code' => 'B1'],
            ['name' => 'Block 1']
        );

        $zone = Zone::updateOrCreate(
            ['block_id' => $block->id, 'code' => 'Z1'],
            ['name' => 'Zone 1']
        );

        $slot = Slot::updateOrCreate(
            ['zone_id' => $zone->id, 'code' => 'S1'],
            []
        );

        $vessel = Vessel::updateOrCreate(
            ['client_id' => $client->id, 'name' => 'Sea Breeze'],
            []
        );

        $start = now()->addDay();
        $end = (clone $start)->addHours(2);

        $booking = Booking::updateOrCreate(
            [
                'client_id' => $client->id,
                'vessel_id' => $vessel->id,
                'slot_id' => $slot->id,
            ],
            [
                'property_id' => $property->id,
                'block_id' => $block->id,
                'zone_id' => $zone->id,
                'start_at' => $start,
                'end_at' => $end,
                'status' => 'approved',
                'type' => 'standard',
                'priority' => 'normal',
            ]
        );

        if (!$booking->logs()->exists()) {
            $booking->logs()->create(['status' => 'approved', 'notes' => 'Seeded']);
        }

        $contract = Contract::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'status' => 'active',
                'effective_from' => now()->toDateString(),
                'total' => 1000,
            ]
        );

        $invoice = Invoice::updateOrCreate(
            ['contract_id' => $contract->id],
            [
                'status' => 'draft',
                'currency' => 'USD',
                'total' => 1000,
            ]
        );

        if (!$invoice->lines()->exists()) {
            InvoiceLine::create([
                'invoice_id' => $invoice->id,
                'description' => 'Docking Fee',
                'qty' => 1,
                'unit_price' => 1000,
                'tax_rate' => 0,
                'amount' => 1000,
            ]);
        }

        // Sample activities - omitted for brevity
    }
}
