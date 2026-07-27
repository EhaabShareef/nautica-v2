<?php

namespace App\Livewire\Admin\Configuration\Forms;

use App\Models\Block;
use App\Models\Slot;
use App\Models\Booking;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
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

        try {
            $slotIds = Slot::whereHas('zone', function ($q) {
                $q->where('block_id', $this->block->id);
            })->pluck('id');

            if ($slotIds->isNotEmpty()) {
                $hasBookings = Booking::whereIn('slot_id', $slotIds)
                    ->whereIn('status', ['confirmed', 'pending'])
                    ->where('end_date', '>=', now())
                    ->exists();

                if ($hasBookings) {
                    session()->flash('error', 'This block cannot be inactivated or deleted because it contains active or upcoming bookings.');
                    return;
                }
            }

            DB::transaction(function () {
                $name = $this->block->name;
                $this->block->delete();

                // Flash success message and dispatch events within transaction
                session()->flash('message', "Block '{$name}' deleted successfully!");
                $this->dispatch('block:deleted');
            });

            // Close modal only after successful transaction
            $this->closeModal();
            
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Block deletion failed', [
                'block_id' => $this->block->id,
                'block_name' => $this->block->name,
                'error' => $e->getMessage()
            ]);
            
            // Flash error message to user
            session()->flash('error', 'Failed to delete block. Please try again or contact support if the issue persists.');
            
            // Don't close modal or dispatch success events on failure
        }
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->block = null;
        $this->dispatch('block-delete:closed');
    }

    public function render()
    {
        return view('livewire.admin.configuration.forms.block-delete');
    }
}
