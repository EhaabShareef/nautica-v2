{{-- Vessel Status Toggle Modal --}}
<div>
    @if($showModal && $vessel)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-data
             x-init="$nextTick(() => { document.body.style.overflow='hidden' })"
             x-on:keydown.escape.window="$wire.closeModal()"
             x-on:vessel-status-toggle:closed.window="$nextTick(() => { document.body.style.overflow=''; })"
             wire:ignore.self role="dialog" aria-modal="true"
             aria-labelledby="status-toggle-title">

            {{-- Full-screen backdrop --}}
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeModal"></div>

            {{-- Modal panel --}}
            <div class="relative w-full max-w-md transform transition-all duration-200"
                 style="background: var(--card); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); animation: modal-appear 0.2s ease-out;">
                    
                {{-- Modal Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b" style="border-color: var(--border);">
                    <div class="flex items-center gap-3">
                        @if($vessel->is_active)
                            <div class="p-2 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg">
                                <x-heroicon name="pause-circle" class="w-5 h-5 text-white" />
                            </div>
                        @else
                            <div class="p-2 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg">
                                <x-heroicon name="play-circle" class="w-5 h-5 text-white" />
                            </div>
                        @endif
                        <div>
                            <h3 id="status-toggle-title" class="text-lg font-semibold" style="color: var(--foreground);">
                                {{ $vessel->is_active ? 'Deactivate Vessel' : 'Activate Vessel' }}
                            </h3>
                            <p class="text-sm" style="color: var(--muted-foreground);">
                                {{ $vessel->is_active ? 'Confirm vessel deactivation' : 'Confirm vessel activation' }}
                            </p>
                        </div>
                    </div>
                    <button type="button" wire:click="closeModal" class="p-2 rounded-lg transition-colors hover:bg-gray-100 dark:hover:bg-gray-800">
                        <x-heroicon name="x-mark" class="w-5 h-5" style="color: var(--muted-foreground);" />
                    </button>
                </div>

                {{-- Modal Content --}}
                <div class="px-6 py-6">
                    {{-- Vessel Info --}}
                    <div class="bg-muted/30 rounded-lg p-4 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-semibold">
                                {{ substr($vessel->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-foreground">{{ $vessel->name }}</div>
                                <div class="text-sm text-muted-foreground">{{ $vessel->registration_number }}</div>
                            </div>
                            <div>
                                @if($vessel->is_active)
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-400">
                                        <x-heroicon name="check-circle" class="w-3 h-3 mr-1" />
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                        <x-heroicon name="pause-circle" class="w-3 h-3 mr-1" />
                                        Inactive
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Confirmation Message --}}
                    <div class="flex items-start gap-4">
                        @if($vessel->is_active)
                            <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 dark:bg-yellow-900/20 rounded-full flex items-center justify-center">
                                <x-heroicon name="exclamation-triangle" class="w-6 h-6 text-yellow-600 dark:text-yellow-400" />
                            </div>
                            <div class="flex-1">
                                <p class="text-sm" style="color: var(--foreground);">
                                    Are you sure you want to deactivate this vessel? This action will make it unavailable for new bookings and operations.
                                </p>
                                <p class="text-xs mt-2" style="color: var(--muted-foreground);">
                                    You can reactivate the vessel at any time.
                                </p>
                            </div>
                        @else
                            <div class="flex-shrink-0 w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-full flex items-center justify-center">
                                <x-heroicon name="check-circle" class="w-6 h-6 text-green-600 dark:text-green-400" />
                            </div>
                            <div class="flex-1">
                                <p class="text-sm" style="color: var(--foreground);">
                                    Are you sure you want to activate this vessel? This will make it available for bookings and operations.
                                </p>
                                <p class="text-xs mt-2" style="color: var(--muted-foreground);">
                                    The vessel will be available for new bookings once activated.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center justify-end px-6 py-4 border-t gap-3" style="border-color: var(--border);">
                    <button type="button" 
                            wire:click="closeModal"
                            class="px-4 py-2 text-sm rounded-lg border transition-colors hover:bg-muted/50" 
                            style="border-color: var(--border); color: var(--muted-foreground);">
                        Cancel
                    </button>
                    <button type="button" 
                            wire:click="toggleVesselStatus"
                            wire:loading.attr="disabled"
                            wire:target="toggleVesselStatus"
                            class="px-4 py-2 text-sm rounded-lg text-white transition-colors shadow-sm disabled:opacity-50 {{ $vessel->is_active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }}">
                        <span wire:loading.remove wire:target="toggleVesselStatus">
                            {{ $vessel->is_active ? 'Deactivate Vessel' : 'Activate Vessel' }}
                        </span>
                        <span wire:loading wire:target="toggleVesselStatus" class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ $vessel->is_active ? 'Deactivating...' : 'Activating...' }}
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>