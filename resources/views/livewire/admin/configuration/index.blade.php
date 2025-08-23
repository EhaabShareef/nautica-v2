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

    </div>


    {{-- Tabs --}}
    <div class="sys-card slide-up" style="animation-delay:.06s">
        <div class="border-b sys-border mb-3 md:mb-4">
            <div class="-mx-3 md:-mx-6 overflow-x-auto hide-scrollbar">
                <nav class="flex items-center gap-1 md:gap-0 px-3 md:px-6">
                    <button wire:click="setActiveTab('properties')" class="tab-button sys-tab {{ $activeTab === 'properties' ? 'active' : '' }} px-3 md:px-4 py-3 text-sm md:text-base whitespace-nowrap transition-all duration-300 ease-in-out focus-visible:ring-2 border-b-2 border-transparent">
                        <x-heroicon name="building-office-2" class="w-4 h-4 inline mr-2" />Properties
                    </button>
                    <button wire:click="setActiveTab('blocks')" class="tab-button sys-tab {{ $activeTab === 'blocks' ? 'active' : '' }} px-3 md:px-4 py-3 text-sm md:text-base whitespace-nowrap transition-all duration-300 ease-in-out focus-visible:ring-2 border-b-2 border-transparent">
                        <x-heroicon name="building-storefront" class="w-4 h-4 inline mr-2" />Blocks
                    </button>
                    <button wire:click="setActiveTab('zones')" class="tab-button sys-tab {{ $activeTab === 'zones' ? 'active' : '' }} px-3 md:px-4 py-3 text-sm md:text-base whitespace-nowrap transition-all duration-300 ease-in-out focus-visible:ring-2 border-b-2 border-transparent">
                        <x-heroicon name="map" class="w-4 h-4 inline mr-2" />Zones
                    </button>
                    <button wire:click="setActiveTab('slots')" class="tab-button sys-tab {{ $activeTab === 'slots' ? 'active' : '' }} px-3 md:px-4 py-3 text-sm md:text-base whitespace-nowrap transition-all duration-300 ease-in-out focus-visible:ring-2 border-b-2 border-transparent">
                        <x-heroicon name="squares-2x2" class="w-4 h-4 inline mr-2" />Slots
                    </button>
                    <button wire:click="setActiveTab('settings')" class="tab-button sys-tab {{ $activeTab === 'settings' ? 'active' : '' }} px-3 md:px-4 py-3 text-sm md:text-base whitespace-nowrap transition-all duration-300 ease-in-out focus-visible:ring-2 border-b-2 border-transparent">
                        <x-heroicon name="cog-6-tooth" class="w-4 h-4 inline mr-2" />Settings
                    </button>
                    <button wire:click="setActiveTab('app_types')" class="tab-button sys-tab {{ $activeTab === 'app_types' ? 'active' : '' }} px-3 md:px-4 py-3 text-sm md:text-base whitespace-nowrap transition-all duration-300 ease-in-out focus-visible:ring-2 border-b-2 border-transparent">
                        <x-heroicon name="tag" class="w-4 h-4 inline mr-2" />App Types
                    </button>
                    <button wire:click="setActiveTab('roles')" class="tab-button sys-tab {{ $activeTab === 'roles' ? 'active' : '' }} px-3 md:px-4 py-3 text-sm md:text-base whitespace-nowrap transition-all duration-300 ease-in-out focus-visible:ring-2 border-b-2 border-transparent">
                        <x-heroicon name="user-group" class="w-4 h-4 inline mr-2" />Roles
                    </button>
                </nav>
            </div>
        </div>

        <div class="p-4 md:p-6 transition-all duration-300 ease-in-out">
            @if($activeTab === 'properties')
                @livewire('admin.configuration.properties')
            @elseif($activeTab === 'blocks')
                @livewire('admin.configuration.blocks')
            @elseif($activeTab === 'zones')
                @livewire('admin.configuration.zones')
            @elseif($activeTab === 'slots')
                @livewire('admin.configuration.slots')
            @elseif($activeTab === 'settings')
                @livewire('admin.configuration.settings.settings-list')
            @elseif($activeTab === 'app_types')
                @livewire('admin.configuration.settings.app-types-list')
            @elseif($activeTab === 'roles')
                @livewire('admin.configuration.roles.index')
            @endif
        </div>
    </div>

    {{-- Configuration Forms --}}
    @livewire('admin.configuration.forms.property-form')
    @livewire('admin.configuration.forms.property-delete')
    @livewire('admin.configuration.forms.block-form')
    @livewire('admin.configuration.forms.block-delete')
    @livewire('admin.configuration.forms.slot-form')
    @livewire('admin.configuration.forms.slot-delete')
    @livewire('admin.configuration.forms.zone-form')
    @livewire('admin.configuration.forms.zone-delete')
    
    {{-- Settings Forms --}}
    @livewire('admin.configuration.settings.forms.setting-form')
    @livewire('admin.configuration.settings.forms.setting-delete')
    @livewire('admin.configuration.settings.forms.app-type-form')
    @livewire('admin.configuration.settings.forms.app-type-delete')
</div>
