<x-app-layout>
    <x-slot name="header">Detail Divisi — {{ $divisi->name }}</x-slot>

    <div class="max-w-lg space-y-5">

        <a href="{{ route('divisi.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Divisi
        </a>

        {{-- Info Divisi --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Informasi Divisi
                </h2>
                <a href="{{ route('divisi.edit', $divisi) }}"
                   class="px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                    Edit
                </a>
            </div>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Kode</p>
                    <p class="font-mono font-medium text-gray-800 bg-gray-50 px-2 py-1 rounded text-xs inline-block">{{ $divisi->code }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Nama</p>
                    <p class="font-medium text-gray-800">{{ $divisi->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Status</p>
                    @if($divisi->is_active)
                        <span class="text-xs font-medium text-blue-700 bg-blue-50 ring-1 ring-blue-200 px-2.5 py-0.5 rounded-full">Aktif</span>
                    @else
                        <span class="text-xs font-medium text-gray-500 bg-gray-100 ring-1 ring-gray-200 px-2.5 py-0.5 rounded-full">Nonaktif</span>
                    @endif
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Dibuat</p>
                    <p class="text-gray-700 text-sm">{{ $divisi->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Binding RFID --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-sm font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Kartu RFID
            </h2>

            @if($divisi->isBound())
                <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-xl mb-4">
                    <div class="w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center shrink-0">
                        <span class="w-2.5 h-2.5 bg-green-500 rounded-full"></span>
                    </div>
                    <div>
                        <p class="font-mono font-semibold text-green-800 text-sm">{{ $divisi->rfid_uid }}</p>
                        <p class="text-green-600 text-xs mt-0.5">
                            Di-bind pada {{ $divisi->rfid_bound_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>
                <form method="POST" action="{{ route('divisi.unbind-rfid', $divisi) }}"
                      onsubmit="return confirm('Lepas binding kartu RFID dari divisi ini?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 rounded-xl transition-colors">
                        Lepas Binding RFID
                    </button>
                </form>
            @else
                <div class="flex items-center gap-3 p-4 bg-gray-50 border border-gray-200 rounded-xl mb-4">
                    <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                        <span class="w-2.5 h-2.5 bg-gray-300 rounded-full"></span>
                    </div>
                    <p class="text-sm text-gray-500">Belum ada kartu RFID yang di-bind</p>
                </div>

                @error('rfid_uid')
                    <p class="text-sm text-red-600 mb-3 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror

                <form method="POST" action="{{ route('divisi.bind-rfid', $divisi) }}" class="flex gap-2">
                    @csrf
                    <input type="text" name="rfid_uid" value="{{ old('rfid_uid') }}"
                           class="flex-1 border border-gray-300 rounded-xl px-3 py-2.5 text-sm font-mono uppercase
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Masukkan UID, cth: A1B2C3D4" required>
                    <button type="submit"
                            class="px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        Bind
                    </button>
                </form>
            @endif
        </div>

    </div>
</x-app-layout>
