<div class="space-y-6">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Form Peminjaman</h2>
                <p class="text-sm text-slate-600">Pilih siswa dari database agar hanya pengguna siswa yang terdaftar dapat meminjam barang.</p>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="save" class="mt-6 grid gap-4 lg:grid-cols-2">
            <div class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Siswa</label>
                    <select wire:model="student_id" class="w-full rounded-xl border border-slate-300 px-4 py-2.5">
                        <option value="">Pilih siswa</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}">{{ $student->name }} — {{ $student->email }}</option>
                        @endforeach
                    </select>
                    @error('student_id') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Barang</label>
                    <select wire:model="item_id" class="w-full rounded-xl border border-slate-300 px-4 py-2.5">
                        <option value="">Pilih barang</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->inventory_number }}) — Stok: {{ $item->stock }}</option>
                        @endforeach
                    </select>
                    @error('item_id') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Jumlah</label>
                        <input wire:model="quantity" type="number" min="1" class="w-full rounded-xl border border-slate-300 px-4 py-2.5">
                        @error('quantity') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Jatuh tempo</label>
                        <input wire:model="due_at" type="date" class="w-full rounded-xl border border-slate-300 px-4 py-2.5">
                        @error('due_at') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Catatan</label>
                    <textarea wire:model="notes" rows="5" class="w-full rounded-xl border border-slate-300 px-4 py-2.5" placeholder="Keterangan peminjaman..."></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="rounded-xl bg-sky-600 px-4 py-2.5 font-semibold text-white">Simpan Peminjaman</button>
                    <button type="button" wire:click="resetForm" class="rounded-xl border border-slate-300 px-4 py-2.5">Reset</button>
                </div>
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-semibold text-slate-900">Peminjaman Terakhir</h2>
            <p class="text-sm text-slate-600">Menampilkan daftar peminjaman terakhir yang dibuat oleh admin.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">No. Peminjaman</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Siswa</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Barang</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Jumlah</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($borrowings as $borrowing)
                        <tr>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $borrowing->number }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $borrowing->user->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">
                                @foreach ($borrowing->details as $detail)
                                    <div>{{ $detail->item->name ?? '-' }}</div>
                                @endforeach
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">
                                @foreach ($borrowing->details as $detail)
                                    <div>{{ $detail->quantity }}</div>
                                @endforeach
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ ucfirst($borrowing->status) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-3 text-sm text-slate-600" colspan="5">Belum ada peminjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
