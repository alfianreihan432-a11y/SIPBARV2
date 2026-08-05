<div class="space-y-6">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Kategori Inventaris</h2>
                <p class="text-sm text-slate-600">Tambahkan kategori baru untuk mengelompokkan barang inventaris.</p>
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
                    <label class="mb-1 block text-sm font-medium text-slate-700">Nama kategori</label>
                    <input wire:model="name" type="text" class="w-full rounded-xl border border-slate-300 px-4 py-2.5" placeholder="Contoh: Elektronik">
                    @error('name') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Ikon</label>
                    <input wire:model="icon" type="text" class="w-full rounded-xl border border-slate-300 px-4 py-2.5" placeholder="Contoh: 📁">
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Warna</label>
                    <input wire:model="color" type="color" class="w-full h-12 rounded-xl border border-slate-300 px-4 py-2.5">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Deskripsi</label>
                    <textarea wire:model="description" rows="3" class="w-full rounded-xl border border-slate-300 px-4 py-2.5" placeholder="Deskripsi kategori..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="rounded-xl bg-sky-600 px-4 py-2.5 font-semibold text-white">Simpan</button>
                    <button type="button" wire:click="resetForm" class="rounded-xl border border-slate-300 px-4 py-2.5">Reset</button>
                </div>
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Nama</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Deskripsi</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Warna</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @foreach ($categories as $category)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $category->name }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $category->description ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-sm font-semibold" style="background: {{ $category->color }}20; color: {{ $category->color }};">
                                    {{ $category->icon ?: '🗂️' }} {{ $category->color }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <button wire:click="edit({{ $category->id }})" class="text-sky-600">Edit</button>
                                <button wire:click="delete({{ $category->id }})" class="ml-3 text-red-600">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
