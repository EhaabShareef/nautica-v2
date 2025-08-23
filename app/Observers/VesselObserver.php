<?php

namespace App\Observers;

use App\Models\Vessel;

class VesselObserver
{
    /**
     * Handle the Vessel "creating" event.
     */
    public function creating(Vessel $vessel): void
    {
        // Set created_by to the current authenticated user ID when creating
        if (auth()->check()) {
            $vessel->created_by = auth()->id();
        }
    }

    /**
     * Handle the Vessel "updating" event.
     */
    public function updating(Vessel $vessel): void
    {
        // Set updated_by to the current authenticated user ID when updating
        if (auth()->check()) {
            $vessel->updated_by = auth()->id();
        }
    }
}