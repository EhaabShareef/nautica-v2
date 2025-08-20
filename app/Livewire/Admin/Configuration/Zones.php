<?php

namespace App\Livewire\Admin\Configuration;

use App\Models\Zone;
use App\Models\Block;
use Livewire\Component;
use Livewire\WithPagination;

class Zones extends Component
{
    use WithPagination;

    public $showModal = false;
    public $editingZone = null;
    
    public $block_id = '';
    public $name = '';
    public $code = '';
    public $location = '';
    public $notes = '';
    public $is_active = true;

    protected $rules = [
        'block_id' => 'required|exists:blocks,id',
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:50',
        'location' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
        'is_active' => 'boolean',
    ];

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(Zone $zone)
    {
        $this->editingZone = $zone;
        $this->block_id = $zone->block_id;
        $this->name = $zone->name;
        $this->code = $zone->code;
        $this->location = $zone->location;
        $this->notes = $zone->notes;
        $this->is_active = $zone->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        // Check unique constraint for block_id + code
        $query = Zone::where('block_id', $this->block_id)
                     ->where('code', $this->code);
                     
        if ($this->editingZone) {
            $query->where('id', '!=', $this->editingZone->id);
        }

        if ($query->exists()) {
            $this->addError('code', 'Code must be unique within this block.');
            return;
        }

        $data = [
            'block_id' => $this->block_id,
            'name' => $this->name,
            'code' => $this->code,
            'location' => $this->location,
            'notes' => $this->notes,
            'is_active' => $this->is_active,
        ];

        if ($this->editingZone) {
            $this->editingZone->update($data);
            session()->flash('message', 'Zone updated successfully!');
        } else {
            Zone::create($data);
            session()->flash('message', 'Zone created successfully!');
        }

        $this->closeModal();
    }

    public function delete(Zone $zone)
    {
        $zone->delete();
        session()->flash('message', 'Zone deleted successfully!');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->editingZone = null;
        $this->block_id = '';
        $this->name = '';
        $this->code = '';
        $this->location = '';
        $this->notes = '';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.configuration.zones', [
            'zones' => Zone::with(['block.property', 'slots'])->paginate(10),
            'blocks' => Block::with('property')->where('is_active', true)->get()
        ]);
    }
}