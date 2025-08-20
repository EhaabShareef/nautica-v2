<?php

namespace App\Livewire\Admin\Configuration;

use App\Models\AppType;
use Livewire\Component;
use Livewire\WithPagination;

class AppTypes extends Component
{
    use WithPagination;

    public $showModal = false;
    public $editingAppType = null;
    
    public $group = '';
    public $code = '';
    public $label = '';
    public $extra = '';
    public $is_active = true;

    // Common groups for easy selection
    public $commonGroups = [
        'booking_status' => 'Booking Status',
        'booking_type' => 'Booking Type',
        'invoice_status' => 'Invoice Status',
        'payment_method' => 'Payment Method',
        'payment_status' => 'Payment Status',
        'service_status' => 'Service Status',
        'service_unit' => 'Service Unit',
        'tax_rate' => 'Tax Rate',
        'size_unit' => 'Size Unit',
        'user_role' => 'User Role',
        'vessel_type' => 'Vessel Type',
        'contract_status' => 'Contract Status',
    ];

    protected $rules = [
        'group' => 'required|string|max:100',
        'code' => 'required|string|max:50',
        'label' => 'required|string|max:255',
        'extra' => 'nullable|json',
        'is_active' => 'boolean',
    ];

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(AppType $appType)
    {
        $this->editingAppType = $appType;
        $this->group = $appType->group;
        $this->code = $appType->code;
        $this->label = $appType->label;
        $this->extra = $appType->extra ? json_encode($appType->extra, JSON_PRETTY_PRINT) : '';
        $this->is_active = $appType->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        // Check unique constraint for group + code
        $query = AppType::where('group', $this->group)
                        ->where('code', $this->code);
                        
        if ($this->editingAppType) {
            $query->where('id', '!=', $this->editingAppType->id);
        }

        if ($query->exists()) {
            $this->addError('code', 'Code must be unique within this group.');
            return;
        }

        // Process extra field if provided
        $extraData = null;
        if (!empty($this->extra)) {
            $extraData = json_decode($this->extra, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->addError('extra', 'Invalid JSON format.');
                return;
            }
        }

        $data = [
            'group' => $this->group,
            'code' => $this->code,
            'label' => $this->label,
            'extra' => $extraData,
            'is_active' => $this->is_active,
        ];

        if ($this->editingAppType) {
            $this->editingAppType->update($data);
            session()->flash('message', 'App type updated successfully!');
        } else {
            AppType::create($data);
            session()->flash('message', 'App type created successfully!');
        }

        $this->closeModal();
    }

    public function delete(AppType $appType)
    {
        $appType->delete();
        session()->flash('message', 'App type deleted successfully!');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->editingAppType = null;
        $this->group = '';
        $this->code = '';
        $this->label = '';
        $this->extra = '';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.configuration.app-types', [
            'appTypes' => AppType::orderBy('group')->orderBy('code')->paginate(15),
            'groupedAppTypes' => AppType::all()->groupBy('group')
        ]);
    }
}