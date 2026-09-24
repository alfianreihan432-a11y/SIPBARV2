@extends('layouts.siswa')

@section('title', 'Peminjaman Saya – SIPBAR')

@section('content')
<div x-data="{ cancelRequestId: null, showCancelModal: false }" x-cloak>
@php
    $requests = \App\Models\BorrowingRequest::with(['itemWithTrashed', 'items.itemWithTrashed', 'qrCode', 'teacher'])
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
        'pending'   => ['label'=>'Menunggu',    'cls'=>'s-badge--pending',  'card'=>'s-loan-card--pending',  'dot'=>'var(--s-pending)'],
        'cancelled' => ['label'=>'Dibatalkan',  'cls'=>'s-badge--returned', 'card'=>'s-loan-card--returned', 'dot'=>'var(--s-returned)'],
        'approved'  => ['label'=>'Disetujui',   'cls'=>'s-badge--approved', 'card'=>'s-loan-card--approved', 'dot'=>'var(--s-approved)'],
        'qr_ready'  => ['label'=>'Siap Ambil',  'cls'=>'s-badge--approved', 'card'=>'s-loan-card--approved', 'dot'=>'var(--s-approved)'],
        'borrowed'  => ['label'=>'Dipinjam',    'cls'=>'s-badge--borrowed', 'card'=>'s-loan-card--borrowed', 'dot'=>'var(--s-borrowed)'],
        'returned'  => ['label'=>'Dikembalikan','cls'=>'s-badge--returned', 'card'=>'s-loan-card--returned', 'dot'=>'var(--s-returned)'],
        'rejected'  => ['label'=>'Ditolak',     'cls'=>'s-badge--rejected', 'card'=>'s-loan-card--rejected', 'dot'=>'var(--s-rejected)'],
    ];
@endphp

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

    /* CSS Grid Layout untuk Card Peminjaman */
    .s-loans-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 16px;
        align-items: stretch;
    }
    @media(max-width:640px) {
        .s-loans-grid {
            grid-template-columns: 1fr;
        }
    }

    .s-loan-card {
        background: var(--card2);
        border: 1px solid var(--border2);
        border-radius: 14px;
        padding: 18px 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 14px;
        transition: all .2s ease;
        border-left: 4px solid var(--border2);
        box-shadow: 0 1px 4px rgba(0,0,0,.03);
    }
    .s-loan-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,.08);
        border-color: var(--border);
        background: var(--card);
    }
    .s-loan-card--pending  { border-left-color: var(--s-pending); }
    .s-loan-card--approved { border-left-color: var(--s-approved); }
    .s-loan-card--borrowed { border-left-color: var(--s-borrowed); }
    .s-loan-card--returned { border-left-color: var(--s-returned); }
    .s-loan-card--rejected { border-left-color: var(--s-rejected); }
</style>

