<x-app-layout>
    <x-slot name="header">Tambah Divisi</x-slot>

    <div class="max-w-lg space-y-5">

        <a href="{{ route('divisi.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Divisi
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 mb-5">
                    <ul class="space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('divisi.store') }}" class="space-y-5">
                @csrf

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-gray-700">Nama Divisi <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="cth: Divisi Akademik" required>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-gray-700">Kode Divisi <span class="text-red-500">*</span></label>
                    <input type="text" name="code" value="{{ old('code') }}"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm font-mono uppercase
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="cth: DIV-AKD" required>
                    <p class="text-xs text-gray-400">Kode unik, otomatis diubah ke huruf kapital.</p>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors">
                        Simpan Divisi
                    </button>
                    <a href="{{ route('divisi.index') }}"
                       class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
