@extends('layouts.guru')

@section('title', 'Pengembalian')
@section('page-heading', 'Pengembalian')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pengembalian Barang</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Proses pengembalian barang yang sudah selesai dipinjam</p>
            </div>
            
            {{-- Quick Stats --}}
            <div class="flex gap-3">
                @php
                    $returnedToday = \App\Models\BorrowingRequest::where('teacher_id', auth()->id())
                        ->where('status', 'returned')
                        ->whereDate('updated_at', today())
                        ->count();
                    $totalReturned = \App\Models\BorrowingRequest::where('teacher_id', auth()->id())
                        ->where('status', 'returned')
                        ->count();
                @endphp
                <div class="text-center px-4 py-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg border border-emerald-200 dark:border-emerald-800">
                    <p class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold">Hari Ini</p>
                    <p class="text-xl font-bold text-emerald-900 dark:text-emerald-300">{{ $returnedToday }}</p>
                </div>
                <div class="text-center px-4 py-2 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-200 dark:border-purple-800">
                    <p class="text-xs text-purple-600 dark:text-purple-400 font-semibold">Total</p>
                    <p class="text-xl font-bold text-purple-900 dark:text-purple-300">{{ $totalReturned }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Ready for Return List --}}
    @php
        $readyForReturn = \App\Models\BorrowingRequest::with(['user', 'item'])
            ->where('teacher_id', auth()->id())
            ->whereIn('status', ['borrowed'])
            ->latest()
            ->get();
    @endphp

    @if($readyForReturn->isNotEmpty())
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-slate-700 border-b border-gray-200 dark:border-slate-600">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Tanggal Pinjam</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Jatuh Tempo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                    @foreach($readyForReturn as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-teal-600 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-xs font-bold text-white">{{ strtoupper(substr($item->user->name ?? 'N/A', 0, 2)) }}</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $item->user->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $item->user->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $item->item->name ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $item->quantity }} unit</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($item->borrow_date)->format('d M Y') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm {{ $item->return_date < now() ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-gray-600 dark:text-gray-400' }}">
                                {{ \Carbon\Carbon::parse($item->return_date)->format('d M Y') }}
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-lg text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                Dipinjam
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('teacher.returns.process', $item->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                    Proses Kembali
                                </button>
                            </form>
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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Tidak Ada Barang Siap Dikembalikan</h3>
        <p class="text-gray-600 dark:text-gray-400">Belum ada barang yang siap untuk diproses pengembaliannya</p>
    </div>
    @endif
</div>
@endsection
