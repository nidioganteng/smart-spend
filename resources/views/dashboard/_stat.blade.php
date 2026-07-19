@php
    $configs = [
        'blue'   => ['bg' => 'bg-blue-50',   'icon' => 'text-blue-500',   'text' => 'text-blue-700',  'ring' => 'ring-blue-100'],
        'green'  => ['bg' => 'bg-green-50',  'icon' => 'text-green-500',  'text' => 'text-green-700', 'ring' => 'ring-green-100'],
        'yellow' => ['bg' => 'bg-amber-50',  'icon' => 'text-amber-500',  'text' => 'text-amber-700', 'ring' => 'ring-amber-100'],
        'red'    => ['bg' => 'bg-red-50',    'icon' => 'text-red-500',    'text' => 'text-red-700',   'ring' => 'ring-red-100'],
        'gray'   => ['bg' => 'bg-gray-50',   'icon' => 'text-gray-400',   'text' => 'text-gray-600',  'ring' => 'ring-gray-100'],
        'indigo' => ['bg' => 'bg-indigo-50', 'icon' => 'text-indigo-500', 'text' => 'text-indigo-700','ring' => 'ring-indigo-100'],
    ];
    $c = $configs[$color ?? 'blue'];
    $icon ??= 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z';
@endphp
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
    <div class="w-11 h-11 rounded-xl {{ $c['bg'] }} ring-1 {{ $c['ring'] }} flex items-center justify-center shrink-0">
        <svg class="w-5 h-5 {{ $c['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $icon }}"/>
        </svg>
    </div>
    <div class="min-w-0">
        <p class="text-xs font-medium text-gray-500 truncate">{{ $label }}</p>
        <p class="text-xl font-bold text-gray-900 leading-tight mt-0.5">{{ $value }}</p>
    </div>
</div>
