<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vessel;

class VesselPolicy
{
    public function view(User $user, Vessel $vessel): bool
    {
        return $user->hasRole(['admin', 'agent']) || $vessel->owner_client_id === $user->id;
    }

    public function create(User $user): bool
    {
        if ($user->hasRole(['admin', 'agent'])) {
            return true;
        }

        return $user->hasRole('client') && $user->is_active && !$user->is_blacklisted;
    }

    public function update(User $user, Vessel $vessel): bool
    {
        return $user->hasRole(['admin', 'agent']) || $vessel->owner_client_id === $user->id;
    }

    public function delete(User $user, Vessel $vessel): bool
    {
        return $user->hasRole('admin');
    }

    public function assignRenter(User $user, Vessel $vessel): bool
    {
        return $user->hasRole(['admin', 'agent']) || $vessel->owner_client_id === $user->id;
    }
}
