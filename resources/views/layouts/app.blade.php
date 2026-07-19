<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ ($title ?? $header ?? 'Dashboard') }} — SmartSpend</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">

<div class="flex min-h-screen">

    @include('layouts.sidebar')

    <div class="flex-1 flex flex-col ml-64">

        {{-- Top Bar --}}
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shrink-0 sticky top-0 z-40">
            <div>
                <h1 class="text-base font-semibold text-gray-800">{{ $header ?? 'Dashboard' }}</h1>
            </div>
            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-2.5 hover:bg-gray-50 rounded-lg px-2 py-1.5 transition-colors group">
                <div class="w-7 h-7 rounded-full bg-blue-600 flex items-center justify-center text-xs font-bold text-white shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-medium text-gray-700 group-hover:text-gray-900 leading-none">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400 leading-none mt-0.5">
                        @switch(Auth::user()->role)
                            @case('admin') Administrator @break
                            @case('head_division') Kepala Divisi @break
                            @case('finance_staff') Staf Keuangan @break
                            @case('leader') Rektor / Pimpinan @break
                        @endswitch
                    </p>
                </div>
            </a>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="mx-6 mt-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl px-4 py-3">
                <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="flex-1">{{ session('success') }}</span>
                <button @click="show = false" class="text-green-400 hover:text-green-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show"
                 class="mx-6 mt-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 text-sm rounded-xl px-4 py-3">
                <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="flex-1">{{ session('error') }}</span>
                <button @click="show = false" class="text-red-400 hover:text-red-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 p-6">
            {{ $slot }}
        </main>

    </div>
</div>

<script>
// Alpine.js fallback for flash message auto-dismiss (if Alpine isn't loaded)
document.addEventListener('DOMContentLoaded', function() {
    const flashes = document.querySelectorAll('[x-data]');
    if (typeof Alpine === 'undefined') {
        setTimeout(() => {
            flashes.forEach(el => { el.style.display = 'none'; });
        }, 4000);
    }
});
</script>

</body>
</html>
