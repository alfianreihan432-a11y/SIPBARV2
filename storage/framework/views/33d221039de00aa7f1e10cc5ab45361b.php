<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Ditolak — SIPBAR</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #fef2f2;
            color: #0b2220;
            padding: 32px 16px;
        }
        .wrapper {
            max-width: 560px;
            margin: 0 auto;
        }
        .header {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
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
            border-left: 1px solid #fecaca;
            border-right: 1px solid #fecaca;
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
            background: #fff5f5;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 24px;
        }
        .info-card h3 {
            font-size: 12px;
            font-weight: 700;
            color: #dc2626;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 0;
            border-bottom: 1px solid #fecaca;
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
        }
        .info-value {
            font-size: 13px;
            color: #0b2220;
            font-weight: 600;
            text-align: right;
        }
        .reason-box {
            background: #fff5f5;
            border: 2px solid #fca5a5;
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 24px;
        }
        .reason-box h3 {
            font-size: 12px;
            font-weight: 700;
            color: #dc2626;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .reason-box p {
            font-size: 14px;
            color: #450a0a;
            line-height: 1.7;
            font-style: italic;
        }
        .advice-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 12px;
            padding: 18px 22px;
            margin-bottom: 24px;
        }
        .advice-box h3 {
            font-size: 12px;
            font-weight: 700;
            color: #1d4ed8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .advice-box p {
            font-size: 13px;
            color: #1e3a8a;
            line-height: 1.7;
        }
        .footer {
            background: #fff5f5;
            border: 1px solid #fecaca;
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
            color: #dc2626;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Header -->
        <div class="header">
            <div class="header-badge">❌ Ditolak</div>
            <h1>Pengajuan Peminjaman<br>Tidak Disetujui</h1>
            <p>Jangan menyerah — kamu bisa mengajukan kembali</p>
        </div>

        <!-- Body -->
        <div class="body">
            <p class="greeting">Halo, <?php echo e($borrowingRequest->user?->name ?? 'Siswa'); ?>,</p>
            <p class="intro">
                Kami perlu menyampaikan bahwa pengajuan peminjaman barang yang kamu ajukan
                <strong>tidak dapat disetujui</strong> saat ini. Berikut detail pengajuan
                dan alasan dari guru.
            </p>

            <!-- Info Card -->
            <div class="info-card">
                <h3>📦 Detail Pengajuan</h3>
                <div class="info-row">
                    <span class="info-label">Barang</span>
                    <span class="info-value"><?php echo e($borrowingRequest->item?->name ?? '-'); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jumlah</span>
                    <span class="info-value"><?php echo e($borrowingRequest->quantity); ?> unit</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Pinjam</span>
                    <span class="info-value"><?php echo e($borrowingRequest->borrow_date?->format('d M Y') ?? '-'); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Kembali</span>
                    <span class="info-value"><?php echo e($borrowingRequest->return_date?->format('d M Y') ?? '-'); ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($borrowingRequest->return_time): ?> · <?php echo e($borrowingRequest->return_time); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ditolak oleh</span>
                    <span class="info-value"><?php echo e($borrowingRequest->teacher?->name ?? '-'); ?></span>
                </div>
            </div>

            <!-- Rejection Reason -->
            <div class="reason-box">
                <h3>💬 Alasan Penolakan</h3>
                <p><?php echo e($borrowingRequest->rejection_reason ?? 'Tidak ada alasan yang diberikan.'); ?></p>
            </div>

            <!-- Advice -->
            <div class="advice-box">
                <h3>💡 Langkah Selanjutnya</h3>
                <p>
                    Kamu masih dapat mengajukan peminjaman barang yang lain atau mengajukan
                    ulang dengan melengkapi keperluan yang lebih detail. Hubungi guru terkait
                    jika kamu memerlukan klarifikasi lebih lanjut.
                </p>
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
<?php /**PATH C:\Users\Dell\SIPBARV2\resources\views/emails/rejected.blade.php ENDPATH**/ ?>