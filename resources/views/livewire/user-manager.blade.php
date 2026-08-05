<div class="space-y-6">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Manajemen Pengguna</h2>
                <p class="text-sm text-slate-600">Tambahkan siswa atau guru baru langsung ke database dengan role yang sesuai.</p>
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
                    <label class="mb-1 block text-sm font-medium text-slate-700">Nama</label>
                    <input wire:model="name" type="text" class="w-full rounded-xl border border-slate-300 px-4 py-2.5" placeholder="Nama lengkap siswa atau guru">
                    @error('name') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                    <input wire:model="email" type="email" class="w-full rounded-xl border border-slate-300 px-4 py-2.5" placeholder="email@domain.tld">
                    @error('email') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Password</label>
                    <input wire:model="password" type="password" class="w-full rounded-xl border border-slate-300 px-4 py-2.5" placeholder="Minimal 6 karakter">
                    @if($editingId)
                        <p class="text-xs text-slate-500">Kosongkan jika tidak ingin mengubah password.</p>
                    @endif
                    @error('password') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Peran</label>
                    <select wire:model="role" class="w-full rounded-xl border border-slate-300 px-4 py-2.5">
                        <option value="">Pilih role</option>
                        @foreach ($roles as $roleName)
                            <option value="{{ $roleName }}">{{ ucfirst($roleName) }}</option>
                        @endforeach
                    </select>
                    @error('role') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="rounded-xl bg-sky-600 px-4 py-2.5 font-semibold text-white">Simpan</button>
                    <button type="button" wire:click="resetForm" class="rounded-xl border border-slate-300 px-4 py-2.5">Reset</button>
                </div>
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-semibold text-slate-900">Daftar Pengguna</h2>
            <p class="text-sm text-slate-600">Hanya siswa dan guru terdaftar yang dapat mengakses halaman terkait.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Nama</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Email</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Role</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @foreach ($users as $user)
                        <tr>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $user->roles->pluck('name')->map(fn($item) => ucfirst($item))->join(', ') }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">
                                <button wire:click="edit({{ $user->id }})" class="text-sky-600">Edit</button>
                                <button wire:click="delete({{ $user->id }})" class="ml-3 text-red-600">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
