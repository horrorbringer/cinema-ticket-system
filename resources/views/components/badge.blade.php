@props(['type' => 'default', 'size' => 'sm', 'rounded' => true])

@php
    $baseClasses = 'inline-flex items-center font-medium';

    $sizeClasses = [
        'sm' => 'px-2.5 py-0.5 text-xs',
        'md' => 'px-3 py-1 text-sm',
        'lg' => 'px-4 py-1.5 text-base',
    ];

    $typeClasses = [
        'default' => 'bg-white/10 text-white/70',
        'primary' => 'bg-[#f12711]/20 text-[#f5af19]',
        'success' => 'bg-green-500/20 text-green-400',
        'warning' => 'bg-yellow-500/20 text-yellow-400',
        'danger' => 'bg-red-500/20 text-red-400',
        'info' => 'bg-blue-500/20 text-blue-400',
        'purple' => 'bg-purple-500/20 text-purple-400',
        'pink' => 'bg-pink-500/20 text-pink-400',
    ];

    $classes = "{$baseClasses} {$sizeClasses[$size]} {$typeClasses[$type]}";
    if ($rounded) {
        $classes .= ' rounded-full';
    }
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</span>
