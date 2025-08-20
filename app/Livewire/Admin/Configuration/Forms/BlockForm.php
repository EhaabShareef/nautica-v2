<?php

namespace App\Livewire\Admin\Configuration\Forms;

use App\Models\Block;
use App\Models\Property;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class BlockForm extends Component
{
    use AuthorizesRequests;

    public bool $showModal = false;
    public ?Block $editingBlock = null;

    public $property_id = '';
    public $name = '';
    public $code = '';
    public $location = '';
    public $is_active = true;

    protected $listeners = [
        'block:create' => 'create',
        'block:edit' => 'edit',
    ];

    protected function rules(): array
    {
        $uniqueRule = 'unique:blocks,code';
        if ($this->editingBlock) {
            $uniqueRule = 'unique:blocks,code,' . $this->editingBlock->id;
        }

        return [
            'property_id' => 'required|exists:properties,id',
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', $uniqueRule],
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ];
    }

    public function create(): void
    {
        $this->authorize('create', Block::class);
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(string $blockId): void
    {
        $block = Block::find($blockId);
        if (!$block) {
            return;
        }
        $this->authorize('update', $block);
        $this->editingBlock = $block;
        $this->property_id = $block->property_id;
        $this->name = $block->name;
        $this->code = $block->code;
        $this->location = $block->location;
        $this->is_active = $block->is_active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'property_id' => $this->property_id,
            'name' => $this->name,
            'code' => $this->code,
            'location' => $this->location,
            'is_active' => $this->is_active,
        ];

        if ($this->editingBlock) {
            $this->editingBlock->update($data);
            session()->flash('message', 'Block updated successfully!');
        } else {
            Block::create($data);
            session()->flash('message', 'Block created successfully!');
        }

        $this->dispatch('block:saved');
        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->editingBlock = null;
        $this->property_id = '';
        $this->name = '';
        $this->code = '';
        $this->location = '';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.configuration.forms.block-form', [
            'properties' => Property::where('is_active', true)->get(),
        ]);
    }
}
