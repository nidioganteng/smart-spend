<x-app-layout>
    <x-slot name="header">Simulasi Tap NFC + Scan QR</x-slot>

    <div class="max-w-lg">
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-700 mb-4">
            Halaman ini mensimulasikan input dari hardware ESP32. Di production, data dikirim otomatis via <code class="bg-blue-100 px-1 rounded">POST /api/tap</code>.
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 mb-4">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('transaction.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">UID Kartu RFID</label>
                    <input type="text" name="rfid_uid" value="{{ old('rfid_uid') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono uppercase focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="cth: A1B2C3D4" required>
                    <p class="text-xs text-gray-400 mt-1">UID kartu RFID yang di-tap ke reader.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">QR Token Vendor</label>
                    <textarea name="qr_token" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Paste token QR dari halaman detail vendor..." required>{{ old('qr_token') }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Salin dari halaman Detail Vendor → QR Code.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Item</label>
                    <input type="text" name="category" value="{{ old('category') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="cth: Alat Tulis Kantor" required>
                    <p class="text-xs text-gray-400 mt-1">Harus cocok dengan kategori di Budget Plan yang disetujui.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <input type="text" name="description" value="{{ old('description') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="cth: Pembelian kertas A4 10 rim" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nominal (Rp)</label>
                    <input type="number" name="amount" value="{{ old('amount') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="cth: 500000" min="1000" required>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        Proses Transaksi
                    </button>
                    <a href="{{ route('transaction.index') }}"
                       class="px-5 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
