@extends('layouts.guru')

@section('title', 'Laporan Peminjaman')
@section('page-heading', 'Laporan Peminjaman')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laporan & Statistik</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Ringkasan peminjaman siswa bimbingan Anda</p>
            </div>
            <div class="w-12 h-12 bg-teal-100 dark:bg-teal-900/30 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>
    </div>

    @php
        $teacherId = auth()->id();
        
        // Summary statistics
        $totalRequests = \App\Models\BorrowingRequest::where('teacher_id', $teacherId)->count();
        $pendingRequests = \App\Models\BorrowingRequest::where('teacher_id', $teacherId)->where('status', 'pending')->count();
        $approvedRequests = \App\Models\BorrowingRequest::where('teacher_id', $teacherId)->whereIn('status', ['approved', 'borrowed'])->count();
        $completedRequests = \App\Models\BorrowingRequest::where('teacher_id', $teacherId)->where('status', 'returned')->count();
        $rejectedRequests = \App\Models\BorrowingRequest::where('teacher_id', $teacherId)->where('status', 'rejected')->count();
        
        // This month
        $thisMonthRequests = \App\Models\BorrowingRequest::where('teacher_id', $teacherId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        // Active students
        $activeStudents = \App\Models\BorrowingRequest::where('teacher_id', $teacherId)
            ->distinct('user_id')
            ->count('user_id');
    @endphp

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-blue-600 dark:text-blue-400">Total</span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalRequests }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Total Pengajuan</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-amber-600 dark:text-amber-400">Pending</span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pendingRequests }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Menunggu Persetujuan</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">Done</span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $completedRequests }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Selesai</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-purple-600 dark:text-purple-400">Students</span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $activeStudents }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Siswa Aktif</p>
        </div>
    </div>

    {{-- Status Breakdown --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Status Distribution --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Distribusi Status</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-800">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="font-semibold text-gray-900 dark:text-white">Pending</span>
                    </div>
                    <span class="text-xl font-bold text-amber-600 dark:text-amber-400">{{ $pendingRequests }}</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <span class="font-semibold text-gray-900 dark:text-white">Aktif</span>
                    </div>
                    <span class="text-xl font-bold text-blue-600 dark:text-blue-400">{{ $approvedRequests }}</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg border border-emerald-200 dark:border-emerald-800">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="font-semibold text-gray-900 dark:text-white">Selesai</span>
                    </div>
                    <span class="text-xl font-bold text-emerald-600 dark:text-emerald-400">{{ $completedRequests }}</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <span class="font-semibold text-gray-900 dark:text-white">Ditolak</span>
                    </div>
                    <span class="text-xl font-bold text-red-600 dark:text-red-400">{{ $rejectedRequests }}</span>
                </div>
            </div>
        </div>

        {{-- Monthly Statistics --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Statistik Bulan Ini</h2>
            <div class="space-y-4">
                <div class="text-center p-6 bg-teal-50 dark:bg-teal-900/20 rounded-xl border border-teal-200 dark:border-teal-800">
                    <p class="text-4xl font-bold text-teal-600 dark:text-teal-400 mb-2">{{ $thisMonthRequests }}</p>
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Pengajuan Bulan {{ now()->format('F') }}</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="text-center p-4 bg-gray-50 dark:bg-slate-700 rounded-lg">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ round($totalRequests > 0 ? ($completedRequests / $totalRequests) * 100 : 0) }}%</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Completion Rate</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 dark:bg-slate-700 rounded-lg">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ round($totalRequests > 0 ? ($rejectedRequests / $totalRequests) * 100 : 0) }}%</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Rejection Rate</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    @php
        $recentActivity = \App\Models\BorrowingRequest::with(['user', 'item'])
            ->where('teacher_id', $teacherId)
            ->latest()
            ->take(10)
            ->get();
    @endphp

    @if($recentActivity->isNotEmpty())
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
        <div class="p-5 border-b border-gray-200 dark:border-slate-700">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Aktivitas Terakhir</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">10 transaksi terakhir dari siswa bimbingan Anda</p>
        </div>
        <div class="divide-y divide-gray-200 dark:divide-slate-700">
            @foreach($recentActivity as $activity)
            <div class="p-4 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 flex-1">
                        <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $activity->user->name }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $activity->item->name }} • {{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-lg text-xs font-bold whitespace-nowrap
                        @if($activity->status === 'pending') bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300
                        @elseif($activity->status === 'approved') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300
                        @elseif($activity->status === 'borrowed') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                        @elseif($activity->status === 'returned') bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300
                        @elseif($activity->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                        @endif">
                        {{ $activity->status_label }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
