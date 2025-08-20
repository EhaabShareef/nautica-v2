<div>
    <!-- Header with Action Button -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--foreground); margin: 0;">Zones Management</h3>
            <p style="color: var(--muted-foreground); font-size: 0.875rem; margin: 0;">Define zones within blocks for precise area organization</p>
        </div>
        <button wire:click="create" class="btn" style="font-size: 0.875rem;">
            <x-heroicon name="plus" class="w-4 h-4" />
            Add Zone
        </button>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div style="background-color: var(--success); color: var(--success-foreground); padding: 1rem; border-radius: var(--radius); margin-bottom: 1rem;">
            {{ session('message') }}
        </div>
    @endif

    <div class="table-container">
        <p style="color: var(--muted-foreground); padding: 2rem; text-align: center;">
            Zones management interface - detailed implementation coming soon
        </p>
    </div>
</div>