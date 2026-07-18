<x-app-layout>
    <x-slot name="header">Transaksi Pending — Butuh Review</x-slot>

    <div class="space-y-4">

        <p class="text-sm text-gray-500">{{ $transactions->total() }} transaksi menunggu review manual.</p>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600">#</th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600">Divisi</th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600">Vendor</th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600">Kategori</th>
                        <th class="text-right px-6 py-3 font-semibold text-gray-600">Nominal</th>
                        <th class="text-center px-6 py-3 font-semibold text-gray-600">Risk</th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600">Keterangan</th>
                        <th class="text-right px-6 py-3 font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($transactions as $trx)
                        @php
                            $riskColor = match($trx->risk_level) {
                                'low'    => 'text-green-600',
                                'medium' => 'text-yellow-600',
                                'high'   => 'text-red-600 font-bold',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-400">{{ $trx->id }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $trx->divisi?->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $trx->vendor?->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $trx->category }}</td>
                            <td class="px-6 py-4 text-right font-mono text-gray-800">Rp {{ number_format($trx->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="{{ $riskColor }} text-xs font-semibold">{{ $trx->risk_score }}</span>
                                <span class="text-xs text-gray-400 ml-0.5 uppercase">{{ $trx->risk_level }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500 max-w-xs truncate">
                                {{ $trx->system_notes ?? ($trx->validation_layer_failed ? 'Gagal ' . $trx->validation_layer_failed : '—') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('transaction.show', $trx) }}" class="text-blue-600 hover:underline">Review</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-gray-400">Tidak ada transaksi yang perlu direview.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $transactions->links() }}
    </div>
</x-app-layout>
