<?php

namespace App\Livewire\Admin\Management\Clients\Components;

use Livewire\Component;

class SearchBar extends Component
{
    public $search = '';
    public $statusFilter = 'all';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
        'perPage' => ['except' => 10],
    ];

    public function updatingSearch()
    {
        $this->dispatch('filtersUpdated', [
            'search' => $this->search,
            'statusFilter' => $this->statusFilter,
            'perPage' => $this->perPage,
        ]);
    }

    public function updatingStatusFilter()
    {
        $this->dispatch('filtersUpdated', [
            'search' => $this->search,
            'statusFilter' => $this->statusFilter,
            'perPage' => $this->perPage,
        ]);
    }

    public function updatingPerPage()
    {
        $this->dispatch('filtersUpdated', [
            'search' => $this->search,
            'statusFilter' => $this->statusFilter,
            'perPage' => $this->perPage,
        ]);
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->statusFilter = 'all';
        
        $this->dispatch('filtersUpdated', [
            'search' => $this->search,
            'statusFilter' => $this->statusFilter,
            'perPage' => $this->perPage,
        ]);
    }

    public function createClient()
    {
        $this->dispatch('openClientForm');
    }

    public function render()
    {
        return view('livewire.admin.management.clients.components.search-bar');
    }
}