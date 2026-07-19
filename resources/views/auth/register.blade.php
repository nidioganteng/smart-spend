<x-guest-layout>
    <div class="space-y-6">

        <div>
            <h2 class="text-2xl font-bold text-gray-900">Buat akun baru</h2>
            <p class="mt-1 text-sm text-gray-500">Isi form di bawah untuk mendaftar</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div class="space-y-1">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}"
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors
                              @error('name') border-red-400 bg-red-50 @enderror"
                       placeholder="Nama Anda" required autofocus autocomplete="name">
                @error('name')
                    <p class="text-xs text-red-600 flex items-center gap-1">
                        <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="space-y-1">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors
                              @error('email') border-red-400 bg-red-50 @enderror"
                       placeholder="nama@email.com" required autocomplete="username">
                @error('email')
                    <p class="text-xs text-red-600 flex items-center gap-1">
                        <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="space-y-1">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" type="password" name="password"
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors
                              @error('password') border-red-400 bg-red-50 @enderror"
                       placeholder="Min. 8 karakter" required autocomplete="new-password">
                @error('password')
                    <p class="text-xs text-red-600 flex items-center gap-1">
                        <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="space-y-1">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors
                              @error('password_confirmation') border-red-400 bg-red-50 @enderror"
                       placeholder="Ulangi password" required autocomplete="new-password">
                @error('password_confirmation')
                    <p class="text-xs text-red-600 flex items-center gap-1">
                        <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg
                           transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Daftar Sekarang
            </button>

            <p class="text-center text-sm text-gray-500">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium">Masuk di sini</a>
            </p>
        </form>

    </div>
</x-guest-layout>
