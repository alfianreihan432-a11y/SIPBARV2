@extends('layouts.guru')

@section('title', 'Peminjaman Aktif')
@section('page-heading', 'Peminjaman Aktif')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Peminjaman Aktif</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Daftar peminjaman barang yang sedang berlangsung</p>
            </div>
            
            {{-- Quick Stats --}}
            <div class="flex gap-3">
                @php
                    $activeLoans = \App\Models\BorrowingRequest::where('teacher_id', auth()->id())
                        ->whereIn('status', ['approved', 'borrowed'])
                        ->count();
                    $overdue = \App\Models\BorrowingRequest::where('teacher_id', auth()->id())
                        ->whereIn('status', ['approved', 'borrowed'])
                        ->where('return_date', '<', now())
                        ->count();
                @endphp
                <div class="text-center px-4 py-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                    <p class="text-xs text-blue-600 dark:text-blue-400 font-semibold">Aktif</p>
                    <p class="text-xl font-bold text-blue-900 dark:text-blue-300">{{ $activeLoans }}</p>
                </div>
                <div class="text-center px-4 py-2 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                    <p class="text-xs text-red-600 dark:text-red-400 font-semibold">Terlambat</p>
                    <p class="text-xl font-bold text-red-900 dark:text-red-300">{{ $overdue }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Active Loans List --}}
    @php
        $activeLoansList = \App\Models\BorrowingRequest::with(['user', 'item'])
            ->where('teacher_id', auth()->id())
            ->whereIn('status', ['approved', 'borrowed'])
            ->latest()
            ->get();
    @endphp

    @if($activeLoansList->isNotEmpty())
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-slate-700 border-b border-gray-200 dark:border-slate-600">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Tanggal Pinjam</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Tanggal Kembali</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                    @foreach($activeLoansList as $loan)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-teal-600 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-xs font-bold text-white">{{ strtoupper(substr($loan->user->name ?? 'N/A', 0, 2)) }}</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $loan->user->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $loan->user->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $loan->item->name ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $loan->quantity }} unit</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($loan->borrow_date)->format('d M Y') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm {{ $loan->return_date < now() ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-gray-600 dark:text-gray-400' }}">
                                {{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y') }}
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-lg text-xs font-bold 
                                @if($loan->status === 'approved') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300
                                @elseif($loan->status === 'borrowed') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                @endif">
                                {{ $loan->status === 'approved' ? 'Disetujui' : 'Dipinjam' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('teacher.returns') }}" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                Proses Kembali
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-12 text-center">
        <svg class="w-20 h-20 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
        </svg>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Tidak Ada Peminjaman Aktif</h3>
        <p class="text-gray-600 dark:text-gray-400">Belum ada peminjaman barang yang sedang berlangsung</p>
    </div>
    @endif
</div>
@endsection
