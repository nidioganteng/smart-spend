@php
    $colors = [
        'blue'   => 'text-blue-600',
        'green'  => 'text-green-600',
        'yellow' => 'text-yellow-600',
        'red'    => 'text-red-600',
        'gray'   => 'text-gray-500',
    ];
    $textColor = $colors[$color] ?? 'text-gray-800';
@endphp
<div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
    <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">{{ $label }}</p>
    <p class="text-2xl font-bold {{ $textColor }}">{{ $value }}</p>
</div>
