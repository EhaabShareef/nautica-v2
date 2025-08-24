<div>
<div class="p-4 sm:p-6 md:p-8 fade-in">
    {{-- Header Section --}}
    <div class="mb-6 md:mb-8">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div class="min-w-0">
                <h1 class="text-2xl md:text-3xl font-bold text-foreground mb-1 flex items-center gap-3">
                    <div class="p-3 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl shadow-lg">
                        <x-heroicon name="rocket-launch" class="w-6 h-6 text-white" />
                    </div>
                    Vessel Management
                </h1>
                <p class="text-sm md:text-base text-muted-foreground">Manage vessel registrations, owners, and rentals</p>
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
        <div class="hidden md:grid grid-cols-2 lg:grid-cols-5 gap-4 mt-6">
            @php
                $stats = app(\App\Services\VesselService::class)->getVesselStats();
            @endphp
            <div class="bg-card rounded-xl p-4 border border-border">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                        <x-heroicon name="rocket-launch" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-foreground">{{ $stats['total'] }}</div>
                        <div class="text-xs text-muted-foreground">Total Vessels</div>
                    </div>
                </div>
            </div>
            <div class="bg-card rounded-xl p-4 border border-border">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-green-100 dark:bg-green-900/20 rounded-lg">
                        <x-heroicon name="check-circle" class="w-5 h-5 text-green-600 dark:text-green-400" />
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-foreground">{{ $stats['active'] }}</div>
                        <div class="text-xs text-muted-foreground">Active</div>
                    </div>
                </div>
            </div>
            <div class="bg-card rounded-xl p-4 border border-border">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-gray-100 dark:bg-gray-900/20 rounded-lg">
                        <x-heroicon name="pause-circle" class="w-5 h-5 text-gray-600 dark:text-gray-400" />
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-foreground">{{ $stats['inactive'] }}</div>
                        <div class="text-xs text-muted-foreground">Inactive</div>
                    </div>
                </div>
            </div>
            <div class="bg-card rounded-xl p-4 border border-border">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900/20 rounded-lg">
                        <x-heroicon name="user" class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-foreground">{{ $stats['with_renter'] }}</div>
                        <div class="text-xs text-muted-foreground">With Renter</div>
                    </div>
                </div>
            </div>
            <div class="bg-card rounded-xl p-4 border border-border">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-orange-100 dark:bg-orange-900/20 rounded-lg">
                        <x-heroicon name="user-minus" class="w-5 h-5 text-orange-600 dark:text-orange-400" />
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-foreground">{{ $stats['without_renter'] }}</div>
                        <div class="text-xs text-muted-foreground">Available</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Card --}}
    <div class="card slide-up" style="animation-delay: 0.2s;">
        {{-- Action Bar --}}
        <div class="p-4 sm:p-6 border-b border-border">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                {{-- Search and Filters --}}
                <div class="flex flex-col sm:flex-row gap-3 flex-1 max-w-2xl">
                    {{-- Search Input --}}
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <x-heroicon name="magnifying-glass" class="w-4 h-4 text-muted-foreground" />
                        </div>
                        <input type="text" 
                               wire:model.live.debounce.300ms="search"
                               placeholder="Search vessels..." 
                               class="input pl-10 w-full">
                    </div>

                    {{-- Filter Button --}}
                    <button wire:click="toggleFilters" 
                            class="btn-secondary inline-flex items-center gap-2 px-4 py-2.5 whitespace-nowrap">
                        <x-heroicon name="funnel" class="w-4 h-4" />
                        Filters
                        @if($ownerFilter !== 'all' || $renterFilter !== 'all' || $statusFilter !== 'all' || $typeFilter !== 'all')
                            <span class="bg-primary text-primary-foreground rounded-full w-5 h-5 text-xs flex items-center justify-center">
                                {{ collect([$ownerFilter !== 'all', $renterFilter !== 'all', $statusFilter !== 'all', $typeFilter !== 'all'])->filter()->count() }}
                            </span>
                        @endif
                    </button>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3">
                    {{-- Per Page Selector --}}
                    <div class="flex items-center gap-2 text-sm text-muted-foreground">
                        <span class="hidden sm:inline">Show:</span>
                        <select wire:model.live="perPage" class="input-sm py-1">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>

                    {{-- Add Vessel Button --}}
                    @can('create', \App\Models\Vessel::class)
                        <button wire:click="createVessel" 
                                class="btn inline-flex items-center gap-2 px-4 py-2.5">
                            <x-heroicon name="plus" class="w-4 h-4" />
                            <span class="hidden sm:inline">Add Vessel</span>
                            <span class="sm:hidden">Add</span>
                        </button>
                    @endcan
                </div>
            </div>

            {{-- Filters Panel --}}
            @if($showFilters)
                <div class="mt-4 p-4 bg-muted/30 rounded-xl border border-border">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- Owner Filter --}}
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Owner</label>
                            <select wire:model.live="ownerFilter" class="input-sm w-full">
                                <option value="all">All Owners</option>
                                @foreach($owners as $owner)
                                    <option value="{{ $owner->id }}">{{ $owner->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Renter Filter --}}
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Renter</label>
                            <select wire:model.live="renterFilter" class="input-sm w-full">
                                <option value="all">All</option>
                                <option value="none">No Renter</option>
                                @foreach($renters as $renter)
                                    <option value="{{ $renter->id }}">{{ $renter->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status Filter --}}
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Status</label>
                            <select wire:model.live="statusFilter" class="input-sm w-full">
                                <option value="all">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        {{-- Type Filter --}}
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Type</label>
                            <select wire:model.live="typeFilter" class="input-sm w-full">
                                <option value="all">All Types</option>
                                @foreach($vesselTypes as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Table Container --}}
        <div class="overflow-x-auto rounded-b-2xl" style="background: var(--card);">
            <table class="min-w-full text-sm">
                <thead style="background: color-mix(in oklab, var(--muted) 60%, transparent); color: var(--muted-foreground);">
                    <tr class="border-b" style="border-color: var(--border);">
                        <th scope="col" class="table-header cursor-pointer hover:bg-muted/50 transition-colors"
                            wire:click="sortBy('name')" 
                            aria-sort="{{ $sortBy === 'name' ? ($sortDirection === 'asc' ? 'ascending' : 'descending') : 'none' }}">
                            <div class="flex items-center gap-2">
                                <span>Vessel</span>
                                @if($sortBy === 'name')
                                    <x-heroicon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-4 h-4 text-primary" />
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="table-header">Owner</th>
                        <th scope="col" class="table-header">Renter</th>
                        <th scope="col" class="table-header cursor-pointer hover:bg-muted/50 transition-colors"
                            wire:click="sortBy('type')">
                            <div class="flex items-center gap-2">
                                <span>Type</span>
                                @if($sortBy === 'type')
                                    <x-heroicon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-4 h-4 text-primary" />
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="table-header">Dimensions</th>
                        <th scope="col" class="table-header cursor-pointer hover:bg-muted/50 transition-colors"
                            wire:click="sortBy('is_active')">
                            <div class="flex items-center gap-2">
                                <span>Status</span>
                                @if($sortBy === 'is_active')
                                    <x-heroicon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-4 h-4 text-primary" />
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="table-header cursor-pointer hover:bg-muted/50 transition-colors"
                            wire:click="sortBy('created_at')">
                            <div class="flex items-center gap-2">
                                <span>Registered</span>
                                @if($sortBy === 'created_at')
                                    <x-heroicon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-4 h-4 text-primary" />
                                @endif
                            </div>
                        </th>
                        <th class="table-header text-right">
                            <span class="flex justify-end">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 0; $i < 3; $i++)
                        <tr class="border-t animate-pulse" style="border-color: var(--border);" wire:loading>
                            @for($j = 0; $j < 8; $j++)
                                <td class="px-4 py-3">
                                    <div class="h-4 rounded bg-muted"></div>
                                </td>
                            @endfor
                        </tr>
                    @endfor

                    @forelse($vessels as $vessel)
                        <tr class="table-row group" wire:loading.remove>
                            {{-- Vessel Column --}}
                            <td class="table-cell">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                        {{ substr($vessel->name, 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-medium text-foreground truncate">{{ $vessel->name }}</div>
                                        <div class="text-xs text-muted-foreground truncate">{{ $vessel->registration_number }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Owner Column --}}
                            <td class="table-cell">
                                <div class="flex items-center gap-2">
                                    <div class="flex-shrink-0 w-6 h-6 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                        {{ substr($vessel->owner->name, 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-sm text-foreground truncate">{{ $vessel->owner->name }}</div>
                                        @if($vessel->owner->id_card)
                                            <div class="text-xs text-muted-foreground truncate">{{ $vessel->owner->id_card }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Renter Column --}}
                            <td class="table-cell">
                                @if($vessel->renter)
                                    <div class="flex items-center gap-2">
                                        <div class="flex-shrink-0 w-6 h-6 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                            {{ substr($vessel->renter->name, 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm text-foreground truncate">{{ $vessel->renter->name }}</div>
                                            @if($vessel->renter->id_card)
                                                <div class="text-xs text-muted-foreground truncate">{{ $vessel->renter->id_card }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <span class="text-xs text-muted-foreground">No renter</span>
                                @endif
                            </td>

                            {{-- Type Column --}}
                            <td class="table-cell">
                                @if($vessel->type)
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                        {{ $vessel->type_display }}
                                    </span>
                                @else
                                    <span class="text-xs text-muted-foreground">Not specified</span>
                                @endif
                            </td>

                            {{-- Dimensions Column --}}
                            <td class="table-cell">
                                <div class="text-xs text-muted-foreground">
                                    @php
                                        $dimensions = collect([
                                            $vessel->length ? "L: {$vessel->length}m" : null,
                                            $vessel->width ? "W: {$vessel->width}m" : null,
                                            $vessel->draft ? "D: {$vessel->draft}m" : null,
                                        ])->filter()->take(2);
                                    @endphp
                                    @if($dimensions->count() > 0)
                                        {{ $dimensions->join(', ') }}
                                    @else
                                        Not specified
                                    @endif
                                </div>
                            </td>

                            {{-- Status Column --}}
                            <td class="table-cell">
                                @if($vessel->is_active)
                                    <span class="status-badge status-active">
                                        <x-heroicon name="check-circle" class="w-3 h-3" />
                                        Active
                                    </span>
                                @else
                                    <span class="status-badge status-inactive">
                                        <x-heroicon name="pause-circle" class="w-3 h-3" />
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            {{-- Registered Column --}}
                            <td class="table-cell">
                                <div class="text-sm text-muted-foreground">
                                    {{ $vessel->created_at->format('M j, Y') }}
                                </div>
                            </td>

                            {{-- Actions Column --}}
                            <td class="table-cell text-right">
                                <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    @can('view', $vessel)
                                        <button wire:click="editVessel('{{ $vessel->id }}')"
                                                class="action-btn action-btn-edit"
                                                title="Edit Vessel">
                                            <x-heroicon name="pencil-square" class="w-4 h-4" />
                                        </button>
                                    @endcan
                                    
                                    @can('assignRenter', $vessel)
                                        @if(!$vessel->renter_client_id)
                                            <button wire:click="openQuickAssign('{{ $vessel->id }}')"
                                                    class="action-btn hover:bg-purple-100 dark:hover:bg-purple-900/20 hover:text-purple-600"
                                                    title="Quick Assign Renter">
                                                <x-heroicon name="user-plus" class="w-4 h-4" />
                                            </button>
                                        @else
                                            <button wire:click="openQuickAssign('{{ $vessel->id }}')"
                                                    class="action-btn hover:bg-blue-100 dark:hover:bg-blue-900/20 hover:text-blue-600"
                                                    title="Change Renter">
                                                <x-heroicon name="arrow-path-rounded-square" class="w-4 h-4" />
                                            </button>
                                        @endif
                                    @endcan
                                    
                                    @can('toggleStatus', $vessel)
                                        @if($vessel->is_active)
                                            <button wire:click="confirmDeactivate('{{ $vessel->id }}')"
                                                    class="action-btn hover:bg-yellow-100 dark:hover:bg-yellow-900/20 hover:text-yellow-600"
                                                    title="Deactivate Vessel">
                                                <x-heroicon name="pause-circle" class="w-4 h-4" />
                                            </button>
                                        @else
                                            <button wire:click="toggleVesselStatus('{{ $vessel->id }}')"
                                                    class="action-btn hover:bg-green-100 dark:hover:bg-green-900/20 hover:text-green-600"
                                                    title="Activate Vessel">
                                                <x-heroicon name="play-circle" class="w-4 h-4" />
                                            </button>
                                        @endif
                                    @endcan
                                    
                                    @can('delete', $vessel)
                                        <button wire:click="deleteVessel('{{ $vessel->id }}')"
                                                class="action-btn action-btn-delete"
                                                title="Delete Vessel">
                                            <x-heroicon name="trash" class="w-4 h-4" />
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr wire:loading.remove>
                            <td colspan="8" class="text-center py-12">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                                        <x-heroicon name="rocket-launch" class="w-8 h-8 text-gray-400" />
                                    </div>
                                    <div>
                                        <p class="text-lg font-medium text-foreground">No vessels found</p>
                                        <p class="text-sm text-muted-foreground mt-1">
                                            @if($search || $ownerFilter !== 'all' || $renterFilter !== 'all' || $statusFilter !== 'all' || $typeFilter !== 'all')
                                                No vessels match your current filters.
                                            @else
                                                Get started by registering your first vessel.
                                            @endif
                                        </p>
                                    </div>
                                    @if(!$search && $ownerFilter === 'all' && $renterFilter === 'all' && $statusFilter === 'all' && $typeFilter === 'all')
                                        @can('create', \App\Models\Vessel::class)
                                            <button wire:click="createVessel" class="btn mt-2">
                                                <x-heroicon name="plus" class="w-4 h-4" />
                                                Register First Vessel
                                            </button>
                                        @endcan
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($vessels->hasPages())
            <div class="pagination-wrapper mt-6 pt-6 border-t border-border">
                {{ $vessels->links() }}
            </div>
        @endif
    </div>

    {{-- Child Components --}}
    @livewire('admin.management.vessels.vessel-form')
    @livewire('admin.management.vessels.vessel-delete')

    {{-- Quick Assign Renter Modal --}}
    @if($showQuickAssignModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="quick-assign-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeQuickAssign"></div>
                
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-modal">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-purple-100 dark:bg-purple-900/20 sm:mx-0 sm:h-10 sm:w-10">
                                <x-heroicon name="user-plus" class="h-6 w-6 text-purple-600" />
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="quick-assign-title">
                                    Quick Assign Renter
                                </h3>
                                <div class="mt-4">
                                    {{-- Quick Assign Owner Button --}}
                                    @php
                                        $vessel = $vessels->find($quickAssignVesselId);
                                    @endphp
                                    @if($vessel && $vessel->owner && $vessel->owner->id != $vessel->renter_client_id)
                                        <button wire:click="assignOwnerAsRenter" 
                                                class="w-full mb-4 px-4 py-2 bg-blue-100 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/30 transition-colors">
                                            <div class="flex items-center justify-center gap-2">
                                                <x-heroicon name="user" class="w-4 h-4" />
                                                Assign Owner as Renter ({{ $vessel->owner->display_name }})
                                            </div>
                                        </button>
                                    @endif
                                    
                                    {{-- Search for Other Clients --}}
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Or search for a different client:
                                    </label>
                                    <div class="relative" x-data="{ open: @entangle('showClientDropdown') }">
                                        <input type="text" 
                                               wire:model.live.debounce.300ms="quickAssignSearch"
                                               @focus="open = true"
                                               class="form-input w-full"
                                               placeholder="Search by name or ID...">
                                        
                                        @if($showClientDropdown && count($eligibleClients) > 0)
                                            <div class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                                                @foreach($eligibleClients as $client)
                                                    <button type="button" 
                                                            wire:click="assignClientAsRenter({{ $client['id'] }})"
                                                            class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2">
                                                        <div class="w-6 h-6 bg-gradient-to-r from-green-500 to-blue-500 rounded-full flex items-center justify-center text-white text-xs">
                                                            {{ substr($client['display_name'], 0, 1) }}
                                                        </div>
                                                        <div>
                                                            <div class="text-sm font-medium">{{ $client['display_name'] }}</div>
                                                            @if($client['id_card'])
                                                                <div class="text-xs text-muted-foreground">ID: {{ $client['id_card'] }}</div>
                                                            @endif
                                                        </div>
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" 
                                wire:click="closeQuickAssign"
                                class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-600 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-700">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Deactivate Confirmation Modal --}}
    @if($showDeactivateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="deactivate-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="cancelDeactivate"></div>
                
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-modal">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 dark:bg-yellow-900/20 sm:mx-0 sm:h-10 sm:w-10">
                                <x-heroicon name="exclamation-triangle" class="h-6 w-6 text-yellow-600" />
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="deactivate-title">
                                    Deactivate Vessel
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Are you sure you want to deactivate this vessel? This action will make it unavailable for new bookings.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" 
                                wire:click="deactivateVessel"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Deactivate
                        </button>
                        <button type="button" 
                                wire:click="cancelDeactivate"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-600 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-700">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- Toast Notifications --}}
<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('vesselSaved', (event) => {
        console.log(event.message);
    });

    Livewire.on('vesselDeleted', (event) => {
        console.log(event.message);
    });
    
    Livewire.on('showToast', (event) => {
        console.log(event);
    });
});
</script>
</div>