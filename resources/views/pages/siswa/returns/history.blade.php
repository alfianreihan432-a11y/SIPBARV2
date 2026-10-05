@extends('layouts.siswa')

@section('title', 'Riwayat Pengembalian – SIPBAR')

@section('content')
{{-- Top Alert Messages --}}
@if(session('success'))
    <div style="background: var(--s-returned-bg); border: 1px solid var(--s-returned-bdr); color: var(--s-returned); padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
@endif
@if(session('warning'))
    <div style="background: var(--s-pending-bg); border: 1px solid var(--s-pending-bdr); color: var(--s-pending); padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        {{ session('warning') }}
    </div>
@endif

{{-- Page Header --}}
<div class="page-header">
    <div class="page-header-left">
        <div class="page-title">Riwayat Pengembalian</div>
        <div class="page-subtitle">Daftar status pengajuan pengembalian barang yang pernah Anda buat</div>
    </div>
    <a href="{{ route('student.returns.index') }}" class="s-btn s-btn--primary">
        + Kembalikan Barang
    </a>
</div>

{{-- Tabs --}}
<div style="display:flex;gap:8px;margin-bottom:18px;border-bottom:1px solid var(--border2);padding-bottom:10px">
    <a href="{{ route('student.returns.index') }}" class="s-btn s-btn--secondary s-btn--sm">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
        </svg>
        Barang Saya (Aktif)
    </a>
    <a href="{{ route('student.returns.history') }}" class="s-btn s-btn--primary s-btn--sm">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Riwayat Pengembalian
    </a>
</div>

