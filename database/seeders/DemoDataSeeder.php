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
        // Create users
        $admin = User::updateOrCreate(
            ['email' => 'admin@nautica.com'],
            ['name' => 'Admin User', 'password' => Hash::make('password')]
        );
        $admin->assignRole('admin');

        $client = User::updateOrCreate(
            ['email' => 'client@nautica.com'],
            ['name' => 'Demo Client', 'password' => Hash::make('password')]
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
                'length' => 30.00,
                'width' => 12.00,
                'depth' => 8.00,
                'amenities' => ['power', 'water', 'wifi'],
                'base_rate' => 150.00,
                'is_active' => true
            ]
        );

        $vessel = Vessel::updateOrCreate(
            ['user_id' => $client->id, 'name' => 'Sea Breeze'],
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
                'is_active' => true
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
