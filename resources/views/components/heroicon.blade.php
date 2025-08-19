@props(['name', 'type' => 'outline', 'class' => 'w-6 h-6', 'size' => '24'])

@php
    $iconPath = base_path("node_modules/heroicons/{$size}/{$type}/{$name}.svg");
    $iconContent = file_exists($iconPath) ? file_get_contents($iconPath) : null;
    
    // Try different sizes if not found
    if (!$iconContent && $size === '24') {
        $iconPath = base_path("node_modules/heroicons/20/{$type}/{$name}.svg");
        $iconContent = file_exists($iconPath) ? file_get_contents($iconPath) : null;
    }
@endphp

@if($iconContent)
    @php
        // Safely set class on the <svg> element only
        $safeClass = e($class, false);
        $iconContent = preg_replace(
            '/(<svg\b[^>]*?)\sclass=(["\']).*?\2/i',
            '$1 class="' . $safeClass . '"',
            $iconContent,
            1,
            $replaced
        );
        if (!$replaced) {
            $iconContent = preg_replace(
                '/<svg\b/i',
                '<svg class="' . $safeClass . '"',
                $iconContent,
                1
            );
        }
    @endphp
    {!! $iconContent !!}
@else
    <!-- Fallback icon if not found -->
    <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
    </svg>
@endif