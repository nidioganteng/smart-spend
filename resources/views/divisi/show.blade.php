<x-app-layout>
    <x-slot name="header">Detail Divisi — {{ $divisi->name }}</x-slot>

    <div class="max-w-lg space-y-4">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        {{-- Info Divisi --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-3">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-semibold text-gray-800">Informasi Divisi</h2>
                <a href="{{ route('divisi.edit', $divisi) }}" class="text-sm text-blue-600 hover:underline">Edit</a>
            </div>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <p class="text-gray-400">Kode</p>
                    <p class="font-mono font-medium text-gray-800">{{ $divisi->code }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Nama</p>
                    <p class="font-medium text-gray-800">{{ $divisi->name }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Status</p>
                    @if($divisi->is_active)
                        <span class="text-xs font-medium text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full">Aktif</span>
                    @else
                        <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">Nonaktif</span>
                    @endif
                </div>
                <div>
                    <p class="text-gray-400">Dibuat</p>
                    <p class="text-gray-800">{{ $divisi->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Binding RFID --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
            <h2 class="text-base font-semibold text-gray-800">Kartu RFID</h2>

            @if($divisi->isBound())
                <div class="flex items-center gap-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                    <span class="w-2.5 h-2.5 bg-green-500 rounded-full shrink-0"></span>
                    <div class="text-sm">
                        <p class="font-medium text-green-800 font-mono">{{ $divisi->rfid_uid }}</p>
                        <p class="text-green-600 text-xs">Di-bind pada {{ $divisi->rfid_bound_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('divisi.unbind-rfid', $divisi) }}"
                      onsubmit="return confirm('Lepas binding kartu RFID dari divisi ini?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-red-600 border border-red-300 rounded-lg hover:bg-red-50 transition-colors">
                        Lepas Binding RFID
                    </button>
                </form>
            @else
                <p class="text-sm text-gray-400">Belum ada kartu RFID yang di-bind ke divisi ini.</p>

                @if($errors->has('rfid_uid'))
                    <p class="text-sm text-red-600">{{ $errors->first('rfid_uid') }}</p>
                @endif

                <form method="POST" action="{{ route('divisi.bind-rfid', $divisi) }}" class="flex gap-2">
                    @csrf
                    <input type="text" name="rfid_uid" value="{{ old('rfid_uid') }}"
                           class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono uppercase focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan UID kartu, cth: A1B2C3D4" required>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        Bind
                    </button>
                </form>
            @endif
        </div>

        <a href="{{ route('divisi.index') }}" class="inline-block text-sm text-gray-500 hover:text-gray-700">
            &larr; Kembali ke daftar divisi
        </a>
    </div>
</x-app-layout>
