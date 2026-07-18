<x-app-layout>
    <x-slot name="header">Detail Budget Plan</x-slot>

    <div class="max-w-2xl space-y-4">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        @php
            $statusColor = match($budgetPlan->status) {
                'draft'          => 'bg-gray-100 text-gray-600',
                'pending_finance' => 'bg-yellow-50 text-yellow-700',
                'revision'       => 'bg-orange-50 text-orange-700',
                'pending_leader' => 'bg-blue-50 text-blue-700',
                'approved'       => 'bg-green-50 text-green-700',
                'rejected'       => 'bg-red-50 text-red-600',
                default          => 'bg-gray-100 text-gray-600',
            };
            $statusLabel = match($budgetPlan->status) {
                'draft'          => 'Draft',
                'pending_finance' => 'Menunggu Review Finance',
                'revision'       => 'Perlu Revisi',
                'pending_leader' => 'Menunggu Approval Pimpinan',
                'approved'       => 'Disetujui',
                'rejected'       => 'Ditolak',
                default          => $budgetPlan->status,
            };
        @endphp

        {{-- Info Utama --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">{{ $budgetPlan->title }}</h2>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $budgetPlan->divisi->name }} &middot; {{ $budgetPlan->period }}</p>
                </div>
                <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $statusColor }}">{{ $statusLabel }}</span>
            </div>

            <div class="grid grid-cols-3 gap-4 pt-2 border-t border-gray-100 text-sm">
                <div>
                    <p class="text-gray-400">Total Anggaran</p>
                    <p class="font-mono font-semibold text-gray-800">Rp {{ number_format($budgetPlan->total_amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Terealisasi</p>
                    <p class="font-mono font-semibold text-gray-800">Rp {{ number_format($budgetPlan->realized_amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Sisa</p>
                    <p class="font-mono font-semibold text-blue-600">Rp {{ number_format($budgetPlan->remainingBudget(), 0, ',', '.') }}</p>
                </div>
            </div>

            @if($budgetPlan->isApproved())
                <div class="pt-2 border-t border-gray-100 text-sm">
                    <p class="text-gray-400">Saldo Wallet Divisi</p>
                    <p class="font-mono font-semibold text-green-600">
                        Rp {{ number_format($budgetPlan->divisi->wallet->balance, 0, ',', '.') }}
                    </p>
                </div>
            @endif

            <p class="text-xs text-gray-400">Diajukan oleh {{ $budgetPlan->submittedBy->name }} pada {{ $budgetPlan->created_at->format('d M Y, H:i') }}</p>
        </div>

        {{-- Item Anggaran --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700">Item Anggaran</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600">Kategori</th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600">Deskripsi</th>
                        <th class="text-right px-6 py-3 font-semibold text-gray-600">Ceiling</th>
                        <th class="text-right px-6 py-3 font-semibold text-gray-600">Realisasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($budgetPlan->items as $item)
                        <tr>
                            <td class="px-6 py-3 text-gray-700">{{ $item->category }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $item->description }}</td>
                            <td class="px-6 py-3 text-right font-mono text-gray-800">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-right font-mono text-gray-500">Rp {{ number_format($item->realized_amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Riwayat Review --}}
        @if($budgetPlan->finance_reviewed_at || $budgetPlan->leader_approved_at)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-3">
                <h3 class="text-sm font-semibold text-gray-700">Riwayat Review</h3>
                @if($budgetPlan->finance_reviewed_at)
                    <div class="text-sm border-l-2 border-yellow-400 pl-3">
                        <p class="font-medium text-gray-700">Finance: {{ $budgetPlan->financeReviewedBy->name }}</p>
                        <p class="text-gray-500 text-xs">{{ $budgetPlan->finance_reviewed_at->format('d M Y, H:i') }}</p>
                        @if($budgetPlan->finance_notes)
                            <p class="text-gray-600 mt-1">{{ $budgetPlan->finance_notes }}</p>
                        @endif
                    </div>
                @endif
                @if($budgetPlan->leader_approved_at)
                    <div class="text-sm border-l-2 border-blue-400 pl-3">
                        <p class="font-medium text-gray-700">Pimpinan: {{ $budgetPlan->leaderApprovedBy->name }}</p>
                        <p class="text-gray-500 text-xs">{{ $budgetPlan->leader_approved_at->format('d M Y, H:i') }}</p>
                        @if($budgetPlan->leader_notes)
                            <p class="text-gray-600 mt-1">{{ $budgetPlan->leader_notes }}</p>
                        @endif
                    </div>
                @endif
            </div>
        @endif

        {{-- Action Buttons --}}
        <div class="flex flex-wrap gap-3">

            {{-- Head of Division: submit atau edit --}}
            @if(Auth::user()->isHeadDivision())
                @if($budgetPlan->isDraft() || $budgetPlan->isRevision())
                    <form method="POST" action="{{ route('budget-plan.submit', $budgetPlan) }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Ajukan ke Finance
                        </button>
                    </form>
                    <a href="{{ route('budget-plan.edit', $budgetPlan) }}"
                       class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        Edit
                    </a>
                    @if($budgetPlan->isDraft())
                        <form method="POST" action="{{ route('budget-plan.destroy', $budgetPlan) }}"
                              onsubmit="return confirm('Hapus budget plan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-4 py-2 text-red-600 border border-red-300 text-sm font-medium rounded-lg hover:bg-red-50 transition-colors">
                                Hapus
                            </button>
                        </form>
                    @endif
                @endif
            @endif

            {{-- Finance Staff: review --}}
            @if(Auth::user()->isFinanceStaff() && $budgetPlan->status === 'pending_finance')
                <form method="POST" action="{{ route('budget-plan.finance-review', $budgetPlan) }}" class="flex gap-2 items-end flex-wrap">
                    @csrf
                    <div>
                        <textarea name="notes" rows="2"
                                  class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-72"
                                  placeholder="Catatan (opsional)..."></textarea>
                    </div>
                    <button type="submit" name="action" value="approve"
                            class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                        Setujui ke Pimpinan
                    </button>
                    <button type="submit" name="action" value="revision"
                            class="px-4 py-2 bg-orange-500 text-white text-sm font-medium rounded-lg hover:bg-orange-600 transition-colors">
                        Kembalikan untuk Revisi
                    </button>
                </form>
            @endif

            {{-- Leader: approve atau reject --}}
            @if(Auth::user()->isLeader() && $budgetPlan->status === 'pending_leader')
                <form method="POST" action="{{ route('budget-plan.leader-review', $budgetPlan) }}" class="flex gap-2 items-end flex-wrap">
                    @csrf
                    <div>
                        <textarea name="notes" rows="2"
                                  class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-72"
                                  placeholder="Catatan (opsional)..."></textarea>
                    </div>
                    <button type="submit" name="action" value="approve"
                            class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                        Approve & Alokasikan Dana
                    </button>
                    <button type="submit" name="action" value="reject"
                            class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                        Tolak
                    </button>
                </form>
            @endif

        </div>

        <a href="{{ route('budget-plan.index') }}" class="inline-block text-sm text-gray-500 hover:text-gray-700">
            &larr; Kembali ke daftar
        </a>
    </div>
</x-app-layout>
