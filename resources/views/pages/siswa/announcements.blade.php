@extends('layouts.siswa')

@section('title', 'Pengumuman – SIPBAR')

@section('content')
@php
    $overdueBorrowings = \App\Models\BorrowingRequest::with('itemWithTrashed')
        ->where('user_id', auth()->id())
        ->where('status', 'borrowed')
        ->whereDate('return_date', '<', now())
        ->get();

    $dueSoonBorrowings = \App\Models\BorrowingRequest::with('itemWithTrashed')
        ->where('user_id', auth()->id())
        ->where('status', 'borrowed')
        ->whereDate('return_date', '>=', now())
        ->whereDate('return_date', '<=', now()->addDays(2))
        ->get();

    $recentApprovals = \App\Models\BorrowingRequest::with(['itemWithTrashed', 'qrCode'])
        ->where('user_id', auth()->id())
        ->whereIn('status', ['approved', 'qr_ready'])
        ->latest('updated_at')
        ->get();

    $recentRejections = \App\Models\BorrowingRequest::with('itemWithTrashed')
        ->where('user_id', auth()->id())
        ->where('status', 'rejected')
        ->latest('updated_at')
        ->take(3)->get();

    $totalAlerts = $overdueBorrowings->count() + $dueSoonBorrowings->count() + $recentApprovals->count() + $recentRejections->count();
@endphp



{{-- Page Header --}}
<div class="page-header">
    <div class="page-header-left">
        <div class="page-title">
            Pengumuman
            @if($totalAlerts > 0)
            <span class="page-title-count" style="background:var(--s-rejected-bg);color:var(--s-rejected);border-color:var(--s-rejected-bdr)">{{ $totalAlerts }} peringatan</span>
            @else
            <span class="page-title-count">Semua aman</span>
            @endif
        </div>
        <div class="page-subtitle">Informasi penting seputar status peminjaman dan inventaris</div>
    </div>
</div>

{{-- Critical: Overdue --}}
@if($overdueBorrowings->isNotEmpty())
<div class="s-card" style="border-color:var(--s-rejected-bdr);margin-bottom:16px">
    <div class="s-card-header" style="padding-bottom:14px;border-bottom:1px solid var(--s-rejected-bdr);margin-bottom:16px">
        <div style="display:flex;align-items:center;gap:10px">
            <div style="width:36px;height:36px;border-radius:10px;background:#dc2626;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <div class="s-card-title" style="color:var(--s-rejected)">Terlambat Dikembalikan</div>
                <div class="s-card-sub">Segera kembalikan barang untuk menghindari sanksi</div>
            </div>
        </div>
    </div>
    @foreach($overdueBorrowings as $overdue)
    @php $daysOverdue = now()->diffInDays(\Carbon\Carbon::parse($overdue->return_date)); @endphp
    <div class="s-loan-row s-loan-row--rejected">
        <div class="s-loan-icon" style="background:var(--s-rejected-bg)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:var(--s-rejected)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <div class="s-loan-content">
            <div class="s-loan-name">{{ $overdue->itemWithTrashed?->name ?? 'Barang tidak tersedia' }}</div>
            <div class="s-loan-meta">
                <span>Seharusnya kembali: {{ \Carbon\Carbon::parse($overdue->return_date)->format('d M Y') }}</span>
            </div>
        </div>
        <div class="s-loan-right">
            <span class="s-badge s-badge--rejected">Terlambat {{ $daysOverdue }} hari</span>
            <a href="{{ route('student.returns.index') }}" class="s-btn s-btn--danger s-btn--sm">Kembalikan</a>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- Warning: Due Soon --}}
@if($dueSoonBorrowings->isNotEmpty())
<div class="s-card" style="border-color:var(--s-pending-bdr);margin-bottom:16px">
    <div class="s-card-header" style="padding-bottom:14px;border-bottom:1px solid var(--s-pending-bdr);margin-bottom:16px">
        <div style="display:flex;align-items:center;gap:10px">
            <div style="width:36px;height:36px;border-radius:10px;background:#d97706;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="s-card-title" style="color:var(--s-pending)">Segera Kembalikan</div>
                <div class="s-card-sub">Barang ini harus dikembalikan dalam waktu dekat</div>
            </div>
        </div>
    </div>
    @foreach($dueSoonBorrowings as $dueSoon)
    @php $daysLeft = \Carbon\Carbon::parse($dueSoon->return_date)->diffInDays(now()); @endphp
    <div class="s-loan-row s-loan-row--pending">
        <div class="s-loan-icon" style="background:var(--s-pending-bg)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:var(--s-pending)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <div class="s-loan-content">
            <div class="s-loan-name">{{ $dueSoon->itemWithTrashed?->name ?? 'Barang tidak tersedia' }}</div>
            <div class="s-loan-meta">
                <span>Harus kembali: {{ \Carbon\Carbon::parse($dueSoon->return_date)->format('d M Y') }}</span>
            </div>
        </div>
        <div class="s-loan-right">
            <span class="s-badge s-badge--pending">{{ $daysLeft }} hari lagi</span>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- Approvals with QR --}}
