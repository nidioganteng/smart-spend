<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    <div class="space-y-6">

        {{-- Welcome Card --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-800 mb-1">
                Selamat datang, {{ Auth::user()->name }}!
            </h2>
            <p class="text-gray-500 text-sm">
                Anda login sebagai
                <span class="font-medium text-blue-600">
                    @switch(Auth::user()->role)
                        @case('admin') Administrator @break
                        @case('head_division') Kepala Divisi @break
                        @case('finance_staff') Staf Keuangan @break
                        @case('leader') Rektor / Pimpinan @break
                    @endswitch
                </span>
            </p>
        </div>

        {{-- Placeholder Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Budget Disetujui</p>
                <p class="text-2xl font-bold text-gray-800">—</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Realisasi</p>
                <p class="text-2xl font-bold text-gray-800">—</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Saldo Wallet</p>
                <p class="text-2xl font-bold text-gray-800">—</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Transaksi Pending</p>
                <p class="text-2xl font-bold text-gray-800">—</p>
            </div>
        </div>

        {{-- Coming Soon Notice --}}
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 text-sm text-blue-700">
            Fitur lengkap sedang dalam pengembangan. Milestone 1 selesai — autentikasi dan RBAC aktif.
        </div>

    </div>
</x-app-layout>
