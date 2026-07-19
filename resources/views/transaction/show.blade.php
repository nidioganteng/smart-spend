<x-app-layout>
    <x-slot name="header">Detail Transaksi #{{ $transaction->id }}</x-slot>

    <div class="max-w-2xl space-y-5">

        {{-- Back link --}}
        <a href="{{ route('transaction.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Riwayat Transaksi
        </a>

        @php
            $statusConfig = match($transaction->status) {
                'approved' => ['card' => 'bg-green-50 border-green-200', 'text' => 'text-green-800', 'icon' => 'text-green-500', 'label' => 'Disetujui',
                               'path' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                'pending'  => ['card' => 'bg-amber-50 border-amber-200', 'text' => 'text-amber-800', 'icon' => 'text-amber-500', 'label' => 'Pending — Menunggu Review',
                               'path' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                'rejected' => ['card' => 'bg-red-50 border-red-200', 'text' => 'text-red-800', 'icon' => 'text-red-500', 'label' => 'Ditolak',
                               'path' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                default    => ['card' => 'bg-gray-50 border-gray-200', 'text' => 'text-gray-800', 'icon' => 'text-gray-500', 'label' => ucfirst($transaction->status),
                               'path' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            };
            $riskConfig = match($transaction->risk_level) {
                'low'    => ['bg' => 'bg-green-50',  'text' => 'text-green-700',  'ring' => 'ring-green-200',  'bar' => 'bg-green-500'],
                'medium' => ['bg' => 'bg-amber-50',  'text' => 'text-amber-700',  'ring' => 'ring-amber-200',  'bar' => 'bg-amber-500'],
                'high'   => ['bg' => 'bg-red-50',    'text' => 'text-red-700',    'ring' => 'ring-red-200',    'bar' => 'bg-red-500'],
                default  => ['bg' => 'bg-gray-50',   'text' => 'text-gray-700',   'ring' => 'ring-gray-200',   'bar' => 'bg-gray-400'],
            };
        @endphp

        {{-- Status Banner --}}
        <div class="rounded-2xl border {{ $statusConfig['card'] }} p-5 flex items-start gap-4">
            <svg class="w-5 h-5 mt-0.5 {{ $statusConfig['icon'] }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusConfig['path'] }}"/>
            </svg>
            <div>
                <p class="font-semibold {{ $statusConfig['text'] }}">{{ $statusConfig['label'] }}</p>
                @if($transaction->system_notes)
                    <p class="text-sm mt-1 {{ $statusConfig['text'] }} opacity-80">{{ $transaction->system_notes }}</p>
                @endif
                @if($transaction->validation_layer_failed)
                    <p class="text-xs mt-1 {{ $statusConfig['text'] }} opacity-60">Gagal di layer: {{ $transaction->validation_layer_failed }}</p>
                @endif
            </div>
        </div>

        {{-- Informasi Transaksi --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Informasi Transaksi
            </h3>
            <div class="grid grid-cols-2 gap-y-4 gap-x-6 text-sm">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Divisi</p>
                    <p class="font-medium text-gray-800">{{ $transaction->divisi?->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Vendor</p>
                    <p class="font-medium text-gray-800">{{ $transaction->vendor?->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Kategori</p>
                    <p class="text-gray-800">{{ $transaction->category }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Nominal</p>
                    <p class="font-mono font-bold text-gray-900 text-base">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-xs text-gray-400 mb-0.5">Deskripsi</p>
                    <p class="text-gray-800">{{ $transaction->description }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Waktu Transaksi</p>
                    <p class="text-gray-800">{{ $transaction->created_at->format('d M Y, H:i:s') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">RFID UID</p>
                    <p class="font-mono text-sm text-gray-700 bg-gray-50 px-2 py-1 rounded">{{ $transaction->rfid_uid }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Budget Plan</p>
                    <p class="text-gray-800">{{ $transaction->budgetPlan?->title ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Item Budget Plan</p>
                    <p class="text-gray-800">{{ $transaction->budgetPlanItem?->category ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- Fraud Risk Score --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Fraud Risk Score
                </h3>
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-bold {{ $riskConfig['bg'] }} {{ $riskConfig['text'] }} ring-1 {{ $riskConfig['ring'] }}">
                    {{ $transaction->risk_score }}/100
                    <span class="text-xs font-semibold uppercase">{{ $transaction->risk_level }}</span>
                </span>
            </div>

            {{-- Score bar --}}
            <div class="h-2 bg-gray-100 rounded-full mb-5 overflow-hidden">
                <div class="h-full rounded-full {{ $riskConfig['bar'] }} transition-all" style="width: {{ $transaction->risk_score }}%"></div>
            </div>

            @php $ind = $transaction->risk_indicators; @endphp
            <div class="grid grid-cols-1 gap-2">
                @foreach([
                    ['key' => 'A', 'label' => 'Identitas NFC Invalid', 'points' => '+25', 'color_on' => 'bg-red-50 border-red-200 text-red-700', 'color_off' => 'bg-gray-50 border-gray-100 text-gray-400'],
                    ['key' => 'V', 'label' => 'QR Vendor Invalid', 'points' => '+25', 'color_on' => 'bg-red-50 border-red-200 text-red-700', 'color_off' => 'bg-gray-50 border-gray-100 text-gray-400'],
                    ['key' => 'C', 'label' => 'Melebihi Saldo / Ceiling BP', 'points' => '+20', 'color_on' => 'bg-orange-50 border-orange-200 text-orange-700', 'color_off' => 'bg-gray-50 border-gray-100 text-gray-400'],
                    ['key' => 'F', 'label' => 'Frekuensi Mencurigakan (Split Trx)', 'points' => '+15', 'color_on' => 'bg-amber-50 border-amber-200 text-amber-700', 'color_off' => 'bg-gray-50 border-gray-100 text-gray-400'],
                    ['key' => 'M', 'label' => 'Mismatch Kategori', 'points' => '+15', 'color_on' => 'bg-amber-50 border-amber-200 text-amber-700', 'color_off' => 'bg-gray-50 border-gray-100 text-gray-400'],
                ] as $indicator)
                    @php $active = $ind[$indicator['key']] ?? false; @endphp
                    <div class="flex items-center justify-between px-3 py-2.5 rounded-lg border {{ $active ? $indicator['color_on'] : $indicator['color_off'] }}">
                        <div class="flex items-center gap-2.5">
                            <span class="w-2 h-2 rounded-full {{ $active ? 'bg-current' : 'bg-gray-300' }}"></span>
                            <span class="text-sm font-medium">{{ $indicator['key'] }}</span>
                            <span class="text-sm">{{ $indicator['label'] }}</span>
                        </div>
                        <span class="text-xs font-bold {{ $active ? 'opacity-100' : 'opacity-30' }}">{{ $indicator['points'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Review Section --}}
        @if(Auth::user()->isFinanceStaff() && $transaction->isPending())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Review Manual
                </h3>
                <form method="POST" action="{{ route('transaction.review', $transaction) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Catatan Review <span class="text-red-500">*</span></label>
                        <textarea name="reviewer_notes" rows="3" required
                                  class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                  placeholder="Tuliskan alasan persetujuan atau penolakan..."></textarea>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit" name="action" value="approve"
                                class="flex-1 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl transition-colors">
                            Setujui Transaksi
                        </button>
                        <button type="submit" name="action" value="reject"
                                class="flex-1 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition-colors">
                            Tolak Transaksi
                        </button>
                    </div>
                </form>
            </div>
        @endif

        {{-- Riwayat Review --}}
        @if($transaction->reviewed_at)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-3">Riwayat Review</h3>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-xs font-bold shrink-0">
                        {{ strtoupper(substr($transaction->reviewedBy->name, 0, 1)) }}
                    </div>
                    <div class="border-l-2 border-blue-200 pl-4 flex-1">
                        <p class="text-sm font-medium text-gray-800">{{ $transaction->reviewedBy->name }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $transaction->reviewed_at->format('d M Y, H:i') }}</p>
                        <p class="text-sm text-gray-600 mt-2 bg-gray-50 rounded-lg p-3">{{ $transaction->reviewer_notes }}</p>
                    </div>
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
