@extends('layouts.kepala-jurusan')

@section('title', 'Riwayat Peminjaman')
@section('page-heading', 'Riwayat Peminjaman')

@section('content')
<style>
    .section-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 22px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(133, 30, 42, 0.05), 0 1px 3px rgba(0,0,0,0.03);
    }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .section-title {
        font-size: 15px;
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
        background: var(--accent);
        border-radius: 2px;
        display: inline-block;
    }
    
    .filter-bar-wrap {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 18px;
    }
    .filter-form {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: flex-end;
    }
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .form-label {
        font-size: 11.5px;
        font-weight: 600;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
    .form-input {
        padding: 8px 12px;
        border: 1px solid #f0d5da;
        border-radius: 8px;
        font-size: 13px;
        background: #ffffff;
        color: #0f172a;
        outline: none;
        min-width: 130px;
    }
    .form-input:focus {
        border-color: var(--accent);
    }
    .form-select {
        padding: 8px 36px 8px 12px !important;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 13px;
        background-color: var(--input-bg) !important;
        background-repeat: no-repeat !important;
        background-position: right 12px center !important;
        background-size: 15px 15px !important;
        color: var(--text) !important;
        outline: none;
        min-width: 130px;
        appearance: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        cursor: pointer;
    }
    .form-select::-ms-expand { display: none; }
    .form-select::-webkit-select-dropdown-icon { display: none; }
    html.dark .form-select {
        color: var(--text) !important;
        background-color: var(--input-bg) !important;
        background-repeat: no-repeat !important;
        background-position: right 12px center !important;
        background-size: 15px 15px !important;
    }
    .form-select:focus {
        border-color: var(--primary);
        color: var(--text) !important;
    }
    .filter-btn {
        padding: 8px 16px;
        background: var(--accent);
        color: #ffffff;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    .filter-btn:hover {
        background: var(--accent-hover);
    }
    .reset-btn {
        padding: 8px 14px;
        background: var(--bg3);
        color: var(--text);
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
    }
    .reset-btn:hover {
        background: #fae6e9;
        color: var(--accent);
    }

    .btn-send-report {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        background: var(--accent);
        color: #ffffff;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(153, 27, 27, 0.2);
    }
    .btn-send-report:disabled {
        background: #e2e8f0;
        color: #94a3b8;
        cursor: not-allowed;
        box-shadow: none;
        border: 1px solid #cbd5e1;
    }
    .btn-send-report:not(:disabled):hover {
        background: var(--accent-hover);
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(153, 27, 27, 0.3);
    }
    
    .table-container {
        overflow-x: auto;
        border: 1px solid var(--border);
        border-radius: 10px;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        min-width: 750px;
    }
    .table th {
        text-align: left;
        padding: 12px 14px;
        font-size: 11.5px;
        font-weight: 700;
        color: var(--muted);
        background: #fdf6f7;
        border-bottom: 1px solid var(--border);
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .table td {
        padding: 12px 14px;
        font-size: 13px;
        color: var(--text);
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }
    .table tr:last-child td {
        border-bottom: none;
    }
    .table tr:hover {
        background: #fdf2f4;
    }

    .custom-checkbox {
        width: 16px;
        height: 16px;
        cursor: pointer;
        accent-color: var(--accent);
    }

    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
    .status-pending { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    .status-approved { background: rgba(16, 185, 129, 0.12); color: #059669; border: 1px solid rgba(16, 185, 129, 0.25); }
    html.dark .status-approved { color: #059669; }
    .status-borrowed { background: #fdf2f4; color: #851e2a; border: 1px solid #f0d5da; }
    .status-returned { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
    .status-rejected { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    .status-cancelled { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }
    
    /* Action Link Detail with Color Indicator */
    .btn-detail-link {
        background: none;
        border: none;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: underline;
        padding: 4px 8px;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s ease;
    }
    /* BELUM dikembalikan -> Font ABU-ABU */
    .btn-detail-link.is-not-returned {
        color: #64748b !important;
    }
    .btn-detail-link.is-not-returned:hover {
        color: #334155 !important;
        background: #f1f5f9;
    }
    /* SUDAH dikembalikan -> Font BIRU */
    .btn-detail-link.is-returned {
        color: #2563eb !important;
    }
    .btn-detail-link.is-returned:hover {
        color: #1d4ed8 !important;
        background: rgba(37, 99, 235, 0.08);
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--muted);
    }
    .empty-state svg {
        width: 48px;
        height: 48px;
        margin-bottom: 12px;
        opacity: 0.5;
    }

    /* Modal Styles */
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
        background: #ffffff;
        border-radius: 14px;
        padding: 24px;
        width: 100%;
        max-width: 580px;
        border: 1px solid #f0d5da;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        max-height: 90vh;
        overflow-y: auto;
        color: #0f172a;
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f0d5da;
    }
    .modal-title {
        font-size: 16px;
        font-weight: 800;
        margin: 0;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .modal-close {
        background: none;
        border: none;
        font-size: 22px;
        color: #64748b;
        cursor: pointer;
        line-height: 1;
    }
    .modal-close:hover {
        color: #0f172a;
    }

    .modal-section-title {
        font-size: 12.5px;
        font-weight: 800;
        color: #851e2a;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin: 14px 0 8px 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .modal-section-title::before {
        content: '';
        width: 3px;
        height: 12px;
        background: #851e2a;
        border-radius: 2px;
        display: inline-block;
    }

    .modal-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-bottom: 6px;
    }
    .modal-grid-full {
        grid-column: span 2;
    }
    .modal-item {
        background: #fdf6f7;
        border: 1px solid #f0d5da;
        border-radius: 8px;
        padding: 9px 12px;
    }
    .modal-lbl {
        font-size: 11px;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 2px;
    }
    .modal-val {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
    }

    .return-empty-box {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 8px;
        padding: 14px;
        text-align: center;
        color: #64748b;
        font-size: 13px;
        font-weight: 500;
    }

    .modal-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 20px;
    }
    .modal-btn-close {
        padding: 8px 18px;
        background: #f1f5f9;
        color: #0f172a;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }
    .modal-btn-close:hover {
        background: #e2e8f0;
    }
</style>

<div class="section-card">
    <div class="section-header">
        <h2 class="section-title">Riwayat Peminjaman Guru</h2>
        
        <button type="button" id="btnKirimLaporan" class="btn-send-report" disabled onclick="openSendReportModal()">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
            Kirim ke Laporan (<span id="selectedCount">0</span>)
        </button>
    </div>

    {{-- Filter Form --}}
    <div class="filter-bar-wrap">
        <form method="GET" action="{{ route('kajur.history') }}" class="filter-form">
            <div class="form-group" style="min-width: 200px; flex: 1;">
                <label class="form-label">Cari Riwayat</label>
                <input type="text" name="search" class="form-input" style="width: 100%; min-width: 180px;" value="{{ request('search') }}" placeholder="Cari nama guru atau barang...">
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="borrowed" {{ request('status') === 'borrowed' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Dikembalikan</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Tanggal Dari</label>
                <input type="date" name="date_from" class="form-input" value="{{ request('date_from') }}">
            </div>
            
            <div class="form-group">
                <label class="form-label">Tanggal Sampai</label>
                <input type="date" name="date_to" class="form-input" value="{{ request('date_to') }}">
            </div>
            
            <button type="submit" class="filter-btn">Filter</button>
            <a href="{{ route('kajur.history') }}" class="reset-btn">Reset</a>
        </form>

        <div style="font-size: 12.5px; color: var(--muted);">
            Total Data: <strong>{{ $history->total() }}</strong> Riwayat
        </div>
    </div>
    
    @if($history->count() > 0)
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">
                            <input type="checkbox" id="selectAllCheckbox" class="custom-checkbox" title="Pilih Semua di Halaman Ini">
                        </th>
                        <th>Guru</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                        <th style="text-align: center;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $item)
                        @php
                            $isReturned = ($item->status === 'returned');
                            $itemJurusan = $item->user->jurusan->nama ?? ($item->user->jurusan ?? (auth()->user()->jurusan->nama ?? '-'));
                            $itemCategory = $item->itemWithTrashed?->category?->name ?? ($item->item?->category?->name ?? '-');
                            $itemLocation = $item->itemWithTrashed?->location?->name ?? ($item->item?->location?->name ?? '-');
                            $itemInitialCondition = $item->itemWithTrashed?->condition ?? ($item->item?->condition ?? 'Baik');
                        @endphp
                        <tr>
                            <td style="text-align: center;">
                                <input type="checkbox" class="row-checkbox custom-checkbox" value="{{ $item->id }}" data-date="{{ $item->borrow_date->format('Y-m-d') }}">
                            </td>
                            <td>
                                <strong>{{ $item->user->name ?? 'Guru' }}</strong>
                                @if($item->user && $item->user->phone)
                                    <div style="font-size: 11.5px; color: var(--muted);">WA: {{ $item->user->phone }}</div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $item->itemWithTrashed?->name ?? ($item->item?->name ?? 'Barang #' . $item->item_id) }}</strong>
                            </td>
                            <td><strong>{{ $item->quantity }}</strong> unit</td>
                            <td>{{ $item->borrow_date->format('d/m/Y') }}</td>
                            <td>
                                {{ $item->return_date->format('d/m/Y') }}
                                @if($item->return_time)
                                    <span style="font-size: 11px; color: var(--muted);">({{ $item->return_time }})</span>
                                @endif
                            </td>
                            <td>
                                @if($item->status === 'pending')
                                    <span class="status-badge status-pending">Pending</span>
                                @elseif($item->status === 'approved')
                                    <span class="status-badge status-approved">Disetujui</span>
                                @elseif($item->status === 'borrowed')
                                    <span class="status-badge status-borrowed">Dipinjam</span>
                                @elseif($item->status === 'returned')
                                    <span class="status-badge status-returned">Dikembalikan</span>
                                @elseif($item->status === 'rejected')
                                    <span class="status-badge status-rejected">Ditolak</span>
                                @elseif($item->status === 'cancelled')
                                    <span class="status-badge status-cancelled">Dibatalkan</span>
                                @else
                                    <span class="status-badge">{{ ucfirst($item->status) }}</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <button type="button" 
                                        class="btn-detail-link {{ $isReturned ? 'is-returned' : 'is-not-returned' }}"
                                        title="{{ $isReturned ? 'Barang sudah dikembalikan (Klik untuk detail)' : 'Barang belum dikembalikan (Klik untuk detail)' }}"
                                        onclick="openDetailModal({{ json_encode([
                                            'id' => $item->id,
                                            'guru_name' => $item->user->name ?? '-',
                                            'guru_nip' => $item->user->nip ?? '-',
                                            'guru_jurusan' => $itemJurusan,
                                            'guru_phone' => $item->user->phone ?? '-',
                                            'item_name' => $item->itemWithTrashed?->name ?? ($item->item?->name ?? 'Barang'),
                                            'item_category' => $itemCategory,
                                            'item_condition' => $itemInitialCondition,
                                            'item_location' => $itemLocation,
                                            'quantity' => $item->quantity,
                                            'borrow_date' => $item->borrow_date ? $item->borrow_date->format('d F Y') : '-',
                                            'return_date' => $item->return_date ? $item->return_date->format('d F Y') : '-',
                                            'return_time' => $item->return_time ?? '-',
                                            'purpose' => $item->purpose ?? '-',
                                            'notes' => $item->notes ?? '-',
                                            'status' => $item->status_label ?? ucfirst($item->status),
                                            'approved_by' => $item->approvedByKajur->name ?? (auth()->user()->name ?? 'Kepala Jurusan'),
                                            'approved_at' => $item->approved_at ? $item->approved_at->format('d F Y H:i') : ($item->created_at ? $item->created_at->format('d F Y H:i') : '-'),
                                            'is_returned' => $isReturned,
                                            'returned_at' => $item->returned_at ? $item->returned_at->format('d F Y H:i') : ($item->updated_at && $isReturned ? $item->updated_at->format('d F Y H:i') : '-'),
                                            'return_condition' => in_array($item->return_condition, ['good', 'Baik']) ? 'Baik' : (in_array($item->return_condition, ['damaged', 'Rusak Ringan']) ? 'Rusak Ringan' : (in_array($item->return_condition, ['lost', 'Rusak Berat']) ? 'Rusak Berat' : ($item->return_condition ?? '-'))),
                                            'return_notes' => $item->return_notes ?? '-',
                                            'verified_by' => $item->checkinBy->name ?? ($isReturned ? (auth()->user()->name ?? 'Kepala Jurusan') : '-')
                                        ]) }})">
                                    Detail
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($history->hasPages())
            <div style="margin-top: 20px; display: flex; justify-content: center; gap: 8px;">
                {{ $history->links() }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p>Tidak ada riwayat peminjaman yang ditemukan</p>
        </div>
    @endif
</div>

{{-- Detail Modal --}}
<div id="historyDetailModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#851e2a" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span id="modalHeaderTitle">Detail Riwayat Peminjaman</span>
            </h3>
            <button type="button" class="modal-close" onclick="closeDetailModal()">&times;</button>
        </div>

        {{-- Section 1: Data Peminjam --}}
        <div class="modal-section-title">Data Peminjam (Guru)</div>
        <div class="modal-grid">
            <div class="modal-item">
                <div class="modal-lbl">Nama Guru</div>
                <div class="modal-val" id="dGuruName">-</div>
            </div>
            <div class="modal-item">
                <div class="modal-lbl">NIP</div>
                <div class="modal-val" id="dGuruNip">-</div>
            </div>
            <div class="modal-item">
                <div class="modal-lbl">Jurusan</div>
                <div class="modal-val" id="dGuruJurusan">-</div>
            </div>
            <div class="modal-item">
                <div class="modal-lbl">No. WhatsApp / Kontak</div>
                <div class="modal-val" id="dGuruPhone">-</div>
            </div>
        </div>

        {{-- Section 2: Data Barang --}}
        <div class="modal-section-title">Data Barang</div>
        <div class="modal-grid">
            <div class="modal-item modal-grid-full">
                <div class="modal-lbl">Nama Barang</div>
                <div class="modal-val" id="dItemName" style="color: #851e2a; font-size: 14px;">-</div>
            </div>
            <div class="modal-item">
                <div class="modal-lbl">Kategori</div>
                <div class="modal-val" id="dItemCategory">-</div>
            </div>
            <div class="modal-item">
                <div class="modal-lbl">Kondisi Awal</div>
                <div class="modal-val" id="dItemCondition">-</div>
            </div>
            <div class="modal-item">
                <div class="modal-lbl">Lokasi Penyimpanan</div>
                <div class="modal-val" id="dItemLocation">-</div>
            </div>
            <div class="modal-item">
                <div class="modal-lbl">Jumlah Dipinjam</div>
                <div class="modal-val" id="dQuantity">-</div>
            </div>
        </div>

        {{-- Section 3: Data Peminjaman --}}
        <div class="modal-section-title">Data Peminjaman</div>
        <div class="modal-grid">
            <div class="modal-item">
                <div class="modal-lbl">Tanggal Pinjam</div>
                <div class="modal-val" id="dBorrowDate">-</div>
            </div>
            <div class="modal-item">
                <div class="modal-lbl">Jatuh Tempo Kembali</div>
                <div class="modal-val" id="dReturnDate">-</div>
            </div>
            <div class="modal-item">
                <div class="modal-lbl">Status Peminjaman</div>
                <div class="modal-val" id="dStatus">-</div>
            </div>
            <div class="modal-item">
                <div class="modal-lbl">Disetujui Oleh & Waktu</div>
                <div class="modal-val" id="dApprovedInfo">-</div>
            </div>
            <div class="modal-item modal-grid-full">
                <div class="modal-lbl">Tujuan / Keperluan</div>
                <div class="modal-val" id="dPurpose" style="font-weight: 500;">-</div>
            </div>
            <div class="modal-item modal-grid-full">
                <div class="modal-lbl">Catatan Tambahan</div>
                <div class="modal-val" id="dNotes" style="font-weight: 500;">-</div>
            </div>
        </div>

        {{-- Section 4: Data Pengembalian (KONDISIONAL) --}}
        <div class="modal-section-title">Data Pengembalian</div>
        <div id="returnFilledSection" class="modal-grid" style="display: none;">
            <div class="modal-item">
                <div class="modal-lbl">Tgl Pengembalian Aktual</div>
                <div class="modal-val" id="dReturnedAt">-</div>
            </div>
            <div class="modal-item">
                <div class="modal-lbl">Kondisi Barang Saat Kembali</div>
                <div class="modal-val" id="dReturnCondition">-</div>
            </div>
            <div class="modal-item modal-grid-full">
                <div class="modal-lbl">Catatan Verifikasi / Verifikator</div>
                <div class="modal-val" id="dReturnNotes" style="font-weight: 500;">-</div>
            </div>
        </div>
        <div id="returnEmptySection" class="return-empty-box">
            Belum ada data pengembalian (Barang belum dikembalikan oleh guru)
        </div>

        <div class="modal-actions">
            <button type="button" class="modal-btn-close" onclick="closeDetailModal()">Tutup</button>
        </div>
    </div>
</div>

{{-- Modal Kirim ke Laporan --}}
<div id="sendReportModal" class="modal">
    <div class="modal-content" style="max-width: 480px;">
        <div class="modal-header">
            <h3 class="modal-title">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#851e2a" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Buat & Kirim Laporan Jurusan
            </h3>
            <button type="button" class="modal-close" onclick="closeSendReportModal()">&times;</button>
        </div>

        <p style="font-size: 13px; color: var(--muted); margin-bottom: 16px;">
            Anda telah memilih <strong id="modalSelectedCountText">0 item</strong> riwayat peminjaman. Sistem akan membuat laporan rekapitulasi untuk rentang tanggal berikut:
        </p>

        <form method="POST" action="{{ route('kajur.create-report') }}">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                <div class="form-group">
                    <label class="form-label">Periode Awal *</label>
                    <input type="date" name="periode_awal" id="modalPeriodeAwal" class="form-input" required style="width: 100%;">
                </div>
                <div class="form-group">
                    <label class="form-label">Periode Akhir *</label>
                    <input type="date" name="periode_akhir" id="modalPeriodeAkhir" class="form-input" required style="width: 100%;">
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="modal-btn-close" onclick="closeSendReportModal()">Batal</button>
                <button type="submit" class="btn-send-report">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Kirim Laporan ke Admin
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Selection & Checkbox Logic
var selectAllCheckbox = document.getElementById('selectAllCheckbox');
var rowCheckboxes = document.querySelectorAll('.row-checkbox');
var btnKirimLaporan = document.getElementById('btnKirimLaporan');
var selectedCountSpan = document.getElementById('selectedCount');

function updateSelectionState() {
    var checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
    var count = checkedBoxes.length;
    
    if (selectedCountSpan) {
        selectedCountSpan.innerText = count;
    }
    
    if (btnKirimLaporan) {
        btnKirimLaporan.disabled = (count === 0);
    }

    if (selectAllCheckbox && rowCheckboxes.length > 0) {
        selectAllCheckbox.checked = (count === rowCheckboxes.length);
        selectAllCheckbox.indeterminate = (count > 0 && count < rowCheckboxes.length);
    }
}

if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener('change', function() {
        var isChecked = this.checked;
        rowCheckboxes.forEach(function(cb) {
            cb.checked = isChecked;
        });
        updateSelectionState();
    });
}

rowCheckboxes.forEach(function(cb) {
    cb.addEventListener('change', function() {
        updateSelectionState();
    });
});

// Modal Detail Logic
function openDetailModal(data) {
    document.getElementById('modalHeaderTitle').innerText = 'Detail Riwayat #' + data.id;
    
    // Peminjam
    document.getElementById('dGuruName').innerText = data.guru_name;
    document.getElementById('dGuruNip').innerText = data.guru_nip || '-';
    document.getElementById('dGuruJurusan').innerText = data.guru_jurusan || '-';
    document.getElementById('dGuruPhone').innerText = data.guru_phone || '-';

    // Barang
    document.getElementById('dItemName').innerText = data.item_name;
    document.getElementById('dItemCategory').innerText = data.item_category;
    document.getElementById('dItemCondition').innerText = data.item_condition;
    document.getElementById('dItemLocation').innerText = data.item_location;
    document.getElementById('dQuantity').innerText = data.quantity + ' Unit';

    // Peminjaman
    document.getElementById('dBorrowDate').innerText = data.borrow_date;
    document.getElementById('dReturnDate').innerText = data.return_date + (data.return_time && data.return_time !== '-' ? ' (' + data.return_time + ')' : '');
    document.getElementById('dStatus').innerText = data.status;
    document.getElementById('dApprovedInfo').innerText = data.approved_by + ' (' + data.approved_at + ')';
    document.getElementById('dPurpose').innerText = data.purpose;
    document.getElementById('dNotes').innerText = data.notes;

    // Pengembalian (KONDISIONAL)
    var filledSection = document.getElementById('returnFilledSection');
    var emptySection = document.getElementById('returnEmptySection');

    if (data.is_returned) {
        filledSection.style.display = 'grid';
        emptySection.style.display = 'none';
        
        document.getElementById('dReturnedAt').innerText = data.returned_at;
        document.getElementById('dReturnCondition').innerText = data.return_condition;
        
        var retNotes = data.return_notes || '-';
        if (data.verified_by && data.verified_by !== '-') {
            retNotes += ' (Diverifikasi oleh: ' + data.verified_by + ')';
        }
        document.getElementById('dReturnNotes').innerText = retNotes;
    } else {
        filledSection.style.display = 'none';
        emptySection.style.display = 'block';
    }

    document.getElementById('historyDetailModal').classList.add('show');
}

function closeDetailModal() {
    document.getElementById('historyDetailModal').classList.remove('show');
}

// Modal Send Report Logic
function openSendReportModal() {
    var checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
    if (checkedBoxes.length === 0) return;

    var dates = [];
    checkedBoxes.forEach(function(cb) {
        var d = cb.getAttribute('data-date');
        if (d) dates.push(d);
    });

    dates.sort();
    var minDate = dates[0] || '{{ date('Y-m-01') }}';
    var maxDate = dates[dates.length - 1] || '{{ date('Y-m-d') }}';

    document.getElementById('modalSelectedCountText').innerText = checkedBoxes.length + ' item';
    document.getElementById('modalPeriodeAwal').value = minDate;
    document.getElementById('modalPeriodeAkhir').value = maxDate;

    document.getElementById('sendReportModal').classList.add('show');
}

function closeSendReportModal() {
    document.getElementById('sendReportModal').classList.remove('show');
}

// Close modals when clicking outside
window.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
        e.target.classList.remove('show');
    }
});
</script>
@endsection