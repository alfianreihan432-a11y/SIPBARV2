@extends('layouts.kepala-jurusan')

@section('title', 'Dashboard Kepala Jurusan')
@section('page-heading', 'Dashboard Kepala Jurusan')

@section('content')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 2px 8px rgba(133, 30, 42, 0.05), 0 1px 3px rgba(0,0,0,0.03);
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .stat-icon.pending { background: #fee2e2; color: #991b1b; }
    .stat-icon.active { background: rgba(16, 185, 129, 0.12); color: #059669; }
    html.dark .stat-icon.active { color: #059669; }
    .stat-icon.returns { background: #fee2e2; color: #991b1b; }
    .stat-icon.completed { background: #f1f5f9; color: #475569; }
    
    .stat-info h3 {
        font-size: 26px;
        font-weight: 800;
        margin: 0 0 2px 0;
        color: var(--text);
        line-height: 1.1;
    }
    .stat-info p {
        font-size: 12.5px;
        color: var(--muted);
        margin: 0;
        font-weight: 500;
    }
    
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
    .view-all-btn {
        font-size: 12.5px;
        color: var(--accent);
        text-decoration: none;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 6px;
        transition: all 0.15s ease;
    }
    .view-all-btn:hover {
        background: var(--accent-light);
        color: var(--accent-hover);
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    .table th {
        text-align: left;
        padding: 12px;
        font-size: 12px;
        font-weight: 600;
        color: var(--muted);
        border-bottom: 1px solid var(--border);
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
    .table td {
        padding: 12px;
        font-size: 13px;
        color: var(--text);
        border-bottom: 1px solid var(--border);
    }
    .table tr:last-child td {
        border-bottom: none;
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
    
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--muted);
    }
    .empty-state svg {
        width: 48px;
        height: 48px;
        margin-bottom: 12px;
        opacity: 0.4;
    }
    .welcome-banner {
        background: linear-gradient(135deg, #851e2a 0%, #5a141d 100%);
        color: #ffffff;
        border-radius: 12px;
        padding: 20px 24px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        box-shadow: 0 4px 14px rgba(133, 30, 42, 0.2);
    }
    .welcome-text h2 {
        font-size: 18px;
        font-weight: 800;
        margin: 0 0 4px 0;
        color: #ffffff;
    }
    .welcome-text p {
        font-size: 13px;
        margin: 0;
        color: rgba(255, 255, 255, 0.85);
    }
    .welcome-cta {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        color: #851e2a;
        font-size: 13px;
        font-weight: 700;
        padding: 10px 18px;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .welcome-cta:hover {
        background: #fdf2f4;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
</style>

{{-- Flash Message Alerts --}}
@if(session('success'))
<div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: #065f46; border-radius: 12px; padding: 14px 18px; margin-bottom: 20px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
    <svg xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px; flex-shrink: 0; color: #10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    <span>{{ session('success') }}</span>
</div>
@endif
@if(session('error'))
<div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #991b1b; border-radius: 12px; padding: 14px 18px; margin-bottom: 20px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
    <svg xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px; flex-shrink: 0; color: #ef4444;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    <span>{{ session('error') }}</span>
</div>
@endif

<div class="welcome-banner">
    <div class="welcome-text">
        <h2>Selamat Datang, {{ auth()->user()->name }}!</h2>
        <p>Kelola persetujuan peminjaman dan kirim laporan rekapitulasi jurusan ke Admin.</p>
    </div>
    <a href="{{ route('kajur.reporting') }}" class="welcome-cta">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Kirim Laporan
    </a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon pending">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['pending_approvals'] }}</h3>
            <p>Menunggu Persetujuan</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon active">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['active_borrowings'] }}</h3>
            <p>Peminjaman Aktif</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon returns">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['pending_returns'] }}</h3>
            <p>Pengembalian Pending</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon completed">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['total_completed'] }}</h3>
            <p>Total Selesai</p>
        </div>
    </div>
</div>

<div class="section-card">
    <div class="section-header">
        <h2 class="section-title">Permohonan Peminjaman Terbaru</h2>
        @if($pendingApprovals->count() > 0)
            <a href="{{ route('kajur.pending-approvals') }}" class="view-all-btn">Lihat Semua →</a>
        @endif
    </div>
    
    @if($pendingApprovals->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Guru</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Tanggal Pinjam</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingApprovals as $request)
                    @php 
                        $items = $request->items->count() ? $request->items : collect([$request->item])->filter();
                        $itemsData = $items->map(function($d) use ($request) {
                            $it = ($d instanceof \App\Models\Item) ? $d : ($d->itemWithTrashed ?? $d->item ?? null);
                            return [
                                'name' => $it ? $it->name : 'Barang tidak tersedia',
                                'code' => $it ? ($it->code ?? '-') : '-',
                                'category' => $it && $it->category ? $it->category->name : '-',
                                'quantity' => ($d instanceof \App\Models\Item) ? ($request->quantity ?? 1) : ($d->quantity ?? $request->quantity ?? 1),
                            ];
                        });
                        $modalData = [
                            'id' => $request->id,
                            'guru_name' => $request->user ? $request->user->name : '-',
                            'guru_nip' => $request->user ? ($request->user->nip ?? '-') : '-',
                            'guru_phone' => $request->user ? ($request->user->phone ?? '-') : '-',
                            'borrow_date' => $request->borrow_date ? $request->borrow_date->format('d/m/Y') : '-',
                            'return_date' => $request->return_date ? $request->return_date->format('d/m/Y') : '-',
                            'purpose' => $request->purpose ?? '-',
                            'notes' => $request->notes ?? '',
                            'items' => $itemsData,
                            'total_units' => $request->items->sum('quantity') ?: ($request->quantity ?? 0),
                            'approve_url' => route('kajur.approve-request', $request->id),
                            'reject_url' => route('kajur.reject-request', $request->id),
                        ];
                    @endphp
                    <tr>
                        <td>
                            <div style="font-weight: 700; color: var(--text);">{{ $request->user->name }}</div>
                            <div style="font-size: 11px; color: var(--muted);">NIP: {{ $request->user->nip ?? '-' }}</div>
                        </td>
                        <td>
                            @foreach($items as $detail)
                                @php
                                    $detailName = ($detail instanceof \App\Models\Item) ? $detail->name : ($detail->itemWithTrashed?->name ?? $detail->item?->name ?? 'Barang tidak tersedia');
                                    $detailQty = ($detail instanceof \App\Models\Item) ? ($request->quantity ?? 1) : ($detail->quantity ?? $request->quantity ?? 1);
                                @endphp
                                <div>{{ $detailName }} <span style="font-weight:700; color:var(--accent);">({{ $detailQty }} unit)</span></div>
                            @endforeach
                        </td>
                        <td><strong>{{ $request->items->sum('quantity') ?: ($request->quantity ?? 0) }}</strong> unit</td>
                        <td>{{ $request->borrow_date ? $request->borrow_date->format('d/m/Y') : '-' }}</td>
                        <td>
                            <span class="status-badge status-pending">Menunggu</span>
                        </td>
                        <td style="text-align: right;">
                            <button type="button" 
                                    onclick='openApprovalModal(@json($modalData))'
                                    class="view-all-btn" 
                                    style="background: rgba(133, 30, 42, 0.08); border: 1px solid rgba(133, 30, 42, 0.2); cursor: pointer; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width: 13px; height: 13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Review & Proses
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p>Tidak ada permohonan peminjaman yang menunggu persetujuan</p>
        </div>
    @endif
</div>

<div class="section-card">
    <div class="section-header">
        <h2 class="section-title">Peminjaman Aktif</h2>
        @if($activeBorrowings->count() > 0)
            <a href="{{ route('kajur.history') }}" class="view-all-btn">Lihat Semua →</a>
        @endif
    </div>
    
    @if($activeBorrowings->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Guru</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Tanggal Pinjam</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activeBorrowings as $borrowing)
                    @php $items = $borrowing->items->count() ? $borrowing->items : collect([$borrowing->item])->filter(); @endphp
                    <tr>
                        <td>{{ $borrowing->user->name }}</td>
                        <td>
                            @foreach($items as $detail)
                                {{ $detail->itemWithTrashed?->name ?? $detail->item?->name ?? 'Barang tidak tersedia' }} ({{ $detail->quantity ?? $borrowing->quantity ?? 1 }}){{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </td>
                        <td>{{ $borrowing->items->sum('quantity') ?: ($borrowing->quantity ?? 0) }}</td>
                        <td>{{ $borrowing->borrow_date ? $borrowing->borrow_date->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if($borrowing->status === 'approved')
                                <span class="status-badge status-approved">Disetujui</span>
                            @elseif($borrowing->status === 'borrowed')
                                <span class="status-badge status-borrowed">Dipinjam</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p>Tidak ada peminjaman aktif saat ini</p>
        </div>
    @endif
</div>

{{-- MODAL REVIEW & APPROVAL KAJUR --}}
<div id="kajurApprovalModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: var(--card); border: 1px solid var(--border); border-radius: 18px; max-width: 580px; width: 100%; max-height: 90vh; overflow-y: auto; padding: 24px; box-shadow: 0 20px 50px rgba(0,0,0,0.3); animation: modalPop .2s cubic-bezier(.34,1.56,.64,1);">
        
        {{-- Modal Header --}}
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid var(--border);">
            <div>
                <h3 style="font-size: 17px; font-weight: 800; color: var(--text); margin: 0;">Persetujuan Peminjaman Guru</h3>
                <p style="font-size: 12px; color: var(--muted); margin: 2px 0 0 0;">Verifikasi rincian barang sebelum menyetujui atau menolak</p>
            </div>
            <button type="button" onclick="closeApprovalModal()" style="background: var(--bg3); border: 1px solid var(--border); border-radius: 8px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--muted); font-size: 18px; line-height: 1;">&times;</button>
        </div>

        {{-- Section 1: Guru Info --}}
        <div style="background: var(--bg3); border: 1px solid var(--border); border-radius: 12px; padding: 14px 16px; margin-bottom: 16px; display: flex; align-items: center; gap: 14px;">
            <div style="width: 44px; height: 44px; border-radius: 50%; background: var(--accent); color: #fff; font-weight: 800; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;" id="mGuruAvatar">GR</div>
            <div style="flex: 1; min-width: 0;">
                <div style="font-weight: 800; font-size: 15px; color: var(--text);" id="mGuruName">-</div>
                <div style="font-size: 12px; color: var(--muted); display: flex; gap: 12px; margin-top: 2px; flex-wrap: wrap;">
                    <span>NIP: <strong id="mGuruNip" style="color: var(--text);">-</strong></span>
                    <span>No. WA: <strong id="mGuruPhone" style="color: var(--text);">-</strong></span>
                </div>
            </div>
        </div>

        {{-- Section 2: Items List --}}
        <div style="margin-bottom: 16px;">
            <div style="font-size: 13px; font-weight: 700; color: var(--text); margin-bottom: 8px; display: flex; justify-content: space-between; align-items: center;">
                <span>Daftar Barang yang Diajukan</span>
                <span style="font-size: 11px; background: rgba(133,30,42,0.1); color: var(--accent); padding: 2px 8px; border-radius: 6px; font-weight: 700;" id="mTotalUnits">0 unit</span>
            </div>
            <div id="mItemsContainer" style="display: flex; flex-direction: column; gap: 8px; max-height: 180px; overflow-y: auto;">
                {{-- Injected via JS --}}
            </div>
        </div>

        {{-- Section 3: Waktu & Keperluan --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 16px;">
            <div style="background: var(--bg3); border: 1px solid var(--border); border-radius: 10px; padding: 10px 12px;">
                <div style="font-size: 11px; color: var(--muted); font-weight: 600;">Tanggal Pinjam</div>
                <div style="font-size: 13px; font-weight: 700; color: var(--text); margin-top: 2px;" id="mBorrowDate">-</div>
            </div>
            <div style="background: var(--bg3); border: 1px solid var(--border); border-radius: 10px; padding: 10px 12px;">
                <div style="font-size: 11px; color: var(--muted); font-weight: 600;">Tanggal Kembali</div>
                <div style="font-size: 13px; font-weight: 700; color: var(--text); margin-top: 2px;" id="mReturnDate">-</div>
            </div>
        </div>

        <div style="background: var(--bg3); border: 1px solid var(--border); border-radius: 10px; padding: 12px; margin-bottom: 20px;">
            <div style="font-size: 11px; color: var(--muted); font-weight: 600;">Keperluan / Catatan</div>
            <div style="font-size: 13px; color: var(--text); margin-top: 4px; line-height: 1.4;" id="mPurpose">-</div>
        </div>

        {{-- Form Reject (Hidden toggle) --}}
        <div id="mRejectBox" style="display: none; background: rgba(239, 68, 68, 0.06); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 12px; padding: 14px; margin-bottom: 20px;">
            <form id="mRejectForm" method="POST" action="">
                @csrf
                <label style="display: block; font-size: 12px; font-weight: 700; color: #b91c1c; margin-bottom: 6px;">
                    Alasan Penolakan <span style="color: #dc2626;">*</span>
                </label>
                <textarea name="rejection_reason" id="rejection_reason" rows="2" required placeholder="Tuliskan alasan mengapa peminjaman ditolak..." style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid rgba(239, 68, 68, 0.3); font-size: 13px; color: var(--text); background: var(--card); resize: vertical; margin-bottom: 10px;"></textarea>
                <div style="display: flex; justify-content: flex-end; gap: 8px;">
                    <button type="button" onclick="toggleRejectBox(false)" class="view-all-btn" style="background: var(--bg3); border: 1px solid var(--border); cursor: pointer;">Batal</button>
                    <button type="submit" style="background: #dc2626; color: #fff; border: none; padding: 8px 16px; border-radius: 8px; font-size: 12.5px; font-weight: 700; cursor: pointer;">Konfirmasi Tolak</button>
                </div>
            </form>
        </div>

        {{-- Action Buttons --}}
        <div id="mActionButtons" style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; padding-top: 14px; border-top: 1px solid var(--border);">
            <button type="button" onclick="closeApprovalModal()" class="view-all-btn" style="background: var(--bg3); border: 1px solid var(--border); cursor: pointer; padding: 9px 16px; font-size: 13px;">Tutup</button>
            <button type="button" onclick="toggleRejectBox(true)" style="background: rgba(239, 68, 68, 0.1); color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.25); padding: 9px 16px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer;">Tolak</button>
            <form id="mApproveForm" method="POST" action="" style="margin: 0;">
                @csrf
                <button type="submit" onclick="return confirm('Setujui permohonan peminjaman guru ini?')" style="background: #059669; color: #ffffff; border: none; padding: 9px 20px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Setujui Peminjaman
                </button>
            </form>
        </div>

    </div>
</div>

<script>
function openApprovalModal(data) {
    const modal = document.getElementById('kajurApprovalModal');
    if (!modal) return;

    document.getElementById('mGuruName').textContent = data.guru_name || '-';
    document.getElementById('mGuruNip').textContent = data.guru_nip || '-';
    document.getElementById('mGuruPhone').textContent = data.guru_phone || '-';
    document.getElementById('mBorrowDate').textContent = data.borrow_date || '-';
    document.getElementById('mReturnDate').textContent = data.return_date || '-';
    document.getElementById('mPurpose').textContent = data.purpose || '-';
    document.getElementById('mTotalUnits').textContent = (data.total_units || 0) + ' unit';

    if (data.guru_name) {
        document.getElementById('mGuruAvatar').textContent = data.guru_name.substring(0, 2).toUpperCase();
    }

    // Render items list
    const container = document.getElementById('mItemsContainer');
    container.innerHTML = '';
    if (data.items && data.items.length > 0) {
        data.items.forEach(function(it) {
            const row = document.createElement('div');
            row.style.cssText = 'background: var(--card); border: 1px solid var(--border); border-radius: 8px; padding: 8px 12px; display: flex; justify-content: space-between; align-items: center;';
            row.innerHTML = `
                <div>
                    <div style="font-weight: 700; font-size: 13px; color: var(--text);">${it.name}</div>
                    <div style="font-size: 11px; color: var(--muted);">Kode: ${it.code} · Kategori: ${it.category}</div>
                </div>
                <div style="background: rgba(16, 185, 129, 0.1); color: #059669; font-weight: 800; font-size: 12px; padding: 3px 8px; border-radius: 6px;">
                    ${it.quantity} unit
                </div>
            `;
            container.appendChild(row);
        });
    } else {
        container.innerHTML = '<div style="font-size: 12px; color: var(--muted); text-align: center; padding: 8px;">Tidak ada rincian barang</div>';
    }

    // Forms action
    document.getElementById('mApproveForm').action = data.approve_url;
    document.getElementById('mRejectForm').action = data.reject_url;

    toggleRejectBox(false);
    modal.style.display = 'flex';
}

function closeApprovalModal() {
    const modal = document.getElementById('kajurApprovalModal');
    if (modal) modal.style.display = 'none';
    toggleRejectBox(false);
}

function toggleRejectBox(show) {
    const box = document.getElementById('mRejectBox');
    const actions = document.getElementById('mActionButtons');
    if (box) box.style.display = show ? 'block' : 'none';
    if (actions) actions.style.display = show ? 'none' : 'flex';
    if (show) {
        const txt = document.getElementById('rejection_reason');
        if (txt) setTimeout(function() { txt.focus(); }, 50);
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeApprovalModal();
});

document.getElementById('kajurApprovalModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeApprovalModal();
});
</script>
@endsection