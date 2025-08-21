<?php

namespace App\Livewire\Admin\Configuration\Forms;

use App\Models\Block;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class BlockDelete extends Component
{
    use AuthorizesRequests;

    public bool $showModal = false;
    public ?Block $block = null;

    protected $listeners = [
        'block:delete' => 'confirm',
    ];

    public function confirm(string $blockId): void
    {
        $this->block = Block::with('zones')->find($blockId);
        if (!$this->block) {
            return;
        }
        $this->authorize('delete', $this->block);
        $this->showModal = true;
    }

    public function delete(): void
    {
        if (!$this->block) {
            return;
        }
        $name = $this->block->name;
        $this->block->delete();
        session()->flash('message', "Block '{$name}' deleted successfully!");
        $this->dispatch('block:deleted');
        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->block = null;
    }

    public function render()
    {
        return view('livewire.admin.configuration.forms.block-delete');
    }
}
