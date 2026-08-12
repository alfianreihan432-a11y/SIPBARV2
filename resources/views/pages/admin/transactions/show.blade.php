@extends('layouts.admin')

@section('title', 'Detail Transaksi #' . $transaction->id)
@section('page-heading', 'Detail Transaksi #' . $transaction->id)

@section('content')
<div class="space-y-6">
    {{-- Back Button --}}
    <div>
        <a href="{{ route('transactions.index') }}" 
           class="inline-flex items-center gap-2 text-teal-600 hover:text-teal-800 dark:text-teal-400 dark:hover:text-teal-300 font-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali ke Daftar Transaksi
        </a>
    </div>

    {{-- Main Information --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Transaksi #{{ $transaction->id }}</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Dibuat {{ $transaction->created_at->format('d M Y H:i') }}</p>
            </div>
            <span class="px-3 py-1.5 rounded-lg text-sm font-bold
                @if($transaction->status === 'pending') bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300
                @elseif($transaction->status === 'approved') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300
                @elseif($transaction->status === 'borrowed') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                @elseif($transaction->status === 'returned') bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300
                @elseif($transaction->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                @endif">
                {{ ucfirst($transaction->status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Student Info --}}
            <div class="space-y-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-slate-700 pb-2">Informasi Siswa</h3>
                <div>
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Nama</label>
                    <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $transaction->user->name }}</p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Kelas</label>
                    <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $transaction->user->kelas ?? '-' }} {{ $transaction->user->jurusan ?? '' }}</p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">No. WhatsApp</label>
                    <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $transaction->user->phone ?? '-' }}</p>
                </div>
            </div>

            {{-- Item Info --}}
            <div class="space-y-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-slate-700 pb-2">Informasi Barang</h3>
                <div>
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Nama Barang</label>
                    <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $transaction->item->name }}</p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Jumlah</label>
                    <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $transaction->quantity }} unit</p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Guru yang Menyetujui</label>
                    <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $transaction->teacher->name ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- Dates --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 pt-6 border-t border-gray-200 dark:border-slate-700">
            <div>
                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Tanggal Pinjam</label>
                <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($transaction->borrow_date)->format('d M Y') }}</p>
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Tanggal Kembali</label>
                <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($transaction->return_date)->format('d M Y') }}</p>
            </div>
        </div>

        @if($transaction->purpose)
        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-slate-700">
            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Keperluan</label>
            <p class="mt-2 text-sm text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-slate-700/50 p-4 rounded-lg">{{ $transaction->purpose }}</p>
        </div>
        @endif
    </div>

    {{-- Timeline --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Timeline Transaksi</h3>
        
        <div class="space-y-4">
            {{-- Created --}}
            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-3 h-3 rounded-full bg-gray-400 dark:bg-gray-600"></div>
                    <div class="w-0.5 flex-1 bg-gray-200 dark:bg-slate-700 mt-1"></div>
                </div>
                <div class="flex-1 pb-4">
                    <p class="font-semibold text-gray-900 dark:text-white">Permintaan Dibuat</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $transaction->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>

            {{-- Approved/Rejected --}}
            @if($transaction->approved_at || $transaction->status === 'rejected')
            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-3 h-3 rounded-full {{ $transaction->status === 'rejected' ? 'bg-red-500' : 'bg-emerald-500' }}"></div>
                    @if($transaction->checkout_at || $transaction->returned_at)
                    <div class="w-0.5 flex-1 bg-gray-200 dark:bg-slate-700 mt-1"></div>
                    @endif
                </div>
                <div class="flex-1 pb-4">
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $transaction->status === 'rejected' ? 'Ditolak' : 'Disetujui' }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ ($transaction->approved_at ?? $transaction->updated_at)->format('d M Y H:i') }}</p>
                    @if($transaction->rejection_reason)
                    <p class="text-sm text-red-600 dark:text-red-400 mt-2 bg-red-50 dark:bg-red-900/20 p-2 rounded">
                        <strong>Alasan:</strong> {{ $transaction->rejection_reason }}
                    </p>
                    @endif
                </div>
            </div>
            @endif

            {{-- Checkout --}}
            @if($transaction->checkout_at)
            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                    @if($transaction->returned_at)
                    <div class="w-0.5 flex-1 bg-gray-200 dark:bg-slate-700 mt-1"></div>
                    @endif
                </div>
                <div class="flex-1 pb-4">
                    <p class="font-semibold text-gray-900 dark:text-white">Checkout (Barang Diambil)</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $transaction->checkout_at->format('d M Y H:i') }}</p>
                    @if($transaction->checkoutBy)
                    <p class="text-sm text-gray-600 dark:text-gray-400">Oleh: {{ $transaction->checkoutBy->name }}</p>
                    @endif
                </div>
            </div>
            @endif

            {{-- Checkin --}}
            @if($transaction->returned_at)
            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-3 h-3 rounded-full bg-gray-500"></div>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900 dark:text-white">Checkin (Barang Dikembalikan)</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $transaction->returned_at->format('d M Y H:i') }}</p>
                    @if($transaction->checkinBy)
                    <p class="text-sm text-gray-600 dark:text-gray-400">Oleh: {{ $transaction->checkinBy->name }}</p>
                    @endif
                    @if($transaction->return_condition)
                    <p class="text-sm mt-2">
                        <span class="font-semibold text-gray-700 dark:text-gray-300">Kondisi:</span>
                        <span class="px-2 py-0.5 rounded text-xs font-bold ml-1
                            @if($transaction->return_condition === 'good') bg-emerald-100 text-emerald-800
                            @elseif($transaction->return_condition === 'damaged') bg-amber-100 text-amber-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($transaction->return_condition) }}
                        </span>
                    </p>
                    @endif
                    @if($transaction->return_notes)
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 bg-gray-50 dark:bg-slate-700/50 p-2 rounded">
                        <strong>Catatan:</strong> {{ $transaction->return_notes }}
                    </p>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- WhatsApp Notification Logs --}}
    @if($transaction->whatsappLogs->isNotEmpty())
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Log Notifikasi WhatsApp</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-slate-700 border-b border-gray-200 dark:border-slate-600">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Tipe</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Penerima</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Status</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                    @foreach($transaction->whatsappLogs as $log)
                    <tr>
                        <td class="px-4 py-2 text-gray-900 dark:text-white">{{ $log->notification_type }}</td>
                        <td class="px-4 py-2 text-gray-900 dark:text-white">{{ $log->recipient_phone }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-0.5 rounded text-xs font-bold
                                @if($log->status === 'success') bg-emerald-100 text-emerald-800
                                @elseif($log->status === 'pending') bg-amber-100 text-amber-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($log->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-gray-600 dark:text-gray-400">{{ $log->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
