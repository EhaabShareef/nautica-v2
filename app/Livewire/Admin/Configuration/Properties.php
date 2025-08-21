<?php

namespace App\Livewire\Admin\Configuration;

use App\Models\Property;
use Livewire\Component;
use Livewire\WithPagination;

class Properties extends Component
{
    use WithPagination;

    public $search = '';
    public $showInactive = false;

    protected $listeners = [
        'property:saved' => '$refresh',
        'property:deleted' => '$refresh'
    ];

    public function create()
    {
        $this->dispatch('property:create');
    }

    public function edit($propertyId)
    {
        $this->dispatch('property:edit', $propertyId);
    }

    public function delete($propertyId)
    {
        $this->dispatch('property:delete', $propertyId);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedShowInactive()
    {
        $this->resetPage();
    }

    public function toggleInactiveFilter()
    {
        $this->showInactive = !$this->showInactive;
        $this->resetPage();
    }

    public function render()
    {
        $query = Property::with(['blocks', 'blocks.zones', 'blocks.zones.slots']);

        // Apply search filter
        if ($this->search) {
            $escapedSearch = addcslashes($this->search, '%_\\');
            $query->where(function ($q) use ($escapedSearch) {
                $q->where('name', 'like', '%' . $escapedSearch . '%')
                  ->orWhere('code', 'like', '%' . $escapedSearch . '%');
            });
        }

        // Apply active/inactive filter
        if ($this->showInactive) {
            $query->where('is_active', false);
        } else {
            $query->where('is_active', true);
        }

        // Add counts for zones and slots
        $query->withCount([
            'blocks',
            'blocks as zones_count' => function ($query) {
                $query->join('zones', 'blocks.id', '=', 'zones.block_id');
            },
            'blocks as slots_count' => function ($query) {
                $query->join('zones', 'blocks.id', '=', 'zones.block_id')
                      ->join('slots', 'zones.id', '=', 'slots.zone_id');
            }
        ]);

        return view('livewire.admin.configuration.properties', [
            'properties' => $query->paginate(10)
        ]);
    }
}