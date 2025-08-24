<?php

namespace App\Livewire\Admin\Management\Vessels;

use App\Models\Vessel;
use Livewire\Attributes\On;
use Livewire\Component;

class VesselReactivate extends Component
{
    public $vessel = null;
    public $showModal = false;

    #[On('openVesselReactivate')]
    public function openModal($vesselId)
    {
        $this->vessel = Vessel::with(['owner', 'renter'])
            ->findOrFail($vesselId);
            
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->vessel = null;
    }

    public function reactivateVessel()
    {
        if (!$this->vessel) {
            return;
        }

        // Re-fetch vessel to avoid TOCTOU (Time of Check, Time of Use) issues
        $vessel = Vessel::with('owner')->find($this->vessel->id);

        if (!$vessel) {
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'Vessel not found or has been modified by another user.'
            ]);
            $this->closeModal();
            return;
        }

        // Check authorization
        if (!auth()->user()->can('toggleStatus', $vessel)) {
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'You are not authorized to reactivate this vessel.'
            ]);
            $this->closeModal();
            return;
        }

        if (!$vessel->owner || !$vessel->owner->is_active) {
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'Owner is inactive. Activate owner to reactivate vessel.'
            ]);
            $this->closeModal();
            return;
        }

        try {
            // Simply update the is_active field to true
            $vessel->update(['is_active' => true]);

            $this->dispatch('showToast', [
                'type' => 'success',
                'message' => 'Vessel has been reactivated successfully.'
            ]);

            $this->dispatch('vesselStatusChanged');

            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'Failed to reactivate vessel: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.management.vessels.vessel-reactivate');
    }
}