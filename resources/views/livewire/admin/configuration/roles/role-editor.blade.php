<div wire:key="role-editor-{{ $selectedRoleId }}-{{ $filterGroup }}-{{ $search }}-{{ $renderNonce }}">
    <div class="flex flex-col md:flex-row gap-4 mb-4">
        <div class="flex-1">
            <input type="text" wire:model.debounce.300ms="search" placeholder="Search permissions..." class="w-full border sys-border rounded-md p-2" />
        </div>
        <div>
            <select wire:model="filterGroup" class="border sys-border rounded-md p-2">
                @foreach($groups as $g)
                    <option value="{{ $g == 'All' ? 'all' : $g }}">{{ $g }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <select wire:model="perPage" class="border sys-border rounded-md p-2">
                @foreach([5,10,25,50,100] as $size)
                    <option value="{{ $size }}">{{ $size }} / page</option>
                @endforeach
            </select>
        </div>
    </div>

    @if($pendingAssign || $pendingRevoke)
        <div class="mb-4 p-2 bg-yellow-50 border border-yellow-200 rounded text-sm">Changes not applied</div>
    @endif

    @if($guardMismatch)
        <div class="p-4 bg-red-50 border border-red-200 rounded text-sm">No permissions for guard {{ $guard }}.</div>
    @elseif($permissionsPaginator->total() === 0)
        <div class="p-4 border rounded text-center text-sm">
            <p class="mb-2">No permissions match your filters.</p>
            <button wire:click="clearFilters" class="btn-secondary text-xs">Reset filters</button>
        </div>
    @else
        <div class="space-y-6">
            @foreach($permissionGroups as $groupName => $permissions)
                <div class="border rounded-md p-4" wire:key="perm-group-{{ $groupName }}-{{ $renderNonce }}">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold">{{ $groupName }}</h3>
                        <div class="space-x-2 text-xs">
                            <button type="button" wire:click="grantAll(@js($groupName))" class="btn-secondary">Grant All</button>
                            <button type="button" wire:click="revokeAll(@js($groupName))" class="btn-secondary">Revoke All</button>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                        @foreach($permissions as $permission)
                            @php
                                $isChecked = (in_array($permission->id, $assignedIds) && !in_array($permission->id, $pendingRevoke)) || in_array($permission->id, $pendingAssign);
                            @endphp
                            <label class="flex items-center gap-2" wire:key="perm-{{ $permission->id }}">
                                <input type="checkbox" wire:click="togglePermission({{ $permission->id }})" @checked($isChecked) class="sys-input">
                                <span class="text-sm">{{ $permission->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $permissionsPaginator->links() }}
        </div>

        <div class="mt-6 flex gap-2">
            <button wire:click="apply" class="btn-primary">Apply Changes</button>
            <button wire:click="discard" class="btn-secondary">Discard</button>
        </div>
    @endif
</div>
