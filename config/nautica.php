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

    /**
     * Vessel Management Configuration
     * 
     * search_limit: Maximum number of clients returned in search results (min: 1, recommended: 10-50)
     * allow_owner_renter_same: Whether a vessel owner can also be the renter of the same vessel
     */
    'vessels' => [
        'search_limit' => (int) env('VESSEL_SEARCH_LIMIT', 20),
        'allow_owner_renter_same' => (bool) env('VESSEL_ALLOW_OWNER_RENTER_SAME', true),
    ],
];

