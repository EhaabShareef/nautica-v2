<!DOCTYPE html>
<html lang="en" x-data="themeManager()" :class="{ 'dark': isDark }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Nautica</title>
    
    <!-- Figtree Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-background text-foreground font-sans antialiased">
    <!-- Admin Header -->
    <x-admin.header />
    
    <!-- Main Content Area -->
    <main class="admin-content">
        {{ $slot }}
    </main>
    
    @livewireScripts
    
    <!-- Theme Manager Alpine Component -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('themeManager', () => ({
                isDark: false,
                
                init() {
                    // Sync with store
                    this.isDark = Alpine.store('theme').isDark;
                    Alpine.store('theme').init();
                },
                
                get isDarkMode() {
                    return Alpine.store('theme').isDark;
                }
            }));
        });
    </script>
</body>
</html>