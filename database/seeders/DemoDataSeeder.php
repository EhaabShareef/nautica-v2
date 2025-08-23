<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingLog;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Property;
use App\Models\Block;
use App\Models\Zone;
use App\Models\Slot;
use App\Models\User;
use App\Models\Vessel;
use App\Models\Activity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create users with proper user_type and additional fields
        $admin = User::updateOrCreate(
            ['email' => 'admin@nautica.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'user_type' => 'admin',
                'is_active' => true,
                'is_blacklisted' => false,
            ]
        );
        $admin->assignRole('admin');

        $client = User::updateOrCreate(
            ['email' => 'client@nautica.com'],
            [
                'name' => 'Demo Client',
                'password' => Hash::make('password'),
                'user_type' => 'client',
                'phone' => '+1-555-0123',
                'id_card' => 'ID123456789',
                'address' => '456 Client Street, Demo City, DC 12345',
                'is_active' => true,
                'is_blacklisted' => false,
            ]
        );
        $client->assignRole('client');

        // Property hierarchy
        $property = Property::updateOrCreate(
            ['code' => 'DEMO-MAR'],
            [
                'name' => 'Demo Marina',
                'timezone' => 'UTC',
                'currency' => 'USD',
                'address' => '123 Harbor Drive, Demo City',
                'is_active' => true
            ]
        );

        $block = Block::updateOrCreate(
            ['property_id' => $property->id, 'code' => 'BLOCK-A'],
            [
                'name' => 'Block A',
                'location' => 'North Section',
                'is_active' => true
            ]
        );

        $zone = Zone::updateOrCreate(
            ['block_id' => $block->id, 'code' => 'ZONE-1'],
            [
                'name' => 'Zone 1',
                'location' => 'Waterfront',
                'notes' => 'Premium waterfront zone',
                'is_active' => true
            ]
        );

        $slot = Slot::updateOrCreate(
            ['zone_id' => $zone->id, 'code' => 'SLOT-001'],
            [
                'name' => 'Slot 001',
                'location' => 'Dock 1',
                'length' => 30.00,
                'width' => 12.00,
                'depth' => 8.00,
                'amenities' => ['power', 'water', 'wifi'],
                'base_rate' => 150.00,
                'is_active' => true
            ]
        );

        // Create additional slots for more demo data
        $slot2 = Slot::updateOrCreate(
            ['zone_id' => $zone->id, 'code' => 'SLOT-002'],
            [
                'name' => 'Slot 002',
                'location' => 'Dock 2',
                'length' => 40.00,
                'width' => 15.00,
                'depth' => 10.00,
                'amenities' => ['power', 'water', 'wifi', 'pump_out'],
                'base_rate' => 200.00,
                'is_active' => true
            ]
        );

        $slot3 = Slot::updateOrCreate(
            ['zone_id' => $zone->id, 'code' => 'SLOT-003'],
            [
                'name' => 'Slot 003',
                'location' => 'Dock 3',
                'length' => 25.00,
                'width' => 10.00,
                'depth' => 6.00,
                'amenities' => ['power', 'water'],
                'base_rate' => 120.00,
                'is_active' => true
            ]
        );

        // Create additional clients for more demo data
        $client2 = User::updateOrCreate(
            ['email' => 'john.doe@nautica.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'user_type' => 'client',
                'phone' => '+1-555-0456',
                'id_card' => 'ID987654321',
                'address' => '789 Marina Blvd, Coast City, CC 54321',
                'is_active' => true,
                'is_blacklisted' => false,
            ]
        );
        $client2->assignRole('client');

        $vessel = Vessel::updateOrCreate(
            ['owner_client_id' => $client->id, 'name' => 'Sea Breeze'],
            [
                'registration_number' => 'SB-2024-001',
                'type' => 'yacht',
                'length' => 28.00,
                'width' => 10.00,
                'draft' => 6.00,
                'specifications' => [
                    'engine' => 'Twin Diesel',
                    'fuel_capacity' => '500L',
                    'year' => 2020
                ],
                'is_active' => true,
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]
        );

        // Create another vessel for client2
        $vessel2 = Vessel::updateOrCreate(
            ['owner_client_id' => $client2->id, 'name' => 'Ocean Explorer'],
            [
                'registration_number' => 'OE-2024-002',
                'type' => 'catamaran',
                'length' => 35.00,
                'width' => 15.00,
                'draft' => 4.00,
                'specifications' => [
                    'engine' => 'Outboard Motors',
                    'fuel_capacity' => '300L',
                    'year' => 2022
                ],
                'is_active' => true,
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]
        );

        $start = now()->addDay();
        $end = (clone $start)->addHours(4);

        $booking = Booking::updateOrCreate(
            [
                'user_id' => $client->id,
                'vessel_id' => $vessel->id,
                'slot_id' => $slot->id,
            ],
            [
                'booking_number' => 'BK-' . strtoupper(Str::random(8)),
                'start_date' => $start,
                'end_date' => $end,
                'status' => 'confirmed',
                'total_amount' => 600.00,
                'additional_data' => [
                    'guests' => 4,
                    'special_requests' => ['shore_power', 'water_hookup']
                ],
                'notes' => 'Demo booking for testing'
            ]
        );

        // Create booking log
        BookingLog::updateOrCreate(
            ['booking_id' => $booking->id, 'user_id' => $admin->id],
            [
                'action' => 'status_change',
                'old_status' => 'pending',
                'new_status' => 'confirmed',
                'changes' => ['status' => ['from' => 'pending', 'to' => 'confirmed']],
                'notes' => 'Booking confirmed by admin'
            ]
        );

        $contract = Contract::updateOrCreate(
            ['user_id' => $client->id, 'slot_id' => $slot->id],
            [
                'contract_number' => 'CT-' . strtoupper(Str::random(8)),
                'start_date' => now()->toDateString(),
                'end_date' => now()->addYear()->toDateString(),
                'status' => 'active',
                'monthly_rate' => 2500.00,
                'terms' => [
                    'payment_due' => 'monthly',
                    'deposit' => 5000.00,
                    'late_fee' => 50.00
                ],
                'notes' => 'Annual slip rental contract'
            ]
        );

        $invoice = Invoice::updateOrCreate(
            ['user_id' => $client->id, 'invoiceable_type' => 'App\\Models\\Booking', 'invoiceable_id' => $booking->id],
            [
                'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                'issue_date' => now()->toDateString(),
                'due_date' => now()->addDays(30)->toDateString(),
                'status' => 'pending',
                'subtotal' => 600.00,
                'tax_amount' => 60.00,
                'total_amount' => 660.00,
                'billing_details' => [
                    'name' => $client->name,
                    'email' => $client->email,
                    'address' => '456 Client Street, Demo City'
                ],
                'notes' => 'Docking fees for Sea Breeze'
            ]
        );

        InvoiceLine::updateOrCreate(
            ['invoice_id' => $invoice->id, 'description' => 'Docking Fee - 4 hours'],
            [
                'quantity' => 1,
                'unit_price' => 600.00,
                'total' => 600.00,
                'metadata' => [
                    'rate_per_hour' => 150.00,
                    'hours' => 4
                ]
            ]
        );

        // Create sample activity
        Activity::updateOrCreate(
            ['user_id' => $client->id, 'type' => 'booking_created'],
            [
                'description' => 'Created new booking',
                'subject_type' => 'App\\Models\\Booking',
                'subject_id' => $booking->id,
                'properties' => [
                    'booking_number' => $booking->booking_number,
                    'slot' => $slot->name,
                    'duration' => '4 hours'
                ],
                'occurred_at' => now()
            ]
        );
    }
}
