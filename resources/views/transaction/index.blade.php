<x-app-layout>
    <x-slot name="header">Riwayat Transaksi</x-slot>

    <div class="space-y-5">

        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">{{ $transactions->total() }} transaksi tercatat</p>
            @if(Auth::user()->isHeadDivision())
                <a href="{{ route('transaction.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Tap NFC / Scan QR
                </a>
            @endif
        </div>

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
                            <th class="text-center px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Risk</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Waktu</th>
                            <th class="text-right px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($transactions as $trx)
                            @php
                                $statusBadge = match($trx->status) {
                                    'approved' => 'bg-green-50 text-green-700 ring-1 ring-green-200',
                                    'pending'  => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
                                    'rejected' => 'bg-red-50 text-red-600 ring-1 ring-red-200',
                                    default    => 'bg-gray-100 text-gray-600',
                                };
                                $statusLabel = match($trx->status) {
                                    'approved' => 'Disetujui',
                                    'pending'  => 'Pending',
                                    'rejected' => 'Ditolak',
                                    default    => ucfirst($trx->status),
                                };
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
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusBadge }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-400">{{ $trx->created_at->format('d M, H:i') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('transaction.show', $trx) }}"
                                       class="px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">Belum ada transaksi</p>
                                            <p class="text-xs text-gray-400 mt-0.5">Transaksi akan muncul setelah tap kartu NFC</p>
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
