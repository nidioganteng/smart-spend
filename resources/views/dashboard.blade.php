<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    <div class="space-y-6">

        {{-- Welcome Card --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-800 mb-1">Selamat datang, {{ Auth::user()->name }}!</h2>
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

        {{-- ADMIN --}}
        @if(Auth::user()->isAdmin())
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                @include('dashboard._stat', ['label' => 'Total Divisi', 'value' => $data['total_divisi'], 'color' => 'blue'])
                @include('dashboard._stat', ['label' => 'Total Vendor', 'value' => $data['total_vendor'], 'color' => 'blue'])
                @include('dashboard._stat', ['label' => 'Total Saldo Wallet', 'value' => 'Rp ' . number_format($data['total_wallet'], 0, ',', '.'), 'color' => 'green'])
                @include('dashboard._stat', ['label' => 'Transaksi Approved', 'value' => $data['trx_approved'], 'color' => 'green'])
                @include('dashboard._stat', ['label' => 'Transaksi Pending', 'value' => $data['trx_pending'], 'color' => 'yellow'])
                @include('dashboard._stat', ['label' => 'Transaksi Rejected', 'value' => $data['trx_rejected'], 'color' => 'red'])
            </div>
            @include('dashboard._recent-trx', ['transactions' => $data['recent_trx']])
        @endif

        {{-- HEAD OF DIVISION --}}
        @if(Auth::user()->isHeadDivision())
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                @include('dashboard._stat', ['label' => 'BP Draft', 'value' => $data['bp_draft'], 'color' => 'gray'])
                @include('dashboard._stat', ['label' => 'BP Menunggu Review', 'value' => $data['bp_pending'], 'color' => 'yellow'])
                @include('dashboard._stat', ['label' => 'BP Disetujui', 'value' => $data['bp_approved'], 'color' => 'green'])
                @include('dashboard._stat', ['label' => 'Total Budget', 'value' => 'Rp ' . number_format($data['total_budget'], 0, ',', '.'), 'color' => 'blue'])
                @include('dashboard._stat', ['label' => 'Terealisasi', 'value' => 'Rp ' . number_format($data['total_realized'], 0, ',', '.'), 'color' => 'green'])
                @include('dashboard._stat', ['label' => 'Sisa Anggaran', 'value' => 'Rp ' . number_format($data['total_budget'] - $data['total_realized'], 0, ',', '.'), 'color' => 'blue'])
            </div>
            @include('dashboard._recent-trx', ['transactions' => $data['recent_trx']])
        @endif

        {{-- FINANCE STAFF --}}
        @if(Auth::user()->isFinanceStaff())
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                @include('dashboard._stat', ['label' => 'BP Menunggu Review', 'value' => $data['bp_pending_finance'], 'color' => 'yellow'])
                @include('dashboard._stat', ['label' => 'Transaksi Pending', 'value' => $data['trx_pending'], 'color' => 'yellow'])
                @include('dashboard._stat', ['label' => 'Transaksi Approved Hari Ini', 'value' => $data['trx_approved_today'], 'color' => 'green'])
                @include('dashboard._stat', ['label' => 'Total Saldo Wallet', 'value' => 'Rp ' . number_format($data['total_wallet'], 0, ',', '.'), 'color' => 'blue'])
            </div>
            @include('dashboard._recent-trx', ['transactions' => $data['recent_trx']])
        @endif

        {{-- LEADER --}}
        @if(Auth::user()->isLeader())
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                @include('dashboard._stat', ['label' => 'BP Menunggu Approval', 'value' => $data['bp_pending_leader'], 'color' => 'yellow'])
                @include('dashboard._stat', ['label' => 'BP Disetujui', 'value' => $data['bp_approved_total'], 'color' => 'green'])
                @include('dashboard._stat', ['label' => 'Total Budget Disetujui', 'value' => 'Rp ' . number_format($data['total_budget'], 0, ',', '.'), 'color' => 'blue'])
                @include('dashboard._stat', ['label' => 'Total Terealisasi', 'value' => 'Rp ' . number_format($data['total_realized'], 0, ',', '.'), 'color' => 'green'])
                @include('dashboard._stat', ['label' => 'Saldo Tersisa', 'value' => 'Rp ' . number_format($data['total_wallet'], 0, ',', '.'), 'color' => 'blue'])
                @include('dashboard._stat', ['label' => 'Transaksi High Risk', 'value' => $data['trx_high_risk'], 'color' => 'red'])
            </div>
        @endif

    </div>
</x-app-layout>
