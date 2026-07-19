<x-app-layout>
    <x-slot name="header">Transaksi Pending — Butuh Review</x-slot>

    <div class="space-y-5">

        @if($transactions->total() > 0)
            <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3">
                <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-amber-800 font-medium">
                    {{ $transactions->total() }} transaksi menunggu review manual Anda
                </p>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">#</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Divisi</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Vendor</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Kategori</th>
                            <th class="text-right px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nominal</th>
                            <th class="text-center px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Risk Score</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Keterangan</th>
                            <th class="text-right px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($transactions as $trx)
                            @php
                                $riskBadge = match($trx->risk_level) {
                                    'low'    => 'bg-green-50 text-green-700 ring-1 ring-green-200',
                                    'medium' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
                                    'high'   => 'bg-red-50 text-red-700 ring-1 ring-red-200',
                                    default  => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-xs text-gray-400 font-mono">#{{ $trx->id }}</td>
                                <td class="px-6 py-4 font-medium text-gray-800">{{ $trx->divisi?->name ?? '—' }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $trx->vendor?->name ?? '—' }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $trx->category }}</td>
                                <td class="px-6 py-4 text-right font-mono font-medium text-gray-800">
                                    Rp {{ number_format($trx->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $riskBadge }}">
                                        {{ $trx->risk_score }} {{ strtoupper($trx->risk_level) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-500 max-w-xs">
                                    <p class="truncate">{{ $trx->system_notes ?? ($trx->validation_layer_failed ? 'Gagal di ' . $trx->validation_layer_failed : '—') }}</p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('transaction.show', $trx) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Review
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">Semua sudah bersih!</p>
                                            <p class="text-xs text-gray-400 mt-0.5">Tidak ada transaksi yang perlu direview</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="text-sm">{{ $transactions->links() }}</div>
    </div>
</x-app-layout>
