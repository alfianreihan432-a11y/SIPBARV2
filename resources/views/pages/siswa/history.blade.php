@extends('layouts.siswa')

@section('title', 'Riwayat Peminjaman – SIPBAR')

@push('styles')
<style>
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
        background: #eff6ff;
    }
    /* Dark mode overrides for detail links */
    html.dark .btn-detail-link.is-not-returned {
        color: #94a3b8 !important;
    }
    html.dark .btn-detail-link.is-not-returned:hover {
        color: #cbd5e1 !important;
        background: #1e293b;
    }
    html.dark .btn-detail-link.is-returned {
        color: #60a5fa !important;
    }
    html.dark .btn-detail-link.is-returned:hover {
        color: #93c5fd !important;
        background: #1e3a8a;
    }
    
    .modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    .modal-backdrop.active {
        display: flex;
    }
    .modal-content {
        background: white;
        border-radius: 12px;
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    .modal-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-body {
        padding: 20px;
    }
    .modal-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .modal-row:last-child {
        border-bottom: none;
    }
    .modal-label {
        color: #6b7280;
        font-weight: 500;
        font-size: 13px;
    }
    .modal-value {
        color: #1f2937;
        font-weight: 600;
        font-size: 13px;
        text-align: right;
    }
</style>
@endpush

@section('content')
@php
    $query = \App\Models\BorrowingRequest::with(['itemWithTrashed', 'teacher', 'itemReturns', 'items.itemWithTrashed'])
        ->where('user_id', auth()->id());
    if (request('search')) {
        $query->whereHas('itemWithTrashed', fn($q) => $q->where('name', 'like', '%'.request('search').'%'));
    }
    if (request('status')) $query->where('status', request('status'));
    if (request('date_from')) $query->whereDate('borrow_date', '>=', request('date_from'));
    if (request('date_to'))   $query->whereDate('borrow_date', '<=', request('date_to'));
    $histories = $query->latest()->paginate(10);
    $totalAll      = \App\Models\BorrowingRequest::where('user_id', auth()->id())->count();
    // Count returned as either status='returned' OR has approved item return
    $totalReturned = \App\Models\BorrowingRequest::where('user_id', auth()->id())
        ->where(function($q) {
            $q->where('status', 'returned')
              ->orWhereHas('itemReturns', function($returnQ) {
                  $returnQ->where('status', 'disetujui');
              });
        })->count();

    $statusMap = [
        'pending'  => ['label'=>'Menunggu',    'cls'=>'s-badge--pending',  'row'=>'s-loan-row--pending',  'dot'=>'var(--s-pending)'],
        'approved' => ['label'=>'Disetujui',   'cls'=>'s-badge--approved', 'row'=>'s-loan-row--approved', 'dot'=>'var(--s-approved)'],
        'qr_ready' => ['label'=>'Siap Ambil',  'cls'=>'s-badge--approved', 'row'=>'s-loan-row--approved', 'dot'=>'var(--s-approved)'],
        'borrowed' => ['label'=>'Dipinjam',    'cls'=>'s-badge--borrowed', 'row'=>'s-loan-row--borrowed', 'dot'=>'var(--s-borrowed)'],
        'returned' => ['label'=>'Dikembalikan','cls'=>'s-badge--returned', 'row'=>'s-loan-row--returned', 'dot'=>'var(--s-returned)'],
        'rejected' => ['label'=>'Ditolak',     'cls'=>'s-badge--rejected', 'row'=>'s-loan-row--rejected', 'dot'=>'var(--s-rejected)'],
    ];
@endphp

{{-- Page Header --}}
<div class="page-header">
    <div class="page-header-left">
        <div class="page-title">
            Riwayat Peminjaman
            <span class="page-title-count">{{ $totalAll }} total</span>
        </div>
        <div class="page-subtitle">Seluruh catatan transaksi peminjaman barang inventaris kamu</div>
    </div>
    <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
        <div style="display:flex;gap:10px">
            <div style="padding:10px 16px;background:var(--card);border:1px solid var(--border2);border-radius:10px;text-align:center">
                <div style="font-family:var(--font-head);font-size:18px;font-weight:800;color:var(--text)">{{ $totalAll }}</div>
                <div style="font-size:11px;color:var(--muted);margin-top:2px">Total</div>
            </div>
            <div style="padding:10px 16px;background:var(--s-returned-bg);border:1px solid var(--s-returned-bdr);border-radius:10px;text-align:center">
                <div style="font-family:var(--font-head);font-size:18px;font-weight:800;color:var(--s-returned)">{{ $totalReturned }}</div>
                <div style="font-size:11px;color:var(--s-returned);margin-top:2px">Selesai</div>
            </div>
        </div>
    </div>
</div>

{{-- Filter Bar --}}
<div class="s-card s-card--flat" style="margin-bottom:20px">
    <form method="GET" action="{{ route('student.history') }}">
        <div class="s-filter-grid">
            <div class="s-filter-item" style="flex:2;min-width:200px">
                <label class="s-filter-label">Cari Barang</label>
                <div style="position:relative">
                    <svg xmlns="http://www.w3.org/2000/svg" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);width:14px;height:14px;color:var(--subtle)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" class="s-filter-input" style="padding-left:38px" placeholder="Nama barang...">
                </div>
            </div>
            <div class="s-filter-item">
                <label class="s-filter-label">Status</label>
                <select name="status" class="s-filter-input">
                    <option value="">Semua Status</option>
                    <option value="pending"  {{ request('status')==='pending'  ? 'selected' : '' }}>Menunggu</option>
                    <option value="approved" {{ request('status')==='approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="borrowed" {{ request('status')==='borrowed' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="returned" {{ request('status')==='returned' ? 'selected' : '' }}>Dikembalikan</option>
                    <option value="rejected" {{ request('status')==='rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="s-filter-item">
                <label class="s-filter-label">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="s-filter-input">
            </div>
            <div class="s-filter-item">
                <label class="s-filter-label">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="s-filter-input">
            </div>
            <div class="s-filter-item" style="flex:none;justify-content:flex-end;flex-direction:row;align-items:flex-end;gap:8px;min-width:auto">
                <button type="submit" class="s-btn s-btn--primary">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filter
                </button>
                @if(request()->hasAny(['search','status','date_from','date_to']))
                <a href="{{ route('student.history') }}" class="s-btn s-btn--secondary">Reset</a>
                @endif
            </div>
        </div>
    </form>
</div>

{{-- History List --}}
@if($histories->isNotEmpty())
<div class="s-card">
    <div class="s-card-header">
        <div>
            <div class="s-card-title">{{ $histories->total() }} Transaksi Ditemukan</div>
            <div class="s-card-sub">Halaman {{ $histories->currentPage() }} dari {{ $histories->lastPage() }}</div>
        </div>
    </div>

    @foreach($histories as $h)
    @php 
        $st = $statusMap[$h->status] ?? $statusMap['pending']; 
        // Check if truly returned (either status returned OR has approved item return)
        $isReturned = ($h->status === 'returned') || 
                      ($h->itemReturns && $h->itemReturns->isNotEmpty() && $h->itemReturns->first()->status === 'disetujui');
    @endphp
    <div class="s-loan-row {{ $st['row'] }}">
        <div class="s-loan-icon">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:var(--muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <div class="s-loan-content">
            @if($h->items->isNotEmpty())
                {{-- Multi-item display --}}
                <div class="s-loan-name">{{ $h->items->count() }} Barang</div>
                @foreach($h->items as $index => $detail)
                    <div class="s-loan-code" style="margin-bottom: {{ $index < $h->items->count() - 1 ? '4px' : '0' }}">
                        {{ $detail->itemWithTrashed?->name ?? 'Barang tidak tersedia' }} ({{ $detail->quantity }} unit)
                    </div>
                @endforeach
            @else
                {{-- Legacy single-item display --}}
                <div class="s-loan-name">{{ $h->itemWithTrashed?->name ?? 'Barang tidak tersedia' }}</div>
                <div class="s-loan-code">Kode: {{ $h->itemWithTrashed?->code ?? '-' }} · Qty: {{ $h->quantity }} unit</div>
            @endif

            <div class="s-loan-meta" style="margin-top:8px">
                <div class="s-loan-meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ \Carbon\Carbon::parse($h->borrow_date)->format('d M Y') }} – {{ \Carbon\Carbon::parse($h->return_date)->format('d M Y') }}
                </div>
                <div class="s-loan-meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Guru: <span style="font-weight:600;color:var(--text2);margin-left:3px">{{ $h->teacher?->name ?? '-' }}</span>
                </div>
                <div class="s-loan-meta-item">
                    {{ $h->purpose }}
                </div>
            </div>

            {{-- Rejection reason --}}
            @if($h->status === 'rejected' && $h->rejection_reason)
            <div style="margin-top:10px;padding:9px 12px;background:var(--s-rejected-bg);border:1px solid var(--s-rejected-bdr);border-radius:8px;font-size:12px;color:var(--s-rejected)">
                <strong>Ditolak:</strong> {{ $h->rejection_reason }}
            </div>
            @endif

            {{-- Return condition --}}
            @if($h->status === 'returned' && $h->return_condition)
            <div style="margin-top:10px;padding:9px 12px;background:var(--s-returned-bg);border:1px solid var(--s-returned-bdr);border-radius:8px;font-size:12px;color:var(--s-returned)">
                <strong>Kondisi dikembalikan:</strong> {{ ucfirst($h->return_condition) }}
                @if($h->return_notes) — {{ $h->return_notes }} @endif
            </div>
            @endif

            {{-- Timeline dots for returned --}}
            @if($h->status === 'returned')
            <div style="display:flex;flex-wrap:wrap;gap:14px;margin-top:10px;font-size:11px;color:var(--subtle)">
                @if($h->approved_at)
                <span style="display:flex;align-items:center;gap:4px">
                    <span style="width:6px;height:6px;border-radius:50%;background:var(--s-approved);display:inline-block"></span>
                    Disetujui {{ $h->approved_at->format('d M Y') }}
                </span>
                @endif
                @if($h->borrowed_at)
                <span style="display:flex;align-items:center;gap:4px">
                    <span style="width:6px;height:6px;border-radius:50%;background:var(--s-borrowed);display:inline-block"></span>
                    Diambil {{ $h->borrowed_at->format('d M Y') }}
                </span>
                @endif
                @if($h->returned_at)
                <span style="display:flex;align-items:center;gap:4px">
                    <span style="width:6px;height:6px;border-radius:50%;background:var(--s-returned);display:inline-block"></span>
                    Kembali {{ $h->returned_at->format('d M Y') }}
                </span>
                @endif
            </div>
            @endif
        </div>
        <div class="s-loan-right">
            <span class="s-badge {{ $st['cls'] }}">
                <span class="s-badge-dot" style="background:{{ $st['dot'] }}"></span>
                {{ $st['label'] }}
            </span>
            <span class="s-loan-time">{{ $h->created_at->diffForHumans() }}</span>
            <button type="button" 
                    class="btn-detail-link {{ $isReturned ? 'is-returned' : 'is-not-returned' }}"
                    title="{{ $isReturned ? 'Barang sudah dikembalikan (Klik untuk detail)' : 'Barang belum dikembalikan (Klik untuk detail)' }}"
                    onclick="openDetailModal({{ json_encode([
                        'id' => $h->id,
                        'item_name' => $h->items->isNotEmpty() ? $h->items->count() . ' Barang' : ($h->itemWithTrashed?->name ?? ($h->item?->name ?? 'Barang tidak tersedia')),
                        'item_code' => $h->itemWithTrashed?->code ?? ($h->item?->code ?? '-'),
                        'quantity' => $h->quantity,
                        'items' => $h->items->isNotEmpty() ? $h->items->map(fn($item) => [
                            'name' => $item->itemWithTrashed?->name ?? ($item->item?->name ?? 'Barang tidak tersedia'),
                            'code' => $item->itemWithTrashed?->code ?? ($item->item?->code ?? '-'),
                            'quantity' => $item->quantity
                        ])->toArray() : null,
                        'borrow_date' => $h->borrow_date ? $h->borrow_date->format('d F Y') : '-',
                        'return_date' => $h->return_date ? $h->return_date->format('d F Y') : '-',
                        'return_time' => $h->return_time ?? '-',
                        'purpose' => $h->purpose ?? '-',
                        'notes' => $h->notes ?? '-',
                        'status' => $st['label'],
                        'teacher_name' => $h->teacher?->name ?? '-',
                        'approved_at' => $h->approved_at ? $h->approved_at->format('d F Y H:i') : '-',
                        'borrowed_at' => $h->borrowed_at ? $h->borrowed_at->format('d F Y H:i') : '-',
                        'returned_at' => $h->returned_at ? $h->returned_at->format('d F Y H:i') : '-',
                        'return_condition' => $h->return_condition ? ucfirst($h->return_condition) : '-',
                        'return_notes' => $h->return_notes ?? '-',
                        'is_returned' => $isReturned,
                        'rejection_reason' => $h->rejection_reason ?? '-',
                        'item_return_status' => $h->itemReturns && $h->itemReturns->isNotEmpty() ? $h->itemReturns->first()->status : '-',
                        'item_return_verified_by' => $h->itemReturns && $h->itemReturns->isNotEmpty() && $h->itemReturns->first()->verifier ? $h->itemReturns->first()->verifier->name : '-',
                        'item_return_verified_at' => $h->itemReturns && $h->itemReturns->isNotEmpty() && $h->itemReturns->first()->tanggal_verifikasi ? $h->itemReturns->first()->tanggal_verifikasi->format('d F Y H:i') : '-'
                    ]) }})">
                Detail
            </button>
        </div>
    </div>
    @endforeach

    {{-- Pagination --}}
    <div style="padding-top:16px;border-top:1px solid var(--border2);margin-top:8px">
        {{ $histories->appends(request()->query())->links() }}
    </div>
