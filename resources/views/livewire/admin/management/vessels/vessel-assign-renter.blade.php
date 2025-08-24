{{-- Vessel Assign Renter Modal --}}
<div>
    @if($showModal && $vessel)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-data
             x-init="$nextTick(() => { document.body.style.overflow='hidden' })"
             x-on:keydown.escape.window="$wire.closeModal()"
             x-on:vessel-assign-renter:closed.window="$nextTick(() => { document.body.style.overflow=''; })"
             wire:ignore.self role="dialog" aria-modal="true"
             aria-labelledby="assign-renter-title">

            {{-- Full-screen backdrop --}}
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeModal"></div>

            {{-- Modal panel --}}
            <div class="relative w-full max-w-lg transform transition-all duration-200"
                 style="background: var(--card); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); animation: modal-appear 0.2s ease-out;">
                    
                {{-- Modal Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b" style="border-color: var(--border);">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg">
                            <x-heroicon name="user-plus" class="w-5 h-5 text-white" />
                        </div>
                        <div>
                            <h3 id="assign-renter-title" class="text-lg font-semibold" style="color: var(--foreground);">
                                {{ $vessel->renter_client_id ? 'Change Renter' : 'Assign Renter' }}
                            </h3>
                            <p class="text-sm" style="color: var(--muted-foreground);">
                                {{ $vessel->renter_client_id ? 'Change the current renter for this vessel' : 'Assign a renter to this vessel' }}
                            </p>
                        </div>
                    </div>
                    <button type="button" wire:click="closeModal" class="p-2 rounded-lg transition-colors hover:bg-gray-100 dark:hover:bg-gray-800">
                        <x-heroicon name="x-mark" class="w-5 h-5" style="color: var(--muted-foreground);" />
                    </button>
                </div>

                {{-- Modal Content --}}
                <div class="px-6 py-6 space-y-4">
                    {{-- Vessel Info --}}
                    <div class="bg-muted/30 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-semibold">
                                {{ substr($vessel->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-foreground">{{ $vessel->name }}</div>
                                <div class="text-sm text-muted-foreground">{{ $vessel->registration_number }}</div>
                            </div>
                        </div>
                        @if($vessel->renter)
                            <div class="mt-3 pt-3 border-t border-border">
                                <div class="text-xs text-muted-foreground mb-1">Current Renter:</div>
                                <div class="text-sm font-medium text-foreground">{{ $vessel->renter->name }}</div>
                            </div>
                        @endif
                    </div>

                    {{-- Quick Assign Owner Button --}}
                    @if($vessel->owner && $vessel->owner->id != $vessel->renter_client_id)
                        <button wire:click="assignOwnerAsRenter" 
                                wire:loading.attr="disabled"
                                wire:target="assignOwnerAsRenter"
                                class="w-full px-4 py-3 bg-blue-100 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/30 transition-colors disabled:opacity-50">
                            <div class="flex items-center justify-center gap-2">
                                <span wire:loading.remove wire:target="assignOwnerAsRenter" class="flex items-center gap-2">
                                    <x-heroicon name="user" class="w-4 h-4" />
                                    <span class="font-medium">Assign Owner as Renter</span>
                                    <span class="text-sm opacity-75">({{ $vessel->owner->name }})</span>
                                </span>
                                <span wire:loading wire:target="assignOwnerAsRenter" class="flex items-center gap-2">
                                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Assigning...
                                </span>
                            </div>
                        </button>
                    @endif
                    
                    {{-- Search for Other Clients --}}
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--foreground);">
                            {{ $vessel->owner && $vessel->owner->id != $vessel->renter_client_id ? 'Or search for a different client:' : 'Search for a client:' }}
                        </label>
                        <div class="relative" x-data="{ open: @entangle('showClientDropdown') }">
                            <input type="text" 
                                   wire:model.live.debounce.300ms="quickAssignSearch"
                                   @focus="open = true"
                                   class="form-input w-full text-sm rounded-lg transition-all focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                                   placeholder="Search by name or ID...">
                            
                            @if($showClientDropdown && count($eligibleClients) > 0)
                                <div class="absolute z-10 w-full mt-1 rounded-lg shadow-lg max-h-48 overflow-y-auto" style="background: var(--card); border: 1px solid var(--border);">
                                    @foreach($eligibleClients as $client)
                                        <button type="button" 
                                                wire:click="assignClientAsRenter({{ $client['id'] }})"
                                                wire:loading.attr="disabled"
                                                wire:target="assignClientAsRenter({{ $client['id'] }})"
                                                class="w-full px-4 py-3 text-left hover:bg-muted/50 flex items-center gap-3 transition-colors disabled:opacity-50">
                                            <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-blue-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                                {{ substr($client['display_name'], 0, 1) }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-sm font-medium text-foreground">{{ $client['display_name'] }}</div>
                                                @if($client['id_card'])
                                                    <div class="text-xs text-muted-foreground">ID: {{ $client['id_card'] }}</div>
                                                @endif
                                            </div>
                                            <span wire:loading wire:target="assignClientAsRenter({{ $client['id'] }})" class="ml-2">
                                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </span>
                                        </button>
                                    @endforeach
                                </div>
                            @endif

                            @if($quickAssignSearch && !$showClientDropdown)
                                <div class="absolute z-10 w-full mt-1 rounded-lg shadow-lg p-4 text-center text-sm text-muted-foreground" style="background: var(--card); border: 1px solid var(--border);">
                                    No clients found matching "{{ $quickAssignSearch }}"
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Remove Renter Option --}}
                    @if($vessel->renter_client_id)
                        <div class="pt-4 border-t border-border">
                            <button type="button" 
                                    wire:click="removeRenter"
                                    wire:loading.attr="disabled"
                                    wire:target="removeRenter"
                                    class="w-full px-4 py-2 text-sm rounded-lg border border-red-300 text-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors disabled:opacity-50">
                                <span wire:loading.remove wire:target="removeRenter" class="flex items-center justify-center gap-2">
                                    <x-heroicon name="user-minus" class="w-4 h-4" />
                                    Remove Current Renter
                                </span>
                                <span wire:loading wire:target="removeRenter" class="flex items-center justify-center gap-2">
                                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Removing...
                                </span>
                            </button>
                        </div>
                    @endif
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center justify-end px-6 py-4 border-t gap-3" style="border-color: var(--border);">
                    <button type="button" 
                            wire:click="closeModal"
                            class="px-4 py-2 text-sm rounded-lg border transition-colors hover:bg-muted/50" 
                            style="border-color: var(--border); color: var(--muted-foreground);">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>