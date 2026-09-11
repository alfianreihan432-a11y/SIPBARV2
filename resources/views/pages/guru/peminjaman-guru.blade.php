@extends('layouts.guru')

@section('title', 'Permohonan Peminjaman – SIPBAR Guru')

@section('content')
@php
    $counts = [
        'pending'  => $borrowings->whereIn('status', ['pending'])->count(),
        'approved' => $borrowings->whereIn('status', ['approved', 'qr_ready'])->count(),
        'borrowed' => $borrowings->where('status', 'borrowed')->count(),
        'returned' => $borrowings->where('status', 'returned')->count(),
        'rejected' => $borrowings->where('status', 'rejected')->count(),
    ];
    $statusMap = [
        'pending'   => ['label'=>'Menunggu Kajur', 'cls'=>'g-badge--pending',  'dot'=>'#f59e0b'],
        'cancelled' => ['label'=>'Dibatalkan',     'cls'=>'g-badge--returned', 'dot'=>'#94a3b8'],
        'approved'  => ['label'=>'Disetujui',      'cls'=>'g-badge--approved', 'dot'=>'#10b981'],
        'qr_ready'  => ['label'=>'Siap Ambil',     'cls'=>'g-badge--approved', 'dot'=>'#10b981'],
        'borrowed'  => ['label'=>'Dipinjam',       'cls'=>'g-badge--borrowed', 'dot'=>'#0891b2'],
        'returned'  => ['label'=>'Dikembalikan',   'cls'=>'g-badge--returned', 'dot'=>'#059669'],
        'rejected'  => ['label'=>'Ditolak',        'cls'=>'g-badge--rejected', 'dot'=>'#ef4444'],
    ];
    $waService = app(\App\Services\WhatsAppNotificationService::class);
@endphp

{{-- QR Code Modal --}}
<div id="qr-modal-overlay" style="position:fixed;inset:0;background:rgba(0,0,0,.6);backdrop-filter:blur(4px);z-index:1000;display:flex;align-items:center;justify-content:center;padding:20px;opacity:0;pointer-events:none;transition:opacity .25s ease">
    <div id="qr-modal" style="background:var(--card);border:1px solid var(--border2);border-radius:20px;padding:28px 24px;max-width:380px;width:100%;text-align:center;transform:scale(.94) translateY(12px);transition:transform .28s cubic-bezier(.34,1.56,.64,1),opacity .25s;opacity:0;position:relative">
        <button onclick="closeQRModal()" style="position:absolute;top:14px;right:14px;background:var(--bg3);border:1px solid var(--border2);border-radius:8px;width:30px;height:30px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--muted)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div style="font-size:16px;font-weight:800;color:var(--text);margin-bottom:4px">QR Code Peminjaman</div>
        <div style="font-size:12px;color:var(--muted);margin-bottom:20px">Tunjukkan kepada Kepala Jurusan saat mengambil barang</div>
        <div id="qr-spinner" style="width:40px;height:40px;border:3px solid var(--border2);border-top-color:var(--accent);border-radius:50%;animation:qr-spin .7s linear infinite;margin:40px auto"></div>
        <div id="qr-error" style="display:none;color:#ef4444;font-size:13px;padding:16px;background:rgba(239,68,68,0.08);border-radius:10px;margin-bottom:12px"></div>
        <div id="qr-img-wrap" style="display:none;width:260px;height:260px;margin:0 auto 16px;border-radius:14px;border:2px solid var(--border2);overflow:hidden;background:#fff">
            <img id="qr-img" src="" alt="QR Code" style="width:100%;height:100%;object-fit:contain">
        </div>
        <div id="qr-item-name" style="font-size:15px;font-weight:700;color:var(--text);margin-bottom:4px"></div>
        <div id="qr-token" style="font-size:11px;color:var(--muted);font-family:monospace;background:var(--bg3);border-radius:6px;padding:4px 10px;display:inline-block;margin-bottom:14px;letter-spacing:.04em"></div>
        <div style="font-size:12px;color:var(--muted);line-height:1.6;background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.2);border-radius:10px;padding:10px 14px;margin-bottom:12px;text-align:left">
            Kepala Jurusan akan men-scan QR Code ini untuk konfirmasi pengambilan barang.
        </div>
        <div id="qr-expires" style="font-size:11px;color:var(--subtle)"></div>
    </div>
</div>
<style>@keyframes qr-spin{to{transform:rotate(360deg)}}</style>

