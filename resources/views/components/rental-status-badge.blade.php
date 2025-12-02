@props(['status'])

@php
    $statusConfig = [
        'pending' => ['class' => 'bg-yellow-100 text-yellow-800', 'label' => 'Pending'],
        'active' => ['class' => 'bg-green-100 text-green-800', 'label' => 'Active'],
        'extended' => ['class' => 'bg-blue-100 text-blue-800', 'label' => 'Extended'],
        'returned' => ['class' => 'bg-gray-100 text-gray-800', 'label' => 'Returned'],
        'overdue' => ['class' => 'bg-red-100 text-red-800', 'label' => 'Overdue'],
        'cancelled' => ['class' => 'bg-red-100 text-red-800', 'label' => 'Cancelled'],
    ];
    
    $config = $statusConfig[$status] ?? $statusConfig['pending'];
    
    // Determine badge type
    $badgeType = match($status) {
        'overdue', 'cancelled' => 'error',
        'active', 'extended' => 'success',
        'returned' => 'info',
        default => 'warning'
    };
@endphp

<x-badge :type="$badgeType">
    {{ $config['label'] }}
</x-badge>
