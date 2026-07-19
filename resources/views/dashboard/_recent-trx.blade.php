@if($transactions->count())
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-800">Transaksi Terbaru</h3>
        @if(Auth::user()->isHeadDivision() || Auth::user()->isFinanceStaff())
            <a href="{{ route('transaction.index') }}" class="text-xs text-blue-600 hover:text-blue-700 font-medium">Lihat semua →</a>
        @endif
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Divisi / Vendor</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Kategori</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nominal</th>
                    <th class="text-center px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Risk</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($transactions as $trx)
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
                        <td class="px-6 py-3.5">
                            <p class="font-medium text-gray-800 text-sm">{{ $trx->divisi?->name ?? '—' }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $trx->vendor?->name ?? '—' }}</p>
                        </td>
                        <td class="px-6 py-3.5 text-gray-600 text-sm">{{ $trx->category }}</td>
                        <td class="px-6 py-3.5 text-right font-mono text-sm font-medium text-gray-800">
                            Rp {{ number_format($trx->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-3.5 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $riskBadge }}">
                                {{ $trx->risk_score }} · {{ strtoupper($trx->risk_level) }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusBadge }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5 text-gray-400 text-xs">{{ $trx->created_at->diffForHumans() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