{{-- History Table Panel --}}
<div class="s-card">
    <div class="s-card-header">
        <div>
            <div class="s-card-title">Daftar Pengajuan Pengembalian</div>
            <div class="s-card-sub">Hasil verifikasi kondisi barang oleh petugas / admin</div>
        </div>
    </div>

    @if($returns->count() > 0)
        {{-- Desktop Table View (> 640px) --}}
        <div class="s-history-table-wrap" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
            <table style="width: 100%; border-collapse: separate; border-spacing: 0; font-size: 13px; min-width: 680px;">
                <thead>
                    <tr>
                        <th style="background: var(--bg3); color: var(--muted); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; padding: 12px 14px; text-align: left; border-bottom: 1px solid var(--border2); border-top-left-radius: 8px;">Barang</th>
                        <th style="background: var(--bg3); color: var(--muted); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; padding: 12px 14px; text-align: left; border-bottom: 1px solid var(--border2);">Kondisi & Bukti</th>
                        <th style="background: var(--bg3); color: var(--muted); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; padding: 12px 14px; text-align: left; border-bottom: 1px solid var(--border2);">Tanggal Diajukan</th>
                        <th style="background: var(--bg3); color: var(--muted); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; padding: 12px 14px; text-align: left; border-bottom: 1px solid var(--border2);">Status Verifikasi</th>
                        <th style="background: var(--bg3); color: var(--muted); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; padding: 12px 14px; text-align: left; border-bottom: 1px solid var(--border2); border-top-right-radius: 8px;">Catatan / Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($returns as $ret)
                        @php
                            $req = $ret->borrowingRequest;
                            $itemName = $req ? $req->item_display_name : ($ret->item?->name ?? 'Barang');
                            $itemQty = $req ? $req->totalQuantity() : 1;
                        @endphp
                        <tr>
                            <td style="padding: 14px; border-bottom: 1px solid var(--border2); color: var(--text); vertical-align: middle;">
                                <div style="font-weight: 800; color: var(--text);">
                                    {{ $itemName }}
                                </div>
                                <div style="font-size: 11px; color: var(--muted); margin-top: 2px;">
                                    ID Peminjaman: #{{ $ret->borrowing_request_id }} &bull; Qty: {{ $itemQty ?? 1 }}
                                </div>
                            </td>

                            <td style="padding: 14px; border-bottom: 1px solid var(--border2); color: var(--text); vertical-align: middle;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    @if($ret->foto_bukti)
                                        <img src="{{ asset('storage/' . $ret->foto_bukti) }}" alt="Foto Bukti" style="width: 44px; height: 44px; border-radius: 8px; object-fit: cover; cursor: pointer; border: 1px solid var(--border2);" onclick="openPhotoModal('{{ asset('storage/' . $ret->foto_bukti) }}')">
                                    @endif
                                    <div>
                                        <span style="font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 4px; background: var(--bg3); color: var(--text); border: 1px solid var(--border2);">
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

                            <td style="padding: 14px; border-bottom: 1px solid var(--border2); color: var(--text); vertical-align: middle;">
                                <div style="font-weight: 600; color: var(--text);">
                                    {{ $ret->created_at->format('d M Y') }}
                                </div>
                                <div style="font-size: 11px; color: var(--muted);">
                                    {{ $ret->created_at->format('H:i') }} WIB
                                </div>
                            </td>

                            <td style="padding: 14px; border-bottom: 1px solid var(--border2); color: var(--text); vertical-align: middle;">
                                @if($ret->status === 'menunggu')
                                    <span class="s-badge s-badge--pending">
                                        <span class="s-badge-dot" style="background:var(--s-pending)"></span>
                                        Menunggu Verifikasi
                                    </span>
                                @elseif($ret->status === 'disetujui')
                                    <span class="s-badge s-badge--returned">
                                        <span class="s-badge-dot" style="background:var(--s-returned)"></span>
                                        Disetujui
                                    </span>
                                @elseif($ret->status === 'ditolak')
                                    <span class="s-badge s-badge--rejected">
                                        <span class="s-badge-dot" style="background:var(--s-rejected)"></span>
                                        Ditolak
                                    </span>
                                @endif
                            </td>

                            <td style="padding: 14px; border-bottom: 1px solid var(--border2); color: var(--text); vertical-align: middle;">
                                @if($ret->status === 'disetujui')
                                    <div style="font-size: 11px; color: var(--s-returned); font-weight: 600;">
                                        Diverifikasi oleh {{ $ret->verifier?->name ?? 'Admin' }}
                                    </div>
                                    <div style="font-size: 10px; color: var(--muted);">
                                        {{ $ret->tanggal_verifikasi ? $ret->tanggal_verifikasi->format('d M Y H:i') : '' }}
                                    </div>
                                @elseif($ret->status === 'ditolak')
                                    <div style="color: var(--s-rejected); font-size: 12px; font-weight: 600; margin-bottom: 4px;">
                                        Alasan: "{{ $ret->alasan_ditolak }}"
                                    </div>
                                    @if($ret->borrowingRequest && $ret->borrowingRequest->status === 'borrowed')
                                        <a href="{{ route('student.returns.create', $ret->borrowing_request_id) }}" class="s-btn s-btn--sm s-btn--primary">
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

        {{-- Mobile Cards View (<= 768px) --}}
        <div class="mobile-cards-view">
            @foreach($returns as $ret)
                @php
                    $req = $ret->borrowingRequest;
                    $itemName = $req ? $req->item_display_name : ($ret->item?->name ?? 'Barang');
                    $itemQty = $req ? $req->totalQuantity() : 1;
                    $statusKey = $ret->status === 'menunggu' ? 'pending' : ($ret->status === 'disetujui' ? 'returned' : ($ret->status === 'ditolak' ? 'rejected' : 'pending'));
                    $statusLabel = $ret->status === 'menunggu' ? 'Menunggu' : ($ret->status === 'disetujui' ? 'Disetujui' : ($ret->status === 'ditolak' ? 'Ditolak' : ucfirst($ret->status)));
                    $cardStatusClass = 'mc-card--' . $statusKey;
                    $badgeClass = 'mc-badge--' . $statusKey;
                @endphp
                <div class="mc-card {{ $cardStatusClass }}">
                    <div class="mc-card-header">
                        <div>
                            <div class="mc-card-user">{{ auth()->user()->name ?? 'Siswa' }}</div>
                            <div class="mc-card-user-sub">NIS: {{ auth()->user()->nis ?? '-' }} &bull; ID: #{{ $ret->borrowing_request_id }}</div>
                        </div>
                        <span class="mc-badge {{ $badgeClass }}">
                            <span class="mc-badge-dot"></span>
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <div class="mc-card-details">
                        <div class="mc-card-row">
                            <span class="mc-card-label">Barang:</span>
                            <span class="mc-card-val">{{ $itemName }}</span>
                        </div>
                        <div class="mc-card-row">
                            <span class="mc-card-label">Jumlah:</span>
                            <span class="mc-card-val">{{ $itemQty }} unit</span>
                        </div>
                        <div class="mc-card-row">
                            <span class="mc-card-label">Kondisi Fisik:</span>
                            <span class="mc-card-val">{{ $ret->kondisi_label }}</span>
                        </div>
                        <div class="mc-card-row">
                            <span class="mc-card-label">Tgl Pengajuan:</span>
                            <span class="mc-card-val">{{ $ret->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($ret->catatan)
                        <div class="mc-card-row">
                            <span class="mc-card-label">Catatan:</span>
                            <span class="mc-card-val">"{{ $ret->catatan }}"</span>
                        </div>
                        @endif
                        @if($ret->status === 'ditolak' && $ret->alasan_ditolak)
                        <div class="mc-card-row" style="margin-top: 4px; padding-top: 6px; border-top: 1px dashed var(--border2); color: var(--s-rejected);">
                            <span class="mc-card-label" style="color: var(--s-rejected); font-weight: 700;">Alasan Ditolak:</span>
                            <span class="mc-card-val" style="color: var(--s-rejected); font-weight: 700;">"{{ $ret->alasan_ditolak }}"</span>
                        </div>
                        @endif
                        @if($ret->foto_bukti)
                        <div class="mc-card-row" style="margin-top: 4px; padding-top: 6px; border-top: 1px dashed var(--border2);">
                            <span class="mc-card-label">Foto Bukti:</span>
                            <span class="mc-card-val" style="cursor: pointer; color: var(--primary);" onclick="openPhotoModal('{{ asset('storage/' . $ret->foto_bukti) }}')">Lihat Foto</span>
                        </div>
                        @endif
                    </div>

                    @if($ret->status === 'ditolak' && $ret->borrowingRequest && $ret->borrowingRequest->status === 'borrowed')
                        <div class="mc-card-actions">
                            <a href="{{ route('student.returns.create', $ret->borrowing_request_id) }}" class="mc-card-btn mc-card-btn-primary">
                                Ajukan Pengembalian Ulang &rarr;
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <style>
            @media (max-width: 768px) {
                .s-history-table-wrap { display: none !important; }
            }
        </style>

        <div style="margin-top: 18px;">
            {{ $returns->links() }}
        </div>
    @else
        <div class="s-empty">
            <div class="s-empty-icon-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:32px;height:32px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="s-empty-title">Belum Ada Riwayat Pengembalian</div>
            <div class="s-empty-sub">Anda belum pernah mengajukan pengembalian barang pinjaman.</div>
        </div>
    @endif
</div>

{{-- Modal Image Preview --}}
<div id="photoModal" onclick="closePhotoModal()" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.7); z-index: 999; align-items: center; justify-content: center; padding: 20px; backdrop-filter: blur(4px);">
    <div style="max-width: 90%; max-height: 85vh; border-radius: 16px; background: var(--card); overflow: hidden; position: relative; box-shadow: 0 20px 40px rgba(0,0,0,0.5); border: 1px solid var(--border2);" onclick="event.stopPropagation()">
        <button onclick="closePhotoModal()" style="position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.6); color: #fff; border: none; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <img id="modalImg" src="" alt="Foto Bukti Pengembalian" style="width: 100%; max-height: 75vh; object-fit: contain; display: block;">
    </div>
</div>

<script>
    function openPhotoModal(src) {
        document.getElementById('modalImg').src = src;
        var m = document.getElementById('photoModal');
        m.style.display = 'flex';
    }
    function closePhotoModal() {
        var m = document.getElementById('photoModal');
        m.style.display = 'none';
        document.getElementById('modalImg').src = '';
    }
</script>
@endsection
