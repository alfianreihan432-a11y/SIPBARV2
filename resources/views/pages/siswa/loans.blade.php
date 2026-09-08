@extends('layouts.siswa')

@section('title', 'Peminjaman Saya – SIPBAR')

@section('content')
@php
    $requests = \App\Models\BorrowingRequest::with(['itemWithTrashed', 'qrCode'])
        ->where('user_id', auth()->id())
        ->latest()
        ->get();
    $counts = [
        'pending'   => $requests->whereIn('status', ['pending'])->count(),
        'approved'  => $requests->whereIn('status', ['approved', 'qr_ready'])->count(),
        'borrowed'  => $requests->whereIn('status', ['borrowed'])->count(),
        'returned'  => $requests->where('status', 'returned')->count(),
        'rejected'  => $requests->where('status', 'rejected')->count(),
    ];
    $statusMap = [
        'pending'   => ['label'=>'Menunggu',    'cls'=>'s-badge--pending',  'row'=>'s-loan-row--pending',  'dot'=>'var(--s-pending)'],
        'cancelled' => ['label'=>'Dibatalkan',  'cls'=>'s-badge--returned', 'row'=>'s-loan-row--returned', 'dot'=>'var(--s-returned)'],
        'approved'  => ['label'=>'Disetujui',   'cls'=>'s-badge--approved', 'row'=>'s-loan-row--approved', 'dot'=>'var(--s-approved)'],
        'qr_ready'  => ['label'=>'Siap Ambil',  'cls'=>'s-badge--approved', 'row'=>'s-loan-row--approved', 'dot'=>'var(--s-approved)'],
        'borrowed'  => ['label'=>'Dipinjam',    'cls'=>'s-badge--borrowed', 'row'=>'s-loan-row--borrowed', 'dot'=>'var(--s-borrowed)'],
        'returned'  => ['label'=>'Dikembalikan','cls'=>'s-badge--returned', 'row'=>'s-loan-row--returned', 'dot'=>'var(--s-returned)'],
        'rejected'  => ['label'=>'Ditolak',     'cls'=>'s-badge--rejected', 'row'=>'s-loan-row--rejected', 'dot'=>'var(--s-rejected)'],
    ];
@endphp

{{-- QR Code Modal --}}
<div id="qr-modal-overlay" style="position:fixed;inset:0;background:rgba(0,0,0,.6);backdrop-filter:blur(4px);z-index:1000;display:flex;align-items:center;justify-content:center;padding:20px;opacity:0;pointer-events:none;transition:opacity .25s ease">
    <div id="qr-modal" style="background:var(--card);border:1px solid var(--border2);border-radius:20px;padding:28px 24px;max-width:380px;width:100%;text-align:center;transform:scale(.94) translateY(12px);transition:transform .28s cubic-bezier(.34,1.56,.64,1),opacity .25s;opacity:0;position:relative">
        <button onclick="closeQRModal()" style="position:absolute;top:14px;right:14px;background:var(--bg3);border:1px solid var(--border2);border-radius:8px;width:30px;height:30px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--muted)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div style="font-family:var(--font-head);font-size:16px;font-weight:800;color:var(--text);margin-bottom:4px">QR Code Peminjaman</div>
        <div style="font-size:12px;color:var(--muted);margin-bottom:20px">Tunjukkan kepada petugas saat mengambil barang</div>
        <div id="qr-spinner" style="width:40px;height:40px;border:3px solid var(--border2);border-top-color:var(--primary);border-radius:50%;animation:qr-spin .7s linear infinite;margin:40px auto"></div>
        <div id="qr-error" style="display:none;color:var(--s-rejected);font-size:13px;padding:16px;background:var(--s-rejected-bg);border-radius:10px;margin-bottom:12px"></div>
        <div id="qr-img-wrap" style="display:none;width:260px;height:260px;margin:0 auto 16px;border-radius:14px;border:2px solid var(--border2);overflow:hidden;background:#fff">
            <img id="qr-img" src="" alt="QR Code" style="width:100%;height:100%;object-fit:contain">
        </div>
        <div id="qr-item-name" style="font-family:var(--font-head);font-size:15px;font-weight:700;color:var(--text);margin-bottom:4px"></div>
        <div id="qr-token" style="font-size:11px;color:var(--muted);font-family:monospace;background:var(--bg3);border-radius:6px;padding:4px 10px;display:inline-block;margin-bottom:14px;letter-spacing:.04em"></div>
        <div style="font-size:12px;color:var(--muted);line-height:1.6;background:var(--primary-light);border:1px solid var(--primary-muted);border-radius:10px;padding:10px 14px;margin-bottom:12px;text-align:left">
            Petugas inventaris akan men-scan QR Code ini untuk konfirmasi pengambilan barang.
        </div>
        <div id="qr-expires" style="font-size:11px;color:var(--subtle)"></div>
    </div>
</div>
<style>@keyframes qr-spin{to{transform:rotate(360deg)}}</style>

