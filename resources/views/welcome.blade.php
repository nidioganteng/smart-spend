<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SmartSpend — Sistem Realisasi Anggaran Universitas</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-950 text-white">

    {{-- Background --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600 rounded-full opacity-10 blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-indigo-500 rounded-full opacity-10 blur-3xl"></div>
        <div class="absolute inset-0 opacity-[0.03]">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="50" height="50" patternUnits="userSpaceOnUse">
                        <path d="M 50 0 L 0 0 0 50" fill="none" stroke="white" stroke-width="1"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)" />
            </svg>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="relative z-10 flex items-center justify-between px-8 py-5 max-w-6xl mx-auto">
        <span class="text-xl font-bold tracking-tight">
            Smart<span class="text-blue-400">Spend</span>
        </span>
        <div class="flex items-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}"
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">
                    Buka Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="px-4 py-2 text-sm font-medium text-gray-300 hover:text-white transition-colors">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">
                    Daftar
                </a>
            @endauth
        </div>
    </nav>

    {{-- Hero --}}
    <section class="relative z-10 max-w-6xl mx-auto px-8 pt-20 pb-24 text-center">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-600/20 border border-blue-500/30 rounded-full text-xs text-blue-300 font-medium mb-6">
            <span class="w-1.5 h-1.5 bg-blue-400 rounded-full animate-pulse"></span>
            NFC-QR Fraud Risk Scoring System
        </div>

        <h1 class="text-5xl sm:text-6xl font-extrabold text-white leading-tight mb-6">
            Anggaran Universitas<br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">
                Lebih Aman & Transparan
            </span>
        </h1>

        <p class="text-lg text-gray-400 max-w-2xl mx-auto leading-relaxed mb-10">
            SmartSpend memvalidasi setiap transaksi secara real-time dengan identifikasi NFC, autentikasi QR vendor terenkripsi, dan fraud risk scoring 5-layer — sebelum dana keluar.
        </p>

        <div class="flex items-center justify-center gap-4 flex-wrap">
            @auth
                <a href="{{ route('dashboard') }}"
                   class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl transition-colors shadow-lg shadow-blue-900/30">
                    Buka Dashboard →
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl transition-colors shadow-lg shadow-blue-900/30">
                    Masuk ke Sistem →
                </a>
                <a href="{{ route('register') }}"
                   class="px-6 py-3 bg-white/10 hover:bg-white/15 border border-white/20 text-white font-medium rounded-xl transition-colors">
                    Daftar Sekarang
                </a>
            @endauth
        </div>
    </section>

    {{-- Feature Cards --}}
    <section class="relative z-10 max-w-6xl mx-auto px-8 pb-24">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach([
                [
                    'icon'  => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z',
                    'title' => 'Identifikasi NFC',
                    'desc'  => 'Setiap transaksi dimulai dengan tap kartu NFC/RFID untuk memverifikasi identitas divisi secara instan.',
                    'color' => 'text-blue-400',
                    'bg'    => 'bg-blue-500/10 border-blue-500/20',
                ],
                [
                    'icon'  => 'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z',
                    'title' => 'Autentikasi QR Vendor',
                    'desc'  => 'QR code vendor dienkripsi HMAC-SHA256, time-bound 24 jam, dan anti-duplikasi untuk mencegah penyalahgunaan.',
                    'color' => 'text-indigo-400',
                    'bg'    => 'bg-indigo-500/10 border-indigo-500/20',
                ],
                [
                    'icon'  => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                    'title' => 'Fraud Risk Scoring',
                    'desc'  => 'Validasi 5-layer otomatis menghitung risk score setiap transaksi. Skor tinggi langsung diblokir atau dieskalasi.',
                    'color' => 'text-emerald-400',
                    'bg'    => 'bg-emerald-500/10 border-emerald-500/20',
                ],
            ] as $feature)
                <div class="bg-white/5 border {{ $feature['bg'] }} rounded-2xl p-6 hover:bg-white/[0.07] transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 {{ $feature['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $feature['icon'] }}"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-white mb-2">{{ $feature['title'] }}</h3>
                    <p class="text-sm text-gray-400 leading-relaxed">{{ $feature['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Flow Steps --}}
    <section class="relative z-10 max-w-6xl mx-auto px-8 pb-32">
        <div class="text-center mb-12">
            <h2 class="text-2xl font-bold text-white">Alur Transaksi dalam 5 Langkah</h2>
            <p class="text-gray-400 text-sm mt-2">Setiap transaksi divalidasi secara otomatis sebelum dana dikeluarkan</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-5 gap-6">
            @foreach([
                ['num' => '1', 'title' => 'Identifikasi Kartu',  'desc' => 'Tap kartu NFC divisi ke reader'],
                ['num' => '2', 'title' => 'Autentikasi Vendor',  'desc' => 'Scan QR code vendor terenkripsi'],
                ['num' => '3', 'title' => 'Input Transaksi',     'desc' => 'Isi kategori, deskripsi, nominal'],
                ['num' => '4', 'title' => 'Validasi & Scoring',  'desc' => 'Sistem cek 5-layer + hitung risk'],
                ['num' => '5', 'title' => 'Finalisasi',          'desc' => 'Debit wallet, update BP, audit trail'],
            ] as $step)
                <div class="flex flex-col items-center text-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-600 text-white font-bold text-sm flex items-center justify-center shadow-lg shadow-blue-900/40">
                        {{ $step['num'] }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-white">{{ $step['title'] }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $step['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Footer --}}
    <footer class="relative z-10 border-t border-white/10 py-6 text-center">
        <p class="text-xs text-gray-600">© {{ date('Y') }} SmartSpend · Sistem Realisasi Anggaran Universitas · Berbasis NFC-QR Fraud Risk Scoring</p>
    </footer>

</body>
</html>
