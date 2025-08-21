<?php

namespace App\Livewire\Admin\Configuration\Forms;

use App\Models\Slot;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SlotDelete extends Component
{
    use AuthorizesRequests;

    public bool $showModal = false;
    public ?Slot $slot = null;
    public bool $hasLinkedRecords = false;

    protected $listeners = [
        'slot:delete' => 'confirm',
    ];

    public function confirm(string $slotId): void
    {
        $this->slot = Slot::with('zone.block.property', 'bookings', 'contracts')->find($slotId);
        if (! $this->slot) {
            return;
        }

        $this->authorize('delete', $this->slot);
        $this->hasLinkedRecords = $this->slot->bookings()->exists() || $this->slot->contracts()->exists();
        $this->showModal = true;
    }

    public function delete(): void
    {
        if (! $this->slot || $this->hasLinkedRecords) {
            return;
        }

        try {
            DB::transaction(function () {
                $label = $this->slot->code;
                $this->slot->delete();

                session()->flash('message', "Slot '{$label}' deleted successfully!");
                $this->dispatch('slot:deleted');
            });

            $this->closeModal();
        } catch (\Exception $e) {
            \Log::error('Slot deletion failed', [
                'slot_id' => $this->slot->id,
                'error' => $e->getMessage(),
            ]);

            session()->flash('error', 'Failed to delete slot. Please try again or contact support if the issue persists.');
        }
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->slot = null;
        $this->hasLinkedRecords = false;
        $this->dispatchBrowserEvent('slot-delete:closed');
    }

    public function render()
    {
        return view('livewire.admin.configuration.forms.slot-delete');
    }
}
