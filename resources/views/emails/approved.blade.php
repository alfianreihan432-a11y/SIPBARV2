<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Disetujui — SIPBAR</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f0faf8;
            color: #0b2220;
            padding: 32px 16px;
        }
        .wrapper {
            max-width: 560px;
            margin: 0 auto;
        }
        .header {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            border-radius: 16px 16px 0 0;
            padding: 32px 36px 28px;
            text-align: center;
        }
        .header-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 4px 14px;
            border-radius: 999px;
            margin-bottom: 14px;
        }
        .header h1 {
            color: #fff;
            font-size: 22px;
            font-weight: 800;
            line-height: 1.3;
        }
        .header p {
            color: rgba(255,255,255,0.85);
            font-size: 13px;
            margin-top: 8px;
        }
        .body {
            background: #fff;
            padding: 32px 36px;
            border-left: 1px solid #d1fae5;
            border-right: 1px solid #d1fae5;
        }
        .greeting {
            font-size: 16px;
            font-weight: 600;
            color: #0b2220;
            margin-bottom: 12px;
        }
        .intro {
            font-size: 14px;
            color: #41635f;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .info-card {
            background: #f0fdf4;
            border: 1px solid #a7f3d0;
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 28px;
        }
        .info-card h3 {
            font-size: 12px;
            font-weight: 700;
            color: #059669;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 0;
            border-bottom: 1px solid #d1fae5;
            gap: 16px;
        }
        .info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .info-label {
            font-size: 12px;
            color: #618581;
            font-weight: 500;
            white-space: nowrap;
        }
        .info-value {
            font-size: 13px;
            color: #0b2220;
            font-weight: 700;
            text-align: right;
        }
        .qr-section {
            text-align: center;
            margin-bottom: 28px;
            padding: 24px;
            background: #f8fff8;
            border: 2px dashed #6ee7b7;
            border-radius: 16px;
        }
        .qr-section h3 {
            font-size: 14px;
            font-weight: 700;
            color: #0b2220;
            margin-bottom: 6px;
        }
        .qr-section p {
            font-size: 12px;
            color: #618581;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        .qr-section img {
            width: 200px;
            height: 200px;
            border-radius: 12px;
            border: 3px solid #a7f3d0;
            padding: 8px;
            background: #fff;
        }
        .instructions {
            background: #fffbeb;
            border: 1px solid #fcd34d;
            border-radius: 12px;
            padding: 18px 22px;
            margin-bottom: 24px;
        }
        .instructions h3 {
            font-size: 12px;
            font-weight: 700;
            color: #92400e;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }
        .instructions ol {
            padding-left: 18px;
        }
        .instructions li {
            font-size: 13px;
            color: #78350f;
            line-height: 1.7;
            margin-bottom: 4px;
        }
        .footer {
            background: #f8fffe;
            border: 1px solid #d1fae5;
            border-top: none;
            border-radius: 0 0 16px 16px;
            padding: 20px 36px;
            text-align: center;
        }
        .footer p {
            font-size: 12px;
            color: #618581;
            line-height: 1.6;
        }
        .footer-brand {
            font-weight: 700;
            color: #059669;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Header -->
        <div class="header">
            <div class="header-badge">Disetujui</div>
            <h1>Pengajuan Peminjaman<br>Kamu Disetujui!</h1>
            <p>Tunjukkan QR Code di bawah saat mengambil barang</p>
        </div>

        <!-- Body -->
        <div class="body">
            <p class="greeting">Halo, {{ $borrowingRequest->user?->name ?? 'Siswa' }}!</p>
            <p class="intro">
                Kabar baik! Guru telah <strong>menyetujui</strong> pengajuan peminjaman barangmu.
                Simpan QR Code di bawah ini — kamu wajib menunjukkannya kepada petugas
                saat pengambilan barang.
            </p>

            <!-- Info Card -->
            <div class="info-card">
                <h3>Detail Peminjaman</h3>
                <div class="info-row">
                    <span class="info-label">Barang</span>
                    <span class="info-value">{{ $borrowingRequest->item?->name ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jumlah</span>
                    <span class="info-value">{{ $borrowingRequest->quantity }} unit</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Pinjam</span>
                    <span class="info-value">{{ $borrowingRequest->borrow_date?->format('d M Y') ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Kembali</span>
                    <span class="info-value">{{ $borrowingRequest->return_date?->format('d M Y') ?? '-' }} @if($borrowingRequest->return_time) · {{ $borrowingRequest->return_time }}@endif</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Disetujui oleh</span>
                    <span class="info-value">{{ $borrowingRequest->teacher?->name ?? '-' }}</span>
                </div>
            </div>

            <!-- QR Code -->
            <div class="qr-section">
                <h3>QR Code Pengambilan</h3>
                <p>Screenshot atau cetak QR Code ini.<br>Tunjukkan kepada petugas saat mengambil barang.</p>
                <img
                    src="data:image/png;base64,{{ $qrBase64 }}"
                    alt="QR Code Peminjaman"
                >
            </div>

            <!-- Instructions -->
            <div class="instructions">
                <h3>Langkah Selanjutnya</h3>
                <ol>
                    <li>Simpan atau screenshot QR Code di atas.</li>
                    <li>Datang ke tempat penyimpanan barang pada tanggal <strong>{{ $borrowingRequest->borrow_date?->format('d M Y') ?? '-' }}</strong>.</li>
                    <li>Tunjukkan QR Code ini kepada petugas untuk verifikasi.</li>
                    <li>Kembalikan barang paling lambat <strong>{{ $borrowingRequest->return_date?->format('d M Y') ?? '-' }}</strong>.</li>
                </ol>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                Email ini dikirim otomatis oleh sistem
                <span class="footer-brand">SIPBAR</span> — Sistem Peminjaman Barang Sekolah.<br>
                Mohon jangan membalas email ini.
            </p>
        </div>
    </div>
</body>
</html>
