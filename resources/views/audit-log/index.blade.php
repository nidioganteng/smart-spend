<x-app-layout>
    <x-slot name="header">Audit Trail</x-slot>

    <div class="space-y-5">

        {{-- Filter --}}
        <form method="GET" action="{{ route('audit-log.index') }}"
              class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex gap-3 items-end flex-wrap">
            <div class="space-y-1">
                <label class="block text-xs font-medium text-gray-600">Tipe Event</label>
                <select name="event_type"
                        class="border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="">Semua Event</option>
                    @foreach([
                        'transaction_approved'       => 'Transaksi Disetujui',
                        'transaction_pending'        => 'Transaksi Pending',
                        'transaction_rejected'       => 'Transaksi Ditolak',
                        'transaction_review_approve' => 'Review: Approve',
                        'transaction_review_reject'  => 'Review: Reject',
                        'bp_submitted'               => 'BP Diajukan',
                        'bp_finance_approve'         => 'BP Finance Approve',
                        'bp_finance_revision'        => 'BP Finance Revisi',
                        'bp_leader_approve'          => 'BP Leader Approve',
                        'bp_leader_reject'           => 'BP Leader Reject',
                    ] as $value => $label)
                        <option value="{{ $value }}" {{ request('event_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-1">
                <label class="block text-xs font-medium text-gray-600">Divisi</label>
                <select name="divisi_id"
                        class="border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="">Semua Divisi</option>
                    @foreach($divisis as $divisi)
                        <option value="{{ $divisi->id }}" {{ request('divisi_id') == $divisi->id ? 'selected' : '' }}>
                            {{ $divisi->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                Filter
            </button>
            @if(request('event_type') || request('divisi_id'))
                <a href="{{ route('audit-log.index') }}"
                   class="px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                    Reset Filter
                </a>
            @endif
        </form>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Waktu</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Event</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Deskripsi</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">User</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Divisi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($logs as $log)
                            @php
                                $eventBadge = match(true) {
                                    str_contains($log->event_type, 'approved') || str_contains($log->event_type, 'approve')
                                        => 'bg-green-50 text-green-700 ring-1 ring-green-200',
                                    str_contains($log->event_type, 'rejected') || str_contains($log->event_type, 'reject')
                                        => 'bg-red-50 text-red-600 ring-1 ring-red-200',
                                    str_contains($log->event_type, 'pending') || str_contains($log->event_type, 'revision')
                                        => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
                                    default => 'bg-gray-100 text-gray-600 ring-1 ring-gray-200',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-xs text-gray-400 whitespace-nowrap">
                                    {{ $log->created_at->format('d M Y') }}<br>
                                    <span class="text-gray-300">{{ $log->created_at->format('H:i:s') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $eventBadge }}">
                                        {{ $log->event_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-700 max-w-xs text-xs leading-relaxed">{{ $log->description }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @if($log->user)
                                            <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-xs font-bold shrink-0">
                                                {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                            </div>
                                            <span class="text-gray-700 text-xs">{{ $log->user->name }}</span>
                                        @else
                                            <span class="text-gray-400 text-xs">System</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600 text-xs">{{ $log->divisi?->name ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700">Belum ada audit log</p>
                                        <p class="text-xs text-gray-400">Log akan muncul setelah ada aktivitas di sistem</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="text-sm">{{ $logs->links() }}</div>
    </div>
</x-app-layout>
