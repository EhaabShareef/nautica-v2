<?php

namespace App\Livewire\Admin\Management\Vessels;

use App\Models\Vessel;
use Livewire\Attributes\On;
use Livewire\Component;

class VesselDelete extends Component
{
    public $vessel = null;
    public $showModal = false;

    #[On('openVesselDelete')]
    public function openModal($vesselId)
    {
        $this->vessel = Vessel::with(['owner', 'renter', 'bookings'])
            ->findOrFail($vesselId);
            
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->vessel = null;
    }

    public function deleteVessel()
    {
        if (!$this->vessel) {
            return;
        }

        // Re-fetch vessel to avoid TOCTOU (Time of Check, Time of Use) issues
        $vessel = Vessel::with(['bookings'])->find($this->vessel->id);

        if (!$vessel) {
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'Vessel not found or has been deleted by another user.'
            ]);
            $this->closeModal();
            return;
        }

        // Check authorization
        if (!auth()->user()->can('delete', $vessel)) {
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'You are not authorized to delete this vessel.'
            ]);
            $this->closeModal();
            return;
        }

        // Check for active bookings
        $activeBookings = $vessel->bookings()
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
            ->count();

        if ($activeBookings > 0) {
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => "Cannot delete vessel with {$activeBookings} active booking(s). Please cancel or complete the bookings first."
            ]);
            $this->closeModal();
            return;
        }

        try {
            $vesselName = $vessel->name;
            $vessel->delete();

            $this->dispatch('showToast', [
                'type' => 'success',
                'message' => "Vessel '{$vesselName}' deleted successfully."
            ]);

            $this->dispatch('$refresh')->to('admin.management.vessels.index');
            $this->closeModal();

        } catch (\Exception $e) {
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'An error occurred while deleting the vessel. Please try again.'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.management.vessels.vessel-delete');
    }
}