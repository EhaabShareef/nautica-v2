<?php

namespace App\Livewire\Admin\Configuration\Settings\Forms;

use App\Models\Setting;
use App\Services\SettingsService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SettingForm extends Component
{
    use AuthorizesRequests;

    public bool $showModal = false;
    public ?Setting $editingSetting = null;

    public string $key = '';
    public string $group = '';
    public string $label = '';
    public string $description = '';
    public string $valueInput = '';
    public bool $is_protected = false;
    public bool $is_active = true;

    protected $listeners = [
        'setting:create' => 'create',
        'setting:edit' => 'edit',
    ];

    protected function rules(): array
    {
        $keyRule = 'required|string|max:255|unique:settings,key';
        if ($this->editingSetting) {
            $keyRule = 'required|string|max:255|unique:settings,key,' . $this->editingSetting->key . ',key';
        }

        return [
            'key' => $keyRule,
            'group' => 'nullable|string|max:100',
            'label' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'valueInput' => 'required',
            'is_protected' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function create()
    {
        $this->authorize('create', Setting::class);
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($settingKey)
    {
        $setting = Setting::where('key', $settingKey)->first();
        if (!$setting) {
            return;
        }
        $this->authorize('update', $setting);
        $this->loadSetting($setting);
        $this->showModal = true;
    }

    public function save()
    {
        if ($this->editingSetting) {
            $this->authorize('update', $this->editingSetting);
        } else {
            $this->authorize('create', Setting::class);
        }

        $this->validate();

        $value = $this->parseValue($this->valueInput);

        try {
            DB::transaction(function () use ($value) {
                $data = [
                    'key' => $this->key,
                    'group' => $this->group ?: null,
                    'value' => $value,
                    'label' => $this->label ?: null,
                    'description' => $this->description ?: null,
                    'is_protected' => $this->is_protected,
                    'is_active' => $this->is_active,
                ];

                if ($this->editingSetting) {
                    $this->editingSetting->update($data);
                    session()->flash('message', 'Setting updated successfully!');
                } else {
                    Setting::create($data);
                    session()->flash('message', 'Setting created successfully!');
                }

                $this->dispatch('setting:saved');
                SettingsService::clearCache($this->key, $this->group);
            });

            $this->closeModal();
        } catch (\Exception $e) {
            \Log::error('Setting save failed', [
                'key' => $this->key,
                'error' => $e->getMessage(),
            ]);
            session()->flash('error', 'Failed to save setting. Please check your input and try again.');
        }
    }

    private function parseValue($input)
    {
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
        $this->dispatch('setting-form:closed', [], to: 'window');
    }

    private function resetForm()
    {
        $this->editingSetting = null;
        $this->key = '';
        $this->group = '';
        $this->label = '';
        $this->description = '';
        $this->valueInput = '';
        $this->is_protected = false;
        $this->is_active = true;
        $this->resetErrorBag();
    }

    private function loadSetting(Setting $setting)
    {
        $this->editingSetting = $setting;
        $this->key = $setting->key;
        $this->group = $setting->group ?? '';
        $this->label = $setting->label ?? '';
        $this->description = $setting->description ?? '';
        if (is_string($setting->value)) {
            $this->valueInput = $setting->value;
        } elseif (is_array($setting->value) || is_object($setting->value)) {
            $this->valueInput = json_encode($setting->value, JSON_PRETTY_PRINT);
        } else {
            $this->valueInput = (string) $setting->value ?? '';
        }
        $this->is_protected = $setting->is_protected;
        $this->is_active = $setting->is_active;
    }

    public function render()
    {
        return view('livewire.admin.configuration.settings.forms.setting-form');
    }
}
