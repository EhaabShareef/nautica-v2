<div class="space-y-6">
    {{-- Header + Actions --}}
    <div class="flex flex-col gap-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="min-w-0">
                <h3 class="text-lg sm:text-xl font-semibold" style="color: var(--foreground);">Application Types</h3>
                <p class="text-sm" style="color: var(--muted-foreground);">
                    Manage application type definitions and their configurations
                </p>
            </div>
        </div>

        {{-- Search and Filters --}}
        <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
            {{-- Search Input --}}
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-heroicon name="magnifying-glass" class="w-4 h-4" style="color: var(--muted-foreground);" />
                </div>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search types..."
                    class="form-input pl-10 h-10 text-sm"
                >
            </div>

            {{-- Group Filter --}}
            <select 
                wire:model.live="groupFilter"
                class="form-select h-10 text-sm px-3 py-2 rounded-xl border"
                style="background-color: var(--card); border-color: var(--border);"
            >
                <option value="">All Groups</option>
                @foreach($groups as $group)
                    <option value="{{ $group }}">{{ $group }}</option>
                @endforeach
            </select>

            {{-- Inactive Filter --}}
            <label class="inline-flex items-center gap-2 px-3 py-2 h-10 text-sm rounded-xl border cursor-pointer transition-all hover:-translate-y-0.5"
                   style="background-color: var(--card); border-color: var(--border);">
                <input type="checkbox" wire:model.live="showInactive" class="form-checkbox" />
                <span class="whitespace-nowrap">Show Inactive</span>
            </label>

            {{-- Protected Filter --}}
            <label class="inline-flex items-center gap-2 px-3 py-2 h-10 text-sm rounded-xl border cursor-pointer transition-all hover:-translate-y-0.5"
                   style="background-color: var(--card); border-color: var(--border);">
                <input type="checkbox" wire:model.live="showProtected" class="form-checkbox" />
                <span class="whitespace-nowrap">Show Protected</span>
            </label>

            {{-- Per Page Selector --}}
            <select 
                wire:model.live="perPage"
                class="form-select h-10 text-sm px-3 py-2 rounded-xl border"
                style="background-color: var(--card); border-color: var(--border);"
            >
                @foreach($perPageOptions as $option)
                    <option value="{{ $option }}">{{ $option }} per page</option>
                @endforeach
            </select>

            {{-- Add Type Button --}}
            <button
                wire:click="create"
                class="btn inline-flex items-center gap-2 justify-center px-3 py-2 h-10 text-sm rounded-xl shadow-sm transition-all hover:-translate-y-0.5"
            >
                <x-heroicon name="plus" class="w-4 h-4" />
                <span class="hidden sm:inline">Add Type</span>
                <span class="sm:hidden">Add</span>
            </button>
        </div>
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block">
        <div class="overflow-x-auto rounded-2xl border"
             style="border-color: var(--border); background: var(--card);">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left"
                        style="background: color-mix(in oklab, var(--muted) 60%, transparent); color: var(--muted-foreground);">
                        <th class="px-4 py-3 font-medium">Code</th>
                        <th class="px-4 py-3 font-medium">Group</th>
                        <th class="px-4 py-3 font-medium">Label</th>
                        <th class="px-4 py-3 font-medium">Sort Order</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appTypes as $type)
                        <tr class="align-middle" style="border-top: 1px solid var(--border);">
                            <td class="px-4 py-3">
                                <div class="font-mono text-xs">{{ $type->code }}</div>
                            </td>
                            <td class="px-4 py-3">
                                @if($type->group)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                          style="background-color: var(--muted); color: var(--muted-foreground);">
                                        {{ $type->group }}
                                    </span>
                                @else
                                    <span style="color: var(--muted-foreground);">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $type->label ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $type->sort_order ?? 0 }}</td>
                            <td class="px-4 py-3">
                                <span class="badge {{ $type->is_active ? 'success' : 'secondary' }}">
                                    {{ $type->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                @if($type->is_protected)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200 ml-1">
                                        Protected
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        wire:click="edit('{{ $type->id }}')"
                                        class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-lg transition-colors"
                                        style="color: var(--primary); background: var(--primary-foreground);"
                                    >
                                        <x-heroicon name="pencil" class="w-3 h-3" />
                                        Edit
                                    </button>
                                    @if(!$type->is_protected)
                                        <button
                                            wire:click="delete('{{ $type->id }}')"
                                            class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-lg transition-colors text-red-600 hover:bg-red-50 dark:hover:bg-red-900/10"
                                        >
                                            <x-heroicon name="trash" class="w-3 h-3" />
                                            Delete
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center" style="color: var(--muted-foreground);">
                                <div class="flex flex-col items-center gap-2">
                                    <x-heroicon name="squares-2x2" class="w-8 h-8" />
                                    <p>No types found</p>
                                    <button wire:click="create" class="btn btn-sm">Add your first type</button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile Card List --}}
    <div class="md:hidden space-y-3">
        @forelse ($appTypes as $type)
            <div class="rounded-2xl border p-4"
                 style="border-color: var(--border); background: var(--card);"
                 wire:key="type-card-{{ $type->id }}">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="font-mono text-xs" style="color: var(--foreground);">
                            {{ $type->code }}
                        </div>
                        @if($type->label)
                            <div class="text-sm mt-0.5 truncate" style="color: var(--muted-foreground);">
                                {{ $type->label }}
                            </div>
                        @endif
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="badge {{ $type->is_active ? 'success' : 'secondary' }}">
                            {{ $type->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        @if($type->is_protected)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                                Protected
                            </span>
                        @endif
                    </div>
                </div>

                <dl class="grid grid-cols-2 gap-x-4 gap-y-2 mt-3 text-xs">
                    @if($type->group)
                        <div>
                            <dt class="text-[0.7rem]" style="color: var(--muted-foreground);">Group</dt>
                            <dd class="mt-0.5">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                      style="background-color: var(--muted); color: var(--muted-foreground);">
                                    {{ $type->group }}
                                </span>
                            </dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-[0.7rem]" style="color: var(--muted-foreground);">Sort Order</dt>
                        <dd class="mt-0.5">{{ $type->sort_order ?? 0 }}</dd>
                    </div>
                </dl>

                <div class="mt-3 flex items-center justify-end gap-2">
                    <button
                        wire:click="edit('{{ $type->id }}')"
                        class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-lg transition-colors"
                        style="color: var(--primary); background: var(--primary-foreground);"
                    >
                        <x-heroicon name="pencil" class="w-3 h-3" />
                        Edit
                    </button>
                    @if(!$type->is_protected)
                        <button
                            wire:click="delete('{{ $type->id }}')"
                            class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-lg transition-colors text-red-600 hover:bg-red-50 dark:hover:bg-red-900/10"
                        >
                            <x-heroicon name="trash" class="w-3 h-3" />
                            Delete
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="rounded-2xl border p-6 text-center"
                 style="border-color: var(--border); background: var(--card);">
                <x-heroicon name="squares-2x2" class="w-10 h-10 mx-auto mb-2"
                            style="color: var(--muted-foreground);" />
                <p class="text-sm" style="color: var(--muted-foreground);">No types found</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="flex justify-center">
        {{ $appTypes->links() }}
    </div>
</div>
