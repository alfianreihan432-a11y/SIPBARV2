@extends('layouts.kepala-jurusan')

@section('title', 'Dashboard Kepala Jurusan')
@section('page-heading', 'Dashboard Kepala Jurusan')

@section('content')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 2px 8px rgba(133, 30, 42, 0.05), 0 1px 3px rgba(0,0,0,0.03);
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .stat-icon.pending { background: #fee2e2; color: #991b1b; }
    .stat-icon.active { background: rgba(16, 185, 129, 0.12); color: #059669; }
    html.dark .stat-icon.active { color: #059669; }
    .stat-icon.returns { background: #fee2e2; color: #991b1b; }
    .stat-icon.completed { background: #f1f5f9; color: #475569; }
    
    .stat-info h3 {
        font-size: 26px;
        font-weight: 800;
        margin: 0 0 2px 0;
        color: var(--text);
        line-height: 1.1;
    }
    .stat-info p {
        font-size: 12.5px;
        color: var(--muted);
        margin: 0;
        font-weight: 500;
    }
    
    .section-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 22px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(133, 30, 42, 0.05), 0 1px 3px rgba(0,0,0,0.03);
    }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }
    .section-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-title::before {
        content: '';
        width: 3.5px;
        height: 16px;
        background: var(--accent);
        border-radius: 2px;
        display: inline-block;
    }
    .view-all-btn {
        font-size: 12.5px;
        color: var(--accent);
        text-decoration: none;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 6px;
        transition: all 0.15s ease;
    }
    .view-all-btn:hover {
        background: var(--accent-light);
        color: var(--accent-hover);
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    .table th {
        text-align: left;
        padding: 12px;
        font-size: 12px;
        font-weight: 600;
        color: var(--muted);
        border-bottom: 1px solid var(--border);
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
    .table td {
        padding: 12px;
        font-size: 13px;
        color: var(--text);
        border-bottom: 1px solid var(--border);
    }
    .table tr:last-child td {
        border-bottom: none;
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
    .status-pending { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    .status-approved { background: rgba(16, 185, 129, 0.12); color: #059669; border: 1px solid rgba(16, 185, 129, 0.25); }
    html.dark .status-approved { color: #059669; }
    .status-borrowed { background: #fdf2f4; color: #851e2a; border: 1px solid #f0d5da; }
    .status-returned { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
    
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--muted);
    }
    .empty-state svg {
        width: 48px;
        height: 48px;
        margin-bottom: 12px;
        opacity: 0.4;
    }
    .welcome-banner {
        background: linear-gradient(135deg, #851e2a 0%, #5a141d 100%);
        color: #ffffff;
        border-radius: 12px;
        padding: 20px 24px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        box-shadow: 0 4px 14px rgba(133, 30, 42, 0.2);
    }
    .welcome-text h2 {
        font-size: 18px;
        font-weight: 800;
        margin: 0 0 4px 0;
        color: #ffffff;
    }
    .welcome-text p {
        font-size: 13px;
        margin: 0;
        color: rgba(255, 255, 255, 0.85);
    }
    .welcome-cta {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        color: #851e2a;
        font-size: 13px;
        font-weight: 700;
        padding: 10px 18px;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .welcome-cta:hover {
        background: #fdf2f4;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
</style>

<div class="welcome-banner">
    <div class="welcome-text">
        <h2>Selamat Datang, {{ auth()->user()->name }}!</h2>
        <p>Kelola persetujuan peminjaman dan kirim laporan rekapitulasi jurusan ke Admin.</p>
    </div>
    <a href="{{ route('kajur.reporting') }}" class="welcome-cta">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Kirim Laporan
    </a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon pending">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['pending_approvals'] }}</h3>
            <p>Menunggu Persetujuan</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon active">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['active_borrowings'] }}</h3>
            <p>Peminjaman Aktif</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon returns">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['pending_returns'] }}</h3>
            <p>Pengembalian Pending</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon completed">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['total_completed'] }}</h3>
            <p>Total Selesai</p>
        </div>
    </div>
</div>

<div class="section-card">
    <div class="section-header">
        <h2 class="section-title">Permohonan Peminjaman Terbaru</h2>
        @if($pendingApprovals->count() > 0)
            <a href="{{ route('kajur.pending-approvals') }}" class="view-all-btn">Lihat Semua →</a>
        @endif
    </div>
    
    @if($pendingApprovals->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Guru</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Tanggal Pinjam</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingApprovals as $request)
                    @php $items = $request->items->count() ? $request->items : collect([$request->item])->filter(); @endphp
                    <tr>
                        <td>{{ $request->user->name }}</td>
                        <td>
                            @foreach($items as $detail)
                                {{ $detail->itemWithTrashed?->name ?? $detail->item?->name ?? 'Barang tidak tersedia' }} ({{ $detail->quantity ?? $request->quantity ?? 1 }}){{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </td>
                        <td>{{ $request->items->sum('quantity') ?: ($request->quantity ?? 0) }}</td>
                        <td>{{ $request->borrow_date->format('d/m/Y') }}</td>
                        <td>
                            <span class="status-badge status-pending">Pending</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p>Tidak ada permohonan peminjaman yang menunggu persetujuan</p>
        </div>
    @endif
</div>

<div class="section-card">
    <div class="section-header">
        <h2 class="section-title">Peminjaman Aktif</h2>
        @if($activeBorrowings->count() > 0)
            <a href="{{ route('kajur.history') }}" class="view-all-btn">Lihat Semua →</a>
        @endif
    </div>
    
    @if($activeBorrowings->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Guru</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Tanggal Pinjam</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activeBorrowings as $borrowing)
                    @php $items = $borrowing->items->count() ? $borrowing->items : collect([$borrowing->item])->filter(); @endphp
                    <tr>
                        <td>{{ $borrowing->user->name }}</td>
                        <td>
                            @foreach($items as $detail)
                                {{ $detail->itemWithTrashed?->name ?? $detail->item?->name ?? 'Barang tidak tersedia' }} ({{ $detail->quantity ?? $borrowing->quantity ?? 1 }}){{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </td>
                        <td>{{ $borrowing->items->sum('quantity') ?: ($borrowing->quantity ?? 0) }}</td>
                        <td>{{ $borrowing->borrow_date->format('d/m/Y') }}</td>
                        <td>
                            @if($borrowing->status === 'approved')
                                <span class="status-badge status-approved">Disetujui</span>
                            @elseif($borrowing->status === 'borrowed')
                                <span class="status-badge status-borrowed">Dipinjam</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p>Tidak ada peminjaman aktif saat ini</p>
        </div>
    @endif
</div>
@endsection