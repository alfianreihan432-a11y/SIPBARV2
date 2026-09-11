@extends('layouts.guru')

@section('title', 'Riwayat Pengembalian')
@section('page-heading', 'Riwayat Pengembalian')

@section('content')
<style>
    .section-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }
    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin: 0;
    }
    
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        background: var(--bg3);
        color: var(--text);
        border: 1px solid var(--border);
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        text-decoration: none;
    }
    .back-btn:hover {
        background: var(--border);
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
    
    .condition-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
    }
    .condition-baik { background: rgba(16, 185, 129, 0.12); color: #10b981; }
    .condition-rusak-ringan { background: rgba(245, 158, 11, 0.12); color: #f59e0b; }
    .condition-rusak-berat { background: rgba(239, 68, 68, 0.12); color: #ef4444; }
    
    .verification-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
    }
    .verification-verified { background: rgba(16, 185, 129, 0.12); color: #10b981; }
    .verification-pending { background: rgba(245, 158, 11, 0.12); color: #f59e0b; }
    
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--muted);
    }
    .empty-state svg {
        width: 48px;
        height: 48px;
        margin-bottom: 12px;
        opacity: 0.5;
    }
</style>

<div class="section-card">
    <div class="section-header">
        <h2 class="section-title">Riwayat Pengembalian</h2>
        <a href="{{ route('teacher.pengembalian-guru') }}" class="back-btn">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>
    
    @if($returns->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Kondisi Barang</th>
                    <th>Catatan</th>
                    <th>Status Verifikasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($returns as $return)
                    <tr>
                        <td>{{ $return->item->name }}</td>
                        <td>{{ $return->quantity }}</td>
                        <td>{{ $return->borrow_date->format('d/m/Y') }}</td>
                        <td>{{ $return->returned_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($return->return_condition === 'Baik')
                                <span class="condition-badge condition-baik">Baik</span>
                            @elseif($return->return_condition === 'Rusak Ringan')
                                <span class="condition-badge condition-rusak-ringan">Rusak Ringan</span>
                            @elseif($return->return_condition === 'Rusak Berat')
                                <span class="condition-badge condition-rusak-berat">Rusak Berat</span>
                            @else
                                <span class="condition-badge">{{ $return->return_condition }}</span>
                            @endif
                        </td>
                        <td>{{ Str::limit($return->return_notes ?? '-', 20) }}</td>
                        <td>
                            @if($return->checkin_by)
                                <span class="verification-badge verification-verified">Terverifikasi</span>
                            @else
                                <span class="verification-badge verification-pending">Menunggu Verifikasi</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($returns->hasPages())
            <div style="margin-top: 20px; display: flex; justify-content: center; gap: 8px;">
                {{ $returns->links() }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p>Belum ada riwayat pengembalian</p>
        </div>
    @endif
</div>
@endsection