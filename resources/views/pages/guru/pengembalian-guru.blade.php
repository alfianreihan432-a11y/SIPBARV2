@extends('layouts.guru')

@section('title', 'Pengembalian Saya – SIPBAR Guru')

@section('content')
@php
    $pendingVerif = $completedReturns->where('checkin_by', null)->count();
    $verified     = $completedReturns->where('checkin_by', '!=', null)->count();
@endphp

{{-- Page Header --}}
<div class="g-page-header">
    <div class="g-page-header-left">
        <div class="g-page-title">
            Pengembalian Saya
        </div>
        <div class="g-page-subtitle">Kelola pengembalian barang yang sedang kamu pinjam</div>
    </div>
    <a href="{{ route('teacher.peminjaman-guru') }}" class="g-btn g-btn--ghost">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Permohonan Saya
    </a>
</div>

{{-- Stats Row --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:22px" class="g-return-stat-grid">
    <div class="g-stat" style="padding:16px 18px">
        <div class="g-stat-icon" style="width:42px;height:42px;background:rgba(8,145,178,.1)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#0891b2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
        </div>
        <div class="g-stat-body">
            <div class="g-stat-num">{{ $activeBorrowings->count() }}</div>
            <div class="g-stat-label">Sedang Dipinjam</div>
        </div>
    </div>
    <div class="g-stat" style="padding:16px 18px">
        <div class="g-stat-icon" style="width:42px;height:42px;background:rgba(245,158,11,.1)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#f59e0b" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="g-stat-body">
            <div class="g-stat-num">{{ $pendingVerif }}</div>
            <div class="g-stat-label">Menunggu Verifikasi</div>
        </div>
    </div>
    <div class="g-stat" style="padding:16px 18px">
        <div class="g-stat-icon" style="width:42px;height:42px;background:rgba(5,150,105,.1)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#059669" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <div class="g-stat-body">
            <div class="g-stat-num">{{ $verified }}</div>
            <div class="g-stat-label">Selesai Terverifikasi</div>
        </div>
    </div>
</div>
<style>
    @media(max-width:768px){.g-return-stat-grid{grid-template-columns:1fr!important}}
</style>

{{-- Flash messages --}}
@if(session('success'))
<div style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.3);color:#065f46;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px">
    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
    {{ session('success') }}
</div>
@endif

{{-- Active Borrowings Section --}}
<div class="g-card" style="margin-bottom:20px">
    <div class="g-card-header">
        <div>
            <div class="g-card-title">Barang Aktif – Siap Dikembalikan</div>
            <div class="g-card-sub">Barang yang sedang kamu pinjam dan bisa diajukan pengembaliannya</div>
        </div>
        <span style="font-size:12px;font-weight:600;color:var(--muted);background:var(--bg3);border:1px solid var(--border2);padding:3px 10px;border-radius:20px">{{ $activeBorrowings->count() }} aktif</span>
    </div>

    @if($activeBorrowings->count() > 0)
        @foreach($activeBorrowings as $b)
        <div class="g-loan-row {{ $b->status === 'borrowed' ? 'g-loan-row--borrowed' : 'g-loan-row--approved' }}">
            <div class="g-loan-icon">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:var(--muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div class="g-loan-content">
                <div class="g-loan-name">{{ $b->item?->name ?? $b->itemWithTrashed?->name ?? 'Barang tidak tersedia' }}</div>
                <div class="g-loan-code">ID: #{{ $b->id }} · Qty: {{ $b->quantity }} unit
                    @if($b->approvedByKajur)
                    · <span>Disetujui oleh {{ $b->approvedByKajur->name }}</span>
                    @endif
                </div>
                <div class="g-loan-meta">
                    <div class="g-loan-meta-item">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Pinjam: {{ $b->borrow_date->format('d M Y') }}
                    </div>
                    <div class="g-loan-meta-item">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Batas: {{ $b->return_date->format('d M Y') }} {{ $b->return_time }}
                    </div>
                    @php $isOverdue = $b->return_date->isPast() && $b->status === 'borrowed'; @endphp
                    @if($isOverdue)
                    <div class="g-loan-meta-item" style="color:#ef4444;font-weight:600;display:flex;align-items:center;gap:5px">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                        Melewati batas waktu
                    </div>
                    @endif
                </div>
                @if($b->status === 'borrowed')
                <div style="margin-top:10px">
                    <a href="{{ route('teacher.pengembalian-guru.create', $b->id) }}" class="g-btn g-btn--sm g-btn--success">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                        Ajukan Pengembalian
                    </a>
                </div>
                @else
                <div style="margin-top:8px">
                    <span style="font-size:12px;color:var(--muted);background:var(--bg3);border:1px solid var(--border2);padding:4px 10px;border-radius:6px;display:inline-block">
                        Menunggu pengambilan barang
                    </span>
                </div>
                @endif
            </div>
            <div class="g-loan-right">
                @if($b->status === 'borrowed')
                    <span class="g-badge g-badge--borrowed">
                        <span class="g-badge-dot" style="background:#0891b2"></span>
                        Dipinjam
                    </span>
                @else
                    <span class="g-badge g-badge--approved">
                        <span class="g-badge-dot" style="background:#10b981"></span>
                        Disetujui
                    </span>
                @endif
                <span class="g-loan-time">{{ $b->borrow_date->diffForHumans() }}</span>
            </div>
        </div>
        @endforeach
    @else
        <div class="g-empty" style="padding:32px 24px">
            <div class="g-empty-icon-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:28px;height:28px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div class="g-empty-title">Tidak ada barang aktif</div>
            <div class="g-empty-sub">Kamu tidak sedang meminjam barang apapun saat ini</div>
        </div>
    @endif
</div>

{{-- Return History Section --}}
<div class="g-card">
    <div class="g-card-header">
        <div>
            <div class="g-card-title">Riwayat Pengembalian</div>
            <div class="g-card-sub">Pengembalian yang sudah diajukan (menunggu atau sudah diverifikasi Kepala Jurusan)</div>
        </div>
        @if($completedReturns->count() > 0)
        <a href="{{ route('teacher.pengembalian-guru.history') }}" class="g-card-action">Riwayat Lengkap →</a>
        @endif
    </div>

    @if($completedReturns->count() > 0)
        @foreach($completedReturns->take(10) as $ret)
        <div class="g-loan-row {{ $ret->checkin_by ? 'g-loan-row--approved' : 'g-loan-row--pending' }}">
            <div class="g-loan-icon">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:var(--muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
            </div>
            <div class="g-loan-content">
                <div class="g-loan-name">{{ $ret->item?->name ?? $ret->itemWithTrashed?->name ?? 'Barang tidak tersedia' }}</div>
                <div class="g-loan-code">ID: #{{ $ret->id }} · Qty: {{ $ret->quantity }} unit</div>
                <div class="g-loan-meta">
                    @if($ret->returned_at)
                    <div class="g-loan-meta-item">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Dikembalikan: {{ $ret->returned_at->format('d M Y H:i') }}
                    </div>
                    @endif
                    @if($ret->return_condition)
                    <div class="g-loan-meta-item">
                        @if($ret->return_condition === 'Baik')
                            <span style="background:rgba(16,185,129,.1);color:#065f46;padding:2px 8px;border-radius:5px;font-size:11px;font-weight:600">{{ $ret->return_condition }}</span>
                        @elseif($ret->return_condition === 'Rusak Ringan')
                            <span style="background:rgba(245,158,11,.1);color:#b45309;padding:2px 8px;border-radius:5px;font-size:11px;font-weight:600">{{ $ret->return_condition }}</span>
                        @else
                            <span style="background:rgba(239,68,68,.1);color:#b91c1c;padding:2px 8px;border-radius:5px;font-size:11px;font-weight:600">{{ $ret->return_condition }}</span>
                        @endif
                    </div>
                    @endif
                </div>
                @if($ret->return_notes)
                <div style="margin-top:6px;font-size:12px;color:var(--muted);background:var(--bg3);padding:6px 10px;border-radius:6px;display:inline-flex;align-items:center;gap:6px">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                    {{ Str::limit($ret->return_notes, 60) }}
                </div>
                @endif
            </div>
            <div class="g-loan-right">
                @if($ret->checkin_by)
                    <span class="g-badge g-badge--approved">
                        <span class="g-badge-dot" style="background:#059669"></span>
                        Terverifikasi
                    </span>
                @else
                    <span class="g-badge g-badge--pending">
                        <span class="g-badge-dot" style="background:#f59e0b"></span>
                        Menunggu Kajur
                    </span>
                @endif
                @if($ret->returned_at)
                <span class="g-loan-time">{{ $ret->returned_at->diffForHumans() }}</span>
                @endif
            </div>
        </div>
        @endforeach
        @if($completedReturns->count() > 10)
        <div style="padding:14px 20px;text-align:center;border-top:1px solid var(--border)">
            <a href="{{ route('teacher.pengembalian-guru.history') }}" class="g-btn g-btn--ghost g-btn--sm">
                Lihat {{ $completedReturns->count() - 10 }} pengembalian lainnya
            </a>
        </div>
        @endif
    @else
        <div class="g-empty" style="padding:32px 24px">
            <div class="g-empty-icon-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:28px;height:28px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
            </div>
            <div class="g-empty-title">Belum ada riwayat pengembalian</div>
            <div class="g-empty-sub">Pengembalian barang yang sudah kamu ajukan akan muncul di sini</div>
        </div>
    @endif
</div>

<style>
/* ── Page Header ── */
.g-page-header{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:24px;flex-wrap:wrap}
.g-page-title{font-size:22px;font-weight:800;color:var(--text);letter-spacing:-.02em;display:flex;align-items:center;gap:10px;flex-wrap:wrap}
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

/* ── Buttons ── */
.g-btn{display:inline-flex;align-items:center;gap:7px;padding:9px 16px;border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;border:none;transition:all .15s;text-decoration:none;font-family:inherit;white-space:nowrap}
.g-btn--ghost{background:var(--bg3);border:1px solid var(--border2);color:var(--text2)}
.g-btn--ghost:hover{border-color:var(--accent);color:var(--accent-text)}
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