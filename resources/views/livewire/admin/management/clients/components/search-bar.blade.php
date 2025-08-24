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
                   placeholder="Search clients..." 
                   class="form-input pl-10 w-full text-sm rounded-lg transition-all focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                   style="height: 42px;">
        </div>

        {{-- Status Filter --}}
        <select wire:model.live="statusFilter" 
                class="px-4 py-3 text-sm rounded-lg border border-border bg-secondary text-foreground hover:bg-muted/50 transition-all"
                style="height: 42px;">
            <option value="all">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="blacklisted">Blacklisted</option>
        </select>
    </div>

    {{-- Actions --}}
    <div class="flex items-center gap-3">
        {{-- Per Page Selector --}}
        <div class="flex items-center gap-2 text-sm text-muted-foreground">
            <span class="hidden sm:inline">Show:</span>
            <select wire:model.live="perPage" class="form-input text-sm rounded-lg" style="height: 42px;">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>

        {{-- Clear Filter Button --}}
        @if($search || $statusFilter !== 'all')
            <button wire:click="clearFilters" 
                    class="px-4 py-3 text-sm rounded-lg border border-border bg-secondary text-red-600 hover:bg-red-50 hover:text-red-700 dark:hover:bg-red-900/20 dark:hover:text-red-400 transition-all inline-flex items-center gap-2"
                    style="height: 42px;">
                <x-heroicon name="x-mark" class="w-4 h-4" />
                <span class="hidden sm:inline">Clear</span>
            </button>
        @endif

        {{-- Add Client Button --}}
        <button wire:click="createClient" 
                class="px-4 py-3 text-sm rounded-lg border-0 bg-primary text-primary-foreground hover:opacity-90 hover:transform hover:-translate-y-0.5 transition-all shadow-sm inline-flex items-center gap-2"
                style="height: 42px;">
            <x-heroicon name="plus" class="w-4 h-4" />
            <span class="hidden sm:inline">Add Client</span>
            <span class="sm:hidden">Add</span>
        </button>
    </div>
</div>
