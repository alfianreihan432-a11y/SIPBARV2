<div class="space-y-6">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Inventaris Barang</h2>
                <p class="text-sm text-slate-600">Catat, cari, dan kelola aset sekolah dengan cepat.</p>
            </div>
            <div class="w-full max-w-md">
                <label class="mb-2 block text-sm font-medium text-slate-700">Cari barang</label>
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full rounded-xl border border-slate-300 px-4 py-2.5" placeholder="Cari nama barang...">
            </div>
        </div>

        @if (session()->has('message'))
            <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit="save" class="mt-6 grid gap-4 lg:grid-cols-2">
            <div class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Nama barang</label>
                    <input wire:model="name" type="text" class="w-full rounded-xl border border-slate-300 px-4 py-2.5">
                    @error('name') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Deskripsi</label>
                    <textarea wire:model="description" rows="3" class="w-full rounded-xl border border-slate-300 px-4 py-2.5"></textarea>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Kategori</label>
                        <select wire:model="category_id" class="w-full rounded-xl border border-slate-300 px-4 py-2.5">
                            <option value="">Pilih kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Lokasi</label>
                        <select wire:model="location_id" class="w-full rounded-xl border border-slate-300 px-4 py-2.5">
                            <option value="">Pilih lokasi</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->building }} / {{ $location->room }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="space-y-4">
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Supplier</label>
                        <select wire:model="supplier_id" class="w-full rounded-xl border border-slate-300 px-4 py-2.5">
                            <option value="">Pilih supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Merk</label>
                        <input wire:model="brand" type="text" class="w-full rounded-xl border border-slate-300 px-4 py-2.5">
                    </div>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Tipe</label>
                        <input wire:model="type" type="text" class="w-full rounded-xl border border-slate-300 px-4 py-2.5">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Tahun</label>
                        <input wire:model="purchase_year" type="number" class="w-full rounded-xl border border-slate-300 px-4 py-2.5">
                    </div>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Harga</label>
                        <input wire:model="price" type="number" class="w-full rounded-xl border border-slate-300 px-4 py-2.5">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Stok</label>
                        <input wire:model="stock" type="number" class="w-full rounded-xl border border-slate-300 px-4 py-2.5">
                    </div>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Kondisi</label>
                        <select wire:model="condition" class="w-full rounded-xl border border-slate-300 px-4 py-2.5">
                            <option>Baik</option>
                            <option>Rusak Ringan</option>
                            <option>Rusak Berat</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Status</label>
                        <select wire:model="status" class="w-full rounded-xl border border-slate-300 px-4 py-2.5">
                            <option>Tersedia</option>
                            <option>Dipinjam</option>
                            <option>Maintenance</option>
                            <option>Hilang</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Foto</label>
                    <input wire:model="photo" type="file" class="w-full rounded-xl border border-slate-300 px-4 py-2.5">
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
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Barang</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Kategori</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Lokasi</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @foreach ($items as $item)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-slate-900">{{ $item->name }}</div>
                                <div class="text-sm text-slate-500">{{ $item->inventory_number }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $item->category?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $item->location?->building ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full bg-sky-100 px-2.5 py-1 text-xs font-semibold text-sky-700">{{ $item->status }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <button wire:click="edit({{ $item->id }})" class="text-sky-600">Edit</button>
                                <button wire:click="delete({{ $item->id }})" class="ml-3 text-red-600">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
