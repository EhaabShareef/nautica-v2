<?php

namespace App\Livewire\Admin\Configuration\Forms;

use App\Models\Block;
use App\Models\Property;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
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
        return Block::getValidationRules($this->editingBlock?->id, $this->property_id);
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
        // Authorize per action to prevent direct method calls
        if ($this->editingBlock) {
            $this->authorize('update', $this->editingBlock);
        } else {
            $this->authorize('create', Block::class);
        }

        $this->validate();

        try {
            DB::transaction(function () {
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
            });

            // Close modal only after successful transaction
            $this->closeModal();

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Block save failed', [
                'block_id' => $this->editingBlock?->id,
                'data' => [
                    'property_id' => $this->property_id,
                    'name' => $this->name,
                    'code' => $this->code,
                ],
                'error' => $e->getMessage()
            ]);

            // Flash error message to user
            session()->flash('error', 'Failed to save block. Please check your input and try again.');

            // Don't close modal on failure so user can retry
        }
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('block-form:closed');
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
