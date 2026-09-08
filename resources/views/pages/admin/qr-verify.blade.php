@extends('layouts.admin')

@section('title', 'Verifikasi QR Code – SIPBAR')
@section('page-heading', 'Verifikasi Pengambilan Barang')

@section('content')
<style>
    .qrv-container {
        max-width: 640px;
        margin: 24px auto;
        padding: 0 16px;
    }
    .qrv-card {
        background: var(--bg-card);
        border: 1px solid var(--border-alt);
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        padding: 32px 28px;
        color: var(--text-primary);
    }
    .qrv-header {
        text-align: center;
        margin-bottom: 24px;
    }
    .qrv-icon-wrap {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }
    .qrv-icon-valid   { background: rgba(16, 185, 129, 0.15); color: #10b981; }
    .qrv-icon-invalid { background: rgba(239, 68, 68, 0.15); color: #ef4444; }
    .qrv-icon-warning { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }

    .qrv-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 6px;
    }
    .qrv-sub {
        font-size: 13px;
        color: var(--text-muted);
    }

    .qrv-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 24px;
    }
    .qrv-table tr {
        border-bottom: 1px solid var(--border-subtle);
    }
    .qrv-table td {
        padding: 11px 8px;
        font-size: 13.5px;
        vertical-align: top;
    }
    .qrv-table td:first-child {
        color: var(--text-muted);
        width: 38%;
        font-weight: 500;
    }
    .qrv-table td:last-child {
        color: var(--text-primary);
        font-weight: 600;
    }

    .qrv-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 700;
    }
    .qrv-badge-approved { background: rgba(16, 185, 129, 0.15); color: #10b981; }
    .qrv-badge-borrowed { background: rgba(59, 130, 246, 0.15); color: #3b82f6; }
    .qrv-badge-rejected { background: rgba(239, 68, 68, 0.15); color: #ef4444; }

    .qrv-actions {
        display: flex;
        gap: 10px;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        padding-top: 12px;
        border-top: 1px solid var(--border-subtle);
    }
    .qrv-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all .2s;
        border: none;
    }
    .qrv-btn-primary {
        background: #2563eb;
        color: #ffffff;
    }
    .qrv-btn-primary:hover {
        background: #1d4ed8;
    }
    .qrv-btn-danger {
        background: #dc2626;
        color: #ffffff;
    }
    .qrv-btn-danger:hover {
        background: #b91c1c;
    }
    .qrv-btn-ghost {
        background: transparent;
        color: var(--text-muted);
        border: 1px solid var(--border-alt);
    }
    .qrv-btn-ghost:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }

    /* Modal Tolak */
    .qrv-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.65);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        pointer-events: none;
        transition: opacity .25s ease;
        padding: 16px;
    }
    .qrv-modal-overlay.active {
        opacity: 1;
        pointer-events: all;
    }
    .qrv-modal {
        background: var(--bg-card);
        border: 1px solid var(--border-alt);
        border-radius: 16px;
        max-width: 480px;
        width: 100%;
        padding: 24px;
        transform: translateY(16px);
        transition: transform .25s ease;
        box-shadow: var(--card-shadow);
        color: var(--text-primary);
    }
    .qrv-modal-overlay.active .qrv-modal {
        transform: translateY(0);
    }
    .qrv-textarea {
        width: 100%;
        background: var(--input-bg);
        border: 1.5px solid var(--input-border);
        border-radius: 10px;
        padding: 10px 14px;
        color: var(--text-primary);
        font-size: 13.5px;
        font-family: inherit;
        outline: none;
        resize: vertical;
        box-sizing: border-box;
    }
    .qrv-textarea:focus {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
    }
</style>

