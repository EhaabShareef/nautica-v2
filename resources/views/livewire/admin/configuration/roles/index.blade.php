<div class="config-page p-4 sm:p-6 md:p-8 fade-in">
    <div class="mb-6 md:mb-8">
        <h1 class="text-2xl md:text-3xl font-bold sys-title mb-1">Roles — Permissions Manager</h1>
        <p class="text-sm md:text-base sys-subtitle">Assign and revoke permissions for existing roles</p>
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Select Role</label>
        <select wire:model="selectedRoleId" class="w-full border sys-border rounded-md p-2">
            @foreach($roles as $role)
                <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
            @endforeach
        </select>
    </div>

    @if($selectedRoleId)
        @livewire('admin.configuration.roles.role-editor', ['roleId' => $selectedRoleId], key('role-'.$selectedRoleId))
    @endif
</div>
