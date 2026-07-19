<x-app-layout>
    <x-slot name="header">Budget Plan</x-slot>

    <div class="space-y-5">

        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">{{ $budgetPlans->total() }} budget plan</p>
            @if(Auth::user()->isHeadDivision())
                <a href="{{ route('budget-plan.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Budget Plan
                </a>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Judul</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Divisi</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Periode</th>
                        <th class="text-right px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Total</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="text-right px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($budgetPlans as $bp)
                        @php
                            $statusBadge = match($bp->status) {
                                'draft'           => 'bg-gray-100 text-gray-600 ring-1 ring-gray-200',
                                'pending_finance' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
                                'revision'        => 'bg-orange-50 text-orange-700 ring-1 ring-orange-200',
                                'pending_leader'  => 'bg-blue-50 text-blue-700 ring-1 ring-blue-200',
                                'approved'        => 'bg-green-50 text-green-700 ring-1 ring-green-200',
                                'rejected'        => 'bg-red-50 text-red-600 ring-1 ring-red-200',
                                default           => 'bg-gray-100 text-gray-600',
                            };
                            $statusLabel = match($bp->status) {
                                'draft'           => 'Draft',
                                'pending_finance' => 'Menunggu Finance',
                                'revision'        => 'Perlu Revisi',
                                'pending_leader'  => 'Menunggu Pimpinan',
                                'approved'        => 'Disetujui',
                                'rejected'        => 'Ditolak',
                                default           => $bp->status,
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $bp->title }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $bp->divisi->name }}</td>
                            <td class="px-6 py-4 text-gray-500 text-xs">{{ $bp->period }}</td>
                            <td class="px-6 py-4 text-right font-mono font-medium text-gray-800">
                                Rp {{ number_format($bp->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusBadge }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('budget-plan.show', $bp) }}"
                                   class="px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Belum ada Budget Plan</p>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            @if(Auth::user()->isHeadDivision())
                                                Buat budget plan untuk divisi Anda
                                            @else
                                                Budget plan yang masuk akan tampil di sini
                                            @endif
                                        </p>
                                    </div>
                                    @if(Auth::user()->isHeadDivision())
                                        <a href="{{ route('budget-plan.create') }}"
                                           class="px-4 py-2 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                            Buat Budget Plan
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="text-sm">{{ $budgetPlans->links() }}</div>
    </div>
</x-app-layout>
