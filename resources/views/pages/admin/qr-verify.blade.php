@extends('layouts.app')

@section('title', 'Verifikasi QR Code')

@section('content')
<style>
    .qrv-wrap {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f5f9;
        padding: 24px;
    }
    .qrv-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 32px rgba(0,0,0,.10);
        padding: 40px 36px;
        max-width: 480px;
        width: 100%;
        text-align: center;
    }
    .qrv-icon-wrap {
        width: 72px; height: 72px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 20px;
    }
    .qrv-icon-valid   { background: #dcfce7; }
    .qrv-icon-invalid { background: #fee2e2; }

    .qrv-title   { font-size: 22px; font-weight: 800; color: #1e293b; margin-bottom: 8px; }
    .qrv-sub     { font-size: 14px; color: #64748b; margin-bottom: 28px; }

    .qrv-table   { width: 100%; border-collapse: collapse; text-align: left; margin-bottom: 24px; }
    .qrv-table td { padding: 10px 4px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
    .qrv-table td:first-child { color: #64748b; width: 40%; }
    .qrv-table td:last-child  { color: #1e293b; font-weight: 600; }

    .qrv-btn {
        display: inline-block; padding: 12px 28px;
        background: #1d4ed8; color: #fff;
        border: none; border-radius: 10px;
        font-size: 14px; font-weight: 600;
        cursor: pointer; text-decoration: none;
        transition: background .2s;
    }
    .qrv-btn:hover { background: #1e40af; }
    .qrv-btn-danger { background: #dc2626; }
    .qrv-btn-danger:hover { background: #b91c1c; }
    .qrv-btn-outline {
        background: transparent; color: #1d4ed8;
        border: 1.5px solid #1d4ed8; margin-right: 8px;
    }
    .qrv-btn-outline:hover { background: #eff6ff; }

    .qrv-badge {
        display: inline-block; padding: 4px 12px;
        border-radius: 6px; font-size: 12px; font-weight: 700;
    }
    .qrv-badge-approved { background: #dbeafe; color: #1d4ed8; }
    .qrv-badge-borrowed { background: #dcfce7; color: #15803d; }
</style>

<div class="qrv-wrap">
    <div class="qrv-card">

        @if(session('success'))
            <div style="background:#dcfce7;color:#15803d;padding:12px 16px;border-radius:10px;margin-bottom:20px;font-size:14px;font-weight:600;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background:#fee2e2;color:#dc2626;padding:12px 16px;border-radius:10px;margin-bottom:20px;font-size:14px;font-weight:600;">
                {{ session('error') }}
            </div>
        @endif

        @if(!$valid)
            {{-- Invalid / Expired --}}
            <div class="qrv-icon-wrap qrv-icon-invalid">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:36px;height:36px;color:#dc2626" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <div class="qrv-title" style="color:#dc2626">QR Code Tidak Valid</div>
            <div class="qrv-sub">{{ $message ?? 'QR Code ini tidak dapat diverifikasi.' }}</div>
            <a href="{{ route('admin.qr-scanner') }}" class="qrv-btn qrv-btn-danger">
                Kembali ke Scanner
            </a>

        @else
            {{-- Valid --}}
            @php $br = $borrowingRequest; @endphp
            <div class="qrv-icon-wrap qrv-icon-valid">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:36px;height:36px;color:#16a34a" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="qrv-title" style="color:#16a34a">QR Code Valid ✓</div>
            <div class="qrv-sub">Data peminjaman berhasil diverifikasi</div>

            <table class="qrv-table">
                <tr>
                    <td>Nama Siswa</td>
                    <td>{{ $br->user->name }}</td>
                </tr>
                <tr>
                    <td>Barang</td>
                    <td>{{ $br->itemWithTrashed?->name ?? 'Tidak tersedia' }}</td>
                </tr>
                <tr>
                    <td>Jumlah</td>
                    <td>{{ $br->quantity }} pcs</td>
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
                    <td>Status</td>
                    <td>
                        <span class="qrv-badge {{ $br->status === 'approved' ? 'qrv-badge-approved' : 'qrv-badge-borrowed' }}">
                            {{ strtoupper($br->status) }}
                        </span>
                    </td>
                </tr>
            </table>

            @if($br->status === 'approved')
                <form method="POST" action="{{ route('admin.qr.confirm-checkout', $br->id) }}" style="display:inline">
                    @csrf
                    <a href="{{ route('admin.qr-scanner') }}" class="qrv-btn qrv-btn-outline">Batal</a>
                    <button type="submit" class="qrv-btn">✓ Konfirmasi Pengambilan</button>
                </form>
            @else
                <a href="{{ route('admin.qr-scanner') }}" class="qrv-btn">Kembali ke Scanner</a>
            @endif
        @endif

    </div>
</div>
@endsection
