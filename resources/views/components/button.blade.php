@props(['type' => 'button', 'variant' => 'primary', 'size' => 'md'])

@php
    $variantClasses = [
        'primary' => 'bg-indigo-600 text-white hover:bg-indigo-700',
        'secondary' => 'bg-gray-600 text-white hover:bg-gray-700',
        'success' => 'bg-green-600 text-white hover:bg-green-700',
        'danger' => 'bg-red-600 text-white hover:bg-red-700',
        'warning' => 'bg-yellow-600 text-white hover:bg-yellow-700',
        'outline' => 'border border-indigo-600 text-indigo-600 hover:bg-indigo-50',
    ][$variant];
    
    $sizeClasses = [
        'sm' => 'px-3 py-1 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ][$size];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "inline-flex items-center justify-center font-medium rounded transition {$variantClasses} {$sizeClasses}"]) }}>
    {{ $slot }}
</button>
