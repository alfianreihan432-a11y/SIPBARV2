@extends('layouts.superadmin')

@section('title', 'Laporan & Statistik Sistem – SIPBAR Superadmin')
@section('page-heading', 'Laporan & Statistik')

@section('content')
<style>
    .report-header {
        background: var(--bg-card);
        border: 1px solid var(--border-alt);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: var(--card-shadow);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }
    .report-header-title {
        font-size: 22px;
        font-weight: 800;
        color: var(--text-primary);
        letter-spacing: -0.01em;
        margin-bottom: 4px;
    }
    .report-header-subtitle {
        font-size: 13.5px;
        color: var(--text-muted);
    }
    .report-header-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--bg-card-subtle);
        border: 1px solid var(--border-subtle);
        padding: 8px 14px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 600;
        color: var(--text-secondary);
    }

    .stats-grid-6 {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-card-sa {
        background: var(--bg-card);
        border: 1px solid var(--border-alt);
        border-radius: 16px;
        padding: 20px;
        box-shadow: var(--card-shadow);
        transition: transform 0.2s ease, border-color 0.2s ease;
    }
    .stat-card-sa:hover {
        transform: translateY(-2px);
        border-color: var(--blue);
    }
    .stat-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
    }
    .stat-card-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: var(--bg-card-subtle);
        border: 1px solid var(--border-subtle);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .stat-card-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--text-subtle);
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }
    .stat-card-val {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1.1;
        margin-bottom: 4px;
    }
    .stat-card-sub {
        font-size: 12.5px;
        color: var(--text-muted);
    }

    .grid-2col {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }
    .sa-card {
        background: var(--bg-card);
        border: 1px solid var(--border-alt);
        border-radius: 16px;
        padding: 24px;
        box-shadow: var(--card-shadow);
    }
    .sa-card-title {
        font-size: 17px;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .status-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        background: var(--bg-card-subtle);
        border: 1px solid var(--border-subtle);
        border-radius: 12px;
        margin-bottom: 10px;
        transition: background 0.15s ease;
    }
    .status-item:last-child {
        margin-bottom: 0;
    }
    .status-item:hover {
        background: var(--bg-hover);
    }
    .status-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .status-dot-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .status-title-text {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--text-primary);
    }
    .status-num-count {
        font-size: 18px;
        font-weight: 800;
        color: var(--text-primary);
    }

    .monthly-box {
        text-align: center;
        padding: 24px 16px;
        background: var(--bg-card-subtle);
        border: 1px solid var(--border-subtle);
        border-radius: 14px;
        margin-bottom: 16px;
    }
    .monthly-big-val {
        font-size: 40px;
        font-weight: 800;
        color: var(--blue);
        line-height: 1;
        margin-bottom: 6px;
    }
    .monthly-big-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-muted);
    }

    .rates-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    .rate-box {
        padding: 16px;
        background: var(--bg-card-subtle);
        border: 1px solid var(--border-subtle);
        border-radius: 12px;
        text-align: center;
    }
    .rate-box-val {
        font-size: 22px;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 2px;
    }
    .rate-box-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-muted);
    }

    .activity-card {
        background: var(--bg-card);
        border: 1px solid var(--border-alt);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--card-shadow);
    }
    .activity-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-alt);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .activity-card-title {
        font-size: 17px;
        font-weight: 800;
        color: var(--text-primary);
    }
    .activity-card-subtitle {
        font-size: 12.5px;
        color: var(--text-muted);
        margin-top: 2px;
    }
    .activity-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    .activity-table th {
        background: var(--table-head-bg, var(--bg-card-subtle));
        padding: 12px 20px;
        font-size: 11px;
        font-weight: 700;
        color: var(--text-subtle);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border-bottom: 1px solid var(--border-alt);
    }
    .activity-table td {
        padding: 14px 20px;
        font-size: 13px;
        color: var(--text-primary);
        border-bottom: 1px solid var(--border-subtle);
    }
    .activity-table tr:last-child td {
        border-bottom: none;
    }
    .activity-table tr:hover td {
        background: var(--table-hover, var(--bg-hover));
    }
    .user-info-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .user-cell-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--blue-dark);
        color: #ffffff !important;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11.5px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .sa-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }
    .sa-badge-pending  { background: var(--s-pending, #d97706); color: #fff; border: none; }
    .sa-badge-approved { background: var(--s-approved, #2563eb); color: #fff; border: none; }
    .sa-badge-borrowed { background: var(--s-borrowed, #0891b2); color: #fff; border: none; }
    .sa-badge-returned { background: var(--s-returned, #059669); color: #fff; border: none; }
    .sa-badge-rejected { background: var(--s-rejected, #dc2626); color: #fff; border: none; }
</style>

<div>
    {{-- Header Banner --}}
    <div class="report-header">
        <div>
            <h1 class="report-header-title">Laporan & Statistik Sistem</h1>
            <p class="report-header-subtitle">Ringkasan transaksi peminjaman, ketersediaan barang, dan statistik pengguna di seluruh sekolah</p>
        </div>
        <div class="report-header-badge">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:var(--color-success)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Skala Keseluruhan Sistem
        </div>
    </div>

    {{-- 6 Summary Stat Cards --}}
    <div class="stats-grid-6">
        <div class="stat-card-sa">
            <div class="stat-card-head">
                <div class="stat-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:var(--blue)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <span class="stat-card-label">Total</span>
            </div>
            <div class="stat-card-val">{{ number_format($totalRequests) }}</div>
            <div class="stat-card-sub">Total Pengajuan</div>
        </div>

        <div class="stat-card-sa">
            <div class="stat-card-head">
                <div class="stat-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:var(--color-success)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="stat-card-label">Tersedia</span>
            </div>
            <div class="stat-card-val" style="color:var(--color-success)">{{ number_format($availableStock) }}</div>
            <div class="stat-card-sub">Unit Barang Tersedia</div>
        </div>

        <div class="stat-card-sa">
            <div class="stat-card-head">
                <div class="stat-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:var(--color-borrowed)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
                <span class="stat-card-label">Dipinjam</span>
            </div>
            <div class="stat-card-val" style="color:var(--color-borrowed)">{{ number_format($borrowedStock) }}</div>
            <div class="stat-card-sub">Unit Barang Dipinjam</div>
        </div>

        <div class="stat-card-sa">
            <div class="stat-card-head">
                <div class="stat-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:var(--color-info)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <span class="stat-card-label">Katalog</span>
            </div>
            <div class="stat-card-val">{{ number_format($totalItemTypes) }}</div>
            <div class="stat-card-sub">Jenis Barang ({{ number_format($totalItemStock) }} Total Unit)</div>
        </div>

        <div class="stat-card-sa">
            <div class="stat-card-head">
                <div class="stat-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#a855f7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <span class="stat-card-label">Siswa</span>
            </div>
            <div class="stat-card-val">{{ number_format($totalStudents) }}</div>
            <div class="stat-card-sub">Total Siswa Terdaftar</div>
        </div>

        <div class="stat-card-sa">
            <div class="stat-card-head">
                <div class="stat-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#ec4899" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H14" />
                    </svg>
                </div>
                <span class="stat-card-label">Guru</span>
            </div>
            <div class="stat-card-val">{{ number_format($totalTeachers) }}</div>
            <div class="stat-card-sub">Total Guru & Kajur</div>
        </div>
    </div>

    {{-- Status Breakdown & Monthly Stats --}}
    <div class="grid-2col">
        {{-- Status Breakdown --}}
        <div class="sa-card">
            <h2 class="sa-card-title">
                <span>Distribusi Status Peminjaman</span>
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:var(--text-muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                </svg>
            </h2>

            <div class="status-item">
                <div class="status-left">
                    <div class="status-dot-icon" style="background:rgba(245, 158, 11, 0.15)">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#fbbf24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="status-title-text">Menunggu Persetujuan (Pending)</span>
                </div>
                <span class="status-num-count" style="color:#fbbf24">{{ number_format($pendingRequests) }}</span>
            </div>

            <div class="status-item">
                <div class="status-left">
                    <div class="status-dot-icon" style="background:rgba(59, 130, 246, 0.15)">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#60a5fa" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <span class="status-title-text">Aktif (Disetujui / Dipinjam)</span>
                </div>
                <span class="status-num-count" style="color:#60a5fa">{{ number_format($approvedRequests) }}</span>
            </div>

            <div class="status-item">
                <div class="status-left">
                    <div class="status-dot-icon" style="background:rgba(16, 185, 129, 0.15)">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#34d399" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="status-title-text">Selesai (Dikembalikan)</span>
                </div>
                <span class="status-num-count" style="color:#34d399">{{ number_format($completedRequests) }}</span>
            </div>

            <div class="status-item">
                <div class="status-left">
                    <div class="status-dot-icon" style="background:rgba(239, 68, 68, 0.15)">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#f87171" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <span class="status-title-text">Ditolak (Rejected)</span>
                </div>
                <span class="status-num-count" style="color:#f87171">{{ number_format($rejectedRequests) }}</span>
            </div>
        </div>

        {{-- Monthly Performance & Rates --}}
        <div class="sa-card">
            <h2 class="sa-card-title">
                <span>Performa Bulan Ini</span>
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:var(--text-muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </h2>

            <div class="monthly-box">
                <div class="monthly-big-val">{{ number_format($thisMonthRequests) }}</div>
                <div class="monthly-big-label">Pengajuan Peminjaman di Bulan {{ now()->format('F Y') }}</div>
            </div>

            <div class="rates-row">
                <div class="rate-box">
                    <div class="rate-box-val" style="color:var(--color-success)">{{ $completionRate }}%</div>
                    <div class="rate-box-label">Completion Rate</div>
                </div>
                <div class="rate-box">
                    <div class="rate-box-val" style="color:var(--color-danger)">{{ $rejectionRate }}%</div>
                    <div class="rate-box-label">Rejection Rate</div>
                </div>
            </div>
        </div>
    </div>

    {{-- System Recent Activity List --}}
    <div class="activity-card">
        <div class="activity-card-header">
            <div>
                <h2 class="activity-card-title">10 Transaksi Terbaru di Sistem</h2>
                <p class="activity-card-subtitle">Catatan transaksi permohonan peminjaman paling mutakhir dari seluruh siswa</p>
            </div>
            <a href="{{ route('superadmin.loans') }}" style="font-size:12px;font-weight:700;color:var(--blue);text-decoration:none">
                Lihat Semua Peminjaman →
            </a>
        </div>

        @if($recentActivities->count() > 0)
        <div class="table-responsive">
            <table class="activity-table">
                <thead>
                    <tr>
                        <th>Peminjam</th>
                        <th>Nama Barang</th>
                        <th>Guru Penanggung Jawab</th>
                        <th>Status</th>
                        <th>Waktu Pengajuan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentActivities as $act)
                    <tr>
                        <td>
                            <div class="user-info-cell">
                                <div class="user-cell-avatar">
                                    {{ strtoupper(substr($act->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:700">{{ $act->user->name ?? 'User Tidak Ditemukan' }}</div>
                                    <div style="font-size:11px;color:var(--text-muted)">{{ $act->user->kelas ?? 'Siswa' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight:700">{{ $act->itemWithTrashed?->name ?? 'Barang tidak tersedia' }}</div>
                            <div style="font-size:11px;color:var(--text-muted)">Qty: {{ $act->quantity }} unit</div>
                        </td>
                        <td>
                            <div style="font-size:12.5px;font-weight:600;color:var(--text-secondary)">
                                {{ $act->teacher?->name ?? '-' }}
                            </div>
                        </td>
                        <td>
                            @php
                                $badgeCls = match($act->status) {
                                    'pending'  => 'sa-badge-pending',
                                    'approved', 'qr_ready' => 'sa-badge-approved',
                                    'borrowed' => 'sa-badge-borrowed',
                                    'returned' => 'sa-badge-returned',
                                    'rejected' => 'sa-badge-rejected',
                                    default    => 'sa-badge-pending'
                                };
                            @endphp
                            <span class="sa-badge {{ $badgeCls }}" style="display:inline-flex;align-items:center;gap:5px">
                                <span style="width:7px;height:7px;border-radius:50%;background:currentColor;flex-shrink:0;display:inline-block"></span>
                                {{ $act->status_label }}
                            </span>
                        </td>
                        <td style="font-size:12px;color:var(--text-muted)">
                            {{ $act->created_at->diffForHumans() }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div style="padding:40px;text-align:center;color:var(--text-muted)">
            Belum ada data transaksi peminjaman di dalam sistem.
        </div>
        @endif
    </div>
</div>
@endsection