<div>
@if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-data
         x-init="$nextTick(() => { document.body.style.overflow='hidden' })"
         x-on:keydown.escape.window="$wire.closeModal()"
         x-on:property-form:closed.window="$nextTick(() => { document.body.style.overflow=''; })"
         wire:ignore.self>
        
        {{-- Full-screen backdrop --}}
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"
             wire:click="closeModal"
             style="backdrop-filter: blur(8px);"></div>

        {{-- Modal panel --}}
        <div class="relative w-full max-w-2xl transform transition-all duration-200"
             style="background: var(--card); 
                    border: 1px solid var(--border); 
                    border-radius: var(--radius);
                    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                    animation: modal-appear 0.2s ease-out;">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b"
                 style="border-color: var(--border);">
                <h3 class="text-lg font-semibold" style="color: var(--foreground);">
                    {{ $editingProperty ? 'Edit Property' : 'Create Property' }}
                </h3>
                <button wire:click="closeModal" 
                        class="p-2 rounded-lg transition-colors hover:bg-gray-100 dark:hover:bg-gray-800">
                    <x-heroicon name="x-mark" class="w-5 h-5" style="color: var(--muted-foreground);" />
                </button>
            </div>

            {{-- Form --}}
            <form wire:submit.prevent="save" class="px-6 py-4">
                <div class="space-y-4">
                    {{-- Property Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium mb-2" style="color: var(--foreground);">
                            Property Name
                        </label>
                        <input type="text" id="name" wire:model="name" class="form-input"
                               placeholder="Enter property name">
                        @error('name') <span class="text-sm mt-1 text-red-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- Property Code --}}
                    <div>
                        <label for="code" class="block text-sm font-medium mb-2" style="color: var(--foreground);">
                            Property Code
                        </label>
                        <input type="text" id="code" wire:model="code" class="form-input"
                               placeholder="Enter unique property code">
                        @error('code') <span class="text-sm mt-1 text-red-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- Timezone and Currency --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="timezone" class="block text-sm font-medium mb-2" style="color: var(--foreground);">
                                Timezone
                            </label>
                            <input type="text" id="timezone" wire:model="timezone" class="form-input"
                                   placeholder="e.g., UTC, America/New_York">
                            @error('timezone') <span class="text-sm mt-1 text-red-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="currency" class="block text-sm font-medium mb-2" style="color: var(--foreground);">
                                Currency
                            </label>
                            <input type="text" id="currency" wire:model="currency" class="form-input" maxlength="3"
                                   placeholder="e.g., USD, EUR">
                            @error('currency') <span class="text-sm mt-1 text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Address --}}
                    <div>
                        <label for="address" class="block text-sm font-medium mb-2" style="color: var(--foreground);">
                            Address
                        </label>
                        <textarea id="address" wire:model="address" class="form-input" rows="3"
                                  placeholder="Enter property address"></textarea>
                        @error('address') <span class="text-sm mt-1 text-red-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- Active Status --}}
                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" wire:model="is_active" 
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <label for="is_active" class="ml-2 text-sm" style="color: var(--foreground);">
                            Property is active
                        </label>
                    </div>
                    @error('is_active') <span class="text-sm mt-1 text-red-600">{{ $message }}</span> @enderror
                </div>

                {{-- Footer Actions --}}
                <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t"
                     style="border-color: var(--border);">
                    <button type="button" wire:click="closeModal" class="btn-secondary px-3 py-2 h-10 text-sm">
                        Cancel
                    </button>
                    <button type="submit" class="btn px-3 py-2 h-10 text-sm">
                        {{ $editingProperty ? 'Update Property' : 'Create Property' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        @keyframes modal-appear {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(-10px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
    </style>
@endif
</div>