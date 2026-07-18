@if($transactions->count())
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-sm font-semibold text-gray-700">Transaksi Terbaru</h3>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="text-left px-6 py-3 font-semibold text-gray-600">Divisi / Vendor</th>
                <th class="text-left px-6 py-3 font-semibold text-gray-600">Kategori</th>
                <th class="text-right px-6 py-3 font-semibold text-gray-600">Nominal</th>
                <th class="text-center px-6 py-3 font-semibold text-gray-600">Risk</th>
                <th class="text-left px-6 py-3 font-semibold text-gray-600">Status</th>
                <th class="text-left px-6 py-3 font-semibold text-gray-600">Waktu</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($transactions as $trx)
                @php
                    $statusColor = match($trx->status) {
                        'approved' => 'bg-green-50 text-green-700',
                        'pending'  => 'bg-yellow-50 text-yellow-700',
                        'rejected' => 'bg-red-50 text-red-600',
                    };
                    $riskColor = match($trx->risk_level) {
                        'low'    => 'text-green-600',
                        'medium' => 'text-yellow-600',
                        'high'   => 'text-red-600 font-bold',
                    };
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3">
                        <p class="text-gray-800 font-medium">{{ $trx->divisi?->name ?? '—' }}</p>
                        <p class="text-gray-400 text-xs">{{ $trx->vendor?->name ?? '—' }}</p>
                    </td>
                    <td class="px-6 py-3 text-gray-600">{{ $trx->category }}</td>
                    <td class="px-6 py-3 text-right font-mono text-gray-800">Rp {{ number_format($trx->amount, 0, ',', '.') }}</td>
                    <td class="px-6 py-3 text-center">
                        <span class="{{ $riskColor }} text-xs font-semibold">{{ $trx->risk_score }}</span>
                    </td>
                    <td class="px-6 py-3">
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $statusColor }}">{{ ucfirst($trx->status) }}</span>
                    </td>
                    <td class="px-6 py-3 text-gray-400 text-xs">{{ $trx->created_at->diffForHumans() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
