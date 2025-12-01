@props(['status' => 'primary', 'size' => 'md'])

@php
    $statusClasses = [
        'primary' => 'bg-indigo-100 text-indigo-800',
        'success' => 'bg-green-100 text-green-800',
        'danger' => 'bg-red-100 text-red-800',
        'warning' => 'bg-yellow-100 text-yellow-800',
        'info' => 'bg-blue-100 text-blue-800',
        'secondary' => 'bg-gray-100 text-gray-800',
    ][$status];
    
    $sizeClasses = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2 py-1 text-xs',
        'lg' => 'px-3 py-1 text-sm',
    ][$size];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full font-medium {$statusClasses} {$sizeClasses}"]) }}>
    {{ $slot }}
</span>
