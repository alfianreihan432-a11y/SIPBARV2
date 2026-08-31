<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Peminjaman Baru — SIPBAR</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f0faf8;
            color: #0b2220;
            padding: 32px 16px;
        }
        .wrapper {
            max-width: 580px;
            margin: 0 auto;
        }
        .header {
            background: linear-gradient(135deg, #2d7a6e 0%, #1e5c52 100%);
            border-radius: 16px 16px 0 0;
            padding: 32px 36px 28px;
            text-align: center;
        }
        .header-badge {
            display: inline-block;
            background: rgba(255,255,255,0.15);
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
            color: rgba(255,255,255,0.8);
            font-size: 13px;
            margin-top: 8px;
        }
        .body {
            background: #fff;
            padding: 32px 36px;
            border-left: 1px solid #ddf0ed;
            border-right: 1px solid #ddf0ed;
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
            background: #f0faf8;
            border: 1px solid #b8deda;
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 28px;
        }
        .info-card h3 {
            font-size: 12px;
            font-weight: 700;
            color: #2d7a6e;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 9px 0;
            border-bottom: 1px solid #ddf0ed;
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
            min-width: 110px;
        }
        .info-value {
            font-size: 13px;
            color: #0b2220;
            font-weight: 600;
            text-align: right;
            flex: 1;
        }
        .purpose-box {
            background: #f0faf8;
            border: 1px solid #b8deda;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 28px;
        }
        .purpose-box h3 {
            font-size: 12px;
            font-weight: 700;
            color: #2d7a6e;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .purpose-box p {
            font-size: 14px;
            color: #0b2220;
            line-height: 1.6;
        }
        .cta-section {
            text-align: center;
            margin-bottom: 24px;
        }
        .cta-section p {
            font-size: 13px;
            color: #41635f;
            margin-bottom: 18px;
            line-height: 1.6;
        }
        .btn-approve {
            display: inline-block;
            background: linear-gradient(135deg, #2d7a6e 0%, #1e5c52 100%);
            color: #fff !important;
            text-decoration: none;
            font-size: 15px;
            font-weight: 700;
            padding: 14px 40px;
            border-radius: 12px;
            letter-spacing: 0.3px;
        }
        .deadline-note {
            background: #fffbeb;
            border: 1px solid #fcd34d;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 12px;
            color: #92400e;
            text-align: center;
            margin-top: 16px;
            line-height: 1.5;
        }
        .footer {
            background: #f8fffe;
            border: 1px solid #ddf0ed;
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
            color: #2d7a6e;
        }
        .link-fallback {
            word-break: break-all;
            font-size: 11px;
            color: #618581;
            margin-top: 8px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Header -->
        <div class="header">
            <div class="header-badge">📋 Pengajuan Baru</div>
            <h1>Ada Pengajuan Peminjaman<br>yang Perlu Ditinjau</h1>
            <p>Sistem Peminjaman Barang Sekolah — SIPBAR</p>
        </div>

        <!-- Body -->
        <div class="body">
            <p class="greeting">Yth. {{ $borrowingRequest->teacher?->name ?? 'Bapak/Ibu Guru' }},</p>
            <p class="intro">
                Siswa berikut mengajukan peminjaman barang sekolah dan membutuhkan
                persetujuan Bapak/Ibu. Silakan tinjau detail di bawah dan ambil keputusan.
            </p>

            <!-- Info Card -->
            <div class="info-card">
                <h3>🎒 Detail Pengajuan</h3>
                <div class="info-row">
                    <span class="info-label">Nama Siswa</span>
                    <span class="info-value">{{ $borrowingRequest->user?->name ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Kelas</span>
                    <span class="info-value">{{ $borrowingRequest->user?->kelas ?? '-' }}</span>
                </div>
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
                @if($borrowingRequest->notes)
                <div class="info-row">
                    <span class="info-label">Catatan</span>
                    <span class="info-value">{{ $borrowingRequest->notes }}</span>
                </div>
                @endif
            </div>

            <!-- Purpose -->
            <div class="purpose-box">
                <h3>📝 Keperluan</h3>
                <p>{{ $borrowingRequest->purpose }}</p>
            </div>

            <!-- CTA -->
            <div class="cta-section">
                <p>
                    Klik tombol di bawah untuk membuka halaman konfirmasi.
                    Di sana Bapak/Ibu dapat <strong>menyetujui</strong> atau
                    <strong>menolak</strong> pengajuan ini.
                </p>
                <a href="{{ $approvalUrl }}" class="btn-approve">
                    🔍 Tinjau Pengajuan Ini
                </a>
                <div class="deadline-note">
                    ⏰ Link ini berlaku selama <strong>3 hari</strong> sejak email ini dikirim.
                    Setelah kedaluwarsa, pengajuan masih dapat diproses melalui dashboard SIPBAR.
                </div>
            </div>

            <!-- Fallback link -->
            <p class="link-fallback">
                Jika tombol tidak berfungsi, salin link berikut ke browser:<br>
                {{ $approvalUrl }}
            </p>
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
