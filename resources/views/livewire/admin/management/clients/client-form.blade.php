{{-- Client Form Modal --}}
<div>
    @if($showModal)
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
                <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                     x-data="{ show: @entangle('showModal') }"
                     x-show="show"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    {{-- Modal Header --}}
                    <div class="bg-white dark:bg-gray-800 px-6 pt-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg">
                                    <x-heroicon name="{{ $isEditing ? 'pencil' : 'user-plus' }}" class="w-5 h-5 text-white" />
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $isEditing ? 'Edit Client' : 'Add New Client' }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $isEditing ? 'Update client information' : 'Create a new client account' }}
                                    </p>
                                </div>
                            </div>
                            <button type="button" 
                                    wire:click="closeModal"
                                    class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                                <x-heroicon name="x-mark" class="w-6 h-6" />
                            </button>
                        </div>
                    </div>

                    {{-- Modal Form --}}
                    <form wire:submit="save" class="bg-white dark:bg-gray-800">
                        <div class="px-6 py-6 space-y-6 max-h-96 overflow-y-auto">
                            {{-- Error Display --}}
                            @if($errors->has('form'))
                                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                                    <div class="flex items-center gap-2">
                                        <x-heroicon name="exclamation-triangle" class="w-5 h-5 text-red-500" />
                                        <p class="text-sm text-red-700 dark:text-red-400">{{ $errors->first('form') }}</p>
                                    </div>
                                </div>
                            @endif

                            {{-- Name Field --}}
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="name"
                                       wire:model="name"
                                       class="form-input w-full {{ $errors->has('name') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}"
                                       placeholder="Enter client's full name">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email Field --}}
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       id="email"
                                       wire:model="email"
                                       class="form-input w-full {{ $errors->has('email') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}"
                                       placeholder="client@example.com">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Phone and ID Card Row --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Phone Field --}}
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Phone Number
                                    </label>
                                    <input type="text" 
                                           id="phone"
                                           wire:model="phone"
                                           class="form-input w-full {{ $errors->has('phone') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}"
                                           placeholder="+1 234 567 8900">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- ID Card Field --}}
                                <div>
                                    <label for="id_card" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        ID Card Number
                                    </label>
                                    <input type="text" 
                                           id="id_card"
                                           wire:model="id_card"
                                           class="form-input w-full {{ $errors->has('id_card') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}"
                                           placeholder="ID123456789">
                                    @error('id_card')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Address Field --}}
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Address
                                </label>
                                <textarea id="address"
                                          wire:model="address"
                                          rows="3"
                                          class="form-input w-full {{ $errors->has('address') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}"
                                          placeholder="Enter client's address"></textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Password Fields --}}
                            <div class="space-y-4">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Password 
                                        @if(!$isEditing)
                                            <span class="text-red-500">*</span>
                                        @endif
                                        @if($isEditing)
                                            <span class="text-sm text-gray-500">(leave blank to keep current password)</span>
                                        @endif
                                    </label>
                                    <input type="password" 
                                           id="password"
                                           wire:model="password"
                                           class="form-input w-full {{ $errors->has('password') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}"
                                           placeholder="Enter password">
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Confirm Password
                                        @if(!$isEditing)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>
                                    <input type="password" 
                                           id="password_confirmation"
                                           wire:model="password_confirmation"
                                           class="form-input w-full"
                                           placeholder="Confirm password">
                                </div>
                            </div>

                            {{-- Status Toggle --}}
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Account Status</label>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Enable or disable client login access</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           wire:model="is_active" 
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                    <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ $is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </label>
                            </div>
                        </div>

                        {{-- Modal Footer --}}
                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3 border-t border-gray-200 dark:border-gray-600">
                            <button type="button" 
                                    wire:click="closeModal"
                                    class="w-full sm:w-auto btn-secondary px-4 py-2 text-sm rounded-lg transition-all hover:scale-105">
                                <x-heroicon name="x-mark" class="w-4 h-4" />
                                Cancel
                            </button>
                            
                            <button type="submit" 
                                    class="w-full sm:w-auto btn px-4 py-2 text-sm rounded-lg transition-all hover:scale-105 shadow-lg">
                                <x-heroicon name="{{ $isEditing ? 'check' : 'plus' }}" class="w-4 h-4" />
                                {{ $isEditing ? 'Update Client' : 'Create Client' }}
                            </button>
                        </div>
                    </form>
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
