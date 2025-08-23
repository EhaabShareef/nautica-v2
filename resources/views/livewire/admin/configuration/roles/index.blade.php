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
            {{-- Mobile: Stack vertically, Desktop: Single row with space between --}}
            <div class="flex flex-col lg:flex-row lg:justify-between gap-3 lg:items-center">
                {{-- Left side: Filters --}}
                <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                    {{-- Role Selection --}}
                    <select wire:model="selectedRoleId" class="form-select h-10 text-sm px-3 py-2 rounded-xl border w-full sm:w-auto" style="background-color: var(--card); border-color: var(--border);">
                        <option value="">Select Role to Manage</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>

                    @if($selectedRoleId)
                        {{-- Search Input --}}
                        <div class="relative flex-1 max-w-md">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <x-heroicon name="magnifying-glass" class="w-4 h-4" style="color: var(--muted-foreground);" />
                            </div>
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search permissions..." class="form-input pl-10 h-10 text-sm">
                        </div>

                        {{-- Group Filter --}}
                        <select wire:model.live="groupFilter" class="form-select h-10 text-sm px-3 py-2 rounded-xl border w-full sm:w-auto" style="background-color: var(--card); border-color: var(--border);">
                            <option value="all">All Groups</option>
                            @foreach($this->groups as $group)
                                <option value="{{ $group }}">{{ $group }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>

                {{-- Right side: Action Buttons --}}
                @if($selectedRoleId)
                    <div class="flex gap-3 justify-end lg:justify-start">
                        <button wire:click="handleApplyChanges" 
                                {{ !$isDirty ? 'disabled' : '' }}
                                class="btn px-3 py-2 h-10 text-sm rounded-xl shadow-sm transition-all hover:-translate-y-0.5 whitespace-nowrap {{ !$isDirty ? 'opacity-50 cursor-not-allowed' : '' }}">Apply Changes</button>
                        <button wire:click="handleDiscardChanges" 
                                {{ !$isDirty ? 'disabled' : '' }}
                                class="btn-secondary px-3 py-2 h-10 text-sm rounded-xl transition-all hover:-translate-y-0.5 whitespace-nowrap {{ !$isDirty ? 'opacity-50 cursor-not-allowed' : '' }}">Discard Changes</button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if($selectedRoleId)
        @livewire('admin.configuration.roles.role-editor', ['selectedRoleId' => $selectedRoleId], key('role-'.$selectedRoleId))
    @endif
</div>
