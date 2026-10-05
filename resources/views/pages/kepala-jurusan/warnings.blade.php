@extends('layouts.kepala-jurusan')

@section('title', 'Peringatan Keterlambatan Peminjaman')
@section('page-heading', 'Peringatan Keterlambatan')

@section('content')
<style>
    /* ─── Summary Cards ─── */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    .summary-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 2px 8px rgba(133, 30, 42, 0.04);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(133, 30, 42, 0.08);
    }
    .summary-icon-wrap {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .summary-icon-wrap svg {
        width: 22px;
        height: 22px;
    }
    .icon-danger { background: rgba(239, 68, 68, 0.12); color: #dc2626; }
    .icon-warning { background: rgba(245, 158, 11, 0.12); color: #d97706; }
    .icon-darkred { background: rgba(153, 27, 27, 0.12); color: #991b1b; }
    .icon-success { background: rgba(16, 185, 129, 0.12); color: #059669; }

    .summary-val {
        font-size: 22px;
        font-weight: 800;
        color: var(--text);
        line-height: 1.2;
    }
    .summary-lbl {
        font-size: 12px;
        font-weight: 600;
        color: var(--muted);
        margin-top: 2px;
    }

    /* ─── Main Section Card ─── */
    .section-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 22px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(133, 30, 42, 0.05);
    }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 20px;
    }
    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-title::before {
        content: '';
        width: 4px;
        height: 18px;
        background: var(--accent);
        border-radius: 2px;
        display: inline-block;
    }

    /* ─── Filter Bar ─── */
    .filter-bar {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 20px;
        background: var(--bg3);
        padding: 14px 16px;
        border-radius: 10px;
        border: 1px solid var(--border);
    }
    .filter-input-wrap {
        position: relative;
        flex: 1;
        min-width: 200px;
    }
    .filter-input-wrap svg {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        color: var(--muted);
        pointer-events: none;
    }
    .filter-input {
        width: 100%;
        padding: 9px 12px 9px 36px;
        font-size: 13px;
        border-radius: 8px;
        border: 1px solid var(--border2);
        background: var(--input-bg);
        color: var(--text);
        outline: none;
        transition: border-color 0.15s;
    }
    .filter-input:focus {
        border-color: var(--accent);
    }
    .filter-select {
        padding: 9px 12px;
        font-size: 13px;
        border-radius: 8px;
        border: 1px solid var(--border2);
        background: var(--input-bg);
        color: var(--text);
        outline: none;
        cursor: pointer;
        min-width: 140px;
    }
    .filter-select:focus {
        border-color: var(--accent);
    }
    .btn-filter-submit {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 16px;
        background: var(--accent);
        color: #ffffff;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s;
    }
    .btn-filter-submit:hover {
        background: var(--accent-hover);
    }
    .btn-filter-reset {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 14px;
        background: transparent;
        color: var(--muted);
        border: 1px solid var(--border2);
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.15s;
    }
    .btn-filter-reset:hover {
        background: rgba(0,0,0,0.05);
        color: var(--text);
    }

    /* ─── Action Buttons ─── */
    .btn-send-all {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        background: linear-gradient(135deg, #991b1b 0%, #7f1d1d 100%);
        color: #ffffff;
        border: none;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(153, 27, 27, 0.25);
        transition: all 0.2s ease;
    }
    .btn-send-all:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(153, 27, 27, 0.35);
    }
    .btn-send-all svg {
        width: 16px;
        height: 16px;
    }

    .btn-warn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        background: rgba(220, 38, 38, 0.1);
        color: #dc2626;
        border: 1px solid rgba(220, 38, 38, 0.25);
        cursor: pointer;
        text-decoration: none;
        transition: all 0.15s;
    }
    .btn-warn:hover:not(:disabled) {
        background: #dc2626;
        color: #ffffff;
        border-color: #dc2626;
    }
    .btn-warn:disabled, .btn-warn[disabled] {
        opacity: 0.65;
        cursor: not-allowed;
        background: var(--bg3);
        color: var(--muted);
        border-color: var(--border);
    }
    .btn-warn svg {
        width: 14px;
        height: 14px;
    }

    .btn-wa-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
        border: 1px solid rgba(16, 185, 129, 0.25);
        text-decoration: none;
        transition: all 0.15s;
    }
    .btn-wa-link:hover {
        background: #059669;
        color: #ffffff;
    }
    .btn-wa-link svg {
        width: 16px;
        height: 16px;
    }

    /* ─── Table Styles ─── */
    .table-container {
        overflow-x: auto;
        border-radius: 10px;
        border: 1px solid var(--border);
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    .table th {
        background: var(--bg3);
        padding: 12px 16px;
        font-size: 12px;
        font-weight: 700;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.03em;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }
    .table td {
        padding: 14px 16px;
        font-size: 13px;
        color: var(--text);
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }
    .table tr:last-child td {
        border-bottom: none;
    }
    .table tr:hover td {
        background: rgba(133, 30, 42, 0.02);
    }

    /* ─── Badges & Tags ─── */
    .overdue-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }
    .pill-danger {
        background: rgba(220, 38, 38, 0.12);
        color: #dc2626;
        border: 1px solid rgba(220, 38, 38, 0.3);
    }
    .pill-urgent {
        background: #991b1b;
        color: #ffffff;
    }
    .pill-warning {
        background: rgba(245, 158, 11, 0.12);
        color: #b45309;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .borrower-name {
        font-weight: 700;
        color: var(--text);
        display: block;
    }
    .borrower-sub {
        font-size: 12px;
        color: var(--muted);
        margin-top: 2px;
        display: block;
    }

    .warning-status-badge {
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        border-radius: 6px;
    }
    .ws-warned {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }
    .ws-none {
        background: rgba(100, 116, 139, 0.1);
        color: #64748b;
    }

    /* ─── Empty State ─── */
    .empty-state {
        text-align: center;
        padding: 48px 24px;
    }
    .empty-icon-wrap {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: rgba(16, 185, 129, 0.12);
        color: #059669;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }
    .empty-icon-wrap svg {
        width: 32px;
        height: 32px;
    }
    .empty-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 6px;
    }
    .empty-desc {
        font-size: 13px;
        color: var(--muted);
        max-width: 420px;
        margin: 0 auto;
    }

    /* ─── Mobile Card Stack (Hidden on Desktop) ─── */
    .mobile-cards {
        display: none;
        flex-direction: column;
        gap: 14px;
    }
    .m-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    }
    .m-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border);
    }
    .m-card-row {
        display: flex;
        justify-content: space-between;
        font-size: 12.5px;
        padding: 4px 0;
    }
    .m-card-label {
        color: var(--muted);
    }
    .m-card-val {
        color: var(--text);
        font-weight: 600;
        text-align: right;
    }
    .m-card-actions {
        display: flex;
        gap: 8px;
        margin-top: 14px;
        padding-top: 12px;
        border-top: 1px solid var(--border);
    }

    /* ─── Modals ─── */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.55);
        backdrop-filter: blur(4px);
        z-index: 100;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }
    .modal-overlay.active {
        display: flex;
    }
    .modal-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 14px;
        max-width: 500px;
        width: 100%;
        padding: 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.25);
        animation: modalFadeIn 0.2s ease-out;
    }
    @keyframes modalFadeIn {
        from { opacity: 0; transform: scale(0.96); }
        to { opacity: 1; transform: scale(1); }
    }
    .modal-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .modal-desc {
        font-size: 13px;
        color: var(--muted);
        line-height: 1.5;
        margin-bottom: 20px;
    }
    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
    .btn-modal-cancel {
        padding: 9px 16px;
        border-radius: 8px;
        border: 1px solid var(--border2);
        background: transparent;
        color: var(--text);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }
    .btn-modal-confirm {
        padding: 9px 18px;
        border-radius: 8px;
        border: none;
        background: var(--accent);
        color: #ffffff;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    /* ─── Flash Alerts ─── */
    .flash-alert {
        padding: 12px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .flash-success { background: rgba(16, 185, 129, 0.12); color: #059669; border: 1px solid rgba(16, 185, 129, 0.3); }
    .flash-warning { background: rgba(245, 158, 11, 0.12); color: #b45309; border: 1px solid rgba(245, 158, 11, 0.3); }
    .flash-error { background: rgba(239, 68, 68, 0.12); color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.3); }

    @media (max-width: 900px) {
        .summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 768px) {
        .summary-grid {
            grid-template-columns: 1fr;
        }
        .table-container {
            display: none;
        }
        .mobile-cards {
            display: flex;
        }
    }
</style>

@if(isset($unassignedOverdueCount) && $unassignedOverdueCount > 0)
<div class="flash-alert" style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.3); color: var(--blue, #2563eb);">
    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>Terdapat <strong>{{ $unassignedOverdueCount }}</strong> peminjaman terlambat tidak terhubung ke jurusan manapun, hubungi admin.</span>
</div>
@endif

@if(session('success'))
<div class="flash-alert flash-success">
    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    <span>{{ session('success') }}</span>
    @if(session('wa_link'))
    <a href="{{ session('wa_link') }}" target="_blank" rel="noopener noreferrer" style="margin-left:auto;color:#059669;font-weight:700;text-decoration:underline;">Buka WhatsApp ↗</a>
    @endif
</div>
@endif

@if(session('warning'))
<div class="flash-alert flash-warning">
    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    <span>{{ session('warning') }}</span>
    @if(session('wa_link'))
    <a href="{{ session('wa_link') }}" target="_blank" rel="noopener noreferrer" style="margin-left:auto;color:#b45309;font-weight:700;text-decoration:underline;">Buka WhatsApp Manual ↗</a>
    @endif
</div>
@endif

@if(session('error'))
<div class="flash-alert flash-error">
    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    <span>{{ session('error') }}</span>
</div>
@endif

{{-- Summary Cards --}}
<div class="summary-grid">
    <div class="summary-card">
        <div class="summary-icon-wrap icon-danger">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <div class="summary-val">{{ $summary['total_overdue'] }}</div>
            <div class="summary-lbl">Total Terlambat</div>
        </div>
    </div>

    <div class="summary-card">
        <div class="summary-icon-wrap icon-warning">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div>
            <div class="summary-val">{{ $summary['overdue_gt_3'] }}</div>
            <div class="summary-lbl">Terlambat > 3 Hari</div>
        </div>
    </div>

    <div class="summary-card">
        <div class="summary-icon-wrap icon-darkred">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <div>
            <div class="summary-val">{{ $summary['overdue_gt_7'] }}</div>
            <div class="summary-lbl">Terlambat > 7 Hari</div>
        </div>
    </div>

    <div class="summary-card">
        <div class="summary-icon-wrap icon-success">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <div class="summary-val">{{ $summary['warned_today'] }}</div>
            <div class="summary-lbl">Diperingatkan (24 Jam)</div>
        </div>
    </div>
</div>

{{-- Main Section --}}
<div class="section-card">
    <div class="section-header">
        <div>
            <h2 class="section-title">Daftar Peminjaman Terlambat</h2>
            <div style="font-size:12px;color:var(--muted);margin-top:4px">
                Khusus siswa dan guru jurusan {{ auth()->user()->jurusan?->nama ?? 'Anda' }}
            </div>
        </div>
        @if($summary['total_overdue'] > 0)
        <button type="button" class="btn-send-all" onclick="openBulkModal()">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            Kirim ke Semua yang Terlambat
        </button>
        @endif
    </div>

    {{-- Filter Bar --}}
    <form method="GET" action="{{ route('kajur.warnings.index') }}" class="filter-bar">
        <div class="filter-input-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="q" value="{{ $activeFilter['q'] }}" placeholder="Cari nama peminjam / barang..." class="filter-input">
        </div>

        @if($classrooms->isNotEmpty())
        <select name="kelas" class="filter-select">
            <option value="">Semua Kelas</option>
            @foreach($classrooms as $cls)
            <option value="{{ $cls }}" {{ $activeFilter['kelas'] === $cls ? 'selected' : '' }}>{{ $cls }}</option>
            @endforeach
        </select>
        @endif

        <select name="level" class="filter-select">
            <option value="all" {{ $activeFilter['level'] === 'all' ? 'selected' : '' }}>Semua Keterlambatan</option>
            <option value="1-3" {{ $activeFilter['level'] === '1-3' ? 'selected' : '' }}>1 - 3 Hari</option>
            <option value="gt3" {{ $activeFilter['level'] === 'gt3' ? 'selected' : '' }}>> 3 Hari</option>
            <option value="gt7" {{ $activeFilter['level'] === 'gt7' ? 'selected' : '' }}>> 7 Hari</option>
        </select>

        <button type="submit" class="btn-filter-submit">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            Filter
        </button>

        @if(!empty($activeFilter['q']) || !empty($activeFilter['kelas']) || $activeFilter['level'] !== 'all')
        <a href="{{ route('kajur.warnings.index') }}" class="btn-filter-reset">Reset</a>
        @endif
    </form>

    @if($borrowings->isEmpty())
    {{-- Empty State --}}
    <div class="empty-state">
        <div class="empty-icon-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        @if(!$kajurHasJurusan && $summary['total_overdue'] === 0)
        <div class="empty-title">Akun Belum Terhubung ke Jurusan</div>
        <div class="empty-desc">
            Akun Kepala Jurusan Anda belum terhubung ke jurusan manapun. Silakan hubungi Administrator untuk mengatur jurusan pada akun Anda.
        </div>
        @else
        <div class="empty-title">Tidak Ada Keterlambatan di Jurusan Anda</div>
        <div class="empty-desc">
            @if(!empty($activeFilter['q']) || !empty($activeFilter['kelas']) || $activeFilter['level'] !== 'all')
            Tidak ditemukan peminjaman terlambat yang sesuai dengan filter pencarian Anda.
            @else
            Semua peminjaman siswa dan guru jurusan Anda saat ini berstatus tertib atau telah dikembalikan tepat waktu.
            @endif
        </div>
        @endif
    </div>
    @else

    {{-- Desktop Table --}}
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Peminjam</th>
                    <th>Barang</th>
                    <th>Tgl Pinjam</th>
                    <th>Batas Kembali</th>
                    <th>Keterlambatan</th>
                    <th>Status Peringatan</th>
                    <th style="text-align:right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($borrowings as $b)
                @php
                    $days = isset($warningService) ? $warningService->calculateDaysOverdue($b) : 0;
                    $hasRecentWarning = \App\Models\LateWarningLog::hasRecentWarning($b->id);
                    $latestWarning = $b->latestLateWarning;
                    $warningCount = $b->lateWarningLogs->count();
                    $waLink = isset($warningService) ? $warningService->generateWhatsAppLink($b, auth()->user()) : null;
                @endphp
                <tr>
                    <td>
                        <span class="borrower-name">{{ $b->user?->name ?? 'Peminjam' }}</span>
                        <span class="borrower-sub">
                            @if($b->tipe_peminjam === 'guru')
                                Guru · NIP: {{ $b->user?->nip ?? '-' }}
                            @else
                                {{ $b->user?->kelas ?? 'Siswa' }} · NIS: {{ $b->user?->nis ?? '-' }}
                            @endif
                        </span>
                    </td>
                    <td>
                        <span style="font-weight:700;color:var(--text)">{{ $b->item_display_name }}</span>
                        <span style="font-size:12px;color:var(--muted);display:block">
                            Jumlah: <strong>{{ $b->totalQuantity() }} unit</strong>
                        </span>
                    </td>
                    <td>
                        <span style="font-size:12.5px;color:var(--text)">{{ $b->borrow_date ? $b->borrow_date->format('d/m/Y') : '-' }}</span>
                    </td>
                    <td>
                        <span style="font-size:12.5px;font-weight:600;color:#dc2626">
                            {{ $b->return_date ? $b->return_date->format('d/m/Y') : '-' }}
                            @if($b->return_time)
                            <span style="font-size:12px;color:var(--muted);display:block">{{ $b->return_time }}</span>
                            @endif
                        </span>
                    </td>
                    <td>
                        @if($days > 7)
                        <span class="overdue-pill pill-urgent">🔥 {{ $days }} Hari</span>
                        @elseif($days > 3)
                        <span class="overdue-pill pill-danger">⚠️ {{ $days }} Hari</span>
                        @elseif($days > 0)
                        <span class="overdue-pill pill-warning">{{ $days }} Hari</span>
                        @else
                        <span class="overdue-pill pill-warning">Hari ini</span>
                        @endif
                    </td>
                    <td>
                        @if($hasRecentWarning)
                            <span class="warning-status-badge ws-warned">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Sudah hari ini ({{ $latestWarning?->sent_at?->diffForHumans() }})
                            </span>
                        @elseif($warningCount > 0)
                            <span class="warning-status-badge ws-none">
                                {{ $warningCount }}x dikirim (terakhir {{ $latestWarning?->sent_at?->format('d/m/Y') }})
                            </span>
                        @else
                            <span class="warning-status-badge ws-none">Belum diperingatkan</span>
                        @endif
                    </td>
                    <td style="text-align:right">
                        <div style="display:inline-flex;align-items:center;gap:6px">
                            @if($waLink)
                            <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer" class="btn-wa-link" title="Buka Chat WhatsApp">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            </a>
                            @endif

                            <form method="POST" action="{{ route('kajur.warnings.send', $b->id) }}" onsubmit="return confirm('Kirim peringatan keterlambatan ke {{ $b->user?->name }}?')">
                                @csrf
                                <button type="submit" class="btn-warn" {{ $hasRecentWarning ? 'disabled title="Sudah diperingatkan dalam 24 jam terakhir"' : '' }}>
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                    {{ $hasRecentWarning ? 'Sudah Diperingatkan Hari Ini' : 'Kirim Peringatan' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile Card Stack --}}
    <div class="mobile-cards">
        @foreach($borrowings as $b)
        @php
            $days = isset($warningService) ? $warningService->calculateDaysOverdue($b) : 0;
            $hasRecentWarning = \App\Models\LateWarningLog::hasRecentWarning($b->id);
            $latestWarning = $b->latestLateWarning;
            $warningCount = $b->lateWarningLogs->count();
            $waLink = isset($warningService) ? $warningService->generateWhatsAppLink($b, auth()->user()) : null;
        @endphp
        <div class="m-card">
            <div class="m-card-header">
                <div>
                    <span class="borrower-name">{{ $b->user?->name ?? 'Peminjam' }}</span>
                    <span class="borrower-sub">
                        @if($b->tipe_peminjam === 'guru')
                            Guru · NIP: {{ $b->user?->nip ?? '-' }}
                        @else
                            {{ $b->user?->kelas ?? 'Siswa' }} · NIS: {{ $b->user?->nis ?? '-' }}
                        @endif
                    </span>
                </div>
                <div>
                    @if($days > 7)
                    <span class="overdue-pill pill-urgent">🔥 {{ $days }} Hari</span>
                    @elseif($days > 3)
                    <span class="overdue-pill pill-danger">⚠️ {{ $days }} Hari</span>
                    @else
                    <span class="overdue-pill pill-warning">{{ $days }} Hari</span>
                    @endif
                </div>
            </div>

            <div class="m-card-row">
                <span class="m-card-label">Barang:</span>
                <span class="m-card-val">{{ $b->item_display_name }} ({{ $b->totalQuantity() }} unit)</span>
            </div>
            <div class="m-card-row">
                <span class="m-card-label">Batas Pengembalian:</span>
                <span class="m-card-val" style="color:#dc2626">
                    {{ $b->return_date ? $b->return_date->format('d M Y') : '-' }}
                    @if($b->return_time) · {{ $b->return_time }}@endif
                </span>
            </div>
            <div class="m-card-row">
                <span class="m-card-label">Peringatan:</span>
                <span class="m-card-val">
                    @if($hasRecentWarning)
                        <span style="color:#059669">Sudah hari ini</span>
                    @elseif($warningCount > 0)
                        <span>{{ $warningCount }}x dikirim</span>
                    @else
                        <span style="color:var(--muted)">Belum</span>
                    @endif
                </span>
            </div>

            <div class="m-card-actions">
                @if($waLink)
                <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer" class="btn-wa-link" style="flex:0 0 38px;height:38px" title="Buka WhatsApp">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                </a>
                @endif
                <form method="POST" action="{{ route('kajur.warnings.send', $b->id) }}" style="flex:1" onsubmit="return confirm('Kirim peringatan keterlambatan ke {{ $b->user?->name }}?')">
                    @csrf
                    <button type="submit" class="btn-warn" style="width:100%;justify-content:center;height:38px" {{ $hasRecentWarning ? 'disabled title="Sudah diperingatkan dalam 24 jam terakhir"' : '' }}>
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        {{ $hasRecentWarning ? 'Sudah Diperingatkan Hari Ini' : 'Kirim Peringatan' }}
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Paginasi --}}
    <div style="margin-top:20px">
        {{ $borrowings->links() }}
    </div>
    @endif
</div>

{{-- Bulk Warning Confirmation Modal --}}
<div class="modal-overlay" id="bulkModal">
    <div class="modal-card">
        <div class="modal-title">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:var(--accent)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            Konfirmasi Kirim Peringatan Massal
        </div>
        <div class="modal-desc">
            Anda akan mengirimkan notifikasi peringatan keterlambatan ke seluruh peminjam di jurusan Anda (maksimal 50 per proses).
            <br><br>
            <strong>Catatan Anti-Spam:</strong> Peminjaman yang telah diperingatkan dalam 24 jam terakhir akan otomatis dilewati oleh sistem.
        </div>
        <form method="POST" action="{{ route('kajur.warnings.send-all') }}">
            @csrf
            <div class="modal-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeBulkModal()">Batal</button>
                <button type="submit" class="btn-modal-confirm">Ya, Kirim Sekarang</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openBulkModal() {
        document.getElementById('bulkModal').classList.add('active');
    }
    function closeBulkModal() {
        document.getElementById('bulkModal').classList.remove('active');
    }

    // Close modal when clicking outside
    document.getElementById('bulkModal').addEventListener('click', function(e) {
        if (e.target === this) closeBulkModal();
    });
</script>
@endsection
