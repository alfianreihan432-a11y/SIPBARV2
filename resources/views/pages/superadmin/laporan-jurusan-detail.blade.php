@extends('layouts.superadmin')

@section('title', 'Detail Laporan Jurusan')
@section('page-heading', 'Detail Laporan Jurusan')

@section('content')
<style>
    .detail-header {
        background: var(--bg-card);
        border: 1px solid var(--border-alt);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: var(--card-shadow);
    }
    .detail-title {
        font-size: 22px;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 8px;
    }
    .detail-meta {
        font-size: 13px;
        color: var(--text-muted);
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }
    .detail-meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .detail-meta-item svg {
        width: 14px;
        height: 14px;
        opacity: 0.7;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .status-pending  { background: rgba(245,158,11,0.12); color: #f59e0b; border: 1px solid rgba(245,158,11,0.25); }
    .status-disetujui{ background: rgba(16,185,129,0.12); color: #10b981; border: 1px solid rgba(16,185,129,0.25); }
    .status-ditolak  { background: rgba(239,68,68,0.12);  color: #ef4444; border: 1px solid rgba(239,68,68,0.25);  }

    .grid-2col {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }
    .info-card {
        background: var(--bg-card);
        border: 1px solid var(--border-alt);
        border-radius: 16px;
        padding: 20px;
        box-shadow: var(--card-shadow);
    }
    .info-card-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .info-card-title svg {
        width: 18px;
        height: 18px;
        color: var(--blue);
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid var(--border-subtle);
        font-size: 13px;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-label {
        color: var(--text-muted);
        font-weight: 500;
    }
    .info-value {
        color: var(--text-primary);
        font-weight: 600;
        text-align: right;
    }

    .action-bar {
        display: flex;
        gap: 10px;
        margin-top: 20px;
        flex-wrap: wrap;
    }
    .btn {
        padding: 10px 18px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-primary {
        background: var(--blue);
        color: #ffffff !important;
    }
    .btn-primary:hover {
        background: var(--blue-dark);
    }
    .btn-danger {
        background: rgba(239,68,68,0.12);
        color: #ef4444;
        border: 1px solid rgba(239,68,68,0.25);
    }
    .btn-danger:hover {
        background: rgba(239,68,68,0.22);
    }
    .btn-secondary {
        background: var(--bg-card-subtle);
        color: var(--text-primary);
        border: 1px solid var(--border-alt);
    }
    .btn-secondary:hover {
        background: var(--border-alt);
    }

    .table-responsive {
        overflow-x: auto;
    }
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    .data-table th {
        background: var(--bg-card-subtle);
        padding: 12px 16px;
        font-size: 11px;
        font-weight: 700;
        color: var(--text-subtle);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border-bottom: 1px solid var(--border-alt);
        text-align: left;
    }
    .data-table td {
        padding: 14px 16px;
        font-size: 13px;
        color: var(--text-primary);
        border-bottom: 1px solid var(--border-subtle);
    }
    .data-table tr:last-child td {
        border-bottom: none;
    }
    .data-table tr:hover td {
        background: var(--bg-hover);
    }
</style>

@if(session('success'))
    <div style="background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3); color: var(--color-success); padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 13.5px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
        <svg style="width: 18px; height: 18px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: var(--color-danger); padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 13.5px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
        <svg style="width: 18px; height: 18px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        {{ session('error') }}
    </div>
@endif

<!-- Header -->
<div class="detail-header">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
        <div>
            <h1 class="detail-title">Laporan Jurusan {{ $report->jurusan->nama ?? '-' }}</h1>
            <div class="detail-meta">
                <div class="detail-meta-item">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    ID Laporan: #{{ $report->id }}
                </div>
                <div class="detail-meta-item">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $report->periode_awal->format('d/m/Y') }} - {{ $report->periode_akhir->format('d/m/Y') }}
                </div>
                <div class="detail-meta-item">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $report->created_at->format('d/m/Y, H:i') }}
                </div>
            </div>
        </div>
        @php
            $statusClass = match($report->status) {
                'pending_review' => 'status-pending',
                'disetujui'      => 'status-disetujui',
                'ditolak'        => 'status-ditolak',
                default          => '',
            };
            $statusLabel = match($report->status) {
                'pending_review' => 'Pending Review',
                'disetujui'      => 'Disetujui',
                'ditolak'        => 'Ditolak',
                default          => ucfirst($report->status),
            };
        @endphp
        <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
    </div>

    @if($report->status === 'pending_review')
    <div class="action-bar">
        <button type="button" onclick="showApproveModal()" class="btn btn-primary">
            <svg style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Setujui Laporan
        </button>
        <button type="button" onclick="showRejectModal()" class="btn btn-danger">
            <svg style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Tolak Laporan
        </button>
    </div>
    @endif
</div>

<!-- Info Grid -->
<div class="grid-2col">
    <!-- Report Info -->
    <div class="info-card">
        <h2 class="info-card-title">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Informasi Laporan
        </h2>
        <div class="info-row">
            <span class="info-label">Jurusan</span>
            <span class="info-value">{{ $report->jurusan->nama ?? '-' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Pengirim</span>
            <span class="info-value">{{ $report->pengirim->name ?? '-' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Periode Awal</span>
            <span class="info-value">{{ $report->periode_awal->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Periode Akhir</span>
            <span class="info-value">{{ $report->periode_akhir->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Status</span>
            <span class="info-value">{{ $statusLabel }}</span>
        </div>
        @if($report->catatan_admin)
        <div class="info-row">
            <span class="info-label">Catatan Admin</span>
            <span class="info-value">{{ $report->catatan_admin }}</span>
        </div>
        @endif
    </div>

    <!-- Statistics -->
    <div class="info-card">
        <h2 class="info-card-title">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Statistik Peminjaman
        </h2>
        @if($statistics)
        <div class="info-row">
            <span class="info-label">Total Peminjaman</span>
            <span class="info-value">{{ $statistics['total_peminjaman'] ?? 0 }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Disetujui</span>
            <span class="info-value">{{ $statistics['disetujui'] ?? 0 }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Ditolak</span>
            <span class="info-value">{{ $statistics['ditolak'] ?? 0 }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Dikembalikan</span>
            <span class="info-value">{{ $statistics['dikembalikan'] ?? 0 }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Masih Dipinjam</span>
            <span class="info-value">{{ $statistics['masih_dipinjam'] ?? 0 }}</span>
        </div>
        @else
        <div style="text-align: center; padding: 20px; color: var(--text-muted);">
            Statistik tidak tersedia
        </div>
        @endif
    </div>
</div>

<!-- Borrowing Requests -->
@if($borrowingRequests && $borrowingRequests->count() > 0)
<div class="info-card">
    <h2 class="info-card-title">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Daftar Peminjaman ({{ $borrowingRequests->count() }})
    </h2>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Siswa</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($borrowingRequests as $req)
                <tr>
                    <td>#{{ $req->id }}</td>
                    <td>{{ $req->user->name ?? '-' }}</td>
                    <td>{{ $req->itemWithTrashed?->name ?? $req->item?->name ?? '-' }}</td>
                    <td>{{ $req->quantity ?? 1 }}</td>
                    <td>{{ ucfirst($req->status) }}</td>
                    <td>{{ $req->created_at->format('d/m/Y') }}</td>
                    <td>
                        <button type="button" onclick="showDetailModal({{ $req->id }})" style="padding: 6px 12px; background: var(--blue); color: #ffffff; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">
                            Detail
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Action Buttons -->
<div class="action-bar">
    <a href="{{ route('superadmin.laporan-jurusan') }}" class="btn btn-secondary">
        <svg style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke Daftar
    </a>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:100;align-items:center;justify-content:center;backdrop-filter:blur(3px);">
    <div style="background:var(--bg-card);border-radius:16px;padding:28px;width:100%;max-width:480px;border:1px solid var(--border-alt);box-shadow:0 20px 48px rgba(0,0,0,0.18);">
        <h3 style="font-size:18px;font-weight:700;color:var(--text-primary);margin-bottom:16px;">Setujui Laporan</h3>
        <form method="POST" action="{{ route('superadmin.laporan-jurusan.approve', $report->id) }}">
            @csrf
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:6px;">Catatan (Opsional)</label>
                <textarea name="catatan_admin" style="width:100%;padding:10px 12px;border:1px solid var(--border-alt);border-radius:8px;font-size:13px;background:var(--bg-card-subtle);color:var(--text-primary);outline:none;resize:vertical;min-height:90px;box-sizing:border-box;font-family:inherit;line-height:1.5;" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" onclick="hideApproveModal()" style="padding:8px 16px;background:var(--bg-card-subtle);color:var(--text-primary);border:1px solid var(--border-alt);border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">Batal</button>
                <button type="submit" style="padding:8px 16px;background:var(--color-success);color:#ffffff !important;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">Setujui</button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:100;align-items:center;justify-content:center;backdrop-filter:blur(3px);">
    <div style="background:var(--bg-card);border-radius:16px;padding:28px;width:100%;max-width:480px;border:1px solid var(--border-alt);box-shadow:0 20px 48px rgba(0,0,0,0.18);">
        <h3 style="font-size:18px;font-weight:700;color:var(--text-primary);margin-bottom:16px;">Tolak Laporan</h3>
        <form method="POST" action="{{ route('superadmin.laporan-jurusan.reject', $report->id) }}">
            @csrf
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:6px;">Alasan Penolakan <span style="color:#ef4444">*</span></label>
                <textarea name="catatan_admin" required style="width:100%;padding:10px 12px;border:1px solid var(--border-alt);border-radius:8px;font-size:13px;background:var(--bg-card-subtle);color:var(--text-primary);outline:none;resize:vertical;min-height:90px;box-sizing:border-box;font-family:inherit;line-height:1.5;" placeholder="Jelaskan alasan penolakan..."></textarea>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" onclick="hideRejectModal()" style="padding:8px 16px;background:var(--bg-card-subtle);color:var(--text-primary);border:1px solid var(--border-alt);border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">Batal</button>
                <button type="submit" style="padding:8px 16px;background:var(--color-danger);color:#ffffff !important;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">Tolak</button>
            </div>
        </form>
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:100;align-items:center;justify-content:center;backdrop-filter:blur(3px);">
    <div style="background:var(--bg-card);border-radius:16px;padding:28px;width:100%;max-width:600px;border:1px solid var(--border-alt);box-shadow:0 20px 48px rgba(0,0,0,0.18);max-height:90vh;overflow-y:auto;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h3 style="font-size:18px;font-weight:700;color:var(--text-primary);">Detail Peminjaman</h3>
            <button type="button" onclick="hideDetailModal()" style="background:none;border:none;font-size:24px;color:var(--text-muted);cursor:pointer;">&times;</button>
        </div>
        <div id="detailContent"></div>
    </div>
</div>

<script>
const borrowingRequestsData = @json($borrowingRequests);

function showDetailModal(requestId) {
    const req = borrowingRequestsData.find(r => r.id === requestId);
    if (!req) return;

    let content = '<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">';
    content += '<div>';
    content += '<div style="font-size:11px;font-weight:700;color:var(--text-subtle);text-transform:uppercase;margin-bottom:4px;">Data Peminjam</div>';
    content += '<div style="font-size:14px;font-weight:600;color:var(--text-primary);">' + (req.user?.name || '-') + '</div>';
    content += '<div style="font-size:12px;color:var(--text-muted);margin-top:2px;">' + (req.user?.jurusan?.nama || '-') + (req.user?.kelas ? ' (' + req.user.kelas + ')' : '') + '</div>';
    content += '<div style="font-size:12px;color:var(--text-muted);margin-top:2px;">WA: ' + (req.user?.whatsapp_number || '-') + '</div>';
    content += '</div>';
    content += '<div>';
    content += '<div style="font-size:11px;font-weight:700;color:var(--text-subtle);text-transform:uppercase;margin-bottom:4px;">Guru Penanggung Jawab</div>';
    content += '<div style="font-size:14px;font-weight:600;color:var(--text-primary);">' + (req.teacher?.name || '-') + '</div>';
    content += '</div>';
    content += '</div>';

    content += '<div style="margin-bottom:20px;">';
    content += '<div style="font-size:11px;font-weight:700;color:var(--text-subtle);text-transform:uppercase;margin-bottom:8px;">Data Pengisian Form</div>';
    content += '<div style="background:var(--bg-card-subtle);border:1px solid var(--border-subtle);border-radius:10px;padding:14px;">';
    content += '<div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border-subtle);">';
    content += '<span style="font-size:12px;color:var(--text-muted);">Keperluan</span>';
    content += '<span style="font-size:12px;font-weight:600;color:var(--text-primary);">' + (req.purpose || '-') + '</span>';
    content += '</div>';
    content += '<div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border-subtle);">';
    content += '<span style="font-size:12px;color:var(--text-muted);">Tanggal Pinjam</span>';
    content += '<span style="font-size:12px;font-weight:600;color:var(--text-primary);">' + (req.borrow_date || '-') + '</span>';
    content += '</div>';
    content += '<div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border-subtle);">';
    content += '<span style="font-size:12px;color:var(--text-muted);">Tanggal Kembali</span>';
    content += '<span style="font-size:12px;font-weight:600;color:var(--text-primary);">' + (req.return_date || '-') + '</span>';
    content += '</div>';
    content += '<div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border-subtle);">';
    content += '<span style="font-size:12px;color:var(--text-muted);">Jam Kembali</span>';
    content += '<span style="font-size:12px;font-weight:600;color:var(--text-primary);">' + (req.return_time || '-') + '</span>';
    content += '</div>';
    if (req.notes) {
        content += '<div style="display:flex;justify-content:space-between;padding:6px 0;">';
        content += '<span style="font-size:12px;color:var(--text-muted);">Catatan</span>';
        content += '<span style="font-size:12px;font-weight:600;color:var(--text-primary);">' + (req.notes || '-') + '</span>';
        content += '</div>';
    }
    content += '</div>';
    content += '</div>';

    content += '<div>';
    content += '<div style="font-size:11px;font-weight:700;color:var(--text-subtle);text-transform:uppercase;margin-bottom:8px;">Data Barang Dipinjam</div>';
    content += '<div style="background:var(--bg-card-subtle);border:1px solid var(--border-subtle);border-radius:10px;padding:14px;">';
    content += '<div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border-subtle);">';
    content += '<span style="font-size:12px;color:var(--text-muted);">Nama Barang</span>';
    content += '<span style="font-size:12px;font-weight:600;color:var(--text-primary);">' + (req.itemWithTrashed?.name || req.item?.name || '-') + '</span>';
    content += '</div>';
    content += '<div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border-subtle);">';
    content += '<span style="font-size:12px;color:var(--text-muted);">Kode Barang</span>';
    content += '<span style="font-size:12px;font-weight:600;color:var(--text-primary);">' + (req.itemWithTrashed?.code || req.item?.code || '-') + '</span>';
    content += '</div>';
    content += '<div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border-subtle);">';
    content += '<span style="font-size:12px;color:var(--text-muted);">Jumlah</span>';
    content += '<span style="font-size:12px;font-weight:600;color:var(--text-primary);">' + (req.quantity || 1) + ' unit</span>';
    content += '</div>';
    content += '<div style="display:flex;justify-content:space-between;padding:6px 0;">';
    content += '<span style="font-size:12px;color:var(--text-muted);">Kategori</span>';
    content += '<span style="font-size:12px;font-weight:600;color:var(--text-primary);">' + (req.itemWithTrashed?.category?.name || req.item?.category?.name || '-') + '</span>';
    content += '</div>';
    content += '</div>';
    content += '</div>';

    document.getElementById('detailContent').innerHTML = content;
    document.getElementById('detailModal').style.display = 'flex';
}

function hideDetailModal() {
    document.getElementById('detailModal').style.display = 'none';
}

function showApproveModal() {
    document.getElementById('approveModal').style.display = 'flex';
}

function hideApproveModal() {
    document.getElementById('approveModal').style.display = 'none';
}

function showRejectModal() {
    document.getElementById('rejectModal').style.display = 'flex';
}

function hideRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideDetailModal();
        hideApproveModal();
        hideRejectModal();
    }
});
</script>
@endsection
