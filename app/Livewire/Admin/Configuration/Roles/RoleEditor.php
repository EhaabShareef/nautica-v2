<?php

namespace App\Livewire\Admin\Configuration\Roles;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleEditor extends Component
{
    use WithPagination;
    public ?string $selectedRoleId = null;
    public string $search = '';
    public string $filterGroup = 'all';
    public ?string $guard = null;
    public bool $onlyActive = true;
    public int $perPage = 25;
    public array $pendingAssign = [];
    public array $pendingRevoke = [];
    public array $assignedIds = [];
    public int $renderNonce = 0;

    public function mount($selectedRoleId = null)
    {
        $this->selectedRoleId = $selectedRoleId;
        $this->loadRole();
    }

    protected function loadRole(): void
    {
        $role = $this->selectedRoleId ? Role::find($this->selectedRoleId) : null;
        if ($role) {
            $this->guard = $role->guard_name;
            $this->assignedIds = $role->permissions->pluck('id')->all();
        } else {
            $this->guard = null;
            $this->assignedIds = [];
        }

        $this->pendingAssign = [];
        $this->pendingRevoke = [];
    }

    public function updatedSelectedRoleId(): void
    {
        $this->resetPage();
        $this->renderNonce++;
        $this->loadRole();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
        $this->renderNonce++;
    }

    public function updatedFilterGroup(): void
    {
        $this->resetPage();
        $this->renderNonce++;
    }

    public function updatedPerPage(): void
    {
        // Coerce to allowed values
        $allowed = [5, 10, 25, 50, 100];
        $this->perPage = in_array((int) $this->perPage, $allowed, true)
            ? (int) $this->perPage
            : 25;

        $this->resetPage();
        $this->renderNonce++;
    }

    public function updatedOnlyActive(): void
    {
        $this->resetPage();
        $this->renderNonce++;
    }

    protected function deriveGroup(string $name): string
    {
        return Str::contains($name, '.') ? Str::before($name, '.') : 'Ungrouped';
    }

    public function togglePermission(int $permissionId): void
    {
        if (in_array($permissionId, $this->assignedIds)) {
            if (in_array($permissionId, $this->pendingRevoke)) {
                $this->pendingRevoke = array_values(array_diff($this->pendingRevoke, [$permissionId]));
            } else {
                $this->pendingRevoke[] = $permissionId;
            }
            $this->pendingAssign = array_values(array_diff($this->pendingAssign, [$permissionId]));
        } else {
            if (in_array($permissionId, $this->pendingAssign)) {
                $this->pendingAssign = array_values(array_diff($this->pendingAssign, [$permissionId]));
            } else {
                $this->pendingAssign[] = $permissionId;
            }
            $this->pendingRevoke = array_values(array_diff($this->pendingRevoke, [$permissionId]));
        }
    }

    public function grantAll(string $group): void
    {
        $ids = Permission::query()
            ->where('guard_name', $this->guard)
            ->when($group === 'Ungrouped', function ($q) {
                $q->whereRaw("name NOT LIKE '%.%'");
            }, function ($q) use ($group) {
                $q->where('name', 'like', $group . '.%');
            })
            ->pluck('id')
            ->all();

        foreach ($ids as $id) {
            if (!in_array($id, $this->assignedIds) && !in_array($id, $this->pendingAssign)) {
                $this->pendingAssign[] = $id;
            }
            $this->pendingRevoke = array_values(array_diff($this->pendingRevoke, [$id]));
        }
    }

    public function revokeAll(string $group): void
    {
        $ids = Permission::query()
            ->where('guard_name', $this->guard)
            ->when($group === 'Ungrouped', function ($q) {
                $q->whereRaw("name NOT LIKE '%.%'");
            }, function ($q) use ($group) {
                $q->where('name', 'like', $group . '.%');
            })
            ->pluck('id')
            ->all();

        foreach ($ids as $id) {
            if (in_array($id, $this->assignedIds) && !in_array($id, $this->pendingRevoke)) {
                $this->pendingRevoke[] = $id;
            }
            $this->pendingAssign = array_values(array_diff($this->pendingAssign, [$id]));
        }
    }

    public function apply(): void
    {
        $role = $this->selectedRoleId ? Role::find($this->selectedRoleId) : null;
        if (! $role) {
            return;
        }

        $final = array_values(array_diff(array_unique(array_merge($this->assignedIds, $this->pendingAssign)), $this->pendingRevoke));

        $role->syncPermissions($final);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->assignedIds = $role->permissions->pluck('id')->all();
        $this->pendingAssign = [];
        $this->pendingRevoke = [];

        $this->dispatch('notify', type: 'success', message: 'Permissions updated');
    }

    public function discard(): void
    {
        $this->pendingAssign = [];
        $this->pendingRevoke = [];
    }

    public function render()
    {
        $role = $this->selectedRoleId ? Role::find($this->selectedRoleId) : null;

        if ($role && $this->guard !== $role->guard_name) {
            $this->guard = $role->guard_name;
        }

        $assignedIds = $role ? $role->permissions->pluck('id')->all() : [];
        $this->assignedIds = $assignedIds;

        $baseQuery = Permission::query();

        if ($this->guard) {
            $baseQuery->where('guard_name', $this->guard);
        }

        if ($this->search !== '') {
            $escaped = addcslashes($this->search, '%_\\');
            $baseQuery->where('name', 'like', '%' . $escaped . '%');
        }

        if ($this->onlyActive && Schema::hasColumn($baseQuery->getModel()->getTable(), 'is_active')) {
            $baseQuery->where('is_active', true);
        }

        $guardMismatch = false;
        if ($this->guard) {
            $guardMismatch = Permission::where('guard_name', $this->guard)->count() === 0;
        }

        $allFiltered = (clone $baseQuery)->orderBy('name')->get();

        $groups = $allFiltered->map(fn($p) => $this->deriveGroup($p->name))
            ->unique()
            ->sort()
            ->values()
            ->all();
        array_unshift($groups, 'All');

        $query = clone $baseQuery;

        if ($this->filterGroup !== 'all') {
            if ($this->filterGroup === 'Ungrouped') {
                $query->whereRaw("name NOT LIKE '%.%'");
            } else {
                $query->where('name', 'like', $this->filterGroup . '.%');
            }
        }

        $permissions = $query->orderBy('name')->paginate($this->perPage);
        $permissionGroups = collect($permissions->items())
            ->groupBy(fn($perm) => $this->deriveGroup($perm->name));

        return view('livewire.admin.configuration.roles.role-editor', [
            'role' => $role,
            'permissionGroups' => $permissionGroups,
            'groups' => $groups,
            'assignedIds' => $assignedIds,
            'pendingAssign' => $this->pendingAssign,
            'pendingRevoke' => $this->pendingRevoke,
            'permissionsPaginator' => $permissions,
            'guardMismatch' => $guardMismatch,
        ]);
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->filterGroup = 'all';
        $this->onlyActive = true;
        $this->resetPage();
        $this->renderNonce++;
    }
}

