<div class="space-y-6">
    {{-- Header + Actions --}}
    <div class="flex flex-col gap-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="min-w-0">
                <h3 class="text-lg sm:text-xl font-semibold" style="color: var(--foreground);">Roles & Permissions Management</h3>
                <p class="text-sm" style="color: var(--muted-foreground);">
                    Assign and revoke permissions for existing roles
                </p>
            </div>
        </div>

        {{-- Search and Filters with Action Buttons - ALL IN SAME ROW --}}

        <div class="flex flex-col gap-3">
            {{-- Role Selection --}}
            <select wire:model="selectedRoleId" class="form-select h-10 text-sm px-3 py-2 rounded-xl border w-full sm:w-auto" style="background-color: var(--card); border-color: var(--border);">
                <option value="">Select Role to Manage</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @if($selectedRoleId)
        @livewire('admin.configuration.roles.role-editor', ['selectedRoleId' => $selectedRoleId], key('role-'.$selectedRoleId))
    @endif
</div>