@if($recentApprovals->isNotEmpty())
<div class="s-card" style="margin-bottom:16px">
    <div class="s-card-header">
        <div style="display:flex;align-items:center;gap:10px">
            <div style="width:36px;height:36px;border-radius:10px;background:var(--s-returned-bg);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:var(--s-returned)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="s-card-title">Peminjaman Disetujui</div>
                <div class="s-card-sub">Ambil barang dari ruang inventaris dengan QR Code</div>
            </div>
        </div>
    </div>
    @foreach($recentApprovals as $approval)
    <div class="s-loan-row s-loan-row--approved">
        <div class="s-loan-icon" style="background:var(--s-approved-bg)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:var(--s-approved)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="s-loan-content">
            <div class="s-loan-name">{{ $approval->itemWithTrashed?->name ?? 'Barang tidak tersedia' }}</div>
            <div class="s-loan-meta">
                <span>Disetujui {{ $approval->approved_at ? $approval->approved_at->diffForHumans() : 'baru saja' }}</span>
            </div>
        </div>
        <div class="s-loan-right">
            <span class="s-badge s-badge--approved">Disetujui</span>
            <button onclick="openQRModal({{ $approval->id }})" class="s-btn s-btn--sm s-btn--primary">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                QR Code
            </button>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- Recent Rejections --}}
@if($recentRejections->isNotEmpty())
<div class="s-card" style="margin-bottom:16px">
    <div class="s-card-header">
        <div style="display:flex;align-items:center;gap:10px">
            <div style="width:36px;height:36px;border-radius:10px;background:var(--s-rejected-bg);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:var(--s-rejected)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="s-card-title">Peminjaman Ditolak</div>
                <div class="s-card-sub">Pengajuan yang tidak dapat disetujui</div>
            </div>
        </div>
    </div>
    @foreach($recentRejections as $rejection)
    <div class="s-loan-row s-loan-row--rejected">
        <div class="s-loan-icon" style="background:var(--s-rejected-bg)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:var(--s-rejected)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <div class="s-loan-content">
            <div class="s-loan-name">{{ $rejection->itemWithTrashed?->name ?? 'Barang tidak tersedia' }}</div>
            @if($rejection->rejection_reason)
            <div style="margin-top:8px;padding:8px 12px;background:var(--s-rejected-bg);border:1px solid var(--s-rejected-bdr);border-radius:8px;font-size:12px;color:var(--s-rejected)">
                <strong>Alasan:</strong> {{ $rejection->rejection_reason }}
            </div>
            @endif
        </div>
        <div class="s-loan-right">
            <span class="s-badge s-badge--rejected">Ditolak</span>
            <span class="s-loan-time">{{ $rejection->updated_at->diffForHumans() }}</span>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- System Announcements --}}
<div class="s-card" style="margin-bottom:16px">
    <div class="s-card-header">
        <div>
            <div class="s-card-title">Informasi Sistem</div>
            <div class="s-card-sub">Panduan dan kebijakan penggunaan SIPBAR</div>
        </div>
    </div>

    @php
    $sysAnn = [
        ['icon'=>'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z', 'color'=>'var(--primary)', 'bg'=>'var(--primary-light)', 'title'=>'Cara Menggunakan QR Code', 'body'=>'Setelah peminjaman disetujui, QR Code akan muncul di pengumuman ini. Tunjukkan QR Code kepada admin untuk mengambil barang dari gudang inventaris.', 'date'=>'Hari ini'],
        ['icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color'=>'#d97706', 'bg'=>'rgba(217,119,6,.1)', 'title'=>'Kebijakan Pengembalian', 'body'=>'Pastikan mengembalikan barang tepat waktu sesuai tanggal yang tertera. Keterlambatan dapat berdampak pada reputasi peminjaman dan izin penggunaan fasilitas ke depannya.', 'date'=>'2 hari lalu'],
        ['icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'color'=>'#059669', 'bg'=>'rgba(5,150,105,.1)', 'title'=>'Tanggung Jawab Perawatan Barang', 'body'=>'Jaga kondisi barang selama masa pinjam. Laporkan segera jika terjadi kerusakan sebelum dikembalikan. Pengembalian dalam kondisi baik adalah kewajiban setiap peminjam.', 'date'=>'1 minggu lalu'],
    ];
    @endphp
    @foreach($sysAnn as $ann)
    <div style="display:flex;gap:14px;padding:14px 0;border-bottom:1px solid var(--border2)">
        <div style="width:40px;height:40px;border-radius:10px;background:{{ $ann['bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:{{ $ann['color'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $ann['icon'] }}"/></svg>
        </div>
        <div style="flex:1">
            <div style="font-family:var(--font-head);font-size:14px;font-weight:700;color:var(--text);margin-bottom:5px">{{ $ann['title'] }}</div>
            <div style="font-size:13px;color:var(--muted);line-height:1.6">{{ $ann['body'] }}</div>
            <div style="display:inline-flex;align-items:center;gap:5px;margin-top:10px;font-size:11px;color:var(--subtle);background:var(--bg3);border:1px solid var(--border2);padding:3px 10px;border-radius:6px">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ $ann['date'] }}
            </div>
        </div>
    </div>
    @endforeach
    <div style="padding-top:4px"></div>
</div>

{{-- Empty State --}}
@if($overdueBorrowings->isEmpty() && $dueSoonBorrowings->isEmpty() && $recentApprovals->isEmpty() && $recentRejections->isEmpty())
<div class="s-empty" style="margin-top:-10px">
    <div class="s-empty-icon-wrap" style="background:var(--s-returned-bg);border-color:var(--s-returned-bdr)">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:32px;height:32px;color:var(--s-returned)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="s-empty-title">Semua Lancar!</div>
    <div class="s-empty-sub">Tidak ada peringatan atau notifikasi penting saat ini. Peminjaman kamu dalam status yang baik.</div>
    <a href="{{ route('student.catalog') }}" class="s-btn s-btn--primary">Lihat Katalog Barang</a>
</div>
@endif
@endsection
