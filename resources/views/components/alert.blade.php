@props([
    'variant' => 'info',
    'size' => 'md',
    'title' => null,
    'dismissible' => false,
    'show' => true
])

@php
    $variantClasses = [
        'info' => 'bg-blue-500/10 border-blue-500/30 text-blue-300',
        'success' => 'bg-green-500/10 border-green-500/30 text-green-300',
        'warning' => 'bg-yellow-500/10 border-yellow-500/30 text-yellow-300',
        'danger' => 'bg-red-500/10 border-red-500/30 text-red-300',
        'dark' => 'bg-white/5 border-white/10 text-white/60',
    ];

    $sizeClasses = [
        'sm' => 'p-3 text-sm',
        'md' => 'p-4',
        'lg' => 'p-6 text-lg',
    ];

    $variantClass = $variantClasses[$variant] ?? $variantClasses['info'];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];

    $classes = "border-l-4 rounded-md {$sizeClass} {$variantClass}";
@endphp

<div x-data="{ show: {{ $show ? 'true' : 'false' }} }" x-show="show" x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-2"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100 transform translate-y-0"
     x-transition:leave-end="opacity-0 transform translate-y-2"
     role="alert" {{ $attributes->merge(['class' => $classes]) }}
     style="display: {{ $show ? 'block' : 'none' }};">
    <div class="flex">
        <div class="flex-shrink-0">
            @switch($variant)
                @case('info')
                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    @break

                @case('success')
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    @break

                @case('warning')
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.334A1 1 0 019.74 2.28l5.522 5.522a1 1 0 010 1.414l-5.522 5.522a1 1 0 01-1.414-1.414L10.828 9.28a1 1 0 00-1.414-1.414L6.243 3.334z" clip-rule="evenodd"/>
                    </svg>
                    @break

                @case('danger')
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    @break

                @case('dark')
                    <svg class="h-5 w-5 text-white/40" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    @break
            @endswitch
        </div>
        <div class="ml-3">
            @if($title)
                <h3 class="text-sm font-medium">{{ $title }}</h3>
            @endif
            <div class="text-sm {{ $title ? 'mt-2' : '' }}">
                {{ $slot }}
            </div>
        </div>
        @if($dismissible)
            <div class="ml-auto pl-3">
                <div class="-mr-1.5 -mt-1.5">
                    <button type="button" @click="show = false"
                        class="inline-flex rounded-md p-1.5 {{ $variant === 'info' ? 'text-blue-400 hover:bg-blue-500/20' : '' }}
                            {{ $variant === 'success' ? 'text-green-400 hover:bg-green-500/20' : '' }}
                            {{ $variant === 'warning' ? 'text-yellow-400 hover:bg-yellow-500/20' : '' }}
                            {{ $variant === 'danger' ? 'text-red-400 hover:bg-red-500/20' : '' }}
                            {{ $variant === 'dark' ? 'text-white/40 hover:bg-white/10' : '' }}
                            focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#0a0a0a] {{ $variant === 'info' ? 'focus:ring-blue-500' : '' }}
                            {{ $variant === 'success' ? 'focus:ring-green-500' : '' }}
                            {{ $variant === 'warning' ? 'focus:ring-yellow-500' : '' }}
                            {{ $variant === 'danger' ? 'focus:ring-red-500' : '' }}
                            {{ $variant === 'dark' ? 'focus:ring-white/30' : '' }}"
                        aria-label="Dismiss">
                        <span class="sr-only">Dismiss</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
