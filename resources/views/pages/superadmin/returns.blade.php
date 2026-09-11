@extends('layouts.superadmin')

@section('title', 'Verifikasi Pengembalian Barang – SIPBAR Superadmin')
@section('page-heading', 'Verifikasi Pengembalian (Read Only)')

@section('content')
<style>
    /* Stats Bar */
    .admin-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 20px;
    }
    @media (max-width: 1024px) { .admin-stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px) { .admin-stats-grid { grid-template-columns: 1fr; } }

    .stat-card-admin {
        background: var(--bg-card);
        border: 1px solid var(--border-alt);
        border-radius: 14px;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: var(--card-shadow);
        transition: transform .15s;
    }
    .stat-card-admin:hover { transform: translateY(-2px); }
    .stat-card-label { font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.04em; }
    .stat-card-value { font-size: 22px; font-weight: 800; color: var(--text-primary); margin-top: 3px; }
    .stat-card-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Filters & Search Toolbar */
    .toolbar-panel {
        background: var(--bg-card);
        border: 1px solid var(--border-alt);
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        box-shadow: var(--card-shadow);
    }
    .status-filter-pills {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }
    .pill-btn {
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        color: var(--text-muted);
        background: var(--bg-card-subtle);
        border: 1px solid var(--border-subtle);
        transition: all .15s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .pill-btn:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }
    .pill-btn.active {
        background: var(--blue-dark);
        color: #fff;
        border-color: var(--blue-dark);
    }
    .pill-badge {
        font-size: 10px;
        font-weight: 800;
        padding: 1px 6px;
        border-radius: 999px;
        background: rgba(255,255,255,0.2);
    }
    .pill-btn:not(.active) .pill-badge {
        background: var(--bg-hover);
        color: var(--text-muted);
    }

    .search-form {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .search-input-wrap {
        display: flex;
        align-items: center;
        background: var(--bg-card-subtle);
        border: 1px solid var(--border-subtle);
        border-radius: 8px;
        padding: 6px 12px;
        gap: 6px;
        width: 240px;
    }
    .search-input-wrap input {
        background: none;
        border: none;
        outline: none;
        font-size: 12px;
        color: var(--text-primary);
        width: 100%;
    }
    .search-input-wrap input::placeholder { color: var(--text-muted); }

    /* Returns Table Panel */
    .table-panel {
        background: var(--bg-card);
        border: 1px solid var(--border-alt);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: var(--card-shadow);
    }
    .table-responsive {
        overflow-x: auto;
    }
    .returns-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 13px;
    }
    .returns-table th {
        background: var(--table-head-bg);
        color: var(--text-muted);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 14px 16px;
        text-align: left;
        border-bottom: 1px solid var(--border-alt);
    }
    .returns-table td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--border-alt);
        color: var(--text-primary);
        vertical-align: middle;
    }
    .returns-table tr:hover td {
        background: var(--table-hover);
    }
    .returns-table tr:last-child td {
        border-bottom: none;
    }

    /* Badges */
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.02em;
    }
    .badge-menunggu {
        background: rgba(245, 158, 11, 0.15);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }
    .badge-disetujui {
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    .badge-ditolak {
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .badge-kondisi {
        font-size: 11px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 5px;
        background: var(--bg-card-subtle);
        color: var(--text-secondary);
        border: 1px solid var(--border-subtle);
    }

    .photo-thumb {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        object-fit: cover;
        cursor: pointer;
        border: 1px solid var(--border-alt);
        transition: transform .15s;
    }
    .photo-thumb:hover { transform: scale(1.1); }

    /* Action Buttons */
    .btn-approve {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #10b981;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        padding: 6px 12px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        transition: all .15s;
    }
    .btn-approve:hover { background: #059669; }

    .btn-reject {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: transparent;
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
        font-size: 11px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 6px;
        cursor: pointer;
        transition: all .15s;
    }
    .btn-reject:hover {
        background: rgba(239, 68, 68, 0.1);
        border-color: #ef4444;
    }

    /* Modal Styles */
    .admin-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.7);
        z-index: 100;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .admin-modal.active { display: flex; }
    .modal-box {
        background: var(--bg-card);
        border: 1px solid var(--border-alt);
        border-radius: 16px;
        max-width: 520px;
        width: 100%;
        padding: 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        position: relative;
    }
    .modal-box-title { font-size: 16px; font-weight: 800; color: var(--text-primary); margin-bottom: 6px; }
    .modal-box-sub { font-size: 12px; color: var(--text-muted); margin-bottom: 18px; }

    .form-textarea {
        width: 100%;
        background: var(--input-bg);
        border: 1.5px solid var(--border-alt);
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 13px;
        color: var(--text-primary);
        outline: none;
        font-family: inherit;
        transition: border-color .2s;
    }
    .form-textarea:focus {
        border-color: var(--blue);
    }

    .modal-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 18px;
    }
    .btn-cancel {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        color: var(--text-muted);
        background: var(--bg-card-subtle);
        border: 1px solid var(--border-subtle);
        cursor: pointer;
    }
    .btn-submit-reject {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        color: #fff;
        background: #ef4444;
        border: none;
        cursor: pointer;
    }
    .btn-submit-reject:hover { background: #dc2626; }

    /* Read-only badge */
    .readonly-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        background: rgba(156, 163, 175, 0.15);
        color: #9ca3af;
        border: 1px solid rgba(156, 163, 175, 0.3);
    }
</style>

<div>
    {{-- Read-only warning --}}
    <div style="background: rgba(156, 163, 175, 0.15); border: 1px solid rgba(156, 163, 175, 0.3); color: #9ca3af; padding: 12px 18px; border-radius: 10px; margin-bottom: 16px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
        Mode Read Only: Superadmin tidak dapat melakukan approve/reject pengembalian barang.
    </div>

    {{-- Stats Cards --}}
    <div class="admin-stats-grid">
        <div class="stat-card-admin">
            <div>
                <div class="stat-card-label">Semua Pengajuan</div>
                <div class="stat-card-value">{{ $countSemua }}</div>
            </div>
            <div class="stat-card-icon" style="background: rgba(59, 130, 246, 0.15); color: #3b82f6;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>

        <div class="stat-card-admin">
            <div>
                <div class="stat-card-label">Menunggu Verifikasi</div>
                <div class="stat-card-value" style="color: #f59e0b;">{{ $countMenunggu }}</div>
            </div>
            <div class="stat-card-icon" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <div class="stat-card-admin">
            <div>
                <div class="stat-card-label">Disetujui</div>
                <div class="stat-card-value" style="color: #10b981;">{{ $countDisetujui }}</div>
            </div>
            <div class="stat-card-icon" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>

        <div class="stat-card-admin">
            <div>
                <div class="stat-card-label">Ditolak</div>
                <div class="stat-card-value" style="color: #ef4444;">{{ $countDitolak }}</div>
            </div>
            <div class="stat-card-icon" style="background: rgba(239, 68, 68, 0.15); color: #ef4444;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Toolbar Filter & Search --}}
    <div class="toolbar-panel">
        <div class="status-filter-pills">
            <a href="{{ route('superadmin.returns', ['status' => 'semua', 'q' => $search]) }}" class="pill-btn {{ $status === 'semua' ? 'active' : '' }}">
                Semua
                <span class="pill-badge">{{ $countSemua }}</span>
            </a>
            <a href="{{ route('superadmin.returns', ['status' => 'menunggu', 'q' => $search]) }}" class="pill-btn {{ $status === 'menunggu' ? 'active' : '' }}">
                Menunggu Verifikasi
                <span class="pill-badge">{{ $countMenunggu }}</span>
            </a>
            <a href="{{ route('superadmin.returns', ['status' => 'disetujui', 'q' => $search]) }}" class="pill-btn {{ $status === 'disetujui' ? 'active' : '' }}">
                Disetujui
                <span class="pill-badge">{{ $countDisetujui }}</span>
            </a>
            <a href="{{ route('superadmin.returns', ['status' => 'ditolak', 'q' => $search]) }}" class="pill-btn {{ $status === 'ditolak' ? 'active' : '' }}">
                Ditolak
                <span class="pill-badge">{{ $countDitolak }}</span>
            </a>
        </div>

        <form method="GET" action="{{ route('superadmin.returns') }}" class="search-form">
            <input type="hidden" name="status" value="{{ $status }}">
            <div class="search-input-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;color:var(--text-muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari siswa atau barang...">
            </div>
            @if($search)
                <a href="{{ route('superadmin.returns', ['status' => $status]) }}" style="font-size: 11px; color: var(--text-muted); text-decoration: none;">Reset</a>
            @endif
        </form>
    </div>

    {{-- Main Returns Table Panel --}}
    <div class="table-panel">
        @if($returns->count() > 0)
            <div class="table-responsive">
                <table class="returns-table">
                    <thead>
                        <tr>
                            <th>Siswa Peminjam</th>
                            <th>Barang</th>
                            <th>Kondisi & Bukti</th>
                            <th>Tanggal Diajukan</th>
                            <th>Status</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($returns as $ret)
                            @php
                                $borrowing = $ret->borrowingRequest;
                                $item = $borrowing?->item;
                                $user = $ret->user;
                            @endphp
                            <tr>
                                <td>
                                    <div style="font-weight: 700; color: var(--text-primary);">
                                        {{ $user?->name ?? 'Siswa' }}
                                    </div>
                                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                                        NIS: {{ $user?->nis ?? '-' }} &bull; Kelas: {{ $user?->kelas ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    <div style="font-weight: 700; color: var(--text-primary);">
                                        {{ $item?->name ?? 'Barang #' . $ret->borrowing_request_id }}
                                    </div>
                                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                                        Kode: {{ $item?->code ?? '-' }} &bull; ID Pinjam: #{{ $ret->borrowing_request_id }}
                                    </div>
                                </td>

                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        @if($ret->foto_bukti)
                                            <img src="{{ asset('storage/' . $ret->foto_bukti) }}" alt="Bukti" class="photo-thumb" onclick="openPhotoModal('{{ asset('storage/' . $ret->foto_bukti) }}')">
                                        @endif
                                        <div>
                                            <span class="badge-kondisi">
                                                {{ $ret->kondisi_label }}
                                            </span>
                                            @if($ret->catatan)
                                                <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px; max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $ret->catatan }}">
                                                    "{{ $ret->catatan }}"
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div style="font-weight: 600; color: var(--text-primary);">
                                        {{ $ret->created_at->format('d M Y') }}
                                    </div>
                                    <div style="font-size: 11px; color: var(--text-muted);">
                                        {{ $ret->created_at->format('H:i') }} WIB
                                    </div>
                                </td>

                                <td>
                                    @if($ret->status === 'menunggu')
                                        <span class="badge-status badge-menunggu">
                                            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Menunggu
                                        </span>
                                    @elseif($ret->status === 'disetujui')
                                        <span class="badge-status badge-disetujui">
                                            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Disetujui
                                        </span>
                                    @elseif($ret->status === 'ditolak')
                                        <span class="badge-status badge-ditolak">
                                            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Ditolak
                                        </span>
                                    @endif
                                </td>

                                <td style="text-align: right;">
                                    {{-- Read-only badge for superadmin --}}
                                    <span class="readonly-badge">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        Read Only
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="padding: 40px; text-align: center; color: var(--text-muted);">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:48px;height:48px;margin:0 auto 16px;color:var(--text-subtle)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <div style="font-size: 14px; font-weight: 600; color: var(--text-subtle);">Tidak ada data pengembalian</div>
                <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Silakan cek filter atau kata kunci pencarian Anda</div>
            </div>
        @endif
    </div>
</div>
@endsection