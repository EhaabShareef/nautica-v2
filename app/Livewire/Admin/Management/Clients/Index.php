<?php

namespace App\Livewire\Admin\Management\Clients;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    // Search and filter properties
    public $search = '';
    public $statusFilter = 'all'; // all, active, inactive, blacklisted
    public $perPage = 10;
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    // Component state
    public $showFilters = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
        'perPage' => ['except' => 10],
    ];

    public function mount()
    {
        $this->dispatch('clientsPageLoaded');
    }

    #[On('filtersUpdated')]
    public function updateFilters($filters)
    {
        $this->search = $filters['search'] ?? '';
        $this->statusFilter = $filters['statusFilter'] ?? 'all';
        $this->perPage = $filters['perPage'] ?? 10;
        $this->resetPage();
    }

    #[On('client-blacklisted')]
    #[On('client-unblacklisted')]
    public function refreshAfterBlacklistChange()
    {
        // Refresh the component to show updated data
        $this->render();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
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


    public function createClient()
    {
        $this->dispatch('openClientForm');
    }

    public function editClient($clientId)
    {
        $this->dispatch('openClientForm', clientId: $clientId);
    }

    public function deleteClient($clientId)
    {
        $this->dispatch('openClientDelete', clientId: $clientId);
    }

    public function blacklistClient($clientId)
    {
        $this->dispatch('blacklist-client', clientId: $clientId);
    }

    public function unblacklistClient($clientId)
    {
        $this->dispatch('blacklist-client', clientId: $clientId);
    }

    public function getClientsProperty()
    {
        $query = User::clients()
            ->withCount('vessels')
            ->select(['id', 'name', 'email', 'phone', 'id_card', 'is_active', 'is_blacklisted', 'created_at', 'last_login_at']);

        // Apply search
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%')
                  ->orWhere('id_card', 'like', '%' . $this->search . '%');
            });
        }

        // Apply status filter
        if ($this->statusFilter === 'active') {
            $query->active()->where('is_blacklisted', false);
        } elseif ($this->statusFilter === 'inactive') {
            $query->where('is_active', false)->where('is_blacklisted', false);
        } elseif ($this->statusFilter === 'blacklisted') {
            $query->where('is_blacklisted', true);
        }

        // Apply sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.admin.management.clients.index', [
            'clients' => $this->clients,
        ])->layout('layouts.admin');
    }
}