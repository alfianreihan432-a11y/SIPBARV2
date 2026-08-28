@extends('layouts.siswa')

@section('title', 'Pengumuman')
@section('page-heading', 'Pengumuman')

@section('content')
<style>
    .ann-header {
        background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
        border-radius: 16px; padding: 28px 32px; margin-bottom: 24px;
        box-shadow: 0 4px 20px rgba(29, 78, 216, 0.25);
        position: relative; overflow: hidden;
    }
    .ann-header::before {
        content: ''; position: absolute;
        top: -50px; right: -30px;
        width: 150px; height: 150px;
        background: rgba(255,255,255,0.08); border-radius: 50%;
    }
    .ann-header-content { display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 1; }
    .ann-title { font-size: 22px; font-weight: 800; color: #fff; margin-bottom: 6px; }
    .ann-sub { font-size: 14px; color: rgba(255,255,255,0.85); }
    .ann-icon {
        width: 48px; height: 48px; background: rgba(255,255,255,0.2);
        border-radius: 12px; display: flex; align-items: center; justify-content: center;
        backdrop-filter: blur(10px);
    }

    .ann-panel {
        background: var(--card); border: 1px solid var(--border2);
        border-radius: 16px; overflow: hidden; margin-bottom: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        transition: box-shadow .2s;
    }
    .ann-panel:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
    .ann-panel-header {
        padding: 22px 24px; border-bottom: 1px solid var(--border2);
        background: linear-gradient(to bottom, var(--card), var(--bg3));
    }
    .ann-panel-header-content { display: flex; align-items: center; gap: 14px; }
    .ann-panel-title { font-size: 18px; font-weight: 800; color: var(--text); }
    .ann-panel-sub { font-size: 13px; color: var(--muted); margin-top: 3px; }

    .ann-panel-body { padding: 20px 24px; }
    .ann-item {
        display: flex; align-items: center; justify-content: space-between;
        padding: 18px; background: var(--bg3); border: 1px solid var(--border2);
        border-radius: 12px; margin-bottom: 14px;
        transition: all .2s;
    }
    .ann-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        border-color: var(--primary);
    }
    .ann-item:last-child { margin-bottom: 0; }
    .ann-item-left { display: flex; align-items: center; gap: 14px; }
    .ann-item-icon {
        width: 42px; height: 42px; background: var(--card);
        border-radius: 10px; display: flex; align-items: center; justify-content: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .ann-item-name { font-size: 15px; font-weight: 700; color: var(--text); }
    .ann-item-meta { font-size: 13px; color: var(--muted); margin-top: 5px; display: flex; align-items: center; gap: 6px; }

    .ann-badge {
        padding: 4px 12px; border-radius: 6px; font-size: 11px; font-weight: 600;
    }
    .ann-badge-danger { background: #dc2626; color: #fff; }
    .ann-badge-warning { background: var(--bg2); color: var(--text); border: 1px solid var(--border2); }
    .ann-badge-primary { background: var(--primary); color: #fff; }

    .ann-card {
        padding: 20px; background: var(--bg3); border: 1px solid var(--border2);
        border-radius: 12px; margin-bottom: 16px;
        transition: all .2s;
        position: relative;
    }
    .ann-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        border-color: var(--primary);
    }
    .ann-card:last-child { margin-bottom: 0; }
    .ann-card-header { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 12px; }
    .ann-card-icon {
        width: 40px; height: 40px; background: var(--card);
        border-radius: 10px; display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .ann-card-title { font-size: 15px; font-weight: 700; color: var(--text); line-height: 1.3; }
    .ann-card-text { font-size: 14px; color: var(--muted); line-height: 1.6; }
    .ann-card-date-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600;
        background: var(--card); color: var(--muted); border: 1px solid var(--border2);
        margin-top: 12px;
    }
    .ann-card-date-badge svg { width: 12px; height: 12px; }

    .ann-rejection {
        background: rgba(239,68,68,0.08); padding: 14px; border-radius: 10px;
        border: 1px solid rgba(239,68,68,0.2); margin-top: 12px;
    }
    .ann-rejection-text { font-size: 13px; color: #dc2626; }

    .ann-btn {
        padding: 10px 18px; background: var(--primary); color: #fff;
        border: none; border-radius: 10px; font-size: 13px; font-weight: 700;
        cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
        box-shadow: 0 2px 8px rgba(37,99,235,0.25);
        transition: all .2s;
    }
    .ann-btn:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37,99,235,0.35);
    }
    .ann-btn:active { transform: translateY(0); }

    /* ── QR Code Modal ── */
    .qr-modal-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,.55); backdrop-filter: blur(4px);
        z-index: 1000;
        display: flex; align-items: center; justify-content: center;
        padding: 20px;
        opacity: 0; pointer-events: none;
        transition: opacity .25s ease;
    }
    .qr-modal-overlay.is-open {
        opacity: 1; pointer-events: all;
    }
    .qr-modal {
        background: var(--card); border: 1px solid var(--border2);
        border-radius: 18px;
        padding: 28px 24px;
        max-width: 360px; width: 100%;
        text-align: center;
        transform: scale(.94) translateY(12px);
        transition: transform .28s cubic-bezier(.34,1.56,.64,1), opacity .25s;
        opacity: 0;
        position: relative;
    }
    .qr-modal-overlay.is-open .qr-modal {
        transform: scale(1) translateY(0);
        opacity: 1;
    }
    .qr-modal-close {
        position: absolute; top: 14px; right: 14px;
        background: var(--bg3); border: 1px solid var(--border2);
        border-radius: 8px; width: 30px; height: 30px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; color: var(--muted);
        transition: background .15s;
    }
    .qr-modal-close:hover { background: var(--bg2); }
    .qr-modal-heading {
        font-size: 16px; font-weight: 800; color: var(--text);
        margin: 0 0 4px;
    }
    .qr-modal-sub {
        font-size: 12px; color: var(--muted); margin-bottom: 20px;
    }
    .qr-img-wrap {
        width: 260px; height: 260px;
        margin: 0 auto 16px;
        border-radius: 12px;
        border: 2px solid var(--border2);
        overflow: hidden;
        display: flex; align-items: center; justify-content: center;
        background: #fff;
    }
    .qr-img-wrap img { width: 100%; height: 100%; object-fit: contain; }
    .qr-item-name {
        font-size: 15px; font-weight: 700; color: var(--text); margin-bottom: 4px;
    }
    .qr-token {
        font-size: 11px; color: var(--muted); font-family: monospace;
        background: var(--bg3); border-radius: 6px; padding: 4px 8px;
        display: inline-block; margin-bottom: 16px; letter-spacing: .04em;
    }
    .qr-instruction {
        font-size: 12px; color: var(--muted); line-height: 1.6;
        background: var(--bg3); border-radius: 8px;
        padding: 10px 12px; margin-bottom: 16px;
        border: 1px solid var(--border2);
    }
    .qr-expires {
        font-size: 11px; color: var(--subtle);
    }
    /* spinner */
    .qr-spinner {
        width: 40px; height: 40px;
        border: 3px solid var(--border2);
        border-top-color: var(--primary);
        border-radius: 50%;
        animation: spin .7s linear infinite;
        margin: 60px auto;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    .ann-empty {
        padding: 48px 20px; text-align: center;
    }
    .ann-empty-icon { width: 80px; height: 80px; margin: 0 auto 16px; color: var(--subtle); }
    .ann-empty-title { font-size: 18px; font-weight: 600; color: var(--text); margin-bottom: 8px; }
    .ann-empty-sub { font-size: 14px; color: var(--muted); margin-bottom: 16px; }

    /* Responsive Design */
    @media (max-width: 768px) {
        .ann-header {
            padding: 20px 24px;
        }
        .ann-header-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }
        .ann-icon {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .ann-title {
            font-size: 18px;
        }
        .ann-sub {
            font-size: 13px;
        }

        .ann-panel-header {
            padding: 18px 20px;
        }
        .ann-panel-header-content {
            gap: 12px;
        }
        .ann-panel-title {
            font-size: 16px;
        }
        .ann-panel-body {
            padding: 16px 20px;
        }

        .ann-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 14px;
            padding: 16px;
        }
        .ann-item-left {
            width: 100%;
        }
        .ann-btn {
            width: 100%;
            justify-content: center;
        }

        .ann-card {
            padding: 18px;
        }
        .ann-card-header {
            gap: 10px;
        }
        .ann-card-title {
            font-size: 14px;
        }
        .ann-card-text {
            font-size: 13px;
        }

        .ann-badge {
            font-size: 10px;
            padding: 3px 10px;
        }
    }

    @media (max-width: 480px) {
        .ann-header {
            padding: 16px 20px;
        }
        .ann-title {
            font-size: 16px;
        }
        .ann-sub {
            font-size: 12px;
        }

        .ann-item-icon {
            width: 36px;
            height: 36px;
        }
        .ann-item-name {
            font-size: 14px;
        }
        .ann-item-meta {
            font-size: 12px;
        }

        .ann-card-icon {
            width: 36px;
            height: 36px;
        }
        .ann-card-icon svg {
            width: 18px;
            height: 18px;
        }
    }
