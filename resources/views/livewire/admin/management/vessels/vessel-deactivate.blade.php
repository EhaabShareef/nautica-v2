{{-- Vessel Deactivate Confirmation Modal --}}
<div>
    @if($showModal && $vessel)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-data
             x-init="$nextTick(() => { document.body.style.overflow='hidden' })"
             x-on:keydown.escape.window="$wire.closeModal()"
             wire:ignore.self role="dialog" aria-modal="true"
             aria-labelledby="vessel-deactivate-title"
             aria-describedby="vessel-deactivate-desc">

            {{-- Full-screen backdrop --}}
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeModal"></div>

            {{-- Modal panel --}}
            <div class="relative w-full max-w-md transform transition-all duration-200"
                 style="background: var(--card); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); animation: modal-appear 0.2s ease-out;">
                    
                {{-- Modal Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b" style="border-color: var(--border);">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg">
                            <x-heroicon name="pause-circle" class="w-5 h-5 text-white" />
                        </div>
                        <div>
                            <h3 id="vessel-deactivate-title" class="text-lg font-semibold" style="color: var(--foreground);">
                                Deactivate Vessel
                            </h3>
                            <p id="vessel-deactivate-desc" class="text-sm" style="color: var(--muted-foreground);">
                                This will make the vessel unavailable
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
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-semibold">
                                {{ substr($vessel->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-medium text-foreground">{{ $vessel->name }}</div>
                                <div class="text-sm text-muted-foreground">{{ $vessel->registration_number }}</div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-muted-foreground">Owner:</span>
                                <div class="font-medium">{{ $vessel->owner->name }}</div>
                            </div>
                            @if($vessel->renter)
                                <div>
                                    <span class="text-muted-foreground">Renter:</span>
                                    <div class="font-medium">{{ $vessel->renter->name }}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Warning Message --}}
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <x-heroicon name="exclamation-triangle" class="w-5 h-5 text-yellow-500 flex-shrink-0 mt-0.5" />
                            <div class="text-sm">
                                <p class="text-yellow-700 dark:text-yellow-400 font-medium mb-2">
                                    Are you sure you want to deactivate this vessel?
                                </p>
                                <ul class="text-yellow-600 dark:text-yellow-400 space-y-1">
                                    <li>• The vessel will be unavailable for new bookings</li>
                                    <li>• All operations involving this vessel will be suspended</li>
                                    <li>• You can reactivate it at any time</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="px-6 py-4 border-t flex justify-end gap-3" style="border-color: var(--border);">
                    <button type="button" wire:click="closeModal" class="btn-secondary">
                        Cancel
                    </button>
                    <button type="button" 
                            wire:click="deactivateVessel"
                            wire:loading.attr="disabled"
                            wire:target="deactivateVessel"
                            class="btn bg-yellow-600 hover:bg-yellow-700 text-white disabled:opacity-50">
                        <span wire:loading.remove wire:target="deactivateVessel">
                            Deactivate Vessel
                        </span>
                        <span wire:loading wire:target="deactivateVessel" class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Deactivating...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>