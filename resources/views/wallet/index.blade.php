<x-app-layout>
    <x-slot name="header">Virtual Wallet Divisi</x-slot>

    <div class="space-y-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600">Kode</th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600">Nama Divisi</th>
                        <th class="text-right px-6 py-3 font-semibold text-gray-600">Saldo</th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600">Terakhir Diperbarui</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($wallets as $wallet)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-mono text-gray-700">{{ $wallet->divisi->code }}</td>
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $wallet->divisi->name }}</td>
                            <td class="px-6 py-4 text-right font-mono font-semibold {{ $wallet->balance > 0 ? 'text-green-600' : 'text-gray-400' }}">
                                Rp {{ number_format($wallet->balance, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-xs">{{ $wallet->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-400">Belum ada divisi terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
