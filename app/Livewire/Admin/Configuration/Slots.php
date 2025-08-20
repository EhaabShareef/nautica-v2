<?php

namespace App\Livewire\Admin\Configuration;

use App\Models\Slot;
use App\Models\Zone;
use Livewire\Component;
use Livewire\WithPagination;

class Slots extends Component
{
    use WithPagination;

    public $showModal = false;
    public $editingSlot = null;
    
    public $zone_id = '';
    public $name = '';
    public $code = '';
    public $length = '';
    public $width = '';
    public $depth = '';
    public $amenities = [];
    public $base_rate = '';
    public $is_active = true;

    // Available amenities
    public $availableAmenities = [
        'power' => 'Shore Power',
        'water' => 'Fresh Water',
        'wifi' => 'WiFi',
        'fuel' => 'Fuel Dock',
        'pump_out' => 'Pump Out',
        'security' => '24/7 Security',
        'lighting' => 'LED Lighting',
        'fire_safety' => 'Fire Safety Equipment'
    ];

    protected $rules = [
        'zone_id' => 'required|exists:zones,id',
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:50',
        'length' => 'nullable|numeric|min:0|max:999.99',
        'width' => 'nullable|numeric|min:0|max:999.99',
        'depth' => 'nullable|numeric|min:0|max:999.99',
        'amenities' => 'array',
        'base_rate' => 'nullable|numeric|min:0|max:99999.99',
        'is_active' => 'boolean',
    ];

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(Slot $slot)
    {
        $this->editingSlot = $slot;
        $this->zone_id = $slot->zone_id;
        $this->name = $slot->name;
        $this->code = $slot->code;
        $this->length = $slot->length;
        $this->width = $slot->width;
        $this->depth = $slot->depth;
        $this->amenities = $slot->amenities ?? [];
        $this->base_rate = $slot->base_rate;
        $this->is_active = $slot->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        // Check unique constraint for zone_id + code
        $query = Slot::where('zone_id', $this->zone_id)
                     ->where('code', $this->code);
                     
        if ($this->editingSlot) {
            $query->where('id', '!=', $this->editingSlot->id);
        }

        if ($query->exists()) {
            $this->addError('code', 'Code must be unique within this zone.');
            return;
        }

        $data = [
            'zone_id' => $this->zone_id,
            'name' => $this->name,
            'code' => $this->code,
            'length' => $this->length ?: null,
            'width' => $this->width ?: null,
            'depth' => $this->depth ?: null,
            'amenities' => $this->amenities,
            'base_rate' => $this->base_rate ?: null,
            'is_active' => $this->is_active,
        ];

        if ($this->editingSlot) {
            $this->editingSlot->update($data);
            session()->flash('message', 'Slot updated successfully!');
        } else {
            Slot::create($data);
            session()->flash('message', 'Slot created successfully!');
        }

        $this->closeModal();
    }

    public function delete(Slot $slot)
    {
        $slot->delete();
        session()->flash('message', 'Slot deleted successfully!');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->editingSlot = null;
        $this->zone_id = '';
        $this->name = '';
        $this->code = '';
        $this->length = '';
        $this->width = '';
        $this->depth = '';
        $this->amenities = [];
        $this->base_rate = '';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.configuration.slots', [
            'slots' => Slot::with(['zone.block.property'])->paginate(10),
            'zones' => Zone::with(['block.property'])->where('is_active', true)->get()
        ]);
    }
}