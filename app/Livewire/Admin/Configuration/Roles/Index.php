<?php

namespace App\Livewire\Admin\Configuration\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Str;

class Index extends Component
{
    public $selectedRoleId;
    public $roles;
    public $search = '';
    public $groupFilter = 'all';
    public $isDirty = false;
    
    // Role editor properties
    public $selectedRole;
    public $assigned = [];
    public $original = [];

    public function mount()
    {
        $this->roles = Role::orderBy('name')->get();
        $this->selectedRoleId = $this->roles->first()?->id;
        if ($this->selectedRoleId) {
            $this->loadRole($this->selectedRoleId);
        }
    }

    public function updatedSelectedRoleId()
    {
        $this->isDirty = false;
        $this->search = ''; // Reset search when role changes
        $this->groupFilter = 'all'; // Reset group filter when role changes
        
        if ($this->selectedRoleId) {
            $this->loadRole($this->selectedRoleId);
        } else {
            $this->selectedRole = null;
            $this->assigned = [];
            $this->original = [];
        }
        
        // Force refresh of computed properties
        if (isset($this->computedPropertyCache['permissionGroups'])) {
            unset($this->computedPropertyCache['permissionGroups']);
        }
        if (isset($this->computedPropertyCache['groups'])) {
            unset($this->computedPropertyCache['groups']);
        }
    }

    public function updatedSearch()
    {
        // Force refresh of computed properties when search changes
        if (isset($this->computedPropertyCache['permissionGroups'])) {
            unset($this->computedPropertyCache['permissionGroups']);
        }
    }

    public function updatedGroupFilter()
    {
        // Force refresh of computed properties when group filter changes
        if (isset($this->computedPropertyCache['permissionGroups'])) {
            unset($this->computedPropertyCache['permissionGroups']);
        }
    }

    public function loadRole($roleId)
    {
        try {
            $this->selectedRole = Role::findOrFail($roleId);
            $this->assigned = $this->selectedRole->permissions->pluck('id')->toArray();
            $this->original = $this->assigned;
            $this->isDirty = false;
            
            // Force refresh of computed properties
            if (isset($this->computedPropertyCache['permissionGroups'])) {
                unset($this->computedPropertyCache['permissionGroups']);
            }
            if (isset($this->computedPropertyCache['groups'])) {
                unset($this->computedPropertyCache['groups']);
            }
        } catch (\Exception $e) {
            $this->selectedRole = null;
            $this->assigned = [];
            $this->original = [];
            throw $e;
        }
    }

    public function updatedAssigned()
    {
        $this->isDirty = $this->hasChanges();
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

    public function handleApplyChanges()
    {
        $this->apply();
    }

    public function handleDiscardChanges()
    {
        $this->discard();
    }

    public function apply()
    {
        if (!$this->selectedRole) return;
        
        try {
            $this->selectedRole->syncPermissions($this->assigned);
            app(PermissionRegistrar::class)->forgetCachedPermissions();
            $this->loadRole($this->selectedRole->id);
            $this->dispatch('notify', type: 'success', message: 'Permissions updated');
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Failed to update permissions: ' . $e->getMessage());
            \Log::error('Permission sync failed', [
                'role' => $this->selectedRole->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function discard()
    {
        $this->assigned = $this->original;
        $this->isDirty = false;
    }

    protected function permissionsQuery()
    {
        if (!$this->selectedRole) {
            return collect([]);
        }
        return Permission::query()->where('guard_name', $this->selectedRole->guard_name);
    }

    public function getPermissionGroupsProperty()
    {
        if (!$this->selectedRole) {
            return collect([]);
        }

        // Always get fresh data
        $allPermissions = Permission::where('guard_name', $this->selectedRole->guard_name)->get();
        
        // Apply search filter first
        if ($this->search && trim($this->search) !== '') {
            $search = Str::lower(trim($this->search));
            $allPermissions = $allPermissions->filter(function ($perm) use ($search) {
                return Str::contains(Str::lower($perm->name), $search);
            });
        }
        
        // Apply group filter second
        if ($this->groupFilter && $this->groupFilter !== 'all') {
            $allPermissions = $allPermissions->filter(function ($perm) {
                return $this->deriveGroup($perm->name) === $this->groupFilter;
            });
        }
        
        // Group the filtered permissions
        $grouped = $allPermissions->groupBy(fn($perm) => $this->deriveGroup($perm->name));
        
        // Sort groups by name
        return $grouped->sortKeys();
    }

    public function getGroupsProperty()
    {
        if (!$this->selectedRole) {
            return collect([]);
        }
        
        // Always get fresh data for groups
        return Permission::where('guard_name', $this->selectedRole->guard_name)
            ->get()
            ->map(fn($p) => $this->deriveGroup($p->name))
            ->unique()
            ->sort()
            ->values()
            ->toArray();
    }

    public function refreshPermissions()
    {
        // Force clear all cached properties
        if (property_exists($this, 'computedPropertyCache')) {
            $this->computedPropertyCache = [];
        }
    }

    public function render()
    {
        return view('livewire.admin.configuration.roles.index')
            ->layout('layouts.admin');
    }
}
