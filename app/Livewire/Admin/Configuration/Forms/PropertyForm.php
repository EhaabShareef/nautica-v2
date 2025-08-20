<?php

namespace App\Livewire\Admin\Configuration\Forms;

use App\Models\Property;
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

    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:50|unique:properties,code',
        'timezone' => 'nullable|string|max:100',
        'currency' => 'nullable|string|max:3',
        'address' => 'nullable|string',
        'is_active' => 'boolean',
    ];

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
        if ($this->editingProperty) {
            $this->rules['code'] = 'required|string|max:50|unique:properties,code,' . $this->editingProperty->id;
        }

        $this->validate();

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
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
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