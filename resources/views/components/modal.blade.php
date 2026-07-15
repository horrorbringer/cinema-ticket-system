@props([
    'title' => null,
    'size' => 'md',
    'show' => false,
    'centered' => true,
    'fullscreen' => false,
    'noPadding' => false,
    'footer' => false,
    'closeable' => true,
    'closeOnEscape' => true,
    'closeOnClickOutside' => true,
    'zIndex' => 'z-50'
])

@php
    $sizeClasses = [
        'sm' => 'max-w-md',
        'md' => 'max-w-2xl',
        'lg' => 'max-w-4xl',
        'xl' => 'max-w-6xl',
        'full' => 'max-w-full',
    ];

    $sizeClass = isset($sizeClasses[$size]) ? $sizeClasses[$size] : $sizeClasses['md'];

    $containerClasses = "fixed inset-0 overflow-y-auto {$zIndex} {$sizeClass} w-full";
    $dialogClasses = "relative bg-[#1a1a1a] rounded-lg shadow-xl transform transition-all border border-white/10";
    $contentPadding = $noPadding ? 'p-0' : 'p-6';

    $baseClasses = "{$containerClasses} {$dialogClasses} {$contentPadding}";

    $fullscreenClass = $fullscreen ? 'w-screen h-screen max-w-none rounded-none' : '';

    $alignClass = $centered ? 'flex items-center justify-center min-h-screen p-4' : 'p-4';
@endphp

<!-- Backdrop -->
<div x-show="{{ $show }}" 
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-black bg-opacity-50 transition-opacity {{ $zIndex }}"
     style="display: {{ $show ? 'block' : 'none' }};"
     {{ $closeOnClickOutside ? '@click' : '' }}="{{ $closeOnClickOutside ? '$dispatch(\'modal-close\')' : '' }}">
</div>

<!-- Modal -->
<div x-show="{{ $show }}" 
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-4"
     class="{{ $baseClasses }} {{ $fullscreenClass }} {{ $alignClass }}"
     style="display: {{ $show ? 'block' : 'none' }};"
     role="dialog"
     aria-modal="true"
     {{ $closeOnEscape ? '@keydown.escape.window' : '' }}="{{ $closeOnEscape ? '$dispatch(\'modal-close\')' : '' }}">

    <!-- Modal Header -->
    @if($title || $closeable)
        <div class="flex items-center justify-between border-b border-white/10 {{ $noPadding ? 'p-6' : 'pb-4' }}">
            @if($title)
                <h3 class="text-lg font-semibold text-white">{{ $title }}</h3>
            @endif
            {{ $slot ?? '' }}
            @if($closeable)
                <button type="button"
                    class="rounded-md text-white/40 hover:text-white/60 focus:outline-none focus:ring-2 focus:ring-[#f5af19]"
                    {{ $closeOnEscape ? '@click' : '' }}="{{ $closeOnEscape ? '$dispatch(\'modal-close\')' : '' }}">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            @endif
        </div>
    @endif

    <!-- Modal Body -->
    <div class="{{ $noPadding ? 'p-6' : 'py-4' }} {{ $title ? 'pt-4' : '' }} {{ $footer ? 'pb-4' : '' }}">
        {{ $slot }}
    </div>

    <!-- Modal Footer -->
    @if($footer)
        <div class="flex justify-end space-x-3 border-t border-white/10 pt-4 {{ $noPadding ? 'px-6' : '' }}">
            {{ $footer }}
        </div>
    @endif
</div>