<div class="qrv-container">
    {{-- Alerts --}}
    @if(session('success'))
        <div style="background:rgba(16,185,129,0.15);border:1px solid #10b981;color:#10b981;padding:12px 16px;border-radius:10px;margin-bottom:18px;font-size:13.5px;font-weight:600;display:flex;align-items:center;gap:8px;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background:rgba(239,68,68,0.15);border:1px solid #ef4444;color:#ef4444;padding:12px 16px;border-radius:10px;margin-bottom:18px;font-size:13.5px;font-weight:600;display:flex;align-items:center;gap:8px;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div style="background:rgba(239,68,68,0.15);border:1px solid #ef4444;color:#ef4444;padding:12px 16px;border-radius:10px;margin-bottom:18px;font-size:13.5px;font-weight:600;">
            @foreach($errors->all() as $err)
                <div>• {{ $err }}</div>
            @endforeach
        </div>
    @endif

    <div class="qrv-card">
        @if(!$valid)
            {{-- Invalid / Expired State --}}
            <div class="qrv-header">
                <div class="qrv-icon-wrap qrv-icon-invalid">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:32px;height:32px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div class="qrv-title" style="color:#ef4444">QR Code Tidak Valid</div>
                <div class="qrv-sub">{{ $message ?? 'QR Code ini tidak dapat diverifikasi oleh sistem.' }}</div>
            </div>

            <div style="text-align: center; padding-top: 12px;">
                <a href="{{ route('admin.qr-scanner') }}" class="qrv-btn qrv-btn-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-5v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V8a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1zm12 0h2a1 1 0 001-1V8a1 1 0 00-1-1h-2a1 1 0 00-1 1v1a1 1 0 001 1zM5 20h2a1 1 0 001-1v-1a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1z"/></svg>
                    Buka Scanner QR
                </a>
            </div>
        @else
            {{-- Valid State --}}
            @php $br = $borrowingRequest; @endphp
            <div class="qrv-header">
                @if(in_array($br->status, ['approved', 'qr_ready']))
                    <div class="qrv-icon-wrap qrv-icon-valid">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:32px;height:32px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="qrv-title" style="color:#10b981">QR Code Valid</div>
                    <div class="qrv-sub">Data peminjaman terverifikasi & siap dikonfirmasi pengambilan</div>
                @elseif($br->status === 'borrowed')
                    <div class="qrv-icon-wrap qrv-icon-valid" style="background:rgba(59,130,246,0.15);color:#3b82f6;">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:32px;height:32px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div class="qrv-title" style="color:#3b82f6">Barang Sudah Diambil</div>
                    <div class="qrv-sub">Barang saat ini berstatus Dipinjam oleh siswa</div>
                @elseif($br->status === 'rejected')
                    <div class="qrv-icon-wrap qrv-icon-invalid">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:32px;height:32px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div class="qrv-title" style="color:#ef4444">Pengambilan Ditolak</div>
                    <div class="qrv-sub">Permohonan peminjaman ini telah ditolak oleh petugas/guru</div>
                @else
                    <div class="qrv-icon-wrap qrv-icon-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:32px;height:32px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="qrv-title">{{ $br->status_label }}</div>
                    <div class="qrv-sub">Status permohonan saat ini: {{ $br->status_label }}</div>
                @endif
            </div>

            <table class="qrv-table">
                <tr>
                    <td>Nama Siswa</td>
                    <td>{{ $br->user?->name ?? 'Siswa tidak ditemukan' }}</td>
                </tr>
                <tr>
                    <td>Kelas</td>
                    <td>{{ $br->user?->classroom?->name ?? $br->user?->kelas ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Barang</td>
                    <td>{{ $br->itemWithTrashed?->name ?? $br->item?->name ?? 'Tidak tersedia' }}</td>
                </tr>
                <tr>
                    <td>Stok Tersedia</td>
                    <td>
                        @php
                            $item = $br->itemWithTrashed ?? $br->item;
                            $availableStock = $item ? $item->available_stock : 0;
                            $totalStock = $item ? $item->stock : 0;
                        @endphp
                        @if($item)
                            <span style="color: {{ $availableStock > 0 ? '#10b981' : '#ef4444' }}; font-weight: 700;">
                                {{ $availableStock }} unit
                            </span>
                            <span style="color: var(--text-muted); font-weight: 400; font-size: 12px;">
                                (dari {{ $totalStock }} total)
                            </span>
                        @else
                            <span style="color: var(--text-muted);">-</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Jumlah</td>
                    <td>{{ $br->quantity }} unit</td>
                </tr>
                <tr>
                    <td>Tgl Pinjam</td>
                    <td>{{ \Carbon\Carbon::parse($br->borrow_date)->format('d M Y') }}</td>
                </tr>
                <tr>
                    <td>Tgl Kembali</td>
                    <td>{{ \Carbon\Carbon::parse($br->return_date)->format('d M Y') }}</td>
                </tr>
                <tr>
                    <td>Jam Kembali</td>
                    <td>{{ $br->return_time ? \Carbon\Carbon::parse($br->return_time)->format('H:i') . ' WIB' : '-' }}</td>
                </tr>
                <tr>
                    <td>Keperluan</td>
                    <td>{{ $br->purpose ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Catatan</td>
                    <td style="{{ $br->notes ? '' : 'color:var(--text-muted);font-weight:400' }}">
                        {{ $br->notes ?? '-' }}
                    </td>
                </tr>
                @if($br->teacher)
                <tr>
                    <td>Guru Pembimbing</td>
                    <td>{{ $br->teacher->name }}</td>
                </tr>
                @endif
                <tr>
                    <td>Status</td>
                    <td>
                        @if(in_array($br->status, ['approved', 'qr_ready']))
                            <span class="qrv-badge qrv-badge-approved">
                                <span style="width:7px;height:7px;border-radius:50%;background:#10b981"></span>
                                Disetujui
                            </span>
                        @elseif($br->status === 'borrowed')
                            <span class="qrv-badge qrv-badge-borrowed">
                                <span style="width:7px;height:7px;border-radius:50%;background:#3b82f6"></span>
                                Dipinjam
                            </span>
                        @elseif($br->status === 'rejected')
                            <span class="qrv-badge qrv-badge-rejected">
                                <span style="width:7px;height:7px;border-radius:50%;background:#ef4444"></span>
                                Ditolak
                            </span>
                        @else
                            <span class="qrv-badge">
                                {{ strtoupper($br->status) }}
                            </span>
                        @endif
                    </td>
                </tr>
                @if($br->status === 'rejected' && $br->rejection_reason)
                <tr>
                    <td style="color:#ef4444">Alasan Penolakan</td>
                    <td style="color:#ef4444">{{ $br->rejection_reason }}</td>
                </tr>
                @endif
            </table>

            @if(in_array($br->status, ['approved', 'qr_ready']))
                <div class="qrv-actions">
                    <a href="{{ route('admin.qr-scanner') }}" class="qrv-btn qrv-btn-ghost">
                        Scanner
                    </a>

                    {{-- Tombol Buka Modal Tolak --}}
                    <button type="button" onclick="openRejectModal()" class="qrv-btn qrv-btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Tolak
                    </button>

                    {{-- Form Konfirmasi Pengambilan (Approve) --}}
                    <form method="POST" action="{{ route('admin.qr.confirm-checkout', $br->id) }}" style="display:inline;margin:0">
                        @csrf
                        <button type="submit" class="qrv-btn qrv-btn-primary" onclick="return confirm('Konfirmasi bahwa barang telah diserahkan kepada siswa?')">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            Konfirmasi Pengambilan
                        </button>
                    </form>
                </div>
            @else
                <div class="qrv-actions">
                    <a href="{{ route('admin.qr-scanner') }}" class="qrv-btn qrv-btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-5v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V8a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1zm12 0h2a1 1 0 001-1V8a1 1 0 00-1-1h-2a1 1 0 00-1 1v1a1 1 0 001 1zM5 20h2a1 1 0 001-1v-1a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1z"/></svg>
                        Scan QR Lainnya
                    </a>
                </div>
            @endif
        @endif
    </div>
</div>

@if($valid && isset($borrowingRequest) && in_array($borrowingRequest->status, ['approved', 'qr_ready']))
{{-- Modal Tolak Pengambilan --}}
<div id="rejectModalOverlay" class="qrv-modal-overlay">
    <div class="qrv-modal">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
            <div style="font-size:16px;font-weight:700;color:#ef4444;display:flex;align-items:center;gap:8px;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Tolak Pengambilan Barang
            </div>
            <button type="button" onclick="closeRejectModal()" style="background:transparent;border:none;color:var(--text-muted);cursor:pointer;padding:4px">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <p style="font-size:13px;color:var(--text-muted);margin-bottom:16px;line-height:1.5;">
            Pengambilan barang atas nama <strong>{{ $borrowingRequest->user?->name }}</strong> untuk barang <strong>{{ $borrowingRequest->itemWithTrashed?->name ?? $borrowingRequest->item?->name }}</strong> akan ditolak. Alasan penolakan <strong>wajib diisi</strong>.
        </p>

        <form method="POST" action="{{ route('admin.qr.reject-checkout', $borrowingRequest->id) }}">
            @csrf
            <div style="margin-bottom:18px;">
                <label for="rejection_reason" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:var(--text-primary)">
                    Alasan Penolakan <span style="color:#ef4444">*</span>
                </label>
                <textarea 
                    name="rejection_reason" 
                    id="rejection_reason" 
                    rows="3" 
                    class="qrv-textarea" 
                    placeholder="Tuliskan alasan penolakan (misal: kondisi barang fisik sedang rusak / siswa tidak membawa tanda pengenal / permohonan dibatalkan guru)..."
                    required
                    minlength="5"
                    maxlength="500"
                >{{ old('rejection_reason') }}</textarea>
                <div style="font-size:11.5px;color:var(--text-muted);margin-top:4px;">Minimal 5 karakter. Alasan akan tercatat pada data peminjaman siswa.</div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;">
                <button type="button" onclick="closeRejectModal()" class="qrv-btn qrv-btn-ghost">Batal</button>
                <button type="submit" class="qrv-btn qrv-btn-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Kirim Penolakan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openRejectModal() {
    var modal = document.getElementById('rejectModalOverlay');
    if (modal) {
        modal.classList.add('active');
        var textarea = document.getElementById('rejection_reason');
        if (textarea) setTimeout(function(){ textarea.focus(); }, 150);
    }
}
function closeRejectModal() {
    var modal = document.getElementById('rejectModalOverlay');
    if (modal) {
        modal.classList.remove('active');
    }
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeRejectModal();
});
</script>
@endif
@endsection
