<x-app-layout>
    <x-slot name="header">Detail Transaksi #{{ $transaction->id }}</x-slot>

    <div class="max-w-2xl space-y-4">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        @php
            $statusConfig = match($transaction->status) {
                'approved' => ['bg-green-50 border-green-200', 'text-green-700', 'Disetujui'],
                'pending'  => ['bg-yellow-50 border-yellow-200', 'text-yellow-700', 'Pending — Menunggu Review'],
                'rejected' => ['bg-red-50 border-red-200', 'text-red-600', 'Ditolak'],
            };
            $riskColor = match($transaction->risk_level) {
                'low'    => 'text-green-600 bg-green-50',
                'medium' => 'text-yellow-600 bg-yellow-50',
                'high'   => 'text-red-600 bg-red-50',
            };
        @endphp

        {{-- Status Banner --}}
        <div class="rounded-xl border p-4 {{ $statusConfig[0] }}">
            <p class="font-semibold {{ $statusConfig[1] }}">{{ $statusConfig[2] }}</p>
            @if($transaction->system_notes)
                <p class="text-sm mt-1 {{ $statusConfig[1] }}">{{ $transaction->system_notes }}</p>
            @endif
            @if($transaction->validation_layer_failed)
                <p class="text-xs mt-1 {{ $statusConfig[1] }} opacity-75">Gagal di: {{ $transaction->validation_layer_failed }}</p>
            @endif
        </div>

        {{-- Info Transaksi --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
            <h3 class="text-sm font-semibold text-gray-700">Informasi Transaksi</h3>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div><p class="text-gray-400">Divisi</p><p class="font-medium text-gray-800">{{ $transaction->divisi?->name ?? '—' }}</p></div>
                <div><p class="text-gray-400">Vendor</p><p class="font-medium text-gray-800">{{ $transaction->vendor?->name ?? '—' }}</p></div>
                <div><p class="text-gray-400">Kategori</p><p class="text-gray-800">{{ $transaction->category }}</p></div>
                <div><p class="text-gray-400">Nominal</p><p class="font-mono font-semibold text-gray-800">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p></div>
                <div><p class="text-gray-400">Deskripsi</p><p class="text-gray-800">{{ $transaction->description }}</p></div>
                <div><p class="text-gray-400">Waktu</p><p class="text-gray-800">{{ $transaction->created_at->format('d M Y, H:i') }}</p></div>
                <div><p class="text-gray-400">RFID UID</p><p class="font-mono text-gray-700">{{ $transaction->rfid_uid }}</p></div>
                <div><p class="text-gray-400">Budget Plan</p><p class="text-gray-800">{{ $transaction->budgetPlan?->title ?? '—' }}</p></div>
            </div>
        </div>

        {{-- Fraud Risk Score --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-700">Fraud Risk Score</h3>
                <span class="text-lg font-bold px-3 py-1 rounded-full {{ $riskColor }}">
                    {{ $transaction->risk_score }} / 100
                    <span class="text-xs font-semibold ml-1 uppercase">{{ $transaction->risk_level }}</span>
                </span>
            </div>

            @php $ind = $transaction->risk_indicators; @endphp
            <div class="grid grid-cols-2 gap-2 text-sm">
                <div class="flex items-center gap-2 p-2 rounded-lg {{ $ind['A'] ? 'bg-red-50' : 'bg-gray-50' }}">
                    <span class="w-2 h-2 rounded-full {{ $ind['A'] ? 'bg-red-500' : 'bg-gray-300' }}"></span>
                    <span class="{{ $ind['A'] ? 'text-red-700 font-medium' : 'text-gray-500' }}">A — NFC Invalid (+25)</span>
                </div>
                <div class="flex items-center gap-2 p-2 rounded-lg {{ $ind['V'] ? 'bg-red-50' : 'bg-gray-50' }}">
                    <span class="w-2 h-2 rounded-full {{ $ind['V'] ? 'bg-red-500' : 'bg-gray-300' }}"></span>
                    <span class="{{ $ind['V'] ? 'text-red-700 font-medium' : 'text-gray-500' }}">V — QR Invalid (+25)</span>
                </div>
                <div class="flex items-center gap-2 p-2 rounded-lg {{ $ind['C'] ? 'bg-orange-50' : 'bg-gray-50' }}">
                    <span class="w-2 h-2 rounded-full {{ $ind['C'] ? 'bg-orange-500' : 'bg-gray-300' }}"></span>
                    <span class="{{ $ind['C'] ? 'text-orange-700 font-medium' : 'text-gray-500' }}">C — Melebihi Saldo/Ceiling (+20)</span>
                </div>
                <div class="flex items-center gap-2 p-2 rounded-lg {{ $ind['F'] ? 'bg-yellow-50' : 'bg-gray-50' }}">
                    <span class="w-2 h-2 rounded-full {{ $ind['F'] ? 'bg-yellow-500' : 'bg-gray-300' }}"></span>
                    <span class="{{ $ind['F'] ? 'text-yellow-700 font-medium' : 'text-gray-500' }}">F — Frekuensi Mencurigakan (+15)</span>
                </div>
                <div class="flex items-center gap-2 p-2 rounded-lg {{ $ind['M'] ? 'bg-yellow-50' : 'bg-gray-50' }}">
                    <span class="w-2 h-2 rounded-full {{ $ind['M'] ? 'bg-yellow-500' : 'bg-gray-300' }}"></span>
                    <span class="{{ $ind['M'] ? 'text-yellow-700 font-medium' : 'text-gray-500' }}">M — Mismatch Kategori (+15)</span>
                </div>
            </div>
        </div>

        {{-- Review Section (Finance Staff only, status pending) --}}
        @if(Auth::user()->isFinanceStaff() && $transaction->isPending())
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Review Manual</h3>
                <form method="POST" action="{{ route('transaction.review', $transaction) }}" class="space-y-3">
                    @csrf
                    <textarea name="reviewer_notes" rows="3" required
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Catatan wajib diisi..."></textarea>
                    <div class="flex gap-2">
                        <button type="submit" name="action" value="approve"
                                class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                            Setujui Transaksi
                        </button>
                        <button type="submit" name="action" value="reject"
                                class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                            Tolak Transaksi
                        </button>
                    </div>
                </form>
            </div>
        @endif

        {{-- Riwayat Review --}}
        @if($transaction->reviewed_at)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Riwayat Review</h3>
                <div class="text-sm border-l-2 border-blue-400 pl-3">
                    <p class="font-medium text-gray-700">{{ $transaction->reviewedBy->name }}</p>
                    <p class="text-gray-500 text-xs">{{ $transaction->reviewed_at->format('d M Y, H:i') }}</p>
                    <p class="text-gray-600 mt-1">{{ $transaction->reviewer_notes }}</p>
                </div>
            </div>
        @endif

        <a href="{{ route('transaction.index') }}" class="inline-block text-sm text-gray-500 hover:text-gray-700">
            &larr; Kembali ke riwayat transaksi
        </a>
    </div>
</x-app-layout>
