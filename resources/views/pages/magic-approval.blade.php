<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pengajuan Peminjaman — SIPBAR</title>
    <meta name="description" content="Halaman konfirmasi persetujuan pengajuan peminjaman barang sekolah">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        :root {
            --teal-50:  #f0faf8;
            --teal-100: #ddf0ed;
            --teal-200: #b8deda;
            --teal-400: #4da99e;
            --teal-600: #2d7a6e;
            --teal-700: #1e5c52;
            --teal-900: #0b2220;
            --text:     #0b2220;
            --muted:    #41635f;
            --subtle:   #618581;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: var(--teal-50);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 16px 60px;
            -webkit-font-smoothing: antialiased;
        }

        /* ─── Top bar ─── */
        .topbar {
            width: 100%;
            max-width: 680px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 32px;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .brand-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--teal-600), var(--teal-700));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }
        .brand-name {
            font-size: 16px;
            font-weight: 800;
            color: var(--teal-700);
        }
        .brand-sub {
            font-size: 11px;
            color: var(--subtle);
            font-weight: 500;
        }

        /* ─── Card ─── */
        .card {
            width: 100%;
            max-width: 680px;
            background: #fff;
            border-radius: 20px;
            border: 1px solid var(--teal-200);
            overflow: hidden;
            box-shadow: 0 4px 32px rgba(29,82,77,0.08);
        }

        .card-header {
            background: linear-gradient(135deg, var(--teal-600) 0%, var(--teal-700) 100%);
            padding: 32px 36px;
        }
        .card-header-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.15);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 999px;
            margin-bottom: 16px;
        }
        .card-header h1 {
            color: #fff;
            font-size: 24px;
            font-weight: 800;
            line-height: 1.3;
            margin-bottom: 6px;
        }
        .card-header p {
            color: rgba(255,255,255,0.8);
            font-size: 13px;
        }

        /* Processed banner */
        .status-banner {
            padding: 16px 36px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .status-banner.approved {
            background: #f0fdf4;
            border-bottom: 1px solid #a7f3d0;
            color: #065f46;
        }
        .status-banner.rejected {
            background: #fff5f5;
            border-bottom: 1px solid #fecaca;
            color: #991b1b;
        }
        .status-banner.pending {
            background: #fffbeb;
            border-bottom: 1px solid #fcd34d;
            color: #92400e;
        }

        .card-body {
            padding: 32px 36px;
        }

        /* ─── Alerts ─── */
        .alert {
            padding: 14px 18px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            line-height: 1.5;
        }
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }
        .alert-error {
            background: #fff5f5;
            border: 1px solid #fecaca;
            color: #991b1b;
        }
        .alert-info {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
        }

        /* ─── Info grid ─── */
        .section-title {
            font-size: 11px;
            font-weight: 700;
            color: var(--teal-600);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 28px;
        }

        .info-item {
            background: var(--teal-50);
            border: 1px solid var(--teal-100);
            border-radius: 12px;
            padding: 14px 16px;
        }
        .info-item.full {
            grid-column: 1 / -1;
        }
        .info-item-label {
            font-size: 11px;
            color: var(--subtle);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .info-item-value {
            font-size: 15px;
            font-weight: 700;
            color: var(--text);
            line-height: 1.3;
        }
        .info-item-sub {
            font-size: 12px;
            color: var(--muted);
            margin-top: 2px;
        }

        .divider {
            border: none;
            border-top: 1px solid var(--teal-100);
            margin: 28px 0;
        }

        /* ─── Action buttons ─── */
        .action-section h2 {
            font-size: 14px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 6px;
        }
        .action-section p {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 700;
            padding: 13px 28px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            letter-spacing: 0.2px;
        }
        .btn:active { transform: scale(0.97); }

        .btn-approve {
            background: linear-gradient(135deg, #059669, #047857);
            color: #fff;
            flex: 1;
            box-shadow: 0 4px 12px rgba(5,150,105,0.3);
        }
        .btn-approve:hover { filter: brightness(1.08); }

        .btn-reject-toggle {
            background: #fff5f5;
            color: #dc2626;
            border: 1.5px solid #fca5a5;
            flex: 1;
        }
        .btn-reject-toggle:hover {
            background: #fef2f2;
        }

        /* ─── Reject form ─── */
        .reject-form {
            display: none;
            background: #fff5f5;
            border: 1.5px solid #fca5a5;
            border-radius: 16px;
            padding: 24px;
            margin-top: 4px;
            animation: slideDown 0.2s ease;
        }
        .reject-form.show { display: block; }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .reject-form label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #991b1b;
            margin-bottom: 8px;
        }
        .reject-form textarea {
            width: 100%;
            min-height: 110px;
            padding: 12px 14px;
            border: 1.5px solid #fca5a5;
            border-radius: 10px;
            font-family: inherit;
            font-size: 14px;
            color: var(--text);
            background: #fff;
            resize: vertical;
            outline: none;
            transition: border-color 0.15s;
            line-height: 1.6;
        }
        .reject-form textarea:focus {
            border-color: #dc2626;
        }
        .reject-form textarea::placeholder { color: #9ca3af; }

        .char-count {
            font-size: 11px;
            color: var(--subtle);
            text-align: right;
            margin-top: 5px;
            margin-bottom: 16px;
        }

        .btn-reject-confirm {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: #fff;
            box-shadow: 0 4px 12px rgba(220,38,38,0.25);
            width: 100%;
        }
        .btn-reject-confirm:hover { filter: brightness(1.08); }

        /* ─── Footer note ─── */
        .footer-note {
            text-align: center;
            font-size: 12px;
            color: var(--subtle);
            line-height: 1.6;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid var(--teal-100);
        }
        .footer-note strong { color: var(--teal-600); }

        @media (max-width: 520px) {
            .card-header, .card-body { padding: 24px 20px; }
            .info-grid { grid-template-columns: 1fr; }
            .action-buttons { flex-direction: column; }
        }
    </style>
</head>
<body>

    <!-- Top bar -->
    <div class="topbar">
        <div class="brand">
            <div class="brand-icon">📦</div>
            <div>
                <div class="brand-name">SIPBAR</div>
                <div class="brand-sub">Sistem Peminjaman Barang Sekolah</div>
            </div>
        </div>
    </div>

    <!-- Main card -->
    <div class="card">

        <!-- Header -->
        <div class="card-header">
            <div class="card-header-badge">📋 Magic Link Approval</div>
            <h1>Konfirmasi Pengajuan<br>Peminjaman Barang</h1>
            <p>Tinjau detail di bawah lalu ambil keputusan persetujuan</p>
        </div>

        @php
            $status = $borrowingRequest->status;
        @endphp

        <!-- Status banner if already processed -->
        @if($status === \App\Models\BorrowingRequest::STATUS_APPROVED)
            <div class="status-banner approved">
                ✅ Pengajuan ini sudah <strong>disetujui</strong> sebelumnya.
            </div>
        @elseif($status === \App\Models\BorrowingRequest::STATUS_REJECTED)
            <div class="status-banner rejected">
                ❌ Pengajuan ini sudah <strong>ditolak</strong> sebelumnya.
            </div>
        @elseif($status !== \App\Models\BorrowingRequest::STATUS_PENDING)
            <div class="status-banner pending">
                ℹ️ Status pengajuan saat ini: <strong>{{ $borrowingRequest->status_label }}</strong>
            </div>
        @endif

        <!-- Body -->
        <div class="card-body">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="alert alert-success">✅ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">❌ {{ session('error') }}</div>
            @endif
            @if(session('info'))
                <div class="alert alert-info">ℹ️ {{ session('info') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-error">
                    ❌ {{ $errors->first() }}
                </div>
            @endif

            <!-- Detail pengajuan -->
            <p class="section-title">🎒 Detail Pengajuan</p>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-item-label">Nama Siswa</div>
                    <div class="info-item-value">{{ $borrowingRequest->user?->name ?? '-' }}</div>
                    <div class="info-item-sub">{{ $borrowingRequest->user?->kelas ?? '' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-item-label">Barang</div>
                    <div class="info-item-value">{{ $borrowingRequest->item?->name ?? '-' }}</div>
                    <div class="info-item-sub">Jumlah: {{ $borrowingRequest->quantity }} unit</div>
                </div>
                <div class="info-item">
                    <div class="info-item-label">Tanggal Pinjam</div>
                    <div class="info-item-value">{{ $borrowingRequest->borrow_date?->format('d M Y') ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-item-label">Tanggal Kembali</div>
                    <div class="info-item-value">{{ $borrowingRequest->return_date?->format('d M Y') ?? '-' }}</div>
                    <div class="info-item-sub">
                        {{ $borrowingRequest->borrow_date && $borrowingRequest->return_date
                            ? $borrowingRequest->borrow_date->diffInDays($borrowingRequest->return_date) . ' hari'
                            : '' }}
                    </div>
                </div>
                <div class="info-item full">
                    <div class="info-item-label">Keperluan</div>
                    <div class="info-item-value" style="font-size:14px;font-weight:500;">
                        {{ $borrowingRequest->purpose }}
                    </div>
                </div>
                @if($borrowingRequest->notes)
                <div class="info-item full">
                    <div class="info-item-label">Catatan Tambahan</div>
                    <div class="info-item-value" style="font-size:14px;font-weight:400;color:var(--muted);">
                        {{ $borrowingRequest->notes }}
                    </div>
                </div>
                @endif
            </div>

            {{-- Rejection reason (if already rejected) --}}
            @if($borrowingRequest->rejection_reason)
                <hr class="divider">
                <p class="section-title">💬 Alasan Penolakan</p>
                <div class="info-item full" style="background:#fff5f5;border-color:#fecaca;margin-bottom:0;">
                    <div class="info-item-value" style="font-size:14px;font-weight:500;color:#991b1b;">
                        {{ $borrowingRequest->rejection_reason }}
                    </div>
                </div>
            @endif

            {{-- Action buttons hanya tampil saat status pending --}}
            @if($status === \App\Models\BorrowingRequest::STATUS_PENDING)
                <hr class="divider">

                <div class="action-section">
                    <h2>⚖️ Ambil Keputusan</h2>
                    <p>
                        Pilih untuk menyetujui atau menolak pengajuan ini.
                        Notifikasi akan otomatis dikirim ke email siswa.
                    </p>

                    <div class="action-buttons">
                        {{-- Approve form --}}
                        <form
                            id="form-approve"
                            method="POST"
                            action="{{ route('approval.approve', array_merge(['borrowingRequest' => $borrowingRequest->id], request()->query())) }}"
                        >
                            @csrf
                            <button type="submit" class="btn btn-approve" id="btn-approve">
                                ✅ Setujui Pengajuan
                            </button>
                        </form>

                        {{-- Toggle reject form --}}
                        <button
                            type="button"
                            class="btn btn-reject-toggle"
                            id="btn-reject-toggle"
                            onclick="toggleRejectForm()"
                        >
                            ❌ Tolak Pengajuan
                        </button>
                    </div>

                    {{-- Reject form (hidden by default) --}}
                    <div class="reject-form" id="reject-form">
                        <form
                            method="POST"
                            action="{{ route('approval.reject', array_merge(['borrowingRequest' => $borrowingRequest->id], request()->query())) }}"
                        >
                            @csrf
                            <label for="rejection_reason">
                                Alasan Penolakan <span style="color:#dc2626">*</span>
                            </label>
                            <textarea
                                id="rejection_reason"
                                name="rejection_reason"
                                placeholder="Jelaskan alasan penolakan (minimal 10, maksimal 500 karakter)..."
                                maxlength="500"
                                oninput="updateCharCount(this)"
                            >{{ old('rejection_reason') }}</textarea>
                            <div class="char-count">
                                <span id="char-count">0</span>/500 karakter
                            </div>
                            <button type="submit" class="btn btn-reject-confirm">
                                ❌ Konfirmasi Penolakan
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <div class="footer-note">
                Halaman ini dibuka melalui link aman dari email SIPBAR.<br>
                Aksi yang Anda lakukan akan <strong>langsung berlaku</strong> dan tidak dapat dibatalkan.
            </div>
        </div>
    </div>

    <script>
        function toggleRejectForm() {
            const form = document.getElementById('reject-form');
            const toggle = document.getElementById('btn-reject-toggle');
            const isOpen = form.classList.contains('show');
            form.classList.toggle('show');
            toggle.textContent = isOpen ? '❌ Tolak Pengajuan' : '✖ Batal';
            if (!isOpen) {
                document.getElementById('rejection_reason').focus();
            }
        }

        function updateCharCount(el) {
            document.getElementById('char-count').textContent = el.value.length;
        }

        // Confirm sebelum submit approve
        document.getElementById('form-approve')?.addEventListener('submit', function(e) {
            if (!confirm('Anda yakin ingin menyetujui pengajuan ini? QR Code akan dikirim ke email siswa.')) {
                e.preventDefault();
            }
        });

        // Init char count jika ada old value
        const ta = document.getElementById('rejection_reason');
        if (ta && ta.value) {
            document.getElementById('char-count').textContent = ta.value.length;
            document.getElementById('reject-form').classList.add('show');
        }
    </script>
</body>
</html>
