<?php

namespace App\Livewire\Admin\Management\Vessels;

use App\Models\Vessel;
use Livewire\Component;
use Livewire\Attributes\On;

class VesselStatusToggle extends Component
{
    public $showModal = false;
    public $vessel = null;
    public $action = null; // 'activate' or 'deactivate'

    protected $listeners = ['vessel-status-toggle:show' => 'show'];

    #[On('vessel-status-toggle:show')]
    public function show($vesselId, $action = 'deactivate')
    {
        $this->vessel = Vessel::find($vesselId);
        $this->action = $action;
        
        if ($this->vessel) {
            $this->showModal = true;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->vessel = null;
        $this->action = null;
        $this->dispatch('vessel-status-toggle:closed');
    }

    public function toggleVesselStatus()
    {
        if (!$this->vessel) {
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'Vessel not found.'
            ]);
            return;
        }

        try {
            $newStatus = !$this->vessel->is_active;
            $this->vessel->update(['is_active' => $newStatus]);

            $statusText = $newStatus ? 'activated' : 'deactivated';
            
            $this->dispatch('showToast', [
                'type' => 'success',
                'message' => "Vessel has been {$statusText} successfully."
            ]);

            $this->dispatch('vessel-status-changed', [
                'message' => "Vessel {$statusText} successfully.",
                'status' => $newStatus
            ]);

            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'Failed to update vessel status: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.management.vessels.vessel-status-toggle');
    }
}