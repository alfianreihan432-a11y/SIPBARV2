@extends('layouts.admin')

@section('title', 'Detail Laporan Jurusan ' . $report->jurusan->nama)
@section('page-heading', 'Detail Laporan Jurusan')

@section('content')
<style>
    .detail-wrapper {
        max-width: 1100px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .top-actions-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 16px;
        background: var(--card);
        color: var(--text);
        border: 1px solid var(--border);
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .back-btn:hover {
        background: var(--bg3);
        border-color: var(--border2);
    }

    .action-group {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-approve {
        background: rgba(16, 185, 129, 0.12);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.25);
    }
    .btn-approve:hover {
        background: rgba(16, 185, 129, 0.22);
    }
    .btn-reject {
        background: rgba(239, 68, 68, 0.12);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.25);
    }
    .btn-reject:hover {
        background: rgba(239, 68, 68, 0.22);
    }
    .btn-delete {
        background: rgba(239, 68, 68, 0.08);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }
    .btn-delete:hover {
        background: #ef4444;
        color: #ffffff;
    }

    /* Main Header Card */
    .report-header-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    }
    .report-title-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 16px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border);
    }
    .report-title-col {
        flex: 1;
        min-width: 250px;
    }
    .report-title {
        font-size: 22px;
        font-weight: 800;
        color: var(--text);
        margin: 0 0 6px 0;
        line-height: 1.25;
    }
    .report-badge-col {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .status-pending { background: rgba(245, 158, 11, 0.15); color: #d97706; border: 1px solid rgba(245, 158, 11, 0.3); }
    .status-disetujui { background: rgba(16, 185, 129, 0.15); color: #059669; border: 1px solid rgba(16, 185, 129, 0.3); }
    .status-ditolak { background: rgba(239, 68, 68, 0.15); color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.3); }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-top: 20px;
    }
    .info-item {
        background: var(--bg3);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 14px 16px;
    }
    .info-label {
        font-size: 11.5px;
        color: var(--muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 4px;
    }
    .info-value {
        font-size: 14px;
        color: var(--text);
        font-weight: 700;
    }

    /* Section Styling */
    .section-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 22px 24px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border);
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
        width: 3.5px;
        height: 16px;
        background: var(--blue, #2563eb);
        border-radius: 2px;
        display: inline-block;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
        gap: 12px;
    }
    .stat-box {
        background: var(--bg3);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 14px 12px;
        text-align: center;
        transition: transform 0.15s ease;
    }
    .stat-box:hover {
        transform: translateY(-2px);
    }
    .stat-val {
        font-size: 22px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 4px;
        line-height: 1.1;
    }
    .stat-lbl {
        font-size: 11px;
        color: var(--muted);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    /* Transaction Table */
    .table-container {
        overflow-x: auto;
        border: 1px solid var(--border);
        border-radius: 10px;
    }
    .detail-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 650px;
    }
    .detail-table th {
        background: var(--bg3);
        text-align: left;
        padding: 12px 14px;
        font-size: 11.5px;
        font-weight: 700;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom: 1px solid var(--border);
    }
    .detail-table td {
        padding: 13px 14px;
        font-size: 13px;
        color: var(--text);
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }
    .detail-table tr:last-child td {
        border-bottom: none;
    }
    .detail-table tr:hover {
        background: var(--bg3);
    }

    .badge-item-status {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
    }
    .badge-pending { background: var(--s-pending); color: #fff; }
    .badge-approved { background: var(--s-returned); color: #fff; }
    .badge-borrowed { background: var(--s-approved); color: #fff; }
    .badge-returned { background: var(--s-returned); color: #fff; }
    .badge-rejected { background: var(--s-rejected); color: #fff; }

    .condition-tag {
        display: inline-block;
        padding: 2px 7px;
        border-radius: 5px;
        font-size: 11px;
        font-weight: 600;
    }
    .condition-baik { background: var(--s-returned); color: #fff; }
    .condition-rusak-ringan { background: var(--s-pending); color: #fff; }
    .condition-rusak-berat { background: var(--s-rejected); color: #fff; }

    .btn-view-item {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 10px;
        background: rgba(59, 130, 246, 0.1);
        color: #2563eb;
        border: 1px solid rgba(59, 130, 246, 0.2);
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .btn-view-item:hover {
        background: rgba(59, 130, 246, 0.2);
        border-color: rgba(59, 130, 246, 0.3);
    }
    /* Dark mode override for view item button */
    html.dark .btn-view-item, html:not(.light) .btn-view-item {
        background: #475569 !important;
        color: #ffffff !important;
        border-color: #64748b !important;
    }
    html.dark .btn-view-item:hover, html:not(.light) .btn-view-item:hover {
        background: #64748b !important;
        color: #ffffff !important;
        border-color: #94a3b8 !important;
    }

    .note-box {
        background: var(--bg3);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 16px;
    }
    .note-box p {
        margin: 0;
        font-size: 13.5px;
        line-height: 1.6;
        color: var(--text);
    }

    /* Modals */
    .modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.55);
        z-index: 100;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(3px);
        padding: 16px;
    }
    .modal.show {
        display: flex;
    }
    .modal-content {
        background: var(--card);
        border-radius: 16px;
        padding: 28px;
        width: 100%;
        max-width: 520px;
        border: 1px solid var(--border);
        box-shadow: 0 20px 48px rgba(0,0,0,0.18), 0 4px 16px rgba(0,0,0,0.08);
        max-height: 90vh;
        overflow-y: auto;
    }
    .modal-title {
        font-size: 17px;
        font-weight: 700;
        margin-bottom: 16px;
        color: var(--text);
    }
    .form-group {
        margin-bottom: 16px;
    }
    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 6px;
    }
    .form-textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 13px;
        background: var(--input-bg);
        color: var(--text);
        outline: none;
        resize: vertical;
        min-height: 90px;
        box-sizing: border-box;
        font-family: inherit;
        line-height: 1.5;
    }
    .form-textarea:focus {
        border-color: var(--blue, #2563eb);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
    }
    .modal-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 24px;
        padding-top: 16px;
        border-top: 1px solid var(--border);
        padding-bottom: 2px;
    }

    /* Item Detail Grid in Modal */
    .modal-detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-bottom: 14px;
    }
    .modal-detail-full {
        grid-column: span 2;
    }
    .modal-detail-box {
        background: var(--bg3);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 10px 12px;
    }
    .modal-detail-lbl {
        font-size: 11px;
        color: var(--muted);
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 3px;
    }
    .modal-detail-val {
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
    }
</style>

<div class="detail-wrapper">
    {{-- Alerts --}}
    @if(session('success'))
        <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 12px 16px; border-radius: 10px; font-size: 13.5px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <svg style="width: 18px; height: 18px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px 16px; border-radius: 10px; font-size: 13.5px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <svg style="width: 18px; height: 18px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Top Action & Navigation Bar --}}
    <div class="top-actions-bar">
        <a href="{{ route('admin.laporan-jurusan') }}" class="back-btn">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Laporan
        </a>

        <div class="action-group">
            @if($report->status === 'pending_review')
                <button type="button" class="action-btn btn-approve" onclick="showApproveModal({{ $report->id }})">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Setujui Laporan
                </button>
                <button type="button" class="action-btn btn-reject" onclick="showRejectModal({{ $report->id }})">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Tolak Laporan
                </button>
            @endif

            <button type="button" class="action-btn btn-delete" onclick="showDeleteModal({{ $report->id }}, '{{ addslashes($report->jurusan->nama) }}')">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Hapus Laporan
            </button>
        </div>
    </div>

    {{-- Main Header Info Card --}}
    <div class="report-header-card">
        <div class="report-title-row">
            <div class="report-title-col">
                <h1 class="report-title">Laporan Jurusan {{ $report->jurusan->nama }}</h1>
                <div style="font-size: 13px; color: var(--muted); display: flex; align-items: center; gap: 12px; margin-top: 4px;">
                    <span>ID Laporan: <strong>#{{ $report->id }}</strong></span>
                    <span>•</span>
                    <span>Diajukan pada: <strong>{{ $report->created_at->format('d F Y, H:i') }} WIB</strong></span>
                </div>
            </div>
            
            <div class="report-badge-col">
                @if($report->status === 'pending_review')
                    <span class="status-badge status-pending">
                        <svg style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Pending Review
                    </span>
                @elseif($report->status === 'disetujui')
                    <span class="status-badge status-disetujui">
                        <svg style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Disetujui
                    </span>
                @elseif($report->status === 'ditolak')
                    <span class="status-badge status-ditolak">
                        <svg style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Ditolak
                    </span>
                @else
                    <span class="status-badge">{{ ucfirst($report->status) }}</span>
                @endif
            </div>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Jurusan</div>
                <div class="info-value">{{ $report->jurusan->nama }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Periode Rekap</div>
                <div class="info-value">{{ $report->periode_awal->format('d/m/Y') }} s/d {{ $report->periode_akhir->format('d/m/Y') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Dikirim Oleh</div>
                <div class="info-value">{{ $report->pengirim->name ?? 'Kepala Jurusan' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Total Item Rekap</div>
                <div class="info-value">{{ $statistics['total_transaksi'] }} Transaksi</div>
            </div>
        </div>
    </div>

    {{-- Statistics Card --}}
    <div class="section-card">
        <div class="section-header">
            <h2 class="section-title">Statistik Ringkasan Peminjaman</h2>
            <span style="font-size: 12.5px; color: var(--muted); font-weight: 600;">Periode {{ $report->periode_awal->format('d M Y') }} - {{ $report->periode_akhir->format('d M Y') }}</span>
        </div>

        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-val" style="color: var(--blue, #2563eb);">{{ $statistics['total_transaksi'] }}</div>
                <div class="stat-lbl">Total Transaksi</div>
            </div>
            <div class="stat-box">
                <div class="stat-val" style="color: #059669;">{{ $statistics['total_dipinjam'] }}</div>
                <div class="stat-lbl">Dipinjam / Aktif</div>
            </div>
            <div class="stat-box">
                <div class="stat-val" style="color: #475569;">{{ $statistics['total_dikembalikan'] }}</div>
                <div class="stat-lbl">Dikembalikan</div>
            </div>
            <div class="stat-box">
                <div class="stat-val" style="color: #d97706;">{{ $statistics['total_pending'] }}</div>
                <div class="stat-lbl">Masih Pending</div>
            </div>
            <div class="stat-box">
                <div class="stat-val" style="color: #dc2626;">{{ $statistics['total_rejected'] }}</div>
                <div class="stat-lbl">Ditolak</div>
            </div>
            <div class="stat-box">
                <div class="stat-val" style="color: #059669;">{{ $statistics['barang_kondisi_baik'] }}</div>
                <div class="stat-lbl">Kondisi Baik</div>
            </div>
            <div class="stat-box">
                <div class="stat-val" style="color: #d97706;">{{ $statistics['barang_kondisi_rusak_ringan'] }}</div>
                <div class="stat-lbl">Rusak Ringan</div>
            </div>
            <div class="stat-box">
                <div class="stat-val" style="color: #dc2626;">{{ $statistics['barang_kondisi_rusak_berat'] }}</div>
                <div class="stat-lbl">Rusak Berat</div>
            </div>
        </div>
    </div>

    {{-- Detail Transaction List --}}
    <div class="section-card">
        <div class="section-header">
            <h2 class="section-title">Daftar Transaksi Peminjaman ({{ $borrowingRequests->count() }})</h2>
            <span style="font-size: 12.5px; color: var(--muted);">Data guru untuk Jurusan {{ $report->jurusan->nama }}</span>
        </div>

        @if($borrowingRequests->count() > 0)
            <div class="table-container">
                <table class="detail-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Peminjam (Guru)</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Tgl Pinjam & Kembali</th>
                            <th>Status</th>
                            <th>Kondisi Kembali</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($borrowingRequests as $idx => $loan)
                            <tr>
                                <td><strong>#{{ $loan->id }}</strong></td>
                                <td>
                                    <strong>{{ $loan->user->name ?? 'Guru' }}</strong>
                                    @if($loan->user && $loan->user->phone)
                                        <div style="font-size: 11.5px; color: var(--muted);">WA: {{ $loan->user->phone }}</div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $loan->itemWithTrashed?->name ?? $loan->item?->name ?? 'Barang #' . $loan->item_id }}</strong>
                                </td>
                                <td><strong>{{ $loan->quantity }}</strong> unit</td>
                                <td>
                                    <div>{{ $loan->borrow_date->format('d/m/Y') }}</div>
                                    <div style="font-size: 11.5px; color: var(--muted);">s/d {{ $loan->return_date->format('d/m/Y') }}</div>
                                </td>
                                <td>
                                    @if($loan->status === 'pending')
                                        <span class="badge-item-status badge-pending">Pending</span>
                                    @elseif($loan->status === 'approved')
                                        <span class="badge-item-status badge-approved">Disetujui</span>
                                    @elseif($loan->status === 'borrowed')
                                        <span class="badge-item-status badge-borrowed">Dipinjam</span>
                                    @elseif($loan->status === 'returned')
                                        <span class="badge-item-status badge-returned">Dikembalikan</span>
                                    @elseif($loan->status === 'rejected')
                                        <span class="badge-item-status badge-rejected">Ditolak</span>
                                    @else
                                        <span class="badge-item-status">{{ ucfirst($loan->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if(in_array($loan->return_condition, ['Baik', 'good']))
                                        <span class="condition-tag condition-baik">Baik</span>
                                    @elseif(in_array($loan->return_condition, ['Rusak Ringan', 'damaged']))
                                        <span class="condition-tag condition-rusak-ringan">Rusak Ringan</span>
                                    @elseif(in_array($loan->return_condition, ['Rusak Berat', 'lost']))
                                        <span class="condition-tag condition-rusak-berat">Rusak Berat</span>
                                    @else
                                        <span style="color: var(--muted); font-size: 12px;">-</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <button type="button" class="btn-view-item" onclick="openItemDetailModal({{ json_encode([
                                        'id' => $loan->id,
                                        'guru_name' => $loan->user->name ?? '-',
                                        'guru_email' => $loan->user->email ?? '-',
                                        'guru_phone' => $loan->user->phone ?? '-',
                                        'item_name' => $loan->itemWithTrashed?->name ?? $loan->item?->name ?? 'Barang',
                                        'quantity' => $loan->quantity,
                                        'purpose' => $loan->purpose ?? '-',
                                        'notes' => $loan->notes ?? '-',
                                        'borrow_date' => $loan->borrow_date ? $loan->borrow_date->format('d F Y') : '-',
                                        'return_date' => $loan->return_date ? $loan->return_date->format('d F Y') : '-',
                                        'status' => $loan->status_label ?? ucfirst($loan->status),
                                        'approved_by' => $loan->approvedByKajur->name ?? ($report->pengirim->name ?? 'Kepala Jurusan'),
                                        'approved_at' => $loan->approved_at ? $loan->approved_at->format('d F Y H:i') : '-',
                                        'returned_at' => $loan->returned_at ? $loan->returned_at->format('d F Y H:i') : '-',
                                        'return_condition' => $loan->return_condition ?? '-',
                                        'return_notes' => $loan->return_notes ?? '-',
                                        'verified_by' => $loan->checkinBy->name ?? '-'
                                    ]) }})">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Cek Detail
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 36px 16px; color: var(--muted);">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:40px;height:40px;margin-bottom:10px;opacity:0.4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p style="margin:0; font-size:13.5px;">Tidak ada transaksi peminjaman guru yang tercatat dalam rentang periode ini.</p>
            </div>
        @endif
    </div>

    {{-- Admin Notes / Review Information --}}
    @if($report->catatan_admin)
        <div class="section-card">
            <div class="section-header">
                <h2 class="section-title">Catatan Admin</h2>
            </div>
            <div class="note-box">
                <p>{{ $report->catatan_admin }}</p>
            </div>
        </div>
    @endif

    {{-- Previous Submission History --}}
    <div class="section-card">
        <div class="section-header">
            <h2 class="section-title">Riwayat Pengajuan Sebelumnya ({{ $histories->count() }})</h2>
            <span style="font-size: 12.5px; color: var(--muted);">Histori pengajuan laporan terdahulu untuk Jurusan {{ $report->jurusan->nama }}</span>
        </div>

        @if($histories && $histories->count() > 0)
            <div class="table-container">
                <table class="detail-table">
                    <thead>
                        <tr>
                            <th>Periode Laporan</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Diajukan Oleh</th>
                            <th>Status Terakhir</th>
                            <th>Catatan Admin</th>
                            <th>Waktu Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($histories as $hist)
                            <tr>
                                <td style="font-weight: 600; color: var(--text);">
                                    {{ $hist->periode_awal ? $hist->periode_awal->format('d/m/Y') : '-' }} s/d {{ $hist->periode_akhir ? $hist->periode_akhir->format('d/m/Y') : '-' }}
                                </td>
                                <td>
                                    {{ $hist->submitted_at ? $hist->submitted_at->format('d M Y, H:i') : ($hist->created_at ? $hist->created_at->format('d M Y, H:i') : '-') }} WIB
                                </td>
                                <td>{{ $hist->pengirim->name ?? 'Kepala Jurusan' }}</td>
                                <td>
                                    @if($hist->status === 'pending_review')
                                        <span class="status-badge status-pending">Pending Review</span>
                                    @elseif($hist->status === 'disetujui')
                                        <span class="status-badge status-disetujui">Disetujui</span>
                                    @elseif($hist->status === 'ditolak')
                                        <span class="status-badge status-ditolak">Ditolak</span>
                                    @else
                                        <span class="status-badge">{{ ucfirst($hist->status) }}</span>
                                    @endif
                                </td>
                                <td style="max-width: 250px; font-size: 12.5px; color: var(--muted);">
                                    {{ $hist->catatan_admin ?? '-' }}
                                </td>
                                <td>
                                    {{ $hist->reviewed_at ? $hist->reviewed_at->format('d M Y, H:i') . ' WIB' : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="padding: 24px; text-align: center; color: var(--muted); font-size: 13px;">
                Belum ada riwayat pengajuan sebelumnya. Laporan saat ini adalah pengajuan aktif untuk jurusan {{ $report->jurusan->nama }}.
            </div>
        @endif
    </div>
</div>

{{-- Modal Detail Transaksi Peminjaman --}}
<div id="itemDetailModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; border-bottom: 1px solid var(--border); padding-bottom: 12px;">
            <h3 class="modal-title" style="margin: 0; color: #ffffff !important;" id="modalItemTitle">Detail Peminjaman</h3>
            <button type="button" onclick="closeItemDetailModal()" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); border-radius: 8px; width: 32px; height: 32px; color: #ffffff; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>
        </div>

        <div class="modal-detail-grid">
            <div class="modal-detail-box modal-detail-full">
                <div class="modal-detail-lbl">Barang</div>
                <div class="modal-detail-val" id="mItemName" style="font-size: 15px; color: var(--blue, #2563eb);">-</div>
            </div>

            <div class="modal-detail-box">
                <div class="modal-detail-lbl">Peminjam (Guru)</div>
                <div class="modal-detail-val" id="mGuruName">-</div>
            </div>

            <div class="modal-detail-box">
                <div class="modal-detail-lbl">Kontak / No. WA</div>
                <div class="modal-detail-val" id="mGuruPhone">-</div>
            </div>

            <div class="modal-detail-box">
                <div class="modal-detail-lbl">Jumlah Pinjam</div>
                <div class="modal-detail-val" id="mQuantity">-</div>
            </div>

            <div class="modal-detail-box">
                <div class="modal-detail-lbl">Status Transaksi</div>
                <div class="modal-detail-val" id="mStatus">-</div>
            </div>

            <div class="modal-detail-box">
                <div class="modal-detail-lbl">Tanggal Pinjam</div>
                <div class="modal-detail-val" id="mBorrowDate">-</div>
            </div>

            <div class="modal-detail-box">
                <div class="modal-detail-lbl">Target Tanggal Kembali</div>
                <div class="modal-detail-val" id="mReturnDate">-</div>
            </div>

            <div class="modal-detail-box modal-detail-full">
                <div class="modal-detail-lbl">Keperluan / Keterangan</div>
                <div class="modal-detail-val" id="mPurpose" style="font-weight: 500;">-</div>
            </div>

            <div class="modal-detail-box">
                <div class="modal-detail-lbl">Disetujui Oleh (Kajur)</div>
                <div class="modal-detail-val" id="mApprovedBy">-</div>
            </div>

            <div class="modal-detail-box">
                <div class="modal-detail-lbl">Waktu Approval</div>
                <div class="modal-detail-val" id="mApprovedAt">-</div>
            </div>

            <div class="modal-detail-box">
                <div class="modal-detail-lbl">Kondisi Pengembalian</div>
                <div class="modal-detail-val" id="mReturnCondition">-</div>
            </div>

            <div class="modal-detail-box">
                <div class="modal-detail-lbl">Waktu Pengembalian</div>
                <div class="modal-detail-val" id="mReturnedAt">-</div>
            </div>

            <div class="modal-detail-box modal-detail-full">
                <div class="modal-detail-lbl">Catatan Pengembalian / Verifikator</div>
                <div class="modal-detail-val" id="mReturnNotes" style="font-weight: 500;">-</div>
            </div>
        </div>

        <div class="modal-actions">
            <button type="button" class="action-btn" style="background: var(--bg3); color: var(--text);" onclick="closeItemDetailModal()">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="modal">
    <div class="modal-content">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; margin-bottom:20px; padding-bottom:14px; border-bottom:1px solid var(--border);">
            <div style="display:flex; align-items:center; gap:12px; min-width:0;">
                <div style="width:40px; height:40px; border-radius:50%; background:rgba(16,185,129,0.18); display:flex; align-items:center; justify-content:center; flex-shrink:0; border:1px solid rgba(16,185,129,0.3);">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:20px; height:20px; color:#10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div style="min-width:0;">
                    <h3 class="modal-title" style="margin:0; font-size:16px; font-weight:700; color:#ffffff !important; line-height:1.3;">Setujui Laporan Jurusan</h3>
                    <p style="margin:3px 0 0 0; font-size:12px; color:#cbd5e1; line-height:1.3;">Verifikasi dan setujui laporan rekap peminjaman</p>
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:10px; flex-shrink:0;">
                <span style="background:rgba(16,185,129,0.2); color:#6ee7b7; border:1px solid rgba(16,185,129,0.4); padding:4px 10px; border-radius:9999px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; white-space:nowrap;">
                    Setujui
                </span>
                <button type="button" onclick="hideApproveModal()" style="background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.15); color:#ffffff; width:32px; height:32px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; transition:all 0.2s; padding:0;" title="Tutup Modal" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.08)'">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px; height:16px; color:#ffffff;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.laporan-jurusan.approve', $report->id) }}" id="approveForm">
            @csrf
            <div class="form-group">
                <label class="form-label" style="color:#ffffff !important; font-weight:600; font-size:13px; margin-bottom:6px; display:block;">Catatan Admin (Opsional)</label>
                <textarea 
                    name="catatan_admin" 
                    class="form-textarea" 
                    rows="3" 
                    placeholder="Tambahkan catatan jika diperlukan..."
                ></textarea>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="action-btn" style="background: var(--bg3); color: var(--text);" onclick="hideApproveModal()">
                    Batal
                </button>
                <button type="submit" class="action-btn btn-approve">
                    Setujui Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal">
    <div class="modal-content">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; margin-bottom:20px; padding-bottom:14px; border-bottom:1px solid var(--border);">
            <div style="display:flex; align-items:center; gap:12px; min-width:0;">
                <div style="width:40px; height:40px; border-radius:50%; background:rgba(239,68,68,0.18); display:flex; align-items:center; justify-content:center; flex-shrink:0; border:1px solid rgba(239,68,68,0.3);">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:20px; height:20px; color:#ef4444;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div style="min-width:0;">
                    <h3 class="modal-title" style="margin:0; font-size:16px; font-weight:700; color:#ffffff !important; line-height:1.3;">Tolak Laporan Jurusan</h3>
                    <p style="margin:3px 0 0 0; font-size:12px; color:#cbd5e1; line-height:1.3;">Berikan alasan yang jelas untuk penolakan ini</p>
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:10px; flex-shrink:0;">
                <span style="background:rgba(239,68,68,0.2); color:#fca5a5; border:1px solid rgba(239,68,68,0.4); padding:4px 10px; border-radius:9999px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; white-space:nowrap;">
                    Tolak
                </span>
                <button type="button" onclick="hideRejectModal()" style="background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.15); color:#ffffff; width:32px; height:32px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; transition:all 0.2s; padding:0;" title="Tutup Modal" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.08)'">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px; height:16px; color:#ffffff;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.laporan-jurusan.reject', $report->id) }}" id="rejectForm">
            @csrf
            <div class="form-group">
                <label class="form-label" style="color:#ffffff !important; font-weight:600; font-size:13px; margin-bottom:6px; display:block;">Alasan Penolakan <span style="color:#ef4444;">*</span></label>
                <textarea 
                    name="catatan_admin" 
                    class="form-textarea" 
                    rows="4" 
                    required
                    placeholder="Jelaskan alasan penolakan laporan ini secara detail..."
                ></textarea>
                <p style="margin:6px 0 0 0; font-size:11px; color:#cbd5e1;">Alasan akan dikirim ke Kepala Jurusan yang bersangkutan.</p>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="action-btn" style="background: var(--bg3); color: var(--text); padding: 9px 18px; font-size: 13px; border: 1px solid var(--border);" onclick="hideRejectModal()">
                    Batal
                </button>
                <button type="submit" class="action-btn" style="background: #ef4444; color: #fff; padding: 9px 18px; font-size: 13px;">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px; height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Tolak Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; margin-bottom:16px; padding-bottom:14px; border-bottom:1px solid var(--border);">
            <div style="display:flex; align-items:center; gap:12px; min-width:0;">
                <div style="width:40px; height:40px; border-radius:50%; background:rgba(239,68,68,0.18); display:flex; align-items:center; justify-content:center; flex-shrink:0; border:1px solid rgba(239,68,68,0.3);">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:20px; height:20px; color:#ef4444;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div style="min-width:0;">
                    <h3 class="modal-title" style="margin:0; font-size:16px; font-weight:700; color:#ffffff !important; line-height:1.3;">Hapus Laporan Jurusan</h3>
                    <p style="margin:3px 0 0 0; font-size:12px; color:#cbd5e1; line-height:1.3;">Jurusan: {{ $report->jurusan->nama }} (ID #{{ $report->id }})</p>
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:10px; flex-shrink:0;">
                <span style="background:rgba(239,68,68,0.2); color:#fca5a5; border:1px solid rgba(239,68,68,0.4); padding:4px 10px; border-radius:9999px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; white-space:nowrap;">
                    Hapus
                </span>
                <button type="button" onclick="hideDeleteModal()" style="background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.15); color:#ffffff; width:32px; height:32px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; transition:all 0.2s; padding:0;" title="Tutup Modal" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.08)'">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px; height:16px; color:#ffffff;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <p style="font-size:13.5px; color:#ffffff; line-height:1.5; margin-bottom:20px;">
            Yakin ingin menghapus laporan ini? Tindakan ini tidak bisa dibatalkan.
        </p>

        <form method="POST" action="{{ route('admin.laporan-jurusan.destroy', $report->id) }}" id="deleteForm">
            @csrf
            @method('DELETE')
            <div class="modal-actions">
                <button type="button" class="action-btn" style="background: var(--bg3); color: var(--text);" onclick="hideDeleteModal()">
                    Batal
                </button>
                <button type="submit" class="action-btn btn-reject" style="background:#ef4444; color:#ffffff;">
                    Ya, Hapus Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showApproveModal(id) {
    document.getElementById('approveModal').classList.add('show');
}
function hideApproveModal() {
    document.getElementById('approveModal').classList.remove('show');
}

