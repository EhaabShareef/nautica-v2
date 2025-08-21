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
    public $perPage = 10;

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

    public function updatedPerPage()
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
        $query = Block::query()
            ->with('property:id,name,code')
            ->withCount('zones');

        // Apply search filter with debounced input
        if ($this->search) {
            $escapedSearch = addcslashes($this->search, '%_\\');
            $query->where(function ($q) use ($escapedSearch) {
                $q->where('name', 'like', '%' . $escapedSearch . '%')
                  ->orWhere('code', 'like', '%' . $escapedSearch . '%');
            });
        }

        // Apply active/inactive filter
        $query->where('is_active', $this->showInactive ? false : true);

        return view('livewire.admin.configuration.blocks', [
            'blocks' => $query->paginate($this->perPage),
            'properties' => Property::where('is_active', true)->select('id', 'name', 'code')->get(),
            'perPageOptions' => [5, 10, 25, 50, 100]
        ]);
    }
}