</div>
@else
<div class="s-card">
    <div class="s-empty">
        <div class="s-empty-icon-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:32px;height:32px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <div class="s-empty-title">
            @if(request()->hasAny(['search','status','date_from','date_to']))
                Tidak ada hasil yang sesuai
            @else
                Belum ada riwayat peminjaman
            @endif
        </div>
        <div class="s-empty-sub">
            @if(request()->hasAny(['search','status','date_from','date_to']))
                Coba ubah filter pencarian atau hapus beberapa kriteria
            @else
                Mulai ajukan peminjaman dari katalog barang inventaris sekolah
            @endif
        </div>
        @if(request()->hasAny(['search','status','date_from','date_to']))
            <a href="{{ route('student.history') }}" class="s-btn s-btn--secondary">Reset Filter</a>
        @else
            <a href="{{ route('student.catalog') }}" class="s-btn s-btn--primary">Lihat Katalog Barang</a>
        @endif
    </div>
</div>
@endif

{{-- Detail Modal --}}
<div id="detailModal" class="modal-backdrop">
    <div class="modal-content">
        <div class="modal-header">
            <h3 style="margin:0;font-size:16px;font-weight:700">Detail Peminjaman</h3>
            <button type="button" onclick="closeDetailModal()" style="background:none;border:none;cursor:pointer;padding:4px;border-radius:4px;color:inherit;display:flex;align-items:center;justify-content:center">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <div class="modal-body" id="modalBody">
            <!-- Content will be populated by JavaScript -->
        </div>
    </div>
