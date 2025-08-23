<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vessel;

class VesselPolicy
{
    /**
     * Determine whether the user can view any vessels.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'agent']) || $user->hasRole('client');
    }

    /**
     * Determine whether the user can view the vessel.
     */
    public function view(User $user, Vessel $vessel): bool
    {
        // Admin and agents can view all vessels
        if ($user->hasRole(['admin', 'agent'])) {
            return true;
        }

        // Clients can view their own vessels (as owner or renter)
        return $vessel->owner_client_id === $user->id || $vessel->renter_client_id === $user->id;
    }

    /**
     * Determine whether the user can create vessels.
     */
    public function create(User $user): bool
    {
        // Admin and agents can always create
        if ($user->hasRole(['admin', 'agent'])) {
            return true;
        }

        // Clients can create if they are active and not blacklisted
        return $user->hasRole('client') && $user->is_active && !$user->is_blacklisted;
    }

    /**
     * Determine whether the user can update the vessel.
     */
    public function update(User $user, Vessel $vessel): bool
    {
        // Admin and agents can update any vessel
        if ($user->hasRole(['admin', 'agent'])) {
            return true;
        }

        // Vessel owners can update their vessels
        return $vessel->owner_client_id === $user->id;
    }

    /**
     * Determine whether the user can delete the vessel.
     */
    public function delete(User $user, Vessel $vessel): bool
    {
        // Only admins can delete vessels
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can assign or change renters.
     */
    public function assignRenter(User $user, Vessel $vessel): bool
    {
        // Admin and agents can assign renters to any vessel
        if ($user->hasRole(['admin', 'agent'])) {
            return true;
        }

        // Vessel owners can assign renters to their vessels
        return $vessel->owner_client_id === $user->id;
    }

    /**
     * Determine whether the user can change vessel ownership.
     */
    public function changeOwner(User $user, Vessel $vessel): bool
    {
        // Only admins can change ownership
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can activate/deactivate vessels.
     */
    public function toggleStatus(User $user, Vessel $vessel): bool
    {
        // Admin and agents can toggle status
        if ($user->hasRole(['admin', 'agent'])) {
            return true;
        }

        // Vessel owners can deactivate their own vessels
        return $vessel->owner_client_id === $user->id;
    }
}
