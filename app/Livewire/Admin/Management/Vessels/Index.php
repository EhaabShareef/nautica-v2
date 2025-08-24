<?php

namespace App\Livewire\Admin\Management\Vessels;

use App\Models\Vessel;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    // Search and filter properties
    public $search = '';
    public $ownerFilter = 'all';
    public $renterFilter = 'all';
    public $statusFilter = 'all'; // all, active, inactive
    public $typeFilter = 'all';
    public $perPage = 10;
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    // Component state
    public $showFilters = false;
    
    // Quick assign renter modal
    public $showQuickAssignModal = false;
    public $quickAssignVesselId = null;
    public $quickAssignSearch = '';
    public $eligibleClients = [];
    public $showClientDropdown = false;
    
    // Deactivation confirmation
    public $showDeactivateModal = false;
    public $deactivateVesselId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'ownerFilter' => ['except' => 'all'],
        'renterFilter' => ['except' => 'all'],
        'statusFilter' => ['except' => 'all'],
        'typeFilter' => ['except' => 'all'],
        'perPage' => ['except' => 10],
    ];

    public function mount()
    {
        $this->dispatch('vesselsPageLoaded');
    }

    #[On('filtersUpdated')]
    public function updateFilters($filters)
    {
        $this->search = $filters['search'] ?? '';
        $this->ownerFilter = $filters['ownerFilter'] ?? 'all';
        $this->renterFilter = $filters['renterFilter'] ?? 'all';
        $this->statusFilter = $filters['statusFilter'] ?? 'all';
        $this->typeFilter = $filters['typeFilter'] ?? 'all';
        $this->perPage = $filters['perPage'] ?? 10;
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingOwnerFilter()
    {
        $this->resetPage();
    }

    public function updatingRenterFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function createVessel()
    {
        $this->dispatch('openVesselForm');
    }

    public function editVessel($vesselId)
    {
        $this->dispatch('openVesselForm', vesselId: $vesselId);
    }

    public function deleteVessel($vesselId)
    {
        $this->dispatch('openVesselDelete', vesselId: $vesselId);
    }

    public function assignRenter($vesselId)
    {
        $this->dispatch('openRenterAssignment', vesselId: $vesselId);
    }

    public function toggleVesselStatus($vesselId)
    {
        $vessel = Vessel::findOrFail($vesselId);
        
        if (!auth()->user()->can('toggleStatus', $vessel)) {
            $this->dispatch('showToast', [
                'type' => 'error', 
                'message' => 'You are not authorized to toggle vessel status.'
            ]);
            return;
        }
        
        $vessel->update(['is_active' => !$vessel->is_active]);
        
        $status = $vessel->is_active ? 'activated' : 'deactivated';
        $this->dispatch('showToast', [
            'type' => 'success', 
            'message' => "Vessel {$status} successfully."
        ]);
    }

    public function getVesselsProperty()
    {
        $query = Vessel::with(['owner:id,name,id_card', 'renter:id,name,id_card'])
            ->select(['id', 'name', 'registration_number', 'type', 'owner_client_id', 'renter_client_id', 'length', 'width', 'draft', 'is_active', 'created_at', 'updated_at']);

        // Apply search
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('registration_number', 'like', '%' . $this->search . '%')
                  ->orWhere('type', 'like', '%' . $this->search . '%')
                  ->orWhereHas('owner', function ($q) {
                      $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('id_card', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('renter', function ($q) {
                      $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('id_card', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Apply owner filter
        if ($this->ownerFilter !== 'all') {
            $query->where('owner_client_id', $this->ownerFilter);
        }

        // Apply renter filter
        if ($this->renterFilter === 'none') {
            $query->whereNull('renter_client_id');
        } elseif ($this->renterFilter !== 'all') {
            $query->where('renter_client_id', $this->renterFilter);
        }

        // Apply status filter
        if ($this->statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        // Apply type filter
        if ($this->typeFilter !== 'all') {
            $query->where('type', $this->typeFilter);
        }

        // Apply sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query->paginate($this->perPage);
    }

    public function getOwnersProperty()
    {
        return User::clients()
            ->select(['id', 'name'])
            ->whereHas('vessels')
            ->distinct()
            ->orderBy('name')
            ->get();
    }

    public function getRentersProperty()
    {
        return User::clients()
            ->select(['id', 'name'])
            ->whereHas('rentedVessels')
            ->distinct()
            ->orderBy('name')
            ->get();
    }

    public function getVesselTypesProperty()
    {
        return Vessel::select('type')
            ->whereNotNull('type')
            ->distinct()
            ->orderBy('type')
            ->pluck('type');
    }

    public function openQuickAssign($vesselId)
    {
        $this->quickAssignVesselId = $vesselId;
        $this->showQuickAssignModal = true;
        $this->quickAssignSearch = '';
        $this->loadEligibleClients();
    }

    public function closeQuickAssign()
    {
        $this->showQuickAssignModal = false;
        $this->quickAssignVesselId = null;
        $this->quickAssignSearch = '';
        $this->eligibleClients = [];
        $this->showClientDropdown = false;
    }

    public function updatedQuickAssignSearch()
    {
        $this->showClientDropdown = !empty($this->quickAssignSearch);
        if (!empty($this->quickAssignSearch)) {
            $this->loadEligibleClients();
        }
    }

    public function assignOwnerAsRenter()
    {
        $vessel = Vessel::with('owner')->find($this->quickAssignVesselId);
        if ($vessel && $vessel->owner) {
            $this->assignClientAsRenter($vessel->owner->id);
        }
    }

    public function assignClientAsRenter($clientId)
    {
        $vessel = Vessel::find($this->quickAssignVesselId);
        if ($vessel) {
            $vessel->update(['renter_client_id' => $clientId]);
            $this->closeQuickAssign();
            $this->dispatch('vessel-updated');
            session()->flash('message', 'Renter assigned successfully.');
        }
    }

    public function loadEligibleClients()
    {
        $query = User::clients()
            ->active()
            ->notBlacklisted()
            ->select(['id', 'name', 'id_card'])
            ->orderBy('name');

        if (!empty($this->quickAssignSearch)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->quickAssignSearch . '%')
                  ->orWhere('id_card', 'like', '%' . $this->quickAssignSearch . '%');
            });
        }

        $this->eligibleClients = $query->limit(20)->get()->map(function ($client) {
            return [
                'id' => $client->id,
                'display_name' => $client->display_name,
                'id_card' => $client->id_card,
            ];
        })->toArray();
    }

    public function confirmDeactivate($vesselId)
    {
        $this->deactivateVesselId = $vesselId;
        $this->showDeactivateModal = true;
    }

    public function cancelDeactivate()
    {
        $this->showDeactivateModal = false;
        $this->deactivateVesselId = null;
    }

    public function deactivateVessel()
    {
        $vessel = Vessel::find($this->deactivateVesselId);
        if ($vessel) {
            $vessel->update(['is_active' => false]);
            $this->cancelDeactivate();
            session()->flash('message', 'Vessel deactivated successfully.');
        }
    }

    public function render()
    {
        return view('livewire.admin.management.vessels.index', [
            'vessels' => $this->vessels,
            'owners' => $this->owners,
            'renters' => $this->renters,
            'vesselTypes' => $this->vesselTypes,
        ])->layout('layouts.admin');
    }
}