<?php

namespace App\Livewire\Admin\Configuration\Forms;

use App\Models\Slot;
use App\Models\Property;
use App\Models\Block;
use App\Models\Zone;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class SlotForm extends Component
{
    use AuthorizesRequests;

    public bool $showModal = false;
    public ?Slot $editingSlot = null;

    public $property_id = '';
    public $block_id = '';
    public $zone_id = '';
    public $code = '';
    public $location = '';
    public $is_active = true;

    protected $listeners = [
        'slot:create' => 'create',
        'slot:edit' => 'edit',
    ];

    protected function rules(): array
    {
        $uniqueRule = Rule::unique('slots', 'code')->where(fn ($q) => $q->where('zone_id', $this->zone_id));
        if ($this->editingSlot) {
            $uniqueRule->ignore($this->editingSlot->id);
        }

        return [
            'property_id' => 'required|exists:properties,id',
            'block_id' => 'required|exists:blocks,id',
            'zone_id' => 'required|exists:zones,id',
            'code' => ['required', 'string', 'max:50', $uniqueRule],
            'location' => 'required|string|max:255',
            'is_active' => 'boolean',
        ];
    }

    public function create(): void
    {
        $this->authorize('create', Slot::class);
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(string $slotId): void
    {
        $slot = Slot::with('zone.block')->find($slotId);
        if (! $slot) {
            return;
        }

        $this->authorize('update', $slot);
        $this->editingSlot = $slot;
        $this->property_id = $slot->zone->block->property_id;
        $this->block_id = $slot->zone->block_id;
        $this->zone_id = $slot->zone_id;
        $this->code = $slot->code;
        $this->location = $slot->location;
        $this->is_active = $slot->is_active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        // Authorize per action to prevent direct method calls
        if ($this->editingSlot) {
            $this->authorize('update', $this->editingSlot);
        } else {
            $this->authorize('create', Slot::class);
        }

        try {
            DB::transaction(function () {
                $data = [
                    'zone_id' => $this->zone_id,
                    'code' => $this->code,
                    'location' => $this->location,
                    'is_active' => $this->is_active,
                ];

                if ($this->editingSlot) {
                    $this->editingSlot->update($data);
                    session()->flash('message', 'Slot updated successfully!');
                } else {
                    Slot::create($data);
                    session()->flash('message', 'Slot created successfully!');
                }

                $this->dispatch('slot:saved');
            });

            $this->closeModal();
        } catch (\Exception $e) {
            \Log::error('Slot save failed', [
                'slot_id' => $this->editingSlot?->id,
                'error' => $e->getMessage(),
            ]);

            session()->flash('error', 'Failed to save slot. Please check your input and try again.');
        }
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
        $this->dispatchBrowserEvent('slot-form:closed');
    }

    protected function resetForm(): void
    {
        $this->editingSlot = null;
        $this->property_id = '';
        $this->block_id = '';
        $this->zone_id = '';
        $this->code = '';
        $this->location = '';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function updatedPropertyId(): void
    {
        $this->block_id = '';
        $this->zone_id = '';
    }

    public function updatedBlockId(): void
    {
        $this->zone_id = '';
    }

    public function render()
    {
        return view('livewire.admin.configuration.forms.slot-form', [
            'properties' => Property::where('is_active', true)->get(),
            'blocks' => $this->property_id ? Block::where('property_id', $this->property_id)->where('is_active', true)->get() : collect(),
            'zones' => $this->block_id ? Zone::where('block_id', $this->block_id)->where('is_active', true)->get() : collect(),
        ]);
    }
}
