{{-- Client Blacklist Modal --}}
<div>
    @if($showModal && $client)
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4" 
             wire:ignore.self role="dialog" aria-modal="true"
             aria-labelledby="client-blacklist-title"
             aria-describedby="client-blacklist-desc">
            {{-- Full-screen backdrop --}}
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeModal"></div>

            {{-- Modal panel --}}
            <div class="relative w-full max-w-md transform transition-all duration-200"
                 style="background: var(--card); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); animation: modal-appear 0.2s ease-out;">
                    
                    {{-- Modal Header --}}
                    <div class="px-6 py-4 border-b" style="border-color: var(--border);">
                        <div class="flex items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full" 
                                 style="background-color: {{ $isBlacklisting ? 'rgba(245,158,11,0.1)' : 'rgba(34,197,94,0.1)' }};">
                                @if($isBlacklisting)
                                    <x-heroicon name="exclamation-triangle" class="h-6 w-6 text-yellow-600" />
                                @else
                                    <x-heroicon name="check-circle" class="h-6 w-6 text-green-600" />
                                @endif
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                                <h3 id="client-blacklist-title" class="text-lg leading-6 font-medium" style="color: var(--foreground);">
                                    {{ $isBlacklisting ? 'Blacklist Client' : 'Remove from Blacklist' }}
                                </h3>
                                <div class="mt-2">
                                    <p id="client-blacklist-desc" class="text-sm" style="color: var(--muted-foreground);">
                                        @if($isBlacklisting)
                                            Are you sure you want to blacklist <strong>{{ $client->name }}</strong>? This will restrict their access to the system.
                                        @else
                                            Are you sure you want to remove <strong>{{ $client->name }}</strong> from the blacklist? They will regain full access.
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <button type="button" wire:click="closeModal" class="p-2 rounded-lg transition-colors hover:bg-gray-100 dark:hover:bg-gray-800 ml-4">
                                <x-heroicon name="x-mark" class="w-5 h-5" style="color: var(--muted-foreground);" />
                            </button>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <div class="px-6 py-4">
                        {{-- Client Info Card --}}
                        <div class="rounded-lg p-4 mb-4" style="background-color: var(--muted); border: 1px solid var(--border);">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white text-lg font-semibold">
                                    {{ substr($client->name, 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium" style="color: var(--foreground);">{{ $client->name }}</h4>
                                    <p class="text-sm" style="color: var(--muted-foreground);">{{ $client->email }}</p>
                                    @if($client->phone)
                                        <p class="text-xs" style="color: var(--muted-foreground);">{{ $client->phone }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <div class="flex items-center gap-1 text-sm" style="color: var(--muted-foreground);">
                                        <x-heroicon name="rocket-launch" class="w-4 h-4" />
                                        <span>{{ $client->vessels->count() ?? 0 }} vessels</span>
                                    </div>
                                    @if($client->is_blacklisted)
                                        <span class="status-badge bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-400 mt-1">
                                            <x-heroicon name="x-circle" class="w-3 h-3" />
                                            Blacklisted
                                        </span>
                                    @elseif($client->is_active)
                                        <span class="status-badge status-active mt-1">
                                            <x-heroicon name="check-circle" class="w-3 h-3" />
                                            Active
                                        </span>
                                    @else
                                        <span class="status-badge status-inactive mt-1">
                                            <x-heroicon name="minus-circle" class="w-3 h-3" />
                                            Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Warning Message --}}
                        @if($isBlacklisting)
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-4">
                                <div class="flex items-start gap-2">
                                    <x-heroicon name="exclamation-triangle" class="w-5 h-5 text-yellow-500 mt-0.5" />
                                    <div>
                                        <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                            Blacklisting this client will:
                                        </h4>
                                        <ul class="text-sm text-yellow-700 dark:text-yellow-300 mt-1 list-disc list-inside space-y-1">
                                            <li>Prevent them from logging into the system</li>
                                            <li>Block new bookings and reservations</li>
                                            <li>Restrict access to client portal features</li>
                                            <li>Keep existing data intact for records</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-4">
                                <div class="flex items-start gap-2">
                                    <x-heroicon name="check-circle" class="w-5 h-5 text-green-500 mt-0.5" />
                                    <div>
                                        <h4 class="text-sm font-medium text-green-800 dark:text-green-200">
                                            Removing from blacklist will:
                                        </h4>
                                        <ul class="text-sm text-green-700 dark:text-green-300 mt-1 list-disc list-inside space-y-1">
                                            <li>Restore full system access</li>
                                            <li>Allow new bookings and reservations</li>
                                            <li>Enable client portal features</li>
                                            <li>Resume normal account functionality</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Error Display --}}
                        @if($errors->any())
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-4">
                                <div class="flex items-start gap-2">
                                    <x-heroicon name="x-circle" class="w-5 h-5 text-red-500 mt-0.5" />
                                    <div>
                                        @foreach($errors->all() as $error)
                                            <p class="text-sm text-red-700 dark:text-red-400">{{ $error }}</p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Optional reason field for blacklisting --}}
                        @if($isBlacklisting)
                            <div class="mb-4">
                                <label for="blacklistReason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Reason (Optional)
                                </label>
                                <textarea id="blacklistReason"
                                         wire:model="blacklistReason"
                                         rows="3"
                                         class="form-input w-full text-sm resize-none"
                                         placeholder="Enter reason for blacklisting (for internal records)"></textarea>
                            </div>
                        @endif
                    </div>

                    {{-- Modal Footer --}}
                    <div class="flex items-center justify-end gap-3 px-6 py-4 border-t" style="border-color: var(--border);">
                        <button type="button" wire:click="closeModal" class="w-full sm:w-auto btn-secondary px-4 py-2 text-sm">
                            <x-heroicon name="x-mark" class="w-4 h-4" />
                            Cancel
                        </button>

                        @if($isBlacklisting)
                            <button type="button" wire:click="confirmBlacklist" class="w-full sm:w-auto btn px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white" wire:loading.attr="disabled" wire:target="confirmBlacklist">
                                <svg wire:loading wire:target="confirmBlacklist" class="animate-spin w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                <x-heroicon name="x-circle" class="w-4 h-4" wire:loading.remove wire:target="confirmBlacklist" />
                                <span wire:loading.remove wire:target="confirmBlacklist">Blacklist Client</span>
                            </button>
                        @else
                            <button type="button" wire:click="confirmUnblacklist" class="w-full sm:w-auto btn px-4 py-2 bg-green-600 hover:bg-green-700 text-white" wire:loading.attr="disabled" wire:target="confirmUnblacklist">
                                <svg wire:loading wire:target="confirmUnblacklist" class="animate-spin w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                <x-heroicon name="check-circle" class="w-4 h-4" wire:loading.remove wire:target="confirmUnblacklist" />
                                <span wire:loading.remove wire:target="confirmUnblacklist">Remove from Blacklist</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>