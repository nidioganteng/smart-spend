<x-app-layout>
    <x-slot name="header">Tap NFC / Scan QR</x-slot>

    <div class="max-w-lg space-y-5">

        <div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3.5 text-sm text-blue-700">
            <svg class="w-4 h-4 mt-0.5 shrink-0 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>
                Halaman ini mensimulasikan input dari hardware ESP32. Di production, data dikirim otomatis via
                <code class="bg-blue-100 px-1.5 py-0.5 rounded text-xs font-mono">POST /api/tap</code>.
            </span>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 mb-5">
                    <ul class="space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('transaction.store') }}" class="space-y-5">
                @csrf

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-gray-700">UID Kartu RFID</label>
                    <input type="text" name="rfid_uid" value="{{ old('rfid_uid') }}"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm font-mono uppercase
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="cth: A1B2C3D4" required>
                    <p class="text-xs text-gray-400">UID kartu RFID yang di-tap ke reader.</p>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-gray-700">QR Token Vendor</label>
                    <textarea name="qr_token" rows="3"
                              class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm font-mono
                                     focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                              placeholder="Paste token QR dari halaman detail vendor..." required>{{ old('qr_token') }}</textarea>
                    <p class="text-xs text-gray-400">Salin dari halaman Detail Vendor → QR Code.</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-gray-700">Kategori Item</label>
                        <input type="text" name="category" value="{{ old('category') }}"
                               class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="cth: Alat Tulis Kantor" required>
                        <p class="text-xs text-gray-400">Harus cocok dengan BP yang disetujui.</p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-gray-700">Nominal (Rp)</label>
                        <input type="number" name="amount" value="{{ old('amount') }}"
                               class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="cth: 500000" min="1000" required>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <input type="text" name="description" value="{{ old('description') }}"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="cth: Pembelian kertas A4 10 rim" required>
                </div>

                <div class="flex gap-3 pt-1">
                    <button type="submit"
                            class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors">
                        Proses Transaksi
                    </button>
                    <a href="{{ route('transaction.index') }}"
                       class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
