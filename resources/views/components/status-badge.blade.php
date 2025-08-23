{{-- resources/views/components/status-badge.blade.php --}}
@php
$statusClasses = [
    'confirmed' => 'bg-green-100 text-green-800',
    'pending' => 'bg-yellow-100 text-yellow-800',
    'cancelled' => 'bg-red-100 text-red-800',
    'completed' => 'bg-blue-100 text-blue-800',
    'maintenance' => 'bg-gray-100 text-gray-800',
    'available' => 'bg-green-100 text-green-800',
    'occupied' => 'bg-red-100 text-red-800',
];

$normalizedStatus = strtolower($status ?? '');
$classes = $statusClasses[$normalizedStatus] ?? 'bg-gray-100 text-gray-800';
$displayStatus = ucfirst($status ?? 'Unknown');
@endphp

<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $classes }}">
    {{ $displayStatus }}
</span>
