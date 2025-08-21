<?php

namespace App\Livewire\Admin\Configuration\Forms;

use App\Models\Block;
use App\Models\Zone;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ZoneForm extends Component
{
    use AuthorizesRequests;

    public bool $showModal = false;
    public ?Zone $editingZone = null;

    public $block_id = '';
    public $name = '';
    public $code = '';
    public $location = '';
    public bool $is_active = true;

    protected $listeners = [
        'zone:create' => 'create',
        'zone:edit' => 'edit',
    ];

    protected function rules(): array
    {
        $uniqueRule = Rule::unique('zones', 'code')
            ->where(fn($q) => $q->where('block_id', $this->block_id));

        if ($this->editingZone) {
            $uniqueRule->ignore($this->editingZone->id);
        }

        return [
            'block_id' => 'required|exists:blocks,id',
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', $uniqueRule],
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ];
    }

    public function create(): void
    {
        $this->authorize('create', Zone::class);
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(string $zoneId): void
    {
        $zone = Zone::find($zoneId);
        if (!$zone) {
            return;
        }

        $this->authorize('update', $zone);

        $this->editingZone = $zone;
        $this->block_id = $zone->block_id;
        $this->name = $zone->name;
        $this->code = $zone->code;
        $this->location = $zone->location;
        $this->is_active = $zone->is_active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $data = [
                    'block_id' => $this->block_id,
                    'name' => $this->name,
                    'code' => $this->code,
                    'location' => $this->location,
                    'is_active' => $this->is_active,
                ];

                if ($this->editingZone) {
                    $this->editingZone->update($data);
                    session()->flash('message', 'Zone updated successfully!');
                } else {
                    Zone::create($data);
                    session()->flash('message', 'Zone created successfully!');
                }

                $this->dispatch('zone:saved');
            });

            $this->closeModal();
        } catch (\Exception $e) {
            \Log::error('Zone save failed', [
                'zone_id' => $this->editingZone?->id,
                'data' => [
                    'block_id' => $this->block_id,
                    'name' => $this->name,
                    'code' => $this->code,
                ],
                'error' => $e->getMessage(),
            ]);

            session()->flash('error', 'Failed to save zone. Please check your input and try again.');
        }
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
        $this->dispatchBrowserEvent('zone-form:closed');
    }

    protected function resetForm(): void
    {
        $this->editingZone = null;
        $this->block_id = '';
        $this->name = '';
        $this->code = '';
        $this->location = '';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.configuration.forms.zone-form', [
            'blocks' => Block::with('property')->where('is_active', true)->get(),
        ]);
    }
}

