<div class="p-6 fade-in">
    <!-- Header -->
    <div class="mb-8" style="display: flex; justify-content: space-between; align-items: start;">
        <div>
            <h1 style="font-size: 2rem; font-weight: bold; color: var(--foreground); margin-bottom: 0.5rem;">
                Bookings Management
            </h1>
            <p style="color: var(--muted-foreground); font-size: 1rem;">
                Manage reservations, schedule, and booking operations
            </p>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8 slide-up" style="animation-delay: 0.1s;">
        <!-- Total Bookings -->
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon primary">
                    <x-heroicon name="calendar-days" class="w-5 h-5 md:w-4 md:h-4 lg:w-5 lg:h-5" />
                </div>
                <span class="stat-label">Total Bookings</span>
            </div>
            <div class="stat-value">24</div>
        </div>

        <!-- Active Today -->
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon success">
                    <x-heroicon name="check-circle" class="w-5 h-5 md:w-4 md:h-4 lg:w-5 lg:h-5" />
                </div>
                <span class="stat-label">Active Today</span>
            </div>
            <div class="stat-value">8</div>
        </div>

        <!-- Pending -->
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon warning">
                    <x-heroicon name="clock" class="w-5 h-5 md:w-4 md:h-4 lg:w-5 lg:h-5" />
                </div>
                <span class="stat-label">Pending</span>
            </div>
            <div class="stat-value">5</div>
        </div>

        <!-- This Month -->
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon info">
                    <x-heroicon name="chart-bar" class="w-5 h-5 md:w-4 md:h-4 lg:w-5 lg:h-5" />
                </div>
                <span class="stat-label">This Month</span>
            </div>
            <div class="stat-value">142</div>
        </div>
    </div>

    <!-- Booking Management Hub -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 slide-up" style="animation-delay: 0.3s;">
        <!-- Main Management Section -->
        <div class="card nav-section-card xl:col-span-2">
            <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 1.5rem;">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--foreground); display: flex; align-items: center; gap: 0.75rem;">
                    <div class="nav-icon-wrapper" style="background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%); padding: 0.75rem; border-radius: 12px; box-shadow: 0 4px 16px rgba(37, 99, 235, 0.3);">
                        <x-heroicon name="calendar-days" class="w-6 h-6 text-white" />
                    </div>
                    Booking Operations
                </h2>
            </div>
            
            <!-- Primary Navigation Grid -->
            <div class="nav-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">

                <!-- New Reservation -->
                <a href="{{ route('admin.bookings.new') }}" class="nav-card management-nav-item text-white" data-section="new-booking" style="text-decoration: none; background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);">
                    <div class="nav-card-inner">
                        <div class="nav-icon">
                            <x-heroicon name="plus-circle" class="w-6 h-6 text-white stroke-1" />
                        </div>
                        <h3>New Reservation</h3>
                        <div class="nav-arrow">
                            <x-heroicon name="arrow-right" class="w-5 h-5" />
                        </div>
                    </div>
                </a>

                <!-- Manage Reservations -->
                <div class="nav-card management-nav-item text-white" data-section="manage-bookings" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); opacity: 0.6; cursor: not-allowed;">
                    <div class="nav-card-inner">
                        <div class="nav-icon">
                            <x-heroicon name="clipboard-document-list" class="w-6 h-6 text-white stroke-1" />
                        </div>
                        <h3>Manage Reservations</h3>
                        <div class="nav-arrow">
                            <span style="font-size: 0.75rem; color: rgba(255, 255, 255, 0.8);">Soon</span>
                        </div>
                    </div>
                </div>

                <!-- Schedule View -->
                <div class="nav-card management-nav-item text-white" data-section="schedule" style="background: linear-gradient(135deg, #7c3aed 0%, #8b5cf6 100%); opacity: 0.6; cursor: not-allowed;">
                    <div class="nav-card-inner">
                        <div class="nav-icon">
                            <x-heroicon name="calendar" class="w-6 h-6 text-white stroke-1" />
                        </div>
                        <h3>Schedule</h3>
                        <div class="nav-arrow">
                            <span style="font-size: 0.75rem; color: rgba(255, 255, 255, 0.8);">Soon</span>
                        </div>
                    </div>
                </div>

                <!-- Reports -->
                <div class="nav-card management-nav-item text-white" data-section="reports" style="background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%); opacity: 0.6; cursor: not-allowed;">
                    <div class="nav-card-inner">
                        <div class="nav-icon">
                            <x-heroicon name="document-chart-bar" class="w-6 h-6 text-white stroke-1" />
                        </div>
                        <h3>Reports</h3>
                        <div class="nav-arrow">
                            <span style="font-size: 0.75rem; color: rgba(255, 255, 255, 0.8);">Soon</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Sidebar -->
        <div class="card">
            <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--foreground); margin-bottom: 1rem;">
                <x-heroicon name="clock" class="w-5 h-5 inline mr-2" />
                Recent Bookings
            </h2>
            <div style="space-y: 0.75rem;">
                <div style="border-bottom: 1px solid var(--border); padding-bottom: 0.75rem; margin-bottom: 0.75rem;">
                    <p style="font-size: 0.875rem; color: var(--foreground); margin: 0;">
                        New booking created for Marina Bay
                    </p>
                    <p style="font-size: 0.75rem; color: var(--muted-foreground); margin: 0;">
                        2 hours ago by Admin
                    </p>
                </div>
                <div style="border-bottom: 1px solid var(--border); padding-bottom: 0.75rem; margin-bottom: 0.75rem;">
                    <p style="font-size: 0.875rem; color: var(--foreground); margin: 0;">
                        Slot B-12 booking confirmed
                    </p>
                    <p style="font-size: 0.75rem; color: var(--muted-foreground); margin: 0;">
                        4 hours ago by System
                    </p>
                </div>
                <div style="border-bottom: 1px solid var(--border); padding-bottom: 0.75rem; margin-bottom: 0.75rem;">
                    <p style="font-size: 0.875rem; color: var(--foreground); margin: 0;">
                        Payment received for booking #BK-ABC123
                    </p>
                    <p style="font-size: 0.75rem; color: var(--muted-foreground); margin: 0;">
                        6 hours ago by Payment System
                    </p>
                </div>
                <div style="text-align: center; padding: 1rem;">
                    <p style="font-size: 0.875rem; color: var(--muted-foreground); margin: 0;">
                        View all activities
                    </p>
                </div>
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
    
    // Add enhanced hover effects (only for non-disabled items)
    navItems.forEach(item => {
        if (!item.style.cursor || item.style.cursor !== 'not-allowed') {
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
        }
    });
    
    // Add ripple effect on click for enabled items
    document.querySelectorAll('.nav-card:not([disabled]), .system-tool-btn:not([disabled])').forEach(element => {
        element.addEventListener('click', function(e) {
            // Don't add ripple to disabled items
            if (element.disabled || element.style.cursor === 'not-allowed') return;
            
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