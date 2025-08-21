<?php

namespace App\Livewire\Admin\Configuration\Settings\Forms;

use App\Models\AppType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AppTypeForm extends Component
{
    use AuthorizesRequests;

    public bool $showModal = false;
    public ?AppType $editingAppType = null;

    public string $group = '';
    public string $code = '';
    public string $label = '';
    public string $description = '';
    public int $sort_order = 0;
    public string $extraInput = '';
    public bool $is_protected = false;
    public bool $is_active = true;

    protected $listeners = [
        'apptype:create' => 'create',
        'apptype:edit' => 'edit',
    ];

    protected function rules(): array
    {
        $codeRule = 'required|string|max:100';
        if ($this->editingAppType) {
            $codeRule .= '|unique:app_types,code,' . $this->editingAppType->id;
        } else {
            $codeRule .= '|unique:app_types,code';
        }

        return [
            'group' => 'required|string|max:100',
            'code' => $codeRule,
            'label' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'integer',
            'extraInput' => 'nullable',
            'is_protected' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function create()
    {
        $this->authorize('create', AppType::class);
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($appTypeId)
    {
        $appType = AppType::find($appTypeId);
        if (!$appType) {
            return;
        }
        $this->authorize('update', $appType);
        $this->loadAppType($appType);
        $this->showModal = true;
    }

    public function save()
    {
        if ($this->editingAppType) {
            $this->authorize('update', $this->editingAppType);
        } else {
            $this->authorize('create', AppType::class);
        }

        $this->validate();

        $extra = $this->parseExtra($this->extraInput);

        try {
            DB::transaction(function () use ($extra) {
                $data = [
                    'group' => $this->group,
                    'code' => $this->code,
                    'label' => $this->label,
                    'description' => $this->description ?: null,
                    'sort_order' => $this->sort_order,
                    'extra' => $extra,
                    'is_protected' => $this->is_protected,
                    'is_active' => $this->is_active,
                ];

                if ($this->editingAppType) {
                    $this->editingAppType->update($data);
                    session()->flash('message', 'App type updated successfully!');
                } else {
                    AppType::create($data);
                    session()->flash('message', 'App type created successfully!');
                }

                $this->dispatch('apptype:saved');
                Cache::forget("types:{$this->group}");
            });

            $this->closeModal();
        } catch (\Exception $e) {
            \Log::error('AppType save failed', [
                'code' => $this->code,
                'error' => $e->getMessage(),
            ]);
            session()->flash('error', 'Failed to save app type.');
        }
    }

    private function parseExtra($input)
    {
        if ($input === '') {
            return null;
        }
        $decoded = json_decode($input, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }
        return $input;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->dispatchBrowserEvent('apptype-form:closed');
    }

    private function resetForm()
    {
        $this->editingAppType = null;
        $this->group = '';
        $this->code = '';
        $this->label = '';
        $this->description = '';
        $this->sort_order = 0;
        $this->extraInput = '';
        $this->is_protected = false;
        $this->is_active = true;
        $this->resetErrorBag();
    }

    private function loadAppType(AppType $appType)
    {
        $this->editingAppType = $appType;
        $this->group = $appType->group;
        $this->code = $appType->code;
        $this->label = $appType->label;
        $this->description = $appType->description ?? '';
        $this->sort_order = $appType->sort_order;
        $this->extraInput = $appType->extra ? json_encode($appType->extra, JSON_PRETTY_PRINT) : '';
        $this->is_protected = $appType->is_protected;
        $this->is_active = $appType->is_active;
    }

    public function render()
    {
        return view('livewire.admin.configuration.settings.forms.app-type-form');
    }
}
