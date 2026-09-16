@extends('layouts.guru')

@section('title', 'QR Code Peminjaman')
@section('page-heading', 'QR Code Peminjaman')

@section('content')
<style>
    .qr-container {
        max-width: 600px;
        margin: 20px auto;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 32px 24px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        color: var(--text);
    }

    .qr-header {
        text-align: center;
        margin-bottom: 24px;
    }

    .qr-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 8px;
    }

    .qr-subtitle {
        font-size: 14px;
        color: var(--muted);
    }

    .qr-card {
        background: var(--bg3);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
    }

    .qr-image-container {
        width: 300px;
        height: 300px;
        margin: 0 auto 20px;
        background: #ffffff;
        border-radius: 12px;
        border: 2px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .qr-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .qr-spinner {
        width: 40px;
        height: 40px;
        border: 3px solid var(--border);
        border-top-color: var(--accent);
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .loan-details {
        background: var(--bg3);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid var(--border);
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-size: 13px;
        color: var(--muted);
        font-weight: 500;
    }

    .detail-value {
        font-size: 14px;
        color: var(--text);
        font-weight: 600;
        text-align: right;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .status-waiting {
        background: rgba(217, 119, 6, 0.12);
        color: #d97706;
    }

    .status-ready {
        background: rgba(16, 185, 129, 0.12);
        color: #10b981;
    }

    .action-buttons {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 24px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 20px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        border: none;
    }

    .btn-primary {
        background: var(--accent);
        color: #ffffff;
    }

    .btn-primary:hover {
        background: #059669;
    }

    .btn-secondary {
        background: var(--bg3);
        color: var(--text);
        border: 1px solid var(--border);
    }

    .btn-secondary:hover {
        background: var(--border);
    }

    .info-box {
        background: rgba(16, 185, 129, 0.08);
        border: 1px solid rgba(16, 185, 129, 0.2);
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 20px;
        font-size: 13px;
        color: var(--text);
        line-height: 1.6;
    }

    .token-display {
        font-family: monospace;
        font-size: 11px;
        color: var(--muted);
        background: var(--bg3);
        border-radius: 6px;
        padding: 6px 10px;
        display: inline-block;
        margin-top: 8px;
        letter-spacing: 0.04em;
    }
</style>

<div class="qr-container">
    <div class="qr-header">
        <h1 class="qr-title">QR Code Peminjaman</h1>
        <p class="qr-subtitle">Tunjukkan QR Code ini kepada Kepala Jurusan untuk pengambilan barang</p>
    </div>

    <div class="info-box">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#10b981;margin-right:8px;vertical-align:text-bottom" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Kepala Jurusan akan men-scan QR Code ini untuk konfirmasi persetujuan final dan serah terima barang.
    </div>

    <div class="qr-card">
        <div class="qr-image-container">
            <div id="qr-spinner" class="qr-spinner"></div>
            <img id="qr-image" class="qr-image" src="" alt="QR Code" style="display: none;">
        </div>
        <div id="qr-token" class="token-display" style="display: none;"></div>
    </div>

    <div class="loan-details">
        <div class="detail-row">
            <span class="detail-label">Nama Barang</span>
            <span class="detail-value">
                @php $items = $borrowing->items->count() ? $borrowing->items : collect([$borrowing->item])->filter(); @endphp
                @foreach($items as $detail)
                    {{ $detail->itemWithTrashed?->name ?? $detail->item?->name ?? 'Barang tidak tersedia' }} ({{ $detail->quantity ?? $borrowing->quantity ?? 1 }}){{ !$loop->last ? ', ' : '' }}
                @endforeach
            </span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Jumlah</span>
            <span class="detail-value">{{ $borrowing->items->sum('quantity') ?: ($borrowing->quantity ?? 0) }} unit</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Tanggal Pinjam</span>
            <span class="detail-value">{{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Tanggal Kembali</span>
            <span class="detail-value">{{ \Carbon\Carbon::parse($borrowing->return_date)->format('d M Y') }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Jam Kembali</span>
            <span class="detail-value">{{ $borrowing->return_time }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Kepala Jurusan</span>
            <span class="detail-value">{{ $borrowing->approvedByKajur->name ?? 'Belum ditentukan' }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Status</span>
            <span class="detail-value">
                @if($borrowing->status === 'approved')
                    <span class="status-badge status-waiting">Menunggu Scan Kajur</span>
                @elseif($borrowing->status === 'qr_ready')
                    <span class="status-badge status-ready">Siap Diambil</span>
                @elseif($borrowing->status === 'borrowed')
                    <span class="status-badge status-ready">Sedang Dipinjam</span>
                @else
                    <span class="status-badge">{{ $borrowing->status }}</span>
                @endif
            </span>
        </div>
    </div>

    <div class="action-buttons">
        <a href="{{ route('teacher.peminjaman-guru') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
        <button onclick="downloadQR()" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Download QR
        </button>
    </div>
</div>

<script>
const borrowingId = {{ $borrowing->id }};

// Load QR Code on page load
document.addEventListener('DOMContentLoaded', function() {
    loadQRCode();
});

function loadQRCode() {
    fetch(`{{ route('teacher.qr.generate', ['id' => $borrowing->id]) }}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('qr-spinner').style.display = 'none';
            document.getElementById('qr-image').src = data.qr_image;
            document.getElementById('qr-image').style.display = 'block';
            document.getElementById('qr-token').textContent = 'Token: ' + data.token;
            document.getElementById('qr-token').style.display = 'inline-block';
        } else {
            document.getElementById('qr-spinner').style.display = 'none';
            alert('Gagal memuat QR Code: ' + (data.message || 'Terjadi kesalahan'));
        }
    })
    .catch(error => {
        document.getElementById('qr-spinner').style.display = 'none';
        console.error('Error loading QR code:', error);
        alert('Gagal memuat QR Code. Silakan refresh halaman.');
    });
}

function downloadQR() {
    const qrImage = document.getElementById('qr-image');
    if (qrImage && qrImage.src) {
        const link = document.createElement('a');
        link.href = qrImage.src;
        link.download = 'qr-peminjaman-{{ $borrowing->id }}.png';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    } else {
        alert('QR Code belum dimuat. Silakan tunggu sebentar.');
    }
}
</script>
@endsection
