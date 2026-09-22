@extends('layouts.guru')

@section('title', 'QR Barang – Panel Guru')
@section('page-heading', 'QR Barang')

@section('content')
@php
    $approvedCount = $activeQrLoans->whereIn('status', ['approved', 'qr_ready'])->count();
    $borrowedCount = $activeQrLoans->where('status', 'borrowed')->count();
@endphp

{{-- QR Code Modal --}}
<div id="qr-modal-overlay" style="position:fixed;inset:0;background:rgba(0,0,0,.6);backdrop-filter:blur(4px);z-index:1000;display:flex;align-items:center;justify-content:center;padding:20px;opacity:0;pointer-events:none;transition:opacity .25s ease">
    <div id="qr-modal" style="background:var(--card);border:1px solid var(--border);border-radius:20px;padding:28px 24px;max-width:380px;width:100%;text-align:center;transform:scale(.94) translateY(12px);transition:transform .28s cubic-bezier(.34,1.56,.64,1),opacity .25s;opacity:0;position:relative;box-shadow:0 20px 40px rgba(0,0,0,0.15)">
        <button onclick="closeQRModal()" style="position:absolute;top:14px;right:14px;background:var(--bg3);border:1px solid var(--border);border-radius:8px;width:30px;height:30px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--muted);transition:all .15s">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div style="font-size:16px;font-weight:800;color:var(--text);margin-bottom:4px">QR Code Pengambilan</div>
        <div style="font-size:12px;color:var(--muted);margin-bottom:18px">Tunjukkan ke Kepala Jurusan saat mengambil barang</div>
        
        <div id="qr-spinner" style="width:40px;height:40px;border:3px solid var(--border);border-top-color:var(--accent);border-radius:50%;animation:qr-spin .7s linear infinite;margin:40px auto"></div>
        <div id="qr-error" style="display:none;color:#dc2626;font-size:13px;padding:14px;background:rgba(220,38,38,0.1);border-radius:10px;margin-bottom:12px"></div>
        
        <div id="qr-img-wrap" style="display:none;width:240px;height:240px;margin:0 auto 16px;border-radius:14px;border:2px solid var(--border);overflow:hidden;background:#fff;padding:8px">
            <img id="qr-img" src="" alt="QR Code" style="width:100%;height:100%;object-fit:contain">
        </div>
        
        <div id="qr-item-name" style="font-size:15px;font-weight:700;color:var(--text);margin-bottom:4px"></div>
        <div id="qr-token" style="font-size:11.5px;color:var(--muted);font-family:monospace;background:var(--bg3);border-radius:6px;padding:4px 10px;display:inline-block;margin-bottom:14px;letter-spacing:.04em"></div>
        
        <div style="font-size:12px;color:var(--muted);line-height:1.5;background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.2);border-radius:10px;padding:10px 14px;margin-bottom:12px;text-align:left">
            Kepala Jurusan akan memindai QR Code ini untuk konfirmasi serah terima barang inventaris.
        </div>
        <div id="qr-expires" style="font-size:11px;color:var(--subtle)"></div>
    </div>
</div>
<style>@keyframes qr-spin{to{transform:rotate(360deg)}}</style>

