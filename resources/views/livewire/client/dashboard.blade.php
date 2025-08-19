<div class="p-6 fade-in">
    <!-- Header -->
    <div class="mb-8" style="display: flex; justify-content: space-between; align-items: start;">
        <div>
            <h1 style="font-size: 2rem; font-weight: bold; color: var(--foreground); margin-bottom: 0.5rem;">
                Welcome, {{ auth()->user()->name }}
            </h1>
            <p style="color: var(--muted-foreground); font-size: 1rem;">
                Manage your vessels, bookings, and payments
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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 slide-up" style="animation-delay: 0.1s;">
        <div class="card">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                <div style="display: flex; align-items: center;">
                    <div style="background-color: var(--primary); color: var(--primary-foreground); padding: 0.75rem; border-radius: var(--radius); margin-right: 1rem;">
                        <x-heroicon name="truck" class="w-5 h-5" />
                    </div>
                    <div>
                        <h3 style="font-size: 0.75rem; font-weight: 500; color: var(--muted-foreground); margin: 0; text-transform: uppercase;">My Vessels</h3>
                        <p style="font-size: 1.5rem; font-weight: bold; color: var(--foreground); margin: 0;">3</p>
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
                        <p style="font-size: 1.5rem; font-weight: bold; color: var(--foreground); margin: 0;">2</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 1rem;">
                <div style="display: flex; align-items: center;">
                    <div style="background-color: var(--chart-3); color: var(--primary-foreground); padding: 0.75rem; border-radius: var(--radius); margin-right: 1rem;">
                        <x-heroicon name="document-text" class="w-5 h-5" />
                    </div>
                    <div>
                        <h3 style="font-size: 0.75rem; font-weight: 500; color: var(--muted-foreground); margin: 0; text-transform: uppercase;">Pending Invoices</h3>
                        <p style="font-size: 1.5rem; font-weight: bold; color: var(--foreground); margin: 0;">1</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 slide-up" style="animation-delay: 0.3s;">
        <!-- My Bookings -->
        <div class="card">
            <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--foreground); margin-bottom: 1rem;">
                <x-heroicon name="calendar-days" class="w-5 h-5 inline mr-2" />
                Recent Bookings
            </h2>
            <div style="space-y: 0.75rem;">
                <div style="border: 1px solid var(--border); border-radius: var(--radius); padding: 1rem;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                        <h3 style="font-weight: 600; color: var(--foreground); margin: 0;">Marina Bay - Slot A12</h3>
                        <span style="background-color: var(--chart-2); color: var(--primary-foreground); padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem;">Active</span>
                    </div>
                    <p style="color: var(--muted-foreground); font-size: 0.875rem; margin: 0;">Vessel: Ocean Explorer</p>
                    <p style="color: var(--muted-foreground); font-size: 0.875rem; margin: 0;">Jan 15 - Jan 20, 2024</p>
                </div>
                <div style="border: 1px solid var(--border); border-radius: var(--radius); padding: 1rem;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                        <h3 style="font-weight: 600; color: var(--foreground); margin: 0;">Harbor Point - Slot B08</h3>
                        <span style="background-color: var(--muted); color: var(--muted-foreground); padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem;">Completed</span>
                    </div>
                    <p style="color: var(--muted-foreground); font-size: 0.875rem; margin: 0;">Vessel: Sea Breeze</p>
                    <p style="color: var(--muted-foreground); font-size: 0.875rem; margin: 0;">Jan 01 - Jan 05, 2024</p>
                </div>
            </div>
            <button class="btn w-full mt-4">
                <x-heroicon name="plus" class="w-4 h-4" />
                New Booking
            </button>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--foreground); margin-bottom: 1rem;">
                <x-heroicon name="lightning-bolt" class="w-5 h-5 inline mr-2" />
                Quick Actions
            </h2>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1.5rem;">
                <button class="btn-secondary" style="font-size: 0.875rem; padding: 0.75rem;">
                    <x-heroicon name="plus" class="w-4 h-4" />
                    Add Vessel
                </button>
                <button class="btn-secondary" style="font-size: 0.875rem; padding: 0.75rem;">
                    <x-heroicon name="calendar-days" class="w-4 h-4" />
                    Book Slot
                </button>
                <button class="btn-secondary" style="font-size: 0.875rem; padding: 0.75rem;">
                    <x-heroicon name="document-text" class="w-4 h-4" />
                    View Invoices
                </button>
                <button class="btn-secondary" style="font-size: 0.875rem; padding: 0.75rem;">
                    <x-heroicon name="cog-6-tooth" class="w-4 h-4" />
                    Settings
                </button>
            </div>

            <!-- Notifications -->
            <div style="border-top: 1px solid var(--border); padding-top: 1rem;">
                <h3 style="font-size: 1rem; font-weight: 600; color: var(--foreground); margin-bottom: 0.75rem;">
                    <x-heroicon name="bell" class="w-4 h-4 inline mr-2" />
                    Notifications
                </h3>
                <div style="space-y: 0.5rem;">
                    <p style="font-size: 0.875rem; color: var(--foreground); margin: 0;">
                        <span style="color: var(--chart-2); font-weight: 500;">✓</span> Booking confirmed for Ocean Explorer
                    </p>
                    <p style="font-size: 0.875rem; color: var(--foreground); margin: 0;">
                        <span style="color: var(--chart-4); font-weight: 500;">!</span> Invoice #INV-2024-00124 due in 3 days
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
