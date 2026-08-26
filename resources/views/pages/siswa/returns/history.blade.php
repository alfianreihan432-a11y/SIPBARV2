@extends('layouts.siswa')

@section('title', 'Riwayat Pengembalian – SIPBAR')
@section('page-heading', 'Riwayat Pengembalian')

@section('content')
<style>
    .history-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .history-title { font-size: 20px; font-weight: 800; color: var(--text); }
    .history-sub { font-size: 13px; color: var(--muted); margin-top: 2px; }

    .nav-tabs-bar {
        display: flex;
        gap: 8px;
        margin-bottom: 18px;
        border-bottom: 1px solid var(--border2);
        padding-bottom: 10px;
    }
    .nav-tab-btn {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        color: var(--muted);
        background: transparent;
        transition: all .15s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .nav-tab-btn.active {
        background: #1d4ed8;
        color: #fff;
    }
    .nav-tab-btn:hover:not(.active) {
        background: var(--bg3);
        color: var(--text);
    }

    .history-panel {
        background: var(--card);
        border: 1px solid var(--border2);
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
    }

    .table-container {
        overflow-x: auto;
    }
    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 13px;
    }
    .custom-table th {
        background: var(--bg3);
        color: var(--muted);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 12px 14px;
        text-align: left;
        border-bottom: 1px solid var(--border2);
    }
    .custom-table td {
        padding: 14px;
        border-bottom: 1px solid var(--border2);
        color: var(--text);
        vertical-align: middle;
    }
    .custom-table tr:last-child td {
        border-bottom: none;
    }

    /* Badges */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.02em;
    }
    .badge-menunggu {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    .badge-disetujui {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    .badge-ditolak {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .badge-kondisi {
        font-size: 10px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 4px;
        background: var(--bg3);
        color: var(--text);
        border: 1px solid var(--border2);
    }

    .photo-thumb {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        object-fit: cover;
        cursor: pointer;
        border: 1px solid var(--border2);
        transition: transform .15s;
    }
    .photo-thumb:hover {
        transform: scale(1.08);
    }

    .btn-reapply {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #f59e0b;
        color: #0f172a;
        font-size: 11px;
        font-weight: 800;
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        box-shadow: 0 1px 4px rgba(245, 158, 11, 0.2);
    }
    .btn-reapply:hover {
        background: #d97706;
        color: #fff;
    }

    .empty-state {
        text-align: center;
        padding: 48px 20px;
        color: var(--muted);
    }
    .empty-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 12px;
        color: var(--subtle);
    }

    /* Simple Image Modal */
    .img-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.7);
        z-index: 999;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .img-modal.active {
        display: flex;
    }
    .img-modal-content {
        max-width: 90%;
        max-height: 85vh;
        border-radius: 12px;
        background: var(--card);
        overflow: hidden;
        position: relative;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    .img-modal-content img {
        width: 100%;
        max-height: 75vh;
        object-fit: contain;
        display: block;
    }
    .modal-close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0,0,0,0.6);
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
</style>

<div>
    {{-- Top Alert Messages --}}
    @if(session('success'))
        <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('warning'))
        <div style="background: #fffbeb; border: 1px solid #fde68a; color: #92400e; padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            {{ session('warning') }}
        </div>
    @endif

    {{-- Tabs --}}
    <div class="nav-tabs-bar">
        <a href="{{ route('student.returns.index') }}" class="nav-tab-btn">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            Barang Saya (Aktif)
        </a>
        <a href="{{ route('student.returns.history') }}" class="nav-tab-btn active">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Riwayat Pengembalian
        </a>
    </div>

    {{-- History Table Panel --}}
    <div class="history-panel">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text);">Riwayat Pengajuan Pengembalian</h3>
                <p style="font-size: 12px; color: var(--muted); margin-top: 2px;">Daftar status pengajuan pengembalian barang yang pernah Anda buat.</p>
            </div>
            <a href="{{ route('student.returns.index') }}" class="btn-reapply" style="font-size: 12px; padding: 8px 14px;">
                + Kembalikan Barang Lain
            </a>
        </div>

        @if($returns->count() > 0)
            <div class="table-container">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th>Kondisi & Bukti</th>
                            <th>Tanggal Diajukan</th>
                            <th>Status Verifikasi</th>
                            <th>Catatan / Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($returns as $ret)
                            <tr>
                                <td>
                                    <div style="font-weight: 800; color: var(--text);">
                                        {{ $ret->borrowingRequest?->item?->name ?? 'Barang' }}
                                    </div>
                                    <div style="font-size: 11px; color: var(--muted); margin-top: 2px;">
                                        ID Peminjaman: #{{ $ret->borrowing_request_id }} &bull; Qty: {{ $ret->borrowingRequest?->quantity ?? 1 }}
                                    </div>
                                </td>

                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        @if($ret->foto_bukti)
                                            <img src="{{ asset('storage/' . $ret->foto_bukti) }}" alt="Foto Bukti" class="photo-thumb" onclick="openPhotoModal('{{ asset('storage/' . $ret->foto_bukti) }}')">
                                        @endif
                                        <div>
                                            <span class="badge-kondisi">
                                                {{ $ret->kondisi_label }}
                                            </span>
                                            @if($ret->catatan)
                                                <div style="font-size: 11px; color: var(--muted); margin-top: 4px; max-width: 180px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;" title="{{ $ret->catatan }}">
                                                    "{{ $ret->catatan }}"
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div style="font-weight: 600; color: var(--text);">
                                        {{ $ret->created_at->format('d M Y') }}
                                    </div>
                                    <div style="font-size: 11px; color: var(--muted);">
                                        {{ $ret->created_at->format('H:i') }} WIB
                                    </div>
                                </td>

                                <td>
                                    @if($ret->status === 'menunggu')
                                        <span class="badge badge-menunggu">
                                            <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Menunggu Verifikasi
                                        </span>
                                    @elseif($ret->status === 'disetujui')
                                        <span class="badge badge-disetujui">
                                            <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Disetujui
                                        </span>
                                    @elseif($ret->status === 'ditolak')
                                        <span class="badge badge-ditolak">
                                            <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Ditolak
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if($ret->status === 'disetujui')
                                        <div style="font-size: 11px; color: #065f46; font-weight: 600;">
                                            Diverifikasi oleh {{ $ret->verifier?->name ?? 'Admin' }}
                                        </div>
                                        <div style="font-size: 10px; color: var(--muted);">
                                            {{ $ret->tanggal_verifikasi ? $ret->tanggal_verifikasi->format('d M Y H:i') : '' }}
                                        </div>
                                    @elseif($ret->status === 'ditolak')
                                        <div style="color: #991b1b; font-size: 12px; font-weight: 600; margin-bottom: 4px;">
                                            Alasan: "{{ $ret->alasan_ditolak }}"
                                        </div>
                                        @if($ret->borrowingRequest && $ret->borrowingRequest->status === 'borrowed')
                                            <a href="{{ route('student.returns.create', $ret->borrowing_request_id) }}" class="btn-reapply">
                                                Ajukan Ulang &rarr;
                                            </a>
                                        @endif
                                    @else
                                        <span style="font-size: 12px; color: var(--muted); font-style: italic;">
                                            Sedang diperiksa fisik barang oleh petugas/admin.
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 18px;">
                {{ $returns->links() }}
            </div>
        @else
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" class="empty-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div style="font-size: 15px; font-weight: 700; color: var(--text); margin-bottom: 4px;">Belum Ada Riwayat Pengembalian</div>
                <div style="font-size: 13px; color: var(--muted);">Anda belum pernah mengajukan pengembalian barang.</div>
            </div>
        @endif
    </div>
</div>

{{-- Modal Image Preview --}}
<div class="img-modal" id="photoModal" onclick="closePhotoModal()">
    <div class="img-modal-content" onclick="event.stopPropagation()">
        <button class="modal-close-btn" onclick="closePhotoModal()">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <img id="modalImg" src="" alt="Foto Bukti Pengembalian">
    </div>
</div>

<script>
    function openPhotoModal(src) {
        document.getElementById('modalImg').src = src;
        document.getElementById('photoModal').classList.add('active');
    }
    function closePhotoModal() {
        document.getElementById('photoModal').classList.remove('active');
        document.getElementById('modalImg').src = '';
    }
</script>
@endsection
