<x-app-layout>
    <x-slot name="header">Master Data — Divisi</x-slot>

    <div class="space-y-5">

        {{-- Header Bar --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">{{ $divisis->total() }} divisi terdaftar</p>
            </div>
            <a href="{{ route('divisi.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Divisi
            </a>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Kode</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Divisi</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Kartu RFID</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="text-right px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($divisis as $divisi)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-mono text-xs text-gray-500 bg-gray-50/50">{{ $divisi->code }}</td>
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $divisi->name }}</td>
                            <td class="px-6 py-4">
                                @if($divisi->isBound())
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-green-700 bg-green-50 ring-1 ring-green-200 px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                        {{ $divisi->rfid_uid }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-xs text-gray-400 bg-gray-50 ring-1 ring-gray-200 px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span>
                                        Belum di-bind
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($divisi->is_active)
                                    <span class="inline-flex items-center text-xs font-medium text-blue-700 bg-blue-50 ring-1 ring-blue-200 px-2.5 py-1 rounded-full">Aktif</span>
                                @else
                                    <span class="inline-flex items-center text-xs font-medium text-gray-500 bg-gray-100 ring-1 ring-gray-200 px-2.5 py-1 rounded-full">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('divisi.show', $divisi) }}"
                                       class="px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                        Detail
                                    </a>
                                    <a href="{{ route('divisi.edit', $divisi) }}"
                                       class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('divisi.destroy', $divisi) }}" class="inline"
                                          onsubmit="return confirm('Hapus divisi {{ $divisi->name }}? Tindakan ini tidak bisa dibatalkan.')">
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
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Belum ada divisi</p>
                                        <p class="text-xs text-gray-400 mt-0.5">Mulai dengan menambahkan divisi pertama</p>
                                    </div>
                                    <a href="{{ route('divisi.create') }}"
                                       class="mt-1 px-4 py-2 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                        Tambah Divisi
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="text-sm">{{ $divisis->links() }}</div>
    </div>
</x-app-layout>
