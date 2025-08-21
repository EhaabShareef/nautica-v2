<div>
    <div class="flex justify-between mb-4">
        <div class="flex gap-2">
            <input type="text" wire:model.debounce.500ms="search" placeholder="Search..." class="input" />
            <select wire:model="groupFilter" class="input">
                <option value="">All Groups</option>
                @foreach($groups as $group)
                    <option value="{{ $group }}">{{ $group }}</option>
                @endforeach
            </select>
            <label class="flex items-center gap-1 text-sm">
                <input type="checkbox" wire:model="showProtected" /> Show protected
            </label>
        </div>
        <button wire:click="create" class="btn-primary">New Setting</button>
    </div>

    <table class="w-full text-sm">
        <thead>
            <tr class="text-left">
                <th class="p-2">Key</th>
                <th class="p-2">Group</th>
                <th class="p-2">Label</th>
                <th class="p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($settings as $setting)
                <tr class="border-t">
                    <td class="p-2">{{ $setting->key }}</td>
                    <td class="p-2">{{ $setting->group }}</td>
                    <td class="p-2">{{ $setting->label }}</td>
                    <td class="p-2">
                        <button wire:click="edit('{{ $setting->key }}')" class="text-blue-600 mr-2">Edit</button>
                        <button wire:click="delete('{{ $setting->key }}')" class="text-red-600">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $settings->links() }}
    </div>
</div>
