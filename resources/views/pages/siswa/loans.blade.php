@extends('layouts.siswa')

@section('title', 'Peminjaman Saya')
@section('page-heading', 'Peminjaman Saya')

@section('content')
<style>
    .loans-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 20px; }
    @media (max-width: 1024px) { .loans-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px)  { .loans-grid { grid-template-columns: 1fr; } }

    .loans-card {
        background: var(--card); border: 1px solid var(--border2);
        border-radius: 14px; padding: 18px 20px;
        display: flex; align-items: center; justify-content: space-between;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .loans-card-label { font-size: 11px; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.03em; }
    .loans-card-value { font-size: 24px; font-weight: 800; color: var(--text); margin-top: 4px; }
    .loans-card-icon {
        width: 40px; height: 40px; border-radius: 10px;
        background: var(--bg3); display: flex; align-items: center; justify-content: center;
    }

    .loans-panel {
        background: var(--card); border: 1px solid var(--border2);
        border-radius: 14px; padding: 18px 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .loans-panel-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
    .loans-panel-title { font-size: 16px; font-weight: 700; color: var(--text); }
    .loans-panel-sub { font-size: 12px; color: var(--muted); margin-top: 2px; }

    .loans-item {
        background: var(--bg3); border: 1px solid var(--border2);
        border-radius: 10px; padding: 14px 16px; margin-bottom: 12px;
    }
    .loans-item:last-child { margin-bottom: 0; }
    .loans-item-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 8px; }
    .loans-item-name { font-size: 14px; font-weight: 700; color: var(--text); }
    .loans-item-code { font-size: 12px; color: var(--muted); margin-top: 2px; }
    .loans-item-meta { display: flex; align-items: center; gap: 12px; font-size: 12px; color: var(--subtle); margin-top: 8px; }

    .loans-badge {
        display: inline-flex; align-items: center; padding: 4px 10px;
        border-radius: 6px; font-size: 10px; font-weight: 600; letter-spacing: 0.03em;
    }
    .loans-badge-pending { background: rgba(100,116,139,.12); color: #64748b; border: 1px solid rgba(100,116,139,.2); }
    .loans-badge-approved { background: rgba(37,99,235,.1); color: #2563eb; border: 1px solid rgba(37,99,235,.2); }
    .loans-badge-borrowed { background: rgba(37,99,235,.1); color: #2563eb; border: 1px solid rgba(37,99,235,.2); }
    .loans-badge-returned { background: rgba(16,185,129,.1); color: #10b981; border: 1px solid rgba(16,185,129,.2); }
    .loans-badge-rejected { background: rgba(239,68,68,.1); color: #dc2626; border: 1px solid rgba(239,68,68,.2); }

    .loans-empty { text-align: center; padding: 48px 20px; color: var(--muted); }
    .loans-empty-icon { width: 56px; height: 56px; margin: 0 auto 16px; color: var(--subtle); }
    .loans-empty-title { font-size: 16px; font-weight: 600; color: var(--text); margin-bottom: 4px; }
    .loans-empty-sub { font-size: 13px; color: var(--muted); }
</style>

<div>
    {{-- Summary Cards --}}
    <div class="loans-grid">
        <div class="loans-card">
            <div>
                <div class="loans-card-label">Menunggu</div>
                <div class="loans-card-value">
                    {{ \App\Models\BorrowingRequest::where('user_id', auth()->id())->where('status', 'pending')->count() }}
                </div>
            </div>
            <div class="loans-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:var(--muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="loans-card">
            <div>
                <div class="loans-card-label">Dipinjam</div>
                <div class="loans-card-value">
                    {{ \App\Models\BorrowingRequest::where('user_id', auth()->id())->whereIn('status', ['approved', 'borrowed'])->count() }}
                </div>
            </div>
            <div class="loans-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:var(--muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
        </div>

        <div class="loans-card">
            <div>
                <div class="loans-card-label">Selesai</div>
                <div class="loans-card-value">
                    {{ \App\Models\BorrowingRequest::where('user_id', auth()->id())->where('status', 'returned')->count() }}
                </div>
            </div>
            <div class="loans-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:var(--muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Peminjaman List --}}
    <div class="loans-panel">
        <div class="loans-panel-header">
            <div>
                <div class="loans-panel-title">Daftar Peminjaman</div>
                <div class="loans-panel-sub">Semua permohonan peminjaman Anda</div>
            </div>
        </div>

        @php
            $requests = \App\Models\BorrowingRequest::where('user_id', auth()->id())->latest()->get();
        @endphp

        @if($requests->count() > 0)
            @foreach($requests as $request)
            <div class="loans-item">
                <div class="loans-item-top">
                    <div>
                        <div class="loans-item-name">{{ $request->item?->name ?? 'Barang tidak tersedia' }}</div>
                        <div class="loans-item-code">ID: #{{ $request->id }}</div>
                    </div>
                    <span class="loans-badge
                        @if($request->status === 'pending') loans-badge-pending
                        @elseif($request->status === 'approved' || $request->status === 'qr_ready') loans-badge-approved
                        @elseif($request->status === 'rejected') loans-badge-rejected
                        @elseif($request->status === 'borrowed') loans-badge-borrowed
                        @elseif($request->status === 'returned') loans-badge-returned
                        @else loans-badge-pending @endif">
                        {{ strtoupper($request->status) }}
                    </span>
                </div>
                <div class="loans-item-meta">
                    <div style="display:flex;align-items:center;gap:4px">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $request->created_at->diffForHumans() }}
                    </div>
                    <div>Qty: {{ $request->quantity }}</div>
                    <div>{{ $request->borrow_date->format('d M Y') }} - {{ $request->return_date->format('d M Y') }}</div>
                </div>
            </div>
            @endforeach
        @else
            <div class="loans-empty">
                <svg xmlns="http://www.w3.org/2000/svg" class="loans-empty-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <div class="loans-empty-title">Belum ada peminjaman</div>
                <div class="loans-empty-sub">Mulai pinjam barang sekarang</div>
            </div>
        @endif
    </div>
</div>
@endsection
