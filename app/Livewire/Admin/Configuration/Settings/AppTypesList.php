<?php

namespace App\Livewire\Admin\Configuration\Settings;

use App\Models\AppType;
use Livewire\Component;
use Livewire\WithPagination;

class AppTypesList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $groupFilter = '';
    public bool $showInactive = false;
    public bool $showProtected = false;

    protected $listeners = [
        'apptype:saved' => '$refresh',
        'apptype:deleted' => '$refresh',
    ];

    public function mount()
    {
        $firstGroup = AppType::select('group')->distinct()->first();
        $this->groupFilter = $firstGroup?->group ?? '';
    }

    public function create()
    {
        $this->dispatch('apptype:create');
    }

    public function edit($appTypeId)
    {
        $this->dispatch('apptype:edit', $appTypeId);
    }

    public function delete($appTypeId)
    {
        $this->dispatch('apptype:delete', $appTypeId);
    }

    public function render()
    {
        $query = AppType::query();

        if ($this->groupFilter) {
            $query->where('group', $this->groupFilter);
        }

        if ($this->search) {
            $escapedSearch = addcslashes($this->search, '%_\\');
            $query->where(function ($q) use ($escapedSearch) {
                $q->where('code', 'like', '%' . $escapedSearch . '%')
                  ->orWhere('label', 'like', '%' . $escapedSearch . '%');
            });
        }

        if (!$this->showInactive) {
            $query->where('is_active', true);
        }

        if (!$this->showProtected) {
            $query->where('is_protected', false);
        }

        $appTypes = $query->orderBy('sort_order')
                         ->orderBy('label')
                         ->paginate($this->perPage);

        $groups = AppType::select('group')
                        ->distinct()
                        ->orderBy('group')
                        ->pluck('group');

        return view('livewire.admin.configuration.settings.app-types-list', [
            'appTypes' => $appTypes,
            'groups' => $groups,
        ]);
    }
}
