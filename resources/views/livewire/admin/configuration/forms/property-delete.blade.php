<div>
@if($showModal && $property)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-data
         x-init="$nextTick(() => { document.body.style.overflow='hidden' })"
         x-on:keydown.escape.window="$wire.closeModal()"
         x-on:property-delete:closed.window="$nextTick(() => { document.body.style.overflow=''; })"
         wire:ignore.self>
        
        {{-- Full-screen backdrop --}}
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"
             wire:click="closeModal"
             style="backdrop-filter: blur(8px);"></div>

        {{-- Modal panel --}}
        <div class="relative w-full max-w-md transform transition-all duration-200"
             style="background: var(--card); 
                    border: 1px solid var(--border); 
                    border-radius: var(--radius);
                    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                    animation: modal-appear 0.2s ease-out;">

            {{-- Header --}}
            <div class="px-6 py-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-2 rounded-full" style="background-color: rgba(239, 68, 68, 0.1);">
                        <x-heroicon name="exclamation-triangle" class="w-6 h-6 text-red-600" />
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold" style="color: var(--foreground);">
                            Delete Property
                        </h3>
                        <p class="text-sm mt-1" style="color: var(--muted-foreground);">
                            This action cannot be undone
                        </p>
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div class="px-6 py-4">
                <p class="text-sm" style="color: var(--foreground);">
                    Are you sure you want to delete <strong>{{ $property->name }}</strong>?
                </p>
                
                @if($property->blocks->count() > 0)
                    <div class="mt-4 p-3 rounded-lg" style="background-color: var(--muted); border: 1px solid var(--border);">
                        <p class="text-sm font-medium" style="color: var(--foreground);">
                            This will also permanently delete:
                        </p>
                        <ul class="mt-2 text-sm space-y-1" style="color: var(--muted-foreground);">
                            <li>• {{ $property->blocks->count() }} block(s)</li>
                            @php
                                $totalZones = $property->blocks->sum(fn($block) => $block->zones->count());
                                $totalSlots = $property->blocks->sum(fn($block) => $block->zones->sum(fn($zone) => $zone->slots->count()));
                            @endphp
                            @if($totalZones > 0)
                                <li>• {{ $totalZones }} zone(s)</li>
                            @endif
                            @if($totalSlots > 0)
                                <li>• {{ $totalSlots }} slot(s)</li>
                            @endif
                        </ul>
                    </div>
                @endif
            </div>

            {{-- Footer Actions --}}
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t"
                 style="border-color: var(--border);">
                <button type="button" wire:click="closeModal" class="btn-secondary">
                    Cancel
                </button>
                <button type="button" wire:click="delete" 
                        class="btn px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                    Delete Property
                </button>
            </div>
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