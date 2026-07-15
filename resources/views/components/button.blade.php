@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'rounded' => true,
    'fullWidth' => false,
    'disabled' => false,
    'icon' => null,
    'iconPosition' => 'left'
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium transition-all duration-200';

    $variantClasses = [
        'primary' => 'bg-gradient-to-r from-[#f12711] to-[#f5af19] text-white hover:from-[#d42d02] hover:to-[#e09e00] focus:ring-[#f5af19]',
        'secondary' => 'bg-white/10 text-white/70 hover:bg-white/20 hover:text-white focus:ring-white/30',
        'success' => 'bg-green-600/80 text-white hover:bg-green-600 focus:ring-green-500',
        'danger' => 'bg-red-600/80 text-white hover:bg-red-600 focus:ring-red-500',
        'warning' => 'bg-yellow-500/80 text-white hover:bg-yellow-500 focus:ring-yellow-500',
        'info' => 'bg-blue-600/80 text-white hover:bg-blue-600 focus:ring-blue-500',
        'outline-primary' => 'border border-[#f5af19]/50 text-[#f5af19] hover:bg-[#f5af19]/10 focus:ring-[#f5af19]',
        'outline-secondary' => 'border border-white/20 text-white/70 hover:bg-white/10 hover:text-white focus:ring-white/30',
        'ghost' => 'text-[#f5af19] hover:bg-[#f5af19]/10 focus:ring-[#f5af19]',
    ];

    $sizeClasses = [
        'xs' => 'px-2 py-1 text-xs',
        'sm' => 'px-4 py-2 text-sm',
        'md' => 'px-6 py-3 text-base',
        'lg' => 'px-8 py-4 text-lg',
    ];

    $widthClasses = $fullWidth ? 'w-full' : 'w-auto';

    $disabledClasses = $disabled ? 'opacity-50 cursor-not-allowed pointer-events-none' : 'cursor-pointer';

    $buttonType = $disabled ? 'button' : $type;

    $classes = "{$baseClasses} {$variantClasses[$variant]} {$sizeClasses[$size]} {$widthClasses} {$disabledClasses} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#0a0a0a] rounded-lg";
@endphp

<button type="{{ $buttonType }}" {{ $attributes->merge(['class' => $classes]) }} {{ $disabled ? 'disabled' : '' }}>
    @if($icon && $iconPosition === 'left')
        <span class="mr-2">{!! $icon !!}</span>
    @endif
    <span>{{ $slot }}</span>
    @if($icon && $iconPosition === 'right')
        <span class="ml-2">{!! $icon !!}</span>
    @endif
</button>
