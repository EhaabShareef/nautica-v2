<div x-data="{ open: @entangle('showModal') }">
    <div x-show="open" class="fixed inset-0 flex items-center justify-center bg-black/50">
        <div class="bg-white p-4 w-full max-w-lg rounded">
            <h2 class="text-lg font-semibold mb-4">{{ $editingSetting ? 'Edit Setting' : 'New Setting' }}</h2>
            <form wire:submit.prevent="save" class="space-y-3">
                <div>
                    <label class="block text-sm">Key</label>
                    <input type="text" wire:model="key" class="input w-full" {{ $editingSetting ? 'readonly' : '' }} />
                    @error('key') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm">Group</label>
                    <input type="text" wire:model="group" class="input w-full" />
                </div>
                <div>
                    <label class="block text-sm">Label</label>
                    <input type="text" wire:model="label" class="input w-full" />
                </div>
                <div>
                    <label class="block text-sm">Description</label>
                    <textarea wire:model="description" class="input w-full"></textarea>
                </div>
                <div>
                    <label class="block text-sm">Value</label>
                    <textarea wire:model="valueInput" class="input w-full"></textarea>
                    @error('valueInput') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-1 text-sm"><input type="checkbox" wire:model="is_protected" /> Protected</label>
                    <label class="flex items-center gap-1 text-sm"><input type="checkbox" wire:model="is_active" /> Active</label>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" wire:click="closeModal" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

