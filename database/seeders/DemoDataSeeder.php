<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Property;
use App\Models\Resource;
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
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => Hash::make('password')]
        );
        $admin->assignRole('admin');

        $client = User::firstOrCreate(
            ['email' => 'client@example.com'],
            ['name' => 'Client', 'password' => Hash::make('password')]
        );
        $client->assignRole('client');

        // Property hierarchy
        $property = Property::create(['name' => 'Demo Marina', 'code' => 'MAR1', 'address' => 'Dock 1']);
        $resource = Resource::create(['property_id' => $property->id, 'name' => 'Slip A1', 'capacity' => 1]);
        $slotStart = now()->addDay();
        $slotEnd = (clone $slotStart)->addHours(2);
        $slot = Slot::create([
            'resource_id' => $resource->id,
            'start_at' => $slotStart,
            'end_at' => $slotEnd,
        ]);

        $vessel = Vessel::create(['client_id' => $client->id, 'name' => 'Sea Breeze']);

        $booking = Booking::create([
            'client_id' => $client->id,
            'vessel_id' => $vessel->id,
            'property_id' => $property->id,
            'resource_id' => $resource->id,
            'slot_id' => $slot->id,
            'start_at' => $slotStart,
            'end_at' => $slotEnd,
            'status' => 'approved',
            'type' => 'standard',
            'priority' => 'normal',
        ]);
        $booking->logs()->create(['status' => 'approved', 'notes' => 'Seeded']);

        $contract = Contract::create([
            'booking_id' => $booking->id,
            'status' => 'active',
            'effective_from' => now()->toDateString(),
            'total' => 1000,
        ]);

        $invoice = Invoice::create([
            'contract_id' => $contract->id,
            'status' => 'draft',
            'currency' => 'USD',
            'total' => 1000,
        ]);

        InvoiceLine::create([
            'invoice_id' => $invoice->id,
            'description' => 'Docking Fee',
            'qty' => 1,
            'unit_price' => 1000,
            'tax_rate' => 0,
            'amount' => 1000,
        ]);
    }
}
