@extends('layouts.siswa')

@section('title', 'QR Code Peminjaman – SIPBAR')

@section('content')
<style>
    .qr-page-container {
        max-width: 540px;
        margin: 20px auto;
    }

    .qr-card {
        background: var(--card, #ffffff);
        border: 1px solid var(--border, #e2e8f0);
        border-radius: 16px;
        padding: 32px 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        text-align: center;
    }

    .qr-title {
        font-family: var(--font-head, 'Space Grotesk', sans-serif);
        font-size: 20px;
        font-weight: 700;
        color: var(--text, #1e293b);
        margin-bottom: 6px;
    }

    .qr-subtitle {
        font-size: 13px;
        color: var(--muted, #64748b);
        margin-bottom: 24px;
    }

    .qr-image-wrapper {
        width: 260px;
        height: 260px;
        margin: 0 auto 24px;
        background: #ffffff;
        border: 2px dashed var(--border, #cbd5e1);
        border-radius: 16px;
        padding: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    }

    .qr-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .qr-details-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 24px;
        text-align: left;
        background: var(--bg, #f8fafc);
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--border, #e2e8f0);
    }

    .qr-details-table tr {
        border-bottom: 1px solid var(--border, #e2e8f0);
    }

    .qr-details-table tr:last-child {
        border-bottom: none;
    }

    .qr-details-table td {
        padding: 12px 16px;
        font-size: 13px;
    }

    .qr-details-label {
        color: var(--muted, #64748b);
        font-weight: 500;
        width: 40%;
    }

    .qr-details-val {
        color: var(--text, #1e293b);
        font-weight: 600;
    }

    .qr-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: capitalize;
    }

    .qr-status-badge.approved, .qr-status-badge.qr_ready {
        background: rgba(16, 185, 129, 0.12);
        color: #10b981;
    }

    .qr-status-badge.borrowed {
        background: rgba(59, 130, 246, 0.12);
        color: #3b82f6;
    }

    .qr-notice {
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.25);
        color: #b45309;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 12px;
        line-height: 1.5;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
        text-align: left;
    }

    .dark .qr-notice {
        background: rgba(245, 158, 11, 0.15);
        color: #fcd34d;
    }

    .qr-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
    }
</style>

<div class="qr-page-container">
    <div class="qr-card">
        <h1 class="qr-title">QR Code Peminjaman</h1>
        <p class="qr-subtitle">Tunjukkan QR Code ini kepada petugas inventaris / admin saat mengambil barang</p>

        <div class="qr-image-wrapper">
            <img src="{{ $qr_image }}" alt="QR Code Peminjaman #{{ $borrowing_id }}">
        </div>

        <table class="qr-details-table">
            <tr>
                <td class="qr-details-label">ID Peminjaman</td>
                <td class="qr-details-val">#{{ $borrowing_id }}</td>
            </tr>
            <tr>
                <td class="qr-details-label">Nama Barang</td>
                <td class="qr-details-val">{{ $item_name }}</td>
            </tr>
            <tr>
                <td class="qr-details-label">Status</td>
                <td class="qr-details-val">
                    @php
                        $statusLabel = [
                            'approved' => 'Disetujui',
                            'qr_ready' => 'QR Siap',
                            'borrowed' => 'Sedang Dipinjam'
                        ][$status] ?? ucfirst($status);
                    @endphp
                    <span class="qr-status-badge {{ $status }}">
                        ● {{ $statusLabel }}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="qr-details-label">Masa Berlaku</td>
                <td class="qr-details-val">
                    {{ $expires_at ?? 'Tidak ada batas waktu' }}
                </td>
            </tr>
        </table>

        <div class="qr-notice">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                Pastikan layar ponsel cukup terang agar QR Code dapat terbaca oleh scanner petugas.
            </div>
        </div>

        <div class="qr-actions">
            <a href="{{ route('student.history') }}" class="s-btn s-btn--secondary" style="padding:10px 20px;text-decoration:none">
                Riwayat Peminjaman
            </a>
            <a href="{{ route('student.dashboard') }}" class="s-btn s-btn--primary" style="padding:10px 20px;text-decoration:none">
                Ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
