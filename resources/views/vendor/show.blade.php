<x-app-layout>
    <x-slot name="header">Detail Vendor — {{ $vendor->name }}</x-slot>

    <div class="max-w-lg space-y-4">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        {{-- Info Vendor --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-3">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-semibold text-gray-800">Informasi Vendor</h2>
                <a href="{{ route('vendor.edit', $vendor) }}" class="text-sm text-blue-600 hover:underline">Edit</a>
            </div>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <p class="text-gray-400">Kode</p>
                    <p class="font-mono font-medium text-gray-800">{{ $vendor->code }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Nama</p>
                    <p class="font-medium text-gray-800">{{ $vendor->name }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Kategori</p>
                    <p class="text-gray-800">{{ $vendor->category }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Status</p>
                    @if($vendor->is_active)
                        <span class="text-xs font-medium text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full">Aktif</span>
                    @else
                        <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">Nonaktif</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- QR Code Section --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
            <h2 class="text-base font-semibold text-gray-800">QR Code Autentikasi</h2>

            @if($vendor->isQrValid())
                <div class="flex flex-col items-center gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    {!! QrCode::size(200)->generate($vendor->qr_token) !!}
                    <p class="text-xs text-gray-500 text-center">
                        Berlaku hingga <span class="font-medium text-gray-700">{{ $vendor->qr_expires_at->format('d M Y, H:i') }}</span>
                    </p>
                </div>

                {{-- Token untuk simulasi form transaksi --}}
                <div class="space-y-1">
                    <p class="text-xs font-medium text-gray-500">Token QR (copy untuk simulasi transaksi):</p>
                    <div class="relative">
                        <textarea id="qr-token-text" rows="3" readonly
                                  class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-xs font-mono text-gray-600 resize-none focus:outline-none">{{ $vendor->qr_token }}</textarea>
                        <button onclick="navigator.clipboard.writeText(document.getElementById('qr-token-text').value).then(() => { this.textContent = 'Tersalin!'; setTimeout(() => this.textContent = 'Copy', 2000); })"
                                class="absolute top-2 right-2 text-xs px-2 py-1 bg-white border border-gray-300 rounded text-gray-600 hover:bg-gray-100 transition-colors">
                            Copy
                        </button>
                    </div>
                </div>

                <form method="POST" action="{{ route('vendor.generate-qr', $vendor) }}">
                    @csrf
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-blue-600 border border-blue-300 rounded-lg hover:bg-blue-50 transition-colors">
                        Generate Ulang QR
                    </button>
                </form>
            @elseif($vendor->qr_token)
                <div class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">
                    QR Code sudah expired sejak {{ $vendor->qr_expires_at->format('d M Y, H:i') }}.
                </div>
                <form method="POST" action="{{ route('vendor.generate-qr', $vendor) }}">
                    @csrf
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        Generate QR Baru
                    </button>
                </form>
            @else
                <p class="text-sm text-gray-400">QR Code belum digenerate untuk vendor ini.</p>
                <form method="POST" action="{{ route('vendor.generate-qr', $vendor) }}">
                    @csrf
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        Generate QR Code
                    </button>
                </form>
            @endif
        </div>

        <a href="{{ route('vendor.index') }}" class="inline-block text-sm text-gray-500 hover:text-gray-700">
            &larr; Kembali ke daftar vendor
        </a>
    </div>
</x-app-layout>
