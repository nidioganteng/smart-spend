<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'SmartSpend') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
<div class="min-h-screen flex">

    {{-- Left Panel — Branding --}}
    <div class="hidden lg:flex lg:w-1/2 bg-gray-900 flex-col justify-between p-12 relative overflow-hidden">

        {{-- Background pattern --}}
        <div class="absolute inset-0 opacity-5">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)" />
            </svg>
        </div>

        {{-- Accent circles --}}
        <div class="absolute -top-20 -right-20 w-80 h-80 bg-blue-600 rounded-full opacity-10 blur-3xl"></div>
        <div class="absolute -bottom-20 -left-10 w-64 h-64 bg-indigo-500 rounded-full opacity-10 blur-3xl"></div>

        {{-- Logo --}}
        <div class="relative z-10">
            <span class="text-2xl font-bold tracking-tight text-white">
                Smart<span class="text-blue-400">Spend</span>
            </span>
        </div>

        {{-- Main content --}}
        <div class="relative z-10 space-y-8">
            <div class="space-y-4">
                <h1 class="text-4xl font-bold text-white leading-tight">
                    Kontrol Anggaran<br>
                    <span class="text-blue-400">Lebih Cerdas.</span>
                </h1>
                <p class="text-gray-400 text-lg leading-relaxed">
                    Sistem realisasi anggaran universitas dengan validasi NFC-QR dan fraud risk scoring secara real-time.
                </p>
            </div>

            <div class="space-y-4">
                @foreach([
                    ['Identifikasi divisi via kartu NFC/RFID', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['Autentikasi vendor via QR code terenkripsi', 'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z'],
                    ['Fraud risk scoring otomatis 5-layer', 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                    ['Audit trail lengkap setiap transaksi', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                ] as [$text, $icon])
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                            </svg>
                        </div>
                        <span class="text-gray-300 text-sm">{{ $text }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Footer --}}
        <div class="relative z-10">
            <p class="text-gray-600 text-xs">© {{ date('Y') }} SmartSpend · Sistem Anggaran Universitas</p>
        </div>
    </div>

    {{-- Right Panel — Form --}}
    <div class="flex-1 flex items-center justify-center p-8 bg-gray-50">
        <div class="w-full max-w-md">

            {{-- Mobile logo --}}
            <div class="lg:hidden mb-8 text-center">
                <span class="text-2xl font-bold tracking-tight text-gray-900">
                    Smart<span class="text-blue-600">Spend</span>
                </span>
            </div>

            {{ $slot }}
        </div>
    </div>

</div>
</body>
</html>
