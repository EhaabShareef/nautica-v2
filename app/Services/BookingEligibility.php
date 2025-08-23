<?php

namespace App\Services;

use App\Models\User;

class BookingEligibility
{
    /**
     * Determine if the given user can make a booking.
     *
     * @param User $user
     */
    public function canBook(User $user): bool
    {
        if (!$user->is_active || $user->is_blacklisted) {
            return false;
        }

        // TODO: integrate payments service to check pending payments
        return true;
    }
}
