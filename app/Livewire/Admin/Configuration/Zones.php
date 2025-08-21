<?php

namespace App\Livewire\Admin\Configuration;

use App\Models\Zone;
use Livewire\Component;
use Livewire\WithPagination;

class Zones extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showInactive = false;
    public int $perPage = 10;

    protected $listeners = [
        'zone:saved' => '$refresh',
        'zone:deleted' => '$refresh',
    ];

    public function create(): void
    {
        $this->dispatch('zone:create');
    }

    public function edit(string $zoneId): void
    {
        $this->dispatch('zone:edit', $zoneId);
    }

    public function delete(string $zoneId): void
    {
        $this->dispatch('zone:delete', $zoneId);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedShowInactive(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function toggleInactiveFilter(): void
    {
        $this->showInactive = !$this->showInactive;
        $this->resetPage();
    }

    public function render()
    {
        $query = Zone::query()
            ->with('block.property:id,name,code')
            ->withCount('slots');

        // Apply search filter with debounced input
        if ($this->search) {
            $escapedSearch = addcslashes($this->search, '%_\\');
            $query->where(function ($q) use ($escapedSearch) {
                $q->where('name', 'like', '%' . $escapedSearch . '%')
                  ->orWhere('code', 'like', '%' . $escapedSearch . '%')
                  ->orWhere('location', 'like', '%' . $escapedSearch . '%');
            });
        }

        // Apply active/inactive filter
        $query->where('is_active', $this->showInactive ? false : true);

        return view('livewire.admin.configuration.zones', [
            'zones' => $query->paginate($this->perPage),
            'perPageOptions' => [5, 10, 25, 50, 100]
        ]);
    }
}

