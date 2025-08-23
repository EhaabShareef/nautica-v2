<div class="action-bar mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        {{-- Search and Filters --}}
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1">
            {{-- Search Input --}}
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-heroicon name="magnifying-glass" class="w-4 h-4 text-muted-foreground" />
                </div>
                <input wire:model.live.debounce.300ms="search" 
                       type="text" 
                       placeholder="Search clients..."
                       class="form-input pl-10 w-full text-sm rounded-lg transition-all focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>

            {{-- Status Filter --}}
            <select wire:model.live="statusFilter" class="form-input text-sm rounded-lg min-w-32">
                <option value="all">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>

            {{-- Per Page --}}
            <select wire:model.live="perPage" class="form-input text-sm rounded-lg min-w-20">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-2">
            <button wire:click="clearFilters" 
                    class="btn-secondary px-3 py-2 text-sm rounded-lg transition-all hover:scale-105">
                <x-heroicon name="x-mark" class="w-4 h-4" />
                <span class="hidden sm:inline ml-1">Clear</span>
            </button>
            
            <button wire:click="createClient" 
                    class="btn px-4 py-2 text-sm rounded-lg transition-all hover:scale-105 shadow-lg">
                <x-heroicon name="plus" class="w-4 h-4" />
                <span class="ml-1">Add Client</span>
            </button>
        </div>
    </div>
</div>