{{-- Loan List --}}
<div class="s-card">
    <div class="s-card-header" style="margin-bottom: 20px;">
        <div>
            <div class="s-card-title">Daftar Permohonan</div>
            <div class="s-card-sub">Diurutkan dari yang terbaru</div>
        </div>
        <a href="{{ route('student.history') }}" class="s-card-action">Riwayat Lengkap →</a>
    </div>

    @if($requests->count() > 0)
        <div class="s-loans-grid">
        @foreach($requests as $req)
        @php 
            $st = $statusMap[$req->status] ?? $statusMap['pending']; 
            $cardClass = $st['card'] ?? 's-loan-card--pending';
            $isMultiItem = $req->items->count() > 1;
            $totalQuantity = $req->totalQuantity();
        @endphp
        <div class="s-loan-card {{ $cardClass }}">
            {{-- Top Info: Icon, ID, Timestamp, Badge --}}
            <div>
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:12px;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div class="s-loan-icon" style="width:36px;height:36px;border-radius:10px;background:var(--bg3);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:var(--muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <div>
                            <span style="font-size:11px;font-weight:700;color:var(--subtle);letter-spacing:.04em">ID: #{{ $req->id }}</span>
                            <div style="font-size:11px;color:var(--muted)">{{ $req->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    <span class="s-badge {{ $st['cls'] }}">
                        <span class="s-badge-dot" style="background:{{ $st['dot'] }}"></span>
                        {{ $st['label'] }}
                    </span>
                </div>

                {{-- Item Name & Details --}}
                <div class="s-loan-name" style="font-size:14.5px;font-weight:700;color:var(--text);line-height:1.35;margin-bottom:6px;">
                    {{ $req->item_display_name }}
                </div>

                @if($isMultiItem)
                    <div style="background:var(--bg3);border:1px solid var(--border2);border-radius:9px;padding:8px 10px;margin-bottom:12px;display:flex;flex-direction:column;gap:4px;">
                        <div style="font-size:10.5px;font-weight:700;color:var(--subtle);text-transform:uppercase;letter-spacing:.05em;">Rincian Barang ({{ $req->items->count() }}):</div>
                        @foreach($req->items as $detail)
                            <div style="font-size:12px;color:var(--text2);display:flex;justify-content:space-between;gap:8px;">
                                <span>• {{ $detail->itemWithTrashed?->name ?? 'Barang tidak tersedia' }}</span>
                                <span style="font-weight:600;color:var(--muted)">{{ $detail->quantity }} unit</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="font-size:11.5px;color:var(--muted);font-weight:600;margin-bottom:10px;">
                        Jumlah: {{ $totalQuantity }} unit
                    </div>
                @endif

                {{-- Dates & Purpose Meta --}}
                <div class="s-loan-meta" style="display:flex;flex-direction:column;gap:5px;font-size:11.5px;color:var(--muted);">
                    <div class="s-loan-meta-item" style="display:flex;align-items:center;gap:6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>{{ $req->borrow_date ? $req->borrow_date->format('d M Y') : '-' }} – {{ $req->return_date ? $req->return_date->format('d M Y') : '-' }}</span>
                    </div>
                    @if($req->purpose)
                    <div class="s-loan-meta-item" style="display:flex;align-items:flex-start;gap:6px;color:var(--subtle);">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;flex-shrink:0;margin-top:2px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                        <span>{{ Str::limit($req->purpose, 60) }}</span>
                    </div>
                    @endif
                </div>

                @if($req->status === 'rejected' && $req->rejection_reason)
                <div style="margin-top:10px;padding:8px 12px;background:var(--s-rejected-bg);border:1px solid var(--s-rejected-bdr);border-radius:8px;font-size:12px;color:var(--s-rejected)">
                    <strong>Alasan ditolak:</strong> {{ $req->rejection_reason }}
                </div>
                @endif
            </div>

            {{-- Action Buttons --}}
            @if($req->status === 'pending')
                @php
                    $waService = app(\App\Services\WhatsAppNotificationService::class);
                    $shareLink = $waService->getDirectWaLink($req);
                    $approvalUrl = $waService->getApprovalUrl($req);
                @endphp
                <div style="margin-top:10px;padding-top:12px;border-top:1px solid var(--border2);display:flex;gap:6px;flex-wrap:wrap;">
                    <a href="{{ route('student.loans.edit', $req->id) }}" class="s-btn s-btn--sm s-btn--ghost">Edit</a>
                    <a href="{{ $shareLink }}" target="_blank" rel="noopener" class="s-btn s-btn--sm s-btn--primary">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l.7-3.305A7.93 7.93 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        WA Guru
                    </a>
                    <button type="button" onclick="navigator.clipboard.writeText('{{ $approvalUrl }}'); var btn=this; btn.textContent='Tersalin!'; setTimeout(function(){ btn.textContent='Salin Link'; }, 2000);" class="s-btn s-btn--sm s-btn--ghost">
                        Salin Link
                    </button>
                    <button type="button" @click="cancelRequestId = {{ $req->id }}; showCancelModal = true" class="s-btn s-btn--sm s-btn--danger">
                        Batalkan
                    </button>
                </div>
            @elseif(in_array($req->status, ['approved', 'qr_ready']))
                <div style="margin-top:10px;padding-top:12px;border-top:1px solid var(--border2);display:flex;gap:6px;flex-wrap:wrap;">
                    <button type="button" onclick="openQRModal({{ $req->id }})" class="s-btn s-btn--sm s-btn--primary">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-5v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V8a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1zm12 0h2a1 1 0 001-1V8a1 1 0 00-1-1h-2a1 1 0 00-1 1v1a1 1 0 001 1zM5 20h2a1 1 0 001-1v-1a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1z"/></svg>
                        Lihat QR Code
                    </button>
                </div>
            @endif
        </div>
        @endforeach
        </div>
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

{{-- Cancel Confirmation Modal --}}
<div x-show="showCancelModal" x-cloak style="position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(3px);">
    <div style="background:var(--card);border-radius:16px;padding:28px;width:100%;max-width:480px;border:1px solid var(--border2);box-shadow:0 20px 48px rgba(0,0,0,0.18);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h3 style="font-size:18px;font-weight:700;color:var(--text);">Batalkan Peminjaman</h3>
            <button type="button" @click="showCancelModal = false" style="background:none;border:none;font-size:24px;color:var(--muted);cursor:pointer;">&times;</button>
        </div>
        <p style="font-size:14px;color:var(--text2);margin-bottom:24px;">Apakah Anda yakin ingin membatalkan peminjaman ini? Tindakan ini tidak dapat dibatalkan.</p>
        <form method="POST" x-bind:action="cancelRequestId ? '{{ route('student.loans.cancel', ':id') }}'.replace(':id', cancelRequestId) : '#'" style="display:flex;gap:10px;justify-content:flex-end;">
            @csrf
            <button type="button" @click="showCancelModal = false" style="padding:8px 16px;background:var(--bg3);color:var(--text);border:1px solid var(--border2);border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">Batal</button>
            <button type="submit" style="padding:8px 16px;background:var(--s-rejected-bg);color:var(--s-rejected);border:1px solid var(--s-rejected-bdr);border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">Ya, Batalkan</button>
        </form>
    </div>
</div>
</div>
@endsection
