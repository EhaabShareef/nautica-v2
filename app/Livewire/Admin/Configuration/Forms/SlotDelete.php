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
        if (! $this->slot) {
            return;
        }

        try {
            // Re-authorize at action time
            $this->authorize('delete', $this->slot);

            DB::transaction(function () {
                // Re-check inside the tx to avoid races
                $slot = Slot::lockForUpdate()->find($this->slot->id);
                if (! $slot) {
                    throw new \RuntimeException('Slot not found.');
                }
                
                // Explicit check for linked records before attempting deletion
                $bookingCount = $slot->bookings()->count();
                $contractCount = $slot->contracts()->count();
                
                if ($bookingCount > 0 || $contractCount > 0) {
                    $this->hasLinkedRecords = true;
                    
                    $messages = [];
                    if ($bookingCount > 0) {
                        $messages[] = "{$bookingCount} booking" . ($bookingCount > 1 ? 's' : '');
                    }
                    if ($contractCount > 0) {
                        $messages[] = "{$contractCount} contract" . ($contractCount > 1 ? 's' : '');
                    }
                    
                    throw new \RuntimeException(
                        "Cannot delete slot '{$slot->code}' because it has " . implode(' and ', $messages) . '. Please remove or reassign these records first.'
                    );
                }

                $label = $slot->code;
                $slot->delete();

                session()->flash('message', "Slot '{$label}' deleted successfully!");
                $this->dispatch('slot:deleted');
            });

            $this->closeModal();
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle DB foreign key constraint violations
            if (str_contains($e->getMessage(), 'foreign key constraint fails')) {
                \Log::warning('Slot deletion blocked by foreign key constraint', [
                    'slot_id' => $this->slot->id,
                    'slot_code' => $this->slot->code,
                    'error' => $e->getMessage(),
                ]);
                
                session()->flash('error', "Cannot delete slot '{$this->slot->code}' because it has associated bookings or contracts. Please remove or reassign these records first.");
            } else {
                // Handle other database errors
                \Log::error('Database error during slot deletion', [
                    'slot_id' => $this->slot->id,
                    'error' => $e->getMessage(),
                ]);
                
                session()->flash('error', 'Database error occurred while deleting slot. Please try again or contact support.');
            }
            
            $this->closeModal();
        } catch (\Exception $e) {
            \Log::error('Slot deletion failed', [
                'slot_id' => $this->slot->id,
                'error' => $e->getMessage(),
            ]);

            // Check if it's our custom validation message
            if (str_contains($e->getMessage(), 'Cannot delete slot')) {
                session()->flash('error', $e->getMessage());
            } else {
                session()->flash('error', 'Failed to delete slot. Please try again or contact support if the issue persists.');
            }
            
            $this->closeModal();
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
