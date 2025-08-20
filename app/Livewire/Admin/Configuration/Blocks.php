<?php

namespace App\Livewire\Admin\Configuration;

use App\Models\Block;
use App\Models\Property;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class Blocks extends Component
{
    use WithPagination, AuthorizesRequests;

    public $showModal = false;
    public $editingBlock = null;

    public $property_id = '';
    public $name = '';
    public $code = '';
    public $location = '';
    public $is_active = true;

    protected function rules(): array
    {
        return [
            'property_id' => 'required|exists:properties,id',
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('blocks', 'code')
                    ->where(fn($q) => $q->where('property_id', $this->property_id))
                    ->ignore($this->editingBlock?->id),
            ],
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ];
    }

    public function create()
    {
        $this->authorize('create', Block::class);
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(Block $block)
    {
        $this->authorize('update', $block);
        $this->editingBlock = $block;
        $this->property_id = $block->property_id;
        $this->name = $block->name;
        $this->code = $block->code;
        $this->location = $block->location;
        $this->is_active = $block->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        // Check unique constraint for property_id + code
        $query = Block::where('property_id', $this->property_id)
            ->where('code', $this->code);

        if ($this->editingBlock) {
            $query->where('id', '!=', $this->editingBlock->id);
        }

        if ($query->exists()) {
            $this->addError('code', 'Code must be unique within this property.');
            return;
        }

        $data = [
            'property_id' => $this->property_id,
            'name' => $this->name,
            'code' => $this->code,
            'location' => $this->location,
            'is_active' => $this->is_active,
        ];

        try {
            if ($this->editingBlock) {
                $this->editingBlock->update($data);
                session()->flash('message', 'Block updated successfully!');
            } else {
                Block::create($data);
                session()->flash('message', 'Block created successfully!');
            }
            $this->closeModal();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                $this->addError('code', 'Code must be unique within this property.');
            } else {
                session()->flash('error', 'An error occurred while saving the block.');
            }
        }
    }

    public function delete(Block $block)
    {
        $this->authorize('delete', $block);
        try {
            $block->delete();
            session()->flash('message', 'Block deleted successfully!');
        } catch (\Illuminate\Database\QueryException $e) {
            // SQLSTATE[23000]: Integrity constraint violation
            if ($e->getCode() === '23000') {
                session()->flash('error', 'Block could not be deleted because it is referenced by other records.');
            } else {
                session()->flash('error', 'An error occurred while deleting the block.');
            }
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    protected function resetForm()
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
        return view('livewire.admin.configuration.blocks', [
            'blocks' => Block::with(['property', 'zones'])->paginate(10),
            'properties' => Property::where('is_active', true)->get()
        ]);
    }
}