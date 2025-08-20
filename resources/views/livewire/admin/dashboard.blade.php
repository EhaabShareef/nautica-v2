<div class="p-6 fade-in">
    <!-- Header -->
    <div class="mb-8" style="display: flex; justify-content: space-between; align-items: start;">
        <div>
            <h1 style="font-size: 2rem; font-weight: bold; color: var(--foreground); margin-bottom: 0.5rem;">
                Welcome back, {{ auth()->user()->name }}
            </h1>
            <p style="color: var(--muted-foreground); font-size: 1rem;">
                Admin Dashboard - Manage your Nautica system
            </p>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-secondary" style="font-size: 0.875rem; padding: 0.5rem 1rem;">
                <x-heroicon name="arrow-right-on-rectangle" class="w-4 h-4" />
                Logout
            </button>
        </form>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 slide-up" style="animation-delay: 0.1s;">
        <div class="card">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                <div style="display: flex; align-items: center;">
                    <div style="background-color: var(--primary); color: var(--primary-foreground); padding: 0.75rem; border-radius: var(--radius); margin-right: 1rem;">
                        <x-heroicon name="building-office-2" class="w-5 h-5" />
                    </div>
                    <div>
                        <h3 style="font-size: 0.75rem; font-weight: 500; color: var(--muted-foreground); margin: 0; text-transform: uppercase;">Properties</h3>
                        <p style="font-size: 1.5rem; font-weight: bold; color: var(--foreground); margin: 0;">{{ $stats['properties'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                <div style="display: flex; align-items: center;">
                    <div style="background-color: var(--chart-2); color: var(--primary-foreground); padding: 0.75rem; border-radius: var(--radius); margin-right: 1rem;">
                        <x-heroicon name="calendar-days" class="w-5 h-5" />
                    </div>
                    <div>
                        <h3 style="font-size: 0.75rem; font-weight: 500; color: var(--muted-foreground); margin: 0; text-transform: uppercase;">Active Bookings</h3>
                        <p style="font-size: 1.5rem; font-weight: bold; color: var(--foreground); margin: 0;">{{ $stats['active_bookings'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                <div style="display: flex; align-items: center;">
                    <div style="background-color: var(--chart-3); color: var(--primary-foreground); padding: 0.75rem; border-radius: var(--radius); margin-right: 1rem;">
                        <x-heroicon name="users" class="w-5 h-5" />
                    </div>
                    <div>
                        <h3 style="font-size: 0.75rem; font-weight: 500; color: var(--muted-foreground); margin: 0; text-transform: uppercase;">Total Users</h3>
                        <p style="font-size: 1.5rem; font-weight: bold; color: var(--foreground); margin: 0;">{{ $stats['users'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                <div style="display: flex; align-items: center;">
                    <div style="background-color: var(--chart-4); color: var(--primary-foreground); padding: 0.75rem; border-radius: var(--radius); margin-right: 1rem;">
                        <x-heroicon name="credit-card" class="w-5 h-5" />
                    </div>
                    <div>
                        <h3 style="font-size: 0.75rem; font-weight: 500; color: var(--muted-foreground); margin: 0; text-transform: uppercase;">Revenue</h3>
                        <p style="font-size: 1.5rem; font-weight: bold; color: var(--foreground); margin: 0;">${{ number_format($stats['revenue'] ?? 0, 1) }}k</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 slide-up" style="animation-delay: 0.3s;">
        <div class="card">
            <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--foreground); margin-bottom: 1rem;">
                <x-heroicon name="lightning-bolt" class="w-5 h-5 inline mr-2" />
                Quick Actions
            </h2>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                <button class="btn-secondary" style="font-size: 0.875rem; padding: 0.75rem;">
                    <x-heroicon name="plus" class="w-4 h-4" />
                    Add Property
                </button>
                <button class="btn-secondary" style="font-size: 0.875rem; padding: 0.75rem;">
                    <x-heroicon name="calendar-days" class="w-4 h-4" />
                    View Schedule
                </button>
                <button class="btn-secondary" style="font-size: 0.875rem; padding: 0.75rem;">
                    <x-heroicon name="user-group" class="w-4 h-4" />
                    Manage Users
                </button>
                <button class="btn-secondary" style="font-size: 0.875rem; padding: 0.75rem;">
                    <x-heroicon name="chart-bar" class="w-4 h-4" />
                    View Reports
                </button>
                <a href="{{ route('admin.configuration') }}" class="btn-secondary" style="font-size: 0.875rem; padding: 0.75rem; display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">
                    <x-heroicon name="cog-6-tooth" class="w-4 h-4" />
                    Configuration
                </a>
                <button class="btn-secondary" style="font-size: 0.875rem; padding: 0.75rem;">
                    <x-heroicon name="document-text" class="w-4 h-4" />
                    System Logs
                </button>
            </div>
        </div>

        <div class="card">
            <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--foreground); margin-bottom: 1rem;">
                <x-heroicon name="bell" class="w-5 h-5 inline mr-2" />
                Recent Activity
            </h2>
            <div style="space-y: 0.75rem;">
                @forelse($recentActivities as $activity)
                    <div style="border-bottom: 1px solid var(--border); padding-bottom: 0.75rem; margin-bottom: 0.75rem;">
                        <p style="font-size: 0.875rem; color: var(--foreground); margin: 0;">
                            {{ e($activity->message) }}
                        </p>
                        <p style="font-size: 0.75rem; color: var(--muted-foreground); margin: 0;">
                            {{ $activity->created_at->diffForHumans() }}
                            @if($activity->user)
                                by {{ e($activity->user->name) }}
                            @endif
                        </p>
                    </div>
                @empty
                    <div style="text-align: center; padding: 2rem;">
                        <x-heroicon name="bell-slash" class="w-12 h-12 mx-auto mb-4" style="color: var(--muted-foreground);" />
                        <p style="font-size: 0.875rem; color: var(--muted-foreground); margin: 0;">
                            No recent activities to display
                        </p>
                        <p style="font-size: 0.75rem; color: var(--muted-foreground); margin: 0.5rem 0 0 0;">
                            Activity logs will appear here as users interact with the system
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>