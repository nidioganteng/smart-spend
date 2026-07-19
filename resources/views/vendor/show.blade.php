<x-app-layout>
    <x-slot name="header">Detail Vendor — {{ $vendor->name }}</x-slot>

    <div class="max-w-lg space-y-5">

        <a href="{{ route('vendor.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Vendor
        </a>

        {{-- Info Vendor --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Informasi Vendor
                </h2>
                <a href="{{ route('vendor.edit', $vendor) }}"
                   class="px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                    Edit
                </a>
            </div>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Kode</p>
                    <p class="font-mono text-xs font-medium text-gray-800 bg-gray-50 px-2 py-1 rounded inline-block">{{ $vendor->code }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Nama</p>
                    <p class="font-medium text-gray-800">{{ $vendor->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Kategori</p>
                    <p class="text-gray-800">{{ $vendor->category }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Status</p>
                    @if($vendor->is_active)
                        <span class="text-xs font-medium text-blue-700 bg-blue-50 ring-1 ring-blue-200 px-2.5 py-0.5 rounded-full">Aktif</span>
                    @else
                        <span class="text-xs font-medium text-gray-500 bg-gray-100 ring-1 ring-gray-200 px-2.5 py-0.5 rounded-full">Nonaktif</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- QR Code Section --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-sm font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                QR Code Autentikasi
            </h2>

            @if($vendor->isQrValid())
                <div class="flex items-start gap-3 p-3 bg-green-50 border border-green-200 rounded-xl mb-4 text-sm text-green-700">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>
                        QR aktif · Berlaku hingga <strong>{{ $vendor->qr_expires_at->format('d M Y, H:i') }}</strong>
                    </span>
                </div>

                <div class="flex justify-center p-6 bg-white border border-gray-200 rounded-xl mb-4">
                    {!! QrCode::size(180)->generate($vendor->qr_token) !!}
                </div>

                <div class="space-y-2 mb-4">
                    <p class="text-xs font-medium text-gray-500">Token QR (copy untuk simulasi transaksi):</p>
                    <div class="relative">
                        <textarea id="qr-token-text" rows="3" readonly
                                  class="w-full border border-gray-200 bg-gray-50 rounded-xl px-3 py-2.5 text-xs font-mono text-gray-600 resize-none focus:outline-none">{{ $vendor->qr_token }}</textarea>
                        <button onclick="navigator.clipboard.writeText(document.getElementById('qr-token-text').value).then(() => { this.textContent = 'Tersalin!'; setTimeout(() => this.textContent = 'Copy', 2000); })"
                                class="absolute top-2 right-2 text-xs px-2.5 py-1 bg-white border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors font-medium">
                            Copy
                        </button>
                    </div>
                </div>

                <form method="POST" action="{{ route('vendor.generate-qr', $vendor) }}">
                    @csrf
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-xl transition-colors">
                        Generate Ulang QR
                    </button>
                </form>

            @elseif($vendor->qr_token)
                <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 mb-4">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    QR Code expired sejak {{ $vendor->qr_expires_at->format('d M Y, H:i') }}
                </div>
                <form method="POST" action="{{ route('vendor.generate-qr', $vendor) }}">
                    @csrf
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                        Generate QR Baru
                    </button>
                </form>

            @else
                <div class="flex items-center gap-3 p-4 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-500 mb-4">
                    <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    QR Code belum digenerate untuk vendor ini
                </div>
                <form method="POST" action="{{ route('vendor.generate-qr', $vendor) }}">
                    @csrf
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                        Generate QR Code
                    </button>
                </form>
            @endif
        </div>

    </div>
</x-app-layout>
