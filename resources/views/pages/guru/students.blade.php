@extends('layouts.guru')

@section('title', 'Siswa Bimbingan')
@section('page-heading', 'Siswa Bimbingan')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Siswa Bimbingan</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Daftar siswa yang memilih Anda sebagai guru pembimbing</p>
            </div>
            
            {{-- Quick Stats --}}
            <div class="flex gap-3">
                @php
                    $totalStudents = \App\Models\BorrowingRequest::where('teacher_id', auth()->id())
                        ->distinct('user_id')
                        ->count('user_id');
                    $activeToday = \App\Models\BorrowingRequest::where('teacher_id', auth()->id())
                        ->whereDate('created_at', today())
                        ->distinct('user_id')
                        ->count('user_id');
                @endphp
                <div class="text-center px-4 py-2 bg-teal-50 dark:bg-teal-900/20 rounded-lg border border-teal-200 dark:border-teal-800">
                    <p class="text-xs text-teal-600 dark:text-teal-400 font-semibold">Total</p>
                    <p class="text-xl font-bold text-teal-900 dark:text-teal-300">{{ $totalStudents }}</p>
                </div>
                <div class="text-center px-4 py-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                    <p class="text-xs text-blue-600 dark:text-blue-400 font-semibold">Aktif Hari Ini</p>
                    <p class="text-xl font-bold text-blue-900 dark:text-blue-300">{{ $activeToday }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Search Filter --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-5">
        <form method="GET" action="{{ route('teacher.students') }}" class="flex gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari nama siswa..." 
                    class="w-full px-4 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>
            <button type="submit" 
                class="px-6 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold rounded-lg transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Cari
            </button>
            @if(request('search'))
            <a href="{{ route('teacher.students') }}" 
                class="px-6 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-lg transition-colors">
                Reset
            </a>
            @endif
        </form>
    </div>

    {{-- Students List --}}
    @php
        $studentsQuery = \App\Models\BorrowingRequest::with(['user'])
            ->where('teacher_id', auth()->id())
            ->select('user_id', \DB::raw('COUNT(*) as total_requests'), \DB::raw('MAX(created_at) as last_activity'))
            ->groupBy('user_id');
        
        // Apply search filter
        if (request('search')) {
            $studentsQuery->whereHas('user', function($q) {
                $q->where('name', 'like', '%' . request('search') . '%');
            });
        }
        
        $students = $studentsQuery->paginate(12);
    @endphp

    @if($students->isNotEmpty())
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($students as $studentData)
            @php
                $student = $studentData->user;
                $pending = \App\Models\BorrowingRequest::where('teacher_id', auth()->id())
                    ->where('user_id', $student->id)
                    ->where('status', 'pending')
                    ->count();
                $active = \App\Models\BorrowingRequest::where('teacher_id', auth()->id())
                    ->where('user_id', $student->id)
                    ->whereIn('status', ['approved', 'borrowed'])
                    ->count();
                $completed = \App\Models\BorrowingRequest::where('teacher_id', auth()->id())
                    ->where('user_id', $student->id)
                    ->where('status', 'returned')
                    ->count();
            @endphp
            
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden hover:shadow-lg transition-shadow">
                {{-- Student Header --}}
                <div class="p-5 bg-gradient-to-r from-teal-50 to-blue-50 dark:from-teal-900/20 dark:to-blue-900/20 border-b border-gray-200 dark:border-slate-700">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-teal-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-lg font-bold text-white">{{ strtoupper(substr($student->name, 0, 2)) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-gray-900 dark:text-white truncate">{{ $student->name }}</h3>
                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ $student->email }}</p>
                        </div>
                    </div>
                </div>

                {{-- Statistics --}}
                <div class="p-4">
                    <div class="grid grid-cols-3 gap-2 mb-4">
                        <div class="text-center p-2 bg-amber-50 dark:bg-amber-900/20 rounded-lg">
                            <p class="text-lg font-bold text-amber-600 dark:text-amber-400">{{ $pending }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Pending</p>
                        </div>
                        <div class="text-center p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <p class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $active }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Aktif</p>
                        </div>
                        <div class="text-center p-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                            <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">{{ $completed }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Selesai</p>
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span><strong>Total:</strong> {{ $studentData->total_requests }} pengajuan</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span><strong>Terakhir:</strong> {{ \Carbon\Carbon::parse($studentData->last_activity)->diffForHumans() }}</span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-slate-700">
                        <a href="{{ route('teacher.requests') }}?student={{ $student->id }}" 
                            class="block text-center py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold rounded-lg transition-colors">
                            Lihat Pengajuan
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-4">
        {{ $students->appends(request()->query())->links() }}
    </div>
    @else
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-12 text-center">
        <svg class="w-20 h-20 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
            @if(request('search'))
                Siswa Tidak Ditemukan
            @else
                Belum Ada Siswa Bimbingan
            @endif
        </h3>
        <p class="text-gray-600 dark:text-gray-400 mb-4">
            @if(request('search'))
                Coba ubah kata kunci pencarian Anda
            @else
                Siswa akan muncul setelah mengajukan peminjaman dengan Anda sebagai pembimbing
            @endif
        </p>
        @if(request('search'))
            <a href="{{ route('teacher.students') }}" 
                class="inline-flex items-center gap-2 px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-lg font-semibold transition-colors">
                Lihat Semua Siswa
            </a>
        @endif
    </div>
    @endif
</div>
@endsection
