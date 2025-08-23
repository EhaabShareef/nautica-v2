<div>

    <div class="space-y-4">
        @foreach($permissionGroups as $groupName => $permissions)
            <div class="backdrop-blur-sm bg-white/10 dark:bg-gray-800/10 rounded-xl p-4 shadow-sm" style="background: color-mix(in oklab, var(--card) 60%, transparent); backdrop-filter: blur(8px);">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-medium text-sm uppercase tracking-wide" style="color: var(--foreground);">{{ $groupName }} <span class="text-xs normal-case" style="color: var(--muted-foreground);">({{ collect($permissions)->pluck('id')->intersect($assigned)->count() }} / {{ count($permissions) }})</span></h3>
                    <div class="flex gap-2">
                        <button type="button" wire:click='grantAll(@js($groupName))' class="btn-secondary px-3 py-1 h-8 text-xs rounded-lg transition-all hover:-translate-y-0.5">Grant All</button>
                        <button type="button" wire:click='revokeAll(@js($groupName))' class="btn-secondary px-3 py-1 h-8 text-xs rounded-lg transition-all hover:-translate-y-0.5">Revoke All</button>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach($permissions as $permission)
                        <label class="flex items-center gap-2">
                            <input type="checkbox" value="{{ $permission->id }}" wire:model="assigned" class="sys-input">
                            <span class="text-sm">{{ $permission->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

</div>
