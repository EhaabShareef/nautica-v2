<?php

namespace App\Livewire\Admin\Configuration\Settings;

use Livewire\Component;

class Index extends Component
{
    public string $activeTab = 'settings';

    protected $listeners = [
        'setting:saved' => '$refresh',
        'setting:deleted' => '$refresh',
        'apptype:saved' => '$refresh',
        'apptype:deleted' => '$refresh',
    ];

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.admin.configuration.settings.index');
    }
}
