<?php

namespace App\Livewire\Admin\Configuration;

use App\Models\Block;
use App\Models\Property;
use Livewire\Component;
use Livewire\WithPagination;

class Blocks extends Component
{
    use WithPagination;

    public $search = '';
    public $showInactive = false;

    protected $listeners = [
        'block:saved' => '$refresh',
        'block:deleted' => '$refresh',
    ];

    public function create()
    {
        $this->dispatch('block:create');
    }

    public function edit($blockId)
    {
        $this->dispatch('block:edit', $blockId);
    }

    public function delete($blockId)
    {
        $this->dispatch('block:delete', $blockId);
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
        $query = Block::with('property')->withCount('zones');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->showInactive) {
            $query->where('is_active', false);
        } else {
            $query->where('is_active', true);
        }

        return view('livewire.admin.configuration.blocks', [
            'blocks' => $query->paginate(10),
            'properties' => Property::where('is_active', true)->get(),
        ]);
    }
}
