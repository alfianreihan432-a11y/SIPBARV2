@extends('layouts.siswa')

@section('title', 'Keranjang Peminjaman')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Keranjang Peminjaman</h1>
            <p class="text-base text-gray-600 dark:text-gray-300 mt-1">Pilih beberapa barang lalu review sebelum submit permohonan peminjaman.</p>
        </div>
        <a href="{{ route('student.catalog') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700 transition-colors shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Barang
        </a>
    </div>

    <div class="grid gap-8 lg:grid-cols-[1.4fr_1fr]">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Barang di Keranjang</h2>
                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">{{ count($cartItems) }} Barang</span>
            </div>

            @if(empty($cartItems))
                <div class="rounded-xl border-2 border-dashed border-slate-300 p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-slate-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h18M3 3v18M3 3l2.5 2.5M21 3v18M21 3l-2.5 2.5M9 9h6M9 15h6"/>
                    </svg>
                    <p class="text-base text-slate-500 dark:text-slate-400">Keranjang masih kosong.</p>
                    <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Pilih barang dari katalog terlebih dahulu.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($cartItems as $entry)
                        @php $item = $entry['item']; @endphp
                        <div class="flex items-start justify-between gap-4 rounded-xl border border-slate-200 p-4 hover:border-emerald-300 transition-colors dark:border-slate-700 dark:hover:border-emerald-600">
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900 dark:text-white text-base">{{ $item->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Jumlah: {{ $entry['quantity'] }} unit</div>
                                <div class="text-xs text-slate-400 dark:text-slate-500 mt-1">Kode: {{ $item->code ?? '-' }}</div>
                            </div>
                            <form method="POST" action="{{ route('student.loans.cart.remove', $item->id) }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-1 rounded-lg border border-red-200 px-3 py-2 text-xs font-medium text-red-600 hover:bg-red-50 dark:border-red-900 dark:text-red-300 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 2H2.862a2 2 0 01-1.995-1.858L5 7m5 4v6m6-4v6m6-6h6M2 5h12a2 2 0 002 2v10a2 2 0 002-2H2a2 2 0 01-2-2V7z"/>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Ringkasan Peminjaman</h2>
            <form method="POST" action="{{ route('student.loans.cart.submit') }}">
                @csrf

                <div class="space-y-5">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-200">Tanggal Pinjam <span class="text-red-500">*</span></label>
                        <input type="date" name="borrow_date" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" value="{{ now()->toDateString() }}">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-200">Tanggal Kembali <span class="text-red-500">*</span></label>
                        <input type="date" name="return_date" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" value="{{ now()->addDays(7)->toDateString() }}">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-200">Jam Kembali <span class="text-red-500">*</span></label>
                        <input type="time" name="return_time" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" value="14:00">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-200">Guru Pembimbing <span class="text-red-500">*</span></label>
                        <select name="teacher_id" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">Pilih guru pembimbing...</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-200">Tujuan Peminjaman <span class="text-red-500">*</span></label>
                        <textarea name="purpose" rows="3" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 resize-none" placeholder="Contoh: Praktikum lab dan kebutuhan pembelajaran..."></textarea>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-200">Catatan <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <textarea name="notes" rows="2" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 resize-none" placeholder="Tambahkan catatan tambahan jika diperlukan..."></textarea>
                    </div>
                </div>

                <div class="mt-8 flex gap-4">
                    <a href="{{ route('student.loans') }}" class="flex-1 rounded-xl border border-slate-300 px-5 py-3 text-center text-sm font-semibold text-slate-700 dark:border-slate-600 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        Kembali
                    </a>
                    <button type="submit" class="flex-1 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed" {{ empty($cartItems) ? 'disabled' : '' }}>
                        Ajukan Permohonan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
