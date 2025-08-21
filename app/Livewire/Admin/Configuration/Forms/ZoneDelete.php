<?php

namespace App\Livewire\Admin\Configuration\Forms;

use App\Models\Zone;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ZoneDelete extends Component
{
    use AuthorizesRequests;

    public bool $showModal = false;
    public ?Zone $zone = null;

    protected $listeners = [
        'zone:delete' => 'confirm',
    ];

    public function confirm(string $zoneId): void
    {
        $this->zone = Zone::with('slots')->find($zoneId);
        if (!$this->zone) {
            return;
        }

        $this->authorize('delete', $this->zone);
        $this->showModal = true;
    }

    public function delete(): void
    {
        if (!$this->zone) {
            return;
        }

        if ($this->zone->slots()->exists()) {
            session()->flash('error', "Cannot delete zone '{$this->zone->name}' because it has associated slots.");
            $this->closeModal();
            return;
        }

        try {
            DB::transaction(function () {
                $name = $this->zone->name;
                $this->zone->delete();

                session()->flash('message', "Zone '{$name}' deleted successfully!");
                $this->dispatch('zone:deleted');
            });

            $this->closeModal();
        } catch (\Exception $e) {
            \Log::error('Zone deletion failed', [
                'zone_id' => $this->zone->id,
                'zone_name' => $this->zone->name,
                'error' => $e->getMessage(),
            ]);

            session()->flash('error', 'Failed to delete zone. Please try again or contact support if the issue persists.');
        }
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->zone = null;
        $this->dispatchBrowserEvent('zone-delete:closed');
    }

    public function render()
    {
        return view('livewire.admin.configuration.forms.zone-delete');
    }
}

