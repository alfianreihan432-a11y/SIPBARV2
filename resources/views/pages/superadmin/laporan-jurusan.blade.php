@extends('layouts.superadmin')

@section('title', 'Laporan per Jurusan')
@section('page-heading', 'Laporan per Jurusan')

@section('content')
<style>
    /* ---- Filter bar ---- */
    .filter-bar {
        background: var(--bg-card);
        border: 1px solid var(--border-alt);
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: flex-end;
        gap: 12px;
        flex-wrap: wrap;
    }
    .form-group-inline {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .form-label-sm {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .form-select {
        padding: 8px 12px;
        border: 1px solid var(--border-alt);
        border-radius: 8px;
        font-size: 13px;
        background: var(--bg-card-subtle) !important;
        color: var(--text-primary) !important;
        outline: none;
        min-width: 150px;
        appearance: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        color-scheme: light;
        background-image: none !important;
        position: relative;
    }
    .form-select::-ms-expand { display: none !important; }
    .form-select::-webkit-select-dropdown-icon { display: none !important; }
    .form-select::-webkit-slider-runnable-track { display: none !important; }
    html.dark .form-select {
        color: var(--text-primary) !important;
        background: var(--bg-card-subtle) !important;
        color-scheme: dark;
    }
    html.dark .form-select::-ms-expand { display: none !important; }
    html.dark .form-select::-webkit-select-dropdown-icon { display: none !important; }
    .form-select:focus { border-color: var(--blue); color: var(--text-primary) !important; }
    .filter-btn {
        padding: 8px 16px;
        background: var(--blue-dark);
        color: #ffffff !important;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        align-self: flex-end;
    }
    .filter-btn:hover { background: var(--blue); }
    .reset-btn {
        padding: 8px 16px;
        background: var(--bg-card-subtle);
        color: var(--text-primary) !important;
        border: 1px solid var(--border-alt);
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: background 0.2s;
        align-self: flex-end;
    }
    .reset-btn:hover { background: var(--border-alt); }

    /* ---- Report Cards ---- */
    .laporan-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 14px;
    }
    .laporan-card {
        background: var(--bg-card);
        border: 1px solid var(--border-alt);
        border-radius: 12px;
        overflow: hidden;
        transition: box-shadow 0.2s, border-color 0.2s;
    }
    .laporan-card:hover {
        box-shadow: var(--card-shadow);
        border-color: var(--blue);
    }
    .laporan-card-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        padding: 16px 16px 12px;
        border-bottom: 1px solid var(--border-subtle);
    }
    .laporan-card-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 4px 0;
        line-height: 1.3;
    }
    .laporan-card-meta {
        font-size: 11.5px;
        color: var(--text-muted);
        line-height: 1.5;
    }
    .laporan-card-body {
        padding: 12px 16px;
    }
    .laporan-meta-row {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: var(--text-muted);
        margin-bottom: 4px;
    }
    .laporan-meta-row:last-child { margin-bottom: 0; }
    .laporan-meta-row svg { flex-shrink: 0; opacity: 0.7; }
    .laporan-card-footer {
        padding: 10px 16px;
        border-top: 1px solid var(--border-subtle);
        display: flex;
        align-items: center;
        gap: 8px;
        background: var(--bg-card-subtle);
        flex-wrap: wrap;
    }

    /* ---- Status badges ---- */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 10.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .status-pending  { background: rgba(245,158,11,0.12); color: #f59e0b; border: 1px solid rgba(245,158,11,0.25); }
    .status-disetujui{ background: rgba(16,185,129,0.12); color: #10b981; border: 1px solid rgba(16,185,129,0.25); }
    .status-ditolak  { background: rgba(239,68,68,0.12);  color: #ef4444; border: 1px solid rgba(239,68,68,0.25);  }

    /* ---- Action buttons ---- */
    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 11px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.18s ease;
        text-decoration: none;
        white-space: nowrap;
    }
    .btn-view    { background: rgba(59,130,246,0.12); color: #3b82f6; }
    .btn-view:hover { background: rgba(59,130,246,0.22); }
    .btn-approve { background: rgba(16,185,129,0.12); color: #10b981; }
    .btn-approve:hover { background: rgba(16,185,129,0.22); }
    .btn-reject  { background: rgba(239,68,68,0.12);  color: #ef4444; }
    .btn-reject:hover  { background: rgba(239,68,68,0.22); }
    .btn-delete  { background: rgba(239,68,68,0.08);  color: #ef4444; margin-left: auto; }
    .btn-delete:hover  { background: rgba(239,68,68,0.18); }

    /* ---- Empty state ---- */
    .empty-state {
        text-align: center;
        padding: 52px 20px;
        color: var(--text-muted);
        background: var(--bg-card);
        border: 1px solid var(--border-alt);
        border-radius: 12px;
    }
    .empty-state svg { width: 48px; height: 48px; margin-bottom: 12px; opacity: 0.4; }

    /* ---- Modals ---- */
    .modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 100;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(3px);
    }
    .modal.show { display: flex; }
    .modal-content {
        background: var(--bg-card);
        border-radius: 16px;
        padding: 28px;
        width: 100%;
        max-width: 480px;
        max-height: 90vh;
        overflow-y: auto;
        border: 1px solid var(--border-alt);
        box-shadow: 0 20px 48px rgba(0,0,0,0.18), 0 4px 16px rgba(0,0,0,0.08);
    }
    .form-group { margin-bottom: 16px; }
    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 6px;
    }
    .form-textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--border-alt);
        border-radius: 8px;
        font-size: 13px;
        background: var(--bg-card-subtle);
        color: var(--text-primary);
        outline: none;
        resize: vertical;
        min-height: 90px;
        box-sizing: border-box;
        font-family: inherit;
        line-height: 1.5;
    }
    .form-textarea:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
    }
    .modal-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 24px;
        padding-top: 16px;
        border-top: 1px solid var(--border-alt);
        padding-bottom: 2px;
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

{{-- ===== Filter Bar ===== --}}
<form method="GET" action="{{ route('superadmin.laporan-jurusan') }}" class="filter-bar">
    <div class="form-group-inline">
        <label class="form-label-sm">Jurusan</label>
        <select name="jurusan_id" class="form-select">
            <option value="">Semua Jurusan</option>
            @foreach(\App\Models\Jurusan::orderBy('nama')->get() as $j)
                <option value="{{ $j->id }}" {{ request('jurusan_id') == $j->id ? 'selected' : '' }}>{{ $j->nama }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group-inline">
        <label class="form-label-sm">Status</label>
        <select name="status" class="form-select">
            <option value="">Semua Status</option>
            <option value="pending_review" {{ request('status') === 'pending_review' ? 'selected' : '' }}>Pending Review</option>
            <option value="disetujui"      {{ request('status') === 'disetujui'      ? 'selected' : '' }}>Disetujui</option>
            <option value="ditolak"        {{ request('status') === 'ditolak'        ? 'selected' : '' }}>Ditolak</option>
        </select>
    </div>

    <button type="submit" class="filter-btn">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;display:inline;margin-right:4px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
        </svg>
        Filter
    </button>
    <a href="{{ route('superadmin.laporan-jurusan') }}" class="reset-btn">Reset</a>
</form>

{{-- ===== Card Grid ===== --}}
@if($reports->count() > 0)
    <div class="laporan-grid">
        @foreach($reports as $report)
            @php
                $namaJurusan = $report->jurusan->nama ?? '-';
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
            <div class="laporan-card">
                {{-- Card Header --}}
                <div class="laporan-card-header">
                    <div style="min-width:0;">
                        <p class="laporan-card-title">Laporan Jurusan {{ $namaJurusan }}</p>
                        <p class="laporan-card-meta">
                            ID Laporan: #{{ $report->id }}
                            &nbsp;•&nbsp;
                            Diajukan {{ $report->created_at->format('d/m/Y, H:i') }}
                        </p>
                    </div>
                    <span class="status-badge {{ $statusClass }}" style="flex-shrink:0;">
                        {{ $statusLabel }}
                    </span>
                </div>

                {{-- Card Body --}}
                <div class="laporan-card-body">
                    {{-- Pengirim --}}
                    <div class="laporan-meta-row">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>{{ $report->pengirim->name ?? '-' }}</span>
                    </div>

                    {{-- Periode --}}
                    <div class="laporan-meta-row">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>
                            {{ $report->periode_awal->format('d/m/Y') }}
                            &ndash;
                            {{ $report->periode_akhir->format('d/m/Y') }}
                        </span>
                    </div>

                    {{-- Catatan admin (jika ada) --}}
                    @if($report->catatan_admin)
                        <div class="laporan-meta-row" style="margin-top:6px;">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            <span style="font-style:italic;">{{ Str::limit($report->catatan_admin, 60) }}</span>
                        </div>
                    @endif
                </div>

                {{-- Card Footer — Actions --}}
                <div class="laporan-card-footer">
                    {{-- Detail --}}
                    <a href="{{ route('superadmin.laporan-jurusan.show', $report->id) }}" class="action-btn btn-view">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Detail
                    </a>

                    @if($report->status === 'pending_review')
                        {{-- Setujui --}}
                        <button type="button" class="action-btn btn-approve" onclick="showApproveModal({{ $report->id }})">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Setujui
                        </button>

                        {{-- Tolak --}}
                        <button type="button" class="action-btn btn-reject" onclick="showRejectModal({{ $report->id }})">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Tolak
                        </button>
                    @endif

                    {{-- Hapus — pushed to right --}}
                    <button type="button" class="action-btn btn-delete" onclick="showDeleteModal({{ $report->id }}, '{{ addslashes($namaJurusan) }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    @if($reports->hasPages())
        <div style="margin-top: 20px; display: flex; justify-content: center;">
            {{ $reports->links() }}
        </div>
    @endif

@else
    <div class="empty-state">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <p style="margin:8px 0 0; font-size:14px; font-weight:600;">Belum ada laporan yang diterima</p>
        <p style="margin:4px 0 0; font-size:13px;">
            @if(request()->hasAny(['jurusan_id','status']))
                Tidak ada laporan yang cocok dengan filter ini.
            @else
                Laporan dari Kepala Jurusan akan muncul di sini.
            @endif
        </p>
    </div>
@endif

<!-- Approve Modal -->
<div id="approveModal" class="modal">
    <div class="modal-content">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; margin-bottom:20px; padding-bottom:14px; border-bottom:1px solid var(--border-alt);">
            <div style="display:flex; align-items:center; gap:12px; min-width:0;">
                <div style="width:40px; height:40px; border-radius:50%; background:rgba(16,185,129,0.18); display:flex; align-items:center; justify-content:center; flex-shrink:0; border:1px solid rgba(16,185,129,0.3);">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:20px; height:20px; color:#10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div style="min-width:0;">
                    <h3 class="modal-title" style="margin:0; font-size:16px; font-weight:700; color:var(--text-primary); line-height:1.3;">Setujui Laporan</h3>
                    <p style="margin:3px 0 0 0; font-size:12px; color:var(--text-muted); line-height:1.3;">Verifikasi dan setujui laporan rekap peminjaman</p>
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:10px; flex-shrink:0;">
                <span style="background:rgba(16,185,129,0.2); color:#6ee7b7; border:1px solid rgba(16,185,129,0.4); padding:4px 10px; border-radius:9999px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; white-space:nowrap;">
                    Setujui
                </span>
                <button type="button" onclick="hideApproveModal()" style="background:var(--bg-card-subtle); border:1px solid var(--border-alt); color:var(--text-primary); width:32px; height:32px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; transition:all 0.2s; padding:0;" title="Tutup Modal" onmouseover="this.style.background='var(--border-alt)'" onmouseout="this.style.background='var(--bg-card-subtle)'">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px; height:16px; color:var(--text-primary);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <form method="POST" action="" id="approveForm">
            @csrf
            <input type="hidden" name="report_id" id="approveReportId">
            <div class="form-group">
                <label class="form-label">Catatan (Opsional)</label>
                <textarea name="catatan_admin" class="form-textarea" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" onclick="hideApproveModal()" style="padding:8px 16px; background:var(--bg-card-subtle); color:var(--text-primary); border:1px solid var(--border-alt); border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">Batal</button>
                <button type="submit" style="padding:8px 16px; background:var(--color-success); color:#ffffff !important; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">Setujui Laporan</button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal">
    <div class="modal-content">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; margin-bottom:20px; padding-bottom:14px; border-bottom:1px solid var(--border-alt);">
            <div style="display:flex; align-items:center; gap:12px; min-width:0;">
                <div style="width:40px; height:40px; border-radius:50%; background:rgba(239,68,68,0.18); display:flex; align-items:center; justify-content:center; flex-shrink:0; border:1px solid rgba(239,68,68,0.3);">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:20px; height:20px; color:#ef4444;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div style="min-width:0;">
                    <h3 class="modal-title" style="margin:0; font-size:16px; font-weight:700; color:var(--text-primary); line-height:1.3;">Tolak Laporan</h3>
                    <p style="margin:3px 0 0 0; font-size:12px; color:var(--text-muted); line-height:1.3;">Berikan alasan penolakan laporan</p>
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:10px; flex-shrink:0;">
                <span style="background:rgba(239,68,68,0.2); color:#f87171; border:1px solid rgba(239,68,68,0.4); padding:4px 10px; border-radius:9999px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; white-space:nowrap;">
                    Tolak
                </span>
                <button type="button" onclick="hideRejectModal()" style="background:var(--bg-card-subtle); border:1px solid var(--border-alt); color:var(--text-primary); width:32px; height:32px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; transition:all 0.2s; padding:0;" title="Tutup Modal" onmouseover="this.style.background='var(--border-alt)'" onmouseout="this.style.background='var(--bg-card-subtle)'">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px; height:16px; color:var(--text-primary);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <form method="POST" action="" id="rejectForm">
            @csrf
            <input type="hidden" name="report_id" id="rejectReportId">
            <div class="form-group">
                <label class="form-label">Alasan Penolakan <span style="color:#ef4444">*</span></label>
                <textarea name="catatan_admin" class="form-textarea" placeholder="Jelaskan alasan penolakan laporan..." required></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" onclick="hideRejectModal()" style="padding:8px 16px; background:var(--bg-card-subtle); color:var(--text-primary); border:1px solid var(--border-alt); border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">Batal</button>
                <button type="submit" style="padding:8px 16px; background:var(--color-danger); color:#ffffff !important; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">Tolak Laporan</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; margin-bottom:20px; padding-bottom:14px; border-bottom:1px solid var(--border-alt);">
            <div style="display:flex; align-items:center; gap:12px; min-width:0;">
                <div style="width:40px; height:40px; border-radius:50%; background:rgba(239,68,68,0.18); display:flex; align-items:center; justify-content:center; flex-shrink:0; border:1px solid rgba(239,68,68,0.3);">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:20px; height:20px; color:#ef4444;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div style="min-width:0;">
                    <h3 class="modal-title" style="margin:0; font-size:16px; font-weight:700; color:var(--text-primary); line-height:1.3;">Hapus Laporan</h3>
                    <p style="margin:3px 0 0 0; font-size:12px; color:var(--text-muted); line-height:1.3;">Konfirmasi penghapusan laporan secara permanen</p>
                </div>
            </div>
            <button type="button" onclick="hideDeleteModal()" style="background:var(--bg-card-subtle); border:1px solid var(--border-alt); color:var(--text-primary); width:32px; height:32px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; transition:all 0.2s; padding:0;" title="Tutup Modal" onmouseover="this.style.background='var(--border-alt)'" onmouseout="this.style.background='var(--bg-card-subtle)'">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px; height:16px; color:var(--text-primary;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div style="margin-bottom:20px;">
            <p style="font-size:13px; color:var(--text-primary); margin-bottom:8px;">Apakah Anda yakin ingin menghapus laporan ini?</p>
            <p style="font-size:12px; color:var(--text-muted);">Tindakan ini tidak dapat dibatalkan. Laporan yang dihapus akan hilang secara permanen dari sistem.</p>
        </div>

        <form method="POST" action="" id="deleteForm">
            @csrf
            @method('DELETE')
            <input type="hidden" name="report_id" id="deleteReportId">
            <div class="modal-actions">
                <button type="button" onclick="hideDeleteModal()" style="padding:8px 16px; background:var(--bg-card-subtle); color:var(--text-primary); border:1px solid var(--border-alt); border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">Batal</button>
                <button type="submit" style="padding:8px 16px; background:var(--color-danger); color:#ffffff !important; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">Hapus Permanen</button>
            </div>
        </form>
    </div>
</div>

<script>
function showApproveModal(id) {
    document.getElementById('approveReportId').value = id;
    document.getElementById('approveForm').action = '/superadmin/laporan-jurusan/' + id + '/approve';
    document.getElementById('approveModal').classList.add('show');
}

function hideApproveModal() {
    document.getElementById('approveModal').classList.remove('show');
}

function showRejectModal(id) {
    document.getElementById('rejectReportId').value = id;
    document.getElementById('rejectForm').action = '/superadmin/laporan-jurusan/' + id + '/reject';
    document.getElementById('rejectModal').classList.add('show');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.remove('show');
}

function showDeleteModal(id, name) {
    document.getElementById('deleteReportId').value = id;
    document.getElementById('deleteForm').action = '/superadmin/laporan-jurusan/' + id;
    document.getElementById('deleteModal').classList.add('show');
}

function hideDeleteModal() {
    document.getElementById('deleteModal').classList.remove('show');
}

// Close modals on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideApproveModal();
        hideRejectModal();
        hideDeleteModal();
    }
});

// Close modals on backdrop click
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.remove('show');
        }
    });
});
</script>
@endsection