</style>

<div>
    {{-- ── QR Code Modal ── --}}
    <div id="qr-modal-overlay" class="qr-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="qr-modal-heading">
        <div class="qr-modal">
            <button class="qr-modal-close" onclick="closeQRModal()" aria-label="Tutup modal">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <p class="qr-modal-heading" id="qr-modal-heading">QR Code Peminjaman</p>
            <p class="qr-modal-sub">Tunjukkan ke petugas ruang inventaris saat mengambil barang</p>

            {{-- Spinner (loading state) --}}
            <div id="qr-spinner" class="qr-spinner"></div>

            {{-- Error state --}}
            <div id="qr-error" style="display:none;color:#dc2626;font-size:13px;padding:16px;"></div>

            {{-- QR Image --}}
            <div id="qr-img-wrap" class="qr-img-wrap" style="display:none;">
                <img id="qr-img" src="" alt="QR Code peminjaman" />
            </div>

            {{-- Info barang --}}
            <div id="qr-item-name" class="qr-item-name"></div>
            <div id="qr-token" class="qr-token"></div>

            {{-- Instruksi --}}
            <div class="qr-instruction">
                📋 Tunjukkan QR Code ini ke petugas ruang inventaris saat mengambil barang. Petugas akan men-scan untuk konfirmasi pengambilan.
            </div>

            <div id="qr-expires" class="qr-expires"></div>
        </div>
    </div>

    {{-- Page Header --}}
    <div class="ann-header">
        <div class="ann-header-content">
            <div>
                <div class="ann-title">Pengumuman & Notifikasi</div>
                <div class="ann-sub">Informasi penting terkait peminjaman Anda</div>
            </div>
            <div class="ann-icon">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </div>
        </div>
    </div>

    @php
        $overdueBorrowings = \App\Models\BorrowingRequest::with('itemWithTrashed')
            ->where('user_id', auth()->id())
            ->where('status', 'borrowed')
            ->whereDate('return_date', '<', now())
            ->get();

        $dueSoonBorrowings = \App\Models\BorrowingRequest::with('itemWithTrashed')
            ->where('user_id', auth()->id())
            ->where('status', 'borrowed')
            ->whereDate('return_date', '>=', now())
            ->whereDate('return_date', '<=', now()->addDays(2))
            ->get();

        $recentApprovals = \App\Models\BorrowingRequest::with('itemWithTrashed')
            ->where('user_id', auth()->id())
            ->where('status', 'approved')
            ->latest('approved_at')
            ->take(3)
            ->get();

        $recentRejections = \App\Models\BorrowingRequest::with('itemWithTrashed')
            ->where('user_id', auth()->id())
            ->where('status', 'rejected')
            ->latest('updated_at')
            ->take(3)
            ->get();
    @endphp

    {{-- Critical: Overdue Items --}}
    @if($overdueBorrowings->isNotEmpty())
    <div class="ann-panel" style="border-color:rgba(239,68,68,.3)">
        <div class="ann-panel-header" style="border-color:rgba(239,68,68,.3)">
            <div class="ann-panel-header-content">
                <div style="width:36px;height:36px;background:#dc2626;border-radius:8px;display:flex;align-items:center;justify-content:center">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <div class="ann-panel-title">Peminjaman Terlambat</div>
                    <div class="ann-panel-sub">Segera kembalikan barang berikut untuk menghindari sanksi</div>
                </div>
            </div>
        </div>
        <div class="ann-panel-body">
            @foreach($overdueBorrowings as $overdue)
            @php
                $daysOverdue = now()->diffInDays(\Carbon\Carbon::parse($overdue->return_date));
            @endphp
            <div class="ann-item">
                <div class="ann-item-left">
                    <div class="ann-item-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:var(--muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div>
                        <div class="ann-item-name">{{ $overdue->itemWithTrashed?->name ?? 'Barang tidak tersedia' }}</div>
                        <div class="ann-item-meta">
                            Seharusnya dikembalikan: {{ \Carbon\Carbon::parse($overdue->return_date)->format('d M Y') }}
                        </div>
                    </div>
                </div>
                <span class="ann-badge ann-badge-danger">Terlambat {{ $daysOverdue }} hari</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Warning: Due Soon --}}
    @if($dueSoonBorrowings->isNotEmpty())
    <div class="ann-panel">
        <div class="ann-panel-header">
            <div class="ann-panel-header-content">
                <div class="ann-item-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:var(--muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <div class="ann-panel-title">Pengingat Pengembalian</div>
                    <div class="ann-panel-sub">Barang berikut harus segera dikembalikan</div>
                </div>
            </div>
        </div>
        <div class="ann-panel-body">
            @foreach($dueSoonBorrowings as $dueSoon)
            @php
                $daysLeft = \Carbon\Carbon::parse($dueSoon->return_date)->diffInDays(now());
            @endphp
            <div class="ann-item">
                <div class="ann-item-left">
                    <div class="ann-item-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:var(--muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div>
                        <div class="ann-item-name">{{ $dueSoon->itemWithTrashed?->name ?? 'Barang tidak tersedia' }}</div>
                        <div class="ann-item-meta">
                            Harus dikembalikan: {{ \Carbon\Carbon::parse($dueSoon->return_date)->format('d M Y') }}
                        </div>
                    </div>
                </div>
                <span class="ann-badge ann-badge-warning">{{ $daysLeft }} hari lagi</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Recent Approvals --}}
    @if($recentApprovals->isNotEmpty())
    <div class="ann-panel">
        <div class="ann-panel-header">
            <div class="ann-panel-title">Peminjaman Disetujui</div>
            <div class="ann-panel-sub">Barang siap diambil dari ruang inventaris</div>
        </div>
        <div class="ann-panel-body">
            @foreach($recentApprovals as $approval)
            <div class="ann-item">
                <div class="ann-item-left">
                    <div class="ann-item-icon" style="background:rgba(16,185,129,0.1)">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#10b981" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="ann-item-name">{{ $approval->itemWithTrashed?->name ?? 'Barang tidak tersedia' }}</div>
                        <div class="ann-item-meta">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Disetujui {{ $approval->approved_at ? $approval->approved_at->diffForHumans() : 'baru saja' }}
                        </div>
                    </div>
                </div>
                <button
                    class="ann-btn"
                    onclick="openQRModal({{ $approval->id }}, '{{ addslashes($approval->itemWithTrashed?->name ?? 'Barang tidak tersedia') }}')"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h2M4 12h2m-2-4h2M7 20h2M4 4h4v4H4V4zm12 0h4v4h-4V4zM4 16h4v4H4v-4z" />
                    </svg>
                    Lihat QR Code
                </button>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Recent Rejections --}}
    @if($recentRejections->isNotEmpty())
    <div class="ann-panel">
        <div class="ann-panel-header">
            <div class="ann-panel-title">Peminjaman Ditolak</div>
            <div class="ann-panel-sub">Informasi pengajuan yang tidak disetujui</div>
        </div>
        <div class="ann-panel-body">
            @foreach($recentRejections as $rejection)
            <div class="ann-card">
                <div class="ann-card-title">{{ $rejection->itemWithTrashed?->name ?? 'Barang tidak tersedia' }}</div>
                <div class="ann-card-meta" style="font-size:13px;color:var(--muted);margin-bottom:8px">
                    Ditolak {{ $rejection->updated_at->diffForHumans() }}
                </div>
                @if($rejection->rejection_reason)
                <div class="ann-rejection">
                    <div class="ann-rejection-text"><strong>Alasan:</strong> {{ $rejection->rejection_reason }}</div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- System Announcements --}}
    <div class="ann-panel">
        <div class="ann-panel-header">
            <div class="ann-panel-title">Pengumuman Sistem</div>
            <div class="ann-panel-sub">Informasi penting tentang sistem SIPBAR</div>
        </div>
        <div class="ann-panel-body">
            <div class="ann-card">
                <div class="ann-card-header">
                    <div class="ann-card-icon" style="background:rgba(37,99,235,0.1)">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#2563eb" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h2M4 12h2m-2-4h2M7 20h2M4 4h4v4H4V4zm12 0h4v4h-4V4zM4 16h4v4H4v-4z"/>
                        </svg>
                    </div>
                    <div class="ann-card-title">Cara Menggunakan QR Code</div>
                </div>
                <div class="ann-card-text">
                    Setelah peminjaman disetujui, QR Code akan muncul di halaman "Peminjaman". Tunjukkan QR Code kepada admin untuk mengambil dan mengembalikan barang.
                </div>
                <div class="ann-card-date-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Hari ini
                </div>
            </div>

            <div class="ann-card">
                <div class="ann-card-header">
                    <div class="ann-card-icon" style="background:rgba(245,158,11,0.1)">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#f59e0b" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="ann-card-title">Kebijakan Peminjaman</div>
                </div>
                <div class="ann-card-text">
                    Pastikan mengembalikan barang tepat waktu. Keterlambatan akan mempengaruhi riwayat peminjaman Anda dan dapat berakibat pada sanksi.
                </div>
                <div class="ann-card-date-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    2 hari lalu
                </div>
            </div>

            <div class="ann-card">
                <div class="ann-card-header">
                    <div class="ann-card-icon" style="background:rgba(16,185,129,0.1)">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#10b981" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div class="ann-card-title">Perawatan Barang</div>
                </div>
                <div class="ann-card-text">
                    Jaga kondisi barang yang dipinjam. Laporkan segera jika terjadi kerusakan. Pengembalian dalam kondisi baik adalah tanggung jawab peminjam.
                </div>
                <div class="ann-card-date-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    1 minggu lalu
                </div>
            </div>
        </div>
    </div>

    {{-- Empty State --}}
    @if($overdueBorrowings->isEmpty() && $dueSoonBorrowings->isEmpty() && $recentApprovals->isEmpty() && $recentRejections->isEmpty())
    <div class="ann-empty">
        <svg xmlns="http://www.w3.org/2000/svg" class="ann-empty-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="ann-empty-title">Semua Lancar!</div>
        <div class="ann-empty-sub">Tidak ada pengumuman penting saat ini. Peminjaman Anda dalam kondisi baik.</div>
        <a href="{{ route('student.catalog') }}" class="ann-btn">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;margin-right:8px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Lihat Katalog Barang
        </a>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// ── QR Code Modal Logic ──