{{-- Page Header --}}
<div class="page-header">
    <div class="page-header-left">
        <div class="page-title">
            Peminjaman Saya
            @if($requests->count() > 0)
            <span class="page-title-count">{{ $requests->count() }} transaksi</span>
            @endif
        </div>
        <div class="page-subtitle">Semua permohonan peminjaman barang yang pernah kamu ajukan</div>
    </div>
    <a href="{{ route('student.catalog') }}" class="s-btn s-btn--primary">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        Pinjam Baru
    </a>
</div>

{{-- Summary Stats --}}
<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:22px" class="loans-stat-grid">
    <div class="s-stat" style="padding:14px 16px">
        <div class="s-stat-icon" style="width:38px;height:38px;background:rgba(217,119,6,.1)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#d97706" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="s-stat-body">
            <div class="s-stat-num" style="font-size:20px">{{ $counts['pending'] }}</div>
            <div class="s-stat-label">Menunggu</div>
        </div>
    </div>
    <div class="s-stat" style="padding:14px 16px">
        <div class="s-stat-icon" style="width:38px;height:38px;background:rgba(16,185,129,.1)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#10b981" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="s-stat-body">
            <div class="s-stat-num" style="font-size:20px">{{ $counts['approved'] }}</div>
            <div class="s-stat-label">Disetujui</div>
        </div>
    </div>
    <div class="s-stat" style="padding:14px 16px">
        <div class="s-stat-icon" style="width:38px;height:38px;background:rgba(8,145,178,.1)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#0891b2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
        </div>
        <div class="s-stat-body">
            <div class="s-stat-num" style="font-size:20px">{{ $counts['borrowed'] }}</div>
            <div class="s-stat-label">Dipinjam</div>
        </div>
    </div>
    <div class="s-stat" style="padding:14px 16px">
        <div class="s-stat-icon" style="width:38px;height:38px;background:rgba(5,150,105,.1)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#059669" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <div class="s-stat-body">
            <div class="s-stat-num" style="font-size:20px">{{ $counts['returned'] }}</div>
            <div class="s-stat-label">Selesai</div>
        </div>
    </div>
    <div class="s-stat" style="padding:14px 16px">
        <div class="s-stat-icon" style="width:38px;height:38px;background:rgba(220,38,38,.1)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#dc2626" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="s-stat-body">
            <div class="s-stat-num" style="font-size:20px">{{ $counts['rejected'] }}</div>
            <div class="s-stat-label">Ditolak</div>
        </div>
    </div>
</div>
<style>
    @media(max-width:992px){.loans-stat-grid{grid-template-columns:repeat(3,1fr)!important}}
    @media(max-width:768px){.loans-stat-grid{grid-template-columns:repeat(2,1fr)!important}}
    @media(max-width:480px){.loans-stat-grid{grid-template-columns:1fr!important}}
</style>

