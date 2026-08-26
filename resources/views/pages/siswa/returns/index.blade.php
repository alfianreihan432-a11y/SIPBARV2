@extends('layouts.siswa')

@section('title', 'Barang Saya & Pengembalian – SIPBAR')
@section('page-heading', 'Pengembalian Barang')

@section('content')
<style>
    .return-header-card {
        background: #1d4ed8;
        border-radius: 14px;
        padding: 22px 24px;
        color: #fff;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 14px rgba(29, 78, 216, 0.15);
    }
    .return-header-title { font-size: 20px; font-weight: 800; margin-bottom: 4px; }
    .return-header-sub { font-size: 13px; color: rgba(255, 255, 255, 0.8); }

    .return-stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-bottom: 22px;
    }
    @media (max-width: 768px) {
        .return-stats-grid { grid-template-columns: 1fr; }
        .return-header-card { flex-direction: column; align-items: flex-start; gap: 14px; }
    }

    .stat-box {
        background: var(--card);
        border: 1px solid var(--border2);
        border-radius: 12px;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .stat-box-label { font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em; }
    .stat-box-num { font-size: 24px; font-weight: 800; color: var(--text); margin-top: 4px; }
    .stat-box-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .nav-tabs-bar {
        display: flex;
        gap: 8px;
        margin-bottom: 18px;
        border-bottom: 1px solid var(--border2);
        padding-bottom: 10px;
    }
    .nav-tab-btn {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        color: var(--muted);
        background: transparent;
        transition: all .15s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .nav-tab-btn.active {
        background: #1d4ed8;
        color: #fff;
    }
    .nav-tab-btn:hover:not(.active) {
        background: var(--bg3);
        color: var(--text);
    }

    .loans-panel {
        background: var(--card);
        border: 1px solid var(--border2);
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    }
    .loans-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
    }
    .loans-panel-title { font-size: 16px; font-weight: 700; color: var(--text); }
    .loans-panel-sub { font-size: 12px; color: var(--muted); margin-top: 2px; }

    .loan-card-item {
        background: var(--bg3);
        border: 1px solid var(--border2);
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 14px;
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 16px;
        align-items: center;
        transition: border-color .2s;
    }
    .loan-card-item:last-child { margin-bottom: 0; }
    .loan-card-item:hover { border-color: #1d4ed8; }

    @media (max-width: 640px) {
        .loan-card-item { grid-template-columns: 1fr; }
    }

    .item-title-row { display: flex; align-items: center; gap: 8px; margin-bottom: 4px; }
    .item-name { font-size: 15px; font-weight: 800; color: var(--text); }
    .item-code-tag {
        font-size: 10px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 4px;
        background: rgba(148, 163, 184, 0.15);
        color: var(--muted);
    }

    .item-meta-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        font-size: 12px;
        color: var(--muted);
        margin-top: 8px;
    }
    .item-meta-item { display: inline-flex; align-items: center; gap: 5px; }

    /* CTA Amber Button */
    .btn-return-cta {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f59e0b;
        color: #0f172a;
        font-size: 13px;
        font-weight: 800;
        padding: 10px 18px;
        border-radius: 9px;
        text-decoration: none;
        box-shadow: 0 2px 6px rgba(245, 158, 11, 0.25);
        transition: all .2s;
        white-space: nowrap;
        border: none;
        cursor: pointer;
    }
    .btn-return-cta:hover {
        background: #d97706;
        color: #ffffff;
        transform: translateY(-1px);
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
    }
    .badge-waiting {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    .badge-borrowed {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }
    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .empty-state {
        text-align: center;
        padding: 48px 20px;
        color: var(--muted);
    }
    .empty-icon {
        width: 52px;
        height: 52px;
        margin: 0 auto 12px;
        color: var(--subtle);
    }
    .empty-title { font-size: 15px; font-weight: 700; color: var(--text); margin-bottom: 4px; }
    .empty-sub { font-size: 13px; color: var(--muted); }
</style>

<div>
    {{-- Top Alert Messages --}}
    @if(session('success'))
        <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Hero Header --}}
    <div class="return-header-card">
        <div>
            <div class="return-header-title">Pengembalian Barang Siswa</div>
            <div class="return-header-sub">Kembalikan barang yang Anda pinjam dengan mengisi form verifikasi kondisi barang.</div>
        </div>
        <a href="{{ route('student.returns.history') }}" style="background: rgba(255,255,255,0.2); color:#fff; text-decoration:none; font-weight:700; font-size:12px; padding:8px 16px; border-radius:8px; display:inline-flex; align-items:center; gap:6px;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Lihat Riwayat Pengembalian
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="return-stats-grid">
        <div class="stat-box">
            <div>
                <div class="stat-box-label">Sedang Dipinjam</div>
                <div class="stat-box-num">{{ $totalBorrowed }}</div>
            </div>
            <div class="stat-box-icon" style="background: rgba(29, 78, 216, 0.1); color: #1d4ed8;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>

        <div class="stat-box">
            <div>
                <div class="stat-box-label">Menunggu Verifikasi</div>
                <div class="stat-box-num">{{ $pendingReturns }}</div>
            </div>
            <div class="stat-box-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <div class="stat-box">
            <div>
                <div class="stat-box-label">Selesai Dikembalikan</div>
                <div class="stat-box-num">{{ $completedReturns }}</div>
            </div>
            <div class="stat-box-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="nav-tabs-bar">
        <a href="{{ route('student.returns.index') }}" class="nav-tab-btn active">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            Barang Saya (Aktif)
        </a>
        <a href="{{ route('student.returns.history') }}" class="nav-tab-btn">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Riwayat Pengembalian
        </a>
    </div>

    {{-- Main Active Loans List --}}
    <div class="loans-panel">
        <div class="loans-panel-header">
            <div>
                <div class="loans-panel-title">Daftar Barang yang Sedang Anda Pinjam</div>
                <div class="loans-panel-sub">Pilih barang yang ingin dikembalikan, lalu klik tombol kuning "Ajukan Pengembalian".</div>
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
                <div class="loan-card-item">
                    <div>
                        <div class="item-title-row">
                            <span class="item-name">{{ $loan->item?->name ?? 'Barang #' . $loan->item_id }}</span>
                            <span class="item-code-tag">{{ $loan->item?->code ?? 'KODE-BARANG' }}</span>
                            @if($loan->item?->category)
                                <span class="item-code-tag" style="background: rgba(37,99,235,0.1); color:#2563eb;">{{ $loan->item->category->name }}</span>
                            @endif
                        </div>

                        <div class="item-meta-grid">
                            <div class="item-meta-item">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Pinjam: {{ $loan->borrow_date ? $loan->borrow_date->format('d M Y') : '-' }}
                            </div>
                            <div class="item-meta-item" style="{{ $isOverdue ? 'color:#dc2626;font-weight:700;' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Jatuh Tempo: {{ $loan->return_date ? $loan->return_date->format('d M Y') : '-' }}
                                @if($isOverdue) (Terlambat) @endif
                            </div>
                            <div class="item-meta-item">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Guru Pembimbing: {{ $loan->teacher?->name ?? '-' }}
                            </div>
                            <div class="item-meta-item">
                                <strong>Jumlah:</strong> {{ $loan->quantity }} Unit
                            </div>
                        </div>

                        @if($isPendingVerification)
                            <div style="margin-top: 10px; display: inline-flex; align-items: center; gap: 6px;">
                                <span class="badge-status badge-waiting">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Pengajuan Sedang Diverifikasi Admin
                                </span>
                                <span style="font-size: 11px; color: var(--muted);">Diajukan pada {{ $latestReturn->created_at->format('d M Y H:i') }}</span>
                            </div>
                        @elseif($isRejected)
                            <div style="margin-top: 10px;">
                                <span class="badge-status badge-danger">Pengajuan Sebelumnya Ditolak</span>
                                <span style="font-size: 12px; color: #dc2626; margin-left: 6px;">Alasan: {{ $latestReturn->alasan_ditolak }}</span>
                            </div>
                        @endif
                    </div>

                    <div>
                        @if($isPendingVerification)
                            <button class="btn-return-cta" style="background:#cbd5e1;color:#64748b;cursor:not-allowed;box-shadow:none;" disabled title="Menunggu verifikasi admin">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Menunggu Verifikasi
                            </button>
                        @else
                            <a href="{{ route('student.returns.create', $loan->id) }}" class="btn-return-cta">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                </svg>
                                {{ $isRejected ? 'Ajukan Ulang Pengembalian' : 'Ajukan Pengembalian' }}
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" class="empty-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="empty-title">Tidak ada barang yang sedang dipinjam</div>
                <div class="empty-sub">Saat ini Anda tidak memiliki barang yang perlu dikembalikan.</div>
                <a href="{{ route('student.catalog') }}" style="display:inline-block; margin-top:14px; color:#1d4ed8; font-size:13px; font-weight:700; text-decoration:none;">
                    Jelajahi Katalog Barang &rarr;
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
