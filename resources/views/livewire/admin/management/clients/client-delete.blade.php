{{-- Client Delete Modal --}}
<div>
    @if($showModal && $client)
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4" 
             wire:ignore.self role="dialog" aria-modal="true"
             aria-labelledby="client-delete-title"
             aria-describedby="client-delete-desc">
            {{-- Full-screen backdrop --}}
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeModal"></div>

            {{-- Modal panel --}}
            <div class="relative w-full max-w-md transform transition-all duration-200"
                 style="background: var(--card); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); animation: modal-appear 0.2s ease-out;">
                    
                    {{-- Modal Header --}}
                    <div class="px-6 py-4 border-b" style="border-color: var(--border);">
                        <div class="flex items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full" style="background-color: rgba(239,68,68,0.1);">
                                <x-heroicon name="exclamation-triangle" class="h-6 w-6 text-red-600" />
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                                <h3 id="client-delete-title" class="text-lg leading-6 font-medium" style="color: var(--foreground);">
                                    Delete Client
                                </h3>
                                <div class="mt-2">
                                    <p id="client-delete-desc" class="text-sm" style="color: var(--muted-foreground);">
                                        Are you sure you want to delete <strong>{{ $client->name }}</strong>? This action cannot be undone.
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
                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-red-500 to-pink-500 rounded-full flex items-center justify-center text-white text-lg font-semibold">
                                    {{ substr($client->name, 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium" style="color: var(--foreground);">{{ $client->name }}</h4>
                                    <p class="text-sm" style="color: var(--muted-foreground);">{{ $client->email }}</p>
                                    @if($client->phone)
                                        <p class="text-xs" style="color: var(--muted-foreground);">{{ $client->phone }}</p>
                                    @endif
                                    @if(!$client->id_card || !$client->phone)
                                        <span class="status-badge status-warning mt-2">Incomplete</span>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <div class="flex items-center gap-1 text-sm" style="color: var(--muted-foreground);">
                                        <x-heroicon name="rocket-launch" class="w-4 h-4" />
                                        <span>{{ $client->vessels->count() }} vessels</span>
                                    </div>
                                    @if($client->is_active)
                                        <span class="status-badge status-active mt-1">
                                            <x-heroicon name="check-circle" class="w-3 h-3" />
                                            Active
                                        </span>
                                    @else
                                        <span class="status-badge status-inactive mt-1">
                                            <x-heroicon name="x-circle" class="w-3 h-3" />
                                            Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Vessel Warning --}}
                        @if($client->vessels->count() > 0)
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-4">
                                <div class="flex items-start gap-2">
                                    <x-heroicon name="exclamation-triangle" class="w-5 h-5 text-yellow-500 mt-0.5" />
                                    <div>
                                        <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                            Client has {{ $client->vessels->count() }} vessel(s)
                                        </h4>
                                        <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                                            You must remove all vessels before deleting this client. 
                                            Deleting clients with vessels is not allowed to maintain data integrity.
                                        </p>
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

                        {{-- Confirmation Input --}}
                        @if($client->vessels->count() === 0)
                            <div class="mb-4">
                                <label for="confirmText" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Type <span class="font-mono bg-gray-100 dark:bg-gray-700 px-1 rounded text-red-600 dark:text-red-400">delete</span> to confirm:
                                </label>
                                <input type="text" 
                                       id="confirmText"
                                       wire:model="confirmText"
                                       class="form-input w-full {{ $errors->has('confirmText') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}"
                                       placeholder="Type 'delete' to confirm"
                                       autocomplete="off">
                                @error('confirmText')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-3">
                                <div class="flex items-start gap-2">
                                    <x-heroicon name="information-circle" class="w-4 h-4 text-red-500 mt-0.5 flex-shrink-0" />
                                    <p class="text-xs text-red-700 dark:text-red-400">
                                        <strong>Warning:</strong> This will permanently delete the client account and cannot be reversed. 
                                        All associated login credentials will be removed.
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Modal Footer --}}
                    <div class="flex items-center justify-end gap-3 px-6 py-4 border-t" style="border-color: var(--border);">
                        <button type="button" wire:click="closeModal" class="w-full sm:w-auto btn-secondary px-4 py-2 text-sm">
                            <x-heroicon name="x-mark" class="w-4 h-4" />
                            Cancel
                        </button>

                        @if($client->vessels->count() === 0)
                            <button type="button" wire:click="delete" class="w-full sm:w-auto btn px-4 py-2 bg-red-600 hover:bg-red-700 text-white" wire:loading.attr="disabled" wire:target="delete">
                                <svg wire:loading wire:target="delete" class="animate-spin w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                <x-heroicon name="trash" class="w-4 h-4" wire:loading.remove wire:target="delete" />
                                <span wire:loading.remove wire:target="delete">Delete Client</span>
                            </button>
                        @else
                            <button type="button" disabled aria-disabled="true" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-lg text-gray-400 bg-gray-200 dark:bg-gray-600 cursor-not-allowed opacity-50">
                                <x-heroicon name="lock-closed" class="w-4 h-4" />
                                Cannot Delete
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
