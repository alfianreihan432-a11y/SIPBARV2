@extends('layouts.siswa')

@section('title', 'Pengembalian Barang – SIPBAR')

@section('content')
{{-- Top Alert Messages --}}
@if(session('success'))
    <div style="background: var(--s-returned-bg); border: 1px solid var(--s-returned-bdr); color: var(--s-returned); padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background: var(--s-rejected-bg); border: 1px solid var(--s-rejected-bdr); color: var(--s-rejected); padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        {{ session('error') }}
    </div>
@endif

{{-- Page Header --}}
<div class="page-header">
    <div class="page-header-left">
        <div class="page-title">
            Pengembalian Barang
            @if($totalBorrowed > 0)
            <span class="page-title-count">{{ $totalBorrowed }} aktif</span>
            @endif
        </div>
        <div class="page-subtitle">Kembalikan barang yang Anda pinjam dengan mengisi form verifikasi kondisi barang</div>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap">
        <a href="{{ route('student.returns.history') }}" class="s-btn s-btn--secondary">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Riwayat Pengembalian
        </a>
    </div>
</div>

{{-- Stats Cards --}}
<div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:14px;margin-bottom:20px" class="returns-stat-grid">
    <div class="s-stat">
        <div class="s-stat-icon" style="background: var(--s-borrowed-bg); color: var(--s-borrowed)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        <div class="s-stat-body">
            <div class="s-stat-num">{{ $totalBorrowed }}</div>
            <div class="s-stat-label">Sedang Dipinjam</div>
        </div>
    </div>

    <div class="s-stat">
        <div class="s-stat-icon" style="background: var(--s-pending-bg); color: var(--s-pending)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="s-stat-body">
            <div class="s-stat-num">{{ $pendingReturns }}</div>
            <div class="s-stat-label">Menunggu Verifikasi</div>
        </div>
    </div>

    <div class="s-stat">
        <div class="s-stat-icon" style="background: var(--s-returned-bg); color: var(--s-returned)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="s-stat-body">
            <div class="s-stat-num">{{ $completedReturns }}</div>
            <div class="s-stat-label">Selesai Dikembalikan</div>
        </div>
    </div>
</div>
<style>
    @media (max-width: 768px) { .returns-stat-grid { grid-template-columns: 1fr !important; } }
</style>

{{-- Tabs Navigation --}}
<div style="display:flex;gap:8px;margin-bottom:18px;border-bottom:1px solid var(--border2);padding-bottom:10px">
    <a href="{{ route('student.returns.index') }}" class="s-btn s-btn--primary s-btn--sm">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
        </svg>
        Barang Saya (Aktif)
    </a>
    <a href="{{ route('student.returns.history') }}" class="s-btn s-btn--secondary s-btn--sm">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Riwayat Pengembalian
    </a>
</div>

{{-- Main Active Loans List --}}
<div class="s-card">
    <div class="s-card-header">
        <div>
            <div class="s-card-title">Daftar Barang yang Sedang Anda Pinjam</div>
            <div class="s-card-sub">Pilih barang yang ingin dikembalikan, lalu klik tombol "Ajukan Pengembalian"</div>
        </div>
    </div>

    @if($activeLoans->count() > 0)
        @foreach($activeLoans as $loan)
            @php
                $latestReturn = $loan->latestReturn;
                $isPendingVerification = $latestReturn && $latestReturn->status === 'menunggu';
                $isRejected = $latestReturn && $latestReturn->status === 'ditolak';
                $isOverdue = $loan->return_date && $loan->return_date->isPast();
            @endphp
            <div class="s-loan-row {{ $isOverdue ? 's-loan-row--rejected' : ($isPendingVerification ? 's-loan-row--pending' : 's-loan-row--borrowed') }}" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px">
                <div style="flex:1;min-width:240px">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;flex-wrap:wrap">
                        <span class="s-loan-name">{{ $loan->item?->name ?? 'Barang #' . $loan->item_id }}</span>
                        <span style="font-size:11px;color:var(--subtle);background:var(--bg3);border:1px solid var(--border2);padding:2px 8px;border-radius:5px">
                            {{ $loan->item?->code ?? 'KODE-BARANG' }}
                        </span>
                        @if($loan->item?->category)
                            <span style="font-size:11px;color:var(--primary);background:var(--primary-light);border:1px solid var(--primary-muted);padding:2px 8px;border-radius:5px">
                                {{ $loan->item->category->name }}
                            </span>
                        @endif
                    </div>

                    <div class="s-loan-meta">
                        <div class="s-loan-meta-item">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Pinjam: {{ $loan->borrow_date ? $loan->borrow_date->format('d M Y') : '-' }}
                        </div>
                        <div class="s-loan-meta-item" style="{{ $isOverdue ? 'color:var(--s-rejected);font-weight:700;' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Jatuh Tempo: {{ $loan->return_date ? $loan->return_date->format('d M Y') : '-' }}
                            @if($isOverdue) (Terlambat) @endif
                        </div>
                        <div class="s-loan-meta-item">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Guru: {{ $loan->teacher?->name ?? '-' }}
                        </div>
                        <div class="s-loan-meta-item">
                            <strong>Jumlah:</strong> {{ $loan->quantity }} Unit
                        </div>
                    </div>

                    @if($isPendingVerification)
                        <div style="margin-top: 10px; display: inline-flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                            <span class="s-badge s-badge--pending">
                                <span class="s-badge-dot" style="background:var(--s-pending)"></span>
                                Pengajuan Sedang Diverifikasi Admin
                            </span>
                            <span style="font-size: 11px; color: var(--muted);">Diajukan {{ $latestReturn->created_at->diffForHumans() }}</span>
                        </div>
                    @elseif($isRejected)
                        <div style="margin-top: 10px;">
                            <span class="s-badge s-badge--rejected">Pengajuan Sebelumnya Ditolak</span>
                            <span style="font-size: 12px; color: var(--s-rejected); margin-left: 6px;">Alasan: {{ $latestReturn->alasan_ditolak }}</span>
                        </div>
                    @endif
                </div>

                <div style="flex-shrink:0">
                    @if($isPendingVerification)
                        <button class="s-btn s-btn--secondary" disabled style="opacity:.6;cursor:not-allowed">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Menunggu Verifikasi
                        </button>
                    @else
                        <a href="{{ route('student.returns.create', $loan->id) }}" class="s-btn s-btn--primary">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                            </svg>
                            {{ $isRejected ? 'Ajukan Ulang Pengembalian' : 'Ajukan Pengembalian' }}
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <div class="s-empty">
            <div class="s-empty-icon-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:32px;height:32px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="s-empty-title">Tidak ada barang yang sedang dipinjam</div>
            <div class="s-empty-sub">Saat ini Anda tidak memiliki barang aktif yang perlu dikembalikan.</div>
            <a href="{{ route('student.catalog') }}" class="s-btn s-btn--primary">
                Jelajahi Katalog Barang &rarr;
            </a>
        </div>
    @endif
</div>
@endsection
