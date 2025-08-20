<?php

namespace App\Livewire\Admin\Configuration;

use App\Models\Setting;
use Livewire\Component;
use Livewire\WithPagination;

class Settings extends Component
{
    use WithPagination;

    public $showModal = false;
    public $editingSetting = null;
    
    public $key = '';
    public $value = '';
    public $valueType = 'string'; // string, number, boolean, json

    protected $rules = [
        'key' => 'required|string|max:255',
        'value' => 'required',
        'valueType' => 'required|in:string,number,boolean,json',
    ];

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(Setting $setting)
    {
        $this->editingSetting = $setting;
        $this->key = $setting->key;
        
        // Determine value type and format for display
        $value = $setting->value;
        if (is_bool($value)) {
            $this->valueType = 'boolean';
            $this->value = $value ? '1' : '0';
        } elseif (is_numeric($value)) {
            $this->valueType = 'number';
            $this->value = (string) $value;
        } elseif (is_array($value) || is_object($value)) {
            $this->valueType = 'json';
            $this->value = json_encode($value, JSON_PRETTY_PRINT);
        } else {
            $this->valueType = 'string';
            $this->value = (string) $value;
        }
        
        $this->showModal = true;
    }

    public function save()
    {
        if ($this->editingSetting) {
            $this->rules['key'] = 'required|string|max:255|unique:settings,key,' . $this->editingSetting->key . ',key';
        } else {
            $this->rules['key'] = 'required|string|max:255|unique:settings,key';
        }

        $this->validate();

        // Process value based on type
        $processedValue = $this->processValue();
        
        if ($processedValue === null && $this->valueType === 'json') {
            $this->addError('value', 'Invalid JSON format.');
            return;
        }

        $data = [
            'key' => $this->key,
            'value' => $processedValue,
        ];

        if ($this->editingSetting) {
            $this->editingSetting->update($data);
            session()->flash('message', 'Setting updated successfully!');
        } else {
            Setting::create($data);
            session()->flash('message', 'Setting created successfully!');
        }

        $this->closeModal();
    }

    protected function processValue()
    {
        switch ($this->valueType) {
            case 'boolean':
                return (bool) $this->value;
            case 'number':
                return is_numeric($this->value) ? (float) $this->value : $this->value;
            case 'json':
                $decoded = json_decode($this->value, true);
                return json_last_error() === JSON_ERROR_NONE ? $decoded : null;
            default:
                return $this->value;
        }
    }

    public function delete(Setting $setting)
    {
        $setting->delete();
        session()->flash('message', 'Setting deleted successfully!');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->editingSetting = null;
        $this->key = '';
        $this->value = '';
        $this->valueType = 'string';
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.configuration.settings', [
            'settings' => Setting::paginate(10)
        ]);
    }
}