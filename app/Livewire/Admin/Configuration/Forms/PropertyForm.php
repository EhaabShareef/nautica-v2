<?php

namespace App\Livewire\Admin\Configuration\Forms;

use App\Models\Property;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PropertyForm extends Component
{
    public $showModal = false;
    public $editingProperty = null;
    
    public $name = '';
    public $code = '';
    public $timezone = '';
    public $currency = '';
    public $address = '';
    public $is_active = true;

    protected $listeners = [
        'property:create' => 'create',
        'property:edit' => 'edit'
    ];

    protected function rules(): array
    {
        return Property::getValidationRules($this->editingProperty?->id);
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($propertyId)
    {
        $property = Property::find($propertyId);
        if (!$property) return;

        $this->editingProperty = $property;
        $this->name = $property->name;
        $this->code = $property->code;
        $this->timezone = $property->timezone;
        $this->currency = $property->currency;
        $this->address = $property->address;
        $this->is_active = $property->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        // Authorize per action to prevent direct method calls
        if ($this->editingProperty) {
            $this->authorize('update', $this->editingProperty);
        } else {
            $this->authorize('create', Property::class);
        }

        $this->validate();

        try {
            DB::transaction(function () {
                $data = [
                    'name' => $this->name,
                    'code' => $this->code,
                    'timezone' => $this->timezone,
                    'currency' => $this->currency,
                    'address' => $this->address,
                    'is_active' => $this->is_active,
                ];

                if ($this->editingProperty) {
                    $this->editingProperty->update($data);
                    session()->flash('message', 'Property updated successfully!');
                } else {
                    Property::create($data);
                    session()->flash('message', 'Property created successfully!');
                }

                $this->dispatch('property:saved');
            });

            // Close modal only after successful transaction
            $this->closeModal();

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Property save failed', [
                'property_id' => $this->editingProperty?->id,
                'data' => [
                    'name' => $this->name,
                    'code' => $this->code,
                ],
                'error' => $e->getMessage()
            ]);

            // Flash error message to user
            session()->flash('error', 'Failed to save property. Please check your input and try again.');

            // Don't close modal on failure so user can retry
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('property-form:closed');
    }

    protected function resetForm()
    {
        $this->editingProperty = null;
        $this->name = '';
        $this->code = '';
        $this->timezone = '';
        $this->currency = '';
        $this->address = '';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.configuration.forms.property-form');
    }
}