<x-app-layout>
    <x-slot name="header">Master Data — Vendor</x-slot>

    <div class="space-y-4">

        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">Total: {{ $vendors->total() }} vendor</p>
            <a href="{{ route('vendor.create') }}"
               class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                + Tambah Vendor
            </a>
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
                        <th class="text-left px-6 py-3 font-semibold text-gray-600">Kode</th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600">Nama Vendor</th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600">Kategori</th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600">QR Code</th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600">Status</th>
                        <th class="text-right px-6 py-3 font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($vendors as $vendor)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-mono text-gray-700">{{ $vendor->code }}</td>
                            <td class="px-6 py-4 text-gray-800 font-medium">{{ $vendor->name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $vendor->category }}</td>
                            <td class="px-6 py-4">
                                @if($vendor->isQrValid())
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700 bg-green-50 px-2 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                        Valid s/d {{ $vendor->qr_expires_at->format('d M H:i') }}
                                    </span>
                                @elseif($vendor->qr_token)
                                    <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded-full">Expired</span>
                                @else
                                    <span class="text-xs text-gray-400">Belum digenerate</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($vendor->is_active)
                                    <span class="text-xs font-medium text-blue-700 bg-blue-50 px-2 py-1 rounded-full">Aktif</span>
                                @else
                                    <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded-full">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('vendor.show', $vendor) }}" class="text-blue-600 hover:underline mr-3">Detail</a>
                                <a href="{{ route('vendor.edit', $vendor) }}" class="text-gray-600 hover:underline mr-3">Edit</a>
                                <form method="POST" action="{{ route('vendor.destroy', $vendor) }}" class="inline"
                                      onsubmit="return confirm('Hapus vendor {{ $vendor->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400">Belum ada vendor. Tambahkan vendor pertama.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $vendors->links() }}
    </div>
</x-app-layout>
