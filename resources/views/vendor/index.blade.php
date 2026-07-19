<x-app-layout>
    <x-slot name="header">Master Data — Vendor</x-slot>

    <div class="space-y-5">

        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">{{ $vendors->total() }} vendor terdaftar</p>
            <a href="{{ route('vendor.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Vendor
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Kode</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Vendor</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Kategori</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">QR Code</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="text-right px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($vendors as $vendor)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-mono text-xs text-gray-500">{{ $vendor->code }}</td>
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $vendor->name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $vendor->category }}</td>
                            <td class="px-6 py-4">
                                @if($vendor->isQrValid())
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-green-700 bg-green-50 ring-1 ring-green-200 px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                        Valid s/d {{ $vendor->qr_expires_at->format('d M H:i') }}
                                    </span>
                                @elseif($vendor->qr_token)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-red-600 bg-red-50 ring-1 ring-red-200 px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                        Expired
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">Belum digenerate</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($vendor->is_active)
                                    <span class="text-xs font-medium text-blue-700 bg-blue-50 ring-1 ring-blue-200 px-2.5 py-1 rounded-full">Aktif</span>
                                @else
                                    <span class="text-xs font-medium text-gray-500 bg-gray-100 ring-1 ring-gray-200 px-2.5 py-1 rounded-full">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('vendor.show', $vendor) }}"
                                       class="px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                        Detail
                                    </a>
                                    <a href="{{ route('vendor.edit', $vendor) }}"
                                       class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('vendor.destroy', $vendor) }}" class="inline"
                                          onsubmit="return confirm('Hapus vendor {{ $vendor->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Belum ada vendor</p>
                                        <p class="text-xs text-gray-400 mt-0.5">Tambahkan vendor pertama untuk memulai</p>
                                    </div>
                                    <a href="{{ route('vendor.create') }}"
                                       class="px-4 py-2 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                        Tambah Vendor
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="text-sm">{{ $vendors->links() }}</div>
    </div>
</x-app-layout>
