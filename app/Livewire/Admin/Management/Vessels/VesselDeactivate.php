<?php

namespace App\Livewire\Admin\Management\Vessels;

use App\Models\Vessel;
use Livewire\Attributes\On;
use Livewire\Component;

class VesselDeactivate extends Component
{
    public $vessel = null;
    public $showModal = false;

    #[On('openVesselDeactivate')]
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

    public function deactivateVessel()
    {
        if (!$this->vessel) {
            return;
        }

        // Re-fetch vessel to avoid TOCTOU (Time of Check, Time of Use) issues
        $vessel = Vessel::find($this->vessel->id);

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
                'message' => 'You are not authorized to deactivate this vessel.'
            ]);
            $this->closeModal();
            return;
        }

        try {
            // Simply update the is_active field to false
            $vessel->update(['is_active' => false]);

            $this->dispatch('showToast', [
                'type' => 'success',
                'message' => 'Vessel has been deactivated successfully.'
            ]);

            $this->dispatch('vesselStatusChanged');

            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'Failed to deactivate vessel: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.management.vessels.vessel-deactivate');
    }
}