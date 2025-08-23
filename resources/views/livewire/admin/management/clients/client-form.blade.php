{{-- Client Form Modal --}}
<div>
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-data
             x-init="$nextTick(() => { document.body.style.overflow='hidden' })"
             x-on:keydown.escape.window="$wire.closeModal()"
             x-on:client-form:closed.window="$nextTick(() => { document.body.style.overflow=''; })"
             wire:ignore.self role="dialog" aria-modal="true"
             aria-labelledby="client-form-title"
             aria-describedby="client-form-desc">

            {{-- Full-screen backdrop --}}
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeModal"></div>

            {{-- Modal panel --}}
            <div class="relative w-full max-w-lg transform transition-all duration-200"
                 style="background: var(--card); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); animation: modal-appear 0.2s ease-out;">
                    
                    {{-- Modal Header --}}
                    <div class="flex items-center justify-between px-6 py-4 border-b" style="border-color: var(--border);">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg">
                                <x-heroicon name="{{ $isEditing ? 'pencil' : 'user-plus' }}" class="w-5 h-5 text-white" />
                            </div>
                            <div>
                                <h3 id="client-form-title" class="text-lg font-semibold" style="color: var(--foreground);">
                                    {{ $isEditing ? 'Edit Client' : 'Add New Client' }}
                                </h3>
                                <p id="client-form-desc" class="text-sm" style="color: var(--muted-foreground);">
                                    {{ $isEditing ? 'Update client information' : 'Create a new client account' }}
                                </p>
                            </div>
                        </div>
                        <button type="button" wire:click="closeModal" class="p-2 rounded-lg transition-colors hover:bg-gray-100 dark:hover:bg-gray-800">
                            <x-heroicon name="x-mark" class="w-5 h-5" style="color: var(--muted-foreground);" />
                        </button>
                    </div>

                    {{-- Modal Form --}}
                    <form wire:submit.prevent="save">
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
                                       autocomplete="name"
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
                                       autocomplete="email"
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
                                           autocomplete="tel"
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
                                           autocomplete="off"
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
                                          autocomplete="street-address"
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
                        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t" style="border-color: var(--border);">
                            <button type="button" wire:click="closeModal" class="w-full sm:w-auto btn-secondary px-4 py-2 text-sm">
                                <x-heroicon name="x-mark" class="w-4 h-4" />
                                Cancel
                            </button>

                            <button type="submit" class="w-full sm:w-auto btn px-4 py-2 text-sm" wire:loading.attr="disabled" wire:target="save">
                                <svg wire:loading wire:target="save" class="animate-spin w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                <x-heroicon name="{{ $isEditing ? 'check' : 'plus' }}" class="w-4 h-4" wire:loading.remove wire:target="save" />
                                <span wire:loading.remove wire:target="save">{{ $isEditing ? 'Update Client' : 'Create Client' }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