{{-- Page Header --}}
<div class="g-page-header">
    <div class="g-page-header-left">
        <div class="g-page-title">
            Permohonan Peminjaman
            @if($borrowings->count() > 0)
            <span class="g-page-title-count">{{ $borrowings->count() }} permohonan</span>
            @endif
        </div>
        <div class="g-page-subtitle">Semua permohonan peminjaman barang yang kamu ajukan sebagai guru</div>
    </div>
    <a href="{{ route('teacher.peminjaman-guru.create') }}" class="g-btn g-btn--primary">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        Ajukan Peminjaman
    </a>
</div>

{{-- Summary Stats --}}
<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:22px" class="g-loans-stat-grid">
    <div class="g-stat" style="padding:14px 16px">
        <div class="g-stat-icon" style="width:38px;height:38px;background:rgba(245,158,11,.1)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#f59e0b" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="g-stat-body">
            <div class="g-stat-num">{{ $counts['pending'] }}</div>
            <div class="g-stat-label">Menunggu</div>
        </div>
    </div>
    <div class="g-stat" style="padding:14px 16px">
        <div class="g-stat-icon" style="width:38px;height:38px;background:rgba(16,185,129,.1)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#10b981" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="g-stat-body">
            <div class="g-stat-num">{{ $counts['approved'] }}</div>
            <div class="g-stat-label">Disetujui</div>
        </div>
    </div>
    <div class="g-stat" style="padding:14px 16px">
        <div class="g-stat-icon" style="width:38px;height:38px;background:rgba(8,145,178,.1)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#0891b2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
        </div>
        <div class="g-stat-body">
            <div class="g-stat-num">{{ $counts['borrowed'] }}</div>
            <div class="g-stat-label">Dipinjam</div>
        </div>
    </div>
    <div class="g-stat" style="padding:14px 16px">
        <div class="g-stat-icon" style="width:38px;height:38px;background:rgba(5,150,105,.1)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#059669" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <div class="g-stat-body">
            <div class="g-stat-num">{{ $counts['returned'] }}</div>
            <div class="g-stat-label">Selesai</div>
        </div>
    </div>
    <div class="g-stat" style="padding:14px 16px">
        <div class="g-stat-icon" style="width:38px;height:38px;background:rgba(220,38,38,.1)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#dc2626" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="g-stat-body">
            <div class="g-stat-num">{{ $counts['rejected'] }}</div>
            <div class="g-stat-label">Ditolak</div>
        </div>
    </div>
</div>
<style>
    @media(max-width:992px){.g-loans-stat-grid{grid-template-columns:repeat(3,1fr)!important}}
    @media(max-width:768px){.g-loans-stat-grid{grid-template-columns:repeat(2,1fr)!important}}
    @media(max-width:480px){.g-loans-stat-grid{grid-template-columns:1fr!important}}
</style>

{{-- Flash messages --}}
@if(session('success'))
<div style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.3);color:#065f46;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:13px;font-weight:600">
    ✓ {{ session('success') }}
</div>
@endif
@if(session('error'))
<div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#b91c1c;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:13px;font-weight:600">
    ✗ {{ session('error') }}
</div>
@endif

