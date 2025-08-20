<?php

namespace App\Livewire\Admin\Configuration\Forms;

use App\Models\Property;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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

        $propertyName = $this->property->name;
        $this->property->delete();

        session()->flash('message', "Property '{$propertyName}' and all associated data deleted successfully!");

        $this->dispatch('property:deleted');
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->property = null;
    }

    public function render()
    {
        return view('livewire.admin.configuration.forms.property-delete');
    }
}
