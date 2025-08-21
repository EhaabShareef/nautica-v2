<?php

namespace App\Livewire\Admin\Configuration\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Str;

class RoleEditor extends Component
{
    public $roleId;
    public $role;
    public $group = 'all';
    public $search = '';
    public $assigned = [];
    public $original = [];
    public $dirty = false;

    public function mount($roleId)
    {
        $this->loadRole($roleId);
    }

    public function loadRole($roleId)
    {
        $this->role = Role::findOrFail($roleId);
        $this->assigned = $this->role->permissions->pluck('id')->toArray();
        $this->original = $this->assigned;
        $this->dirty = false;
    }

    public function updatedAssigned()
    {
        $this->dirty = $this->hasChanges();
    }

    public function updatedSearch()
    {
        $this->resetPageIfApplicable();
    }

    public function updatedGroup()
    {
        $this->resetPageIfApplicable();
    }

    protected function resetPageIfApplicable()
    {
        // placeholder for pagination resets if needed
    }

    protected function hasChanges()
    {
        $current = $this->assigned;
        $original = $this->original;
        sort($current);
        sort($original);
        return $current !== $original;
    }

    protected function deriveGroup($name)
    {
        return Str::before($name, '.');
    }

    public function grantAll($group)
    {
        $permissions = $this->permissionsQuery()->get()->filter(function ($perm) use ($group) {
            return $this->deriveGroup($perm->name) === $group;
        });
        foreach ($permissions as $perm) {
            if (!in_array($perm->id, $this->assigned)) {
                $this->assigned[] = $perm->id;
            }
        }
        $this->updatedAssigned();
    }

    public function revokeAll($group)
    {
        $permissions = $this->permissionsQuery()->get()->filter(function ($perm) use ($group) {
            return $this->deriveGroup($perm->name) === $group;
        });
        $ids = $permissions->pluck('id')->toArray();
        $this->assigned = array_values(array_diff($this->assigned, $ids));
        $this->updatedAssigned();
    }

    public function apply()
    {
        $this->role->syncPermissions($this->assigned);
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $this->loadRole($this->role->id);
        $this->dispatch('notify', type: 'success', message: 'Permissions updated');
    }

    public function discard()
    {
        $this->assigned = $this->original;
        $this->dirty = false;
    }

    protected function permissionsQuery()
    {
        $query = Permission::query()->where('guard_name', $this->role->guard_name);
        return $query;
    }

    public function getPermissionsProperty()
    {
        $query = $this->permissionsQuery();

        if ($this->group !== 'all') {
            $query = $query->get()->filter(function ($perm) {
                return $this->deriveGroup($perm->name) === $this->group;
            });
        } else {
            $query = $query->get();
        }

        if ($this->search) {
            $search = Str::lower($this->search);
            $query = $query->filter(function ($perm) use ($search) {
                return Str::contains(Str::lower($perm->name), $search);
            });
        }

        return $query->groupBy(fn($perm) => $this->deriveGroup($perm->name));
    }

    public function getGroupsProperty()
    {
        return Permission::where('guard_name', $this->role->guard_name)
            ->get()
            ->map(fn($p) => $this->deriveGroup($p->name))
            ->unique()
            ->sort()
            ->values();
    }

    public function render()
    {
        return view('livewire.admin.configuration.roles.role-editor', [
            'permissionGroups' => $this->permissions,
            'groups' => $this->groups,
        ]);
    }
}
