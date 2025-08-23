<div class="user-dropdown" x-data="userDropdown()" @click.away="close()">
    <!-- User Avatar & Trigger -->
    <button @click="toggle()" class="user-trigger" :class="{ 'active': isOpen }">
        <div class="user-avatar">
            <span class="avatar-initials">
                {{ substr(auth()->user()->name, 0, 1) }}{{ substr(strstr(auth()->user()->name, ' '), 1, 1) ?: '' }}
            </span>
        </div>
        <div class="user-info">
            <span class="user-name">{{ auth()->user()->name }}</span>
            <span class="user-role">{{ ucfirst(auth()->user()->user_type ?? 'Admin') }}</span>
        </div>
        <div class="dropdown-arrow" :class="{ 'rotated': isOpen }">
            <x-heroicon name="chevron-down" class="w-4 h-4" />
        </div>
    </button>
    
    <!-- Dropdown Menu -->
    <div x-show="isOpen" 
         x-transition:enter="dropdown-enter"
         x-transition:enter-start="dropdown-enter-start"
         x-transition:enter-end="dropdown-enter-end"
         x-transition:leave="dropdown-leave"
         x-transition:leave-start="dropdown-leave-start" 
         x-transition:leave-end="dropdown-leave-end"
         class="dropdown-menu"
         style="display: none;">
         
        <!-- User Info Section -->
        <div class="dropdown-section user-section">
            <div class="section-header">
                <div class="user-avatar large">
                    <span class="avatar-initials">
                        {{ substr(auth()->user()->name, 0, 1) }}{{ substr(strstr(auth()->user()->name, ' '), 1, 1) ?: '' }}
                    </span>
                </div>
                <div class="user-details">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-email">{{ auth()->user()->email }}</div>
                </div>
            </div>
        </div>
        
        <!-- Navigation Section -->
        @can('admin')
        <div class="dropdown-section nav-section">
            <a href="{{ route('admin.configuration') }}" class="dropdown-item">
                <x-heroicon name="cog-6-tooth" class="w-5 h-5" />
                <span>Configuration</span>
            </a>
        </div>
        @endcan
        
        <!-- Preferences Section -->
        <div class="dropdown-section preferences-section">
            <!-- Theme Toggle -->
            <div class="dropdown-item theme-toggle-item" @click.prevent="$store.theme.toggle()">
                <div class="theme-toggle-icon">
                    <x-heroicon name="sun" class="w-5 h-5 light-icon" x-show="!$store.theme.isDark" />
                    <x-heroicon name="moon" class="w-5 h-5 dark-icon" x-show="$store.theme.isDark" />
                </div>
                <span>Theme</span>
                <div class="toggle-switch" :class="{ 'active': $store.theme.isDark }">
                    <div class="toggle-slider"></div>
                </div>
            </div>
            
            <!-- Manage Preferences (TODO) -->
            <button class="dropdown-item disabled" disabled>
                <x-heroicon name="user-circle" class="w-5 h-5" />
                <span>Preferences</span>
                <span class="coming-soon">Soon</span>
            </button>
        </div>
        
        <!-- Logout Section -->
        <div class="dropdown-section logout-section">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item logout-item">
                    <x-heroicon name="arrow-right-on-rectangle" class="w-5 h-5" />
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('userDropdown', () => ({
        isOpen: false,
        
        toggle() {
            this.isOpen = !this.isOpen;
        },
        
        close() {
            this.isOpen = false;
        }
    }));
    
    // Create global theme store
    Alpine.store('theme', {
        isDark: false,
        
        init() {
            // Check localStorage first, then system preference
            const saved = localStorage.getItem('theme');
            if (saved) {
                this.isDark = saved === 'dark';
            } else {
                this.isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            }
            this.updateTheme();
        },
        
        toggle() {
            this.isDark = !this.isDark;
            this.updateTheme();
            localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
        },
        
        updateTheme() {
            if (this.isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    });
});
</script>