<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Peminjaman Guru — SIPBAR</title>
    @include('partials.favicon')
    <meta name="description" content="Halaman konfirmasi persetujuan pengajuan peminjaman barang guru oleh Kepala Jurusan">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        :root {
            --red-50:   #fdf2f2;
            --red-100:  #fde8e8;
            --red-200:  #fbd5d5;
            --red-300:  #f8b4b4;
            --red-400:  #f98080;
            --red-600:  #b91c1c;
            --red-700:  #991b1b;
            --red-800:  #7f1d1d;
            --red-900:  #450a0a;
            --text:     #1f2937;
            --text-dark:#111827;
            --muted:    #4b5563;
            --subtle:   #6b7280;
            --border:   #fee2e2;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: #faf5f5;
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
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--red-600), var(--red-700));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(185, 28, 28, 0.25);
        }
        .brand-name {
            font-size: 16px;
            font-weight: 800;
            color: var(--red-700);
            letter-spacing: -0.3px;
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
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid var(--red-200);
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(185, 28, 28, 0.06);
        }

        .card-header {
            background: linear-gradient(135deg, #b91c1c 0%, #881337 100%);
            padding: 32px 36px;
        }
        .card-header-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.18);
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 999px;
            margin-bottom: 14px;
        }
        .card-header h1 {
            color: #ffffff;
            font-size: 23px;
            font-weight: 800;
            line-height: 1.3;
            margin-bottom: 6px;
        }
        .card-header p {
            color: rgba(255, 255, 255, 0.85);
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

        /* ─── Section Header ─── */
        .section-header {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 700;
            color: var(--red-600);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 16px;
        }
        .section-header-bar {
            width: 3px;
            height: 14px;
            background: var(--red-600);
            border-radius: 2px;
        }

        /* ─── Info grid ─── */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 24px;
        }

        .info-item {
            background: #faf5f5;
            border: 1px solid #fce8e8;
            border-radius: 12px;
            padding: 14px 16px;
        }
        .info-item.full {
            grid-column: 1 / -1;
        }
        .info-item.highlight {
            background: #fdf2f2;
            border-color: #fbd5d5;
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
            color: var(--text-dark);
            line-height: 1.35;
        }
        .info-item-sub {
            font-size: 12px;
            color: var(--muted);
            margin-top: 3px;
        }

        /* ─── Item list in approval ─── */
        .approval-items-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 8px;
        }
        .approval-item-card {
            background: #ffffff;
            border: 1px solid var(--red-200);
            border-radius: 12px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            box-shadow: 0 1px 3px rgba(185,28,28,0.03);
        }
        .approval-item-left {
            flex: 1;
            min-width: 0;
        }
        .approval-item-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
            flex-wrap: wrap;
        }
        .approval-badge-cat {
            display: inline-block;
            font-size: 10.5px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 6px;
            background: var(--red-100);
            color: var(--red-700);
            letter-spacing: .02em;
        }
        .approval-item-code {
            font-size: 11px;
            color: var(--subtle);
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        }
        .approval-item-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1.35;
        }
        .approval-qty-badge {
            background: var(--red-50);
            border: 1px solid var(--red-200);
            color: var(--red-700);
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 12.5px;
            font-weight: 700;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 24px 0;
        }

        /* ─── Action buttons ─── */
        .action-section h2 {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
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
            background: linear-gradient(135deg, #b91c1c, #991b1b);
            color: #ffffff;
            flex: 1;
            box-shadow: 0 4px 14px rgba(185, 28, 28, 0.3);
        }
        .btn-approve:hover {
            filter: brightness(1.1);
            box-shadow: 0 6px 18px rgba(185, 28, 28, 0.4);
        }

        .btn-reject-toggle {
            background: #ffffff;
            color: #b91c1c;
            border: 1.5px solid #f87171;
            flex: 1;
        }
        .btn-reject-toggle:hover {
            background: #fef2f2;
        }

        /* ─── Reject form ─── */
        .reject-form {
            display: none;
            background: #fdf2f2;
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
            color: var(--text-dark);
            background: #ffffff;
            resize: vertical;
            outline: none;
            transition: border-color 0.15s;
            line-height: 1.6;
        }
        .reject-form textarea:focus {
            border-color: #b91c1c;
            box-shadow: 0 0 0 3px rgba(185, 28, 28, 0.15);
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
            background: linear-gradient(135deg, #b91c1c, #7f1d1d);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(185, 28, 28, 0.25);
            width: 100%;
        }
        .btn-reject-confirm:hover { filter: brightness(1.1); }

        /* ─── Footer note ─── */
        .footer-note {
            text-align: center;
            font-size: 12px;
            color: var(--subtle);
            line-height: 1.6;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }
        .footer-note strong { color: var(--red-700); }

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
            <div class="brand-icon">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div>
                <div class="brand-name">SIPBAR</div>
                <div class="brand-sub">Panel Persetujuan Kepala Jurusan</div>
            </div>
        </div>
    </div>

    <!-- Main card -->
    <div class="card">

        <!-- Header -->
        <div class="card-header">
            <div class="card-header-badge">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Persetujuan Kepala Jurusan
            </div>
            <h1>Konfirmasi Pengajuan Peminjaman Guru</h1>
            <p>Tinjau detail permohonan peminjaman barang oleh guru dan tentukan keputusan persetujuan</p>
        </div>

        @php
            $status = $borrowingRequest->status;
            $item = $borrowingRequest->itemWithTrashed ?? $borrowingRequest->item;
            $guru = $borrowingRequest->user;
            $kajur = $borrowingRequest->approvedByKajur;
        @endphp

        <!-- Status banner if already processed -->
        @if($status === \App\Models\BorrowingRequest::STATUS_APPROVED)
            <div class="status-banner approved">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <span>Pengajuan peminjaman guru ini telah <strong>disetujui</strong>.</span>
            </div>
        @elseif($status === \App\Models\BorrowingRequest::STATUS_REJECTED)
            <div class="status-banner rejected">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>Pengajuan peminjaman guru ini telah <strong>ditolak</strong>.</span>
            </div>
        @elseif($status !== \App\Models\BorrowingRequest::STATUS_PENDING)
            <div class="status-banner pending">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Status pengajuan saat ini: <strong>{{ $borrowingRequest->status_label }}</strong></span>
            </div>
        @endif

        <!-- Body -->
        <div class="card-body">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="alert alert-success">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-error">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- ─── Section 1: Profil Pengaju & Penerima ─── -->
            <div class="section-header">
                <div class="section-header-bar"></div>
                Informasi Pemohon & Persetujuan
            </div>
            <div class="info-grid">
                <div class="info-item highlight">
                    <div class="info-item-label">Nama Guru (Pemohon)</div>
                    <div class="info-item-value">{{ $guru?->name ?? 'Guru' }}</div>
                    <div class="info-item-sub">
                        {{ $guru?->jurusan?->nama ? 'Jurusan: ' . $guru->jurusan->nama : 'Guru Pengajar' }}
                        @if($guru?->nip) · NIP: {{ $guru->nip }} @endif
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-item-label">Kepala Jurusan yang Dituju</div>
                    <div class="info-item-value">{{ $kajur?->name ?? 'Kepala Jurusan' }}</div>
                    <div class="info-item-sub">
                        {{ $kajur?->jurusan?->nama ?? 'Jurusan Terkait' }}
                    </div>
                </div>
            </div>

            <!-- ─── Section 2: Detail Barang & Peminjaman ─── -->
            <div class="section-header">
                <div class="section-header-bar"></div>
                Detail Barang & Waktu Peminjaman
            </div>
            <div class="info-grid">
                {{-- Daftar Barang Lengkap (Multi-Item & Single Item Fallback) --}}
                <div class="info-item full" style="background:#fdf2f2;border-color:var(--red-200);">
                    @php
                        $borrowItems = collect();
                        if ($borrowingRequest->items && $borrowingRequest->items->isNotEmpty()) {
                            $borrowItems = $borrowingRequest->items;
                        } elseif ($borrowingRequest->item || $borrowingRequest->itemWithTrashed) {
                            $borrowItems = collect([(object)[
                                'item' => $borrowingRequest->itemWithTrashed ?? $borrowingRequest->item,
                                'quantity' => $borrowingRequest->quantity ?? 1
                            ]]);
                        }
                        $totalUnits = $borrowItems->sum('quantity');
                    @endphp

                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
                        <div class="info-item-label" style="margin-bottom:0;">
                            Daftar Barang yang Diajukan ({{ $borrowItems->count() }} Jenis, {{ $totalUnits }} Total Unit)
                        </div>
                    </div>

                    @if($borrowItems->isEmpty())
                        <div style="padding:14px;border-radius:8px;background:#fff;border:1px dashed var(--red-200);text-align:center;font-size:13px;color:var(--subtle);">
                            Tidak ada rincian barang ditemukan.
                        </div>
                    @else
                        <div class="approval-items-list">
                            @foreach($borrowItems as $entry)
                                @php
                                    $itemModel = $entry->item ?? null;
                                    $rawName = $itemModel?->name ?? 'Barang tidak ditemukan';
                                    $categoryBadge = $itemModel?->category?->name ?? '';
                                    $cleanName = $rawName;

                                    if (strpos($rawName, '.') !== false) {
                                        $parts = explode('.', $rawName);
                                        if (count($parts) >= 2) {
                                            if (empty($categoryBadge)) {
                                                $categoryBadge = trim($parts[0]);
                                            }
                                            $cleanName = trim($parts[count($parts) - 1]);
                                            $cleanName = preg_replace('/\s*-\s*\[.*?\]\s*$/', '', $cleanName);
                                            $cleanName = trim($cleanName);
                                        }
                                    }

                                    $displayTitle = \Illuminate\Support\Str::title(mb_strtolower($cleanName));
                                    if ($categoryBadge) {
                                        $categoryBadge = \Illuminate\Support\Str::title(mb_strtolower($categoryBadge));
                                    }
                                    $itemCode = $itemModel?->kode_barang ?? $itemModel?->code ?? '-';
                                @endphp
                                <div class="approval-item-card">
                                    <div class="approval-item-left">
                                        <div class="approval-item-meta">
                                            @if($categoryBadge)
                                                <span class="approval-badge-cat">{{ $categoryBadge }}</span>
                                            @endif
                                            @if($itemCode && $itemCode !== '-')
                                                <span class="approval-item-code">Kode: {{ $itemCode }}</span>
                                            @endif
                                        </div>
                                        <div class="approval-item-name">{{ $displayTitle }}</div>
                                    </div>
                                    <div class="approval-qty-badge">
                                        {{ (int)($entry->quantity ?? 1) }} unit
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="info-item">
                    <div class="info-item-label">Tanggal Pinjam</div>
                    <div class="info-item-value">{{ $borrowingRequest->borrow_date?->format('d M Y') ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-item-label">Tanggal & Jam Kembali</div>
                    <div class="info-item-value">
                        {{ $borrowingRequest->return_date?->format('d M Y') ?? '-' }}
                        @if($borrowingRequest->return_time)
                            <span style="font-size:13px;font-weight:600;color:var(--red-700)">({{ $borrowingRequest->return_time }})</span>
                        @endif
                    </div>
                    <div class="info-item-sub">
                        @if($borrowingRequest->borrow_date && $borrowingRequest->return_date)
                            @php
                                $days = $borrowingRequest->borrow_date->diffInDays($borrowingRequest->return_date);
                            @endphp
                            Durasi: {{ $days > 0 ? $days . ' hari' : 'Hari yang sama' }}
                        @endif
                    </div>
                </div>

                <div class="info-item full">
                    <div class="info-item-label">Tujuan / Keperluan Peminjaman</div>
                    <div class="info-item-value" style="font-size:14px;font-weight:500;line-height:1.5;">
                        {{ $borrowingRequest->purpose }}
                    </div>
                </div>

                @if($borrowingRequest->notes)
                <div class="info-item full">
                    <div class="info-item-label">Catatan Tambahan</div>
                    <div class="info-item-value" style="font-size:14px;font-weight:400;color:var(--muted);line-height:1.5;">
                        {{ $borrowingRequest->notes }}
                    </div>
                </div>
                @endif
            </div>

            {{-- Rejection reason (if already rejected) --}}
            @if($borrowingRequest->rejection_reason)
                <hr class="divider">
                <div class="section-header" style="color:#b91c1c;">
                    <div class="section-header-bar" style="background:#b91c1c;"></div>
                    Alasan Penolakan
                </div>
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
                    <h2>
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:var(--red-600)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Ambil Keputusan Persetujuan
                    </h2>
                    <p>
                        Pilih untuk menyetujui atau menolak pengajuan peminjaman barang ini.
                        Status permohonan guru akan langsung terupdate setelah keputusan disimpan.
                    </p>

                    <div class="action-buttons">
                        {{-- Approve form --}}
                        <form
                            id="form-approve-guru"
                            method="POST"
                            action="{{ route('approval-guru.approve', array_merge(['borrowingRequest' => $borrowingRequest->id], request()->query())) }}"
                            style="flex:1"
                        >
                            @csrf
                            <button type="submit" class="btn btn-approve" id="btn-approve" style="width:100%">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                Setujui Pengajuan
                            </button>
                        </form>

                        {{-- Toggle reject form --}}
                        <button
                            type="button"
                            class="btn btn-reject-toggle"
                            id="btn-reject-toggle"
                            onclick="toggleRejectForm()"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span id="reject-btn-text">Tolak Pengajuan</span>
                        </button>
                    </div>

                    {{-- Reject form (hidden by default) --}}
                    <div class="reject-form" id="reject-form">
                        <form
                            method="POST"
                            action="{{ route('approval-guru.reject', array_merge(['borrowingRequest' => $borrowingRequest->id], request()->query())) }}"
                        >
                            @csrf
                            <label for="rejection_reason">
                                Alasan Penolakan <span style="color:#b91c1c">*</span>
                            </label>
                            <textarea
                                id="rejection_reason"
                                name="rejection_reason"
                                placeholder="Jelaskan alasan penolakan permohonan peminjaman guru ini (minimal 10, maksimal 500 karakter)..."
                                maxlength="500"
                                oninput="updateCharCount(this)"
                            >{{ old('rejection_reason') }}</textarea>
                            <div class="char-count">
                                <span id="char-count">0</span>/500 karakter
                            </div>
                            <button type="submit" class="btn btn-reject-confirm">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                Konfirmasi Penolakan
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <div class="footer-note">
                Halaman ini dibuka melalui link verifikasi WhatsApp pengajuan guru SIPBAR.<br>
                Keputusan yang Anda ambil oleh <strong>Kepala Jurusan</strong> akan langsung tersimpan ke sistem.
            </div>
        </div>
    </div>

    <script>
        function toggleRejectForm() {
            const form = document.getElementById('reject-form');
            const btnText = document.getElementById('reject-btn-text');
            const isOpen = form.classList.contains('show');
            form.classList.toggle('show');
            if (btnText) {
                btnText.textContent = isOpen ? 'Tolak Pengajuan' : 'Batal';
            }
            if (!isOpen) {
                document.getElementById('rejection_reason').focus();
            }
        }

        function updateCharCount(el) {
            document.getElementById('char-count').textContent = el.value.length;
        }

        // Confirm sebelum submit approve
        document.getElementById('form-approve-guru')?.addEventListener('submit', function(e) {
            if (!confirm('Anda yakin ingin menyetujui pengajuan peminjaman guru ini? QR Code peminjaman akan otomatis diaktifkan.')) {
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