{{-- Loan List --}}
<div class="s-card">
    <div class="s-card-header">
        <div>
            <div class="s-card-title">Daftar Permohonan</div>
            <div class="s-card-sub">Diurutkan dari yang terbaru</div>
        </div>
        <a href="{{ route('student.history') }}" class="s-card-action">Riwayat Lengkap →</a>
    </div>

    @if($requests->count() > 0)
        @foreach($requests as $req)
        @php $st = $statusMap[$req->status] ?? $statusMap['pending']; @endphp
        <div class="s-loan-row {{ $st['row'] }}">
            <div class="s-loan-icon">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:var(--muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div class="s-loan-content">
                <div class="s-loan-name">{{ $req->itemWithTrashed?->name ?? $req->item?->name ?? 'Barang tidak tersedia' }}</div>
                <div class="s-loan-code">ID: #{{ $req->id }} · Qty: {{ $req->quantity }} unit</div>
                <div class="s-loan-meta">
                    <div class="s-loan-meta-item">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $req->borrow_date->format('d M Y') }} – {{ $req->return_date->format('d M Y') }}
                    </div>
                    @if($req->purpose)
                    <div class="s-loan-meta-item" style="color:var(--subtle)">
                        {{ Str::limit($req->purpose, 40) }}
                    </div>
                    @endif
                </div>
                @if($req->status === 'pending')
                    @php
                        $waService = app(\App\Services\WhatsAppNotificationService::class);
                        $shareLink = $waService->getDirectWaLink($req);
                        $approvalUrl = $waService->getApprovalUrl($req);
                    @endphp
                    <div style="margin-top:10px;display:flex;gap:8px;flex-wrap:wrap">
                        <a href="{{ route('student.loans.edit', $req->id) }}" class="s-btn s-btn--sm s-btn--ghost">Edit</a>
                        <a href="{{ $shareLink }}" target="_blank" rel="noopener" class="s-btn s-btn--sm s-btn--primary">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l.7-3.305A7.93 7.93 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            Kirim ke WA Guru
                        </a>
                        <button type="button" onclick="navigator.clipboard.writeText('{{ $approvalUrl }}'); var btn=this; btn.textContent='Tersalin!'; setTimeout(function(){ btn.textContent='Salin Link'; }, 2000);" class="s-btn s-btn--sm s-btn--ghost">
                            Salin Link
                        </button>
                        <form method="POST" action="{{ route('student.loans.cancel', $req->id) }}" onsubmit="return confirm('Yakin ingin membatalkan peminjaman ini?')" style="display:inline;">
                            @csrf
                            <button type="submit" class="s-btn s-btn--sm s-btn--danger">
                                Batalkan
                            </button>
                        </form>
                    </div>
                @elseif(in_array($req->status, ['approved', 'qr_ready']))
                    <div style="margin-top:10px;display:flex;gap:8px;flex-wrap:wrap">
                        <button type="button" onclick="openQRModal({{ $req->id }}, '{{ addslashes($req->itemWithTrashed?->name ?? $req->item?->name ?? 'Barang') }}')" class="s-btn s-btn--sm s-btn--primary">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-5v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V8a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1zm12 0h2a1 1 0 001-1V8a1 1 0 00-1-1h-2a1 1 0 00-1 1v1a1 1 0 001 1zM5 20h2a1 1 0 001-1v-1a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1z"/></svg>
                            Lihat QR Code
                        </button>
                    </div>
                @elseif($req->status === 'rejected' && $req->rejection_reason)
                <div style="margin-top:8px;padding:8px 12px;background:var(--s-rejected-bg);border:1px solid var(--s-rejected-bdr);border-radius:8px;font-size:12px;color:var(--s-rejected)">
                    <strong>Alasan ditolak:</strong> {{ $req->rejection_reason }}
                </div>
                @endif
            </div>
            <div class="s-loan-right">
                <span class="s-badge {{ $st['cls'] }}">
                    <span class="s-badge-dot" style="background:{{ $st['dot'] }}"></span>
                    {{ $st['label'] }}
                </span>
                <span class="s-loan-time">{{ $req->created_at->diffForHumans() }}</span>
            </div>
        </div>
        @endforeach
    @else
        <div class="s-empty">
            <div class="s-empty-icon-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:32px;height:32px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            </div>
            <div class="s-empty-title">Belum ada peminjaman</div>
            <div class="s-empty-sub">Kunjungi katalog untuk menemukan barang yang ingin kamu pinjam dari inventaris sekolah</div>
            <a href="{{ route('student.catalog') }}" class="s-btn s-btn--primary">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Lihat Katalog Barang
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
var qrOverlay  = document.getElementById('qr-modal-overlay');
var qrModal    = document.getElementById('qr-modal');
var qrImgWrap  = document.getElementById('qr-img-wrap');
var qrImgEl    = document.getElementById('qr-img');
var qrItemName = document.getElementById('qr-item-name');
var qrToken    = document.getElementById('qr-token');
var qrExpires  = document.getElementById('qr-expires');
var qrSpinner  = document.getElementById('qr-spinner');
var qrError    = document.getElementById('qr-error');

function openQRModal(borrowingId, itemName) {
    if (!qrOverlay || !qrModal) return;
    qrImgWrap.style.display = 'none';
    qrSpinner.style.display = 'block';
    qrError.style.display   = 'none';
    qrItemName.textContent  = itemName;
    qrToken.textContent     = '';
    qrExpires.textContent   = '';

    qrOverlay.style.opacity        = '1';
    qrOverlay.style.pointerEvents  = 'all';
    qrModal.style.opacity           = '1';
    qrModal.style.transform         = 'scale(1) translateY(0)';
    document.body.style.overflow    = 'hidden';

    fetch('/siswa/peminjaman/' + borrowingId + '/qrcode', {
        headers: {'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}
    })
    .then(function(r){return r.json();})
    .then(function(data) {
        qrSpinner.style.display = 'none';
        if(data.success) {
            qrImgEl.src = data.qr_image;
            qrImgWrap.style.display = 'flex';
            qrToken.textContent = '#' + data.borrowing_id + ' · ' + data.token.substring(0,8).toUpperCase() + '...';
            qrExpires.textContent = data.expires_at ? 'Berlaku hingga: ' + data.expires_at : '';
        } else {
            qrError.style.display = 'block';
            qrError.textContent = data.message || 'Gagal memuat QR Code.';
        }
    })
    .catch(function() {
        qrSpinner.style.display = 'none';
        qrError.style.display = 'block';
        qrError.textContent = 'Koneksi gagal. Coba lagi beberapa saat.';
    });
}

function closeQRModal() {
    if (!qrOverlay || !qrModal) return;
    qrOverlay.style.opacity       = '0';
    qrOverlay.style.pointerEvents = 'none';
    qrModal.style.opacity          = '0';
    qrModal.style.transform        = 'scale(.94) translateY(12px)';
    document.body.style.overflow   = '';
}

if (qrOverlay) {
    qrOverlay.addEventListener('click', function(e) {
        if(e.target === qrOverlay) closeQRModal();
    });
}
document.addEventListener('keydown', function(e) {
    if(e.key === 'Escape') closeQRModal();
});
</script>
@endpush
