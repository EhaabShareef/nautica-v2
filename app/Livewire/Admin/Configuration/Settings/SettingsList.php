<?php

namespace App\Livewire\Admin\Configuration\Settings;

use App\Models\Setting;
use Livewire\Component;
use Livewire\WithPagination;

class SettingsList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $groupFilter = '';
    public bool $showProtected = false;
    public int $perPage = 15;

    protected $listeners = [
        'setting:saved' => '$refresh',
        'setting:deleted' => '$refresh',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedGroupFilter()
    {
        $this->resetPage();
    }

    public function updatedShowProtected()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->dispatch('setting:create');
    }

    public function edit($settingKey)
    {
        $this->dispatch('setting:edit', $settingKey);
    }

    public function delete($settingKey)
    {
        $this->dispatch('setting:delete', $settingKey);
    }

    public function render()
    {
        $query = Setting::query();

        if ($this->search) {
            $escapedSearch = addcslashes($this->search, '%_\\');
            $query->where(function ($q) use ($escapedSearch) {
                $q->where('key', 'like', '%' . $escapedSearch . '%')
                  ->orWhere('label', 'like', '%' . $escapedSearch . '%')
                  ->orWhere('description', 'like', '%' . $escapedSearch . '%');
            });
        }

        if ($this->groupFilter) {
            $query->where('group', $this->groupFilter);
        }

        if (!$this->showProtected) {
            $query->where('is_protected', false);
        }

        $settings = $query->where('is_active', true)
                         ->orderBy('group')
                         ->orderBy('key')
                         ->paginate($this->perPage);

        $groups = Setting::select('group')
                        ->whereNotNull('group')
                        ->distinct()
                        ->pluck('group');

        return view('livewire.admin.configuration.settings.settings-list', [
            'settings' => $settings,
            'groups' => $groups,
            'perPageOptions' => [10, 15, 25, 50, 100]
        ]);
    }
}
