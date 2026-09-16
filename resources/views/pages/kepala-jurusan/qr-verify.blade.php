@extends('layouts.kepala-jurusan')

@section('title', 'Verifikasi QR')
@section('page-heading', 'Verifikasi QR')

@section('content')
<style>
    .verify-card {
        max-width: 600px;
        margin: 20px auto;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 28px 24px;
        box-shadow: 0 2px 8px rgba(133, 30, 42, 0.05), 0 1px 3px rgba(0,0,0,0.03);
        color: var(--text);
    }
    .verify-header {
        text-align: center;
        margin-bottom: 24px;
    }
    .verify-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }
    .verify-icon.success {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }
    html.dark .verify-icon.success { color: #34d399; }
    .verify-icon.error {
        background: var(--accent-light);
        color: var(--accent);
    }
    .verify-title {
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 8px;
        color: var(--text);
    }
    .verify-message {
        font-size: 14px;
        color: var(--muted);
        margin: 0;
    }
    
    .borrowing-details {
        background: #fdf6f7;
        border: 1px solid #f2dbe0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f2dbe0;
    }
    .detail-row:last-child {
        border-bottom: none;
    }
    .detail-label {
        font-size: 13px;
        color: #851e2a;
        font-weight: 700;
    }
    .detail-value {
        font-size: 13px;
        color: #0f172a;
        font-weight: 700;
        text-align: right;
    }
    
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.2s ease;
        text-decoration: none;
        width: 100%;
    }
    .btn-confirm {
        background: var(--accent);
        color: #ffffff;
    }
    .btn-confirm:hover {
        background: var(--accent-hover);
    }
    .btn-back {
        background: var(--bg3);
        color: var(--text);
        border: 1px solid var(--border);
    }
    .btn-back:hover {
        background: var(--border);
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
    .status-approved { background: rgba(16, 185, 129, 0.12); color: #10b981; }
    .status-borrowed { background: var(--accent-light); color: var(--accent-text); }
</style>

<div class="verify-card">
    @if($valid)
        <div class="verify-header">
            <div class="verify-icon success">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:32px;height:32px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h2 class="verify-title">QR Code Valid</h2>
            <p class="verify-message">Permohonan peminjaman ditemukan dalam sistem</p>
        </div>
        
        <div class="borrowing-details">
            <div class="detail-row">
                <span class="detail-label">Guru Peminjam</span>
                <span class="detail-value">{{ $borrowingRequest->user->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Barang</span>
                <span class="detail-value">
                    @php $items = $borrowingRequest->items->count() ? $borrowingRequest->items : collect([$borrowingRequest->item])->filter(); @endphp
                    @foreach($items as $detail)
                        {{ $detail->itemWithTrashed?->name ?? $detail->item?->name ?? 'Barang tidak tersedia' }} ({{ $detail->quantity ?? 1 }}){{ !$loop->last ? ', ' : '' }}
                    @endforeach
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Jumlah</span>
                <span class="detail-value">{{ $borrowingRequest->items->sum('quantity') ?: ($borrowingRequest->quantity ?? 0) }} unit</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Tanggal Pinjam</span>
                <span class="detail-value">{{ $borrowingRequest->borrow_date->format('d/m/Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Tanggal Kembali</span>
                <span class="detail-value">{{ $borrowingRequest->return_date->format('d/m/Y') }} {{ $borrowingRequest->return_time }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Tujuan</span>
                <span class="detail-value">{{ $borrowingRequest->purpose }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status Saat Ini</span>
                <span class="detail-value">
                    @if($borrowingRequest->status === 'approved')
                        <span class="status-badge status-approved">Disetujui</span>
                    @elseif($borrowingRequest->status === 'borrowed')
                        <span class="status-badge status-borrowed">Dipinjam</span>
                    @else
                        <span class="status-badge">{{ ucfirst($borrowingRequest->status) }}</span>
                    @endif
                </span>
            </div>
        </div>
        
        @if(in_array($borrowingRequest->status, ['approved', 'qr_ready']))
            <form method="POST" action="{{ route('kajur.qr.confirm-checkout', $borrowingRequest->id) }}">
                @csrf
                <button type="submit" class="action-btn btn-confirm" onclick="return confirm('Konfirmasi pengambilan barang oleh guru?')">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Konfirmasi Pengambilan Barang
                </button>
            </form>
        @elseif($borrowingRequest->status === 'borrowed')
            <div style="text-align: center; padding: 16px; background: var(--accent-light); border-radius: 10px; color: var(--accent-text); font-size: 13px; font-weight: 600;">
                Barang sudah diambil oleh guru pada {{ $borrowingRequest->borrowed_at->format('d/m/Y H:i') }}
            </div>
        @else
            <div style="text-align: center; padding: 16px; background: rgba(245, 158, 11, 0.12); border-radius: 10px; color: #f59e0b; font-size: 13px; font-weight: 600;">
                Status permohonan saat ini tidak memungkinkan konfirmasi pengambilan
            </div>
        @endif
        
        <div style="margin-top: 16px;">
            <a href="{{ route('kajur.qr-scanner') }}" class="action-btn btn-back">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Scanner
            </a>
        </div>
    @else
        <div class="verify-header">
            <div class="verify-icon error">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:32px;height:32px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <h2 class="verify-title">QR Code Tidak Valid</h2>
            <p class="verify-message">{{ $message }}</p>
        </div>
        
        <div style="margin-top: 16px;">
            <a href="{{ route('kajur.qr-scanner') }}" class="action-btn btn-back">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Scanner
            </a>
        </div>
    @endif
</div>
@endsection