<?php

namespace App\Livewire\Admin\Configuration\Settings\Forms;

use App\Models\AppType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class AppTypeDelete extends Component
{
    use AuthorizesRequests;

    public bool $showModal = false;
    public ?AppType $appType = null;

    protected $listeners = [
        'apptype:delete' => 'confirm',
    ];

    public function confirm($id)
    {
        $appType = AppType::find($id);
        if (!$appType) {
            return;
        }
        $this->authorize('delete', $appType);
        $this->appType = $appType;
        $this->showModal = true;
    }

    public function delete()
    {
        if (!$this->appType) {
            return;
        }
        $this->authorize('delete', $this->appType);
        $group = $this->appType->group;
        $this->appType->delete();
        Cache::forget("types:{$group}");
        $this->dispatch('apptype:deleted');
        session()->flash('message', 'App type deleted successfully!');
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->appType = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.configuration.settings.forms.app-type-delete');
    }
}
