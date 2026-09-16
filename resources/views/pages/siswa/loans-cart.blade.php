@extends('layouts.siswa')

@section('title', 'Keranjang Peminjaman')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Keranjang Peminjaman</h1>
            <p class="text-sm text-gray-600 dark:text-gray-300">Pilih beberapa barang lalu review sebelum submit.</p>
        </div>
        <a href="{{ route('student.catalog') }}" class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Tambah Barang</a>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.3fr_0.7fr]">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Barang di Keranjang</h2>

            @if(empty($cartItems))
                <div class="rounded-xl border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500 dark:border-slate-600 dark:text-slate-300">
                    Keranjang masih kosong. Pilih barang dari katalog terlebih dahulu.
                </div>
            @else
                <div class="space-y-3">
                    @foreach($cartItems as $entry)
                        @php $item = $entry['item']; @endphp
                        <div class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 p-3 dark:border-slate-700">
                            <div>
                                <div class="font-semibold text-gray-900 dark:text-white">{{ $item->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Jumlah: {{ $entry['quantity'] }} unit</div>
                            </div>
                            <form method="POST" action="{{ route('student.loans.cart.remove', $item->id) }}">
                                @csrf
                                <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 dark:border-red-900 dark:text-red-300">Hapus</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Ringkasan</h2>
            <form method="POST" action="{{ route('student.loans.cart.submit') }}">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Tanggal pinjam</label>
                        <input type="date" name="borrow_date" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white" value="{{ now()->toDateString() }}">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Tanggal kembali</label>
                        <input type="date" name="return_date" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white" value="{{ now()->addDays(7)->toDateString() }}">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Jam kembali</label>
                        <input type="time" name="return_time" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white" value="14:00">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Guru pembimbing</label>
                        <select name="teacher_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                            <option value="">Pilih guru</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Tujuan peminjaman</label>
                        <textarea name="purpose" rows="3" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white" placeholder="Contoh: Praktikum lab dan kebutuhan pembelajaran..."></textarea>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Catatan</label>
                        <textarea name="notes" rows="2" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white" placeholder="Opsional"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <a href="{{ route('student.loans') }}" class="flex-1 rounded-lg border border-slate-300 px-4 py-2 text-center text-sm font-medium text-slate-700 dark:border-slate-600 dark:text-slate-200">Kembali</a>
                    <button type="submit" class="flex-1 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700" {{ empty($cartItems) ? 'disabled' : '' }}>Ajukan Permohonan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
