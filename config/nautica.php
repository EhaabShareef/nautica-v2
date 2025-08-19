<?php

return [
    'settings' => [
        'booking' => [
            'hold_minutes' => env('BOOKING_HOLD_MINUTES', 120),
            'require_approval' => env('BOOKING_REQUIRE_APPROVAL', true),
        ],
    ],

    'schedule' => [
        'views' => [
            'daily' => 'Daily',
            'weekly' => 'Weekly',
        ],
    ],

    'reports' => [
        'enabled' => [
            'bookings' => true,
            'revenue' => true,
            'utilization' => true,
        ],
    ],
];

