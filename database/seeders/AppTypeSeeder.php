<?php

namespace Database\Seeders;

use App\Models\AppType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AppTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'org_type' => [
                ['code' => 'owner', 'label' => 'Owner'],
                ['code' => 'operator', 'label' => 'Operator'],
                ['code' => 'agent', 'label' => 'Agent'],
            ],
            'booking_type' => [
                ['code' => 'hourly', 'label' => 'Hourly'],
                ['code' => 'daily', 'label' => 'Daily'],
                ['code' => 'monthly', 'label' => 'Monthly'],
                ['code' => 'yearly', 'label' => 'Yearly'],
            ],
            'booking_status' => [
                ['code' => 'requested', 'label' => 'Requested'],
                ['code' => 'approved', 'label' => 'Approved'],
                ['code' => 'confirmed', 'label' => 'Confirmed'],
                ['code' => 'cancelled', 'label' => 'Cancelled'],
                ['code' => 'checked_in', 'label' => 'Checked In'],
                ['code' => 'checked_out', 'label' => 'Checked Out'],
            ],
            'invoice_status' => [
                ['code' => 'draft', 'label' => 'Draft'],
                ['code' => 'issued', 'label' => 'Issued'],
                ['code' => 'paid', 'label' => 'Paid'],
                ['code' => 'void', 'label' => 'Void'],
            ],
            'payment_method' => [
                ['code' => 'cash', 'label' => 'Cash'],
                ['code' => 'bank', 'label' => 'Bank'],
                ['code' => 'gateway', 'label' => 'Gateway'],
            ],
            'payment_status' => [
                ['code' => 'pending', 'label' => 'Pending'],
                ['code' => 'succeeded', 'label' => 'Succeeded'],
                ['code' => 'failed', 'label' => 'Failed'],
                ['code' => 'refunded', 'label' => 'Refunded'],
            ],
            'service_status' => [
                ['code' => 'requested', 'label' => 'Requested'],
                ['code' => 'scheduled', 'label' => 'Scheduled'],
                ['code' => 'completed', 'label' => 'Completed'],
                ['code' => 'cancelled', 'label' => 'Cancelled'],
            ],
            'service_unit' => [
                ['code' => 'hour', 'label' => 'Hour'],
                ['code' => 'job', 'label' => 'Job'],
                ['code' => 'ton', 'label' => 'Ton'],
            ],
            'tax_rate' => [
                ['code' => 'zero', 'label' => 'Zero'],
                ['code' => 'standard', 'label' => 'Standard'],
            ],
            'size_unit' => [
                ['code' => 'sqm', 'label' => 'Square Meter'],
                ['code' => 'sqft', 'label' => 'Square Foot'],
                ['code' => 'm', 'label' => 'Meter'],
            ],
            'user_role' => [
                ['code' => 'admin', 'label' => 'Admin'],
                ['code' => 'client', 'label' => 'Client'],
                ['code' => 'superadmin', 'label' => 'Super Admin'],
                ['code' => 'pending', 'label' => 'Pending'],
                ['code' => 'rejected', 'label' => 'Rejected'],
            ],
        ];

        foreach ($types as $group => $items) {
            foreach ($items as $item) {
                AppType::updateOrCreate(
                    ['group' => $group, 'code' => $item['code']],
                    [
                        'label' => $item['label'],
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
