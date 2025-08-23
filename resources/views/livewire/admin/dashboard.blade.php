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
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-3 mb-8 slide-up" style="animation-delay: 0.1s;">
        <!-- Available Slots Today -->
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon success">
                    <x-heroicon name="check-circle" class="size-5 md:w-4 md:h-4 lg:w-5 lg:h-5" />
                </div>
                <span class="stat-label">Available Today</span>
            </div>
            <div class="stat-value">{{ $stats['available_slots_today'] ?? 24 }}</div>
        </div>

        <!-- Active Bookings Today -->
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon primary">
                    <x-heroicon name="calendar-days" class="w-5 h-5 md:w-4 md:h-4 lg:w-5 lg:h-5" />
                </div>
                <span class="stat-label">Bookings Today</span>
            </div>
            <div class="stat-value">{{ $stats['bookings_today'] ?? 8 }}</div>
        </div>

        <!-- Pending Payments -->
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon warning">
                    <x-heroicon name="clock" class="w-5 h-5 md:w-4 md:h-4 lg:w-5 lg:h-5" />
                </div>
                <span class="stat-label">Pending Payments</span>
            </div>
            <div class="stat-value">{{ $stats['pending_payments'] ?? 12 }}</div>
        </div>

        <!-- Weekly Revenue MVR -->
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon info">
                    <x-heroicon name="banknotes" class="w-5 h-5 md:w-4 md:h-4 lg:w-5 lg:h-5" />
                </div>
                <span class="stat-label">Weekly MVR</span>
            </div>
            <div class="stat-value text-sm md:text-base lg:text-xl">{{ number_format($stats['weekly_mvr'] ?? 15750) }}</div>
        </div>

        <!-- Total Properties -->
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon secondary">
                    <x-heroicon name="building-office-2" class="w-5 h-5 md:w-4 md:h-4 lg:w-5 lg:h-5" />
                </div>
                <span class="stat-label">Properties</span>
            </div>
            <div class="stat-value">{{ $stats['properties'] ?? 6 }}</div>
        </div>

        <!-- Incomplete Profiles -->
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon danger">
                    <x-heroicon name="exclamation-triangle" class="w-5 h-5 md:w-4 md:h-4 lg:w-5 lg:h-5" />
                </div>
                <span class="stat-label">Incomplete Profiles</span>
            </div>
            <div class="stat-value">{{ $stats['incomplete_profiles'] ?? 5 }}</div>
        </div>

        <!-- Upcoming Events -->
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon accent">
                    <x-heroicon name="star" class="w-5 h-5 md:w-4 md:h-4 lg:w-5 lg:h-5" />
                </div>
                <span class="stat-label">Upcoming Events</span>
            </div>
            <div class="stat-value">{{ $stats['upcoming_events'] ?? 3 }}</div>
        </div>

        <!-- Active Vessels -->
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon teal">
                    <x-heroicon name="rocket-launch" class="w-5 h-5 md:w-4 md:h-4 lg:w-5 lg:h-5" />
                </div>
                <span class="stat-label">Active Vessels</span>
            </div>
            <div class="stat-value">{{ $stats['active_vessels'] ?? 18 }}</div>
        </div>
    </div>

    <!-- Enhanced Navigation Section -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 slide-up" style="animation-delay: 0.3s;">
        <!-- Management Hub -->
        <div class="card nav-section-card xl:col-span-2">
            <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 1.5rem;">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--foreground); display: flex; align-items: center; gap: 0.75rem;">
                    <div class="nav-icon-wrapper" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); padding: 0.75rem; border-radius: 12px; box-shadow: 0 4px 16px rgba(99, 102, 241, 0.3);">
                        <x-heroicon name="squares-plus" class="w-6 h-6 text-white" />
                    </div>
                    Management Hub
                </h2>
            </div>
            
            <!-- Primary Navigation Grid -->
            <div class="nav-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">

                <!-- Vessel Management -->
                <a href="{{ route('admin.management.vessels') }}" class="nav-card management-nav-item text-white" data-section="vessels" style="text-decoration: none; background: linear-gradient(135deg, #059669 0%, #10b981 50%, #10b981 100%);">
                    <div class="nav-card-inner">
                        <div class="nav-icon">
                            <x-heroicon name="rocket-launch" class="w-6 h-6 text-white stroke-1" />
                        </div>
                        <h3>Vessels</h3>
                        <div class="nav-arrow">
                            <x-heroicon name="arrow-right" class="w-5 h-5" />
                        </div>
                    </div>
                </a>

                <!-- Client Management -->
                <a href="{{ route('admin.management.clients') }}" class="nav-card management-nav-item text-white" data-section="clients" style="text-decoration: none; background: linear-gradient(135deg, #dc2626 0%, #f87171 100%);">
                    <div class="nav-card-inner">
                        <div class="nav-icon">
                            <x-heroicon name="user-group" class="w-6 h-6 text-white stroke-1" />
                        </div>
                        <h3>Clients</h3>
                        <div class="nav-arrow">
                            <x-heroicon name="arrow-right" class="w-5 h-5" />
                        </div>
                    </div>
                </a>

                <!-- Booking Management -->
                <a href="#" class="nav-card management-nav-item text-white" data-section="bookings" style="text-decoration: none; background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);">
                    <div class="nav-card-inner">
                        <div class="nav-icon">
                            <x-heroicon name="calendar-days" class="w-6 h-6 text-white stroke-1" />
                        </div>
                        <h3>Bookings</h3>
                        <div class="nav-arrow">
                            <x-heroicon name="arrow-right" class="w-5 h-5" />
                        </div>
                    </div>
                </a>

                <!-- Financial Management -->
                <a href="#" class="nav-card management-nav-item text-white" data-section="finance" style="text-decoration: none; background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);">
                    <div class="nav-card-inner">
                        <div class="nav-icon">
                            <x-heroicon name="banknotes" class="w-6 h-6 text-white stroke-1" />
                        </div>
                        <h3>Finance</h3>
                        <div class="nav-arrow">
                            <x-heroicon name="arrow-right" class="w-5 h-5" />
                        </div>
                    </div>
                </a>
            </div>

            <!-- System Tools Row -->
            <div style="border-top: 1px solid var(--border); padding-top: 1.5rem;">
                <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--muted-foreground); margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.05em;">
                    System Tools
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 0.75rem;">
                    <a href="{{ route('admin.configuration') }}" class="system-tool-btn">
                        <x-heroicon name="cog-6-tooth" class="w-4 h-4" />
                        <span>Configuration</span>
                    </a>
                    <button class="system-tool-btn">
                        <x-heroicon name="chart-bar-square" class="w-4 h-4" />
                        <span>Analytics</span>
                    </button>
                    <button class="system-tool-btn">
                        <x-heroicon name="document-chart-bar" class="w-4 h-4" />
                        <span>Reports</span>
                    </button>
                    <button class="system-tool-btn">
                        <x-heroicon name="shield-check" class="w-4 h-4" />
                        <span>Security</span>
                    </button>
                </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add staggered animations to navigation items
    const navItems = document.querySelectorAll('.management-nav-item');
    const systemTools = document.querySelectorAll('.system-tool-btn');
    
    // Apply slide-up animation to nav items with delays
    navItems.forEach((item, index) => {
        item.classList.add('slide-up');
        item.style.animationDelay = `${0.4 + (index * 0.1)}s`;
    });
    
    // Apply slide-up animation to system tools with delays
    systemTools.forEach((tool, index) => {
        tool.classList.add('slide-up');
        tool.style.animationDelay = `${0.8 + (index * 0.1)}s`;
    });
    
    // Add enhanced hover effects
    navItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            const icon = this.querySelector('.nav-icon');
            if (icon) {
                icon.style.transform = 'scale(1.15) rotate(5deg)';
            }
        });
        
        item.addEventListener('mouseleave', function() {
            const icon = this.querySelector('.nav-icon');
            if (icon) {
                icon.style.transform = 'scale(1) rotate(0deg)';
            }
        });
    });
    
    // Add ripple effect on click
    document.querySelectorAll('.nav-card, .system-tool-btn').forEach(element => {
        element.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(99, 102, 241, 0.3);
                transform: scale(0);
                animation: ripple-animation 0.6s linear;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                pointer-events: none;
                z-index: 10;
            `;
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            ripple.addEventListener('animationend', () => {
                ripple.remove();
            });
        });
    });
    
    // Add CSS for ripple animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple-animation {
            to {
                transform: scale(2);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
});
</script>