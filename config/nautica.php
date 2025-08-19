<?php

return [
    'settings' => [
        'booking' => [
            'hold_minutes' => env('BOOKING_HOLD_MINUTES', 120),
            'require_approval' => env('BOOKING_REQUIRE_APPROVAL', true),
        ],
    ],

    'dictionaries' => [
        'booking_statuses' => [
            'requested' => 'Requested',
            'on_hold' => 'On Hold',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'cancelled' => 'Cancelled',
        ],
        'booking_types' => [
            'standard' => 'Standard',
            'emergency' => 'Emergency',
        ],
        'priority_levels' => [
            'normal' => 'Normal',
            'high' => 'High',
        ],
        'contract_statuses' => [
            'draft' => 'Draft',
            'active' => 'Active',
            'terminated' => 'Terminated',
        ],
        'invoice_statuses' => [
            'draft' => 'Draft',
            'sent' => 'Sent',
            'paid' => 'Paid',
            'void' => 'Void',
        ],
        'payment_methods' => [
            'bank_transfer' => 'Bank Transfer',
            'card' => 'Card',
            'cash' => 'Cash',
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

