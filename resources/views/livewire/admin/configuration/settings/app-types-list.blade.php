<div>
    <div class="flex justify-between mb-4">
        <div class="flex gap-2">
            <select wire:model="groupFilter" class="input">
                @foreach($groups as $group)
                    <option value="{{ $group }}">{{ $group }}</option>
                @endforeach
            </select>
            <input type="text" wire:model.debounce.500ms="search" placeholder="Search..." class="input" />
            <label class="flex items-center gap-1 text-sm">
                <input type="checkbox" wire:model="showInactive" /> Show inactive
            </label>
            <label class="flex items-center gap-1 text-sm">
                <input type="checkbox" wire:model="showProtected" /> Show protected
            </label>
        </div>
        <button wire:click="create" class="btn-primary">New Type</button>
    </div>

    <table class="w-full text-sm">
        <thead>
            <tr class="text-left">
                <th class="p-2">Code</th>
                <th class="p-2">Group</th>
                <th class="p-2">Label</th>
                <th class="p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appTypes as $type)
                <tr class="border-t">
                    <td class="p-2">{{ $type->code }}</td>
                    <td class="p-2">{{ $type->group }}</td>
                    <td class="p-2">{{ $type->label }}</td>
                    <td class="p-2">
                        <button wire:click="edit('{{ $type->id }}')" class="text-blue-600 mr-2">Edit</button>
                        <button wire:click="delete('{{ $type->id }}')" class="text-red-600">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $appTypes->links() }}
    </div>
</div>
