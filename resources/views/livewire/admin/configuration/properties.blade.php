{{-- resources/views/livewire/admin/configuration/properties.blade.php --}}
<div class="properties-panel space-y-6">
    {{-- Header + Actions --}}
    <div class="flex flex-col gap-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="min-w-0">
                <h3 class="text-lg sm:text-xl font-semibold" style="color: var(--foreground);">Properties Management</h3>
                <p class="text-sm" style="color: var(--muted-foreground);">
                    Manage marina properties and their basic information
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
                    placeholder="Search by name or code..."
                    class="form-input pl-10 h-10 text-sm"
                >
            </div>

            {{-- Toggle Filter --}}
            <button
                wire:click="toggleInactiveFilter"
                class="inline-flex items-center gap-2 px-3 py-2 h-10 text-sm rounded-xl border transition-all hover:-translate-y-0.5
                       {{ $showInactive 
                          ? 'border-orange-300 text-orange-600 hover:bg-orange-50 dark:border-orange-700 dark:text-orange-400 dark:hover:bg-orange-900/10' 
                          : 'border-green-300 text-green-600 hover:bg-green-50 dark:border-green-700 dark:text-green-400 dark:hover:bg-green-900/10' 
                       }}"
                style="background-color: var(--card);"
            >
                <div class="w-2 h-2 rounded-full {{ $showInactive ? 'bg-orange-500' : 'bg-green-500' }}"></div>
                <span class="whitespace-nowrap">{{ $showInactive ? 'Show Inactive' : 'Show Active' }}</span>
            </button>

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

            {{-- Add Property Button --}}
            <button
                wire:click="create"
                class="btn inline-flex items-center gap-2 justify-center px-3 py-2 h-10 text-sm rounded-xl shadow-sm transition-all hover:-translate-y-0.5"
            >
                <x-heroicon name="plus" class="w-4 h-4" />
                <span class="hidden sm:inline">Add Property</span>
                <span class="sm:hidden">Add</span>
            </button>
        </div>
    </div>

    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div class="rounded-xl px-4 py-3 text-sm"
             style="background-color: var(--success); color: var(--success-foreground);">
            {{ session('message') }}
        </div>
    @endif

    {{-- Desktop Table --}}
    <div class="hidden md:block">
        <div class="overflow-x-auto rounded-2xl border"
             style="border-color: var(--border); background: var(--card);">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left"
                        style="background: color-mix(in oklab, var(--muted) 60%, transparent); color: var(--muted-foreground);">
                        <th class="px-4 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">Code</th>
                        <th class="px-4 py-3 font-medium">Timezone</th>
                        <th class="px-4 py-3 font-medium">Currency</th>
                        <th class="px-4 py-3 font-medium">Blocks</th>
                        <th class="px-4 py-3 font-medium">Zones</th>
                        <th class="px-4 py-3 font-medium">Slots</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($properties as $property)
                        <tr class="border-t"
                            style="border-color: var(--border); color: var(--foreground);"
                            wire:key="property-row-{{ $property->id }}">
                            <td class="px-4 py-3 align-middle">
                                <div class="font-medium truncate">{{ $property->name }}</div>
                                @if($property->address)
                                    <div class="text-xs mt-0.5 truncate" style="color: var(--muted-foreground);">
                                        {{ Str::limit($property->address, 60) }}
                                    </div>
                                @endif
                            </td>

                            <td class="px-4 py-3 align-middle whitespace-nowrap">
                                <code class="rounded-md px-2 py-1 text-[0.75rem]"
                                      style="background-color: var(--muted); color: var(--foreground);">
                                    {{ $property->code }}
                                </code>
                            </td>

                            <td class="px-4 py-3 align-middle">
                                @if($property->timezone)
                                    <div class="text-sm">{{ $property->timezone }}</div>
                                @else
                                    <span class="text-sm" style="color: var(--muted-foreground);">Not set</span>
                                @endif
                            </td>

                            <td class="px-4 py-3 align-middle whitespace-nowrap">
                                @if($property->currency)
                                    <span class="font-medium">{{ strtoupper($property->currency) }}</span>
                                @else
                                    <span class="text-sm" style="color: var(--muted-foreground);">Not set</span>
                                @endif
                            </td>

                            <td class="px-4 py-3 align-middle">
                                <span class="badge">
                                    {{ $property->blocks_count ?? $property->blocks->count() }}
                                </span>
                            </td>

                            <td class="px-4 py-3 align-middle">
                                <span class="badge">
                                    {{ $property->zones_count ?? 0 }}
                                </span>
                            </td>

                            <td class="px-4 py-3 align-middle">
                                <span class="badge">
                                    {{ $property->slots_count ?? 0 }}
                                </span>
                            </td>

                            <td class="px-4 py-3 align-middle">
                                <span class="badge {{ $property->is_active ? 'success' : 'secondary' }}">
                                    {{ $property->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <td class="px-4 py-3 align-middle">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        wire:click="edit('{{ $property->id }}')"
                                        class="btn-secondary inline-flex items-center justify-center gap-1.5 px-2.5 py-1.5 text-xs rounded-lg min-w-[4.5rem]"
                                        aria-label="Edit {{ $property->name }}"
                                    >
                                        <x-heroicon name="pencil-square" class="w-4 h-4" />
                                        <span>Edit</span>
                                    </button>

                                    <button
                                        wire:click="delete('{{ $property->id }}')"
                                        class="btn-destructive inline-flex items-center justify-center gap-1.5 px-2.5 py-1.5 text-xs rounded-lg min-w-[4.5rem]"
                                        aria-label="Delete {{ $property->name }}"
                                    >
                                        <x-heroicon name="trash" class="w-4 h-4" />
                                        <span>Delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-10 text-center">
                                <x-heroicon name="building-office-2" class="w-12 h-12 mx-auto mb-3"
                                            style="color: var(--muted-foreground);" />
                                <p class="text-sm" style="color: var(--muted-foreground);">No properties found</p>
                                <p class="text-xs mt-1" style="color: var(--muted-foreground);">
                                    Create your first property to get started
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    {{-- Mobile Card List --}}
    <div class="md:hidden space-y-3">
        @forelse ($properties as $property)
            <div class="rounded-2xl border p-4"
                 style="border-color: var(--border); background: var(--card);"
                 wire:key="property-card-{{ $property->id }}">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="font-semibold truncate" style="color: var(--foreground);">
                            {{ $property->name }}
                        </div>
                        @if($property->address)
                            <div class="text-xs mt-0.5 truncate" style="color: var(--muted-foreground);">
                                {{ Str::limit($property->address, 80) }}
                            </div>
                        @endif
                    </div>
                    <span class="badge {{ $property->is_active ? 'success' : 'secondary' }}">
                        {{ $property->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <dl class="grid grid-cols-2 gap-x-4 gap-y-2 mt-3 text-xs">
                    <div>
                        <dt class="text-[0.7rem]" style="color: var(--muted-foreground);">Code</dt>
                        <dd class="mt-0.5">
                            <code class="rounded px-1.5 py-0.5"
                                  style="background-color: var(--muted); color: var(--foreground);">
                                {{ $property->code }}
                            </code>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-[0.7rem]" style="color: var(--muted-foreground);">Timezone</dt>
                        <dd class="mt-0.5">
                            @if($property->timezone)
                                {{ $property->timezone }}
                            @else
                                <span style="color: var(--muted-foreground);">Not set</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-[0.7rem]" style="color: var(--muted-foreground);">Currency</dt>
                        <dd class="mt-0.5">
                            @if($property->currency)
                                <span class="font-medium">{{ strtoupper($property->currency) }}</span>
                            @else
                                <span style="color: var(--muted-foreground);">Not set</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-[0.7rem]" style="color: var(--muted-foreground);">Blocks</dt>
                        <dd class="mt-0.5">
                            <span class="badge">
                                {{ $property->blocks_count ?? $property->blocks->count() }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-[0.7rem]" style="color: var(--muted-foreground);">Zones</dt>
                        <dd class="mt-0.5">
                            <span class="badge">
                                {{ $property->zones_count ?? 0 }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-[0.7rem]" style="color: var(--muted-foreground);">Slots</dt>
                        <dd class="mt-0.5">
                            <span class="badge">
                                {{ $property->slots_count ?? 0 }}
                            </span>
                        </dd>
                    </div>
                </dl>

                <div class="mt-3 flex items-center justify-end gap-2">
                    <button
                        wire:click="edit('{{ $property->id }}')"
                        class="btn-secondary inline-flex items-center justify-center gap-1.5 px-2.5 py-1.5 text-xs rounded-lg min-w-[4.5rem]"
                    >
                        <x-heroicon name="pencil-square" class="w-4 h-4" />
                        Edit
                    </button>

                    <button
                        wire:click="delete('{{ $property->id }}')"
                        class="btn-destructive inline-flex items-center justify-center gap-1.5 px-2.5 py-1.5 text-xs rounded-lg min-w-[4.5rem]"
                    >
                        <x-heroicon name="trash" class="w-4 h-4" />
                        Delete
                    </button>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border p-6 text-center"
                 style="border-color: var(--border); background: var(--card);">
                <x-heroicon name="building-office-2" class="w-10 h-10 mx-auto mb-2"
                            style="color: var(--muted-foreground);" />
                <p class="text-sm" style="color: var(--muted-foreground);">No properties found</p>
            </div>
        @endforelse

        {{-- Pagination --}}
        <div class="mt-2">
            {{ $properties->links() }}
        </div>
    </div>

</div>