const qrOverlay  = document.getElementById('qr-modal-overlay');
const qrImgWrap  = document.getElementById('qr-img-wrap');
const qrImgEl    = document.getElementById('qr-img');
const qrItemName = document.getElementById('qr-item-name');
const qrToken    = document.getElementById('qr-token');
const qrExpires  = document.getElementById('qr-expires');
const qrSpinner  = document.getElementById('qr-spinner');
const qrError    = document.getElementById('qr-error');

function openQRModal(borrowingId, itemName) {
    // Reset state
    qrImgWrap.style.display  = 'none';
    qrSpinner.style.display  = 'block';
    qrError.style.display    = 'none';
    qrItemName.textContent   = itemName;
    qrToken.textContent      = '';
    qrExpires.textContent    = '';

    // Tampilkan modal
    qrOverlay.classList.add('is-open');
    document.body.style.overflow = 'hidden';

    // Fetch QR Code dari backend
    fetch(`/siswa/peminjaman/${borrowingId}/qrcode`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(res => res.json())
    .then(data => {
        qrSpinner.style.display = 'none';
        if (data.success) {
            qrImgEl.src          = data.qr_image;
            qrImgWrap.style.display = 'flex';
            qrToken.textContent  = '#' + data.borrowing_id + ' · ' + data.token.substring(0, 8).toUpperCase() + '...';
            qrExpires.textContent = data.expires_at ? 'Berlaku hingga: ' + data.expires_at : '';
        } else {
            qrError.style.display = 'block';
            qrError.textContent   = data.message || 'Gagal memuat QR Code.';
        }
    })
    .catch(() => {
        qrSpinner.style.display = 'none';
        qrError.style.display   = 'block';
        qrError.textContent     = 'Koneksi gagal. Coba lagi beberapa saat.';
    });
}

function closeQRModal() {
    qrOverlay.classList.remove('is-open');
    document.body.style.overflow = '';
}

// Tutup modal saat klik overlay
qrOverlay.addEventListener('click', function(e) {
    if (e.target === qrOverlay) closeQRModal();
});

// Tutup modal saat tekan Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeQRModal();
});
</script>
@endpush
