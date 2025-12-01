@props(['rental'])

@php
    $statusConfig = [
        'pending' => ['class' => 'bg-yellow-100 text-yellow-800', 'label' => 'Pending'],
        'active' => ['class' => 'bg-green-100 text-green-800', 'label' => 'Active'],
        'extended' => ['class' => 'bg-blue-100 text-blue-800', 'label' => 'Extended'],
        'returned' => ['class' => 'bg-gray-100 text-gray-800', 'label' => 'Returned'],
        'overdue' => ['class' => 'bg-red-100 text-red-800', 'label' => 'Overdue'],
    ];
    
    $config = $statusConfig[$rental->status] ?? $statusConfig['pending'];
@endphp

<x-badge :status="$rental->status == 'overdue' ? 'danger' : ($rental->status == 'active' || $rental->status == 'extended' ? 'success' : ($rental->status == 'returned' ? 'secondary' : 'warning'))">
    {{ $config['label'] }}
</x-badge>
