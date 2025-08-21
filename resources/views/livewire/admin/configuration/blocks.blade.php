{{-- resources/views/livewire/admin/configuration/blocks.blade.php --}}
<div class="blocks-panel space-y-6">
    <div class="flex flex-col gap-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="min-w-0">
                <h3 class="text-lg sm:text-xl font-semibold" style="color: var(--foreground);">Blocks Management</h3>
                <p class="text-sm" style="color: var(--muted-foreground);">Manage property blocks and their locations</p>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-heroicon name="magnifying-glass" class="w-4 h-4" style="color: var(--muted-foreground);" />
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name or code..." class="form-input pl-10 h-10 text-sm">
            </div>
            <button wire:click="toggleInactiveFilter" class="inline-flex items-center gap-2 px-3 py-2 h-10 text-sm rounded-xl border transition-all hover:-translate-y-0.5 {{ $showInactive ? 'border-orange-300 text-orange-600 hover:bg-orange-50 dark:border-orange-700 dark:text-orange-400 dark:hover:bg-orange-900/10' : 'border-green-300 text-green-600 hover:bg-green-50 dark:border-green-700 dark:text-green-400 dark:hover:bg-green-900/10' }}" style="background-color: var(--card);">
                <div class="w-2 h-2 rounded-full {{ $showInactive ? 'bg-orange-500' : 'bg-green-500' }}"></div>
                <span class="whitespace-nowrap">{{ $showInactive ? 'Show Inactive' : 'Show Active' }}</span>
            </button>
            <button wire:click="create" class="btn inline-flex items-center gap-2 justify-center px-3 py-2 h-10 text-sm rounded-xl shadow-sm transition-all hover:-translate-y-0.5">
                <x-heroicon name="plus" class="w-4 h-4" />
                <span class="hidden sm:inline">Add Block</span>
                <span class="sm:hidden">Add</span>
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="rounded-xl px-4 py-3 text-sm" style="background-color: var(--success); color: var(--success-foreground);">
            {{ session('message') }}
        </div>
    @endif

    <div class="hidden md:block">
        <div class="overflow-x-auto rounded-2xl border" style="border-color: var(--border); background: var(--card);">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left" style="background: color-mix(in oklab, var(--muted) 60%, transparent); color: var(--muted-foreground);">
                        <th class="px-4 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">Code</th>
                        <th class="px-4 py-3 font-medium">Property</th>
                        <th class="px-4 py-3 font-medium">Zones</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blocks as $block)
                        <tr class="border-t" style="border-color: var(--border); color: var(--foreground);" wire:key="block-row-{{ $block->id }}">
                            <td class="px-4 py-3 align-middle">
                                <div class="font-medium truncate">{{ $block->name }}</div>
                                @if($block->location)
                                    <div class="text-xs mt-0.5 truncate" style="color: var(--muted-foreground);">{{ $block->location }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 align-middle whitespace-nowrap">
                                <code class="rounded-md px-2 py-1 text-[0.75rem]" style="background-color: var(--muted); color: var(--foreground);">{{ $block->code }}</code>
                            </td>
                            <td class="px-4 py-3 align-middle whitespace-nowrap">{{ $block->property->name }}</td>
                            <td class="px-4 py-3 align-middle"><span class="badge">{{ $block->zones_count }}</span></td>
                            <td class="px-4 py-3 align-middle"><span class="badge {{ $block->is_active ? 'success' : 'secondary' }}">{{ $block->is_active ? 'Active' : 'Inactive' }}</span></td>
                            <td class="px-4 py-3 align-middle">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="edit('{{ $block->id }}')" class="btn-secondary inline-flex items-center justify-center gap-1.5 px-2.5 py-1.5 text-xs rounded-lg min-w-[4.5rem]" aria-label="Edit {{ $block->name }}">
                                        <x-heroicon name="pencil" class="w-4 h-4" />
                                        <span>Edit</span>
                                    </button>
                                    <button wire:click="delete('{{ $block->id }}')" class="btn-destructive inline-flex items-center justify-center gap-1.5 px-2.5 py-1.5 text-xs rounded-lg min-w-[4.5rem]" aria-label="Delete {{ $block->name }}">
                                        <x-heroicon name="trash" class="w-4 h-4" />
                                        <span>Delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-sm" style="color: var(--muted-foreground);">No blocks found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="md:hidden space-y-3">
        @forelse($blocks as $block)
            <div class="p-4 rounded-xl border" style="border-color: var(--border); background: var(--card);" wire:key="block-card-{{ $block->id }}">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-semibold" style="color: var(--foreground);">{{ $block->name }}</h4>
                    <span class="badge {{ $block->is_active ? 'success' : 'secondary' }}">{{ $block->is_active ? 'Active' : 'Inactive' }}</span>
                </div>
                <div class="text-sm mb-2" style="color: var(--muted-foreground);">
                    <div>Code: <code class="px-1 rounded" style="background:var(--muted); color:var(--foreground);">{{ $block->code }}</code></div>
                    <div>Property: {{ $block->property->name }}</div>
                    <div>Zones: {{ $block->zones_count }}</div>
                    @if($block->location)
                        <div>Location: {{ $block->location }}</div>
                    @endif
                </div>
                <div class="flex items-center justify-end gap-2">
                    <button wire:click="edit('{{ $block->id }}')" class="btn-secondary inline-flex items-center justify-center gap-1.5 px-2.5 py-1.5 text-xs rounded-lg min-w-[4.5rem]">
                        <x-heroicon name="pencil" class="w-4 h-4" />
                        <span>Edit</span>
                    </button>
                    <button wire:click="delete('{{ $block->id }}')" class="btn-destructive inline-flex items-center justify-center gap-1.5 px-2.5 py-1.5 text-xs rounded-lg min-w-[4.5rem]">
                        <x-heroicon name="trash" class="w-4 h-4" />
                        <span>Delete</span>
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center text-sm" style="color: var(--muted-foreground);">No blocks found.</div>
        @endforelse
    </div>

    <div>
        {{ $blocks->links() }}
    </div>
</div>
