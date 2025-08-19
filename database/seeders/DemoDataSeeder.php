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
            ['name' => 'Demo Marina', 'address' => 'Dock 1']
        );
        
        $resource = Resource::updateOrCreate(
            ['property_id' => $property->id, 'name' => 'Slip A1'],
            ['capacity' => 1]
        );
        
        $slotStart = now()->addDay();
        $slotEnd = (clone $slotStart)->addHours(2);
        $slot = Slot::updateOrCreate(
            ['resource_id' => $resource->id, 'start_at' => $slotStart],
            ['end_at' => $slotEnd]
        );

        $vessel = Vessel::updateOrCreate(
            ['client_id' => $client->id, 'name' => 'Sea Breeze'],
            []
        );

        $booking = Booking::updateOrCreate(
            [
                'client_id' => $client->id,
                'vessel_id' => $vessel->id,
                'slot_id' => $slot->id,
            ],
            [
                'property_id' => $property->id,
                'resource_id' => $resource->id,
                'start_at' => $slotStart,
                'end_at' => $slotEnd,
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

        // Create sample activities for the dashboard
        $activities = [
            ['action' => 'user_registered', 'message' => 'New user registered: ' . $client->name, 'user_id' => null, 'subject' => $client],
            ['action' => 'booking_created', 'message' => 'New booking request for ' . $vessel->name, 'user_id' => $client->id, 'subject' => $booking],
            ['action' => 'booking_approved', 'message' => 'Booking approved for ' . $property->name . ' - ' . $resource->name, 'user_id' => $admin->id, 'subject' => $booking],
            ['action' => 'contract_created', 'message' => 'Contract generated for booking #' . $booking->id, 'user_id' => $admin->id, 'subject' => $contract],
            ['action' => 'invoice_generated', 'message' => 'Invoice created: $' . number_format($invoice->total, 2), 'user_id' => null, 'subject' => $invoice],
        ];

        foreach ($activities as $index => $activityData) {
            $subject = $activityData['subject'] ?? null;
            $attributes = [
                'action'       => $activityData['action'],
                'subject_type' => $subject?->getMorphClass(),
                'subject_id'   => $subject?->getKey(),
            ];
            $activity = \App\Models\Activity::firstOrCreate(
                $attributes,
                [
                    // Set only on first creation to keep seeds stable across re-runs
                    'created_at' => now()->subMinutes($index * 5),
                ]
            );
            // Keep message/user_id current without touching created_at on re-seed
            $activity->fill([
                'message' => $activityData['message'],
                'user_id' => $activityData['user_id'],
            ]);
            $activity->save();
        }
    }
}
