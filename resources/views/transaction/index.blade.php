<x-app-layout>
    <x-slot name="header">Riwayat Transaksi</x-slot>

    <div class="space-y-4">

        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">Total: {{ $transactions->total() }} transaksi</p>
            @if(Auth::user()->isHeadDivision())
                <a href="{{ route('transaction.create') }}"
                   class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    + Tap NFC / Scan QR
                </a>
            @endif
        </div>

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
                        <th class="text-left px-6 py-3 font-semibold text-gray-600">Status</th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600">Waktu</th>
                        <th class="text-right px-6 py-3 font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($transactions as $trx)
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
                            <td class="px-6 py-4">
                                <span class="text-xs font-medium px-2 py-1 rounded-full {{ $statusColor }}">
                                    {{ ucfirst($trx->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-xs">{{ $trx->created_at->format('d M, H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('transaction.show', $trx) }}" class="text-blue-600 hover:underline">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-10 text-center text-gray-400">Belum ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $transactions->links() }}
    </div>
</x-app-layout>
