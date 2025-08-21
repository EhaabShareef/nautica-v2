<div class="container mx-auto px-4 py-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--foreground);">Settings</h1>
            <p class="text-sm mt-1" style="color: var(--muted-foreground);">
                Manage application settings and configurable types
            </p>
        </div>

        {{-- Back to Dashboard --}}
        <a href="{{ route('admin.dashboard') }}" class="btn-secondary h-10 flex items-center gap-2">
            <x-heroicon name="arrow-left" class="w-4 h-4" />
            Back to Dashboard
        </a>
    </div>

    {{-- Tab Navigation --}}
    <div class="border-b mb-6" style="border-color: var(--border);">
        <nav class="-mb-px flex space-x-8">
            <button
                wire:click="setActiveTab('settings')"
                class="py-2 px-1 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'settings' ? 'border-blue-500 text-blue-600' : 'border-transparent hover:border-gray-300' }}"
            >
                Application Settings
            </button>
            <button
                wire:click="setActiveTab('types')"
                class="py-2 px-1 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'types' ? 'border-blue-500 text-blue-600' : 'border-transparent hover:border-gray-300' }}"
            >
                Configurable Types
            </button>
        </nav>
    </div>

    {{-- Tab Content --}}
    <div class="min-h-[400px]">
        @if($activeTab === 'settings')
            @livewire('admin.configuration.settings.settings-list')
        @else
            @livewire('admin.configuration.settings.app-types-list')
        @endif
    </div>

    {{-- Form Modals --}}
    @livewire('admin.configuration.settings.forms.setting-form')
    @livewire('admin.configuration.settings.forms.setting-delete')
    @livewire('admin.configuration.settings.forms.app-type-form')
    @livewire('admin.configuration.settings.forms.app-type-delete')
</div>
