<div>
    <div class="flex flex-col md:flex-row gap-4 mb-4">
        <div class="flex-1">
            <input type="text" wire:model.debounce.500ms="search" placeholder="Search permissions..." class="w-full border sys-border rounded-md p-2" />
        </div>
        <div>
            <select wire:model="group" class="border sys-border rounded-md p-2">
                <option value="all">All groups</option>
                @foreach($groups as $g)
                    <option value="{{ $g }}">{{ $g }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="space-y-6">
        @foreach($permissionGroups as $groupName => $permissions)
            <div class="border rounded-md p-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold">{{ $groupName }} <span class="text-sm text-gray-500">({{ collect($permissions)->pluck('id')->intersect($assigned)->count() }} / {{ count($permissions) }})</span></h3>
                    <div class="space-x-2">
                        <button type="button" wire:click="grantAll('{{ $groupName }}')" class="btn-secondary text-xs">Grant All</button>
                        <button type="button" wire:click="revokeAll('{{ $groupName }}')" class="btn-secondary text-xs">Revoke All</button>
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

    <div class="mt-6 flex gap-2">
        <button wire:click="apply" class="btn-primary" @disabled(! $dirty)>Apply Changes</button>
        <button wire:click="discard" class="btn-secondary" @disabled(! $dirty)>Discard</button>
    </div>
</div>
