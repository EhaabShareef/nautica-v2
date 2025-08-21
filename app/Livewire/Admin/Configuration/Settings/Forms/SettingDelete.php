<?php

namespace App\Livewire\Admin\Configuration\Settings\Forms;

use App\Models\Setting;
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
        $this->authorize('delete', $this->setting);
        $key = $this->setting->key;
        $this->setting->delete();
        Cache::forget("setting:{$key}");
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
