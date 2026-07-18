<x-app-layout>
    <x-slot name="header">Budget Plan</x-slot>

    <div class="space-y-4">

        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">Total: {{ $budgetPlans->total() }} budget plan</p>
            @if(Auth::user()->isHeadDivision())
                <a href="{{ route('budget-plan.create') }}"
                   class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    + Buat Budget Plan
                </a>
            @endif
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600">Judul</th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600">Divisi</th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600">Periode</th>
                        <th class="text-right px-6 py-3 font-semibold text-gray-600">Total</th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600">Status</th>
                        <th class="text-right px-6 py-3 font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($budgetPlans as $bp)
                        @php
                            $statusColor = match($bp->status) {
                                'draft'          => 'bg-gray-100 text-gray-600',
                                'pending_finance' => 'bg-yellow-50 text-yellow-700',
                                'revision'       => 'bg-orange-50 text-orange-700',
                                'pending_leader' => 'bg-blue-50 text-blue-700',
                                'approved'       => 'bg-green-50 text-green-700',
                                'rejected'       => 'bg-red-50 text-red-600',
                                default          => 'bg-gray-100 text-gray-600',
                            };
                            $statusLabel = match($bp->status) {
                                'draft'          => 'Draft',
                                'pending_finance' => 'Menunggu Finance',
                                'revision'       => 'Perlu Revisi',
                                'pending_leader' => 'Menunggu Pimpinan',
                                'approved'       => 'Disetujui',
                                'rejected'       => 'Ditolak',
                                default          => $bp->status,
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $bp->title }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $bp->divisi->name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $bp->period }}</td>
                            <td class="px-6 py-4 text-right font-mono text-gray-800">
                                Rp {{ number_format($bp->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-medium px-2 py-1 rounded-full {{ $statusColor }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('budget-plan.show', $bp) }}" class="text-blue-600 hover:underline">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400">Belum ada Budget Plan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $budgetPlans->links() }}
    </div>
</x-app-layout>