<style>
    .qr-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
        flex-wrap: wrap;
        gap: 14px;
    }
    .qr-page-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .qr-page-sub {
        font-size: 13px;
        color: var(--muted);
        margin-top: 2px;
    }
    .qr-stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 14px;
        margin-bottom: 22px;
    }
    .qr-stat-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }
    .qr-stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .qr-stat-num {
        font-size: 22px;
        font-weight: 800;
        color: var(--text);
        line-height: 1.1;
    }
    .qr-stat-label {
        font-size: 12px;
        color: var(--muted);
        font-weight: 500;
        margin-top: 2px;
    }

    .qr-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 22px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }
    .qr-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
        flex-wrap: wrap;
        gap: 10px;
    }
    .qr-card-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .qr-card-title::before {
        content: '';
        width: 3.5px;
        height: 16px;
        background: var(--accent);
        border-radius: 2px;
        display: inline-block;
    }

    .qr-list-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 16px;
    }
    .qr-item-box {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 18px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform .18s, box-shadow .18s;
    }
    .qr-item-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.06);
    }
    .qr-item-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 12px;
    }
    .qr-item-name {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        line-height: 1.3;
    }
    .qr-item-code {
        font-size: 11.5px;
        color: var(--muted);
        margin-top: 2px;
    }
    .qr-badge {
        font-size: 10.5px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        white-space: nowrap;
    }
    .qr-badge-approved { background: var(--s-returned, #059669); color: #fff; }
    .qr-badge-borrowed { background: var(--s-approved, #2563eb); color: #fff; }

    .qr-item-meta {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 10px 12px;
        margin-bottom: 14px;
        font-size: 12px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .qr-meta-row {
        display: flex;
        justify-content: space-between;
        color: var(--muted);
    }
    .qr-meta-row strong {
        color: var(--text);
        font-weight: 600;
    }

    .qr-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 10px 14px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all .15s;
        border: none;
        width: 100%;
    }
    .qr-btn-primary {
        background: var(--accent);
        color: #ffffff;
    }
    .qr-btn-primary:hover {
        background: #059669;
    }
    .qr-btn-outline {
        background: var(--card);
        color: var(--text);
        border: 1px solid var(--border);
    }
    .qr-btn-outline:hover {
        background: var(--bg3);
    }

    .qr-empty-state {
        text-align: center;
        padding: 50px 20px;
        color: var(--muted);
    }
    .qr-empty-state svg {
        width: 52px;
        height: 52px;
        margin-bottom: 14px;
        opacity: 0.4;
    }
</style>

{{-- Page Header --}}
<div class="qr-page-header">
    <div>
        <div class="qr-page-title">
            QR Barang Peminjaman
            @if($activeQrLoans->count() > 0)
                <span style="font-size:12px;font-weight:700;background:rgba(16,185,129,0.12);color:#059669;padding:3px 10px;border-radius:999px">{{ $activeQrLoans->count() }} QR Aktif</span>
            @endif
        </div>
        <div class="qr-page-sub">Daftar QR Code aktif untuk konfirmasi pengambilan barang yang telah disetujui Kepala Jurusan</div>
    </div>
    <a href="{{ route('teacher.peminjaman-guru') }}" class="qr-btn qr-btn-outline" style="width:auto">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Daftar Permohonan
    </a>
</div>

{{-- Stats Summary --}}
<div class="qr-stat-grid">
    <div class="qr-stat-card">
        <div class="qr-stat-icon" style="background:rgba(16,185,129,0.12);color:#059669">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <div class="qr-stat-num">{{ $approvedCount }}</div>
            <div class="qr-stat-label">Siap Diambil (Disetujui)</div>
        </div>
    </div>
    <div class="qr-stat-card">
        <div class="qr-stat-icon" style="background:rgba(59,130,246,0.12);color:#2563eb">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <div>
            <div class="qr-stat-num">{{ $borrowedCount }}</div>
            <div class="qr-stat-label">Sedang Dipinjam</div>
        </div>
    </div>
</div>

{{-- QR Code Cards --}}
<div class="qr-card">
    <div class="qr-card-header">
        <div>
            <div class="qr-card-title">Daftar QR Code Aktif</div>
            <div style="font-size:12.5px;color:var(--muted);margin-top:2px">Klik "Tampilkan QR Code" lalu tunjukkan kepada Kepala Jurusan untuk scan</div>
        </div>
    </div>

    @if($activeQrLoans->count() > 0)
        <div class="qr-list-grid">
            @foreach($activeQrLoans as $loan)
                @php
                    $itemName = $loan->itemWithTrashed?->name ?? $loan->item?->name ?? 'Barang Inventaris';
                    $isApproved = in_array($loan->status, ['approved', 'qr_ready']);
                @endphp
                <div class="qr-item-box">
                    <div>
                        <div class="qr-item-head">
                            <div>
                                <div class="qr-item-name">{{ $itemName }}</div>
                                <div class="qr-item-code">ID Transaksi: #{{ $loan->id }} · {{ $loan->quantity }} Unit</div>
                            </div>
                            <span class="qr-badge {{ $isApproved ? 'qr-badge-approved' : 'qr-badge-borrowed' }}">
                                {{ $isApproved ? 'Siap Ambil' : 'Dipinjam' }}
                            </span>
                        </div>

                        <div class="qr-item-meta">
                            <div class="qr-meta-row">
                                <span>Tgl Pinjam:</span>
                                <strong>{{ $loan->borrow_date ? $loan->borrow_date->format('d/m/Y') : '-' }}</strong>
                            </div>
                            <div class="qr-meta-row">
                                <span>Tgl Kembali:</span>
                                <strong>{{ $loan->return_date ? $loan->return_date->format('d/m/Y') : '-' }} {{ $loan->return_time }}</strong>
                            </div>
                            <div class="qr-meta-row">
                                <span>Penyetuju:</span>
                                <strong>{{ $loan->approvedByKajur?->name ?? 'Kepala Jurusan' }}</strong>
                            </div>
                            @if($loan->purpose)
                            <div class="qr-meta-row" style="margin-top:2px">
                                <span>Keperluan:</span>
                                <strong style="text-align:right;max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $loan->purpose }}</strong>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div style="display:flex;gap:8px;flex-direction:column">
                        <button type="button" onclick="openQRModal({{ $loan->id }}, '{{ addslashes($itemName) }}')" class="qr-btn qr-btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            Tampilkan QR Code
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="qr-empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            <div style="font-size:16px;font-weight:700;color:var(--text);margin-bottom:4px">Belum Ada QR Code Aktif</div>
            <div style="font-size:13px;color:var(--muted);max-width:380px;margin:0 auto 18px;line-height:1.5">QR Code akan otomatis muncul di sini setelah permohonan peminjaman Anda disetujui oleh Kepala Jurusan.</div>
            <a href="{{ route('teacher.peminjaman-guru.cart') }}" class="qr-btn qr-btn-primary" style="width:auto;display:inline-flex">
                Ajukan Peminjaman Baru
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
(function() {
    function getEls() {
        return {
            overlay: document.getElementById('qr-modal-overlay'),
            modal: document.getElementById('qr-modal'),
            imgWrap: document.getElementById('qr-img-wrap'),
            imgEl: document.getElementById('qr-img'),
            itemName: document.getElementById('qr-item-name'),
            token: document.getElementById('qr-token'),
            expires: document.getElementById('qr-expires'),
            spinner: document.getElementById('qr-spinner'),
            error: document.getElementById('qr-error')
        };
    }

    window.openQRModal = function(borrowingId, itemName) {
        var els = getEls();
        if (!els.overlay || !els.modal) return;

        els.imgWrap.style.display = 'none';
        els.spinner.style.display = 'block';
        els.error.style.display   = 'none';
        els.itemName.textContent  = itemName || 'Barang Inventaris';
        els.token.textContent     = '';
        els.expires.textContent   = '';

        els.overlay.style.opacity       = '1';
        els.overlay.style.pointerEvents = 'all';
        els.modal.style.opacity         = '1';
        els.modal.style.transform       = 'scale(1) translateY(0)';
        document.body.style.overflow    = 'hidden';

        fetch('/guru/peminjaman-guru/' + borrowingId + '/qr/generate', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            els.spinner.style.display = 'none';
            if (data.success) {
                els.imgEl.src = data.qr_image;
                els.imgWrap.style.display = 'flex';
                els.imgWrap.style.alignItems = 'center';
                els.imgWrap.style.justifyContent = 'center';
                els.token.textContent = '#' + data.borrowing_id + ' · ' + data.token.substring(0, 8).toUpperCase() + '...';
                els.expires.textContent = data.expires_at ? 'Berlaku hingga: ' + data.expires_at : '';
            } else {
                els.error.style.display = 'block';
                els.error.textContent = data.message || 'Gagal memuat QR Code.';
            }
        })
        .catch(function() {
            els.spinner.style.display = 'none';
            els.error.style.display = 'block';
            els.error.textContent = 'Koneksi gagal. Coba lagi beberapa saat.';
        });
    };

    window.closeQRModal = function() {
        var els = getEls();
        if (!els.overlay || !els.modal) return;
        els.overlay.style.opacity       = '0';
        els.overlay.style.pointerEvents = 'none';
        els.modal.style.opacity         = '0';
        els.modal.style.transform       = 'scale(.94) translateY(12px)';
        document.body.style.overflow    = '';
    };

    document.addEventListener('DOMContentLoaded', function() {
        var overlay = document.getElementById('qr-modal-overlay');
        if (overlay) {
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) window.closeQRModal();
            });
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') window.closeQRModal();
    });
})();
</script>
@endpush
