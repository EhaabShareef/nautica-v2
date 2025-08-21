<?php

namespace App\Livewire\Admin\Configuration\Forms;

use App\Models\Property;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PropertyDelete extends Component
{
    use AuthorizesRequests;

    public bool $showModal = false;
    public ?Property $property = null;
    protected $listeners = [
        'property:delete' => 'confirmDelete'
    ];

    public function confirmDelete(string $propertyId)
    {
        $this->property = Property::with('blocks.zones.slots')->find($propertyId);
        if (!$this->property) return;
        $this->authorize('delete', $this->property);

        $this->showModal = true;
    }

    public function delete()
    {
        if (!$this->property) return;

        try {
            DB::transaction(function () {
                $propertyName = $this->property->name;
                $this->property->delete();
                
                // Flash success message and dispatch events within transaction
                session()->flash('message', "Property '{$propertyName}' and all associated data deleted successfully!");
                $this->dispatch('property:deleted');
            });
            
            // Close modal only after successful transaction
            $this->closeModal();
            
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Property deletion failed', [
                'property_id' => $this->property->id,
                'property_name' => $this->property->name,
                'error' => $e->getMessage()
            ]);
            
            // Flash error message to user
            session()->flash('error', 'Failed to delete property and associated data. Please try again or contact support if the issue persists.');
            
            // Don't close modal or dispatch success events on failure
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->property = null;
        $this->dispatch('property-delete:closed');
    }

    public function render()
    {
        return view('livewire.admin.configuration.forms.property-delete');
    }
}
