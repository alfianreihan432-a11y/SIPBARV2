@extends('layouts.kepala-jurusan')

@section('title', 'Pelaporan')
@section('page-heading', 'Pelaporan')

@section('content')
<style>
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
    
    .create-report-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(133, 30, 42, 0.05), 0 1px 3px rgba(0,0,0,0.03);
    }
    .create-report-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .create-report-title::before {
        content: '';
        width: 3.5px;
        height: 16px;
        background: var(--accent);
        border-radius: 2px;
        display: inline-block;
    }
    .create-report-desc {
        font-size: 13px;
        color: var(--muted);
        margin-bottom: 20px;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
    }
    .form-input {
        padding: 10px 12px;
        border: 1px solid #f0d5da;
        border-radius: 8px;
        font-size: 13px;
        background: #ffffff;
        color: #0f172a;
        outline: none;
    }
    .form-input:focus {
        border-color: var(--accent);
    }
    .submit-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: var(--accent);
        color: #ffffff;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    .submit-btn:hover {
        background: var(--accent-hover);
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
    .status-disetujui { background: rgba(16, 185, 129, 0.12); color: #059669; border: 1px solid rgba(16, 185, 129, 0.25); }
    html.dark .status-disetujui { color: #059669; }
    .status-ditolak { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #64748b;
    }
    .empty-state svg {
        width: 48px;
        height: 48px;
        margin-bottom: 12px;
        opacity: 0.5;
    }
    
    .stats-preview {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 12px;
        margin-top: 12px;
    }
    .stat-item {
        background: #fdf6f7;
        border: 1px solid #f0d5da;
        border-radius: 8px;
        padding: 12px;
        text-align: center;
    }
    .stat-value {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 4px;
    }
    .stat-label {
        font-size: 11px;
        color: #851e2a;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
</style>

@if(session('success'))
    <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 13.5px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
        <svg style="width: 18px; height: 18px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 13.5px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
        <svg style="width: 18px; height: 18px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 13.5px;">
        <strong>Perhatian:</strong>
        <ul style="margin: 6px 0 0 18px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="create-report-card">
    <h2 class="create-report-title">Buat Laporan Baru</h2>
    <p class="create-report-desc">Buat laporan peminjaman jurusan untuk periode tertentu dan kirim ke Admin untuk review</p>
    
    <form method="POST" action="{{ route('kajur.create-report') }}">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Periode Awal *</label>
                <input type="date" name="periode_awal" class="form-input" value="{{ old('periode_awal', now()->startOfMonth()->format('Y-m-d')) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Periode Akhir *</label>
                <input type="date" name="periode_akhir" class="form-input" value="{{ old('periode_akhir', now()->format('Y-m-d')) }}" required>
            </div>
        </div>
        <button type="submit" class="submit-btn">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
            Buat & Kirim Laporan
        </button>
    </form>
</div>

<div class="section-card">
    <div class="section-header">
        <h2 class="section-title">Riwayat Laporan</h2>
        <span style="font-size: 13px; color: var(--muted);">
            {{ $existingReports->count() }} laporan
        </span>
    </div>
    
    @if($existingReports->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Periode</th>
                    <th>Dikirim Oleh</th>
                    <th>Tanggal Kirim</th>
                    <th>Status</th>
                    <th>Catatan Admin</th>
                </tr>
            </thead>
            <tbody>
                @foreach($existingReports as $report)
                    <tr>
                        <td>
                            {{ $report->periode_awal->format('d/m/Y') }} - {{ $report->periode_akhir->format('d/m/Y') }}
                        </td>
                        <td>{{ $report->pengirim->name }}</td>
                        <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($report->status === 'pending_review')
                                <span class="status-badge status-pending">Pending Review</span>
                            @elseif($report->status === 'disetujui')
                                <span class="status-badge status-disetujui">Disetujui</span>
                            @elseif($report->status === 'ditolak')
                                <span class="status-badge status-ditolak">Ditolak</span>
                            @else
                                <span class="status-badge">{{ ucfirst($report->status) }}</span>
                            @endif
                        </td>
                        <td>{{ Str::limit($report->catatan_admin ?? '-', 25) }}</td>
                    </tr>
                    
                    {{-- Show statistics for this report --}}
                    <tr>
                        <td colspan="5" style="padding: 16px 12px; background: var(--bg3);">
                            <div class="stats-preview">
                                <div class="stat-item">
                                    <div class="stat-value">{{ $report->statistics['total_dipinjam'] }}</div>
                                    <div class="stat-label">Dipinjam</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">{{ $report->statistics['total_dikembalikan'] }}</div>
                                    <div class="stat-label">Dikembalikan</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">{{ $report->statistics['total_pending'] }}</div>
                                    <div class="stat-label">Pending</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">{{ $report->statistics['total_rejected'] }}</div>
                                    <div class="stat-label">Ditolak</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">{{ $report->statistics['barang_kondisi_baik'] }}</div>
                                    <div class="stat-label">Kondisi Baik</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">{{ $report->statistics['barang_kondisi_rusak_ringan'] }}</div>
                                    <div class="stat-label">Rusak Ringan</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">{{ $report->statistics['barang_kondisi_rusak_berat'] }}</div>
                                    <div class="stat-label">Rusak Berat</div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($existingReports->first() && $existingReports->first()->histories && $existingReports->first()->histories->count() > 0)
            <div style="margin-top: 28px; border-top: 1px solid var(--border); padding-top: 20px;">
                <h3 style="font-size: 15px; font-weight: 700; color: var(--text); margin-bottom: 12px;">Riwayat Pengajuan Sebelumnya ({{ $existingReports->first()->histories->count() }})</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Periode</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Status</th>
                            <th>Catatan Admin</th>
                            <th>Waktu Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($existingReports->first()->histories as $hist)
                            <tr>
                                <td>{{ $hist->periode_awal ? $hist->periode_awal->format('d/m/Y') : '-' }} - {{ $hist->periode_akhir ? $hist->periode_akhir->format('d/m/Y') : '-' }}</td>
                                <td>{{ $hist->submitted_at ? $hist->submitted_at->format('d/m/Y H:i') : ($hist->created_at ? $hist->created_at->format('d/m/Y H:i') : '-') }}</td>
                                <td>
                                    @if($hist->status === 'pending_review')
                                        <span class="status-badge status-pending">Pending Review</span>
                                    @elseif($hist->status === 'disetujui')
                                        <span class="status-badge status-disetujui">Disetujui</span>
                                    @elseif($hist->status === 'ditolak')
                                        <span class="status-badge status-ditolak">Ditolak</span>
                                    @else
                                        <span class="status-badge">{{ ucfirst($hist->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $hist->catatan_admin ?? '-' }}</td>
                                <td>{{ $hist->reviewed_at ? $hist->reviewed_at->format('d/m/Y H:i') : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @else
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p>Belum ada laporan yang dibuat</p>
        </div>
    @endif
</div>
@endsection