<?php

namespace App\Livewire\Admin\Configuration\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;

class Index extends Component
{
    public $selectedRoleId;
    public $roles;

    public function mount()
    {
        $this->roles = Role::orderBy('name')->get();
        $this->selectedRoleId = $this->roles->first()?->id;
    }

    public function render()
    {
        return view('livewire.admin.configuration.roles.index')
            ->layout('layouts.app');
    }
}
