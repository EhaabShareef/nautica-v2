<div x-data="{ open: @entangle('showModal') }">
    <div x-show="open" class="fixed inset-0 flex items-center justify-center bg-black/50">
        <div class="bg-white p-4 w-full max-w-md rounded">
            <h2 class="text-lg font-semibold mb-4">Delete Setting</h2>
            <p class="mb-4">Are you sure you want to delete this setting?</p>
            <div class="flex justify-end gap-2">
                <button type="button" wire:click="closeModal" class="btn-secondary">Cancel</button>
                <button type="button" wire:click="delete" class="btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>

