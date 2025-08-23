<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mt-6 slide-up" style="animation-delay: 0.1s;">
    {{-- Total Clients --}}
    <div class="stat-card bg-blue-500/10 border-blue-300/30 dark:border-blue-500/20">
        <div class="flex items-center gap-3">
            <div class="stat-icon bg-blue-500">
                <x-heroicon name="users" class="w-5 h-5 text-white" />
            </div>
            <div>
                <p class="stat-number text-blue-700 dark:text-blue-300">{{ $stats['total_clients'] }}</p>
                <p class="stat-label text-blue-600 dark:text-blue-400">Total Clients</p>
            </div>
        </div>
    </div>

    {{-- Active Clients --}}
    <div class="stat-card bg-green-500/10 border-green-300/30 dark:border-green-500/20">
        <div class="flex items-center gap-3">
            <div class="stat-icon bg-green-500">
                <x-heroicon name="check-circle" class="w-5 h-5 text-white" />
            </div>
            <div>
                <p class="stat-number text-green-700 dark:text-green-300">{{ $stats['active_clients'] }}</p>
                <p class="stat-label text-green-600 dark:text-green-400">Active</p>
            </div>
        </div>
    </div>

    {{-- Inactive Clients --}}
    <div class="stat-card bg-red-500/10 border-red-300/30 dark:border-red-500/20">
        <div class="flex items-center gap-3">
            <div class="stat-icon bg-red-500">
                <x-heroicon name="x-circle" class="w-5 h-5 text-white" />
            </div>
            <div>
                <p class="stat-number text-red-700 dark:text-red-300">{{ $stats['inactive_clients'] }}</p>
                <p class="stat-label text-red-600 dark:text-red-400">Inactive</p>
            </div>
        </div>
    </div>

    {{-- Blacklisted Clients --}}
    <div class="stat-card bg-yellow-500/10 border-yellow-300/30 dark:border-yellow-500/20">
        <div class="flex items-center gap-3">
            <div class="stat-icon bg-yellow-500">
                <x-heroicon name="exclamation-triangle" class="w-5 h-5 text-white" />
            </div>
            <div>
                <p class="stat-number text-yellow-700 dark:text-yellow-300">{{ $stats['blacklisted_clients'] ?? 0 }}</p>
                <p class="stat-label text-yellow-600 dark:text-yellow-400">Blacklisted</p>
            </div>
        </div>
    </div>
</div>
