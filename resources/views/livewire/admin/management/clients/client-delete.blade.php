{{-- Client Delete Modal --}}
<div>
    @if($showModal && $client)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            {{-- Background overlay --}}
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                     wire:click="closeModal"
                     x-data="{ show: @entangle('showModal') }"
                     x-show="show"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">
                </div>

                {{-- This element is to trick the browser into centering the modal contents --}}
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                {{-- Modal panel --}}
                <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full"
                     x-data="{ show: @entangle('showModal') }"
                     x-show="show"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    {{-- Modal Header --}}
                    <div class="bg-white dark:bg-gray-800 px-6 pt-6 pb-4">
                        <div class="flex items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/20 sm:mx-0 sm:h-10 sm:w-10">
                                <x-heroicon name="exclamation-triangle" class="h-6 w-6 text-red-600 dark:text-red-400" />
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                                    Delete Client
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Are you sure you want to delete <strong>{{ $client->name }}</strong>? 
                                        This action cannot be undone.
                                    </p>
                                </div>
                            </div>
                            <button type="button" 
                                    wire:click="closeModal"
                                    class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors ml-4">
                                <x-heroicon name="x-mark" class="w-6 h-6" />
                            </button>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <div class="bg-white dark:bg-gray-800 px-6 py-4">
                        {{-- Client Info Card --}}
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-red-500 to-pink-500 rounded-full flex items-center justify-center text-white text-lg font-semibold">
                                    {{ substr($client->name, 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900 dark:text-white">{{ $client->name }}</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $client->email }}</p>
                                    @if($client->phone)
                                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ $client->phone }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <div class="flex items-center gap-1 text-sm">
                                        <x-heroicon name="rocket-launch" class="w-4 h-4 text-gray-400" />
                                        <span class="text-gray-600 dark:text-gray-300">{{ $client->vessels->count() }} vessels</span>
                                    </div>
                                    @if($client->is_active)
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-400">
                                            <x-heroicon name="check-circle" class="w-3 h-3" />
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-400">
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
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3 border-t border-gray-200 dark:border-gray-600">
                        <button type="button" 
                                wire:click="closeModal"
                                class="w-full sm:w-auto btn-secondary px-4 py-2 text-sm rounded-lg transition-all hover:scale-105">
                            <x-heroicon name="x-mark" class="w-4 h-4" />
                            Cancel
                        </button>
                        
                        @if($client->vessels->count() === 0)
                            <button type="button" 
                                    wire:click="delete"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all hover:scale-105 shadow-lg">
                                <x-heroicon name="trash" class="w-4 h-4" />
                                Delete Client
                            </button>
                        @else
                            <button type="button" 
                                    disabled
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-lg text-gray-400 bg-gray-200 dark:bg-gray-600 cursor-not-allowed opacity-50">
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

{{-- Alpine.js for animations --}}
<script>
    document.addEventListener('alpine:init', () => {
        // Modal animations are handled by Alpine.js x-transition directives
    });
</script>