function showRejectModal(id) {
    document.getElementById('rejectModal').classList.add('show');
}
function hideRejectModal() {
    document.getElementById('rejectModal').classList.remove('show');
}

function showDeleteModal(id, name) {
    document.getElementById('deleteModal').classList.add('show');
}
function hideDeleteModal() {
    document.getElementById('deleteModal').classList.remove('show');
}

function openItemDetailModal(data) {
    document.getElementById('modalItemTitle').innerText = 'Detail Transaksi #' + data.id;
    document.getElementById('mItemName').innerText = data.item_name;
    document.getElementById('mGuruName').innerText = data.guru_name;
    document.getElementById('mGuruPhone').innerText = data.guru_phone || '-';
    document.getElementById('mQuantity').innerText = data.quantity + ' Unit';
    document.getElementById('mStatus').innerText = data.status;
    document.getElementById('mBorrowDate').innerText = data.borrow_date;
    document.getElementById('mReturnDate').innerText = data.return_date;
    document.getElementById('mPurpose').innerText = data.purpose;
    document.getElementById('mApprovedBy').innerText = data.approved_by;
    document.getElementById('mApprovedAt').innerText = data.approved_at;
    document.getElementById('mReturnCondition').innerText = data.return_condition;
    document.getElementById('mReturnedAt').innerText = data.returned_at;
    
    let returnInfo = data.return_notes;
    if (data.verified_by && data.verified_by !== '-') {
        returnInfo += ' (Diverifikasi oleh: ' + data.verified_by + ')';
    }
    document.getElementById('mReturnNotes').innerText = returnInfo;

    document.getElementById('itemDetailModal').classList.add('show');
}

function closeItemDetailModal() {
    document.getElementById('itemDetailModal').classList.remove('show');
}

// Close modals when clicking outside
window.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
        e.target.classList.remove('show');
    }
});
</script>
@endsection