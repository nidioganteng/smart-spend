<x-app-layout>
    <x-slot name="header">Edit Budget Plan</x-slot>

    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

            @if($budgetPlan->isRevision() && $budgetPlan->finance_notes)
                <div class="bg-orange-50 border border-orange-200 text-orange-700 text-sm rounded-lg px-4 py-3 mb-4">
                    <p class="font-semibold mb-1">Catatan revisi dari Finance Staff:</p>
                    <p>{{ $budgetPlan->finance_notes }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 mb-4">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('budget-plan.update', $budgetPlan) }}" class="space-y-5">
                @csrf @method('PUT')

                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Budget Plan</label>
                        <input type="text" name="title" value="{{ old('title', $budgetPlan->title) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Divisi</label>
                        <input type="text" value="{{ $budgetPlan->divisi->name }}"
                               class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-500" disabled>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                        <input type="text" name="period" value="{{ old('period', $budgetPlan->period) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-medium text-gray-700">Item Anggaran</label>
                        <button type="button" id="add-item" class="text-sm text-blue-600 hover:underline">+ Tambah Item</button>
                    </div>
                    <div id="items-container" class="space-y-3">
                        @foreach($budgetPlan->items as $i => $item)
                            <div class="item-row grid grid-cols-12 gap-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="col-span-3">
                                    <input type="text" name="items[{{ $i }}][category]" value="{{ old("items.$i.category", $item->category) }}"
                                           class="w-full border border-gray-300 rounded-md px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                                           placeholder="Kategori" required>
                                </div>
                                <div class="col-span-5">
                                    <input type="text" name="items[{{ $i }}][description]" value="{{ old("items.$i.description", $item->description) }}"
                                           class="w-full border border-gray-300 rounded-md px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                                           placeholder="Deskripsi" required>
                                </div>
                                <div class="col-span-3">
                                    <input type="number" name="items[{{ $i }}][amount]" value="{{ old("items.$i.amount", $item->amount) }}"
                                           class="w-full border border-gray-300 rounded-md px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                                           placeholder="Nominal (Rp)" min="1000" required>
                                </div>
                                <div class="col-span-1 flex items-center justify-center">
                                    <button type="button" class="remove-item text-red-400 hover:text-red-600 text-lg font-bold {{ $loop->count > 1 ? '' : 'hidden' }}">&times;</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('budget-plan.show', $budgetPlan) }}"
                       class="px-5 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        let itemCount = {{ $budgetPlan->items->count() }};

        document.getElementById('add-item').addEventListener('click', function () {
            const container = document.getElementById('items-container');
            const row = document.createElement('div');
            row.className = 'item-row grid grid-cols-12 gap-2 p-3 bg-gray-50 rounded-lg border border-gray-200';
            row.innerHTML = `
                <div class="col-span-3"><input type="text" name="items[${itemCount}][category]" class="w-full border border-gray-300 rounded-md px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Kategori" required></div>
                <div class="col-span-5"><input type="text" name="items[${itemCount}][description]" class="w-full border border-gray-300 rounded-md px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Deskripsi" required></div>
                <div class="col-span-3"><input type="number" name="items[${itemCount}][amount]" class="w-full border border-gray-300 rounded-md px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Nominal (Rp)" min="1000" required></div>
                <div class="col-span-1 flex items-center justify-center"><button type="button" class="remove-item text-red-400 hover:text-red-600 text-lg font-bold">&times;</button></div>`;
            container.appendChild(row);
            row.querySelector('.remove-item').addEventListener('click', function () { row.remove(); toggleRemoveButtons(); });
            itemCount++;
            toggleRemoveButtons();
        });

        function toggleRemoveButtons() {
            const rows = document.querySelectorAll('.item-row');
            rows.forEach(row => {
                const btn = row.querySelector('.remove-item');
                if (btn) btn.classList.toggle('hidden', rows.length === 1);
            });
        }

        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.addEventListener('click', function () { btn.closest('.item-row').remove(); toggleRemoveButtons(); });
        });
    </script>
</x-app-layout>
