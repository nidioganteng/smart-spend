<x-app-layout>
    <x-slot name="header">Audit Trail</x-slot>

    <div class="space-y-4">

        {{-- Filter --}}
        <form method="GET" action="{{ route('audit-log.index') }}" class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex gap-3 items-end flex-wrap">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Tipe Event</label>
                <select name="event_type" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Event</option>
                    @foreach([
                        'transaction_approved'    => 'Transaksi Approved',
                        'transaction_pending'     => 'Transaksi Pending',
                        'transaction_rejected'    => 'Transaksi Rejected',
                        'transaction_review_approve' => 'Review: Approve',
                        'transaction_review_reject'  => 'Review: Reject',
                        'bp_submitted'            => 'BP Diajukan',
                        'bp_finance_approve'      => 'BP Finance Approve',
                        'bp_finance_revision'     => 'BP Finance Revisi',
                        'bp_leader_approve'       => 'BP Leader Approve',
                        'bp_leader_reject'        => 'BP Leader Reject',
                    ] as $value => $label)
                        <option value="{{ $value }}" {{ request('event_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Divisi</label>
                <select name="divisi_id" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Divisi</option>
                    @foreach($divisis as $divisi)
                        <option value="{{ $divisi->id }}" {{ request('divisi_id') == $divisi->id ? 'selected' : '' }}>{{ $divisi->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                Filter
            </button>
            @if(request('event_type') || request('divisi_id'))
                <a href="{{ route('audit-log.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Reset
                </a>
            @endif
        </form>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600">Waktu</th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600">Event</th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600">Deskripsi</th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600">User</th>
                        <th class="text-left px-6 py-3 font-semibold text-gray-600">Divisi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($logs as $log)
                        @php
                            $eventColor = match(true) {
                                str_contains($log->event_type, 'approved') || str_contains($log->event_type, 'approve') => 'bg-green-50 text-green-700',
                                str_contains($log->event_type, 'rejected') || str_contains($log->event_type, 'reject')  => 'bg-red-50 text-red-600',
                                str_contains($log->event_type, 'pending') || str_contains($log->event_type, 'revision') => 'bg-yellow-50 text-yellow-700',
                                default => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 text-gray-400 text-xs whitespace-nowrap">{{ $log->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-6 py-3">
                                <span class="text-xs font-medium px-2 py-1 rounded-full {{ $eventColor }}">
                                    {{ $log->event_type }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-gray-700 max-w-xs">{{ $log->description }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $log->user?->name ?? '—' }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $log->divisi?->name ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400">Belum ada audit log.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $logs->links() }}
    </div>
</x-app-layout>
