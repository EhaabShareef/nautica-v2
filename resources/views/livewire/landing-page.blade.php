<div>
    <!-- Dark Mode Toggle -->
    <button wire:click="toggleDarkMode" class="theme-toggle" title="Toggle dark mode">
        @if($darkMode)
            <x-heroicon name="sun" class="w-5 h-5" />
        @else
            <x-heroicon name="moon" class="w-5 h-5" />
        @endif
    </button>

    <!-- Hero Section -->
    <div class="hero fade-in">
        <div class="slide-up" style="animation-delay: 0.1s;">
            <h1 class="hero-title">Nautica</h1>
            <p class="hero-subtitle">Modern Vessel Parking Slot Rental Service</p>
        </div>
        
        <div class="slide-up" style="animation-delay: 0.3s;">
            <p style="color: var(--muted-foreground); font-size: 1rem; margin: 0 0 2rem 0; max-width: 500px;">
                Streamline your marina operations with our comprehensive booking system. 
                Manage vessel reservations, slot assignments, and billing with ease.
            </p>
        </div>

        <div class="hero-actions slide-up" style="animation-delay: 0.5s;">
            <a href="/login" class="btn">
                <x-heroicon name="arrow-right-on-rectangle" class="w-4 h-4" />
                Sign In
            </a>
            <a href="/register" class="btn-secondary">
                <x-heroicon name="user-plus" class="w-4 h-4" />
                Get Started
            </a>
        </div>

        <!-- Feature Cards -->
        <div class="slide-up" style="animation-delay: 0.7s; display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-top: 3rem; max-width: 900px; width: 100%;">
            <div class="card">
                <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                    <div style="background-color: var(--primary); color: var(--primary-foreground); padding: 0.75rem; border-radius: var(--radius); margin-right: 1rem;">
                        <x-heroicon name="building-office-2" class="w-5 h-5" />
                    </div>
                    <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600;">Slot Management</h3>
                </div>
                <p style="margin: 0; color: var(--muted-foreground); font-size: 0.875rem;">
                    Organize properties, blocks, zones, and individual parking slots with constraint management.
                </p>
            </div>

            <div class="card">
                <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                    <div style="background-color: var(--chart-2); color: var(--primary-foreground); padding: 0.75rem; border-radius: var(--radius); margin-right: 1rem;">
                        <x-heroicon name="calendar-days" class="w-5 h-5" />
                    </div>
                    <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600;">Smart Booking</h3>
                </div>
                <p style="margin: 0; color: var(--muted-foreground); font-size: 0.875rem;">
                    Automated booking workflow with approval system, conflict detection, and real-time availability.
                </p>
            </div>

            <div class="card">
                <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                    <div style="background-color: var(--chart-3); color: var(--primary-foreground); padding: 0.75rem; border-radius: var(--radius); margin-right: 1rem;">
                        <x-heroicon name="credit-card" class="w-5 h-5" />
                    </div>
                    <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600;">Billing & Finance</h3>
                </div>
                <p style="margin: 0; color: var(--muted-foreground); font-size: 0.875rem;">
                    Flexible contracts, automated invoicing, payment processing, and comprehensive reporting.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    // Theme management
    function initTheme() {
        const savedTheme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const theme = savedTheme || (prefersDark ? 'dark' : 'light');
        
        document.documentElement.classList.toggle('dark', theme === 'dark');
        @this.set('darkMode', theme === 'dark');
    }

    // Initialize after Livewire boots to ensure @this is available
    document.addEventListener('livewire:load', () => {
        initTheme();
    // Listen for server-dispatched browser events from Livewire (v3)
    window.addEventListener('theme-changed', (e) => {
        const theme = e.detail?.theme;
        if (!theme) return;
        document.documentElement.classList.toggle('dark', theme === 'dark');
        localStorage.setItem('theme', theme);
    });
    });

    // Listen for system theme changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (!localStorage.getItem('theme')) {
            document.documentElement.classList.toggle('dark', e.matches);
            @this.set('darkMode', e.matches);
        }
    });
</script>
