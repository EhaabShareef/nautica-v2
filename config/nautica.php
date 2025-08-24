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

    'vessels' => [
        'search_limit' => env('VESSEL_SEARCH_LIMIT', 20),
        'allow_owner_renter_same' => env('VESSEL_ALLOW_OWNER_RENTER_SAME', false),
    ],
];