</div>

<script>
function openDetailModal(data) {
    const modal = document.getElementById('detailModal');
    const modalBody = document.getElementById('modalBody');
    
    let html = `
        <div class="modal-row">
            <span class="modal-label">No. Peminjaman</span>
            <span class="modal-value">#${data.id}</span>
        </div>
        <div class="modal-row">
            <span class="modal-label">Nama Barang</span>
            <span class="modal-value">${data.item_name}</span>
        </div>
    `;
    
    // Display multi-item details if available
    if (data.items && data.items.length > 0) {
        data.items.forEach((item, index) => {
            html += `
            <div class="modal-row">
                <span class="modal-label">Barang ${index + 1}</span>
                <span class="modal-value">${item.name}</span>
            </div>
            <div class="modal-row">
                <span class="modal-label">Kode Barang ${index + 1}</span>
                <span class="modal-value">${item.code}</span>
            </div>
            <div class="modal-row">
                <span class="modal-label">Jumlah ${index + 1}</span>
                <span class="modal-value">${item.quantity} unit</span>
            </div>
            `;
        });
    } else {
        // Legacy single-item display
        html += `
        <div class="modal-row">
            <span class="modal-label">Kode Barang</span>
            <span class="modal-value">${data.item_code}</span>
        </div>
        <div class="modal-row">
            <span class="modal-label">Jumlah</span>
            <span class="modal-value">${data.quantity} unit</span>
        </div>
        `;
    }
    
    html += `
        <div class="modal-row">
            <span class="modal-label">Tanggal Pinjam</span>
            <span class="modal-value">${data.borrow_date}</span>
        </div>
        <div class="modal-row">
            <span class="modal-label">Tanggal Kembali</span>
            <span class="modal-value">${data.return_date}</span>
        </div>
        <div class="modal-row">
            <span class="modal-label">Jam Kembali</span>
            <span class="modal-value">${data.return_time}</span>
        </div>
        <div class="modal-row">
            <span class="modal-label">Keperluan</span>
            <span class="modal-value">${data.purpose}</span>
        </div>
        <div class="modal-row">
            <span class="modal-label">Catatan</span>
            <span class="modal-value">${data.notes}</span>
        </div>
        <div class="modal-row">
            <span class="modal-label">Guru Penanggung Jawab</span>
            <span class="modal-value">${data.teacher_name}</span>
        </div>
        <div class="modal-row">
            <span class="modal-label">Status</span>
            <span class="modal-value">${data.status}</span>
        </div>
    `;
    
    if (data.approved_at && data.approved_at !== '-') {
        html += `
        <div class="modal-row">
            <span class="modal-label">Disetujui Pada</span>
            <span class="modal-value">${data.approved_at}</span>
        </div>
        `;
    }
    
    if (data.borrowed_at && data.borrowed_at !== '-') {
        html += `
        <div class="modal-row">
            <span class="modal-label">Diambil Pada</span>
            <span class="modal-value">${data.borrowed_at}</span>
        </div>
        `;
    }
    
    if (data.is_returned) {
        html += `
        <div class="modal-row">
            <span class="modal-label">Dikembalikan Pada</span>
            <span class="modal-value">${data.returned_at}</span>
        </div>
        <div class="modal-row">
            <span class="modal-label">Kondisi Barang</span>
            <span class="modal-value">${data.return_condition}</span>
        </div>
        <div class="modal-row">
            <span class="modal-label">Catatan Pengembalian</span>
            <span class="modal-value">${data.return_notes}</span>
        </div>
        `;
        
        // Add item return verification info if available
        if (data.item_return_status && data.item_return_status !== '-') {
            html += `
            <div class="modal-row">
                <span class="modal-label">Status Pengembalian</span>
                <span class="modal-value">${data.item_return_status === 'disetujui' ? 'Disetujui' : ucfirst(data.item_return_status)}</span>
            </div>
            `;
        }
        
        if (data.item_return_verified_by && data.item_return_verified_by !== '-') {
            html += `
            <div class="modal-row">
                <span class="modal-label">Diverifikasi Oleh</span>
                <span class="modal-value">${data.item_return_verified_by}</span>
            </div>
            `;
        }
        
        if (data.item_return_verified_at && data.item_return_verified_at !== '-') {
            html += `
            <div class="modal-row">
                <span class="modal-label">Waktu Verifikasi</span>
                <span class="modal-value">${data.item_return_verified_at}</span>
            </div>
            `;
        }
    }
    
    if (data.rejection_reason && data.rejection_reason !== '-') {
        html += `
        <div class="modal-row">
            <span class="modal-label">Alasan Penolakan</span>
            <span class="modal-value" style="color:#dc2626">${data.rejection_reason}</span>
        </div>
        `;
    }
    
    modalBody.innerHTML = html;
    modal.classList.add('active');
}

function closeDetailModal() {
    const modal = document.getElementById('detailModal');
    modal.classList.remove('active');
}

// Close modal when clicking outside
document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDetailModal();
    }
});
</script>
@endsection
