@extends('layouts.guru')

@section('title', 'Pengembalian')
@section('page-heading', 'Pengembalian')

@section('content')
<style>
    .page-header {
        background: var(--card);
        border: 1px solid var(--border2);
        border-radius: 14px;
        padding: 24px;
        margin-bottom: 20px;
    }
    .page-header-content {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    @media (min-width: 768px) {
        .page-header-content {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }
    .page-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 4px;
    }
    .page-subtitle {
        font-size: 14px;
        color: var(--muted);
    }
    .stats-container {
        display: flex;
        gap: 12px;
    }
    .stat-box {
        text-align: center;
        padding: 12px 16px;
        background: var(--bg3);
        border: 1px solid var(--border2);
        border-radius: 10px;
        min-width: 80px;
    }
    .stat-label {
        font-size: 12px;
        color: var(--muted);
        font-weight: 600;
        margin-bottom: 4px;
    }
    .stat-value {
        font-size: 20px;
        font-weight: 700;
        color: var(--accent);
    }
    
    .table-container {
        background: var(--card);
        border: 1px solid var(--border2);
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 20px;
    }
    .table-wrapper {
        overflow-x: auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    thead {
        background: var(--bg3);
        border-bottom: 1px solid var(--border2);
    }
    th {
        padding: 12px 16px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    tbody tr {
        border-bottom: 1px solid var(--border2);
    }
    tbody tr:last-child {
        border-bottom: none;
    }
    tbody tr:hover {
        background: var(--bg3);
    }
    td {
        padding: 16px;
    }
    .user-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .user-avatar-small {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--accent);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }
    .user-info {
        flex: 1;
    }
    .user-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 2px;
    }
    .user-email {
        font-size: 12px;
        color: var(--muted);
    }
    .item-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
    }
    .quantity {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
    }
    .date-text {
        font-size: 13px;
        color: var(--muted);
    }
    .date-text.overdue {
        color: #ef4444;
        font-weight: 600;
    }
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 700;
    }
    .status-borrowed {
        background: #dbeafe;
        color: #1e40af;
    }
    .btn {
        padding: 8px 16px;
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }
    .btn:hover {
        opacity: 0.9;
    }
    .btn-success {
        background: #10b981;
    }
    .btn-success:hover {
        background: #059669;
    }
    
    .empty-state {
        background: var(--card);
        border: 1px solid var(--border2);
        border-radius: 14px;
        padding: 48px;
        text-align: center;
    }
    .empty-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 16px;
        color: var(--subtle);
    }
    .empty-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 8px;
    }
    .empty-text {
        font-size: 14px;
        color: var(--muted);
    }
</style>

<div>
    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-header-content">
            <div>
                <h1 class="page-title">Pengembalian Barang</h1>
                <p class="page-subtitle">Proses pengembalian barang yang sudah selesai dipinjam</p>
            </div>
            
            {{-- Quick Stats --}}
            <div class="stats-container">
                @php
                    $returnedToday = \App\Models\BorrowingRequest::where('teacher_id', auth()->id())
                        ->where('status', 'returned')
                        ->whereDate('updated_at', today())
                        ->count();
                    $totalReturned = \App\Models\BorrowingRequest::where('teacher_id', auth()->id())
                        ->where('status', 'returned')
                        ->count();
                @endphp
                <div class="stat-box">
                    <p class="stat-label">Hari Ini</p>
                    <p class="stat-value">{{ $returnedToday }}</p>
                </div>
                <div class="stat-box">
                    <p class="stat-label">Total</p>
                    <p class="stat-value">{{ $totalReturned }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Ready for Return List --}}
    @php
        $readyForReturn = \App\Models\BorrowingRequest::with(['user', 'item'])
            ->where('teacher_id', auth()->id())
            ->whereIn('status', ['borrowed'])
            ->latest()
            ->get();
    @endphp

    @if($readyForReturn->isNotEmpty())
    <div class="table-container">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Siswa</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Tanggal Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($readyForReturn as $item)
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar-small">
                                    {{ strtoupper(substr($item->user->name ?? 'N/A', 0, 2)) }}
                                </div>
                                <div class="user-info">
                                    <p class="user-name">{{ $item->user->name ?? 'N/A' }}</p>
                                    <p class="user-email">{{ $item->user->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p class="item-name">{{ $item->item->name ?? 'N/A' }}</p>
                        </td>
                        <td>
                            <p class="quantity">{{ $item->quantity }} unit</p>
                        </td>
                        <td>
                            <p class="date-text">{{ \Carbon\Carbon::parse($item->borrow_date)->format('d M Y') }}</p>
                        </td>
                        <td>
                            <p class="date-text {{ $item->return_date < now() ? 'overdue' : '' }}">
                                {{ \Carbon\Carbon::parse($item->return_date)->format('d M Y') }} @if($item->return_time) · {{ $item->return_time }}@endif
                            </p>
                        </td>
                        <td>
                            <span class="status-badge status-borrowed">
                                Dipinjam
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('teacher.returns.process', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    Proses Kembali
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="empty-state">
        <svg class="empty-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="empty-title">Tidak Ada Barang Siap Dikembalikan</h3>
        <p class="empty-text">Belum ada barang yang siap untuk diproses pengembaliannya</p>
    </div>
    @endif
</div>
@endsection
