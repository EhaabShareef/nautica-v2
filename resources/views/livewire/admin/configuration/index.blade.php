{{-- resources/views/admin/configuration/index.blade.php --}}
<div class="config-page p-4 sm:p-6 md:p-8 fade-in">
    {{-- Header with Back Button --}}
    <div class="mb-6 md:mb-8">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div class="min-w-0">
                <h1 class="text-2xl md:text-3xl font-bold sys-title mb-1">System Configuration</h1>
                <p class="text-sm md:text-base sys-subtitle">Manage properties, settings, and system configurations</p>
            </div>
            
            {{-- Back to Dashboard Button --}}
            <a href="{{ route('admin.dashboard') }}" 
               class="btn-secondary inline-flex items-center gap-2 px-4 py-2 text-sm rounded-xl shadow-sm transition-all hover:-translate-y-0.5 whitespace-nowrap self-start">
                <x-heroicon name="arrow-left" class="w-4 h-4" />
                <span class="hidden sm:inline">Back to Dashboard</span>
                <span class="sm:hidden">Back</span>
            </a>
        </div>

        {{-- System Overview Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mt-6">
            {{-- Total Properties --}}
            <div class="bg-blue-500/10 dark:bg-blue-500/10 rounded-xl p-4 border border-blue-300/30 dark:border-blue-500/20">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-500 rounded-lg">
                        <x-heroicon name="building-office-2" class="w-5 h-5 text-white" />
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $stats['total_properties'] }}</p>
                        <p class="text-xs font-medium text-blue-600 dark:text-blue-400">Total Properties</p>
                    </div>
                </div>
            </div>

            {{-- Active Properties --}}
            <div class="bg-green-500/10 dark:bg-green-500/10 rounded-xl p-4 border border-green-300/30 dark:border-green-500/20">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-green-500 rounded-lg">
                        <x-heroicon name="check-circle" class="w-5 h-5 text-white" />
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-green-700 dark:text-green-300">{{ $stats['active_properties'] }}</p>
                        <p class="text-xs font-medium text-green-600 dark:text-green-400">Active Properties</p>
                    </div>
                </div>
            </div>

            {{-- Active Slots --}}
            <div class="bg-purple-500/10 dark:bg-purple-500/10 rounded-xl p-4 border border-purple-300/30 dark:border-purple-500/20">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-purple-500 rounded-lg">
                        <x-heroicon name="squares-2x2" class="w-5 h-5 text-white" />
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-purple-700 dark:text-purple-300">{{ $stats['active_slots'] }}</p>
                        <p class="text-xs font-medium text-purple-600 dark:text-purple-400">Active Slots</p>
                    </div>
                </div>
            </div>

            {{-- Last Updated --}}
            <div class="bg-orange-500/10 dark:bg-orange-500/10 rounded-xl p-4 border border-orange-300/30 dark:border-orange-500/20">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-orange-500 rounded-lg">
                        <x-heroicon name="clock" class="w-5 h-5 text-white" />
                    </div>
                    <div>
                        <p class="text-sm font-bold text-orange-700 dark:text-orange-300 truncate">{{ $stats['last_updated'] }}</p>
                        <p class="text-xs font-medium text-orange-600 dark:text-orange-400">Last Updated</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Tabs --}}
    <div class="sys-card slide-up" style="animation-delay:.06s">
        <div class="border-b sys-border mb-3 md:mb-4">
            <div class="-mx-3 md:-mx-6 overflow-x-auto hide-scrollbar">
                <nav class="flex items-center gap-1 md:gap-0 px-3 md:px-6">
                    <button wire:click="setActiveTab('properties')" class="tab-button sys-tab {{ $activeTab === 'properties' ? 'active' : '' }} px-4 md:px-6 py-3 text-sm md:text-base whitespace-nowrap transition-colors duration-200 focus-visible:ring-2 rounded-md">
                        <x-heroicon name="building-office-2" class="w-4 h-4 inline mr-2" />Properties
                    </button>
                    <button wire:click="setActiveTab('blocks')" class="tab-button sys-tab {{ $activeTab === 'blocks' ? 'active' : '' }} px-4 md:px-6 py-3 text-sm md:text-base whitespace-nowrap transition-colors duration-200 focus-visible:ring-2 rounded-md">
                        <x-heroicon name="building-storefront" class="w-4 h-4 inline mr-2" />Blocks
                    </button>
                    <button wire:click="setActiveTab('zones')" class="tab-button sys-tab {{ $activeTab === 'zones' ? 'active' : '' }} px-4 md:px-6 py-3 text-sm md:text-base whitespace-nowrap transition-colors duration-200 focus-visible:ring-2 rounded-md">
                        <x-heroicon name="map" class="w-4 h-4 inline mr-2" />Zones
                    </button>
                    <button wire:click="setActiveTab('slots')" class="tab-button sys-tab {{ $activeTab === 'slots' ? 'active' : '' }} px-4 md:px-6 py-3 text-sm md:text-base whitespace-nowrap transition-colors duration-200 focus-visible:ring-2 rounded-md">
                        <x-heroicon name="squares-2x2" class="w-4 h-4 inline mr-2" />Slots
                    </button>
                    <button wire:click="setActiveTab('settings')" class="tab-button sys-tab {{ $activeTab === 'settings' ? 'active' : '' }} px-4 md:px-6 py-3 text-sm md:text-base whitespace-nowrap transition-colors duration-200 focus-visible:ring-2 rounded-md">
                        <x-heroicon name="cog-6-tooth" class="w-4 h-4 inline mr-2" />Settings
                    </button>
                    <button wire:click="setActiveTab('app_types')" class="tab-button sys-tab {{ $activeTab === 'app_types' ? 'active' : '' }} px-4 md:px-6 py-3 text-sm md:text-base whitespace-nowrap transition-colors duration-200 focus-visible:ring-2 rounded-md">
                        <x-heroicon name="tag" class="w-4 h-4 inline mr-2" />App Types
                    </button>
                </nav>
            </div>
        </div>

        <div class="p-4 md:p-6 transition-opacity duration-200">
            @if($activeTab === 'properties')
                @livewire('admin.configuration.properties')
            @elseif($activeTab === 'blocks')
                @livewire('admin.configuration.blocks')
            @elseif($activeTab === 'zones')
                @livewire('admin.configuration.zones')
            @elseif($activeTab === 'slots')
                @livewire('admin.configuration.slots')
            @elseif($activeTab === 'settings')
                @livewire('admin.configuration.settings')
            @elseif($activeTab === 'app_types')
                @livewire('admin.configuration.app-types')
            @endif
        </div>
    </div>

    {{-- Property Forms --}}
    @livewire('admin.configuration.forms.property-form')
    @livewire('admin.configuration.forms.property-delete')
    @livewire('admin.configuration.forms.block-form')
    @livewire('admin.configuration.forms.block-delete')
    @livewire('admin.configuration.forms.slot-form')
    @livewire('admin.configuration.forms.slot-delete')
    @livewire('admin.configuration.forms.zone-form')
    @livewire('admin.configuration.forms.zone-delete')
</div>
