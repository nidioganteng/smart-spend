<x-app-layout>
    <x-slot name="header">Laporan Realisasi Anggaran</x-slot>

    <div class="space-y-5">

        {{-- Summary cards --}}
        @php
            $totalSaldo  = $wallets->sum('balance');
            $totalDivisi = $wallets->count();
        @endphp
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl p-5 text-white shadow-sm">
                <p class="text-xs font-medium text-blue-200 uppercase tracking-wide">Total Saldo Aktif</p>
                <p class="text-2xl font-bold mt-1">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</p>
                <p class="text-xs text-blue-200 mt-1">Dari {{ $totalDivisi }} divisi</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-green-50 ring-1 ring-green-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Divisi Aktif</p>
                    <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ $totalDivisi }}</p>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-800">Saldo per Divisi</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Kode</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Divisi</th>
                        <th class="text-right px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Saldo Wallet</th>
                        <th class="text-right px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Proporsi</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Terakhir Diperbarui</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($wallets as $wallet)
                        @php
                            $proportion = $totalSaldo > 0 ? ($wallet->balance / $totalSaldo * 100) : 0;
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-mono text-xs text-gray-500">{{ $wallet->divisi->code }}</td>
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $wallet->divisi->name }}</td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-mono font-semibold {{ $wallet->balance > 0 ? 'text-green-600' : 'text-gray-400' }}">
                                    Rp {{ number_format($wallet->balance, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <div class="w-20 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-blue-500 rounded-full" style="width: {{ $proportion }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-500 w-8 text-right">{{ number_format($proportion, 0) }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-400">{{ $wallet->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-700">Belum ada data wallet</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
