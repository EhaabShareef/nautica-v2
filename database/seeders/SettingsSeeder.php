<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'booking.hold_minutes',
                'group' => 'booking',
                'value' => 120,
                'label' => 'Booking Hold Duration',
                'description' => 'Minutes to hold a booking before expiring',
                'is_protected' => false,
                'is_active' => true,
            ],
            [
                'key' => 'billing.cycle_day',
                'group' => 'billing',
                'value' => 1,
                'label' => 'Billing Cycle Day',
                'description' => 'Day of month for billing cycle',
                'is_protected' => false,
                'is_active' => true,
            ],
            [
                'key' => 'invoice.number_prefix',
                'group' => 'invoice',
                'value' => 'INV-',
                'label' => 'Invoice Number Prefix',
                'description' => 'Prefix for invoice numbers',
                'is_protected' => true,
                'is_active' => true,
            ],
            [
                'key' => 'app.name',
                'group' => 'general',
                'value' => 'Nautica Marina',
                'label' => 'Application Name',
                'description' => 'Name displayed throughout the application',
                'is_protected' => true,
                'is_active' => true,
            ],
            [
                'key' => 'app.timezone',
                'group' => 'general',
                'value' => 'UTC',
                'label' => 'Default Timezone',
                'description' => 'Default timezone for the application',
                'is_protected' => false,
                'is_active' => true,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
