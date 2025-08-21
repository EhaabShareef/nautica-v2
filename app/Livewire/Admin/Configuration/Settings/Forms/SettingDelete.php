<?php

namespace App\Livewire\Admin\Configuration\Settings\Forms;

use App\Models\Setting;
use App\Services\SettingsService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class SettingDelete extends Component
{
    use AuthorizesRequests;

    public bool $showModal = false;
    public ?Setting $setting = null;

    protected $listeners = [
        'setting:delete' => 'confirm',
    ];

    public function confirm($key)
    {
        $setting = Setting::where('key', $key)->first();
        if (!$setting) {
            return;
        }
        $this->authorize('delete', $setting);
        $this->setting = $setting;
        $this->showModal = true;
    }

    public function delete()
    {
        if (!$this->setting) {
            return;
        }

        // Re-fetch to ensure we have the latest is_protected flag
        $fresh = Setting::query()->whereKey($this->setting->key)->first();
        if (!$fresh) {
            $this->closeModal();
            return;
        }
        $this->authorize('delete', $fresh);

        $key   = $fresh->key;
        $group = $fresh->group;

        $fresh->delete();

        // Clear caches: single setting and its group
        SettingsService::clearCache($key, $group);

        $this->dispatch('setting:deleted');
        session()->flash('message', 'Setting deleted successfully!');
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->setting = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.configuration.settings.forms.setting-delete');
    }
}
