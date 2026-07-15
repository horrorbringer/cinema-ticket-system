@props([
    'label' => null,
    'name' => null,
    'id' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'rows' => null,
    'error' => null,
    'helpText' => null,
    'disabled' => false,
    'required' => false,
    'options' => null,
    'class' => null,
    'autocomplete' => 'off',
    'min' => null,
    'max' => null,
    'step' => null
])

@php
    $inputClasses = 'block w-full rounded-lg border-white/20 bg-white/5 text-white placeholder-white/30 shadow-sm focus:border-[#f5af19] focus:ring-[#f5af19]' . ($error ? ' border-red-500' : '') . ($class ? ' ' . $class : '');
    $labelClasses = 'block text-sm font-medium text-white/80 mb-1';
    $errorClasses = 'mt-1 text-sm text-red-400';
@endphp

<div class="mb-4">
    @if($label)
        <label for="{{ $id ?? $name }}" class="{{ $labelClasses }}">
            {{ $label }} {{ $required ? '*' : '' }}
        </label>
    @endif

    @if($type === 'textarea')
        <textarea id="{{ $id ?? $name }}" name="{{ $name }}" rows="{{ $rows ?? 4 }}" 
            class="{{ $inputClasses }}" 
            {{ $disabled ? 'disabled' : '' }}
            {{ $required ? 'required' : '' }}
            {{ $autocomplete ? 'autocomplete="' . $autocomplete . '"' : '' }}>{!! $value !!}</textarea>
    @elseif($type === 'select')
        <select id="{{ $id ?? $name }}" name="{{ $name }}" 
            class="{{ $inputClasses }}" 
            {{ $disabled ? 'disabled' : '' }}
            {{ $required ? 'required' : '' }}>
            {{ $slot }}
        </select>
    @else
        <input type="{{ $type }}" id="{{ $id ?? $name }}" name="{{ $name }}" 
            value="{{ $value }}" 
            placeholder="{{ $placeholder }}" 
            class="{{ $inputClasses }}" 
            {{ $disabled ? 'disabled' : '' }}
            {{ $required ? 'required' : '' }}
            @if($min) min="{{ $min }}" @endif
            @if($max) max="{{ $max }}" @endif
            @if($step) step="{{ $step }}" @endif
            {{ $autocomplete ? 'autocomplete="' . $autocomplete . '"' : '' }}>
    @endif

    @if($error)
        <p class="{{ $errorClasses }}">{{ $error }}</p>
    @endif

    @if($helpText)
        <p class="mt-1 text-sm text-white/50">{{ $helpText }}</p>
    @endif
</div>