{{-- Loan List --}}
<div class="g-card">
    <div class="g-card-header">
        <div>
            <div class="g-card-title">Daftar Permohonan</div>
            <div class="g-card-sub">Diurutkan dari yang terbaru</div>
        </div>
        <a href="{{ route('teacher.pengembalian-guru') }}" class="g-card-action">Pengembalian →</a>
    </div>

    @if($borrowings->count() > 0)
        @foreach($borrowings as $req)
        @php $st = $statusMap[$req->status] ?? $statusMap['pending']; @endphp
        <div class="g-loan-row {{ $req->status === 'pending' ? 'g-loan-row--pending' : ($req->status === 'rejected' || $req->status === 'cancelled' ? 'g-loan-row--rejected' : ($req->status === 'borrowed' ? 'g-loan-row--borrowed' : 'g-loan-row--approved')) }}">
            <div class="g-loan-icon">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:var(--muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div class="g-loan-content">
                <div class="g-loan-name">{{ $req->itemWithTrashed?->name ?? $req->item?->name ?? 'Barang tidak tersedia' }}</div>
                <div class="g-loan-code">ID: #{{ $req->id }} · Qty: {{ $req->quantity }} unit
                    @if($req->approvedByKajur)
                    · <span style="color:var(--muted)">Kajur: {{ $req->approvedByKajur->name }}</span>
                    @endif
                </div>
                <div class="g-loan-meta">
                    <div class="g-loan-meta-item">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $req->borrow_date->format('d M Y') }} – {{ $req->return_date->format('d M Y') }}
                        @if($req->return_time)
                        <span style="color:var(--subtle)">pukul {{ $req->return_time }}</span>
                        @endif
                    </div>
                    @if($req->purpose)
                    <div class="g-loan-meta-item" style="color:var(--subtle)">
                        {{ Str::limit($req->purpose, 50) }}
                    </div>
                    @endif
                </div>

                {{-- Action buttons based on status --}}
                @if($req->status === 'pending')
                    @php
                        $waLink = $waService->getDirectWaLinkForKajur($req);
                        $approvalUrl = $waService->getApprovalUrlForKajur($req);
                    @endphp
                    <div style="margin-top:10px;display:flex;gap:8px;flex-wrap:wrap">
                        <a href="{{ route('teacher.peminjaman-guru.edit', $req->id) }}" class="g-btn g-btn--sm g-btn--ghost">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </a>
                        <a href="{{ $waLink }}" target="_blank" rel="noopener" class="g-btn g-btn--sm g-btn--primary">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l.7-3.305A7.93 7.93 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            Kirim ke WA Kajur
                        </a>
                        <button type="button"
                            onclick="copyLink('{{ $approvalUrl }}', this)"
                            class="g-btn g-btn--sm g-btn--ghost">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            Salin Link
                        </button>
                        <form method="POST" action="{{ route('teacher.peminjaman-guru.cancel', $req->id) }}" onsubmit="return confirm('Yakin ingin membatalkan permohonan peminjaman ini?')" style="display:inline">
                            @csrf
                            <button type="submit" class="g-btn g-btn--sm g-btn--danger">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Batalkan
                            </button>
                        </form>
                    </div>
                @elseif(in_array($req->status, ['approved', 'qr_ready']))
                    <div style="margin-top:10px;display:flex;gap:8px;flex-wrap:wrap">
                        <button type="button" onclick="openQRModal({{ $req->id }}, '{{ addslashes($req->itemWithTrashed?->name ?? $req->item?->name ?? 'Barang') }}')" class="g-btn g-btn--sm g-btn--primary">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            Lihat QR Code
                        </button>
                    </div>
                @elseif($req->status === 'borrowed')
                    <div style="margin-top:10px;display:flex;gap:8px;flex-wrap:wrap">
                        <button type="button" onclick="openQRModal({{ $req->id }}, '{{ addslashes($req->itemWithTrashed?->name ?? $req->item?->name ?? 'Barang') }}')" class="g-btn g-btn--sm g-btn--ghost">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            Lihat QR
                        </button>
                        <a href="{{ route('teacher.pengembalian-guru.create', $req->id) }}" class="g-btn g-btn--sm g-btn--success">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                            Ajukan Pengembalian
                        </a>
                    </div>
                @elseif($req->status === 'rejected' && $req->rejection_reason)
                    <div style="margin-top:8px;padding:8px 12px;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:8px;font-size:12px;color:#b91c1c">
                        <strong>Alasan ditolak:</strong> {{ $req->rejection_reason }}
                    </div>
                @endif
            </div>
            <div class="g-loan-right">
                <span class="g-badge {{ $st['cls'] }}">
                    <span class="g-badge-dot" style="background:{{ $st['dot'] }}"></span>
                    {{ $st['label'] }}
                </span>
                <span class="g-loan-time">{{ $req->created_at->diffForHumans() }}</span>
            </div>
        </div>
        @endforeach
    @else
        <div class="g-empty">
            <div class="g-empty-icon-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:32px;height:32px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            </div>
            <div class="g-empty-title">Belum ada permohonan</div>
            <div class="g-empty-sub">Ajukan peminjaman barang inventaris sekolah untuk keperluan mengajar</div>
            <a href="{{ route('teacher.peminjaman-guru.create') }}" class="g-btn g-btn--primary" style="margin-top:16px">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Ajukan Peminjaman
            </a>
        </div>
    @endif
</div>

