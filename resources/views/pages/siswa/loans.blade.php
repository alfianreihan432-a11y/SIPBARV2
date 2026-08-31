@extends('layouts.siswa')

@section('title', 'Peminjaman Saya – SIPBAR')

@section('content')
@php
    $requests = \App\Models\BorrowingRequest::where('user_id', auth()->id())->latest()->get();
    $counts = [
        'pending'   => $requests->whereIn('status', ['pending'])->count(),
        'cancelled' => $requests->whereIn('status', ['cancelled'])->count(),
        'borrowed'  => $requests->whereIn('status', ['approved','qr_ready','borrowed'])->count(),
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
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:22px" class="loans-stat-grid">
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
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#059669" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
                <div class="s-loan-name">{{ $req->item?->name ?? 'Barang tidak tersedia' }}</div>
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
                        $shareLink = $req->teacher && $req->teacher->phone
                            ? app(\App\Services\WhatsAppNotificationService::class)->getDirectWaLink($req)
                            : null;
                    @endphp
                    <div style="margin-top:10px;display:flex;gap:8px;flex-wrap:wrap">
                        <a href="{{ route('student.loans.edit', $req->id) }}" class="s-btn s-btn--sm s-btn--ghost">
                            Edit
                        </a>
                        @if($shareLink)
                            <a href="{{ $shareLink }}" target="_blank" rel="noopener" class="s-btn s-btn--sm s-btn--primary">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l.7-3.305A7.93 7.93 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                Kirim ke WA Guru
                            </a>
                            <button type="button" onclick="navigator.clipboard.writeText('{{ $shareLink }}'); this.textContent='Tersalin';" class="s-btn s-btn--sm s-btn--ghost">
                                Salin Link
                            </button>
                        @endif
                        <form method="POST" action="{{ route('student.loans.cancel', $req->id) }}" onsubmit="return confirm('Yakin ingin membatalkan peminjaman ini?')" style="display:inline;">
                            @csrf
                            <button type="submit" class="s-btn s-btn--sm s-btn--danger">
                                Batalkan
                            </button>
                        </form>
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
