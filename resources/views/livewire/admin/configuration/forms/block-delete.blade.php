<div>
@if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-data
         x-init="$nextTick(() => { document.body.style.overflow='hidden' })"
         x-on:keydown.escape.window="$wire.closeModal()"
         x-on:block-delete:closed.window="$nextTick(() => { document.body.style.overflow=''; })"
         wire:ignore.self>
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeModal"></div>
        <div class="relative w-full max-w-md" style="background:var(--card); border:1px solid var(--border); border-radius:var(--radius); box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); animation:modal-appear .2s ease-out;">
            <div class="px-6 py-4 border-b" style="border-color:var(--border);">
                <h3 class="text-lg font-semibold" style="color:var(--foreground);">Delete Block</h3>
            </div>
            <div class="px-6 py-4">
                <p class="text-sm" style="color:var(--foreground);">
                    Are you sure you want to delete <span class="font-semibold">{{ $block?->name }}</span>? This action cannot be undone.
                </p>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t" style="border-color:var(--border);">
                <button type="button" wire:click="closeModal" class="btn-secondary px-3 py-2 h-10 text-sm">Cancel</button>
                <button type="button" wire:click="delete" class="btn-destructive">Delete</button>
            </div>
        </div>
    </div>
    <style>
        @keyframes modal-appear {from {opacity:0; transform:scale(0.95) translateY(-10px);} to {opacity:1; transform:scale(1) translateY(0);}}
    </style>
@endif
</div>
