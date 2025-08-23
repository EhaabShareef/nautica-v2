<div class="action-bar mb-6">
    {{-- Mobile View: Two rows --}}
    <div class="flex flex-col gap-3 md:hidden">
        {{-- First Row: Search only --}}
        <div class="relative w-full">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <x-heroicon name="magnifying-glass" class="w-4 h-4 text-muted-foreground" />
            </div>
            <input wire:model.live.debounce.300ms="search" 
                   type="text" 
                   placeholder="Search clients..."
                   class="form-input pl-10 w-full text-sm rounded-lg transition-all focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
        </div>
        
        {{-- Second Row: Other controls --}}
        <div class="grid grid-cols-4 gap-2">
            {{-- Status Filter --}}
            <select wire:model.live="statusFilter" class="form-input text-sm rounded-lg">
                <option value="all">All</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="blacklisted">Blacklisted</option>
            </select>

            {{-- Per Page --}}
            <select wire:model.live="perPage" class="form-input text-sm rounded-lg">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>

            {{-- Clear Filter Button --}}
            <button wire:click="clearFilters" 
                    class="px-3 py-3 text-sm rounded-lg border border-border bg-secondary text-red-600 hover:bg-red-50 hover:text-red-700 dark:hover:bg-red-900/20 dark:hover:text-red-400 transition-all flex items-center justify-center gap-1"
                    style="height: 42px;">
                <x-heroicon name="x-mark" class="w-4 h-4" />
                <span class="hidden lg:inline">Clear</span>
            </button>
            
            {{-- Add Client Button --}}
            <button wire:click="createClient" 
                    class="px-3 py-3 text-sm rounded-lg border-0 bg-primary text-primary-foreground hover:opacity-90 transition-all shadow-sm flex items-center justify-center gap-1"
                    style="height: 42px;">
                <x-heroicon name="plus" class="w-4 h-4" />
                <span class="hidden lg:inline">Add Client</span>
            </button>
        </div>
    </div>

    {{-- Desktop View: Single row with 8/12 search and 1/12 each for others --}}
    <div class="hidden md:grid md:grid-cols-12 gap-3 items-center">
        {{-- Search Input: 8 columns --}}
        <div class="relative col-span-8">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <x-heroicon name="magnifying-glass" class="w-4 h-4 text-muted-foreground" />
            </div>
            <input wire:model.live.debounce.300ms="search" 
                   type="text" 
                   placeholder="Search clients..."
                   class="form-input pl-10 w-full text-sm rounded-lg transition-all focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
        </div>

        {{-- Status Filter: 1 column --}}
        <div class="col-span-1">
            <select wire:model.live="statusFilter" class="form-input text-sm rounded-lg w-full">
                <option value="all">All</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="blacklisted">Blacklisted</option>
            </select>
        </div>

        {{-- Per Page: 1 column --}}
        <div class="col-span-1">
            <select wire:model.live="perPage" class="form-input text-sm rounded-lg w-full">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>

        {{-- Clear Filter Button: 1 column --}}
        <div class="col-span-1">
            <button wire:click="clearFilters" 
                    class="w-full px-3 py-3 text-sm rounded-lg border border-border bg-secondary text-red-600 hover:bg-red-50 hover:text-red-700 dark:hover:bg-red-900/20 dark:hover:text-red-400 transition-all flex items-center justify-center gap-1"
                    title="Clear Filters"
                    style="height: 42px;">
                <x-heroicon name="x-mark" class="w-4 h-4" />
                <span class="hidden lg:inline">Clear</span>
            </button>
        </div>
        
        {{-- Add Client Button: 1 column --}}
        <div class="col-span-1">
            <button wire:click="createClient" 
                    class="w-full px-3 py-3 text-sm rounded-lg border-0 bg-primary text-primary-foreground hover:opacity-90 hover:transform hover:-translate-y-0.5 transition-all shadow-sm flex items-center justify-center gap-1"
                    title="Add Client"
                    style="height: 42px;">
                <x-heroicon name="plus" class="w-4 h-4" />
                <span class="hidden lg:inline">Add Client</span>
            </button>
        </div>
    </div>
</div>
