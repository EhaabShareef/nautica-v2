<?php

namespace App\Livewire\Admin\Management\Vessels;

use App\Models\Client;
use App\Models\Vessel;
use Livewire\Component;
use Livewire\Attributes\On;

class VesselAssignRenter extends Component
{
    public $showModal = false;
    public $vessel = null;
    public $quickAssignSearch = '';
    public $showClientDropdown = false;
    public $eligibleClients = [];

    protected $listeners = ['vessel-assign-renter:show' => 'show'];

    public function mount()
    {
        $this->eligibleClients = [];
    }

    #[On('vessel-assign-renter:show')]
    public function show($vesselId)
    {
        $this->vessel = Vessel::with(['owner', 'renter'])->find($vesselId);
        
        if ($this->vessel) {
            $this->showModal = true;
            $this->quickAssignSearch = '';
            $this->eligibleClients = [];
            $this->showClientDropdown = false;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->vessel = null;
        $this->quickAssignSearch = '';
        $this->eligibleClients = [];
        $this->showClientDropdown = false;
        $this->dispatch('vessel-assign-renter:closed');
    }

    public function updatedQuickAssignSearch()
    {
        if (strlen($this->quickAssignSearch) >= 2) {
            $this->searchClients();
        } else {
            $this->eligibleClients = [];
            $this->showClientDropdown = false;
        }
    }

    private function searchClients()
    {
        $clients = Client::where('is_active', true)
            ->where('is_blacklisted', false)
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->quickAssignSearch . '%')
                    ->orWhere('id_card', 'like', '%' . $this->quickAssignSearch . '%');
            })
            ->when($this->vessel, function ($query) {
                // Exclude current renter if exists
                if ($this->vessel->renter_client_id) {
                    $query->where('id', '!=', $this->vessel->renter_client_id);
                }
            })
            ->limit(10)
            ->get(['id', 'name', 'id_card']);

        $this->eligibleClients = $clients->map(function ($client) {
            return [
                'id' => $client->id,
                'display_name' => $client->name,
                'id_card' => $client->id_card,
            ];
        })->toArray();

        $this->showClientDropdown = count($this->eligibleClients) > 0;
    }

    public function assignOwnerAsRenter()
    {
        if (!$this->vessel || !$this->vessel->owner) {
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'Unable to assign owner as renter.'
            ]);
            return;
        }

        try {
            $this->vessel->update([
                'renter_client_id' => $this->vessel->owner_client_id
            ]);

            $this->dispatch('showToast', [
                'type' => 'success',
                'message' => 'Owner successfully assigned as renter.'
            ]);

            $this->dispatch('vessel-assigned', [
                'message' => 'Renter assigned successfully.'
            ]);

            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'Failed to assign renter: ' . $e->getMessage()
            ]);
        }
    }

    public function assignClientAsRenter($clientId)
    {
        if (!$this->vessel) {
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'Vessel not found.'
            ]);
            return;
        }

        try {
            $client = Client::find($clientId);
            if (!$client) {
                $this->dispatch('showToast', [
                    'type' => 'error',
                    'message' => 'Client not found.'
                ]);
                return;
            }

            $this->vessel->update([
                'renter_client_id' => $clientId
            ]);

            $this->dispatch('showToast', [
                'type' => 'success',
                'message' => 'Renter assigned successfully.'
            ]);

            $this->dispatch('vessel-assigned', [
                'message' => 'Renter assigned successfully.'
            ]);

            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'Failed to assign renter: ' . $e->getMessage()
            ]);
        }
    }

    public function removeRenter()
    {
        if (!$this->vessel) {
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'Vessel not found.'
            ]);
            return;
        }

        try {
            $this->vessel->update([
                'renter_client_id' => null
            ]);

            $this->dispatch('showToast', [
                'type' => 'success',
                'message' => 'Renter removed successfully.'
            ]);

            $this->dispatch('vessel-assigned', [
                'message' => 'Renter removed successfully.'
            ]);

            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'Failed to remove renter: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.management.vessels.vessel-assign-renter');
    }
}