<style>
/* ── Page Header ── */
.g-page-header{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:24px;flex-wrap:wrap}
.g-page-title{font-size:22px;font-weight:800;color:var(--text);letter-spacing:-.02em;display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.g-page-title-count{font-size:13px;font-weight:600;color:var(--muted);background:var(--bg3);border:1px solid var(--border2);border-radius:20px;padding:2px 10px;letter-spacing:0}
.g-page-subtitle{font-size:13px;color:var(--muted);margin-top:4px}

/* ── Stats Cards ── */
.g-stat{background:var(--card);border:1px solid var(--border);border-radius:14px;display:flex;align-items:center;gap:12px;transition:box-shadow .15s}
.g-stat:hover{box-shadow:0 4px 16px rgba(0,0,0,.06)}
.g-stat-icon{border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.g-stat-num{font-size:22px;font-weight:800;color:var(--text);line-height:1}
.g-stat-label{font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.04em;margin-top:3px}

/* ── Card ── */
.g-card{background:var(--card);border:1px solid var(--border);border-radius:14px;overflow:hidden}
.g-card-header{display:flex;align-items:center;justify-content:space-between;padding:18px 20px;border-bottom:1px solid var(--border)}
.g-card-title{font-size:15px;font-weight:700;color:var(--text)}
.g-card-sub{font-size:12px;color:var(--muted);margin-top:2px}
.g-card-action{font-size:12px;font-weight:600;color:var(--accent-text);text-decoration:none;white-space:nowrap}
.g-card-action:hover{text-decoration:underline}

/* ── Loan Rows ── */
.g-loan-row{display:flex;align-items:flex-start;gap:14px;padding:16px 20px;border-bottom:1px solid var(--border);transition:background .12s;position:relative}
.g-loan-row:last-child{border-bottom:none}
.g-loan-row--pending{border-left:3px solid #f59e0b}
.g-loan-row--approved{border-left:3px solid #10b981}
.g-loan-row--borrowed{border-left:3px solid #0891b2}
.g-loan-row--rejected{border-left:3px solid #94a3b8}
.g-loan-row:hover{background:var(--bg3)}
.g-loan-icon{width:40px;height:40px;background:var(--bg3);border:1px solid var(--border2);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px}
.g-loan-content{flex:1;min-width:0}
.g-loan-name{font-size:14px;font-weight:700;color:var(--text);margin-bottom:3px}
.g-loan-code{font-size:12px;color:var(--muted);margin-bottom:6px}
.g-loan-meta{display:flex;flex-wrap:wrap;gap:10px}
.g-loan-meta-item{display:flex;align-items:center;gap:5px;font-size:12px;color:var(--muted)}
.g-loan-right{display:flex;flex-direction:column;align-items:flex-end;gap:6px;flex-shrink:0}
.g-loan-time{font-size:11px;color:var(--subtle)}

/* ── Badges ── */
.g-badge{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;letter-spacing:.02em;white-space:nowrap}
.g-badge-dot{width:6px;height:6px;border-radius:50%;flex-shrink:0}
.g-badge--pending{background:rgba(245,158,11,.12);color:#b45309}
.g-badge--approved{background:rgba(16,185,129,.12);color:#065f46}
.g-badge--borrowed{background:rgba(8,145,178,.12);color:#0e7490}
.g-badge--returned{background:rgba(5,150,105,.12);color:#047857}
.g-badge--rejected{background:rgba(148,163,184,.12);color:var(--muted)}

/* ── Buttons ── */
.g-btn{display:inline-flex;align-items:center;gap:7px;padding:9px 16px;border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;border:none;transition:all .15s;text-decoration:none;font-family:inherit;white-space:nowrap}
.g-btn--primary{background:var(--accent);color:#fff}
.g-btn--primary:hover{filter:brightness(1.1);color:#fff}
.g-btn--ghost{background:var(--bg3);border:1px solid var(--border2);color:var(--text2)}
.g-btn--ghost:hover{border-color:var(--accent);color:var(--accent-text)}
.g-btn--danger{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#b91c1c}
.g-btn--danger:hover{background:rgba(239,68,68,.18)}
.g-btn--success{background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.25);color:#065f46}
.g-btn--success:hover{background:rgba(16,185,129,.18)}
.g-btn--sm{padding:5px 11px;font-size:12px;border-radius:7px;gap:5px}

/* ── Empty State ── */
.g-empty{text-align:center;padding:48px 24px}
.g-empty-icon-wrap{width:64px;height:64px;border-radius:16px;background:var(--bg3);border:1px solid var(--border2);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;color:var(--muted)}
.g-empty-title{font-size:15px;font-weight:700;color:var(--text);margin-bottom:6px}
.g-empty-sub{font-size:13px;color:var(--muted);max-width:320px;margin:0 auto;line-height:1.5}
</style>
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

        els.imgWrap.style.display  = 'none';
        els.spinner.style.display  = 'block';
        els.error.style.display    = 'none';
        els.itemName.textContent   = itemName || 'Barang Inventaris';
        els.token.textContent      = '';
        els.expires.textContent    = '';

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

    window.copyLink = function(url, btn) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(url).then(function() {
                var orig = btn.textContent;
                btn.textContent = '✓ Tersalin!';
                setTimeout(function(){ btn.textContent = orig.trim(); }, 2000);
            });
        } else {
            var t = document.createElement('textarea');
            t.value = url; document.body.appendChild(t); t.select();
            document.execCommand('copy'); document.body.removeChild(t);
            var orig = btn.textContent;
            btn.textContent = '✓ Tersalin!';
            setTimeout(function(){ btn.textContent = orig.trim(); }, 2000);
        }
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