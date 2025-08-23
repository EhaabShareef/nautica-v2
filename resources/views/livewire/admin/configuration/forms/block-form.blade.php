<div>
@if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-data
         x-init="$nextTick(() => { document.body.style.overflow='hidden' })"
         x-on:keydown.escape.window="$wire.closeModal()"
         x-on:block-form:closed.window="$nextTick(() => { document.body.style.overflow=''; })"
         wire:ignore.self>
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeModal"></div>
        <div class="relative w-full max-w-xl" style="background: var(--card); border:1px solid var(--border); border-radius:var(--radius); box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); animation:modal-appear .2s ease-out;">
            <div class="flex items-center justify-between px-6 py-4 border-b" style="border-color:var(--border);">
                <h3 class="text-lg font-semibold" style="color:var(--foreground);">{{ $editingBlock ? 'Edit Block' : 'Create Block' }}</h3>
                <button wire:click="closeModal" class="p-2 rounded-lg transition-colors hover:bg-gray-100 dark:hover:bg-gray-800">
                    <x-heroicon name="x-mark" class="w-5 h-5" style="color:var(--muted-foreground);" />
                </button>
            </div>
            <form wire:submit.prevent="save" class="px-6 py-4">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color:var(--foreground);">Property</label>
                        <select wire:model="property_id" class="form-input">
                            <option value="">Select property</option>
                            @foreach($properties as $property)
                                <option value="{{ $property->id }}">{{ $property->name }}</option>
                            @endforeach
                        </select>
                        @error('property_id') <span class="text-sm mt-1 text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color:var(--foreground);">Block Name</label>
                        <input type="text" wire:model="name" class="form-input" placeholder="Enter block name">
                        @error('name') <span class="text-sm mt-1 text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color:var(--foreground);">Block Code</label>
                        <input type="text" wire:model="code" class="form-input" placeholder="Enter unique code">
                        @error('code') <span class="text-sm mt-1 text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color:var(--foreground);">Location</label>
                        <input type="text" wire:model="location" class="form-input" placeholder="e.g., North Wing">
                        @error('location') <span class="text-sm mt-1 text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" id="block_is_active">
                        <label for="block_is_active" class="ml-2 text-sm" style="color:var(--foreground);">Block is active</label>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t" style="border-color:var(--border);">
                    <button type="button" wire:click="closeModal" class="btn-secondary px-3 py-2 h-10 text-sm">Cancel</button>
                    <button type="submit" class="btn px-3 py-2 h-10 text-sm">{{ $editingBlock ? 'Update Block' : 'Create Block' }}</button>
                </div>
            </form>
        </div>
    </div>
    <style>
        @keyframes modal-appear {
            from {opacity:0; transform:scale(0.95) translateY(-10px);}
            to {opacity:1; transform:scale(1) translateY(0);}
        }
    </style>
@endif
</div>
