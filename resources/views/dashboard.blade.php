<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    <div class="space-y-6">

        {{-- Welcome --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl p-6 text-white shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold">Selamat datang, {{ Auth::user()->name }}!</h2>
                    <p class="text-blue-100 text-sm mt-1">
                        @switch(Auth::user()->role)
                            @case('admin') Anda masuk sebagai <span class="font-semibold text-white">Administrator</span> @break
                            @case('head_division') Anda masuk sebagai <span class="font-semibold text-white">Kepala Divisi</span> @break
                            @case('finance_staff') Anda masuk sebagai <span class="font-semibold text-white">Staf Keuangan</span> @break
                            @case('leader') Anda masuk sebagai <span class="font-semibold text-white">Rektor / Pimpinan</span> @break
                        @endswitch
                    </p>
                </div>
                <div class="hidden sm:flex w-12 h-12 rounded-full bg-white/20 items-center justify-center text-xl font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>
        </div>

        {{-- ADMIN --}}
        @if(Auth::user()->isAdmin())
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                @include('dashboard._stat', [
                    'label' => 'Total Divisi', 'value' => $data['total_divisi'], 'color' => 'blue',
                    'icon'  => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'
                ])
                @include('dashboard._stat', [
                    'label' => 'Total Vendor', 'value' => $data['total_vendor'], 'color' => 'indigo',
                    'icon'  => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'
                ])
                @include('dashboard._stat', [
                    'label' => 'Total Saldo Wallet', 'value' => 'Rp ' . number_format($data['total_wallet'], 0, ',', '.'), 'color' => 'green',
                    'icon'  => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'
                ])
                @include('dashboard._stat', [
                    'label' => 'Transaksi Disetujui', 'value' => $data['trx_approved'], 'color' => 'green',
                    'icon'  => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                ])
                @include('dashboard._stat', [
                    'label' => 'Transaksi Pending', 'value' => $data['trx_pending'], 'color' => 'yellow',
                    'icon'  => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
                ])
                @include('dashboard._stat', [
                    'label' => 'Transaksi Ditolak', 'value' => $data['trx_rejected'], 'color' => 'red',
                    'icon'  => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'
                ])
            </div>
            @include('dashboard._recent-trx', ['transactions' => $data['recent_trx']])
        @endif

        {{-- HEAD OF DIVISION --}}
        @if(Auth::user()->isHeadDivision())
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                @include('dashboard._stat', [
                    'label' => 'BP Draft', 'value' => $data['bp_draft'], 'color' => 'gray',
                    'icon'  => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'
                ])
                @include('dashboard._stat', [
                    'label' => 'Menunggu Review', 'value' => $data['bp_pending'], 'color' => 'yellow',
                    'icon'  => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
                ])
                @include('dashboard._stat', [
                    'label' => 'BP Disetujui', 'value' => $data['bp_approved'], 'color' => 'green',
                    'icon'  => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                ])
                @include('dashboard._stat', [
                    'label' => 'Total Budget', 'value' => 'Rp ' . number_format($data['total_budget'], 0, ',', '.'), 'color' => 'blue',
                    'icon'  => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z'
                ])
                @include('dashboard._stat', [
                    'label' => 'Terealisasi', 'value' => 'Rp ' . number_format($data['total_realized'], 0, ',', '.'), 'color' => 'green',
                    'icon'  => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'
                ])
                @include('dashboard._stat', [
                    'label' => 'Sisa Anggaran', 'value' => 'Rp ' . number_format($data['total_budget'] - $data['total_realized'], 0, ',', '.'), 'color' => 'indigo',
                    'icon'  => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'
                ])
            </div>
            @include('dashboard._recent-trx', ['transactions' => $data['recent_trx']])
        @endif

        {{-- FINANCE STAFF --}}
        @if(Auth::user()->isFinanceStaff())
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                @include('dashboard._stat', [
                    'label' => 'BP Menunggu Review', 'value' => $data['bp_pending_finance'], 'color' => 'yellow',
                    'icon'  => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
                ])
                @include('dashboard._stat', [
                    'label' => 'Transaksi Pending', 'value' => $data['trx_pending'], 'color' => 'yellow',
                    'icon'  => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
                ])
                @include('dashboard._stat', [
                    'label' => 'Disetujui Hari Ini', 'value' => $data['trx_approved_today'], 'color' => 'green',
                    'icon'  => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                ])
                @include('dashboard._stat', [
                    'label' => 'Total Saldo Wallet', 'value' => 'Rp ' . number_format($data['total_wallet'], 0, ',', '.'), 'color' => 'blue',
                    'icon'  => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'
                ])
            </div>
            @include('dashboard._recent-trx', ['transactions' => $data['recent_trx']])
        @endif

        {{-- LEADER --}}
        @if(Auth::user()->isLeader())
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                @include('dashboard._stat', [
                    'label' => 'BP Menunggu Approval', 'value' => $data['bp_pending_leader'], 'color' => 'yellow',
                    'icon'  => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'
                ])
                @include('dashboard._stat', [
                    'label' => 'BP Disetujui', 'value' => $data['bp_approved_total'], 'color' => 'green',
                    'icon'  => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                ])
                @include('dashboard._stat', [
                    'label' => 'Total Budget Disetujui', 'value' => 'Rp ' . number_format($data['total_budget'], 0, ',', '.'), 'color' => 'blue',
                    'icon'  => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z'
                ])
                @include('dashboard._stat', [
                    'label' => 'Total Terealisasi', 'value' => 'Rp ' . number_format($data['total_realized'], 0, ',', '.'), 'color' => 'green',
                    'icon'  => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'
                ])
                @include('dashboard._stat', [
                    'label' => 'Saldo Tersisa', 'value' => 'Rp ' . number_format($data['total_wallet'], 0, ',', '.'), 'color' => 'indigo',
                    'icon'  => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'
                ])
                @include('dashboard._stat', [
                    'label' => 'Transaksi High Risk', 'value' => $data['trx_high_risk'], 'color' => 'red',
                    'icon'  => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'
                ])
            </div>
        @endif

    </div>
</x-app-layout>
