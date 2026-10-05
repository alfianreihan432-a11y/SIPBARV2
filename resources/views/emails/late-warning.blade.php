<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peringatan Keterlambatan Pengembalian Barang — SIPBAR</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #fdf2f4;
            color: #0f172a;
            padding: 32px 16px;
        }
        .wrapper {
            max-width: 560px;
            margin: 0 auto;
        }
        .header {
            background: linear-gradient(135deg, #991b1b 0%, #7f1d1d 100%);
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
            border-left: 1px solid #f0d5da;
            border-right: 1px solid #f0d5da;
        }
        .greeting {
            font-size: 16px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 12px;
        }
        .intro {
            font-size: 14px;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .warning-box {
            background: #fff1f2;
            border: 2px solid #fca5a5;
            border-radius: 12px;
            padding: 18px 22px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .warning-box-text {
            font-size: 13.5px;
            font-weight: 600;
            color: #991b1b;
            line-height: 1.5;
        }
        .info-card {
            background: #fdf2f4;
            border: 1px solid #f0d5da;
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 24px;
        }
        .info-card h3 {
            font-size: 12px;
            font-weight: 700;
            color: #991b1b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 0;
            border-bottom: 1px solid #f0d5da;
            gap: 16px;
        }
        .info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .info-label {
            font-size: 12px;
            color: #64748b;
            font-weight: 500;
        }
        .info-value {
            font-size: 13px;
            color: #0f172a;
            font-weight: 600;
            text-align: right;
        }
        .urgent-tag {
            color: #dc2626;
            font-weight: 800;
        }
        .action-box {
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 12px;
            padding: 18px 22px;
            margin-bottom: 24px;
        }
        .action-box h3 {
            font-size: 12px;
            font-weight: 700;
            color: #c2410c;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .action-box p {
            font-size: 13px;
            color: #9a3412;
            line-height: 1.7;
        }
        .footer {
            background: #fdf2f4;
            border: 1px solid #f0d5da;
            border-top: none;
            border-radius: 0 0 16px 16px;
            padding: 20px 36px;
            text-align: center;
        }
        .footer p {
            font-size: 12px;
            color: #64748b;
            line-height: 1.6;
        }
        .footer-brand {
            font-weight: 700;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Header -->
        <div class="header">
            <div class="header-badge">Peringatan Keterlambatan</div>
            <h1>Peminjaman Melewati Batas Waktu</h1>
            <p>Mohon segera lakukan pengembalian barang ke petugas</p>
        </div>

        <!-- Body -->
        <div class="body">
            <p class="greeting">Halo, {{ $borrowingRequest->user?->name ?? 'Peminjam' }},</p>
            <p class="intro">
                Berdasarkan catatan sistem peminjaman SIPBAR SMKN 1 Bangsri, peminjaman barang atas nama Anda telah 
                <strong>melewati batas waktu pengembalian yang telah disepakati</strong>.
            </p>

            <div class="warning-box">
                <div class="warning-box-text">
                    ⚠️ Keterlambatan: <strong>{{ $daysOverdue > 0 ? $daysOverdue . ' Hari' : 'Hari ini (lewat jam)' }}</strong>.
                    Barang belum dikembalikan ke inventaris.
                </div>
            </div>

            <!-- Info Card -->
            <div class="info-card">
                <h3>Detail Peminjaman</h3>
                <div class="info-row">
                    <span class="info-label">No. Peminjaman</span>
                    <span class="info-value">#BR-{{ str_pad($borrowingRequest->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Nama Barang</span>
                    <span class="info-value">{{ $borrowingRequest->item_display_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jumlah Unit</span>
                    <span class="info-value">{{ $borrowingRequest->totalQuantity() }} unit</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Pinjam</span>
                    <span class="info-value">{{ $borrowingRequest->borrow_date?->format('d M Y') ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Batas Pengembalian</span>
                    <span class="info-value urgent-tag">
                        {{ $borrowingRequest->return_date?->format('d M Y') ?? '-' }}
                        @if($borrowingRequest->return_time) · {{ $borrowingRequest->return_time }}@endif
                    </span>
                </div>
                @if($sender)
                <div class="info-row">
                    <span class="info-label">Pengirim Peringatan</span>
                    <span class="info-value">{{ $sender->name }} (Kepala Jurusan)</span>
                </div>
                @endif
            </div>

            <!-- Action Advice -->
            <div class="action-box">
                <h3>Tindakan yang Harus Dilakukan</h3>
                <p>
                    1. Segera kembalikan unit barang dalam kondisi baik ke petugas/admin SIPBAR.<br>
                    2. Tunjukkan QR Code pengembalian Anda di aplikasi SIPBAR.<br>
                    3. Jika terdapat kendala atau barang mengalami kerusakan/hilang, segera laporkan kepada Kepala Jurusan atau petugas terkait.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                Email ini dikirim atas instruksi Kepala Jurusan melalui sistem
                <span class="footer-brand">SIPBAR SMKN 1 Bangsri</span>.<br>
                Mohon jangan membalas email ini secara langsung.
            </p>
        </div>
    </div>
</body>
</html>
