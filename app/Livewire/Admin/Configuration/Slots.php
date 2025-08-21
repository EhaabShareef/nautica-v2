<?php

namespace App\Livewire\Admin\Configuration;

use App\Models\Slot;
use App\Models\Zone;
use App\Models\Block;
use App\Models\Property;
use Livewire\Component;
use Livewire\WithPagination;

class Slots extends Component
{
    use WithPagination;

    public $search = '';
    public $showInactive = false;
    public $property_id = '';
    public $block_id = '';
    public $zone_id = '';
    public $perPage = 10;

    protected $listeners = [
        'slot:saved' => '$refresh',
        'slot:deleted' => '$refresh',
    ];

    public function create()
    {
        $this->dispatch('slot:create');
    }

    public function edit($slotId)
    {
        $this->dispatch('slot:edit', $slotId);
    }

    public function delete($slotId)
    {
        $this->dispatch('slot:delete', $slotId);
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

    public function updatedPropertyId()
    {
        $this->block_id = '';
        $this->zone_id = '';
        $this->resetPage();
    }

    public function updatedBlockId()
    {
        $this->zone_id = '';
        $this->resetPage();
    }

    public function updatedZoneId()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Slot::with('zone.block.property');

        if ($this->search) {
            $escapedSearch = addcslashes($this->search, '%_\\');
            $query->where(function ($q) use ($escapedSearch) {
                $q->where('code', 'like', '%' . $escapedSearch . '%')
                  ->orWhere('location', 'like', '%' . $escapedSearch . '%');
            });
        }

        if ($this->property_id) {
            $query->whereHas('zone.block', function ($q) {
                $q->where('property_id', $this->property_id);
            });
        }

        if ($this->block_id) {
            $query->whereHas('zone', function ($q) {
                $q->where('block_id', $this->block_id);
            });
        }

        if ($this->zone_id) {
            $query->where('zone_id', $this->zone_id);
        }

        if ($this->showInactive) {
            $query->where('is_active', false);
        } else {
            $query->where('is_active', true);
        }

        $slots = $query->orderBy('code')->paginate($this->perPage);

        $properties = Property::where('is_active', true)->get();
        $blocks = $this->property_id ? Block::where('property_id', $this->property_id)->where('is_active', true)->get() : collect();
        $zones = $this->block_id ? Zone::where('block_id', $this->block_id)->where('is_active', true)->get() : collect();

        return view('livewire.admin.configuration.slots', [
            'slots' => $slots,
            'properties' => $properties,
            'blocks' => $blocks,
            'zones' => $zones,
        ]);
    }
}
