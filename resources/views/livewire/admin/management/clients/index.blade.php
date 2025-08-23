<div>
<div class="p-4 sm:p-6 md:p-8 fade-in">
    {{-- Header Section --}}
    <div class="mb-6 md:mb-8">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div class="min-w-0">
                <h1 class="text-2xl md:text-3xl font-bold text-foreground mb-1 flex items-center gap-3">
                    <div class="p-3 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl shadow-lg">
                        <x-heroicon name="user-group" class="w-6 h-6 text-white" />
                    </div>
                    Client Management
                </h1>
                <p class="text-sm md:text-base text-muted-foreground">Manage client accounts, registrations, and access</p>
            </div>
            
            {{-- Back Button --}}
            <a href="{{ route('admin.dashboard') }}" 
               class="btn-secondary inline-flex items-center gap-2 px-4 py-2 text-sm rounded-xl shadow-sm transition-all hover:-translate-y-0.5 whitespace-nowrap self-start">
                <x-heroicon name="arrow-left" class="w-4 h-4" />
                <span class="hidden sm:inline">Back to Dashboard</span>
                <span class="sm:hidden">Back</span>
            </a>
        </div>

        {{-- Stats Cards --}}
        @livewire('admin.management.clients.components.stats-cards')
    </div>

    {{-- Main Content Card --}}
    <div class="card slide-up" style="animation-delay: 0.2s;">
        {{-- Action Bar --}}
        @livewire('admin.management.clients.components.search-bar', [
            'search' => $search,
            'statusFilter' => $statusFilter,
            'perPage' => $perPage
        ])

        {{-- Table Container --}}
        <div class="overflow-x-auto rounded-2xl border" style="border-color: var(--border); background: var(--card);">
            <table class="min-w-full text-sm">
                <thead style="background: color-mix(in oklab, var(--muted) 60%, transparent); color: var(--muted-foreground);">
                    <tr class="border-b" style="border-color: var(--border);">
                        <th class="table-header cursor-pointer hover:bg-muted/50 transition-colors" wire:click="sortBy('name')">
                            <div class="flex items-center gap-2">
                                <span>Name</span>
                                @if($sortBy === 'name')
                                    <x-heroicon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-4 h-4 text-primary" />
                                @endif
                            </div>
                        </th>
                        <th class="table-header cursor-pointer hover:bg-muted/50 transition-colors" wire:click="sortBy('email')">
                            <div class="flex items-center gap-2">
                                <span>Contact</span>
                                @if($sortBy === 'email')
                                    <x-heroicon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-4 h-4 text-primary" />
                                @endif
                            </div>
                        </th>
                        <th class="table-header">ID Card</th>
                        <th class="table-header">Vessels</th>
                        <th class="table-header cursor-pointer hover:bg-muted/50 transition-colors" wire:click="sortBy('is_active')">
                            <div class="flex items-center gap-2">
                                <span>Status</span>
                                @if($sortBy === 'is_active')
                                    <x-heroicon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-4 h-4 text-primary" />
                                @endif
                            </div>
                        </th>
                        <th class="table-header cursor-pointer hover:bg-muted/50 transition-colors" wire:click="sortBy('created_at')">
                            <div class="flex items-center gap-2">
                                <span>Joined</span>
                                @if($sortBy === 'created_at')
                                    <x-heroicon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-4 h-4 text-primary" />
                                @endif
                            </div>
                        </th>
                        <th class="table-header text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 0; $i < 3; $i++)
                        <tr class="border-t animate-pulse" style="border-color: var(--border);" wire:loading>
                            @for($j = 0; $j < 7; $j++)
                                <td class="px-4 py-3">
                                    <div class="h-4 rounded bg-muted"></div>
                                </td>
                            @endfor
                        </tr>
                    @endfor

                    @forelse($clients as $client)
                        <tr class="table-row group" wire:loading.remove>
                            {{-- Name Column --}}
                            <td class="table-cell">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                        {{ substr($client->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-foreground">{{ $client->name }}</div>
                                        @if($client->last_login_at)
                                            <div class="text-xs text-muted-foreground">
                                                Last login: {{ $client->last_login_at->diffForHumans() }}
                                            </div>
                                        @else
                                            <div class="text-xs text-muted-foreground">Never logged in</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Contact Column --}}
                            <td class="table-cell">
                                <div>
                                    <div class="text-sm text-foreground">{{ $client->email }}</div>
                                    @if($client->phone)
                                        <div class="text-xs text-muted-foreground">{{ $client->phone }}</div>
                                    @endif
                                </div>
                            </td>

                            {{-- ID Card Column --}}
                            <td class="table-cell">
                                @if($client->id_card)
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                        {{ $client->id_card }}
                                    </span>
                                @else
                                    <span class="text-xs text-muted-foreground">Not provided</span>
                                @endif
                                @if(!$client->id_card || !$client->phone)
                                    <span class="status-badge status-warning mt-1">Incomplete</span>
                                @endif
                            </td>

                            {{-- Vessels Column --}}
                            <td class="table-cell">
                                <div class="flex items-center gap-1">
                                    <x-heroicon name="rocket-launch" class="w-4 h-4 text-muted-foreground" />
                                    <span class="text-sm font-medium">{{ $client->vessels_count }}</span>
                                </div>
                            </td>

                            {{-- Status Column --}}
                            <td class="table-cell">
                                @if($client->is_active)
                                    <span class="status-badge status-active">
                                        <x-heroicon name="check-circle" class="w-3 h-3" />
                                        Active
                                    </span>
                                @else
                                    <span class="status-badge status-inactive">
                                        <x-heroicon name="x-circle" class="w-3 h-3" />
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            {{-- Joined Column --}}
                            <td class="table-cell">
                                <div class="text-sm text-muted-foreground">
                                    {{ $client->created_at->format('M j, Y') }}
                                </div>
                            </td>

                            {{-- Actions Column --}}
                            <td class="table-cell text-right">
                                <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button wire:click="editClient({{ $client->id }})"
                                            class="action-btn action-btn-edit"
                                            title="Edit Client"
                                            wire:loading.attr="disabled" wire:target="editClient">
                                        <x-heroicon name="pencil" class="w-4 h-4" />
                                    </button>
                                    <button wire:click="deleteClient({{ $client->id }})"
                                            class="action-btn action-btn-delete"
                                            title="Delete Client"
                                            wire:loading.attr="disabled" wire:target="deleteClient">
                                        <x-heroicon name="trash" class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr wire:loading.remove>
                            <td colspan="7" class="text-center py-12">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                                        <x-heroicon name="user-group" class="w-8 h-8 text-gray-400" />
                                    </div>
                                    <div>
                                        <p class="text-lg font-medium text-foreground">No clients found</p>
                                        <p class="text-sm text-muted-foreground mt-1">
                                            @if($search || $statusFilter !== 'all')
                                                No clients match your current filters.
                                            @else
                                                Get started by creating your first client.
                                            @endif
                                        </p>
                                    </div>
                                    @if(!$search && $statusFilter === 'all')
                                        <button wire:click="createClient" class="btn mt-2">
                                            <x-heroicon name="plus" class="w-4 h-4" />
                                            Add First Client
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($clients->hasPages())
            <div class="pagination-wrapper mt-6 pt-6 border-t border-border">
                {{ $clients->links() }}
            </div>
        @endif
    </div>

    {{-- Child Components --}}
    @livewire('admin.management.clients.client-form')
    @livewire('admin.management.clients.client-delete')
</div>

{{-- Toast Notifications --}}
<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('clientSaved', (event) => {
        // You can add toast notification here
        console.log(event.message);
    });

    Livewire.on('clientDeleted', (event) => {
        // You can add toast notification here  
        console.log(event.message);
    });
});
</script>
</div>
