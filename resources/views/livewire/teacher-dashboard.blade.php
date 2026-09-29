<div>
    <style>
        :root {
            --gd-text-primary: #0f172a;
            --gd-text-secondary: #1e293b;
            --gd-text-muted: #475569;
            --gd-text-subtle: #64748b;
            --gd-bg-card: #ffffff;
            --gd-bg-card-subtle: #f8fafc;
            --gd-border-subtle: #e2e8f0;
            --gd-border-alt: #cbd5e1;
            --gd-card-shadow: 0 1px 3px rgba(0,0,0,.04), 0 4px 14px rgba(0,0,0,.02);
            --gd-card-shadow-hover: 0 10px 25px -5px rgba(5, 150, 105, 0.1), 0 8px 10px -6px rgba(5, 150, 105, 0.06);
            --gd-emerald-main: #059669;
            --gd-emerald-light: #ecfdf5;
            --gd-emerald-border: #a7f3d0;
            --gd-amber-bg: #fffbeb;
            --gd-amber-border: #fde68a;
            --gd-amber-text: #92400e;
        }

        html.dark {
            --gd-text-primary: #f0fdf4;
            --gd-text-secondary: #dcfce7;
            --gd-text-muted: #86efac;
            --gd-text-subtle: #6ee7b7;
            --gd-bg-card: #0f201d;
            --gd-bg-card-subtle: #091210;
            --gd-border-subtle: #1d3d37;
            --gd-border-alt: #162e2a;
            --gd-card-shadow: 0 4px 16px rgba(0,0,0,.35);
            --gd-card-shadow-hover: 0 10px 25px -5px rgba(16, 185, 129, 0.2), 0 8px 10px -6px rgba(16, 185, 129, 0.1);
            --gd-emerald-main: #10b981;
            --gd-emerald-light: rgba(16,185,129,.15);
            --gd-emerald-border: rgba(16,185,129,.3);
            --gd-amber-bg: rgba(245, 158, 11, 0.12);
            --gd-amber-border: rgba(245, 158, 11, 0.35);
            --gd-amber-text: #fbbf24;
        }

        /* Greeting Header */
        .gd-greeting-card {
            background: var(--gd-bg-card);
            border: 1.5px solid var(--gd-border-subtle);
            border-radius: 18px;
            padding: 22px 24px;
            margin-bottom: 24px;
            box-shadow: var(--gd-card-shadow);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
            position: relative;
            overflow: hidden;
        }
        .gd-greeting-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(180deg, var(--gd-emerald-main), #0284c7);
        }
        .gd-greeting-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .gd-avatar-badge {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--gd-emerald-main), #047857);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 800;
            box-shadow: 0 4px 12px rgba(5, 150, 105, .25);
            flex-shrink: 0;
        }
        .gd-greeting-title {
            font-size: 22px;
            font-weight: 800;
            color: var(--gd-text-primary);
            line-height: 1.25;
            letter-spacing: -.02em;
            margin-bottom: 4px;
        }
        .gd-greeting-sub {
            font-size: 13px;
            color: var(--gd-text-muted);
            font-weight: 500;
        }
        .gd-greeting-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 12px;
            font-size: 12.5px;
            font-weight: 700;
            text-decoration: none;
            transition: all .2s ease;
        }
        .gd-greeting-pill-pending {
            background: #fef3c7;
            color: #b45309;
            border: 1.5px solid #fde68a;
            animation: gd-pulse-border 2s infinite ease-in-out;
        }
        html.dark .gd-greeting-pill-pending {
            background: rgba(245, 158, 11, 0.18);
            color: #fcd34d;
            border-color: rgba(245, 158, 11, 0.4);
        }
        .gd-greeting-pill-pending:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(217, 119, 6, .2);
        }
        .gd-greeting-pill-allgood {
            background: var(--gd-emerald-light);
            color: var(--gd-emerald-main);
            border: 1.5px solid var(--gd-emerald-border);
        }

        @keyframes gd-pulse-border {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }

        /* Section Header */
        .gd-section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }
        .gd-section-title-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .gd-section-indicator {
            width: 4px;
            height: 16px;
            background: var(--gd-emerald-main);
            border-radius: 2px;
        }
        .gd-section-title {
            font-size: 15px;
            font-weight: 800;
            color: var(--gd-text-primary);
            letter-spacing: -.01em;
            margin: 0;
        }
        .gd-section-tag {
            font-size: 11px;
            font-weight: 700;
            color: var(--gd-emerald-main);
            background: var(--gd-emerald-light);
            padding: 3px 9px;
            border-radius: 999px;
            border: 1px solid var(--gd-emerald-border);
        }

        /* Statistics Grid - 4 col desktop, 2 col mobile */
        .gd-stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 24px;
        }
        @media (max-width: 1024px) {
            .gd-stat-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 640px) {
            .gd-stat-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
        }

        .gd-stat-card {
            background: var(--gd-bg-card);
            border: 1.5px solid var(--gd-border-subtle);
            border-radius: 16px;
            padding: 18px 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 14px;
            transition: all .2s ease;
            box-shadow: var(--gd-card-shadow);
        }
        @media (max-width: 640px) {
            .gd-stat-card { padding: 14px 14px; gap: 10px; border-radius: 14px; }
        }
        .gd-stat-card:hover {
            border-color: var(--gd-emerald-main);
            transform: translateY(-2px);
            box-shadow: var(--gd-card-shadow-hover);
        }
        .gd-stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
        }
        .gd-stat-icon-box {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        @media (max-width: 640px) {
            .gd-stat-icon-box { width: 36px; height: 36px; border-radius: 10px; }
            .gd-stat-icon-box svg { width: 18px !important; height: 18px !important; }
        }
        .gd-stat-pill {
            font-size: 11px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 999px;
            white-space: nowrap;
        }
        @media (max-width: 640px) {
            .gd-stat-pill { font-size: 9.5px; padding: 2px 6px; }
        }
        .gd-stat-num {
            font-size: 26px;
            font-weight: 800;
            color: var(--gd-text-primary);
            line-height: 1;
            margin-bottom: 4px;
            letter-spacing: -.02em;
        }
        @media (max-width: 640px) {
            .gd-stat-num { font-size: 22px; margin-bottom: 2px; }
        }
        .gd-stat-label {
            font-size: 13px;
            color: var(--gd-text-muted);
            font-weight: 600;
            line-height: 1.3;
        }
        @media (max-width: 640px) {
            .gd-stat-label { font-size: 11.5px; }
        }

        /* Deadline Banner Alert */
        .gd-alert-deadline {
            background: var(--gd-amber-bg);
            border: 1.5px solid var(--gd-amber-border);
            border-radius: 16px;
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .gd-alert-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }
        .gd-alert-title-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .gd-alert-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(245, 158, 11, 0.2);
            color: #d97706;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        html.dark .gd-alert-icon {
            color: #fbbf24;
        }
        .gd-alert-title {
            font-size: 14px;
            font-weight: 800;
            color: var(--gd-amber-text);
        }
        .gd-alert-link {
            font-size: 12px;
            font-weight: 700;
            color: #d97706;
            text-decoration: underline;
            text-underline-offset: 3px;
        }
        html.dark .gd-alert-link {
            color: #fbbf24;
        }
        .gd-deadline-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 10px;
        }
        .gd-deadline-item {
            background: var(--gd-bg-card);
            border: 1px solid var(--gd-amber-border);
            border-radius: 10px;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }
        .gd-deadline-item-name {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--gd-text-primary);
        }
        .gd-deadline-item-user {
            font-size: 11px;
            color: var(--gd-text-muted);
        }
        .gd-deadline-badge {
            font-size: 10.5px;
            font-weight: 800;
            padding: 3px 8px;
            border-radius: 6px;
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
            white-space: nowrap;
        }
        html.dark .gd-deadline-badge {
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            border-color: rgba(239, 68, 68, 0.4);
        }

        /* Activity Panels - 2 col desktop, 1 col mobile */
        .gd-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            margin-bottom: 28px;
        }
        @media (max-width: 960px) {
            .gd-grid-2 { grid-template-columns: 1fr; }
        }

        .gd-panel {
            background: var(--gd-bg-card);
            border: 1.5px solid var(--gd-border-subtle);
            border-radius: 18px;
            padding: 20px;
            box-shadow: var(--gd-card-shadow);
            display: flex;
            flex-direction: column;
        }
        .gd-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            border-bottom: 1px solid var(--gd-border-subtle);
            padding-bottom: 12px;
        }
        .gd-panel-title {
            font-size: 15px;
            font-weight: 800;
            color: var(--gd-text-primary);
            letter-spacing: -.01em;
        }
        .gd-panel-sub {
            font-size: 12px;
            color: var(--gd-text-muted);
            margin-top: 2px;
            font-weight: 500;
        }

        .gd-txn-item {
            background: var(--gd-bg-card-subtle);
            border: 1px solid var(--gd-border-subtle);
            border-radius: 12px;
            padding: 13px 15px;
            margin-bottom: 10px;
            transition: all .15s ease;
        }
        .gd-txn-item:last-child { margin-bottom: 0; }
        .gd-txn-item:hover {
            border-color: var(--gd-emerald-main);
            transform: translateX(2px);
        }
        .gd-txn-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 8px;
            gap: 10px;
        }
        .gd-txn-name {
            font-size: 13.5px;
            font-weight: 800;
            color: var(--gd-text-primary);
            line-height: 1.3;
        }
        .gd-txn-type {
            font-size: 11.5px;
            color: var(--gd-text-muted);
            margin-top: 2px;
        }
        .gd-txn-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 11.5px;
            color: var(--gd-text-subtle);
            padding-top: 6px;
            border-top: 1px dashed var(--gd-border-subtle);
            flex-wrap: wrap;
            gap: 6px;
        }

        .gd-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 9px;
            border-radius: 8px;
            font-size: 10.5px;
            font-weight: 800;
            letter-spacing: .02em;
            white-space: nowrap;
        }
        .gd-badge-pending {
            background: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
        }
        html.dark .gd-badge-pending {
            background: rgba(245, 158, 11, 0.2);
            color: #fbbf24;
            border-color: rgba(245, 158, 11, 0.35);
        }
        .gd-badge-approved {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }
        html.dark .gd-badge-approved {
            background: rgba(37, 99, 235, 0.2);
            color: #93c5fd;
            border-color: rgba(37, 99, 235, 0.35);
        }
        .gd-badge-borrowed {
            background: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }
        html.dark .gd-badge-borrowed {
            background: rgba(2, 132, 199, 0.2);
            color: #7dd3fc;
            border-color: rgba(2, 132, 199, 0.35);
        }
        .gd-badge-returned {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }
        html.dark .gd-badge-returned {
            background: rgba(16, 185, 129, 0.2);
            color: #86efac;
            border-color: rgba(16, 185, 129, 0.35);
        }
        .gd-badge-rejected {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
        }
        html.dark .gd-badge-rejected {
            background: rgba(220, 38, 38, 0.2);
            color: #fca5a5;
            border-color: rgba(220, 38, 38, 0.35);
        }

        .gd-empty {
            text-align: center;
            padding: 36px 16px;
            color: var(--gd-text-muted);
        }
        .gd-empty-icon {
            width: 42px;
            height: 42px;
            margin: 0 auto 10px;
            color: var(--gd-text-subtle);
            opacity: .6;
        }
        .gd-empty-title {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--gd-text-primary);
            margin-bottom: 3px;
        }
        .gd-empty-sub {
            font-size: 12px;
            color: var(--gd-text-muted);
        }

        /* Quick Action Menu Grid (Bawah) - 3 col desktop, 2 col mobile */
        .gd-menu-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
        }
        @media (max-width: 1024px) {
            .gd-menu-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 640px) {
            .gd-menu-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
        }

        .gd-menu-card {
            background: var(--gd-bg-card);
            border: 1.5px solid var(--gd-border-subtle);
            border-radius: 16px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            text-decoration: none;
            transition: all .22s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--gd-card-shadow);
            position: relative;
            overflow: hidden;
        }
        @media (max-width: 640px) {
            .gd-menu-card { padding: 12px; border-radius: 14px; gap: 6px; }
        }
        .gd-menu-card:hover {
            border-color: var(--gd-emerald-main);
            transform: translateY(-3px);
            box-shadow: var(--gd-card-shadow-hover);
        }

        /* Highlight khusus Permohonan Peminjaman */
        .gd-menu-card.gd-card-priority {
            border: 2px solid #10b981;
        }
        .gd-menu-card.gd-card-priority::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #10b981, #059669);
        }
        .gd-menu-card.gd-card-priority:hover {
            border-color: #059669;
            box-shadow: 0 10px 24px rgba(16, 185, 129, .22);
        }

        .gd-menu-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: transform .2s ease;
        }
        @media (max-width: 640px) {
            .gd-menu-icon { width: 34px; height: 34px; border-radius: 9px; }
            .gd-menu-icon svg { width: 18px !important; height: 18px !important; }
        }
        .gd-menu-card:hover .gd-menu-icon {
            transform: scale(1.08);
        }
        .gd-menu-title {
            font-size: 14px;
            font-weight: 800;
            color: var(--gd-text-primary);
            line-height: 1.3;
            letter-spacing: -.01em;
        }
        @media (max-width: 640px) {
            .gd-menu-title { font-size: 12.5px; }
        }
        .gd-menu-desc {
            font-size: 12px;
            color: var(--gd-text-muted);
            line-height: 1.45;
            font-weight: 400;
        }
        @media (max-width: 640px) {
            .gd-menu-desc { display: none; }
        }
        .gd-menu-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 10.5px;
            font-weight: 700;
            margin-top: 2px;
            align-self: flex-start;
            white-space: nowrap;
        }
        @media (max-width: 640px) {
            .gd-menu-badge { font-size: 9.5px; padding: 2px 6px; }
        }
        .gd-badge-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: currentColor;
        }
    </style>

    {{-- 1. Greeting & User Header Card --}}
    <div class="gd-greeting-card">
        <div class="gd-greeting-left">
            <div class="gd-avatar-badge">
                {{ strtoupper(substr(auth()->check() ? auth()->user()->name : 'G', 0, 1)) }}
            </div>
            <div>
                <div class="gd-greeting-title">
                    @php
                        $hour = now()->hour;
                        $greet = $hour < 12 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
                    @endphp
                    {{ $greet }}, {{ auth()->check() ? auth()->user()->name : 'Guru' }}
                </div>
                <div class="gd-greeting-sub">{{ now()->translatedFormat('l, d F Y') }} • Portal Peminjaman Barang Guru Penanggung Jawab</div>
            </div>
        </div>

        <div>
            @if(isset($pendingRequests) && $pendingRequests > 0)
                <a href="{{ route('teacher.requests') }}" class="gd-greeting-pill gd-greeting-pill-pending">
                    <span style="font-size:14px">⚡</span>
                    <span>{{ $pendingRequests }} Permohonan Menunggu</span>
                </a>
            @else
                <div class="gd-greeting-pill gd-greeting-pill-allgood">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Status Sirkulasi Normal</span>
                </div>
            @endif
        </div>
    </div>

    {{-- 2. Statistics Grid (Desktop 4 col, Mobile 2 col) --}}
    <div style="margin-bottom:24px">
        <div class="gd-section-head">
            <div class="gd-section-title-wrap">
                <span class="gd-section-indicator"></span>
                <h2 class="gd-section-title">Ringkasan Ketersediaan & Peminjaman</h2>
            </div>
            <span class="gd-section-tag">Data Real-time</span>
        </div>
        <div class="gd-stat-grid">
            {{-- Barang Tersedia --}}
            <div class="gd-stat-card">
                <div class="gd-stat-top">
                    <div class="gd-stat-icon-box" style="background:rgba(16,185,129,.12)">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#10b981" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="gd-stat-pill" style="background:#ecfdf5;color:#059669">Siap Pakai</span>
                </div>
                <div>
                    <div class="gd-stat-num">{{ $availableItems }}</div>
                    <div class="gd-stat-label">Barang Tersedia</div>
                </div>
            </div>

            {{-- Peminjaman Saya --}}
            <div class="gd-stat-card">
                <div class="gd-stat-top">
                    <div class="gd-stat-icon-box" style="background:rgba(2,132,199,.12)">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#0284c7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    <span class="gd-stat-pill" style="background:#f0f9ff;color:#0284c7">Aktif Guru</span>
                </div>
                <div>
                    <div class="gd-stat-num">{{ $totalBorrowed }}</div>
                    <div class="gd-stat-label">Peminjaman Saya</div>
                </div>
            </div>

            {{-- Selesai / Dikembalikan --}}
            <div class="gd-stat-card">
                <div class="gd-stat-top">
                    <div class="gd-stat-icon-box" style="background:rgba(5,150,105,.12)">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#059669" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="gd-stat-pill" style="background:#ecfdf5;color:#047857">Telah Kembali</span>
                </div>
                <div>
                    <div class="gd-stat-num">{{ $totalReturned }}</div>
                    <div class="gd-stat-label">Barang Selesai</div>
                </div>
            </div>

            {{-- Total Katalog Barang --}}
            <div class="gd-stat-card">
                <div class="gd-stat-top">
                    <div class="gd-stat-icon-box" style="background:rgba(99,102,241,.12)">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#6366f1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="gd-stat-pill" style="background:#eef2ff;color:#4338ca">Katalog</span>
                </div>
                <div>
                    <div class="gd-stat-num">{{ $departmentItems }}</div>
                    <div class="gd-stat-label">Total Item Terdata</div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Deadline Alert (Hanya muncul jika ada deadline pinjaman mendekati batas waktu <= 3 hari) --}}
    @if(isset($upcomingDeadlines) && $upcomingDeadlines->count() > 0)
    <div class="gd-alert-deadline">
        <div class="gd-alert-head">
            <div class="gd-alert-title-wrap">
                <div class="gd-alert-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <div class="gd-alert-title">Perhatian: Jatuh Tempo Pengembalian Segera (≤ 3 Hari)</div>
                    <div style="font-size:12px;color:var(--gd-text-muted)">Terdapat {{ $upcomingDeadlines->count() }} transaksi peminjaman aktif yang mendekati batas waktu.</div>
                </div>
            </div>
            <a href="{{ route('teacher.loans') }}" class="gd-alert-link">Lihat Semua Peminjaman →</a>
        </div>
        <div class="gd-deadline-list">
            @foreach($upcomingDeadlines as $deadline)
            <div class="gd-deadline-item">
                <div>
                    <div class="gd-deadline-item-name">{{ $deadline->details?->first()?->item?->name ?? 'Barang Pinjaman' }}</div>
                    <div class="gd-deadline-item-user">Peminjam: {{ $deadline->user->name ?? 'Siswa/Guru' }}</div>
                </div>
                <div class="gd-deadline-badge">
                    @php
                        $dueDate = $deadline->due_at ?? $deadline->return_date;
                    @endphp
                    Batas: {{ $dueDate ? $dueDate->format('d M') : '-' }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- 4. Activity Logs (2 Kolom: Sirkulasi Sekolah & Peminjaman Saya) --}}
    <div style="margin-bottom:24px">
        <div class="gd-section-head">
            <div class="gd-section-title-wrap">
                <span class="gd-section-indicator"></span>
                <h2 class="gd-section-title">Aktivitas & Log Sirkulasi</h2>
            </div>
            <span class="gd-section-tag">Transaksi Terbaru</span>
        </div>
        <div class="gd-grid-2">
            {{-- Sirkulasi Sekolah / Siswa --}}
            <div class="gd-panel">
                <div class="gd-panel-header">
                    <div>
                        <div class="gd-panel-title">Sirkulasi Sekolah</div>
                        <div class="gd-panel-sub">Aktivitas peminjaman terbaru di lingkungan sekolah</div>
                    </div>
                </div>

                @if($recentDepartmentBorrowings && $recentDepartmentBorrowings->count() > 0)
                    @foreach($recentDepartmentBorrowings as $borrowing)
                    <div class="gd-txn-item">
                        <div class="gd-txn-top">
                            <div>
                                <div class="gd-txn-name">{{ $borrowing->details?->first()?->item?->name ?? 'Barang Pinjaman' }}</div>
                                <div class="gd-txn-type">Diajukan oleh: <strong style="color:var(--gd-text-primary)">{{ $borrowing->user->name ?? 'Siswa' }}</strong></div>
                            </div>
                            <span class="gd-badge {{ $borrowing->status === 'borrowed' ? 'gd-badge-borrowed' : ($borrowing->status === 'returned' ? 'gd-badge-returned' : ($borrowing->status === 'approved' ? 'gd-badge-approved' : ($borrowing->status === 'rejected' ? 'gd-badge-rejected' : 'gd-badge-pending'))) }}">
                                {{ $borrowing->status === 'borrowed' ? 'DIPINJAM' : ($borrowing->status === 'returned' ? 'KEMBALI' : ($borrowing->status === 'approved' ? 'DISETUJUI' : ($borrowing->status === 'rejected' ? 'DITOLAK' : 'MENUNGGU'))) }}
                            </span>
                        </div>
                        <div class="gd-txn-meta">
                            <div style="display:flex;align-items:center;gap:5px;color:var(--gd-text-muted)">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $borrowing->borrowed_at?->diffForHumans() ?? $borrowing->created_at?->diffForHumans() ?? 'Baru saja' }}
                            </div>
                            <div>
                                @php
                                    $retDate = $borrowing->due_at ?? $borrowing->return_date;
                                @endphp
                                @if($retDate)
                                    Batas: {{ $retDate->format('d M Y') }}
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="gd-empty">
                        <svg xmlns="http://www.w3.org/2000/svg" class="gd-empty-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <div class="gd-empty-title">Belum ada aktivitas baru</div>
                        <div class="gd-empty-sub">Menunggu transaksi peminjaman siswa</div>
                    </div>
                @endif
            </div>

            {{-- Peminjaman Saya --}}
            <div class="gd-panel">
                <div class="gd-panel-header">
                    <div>
                        <div class="gd-panel-title">Peminjaman Saya</div>
                        <div class="gd-panel-sub">Daftar transaksi barang yang Anda ajukan</div>
                    </div>
                </div>

                @if($myBorrowings && $myBorrowings->count() > 0)
                    @foreach($myBorrowings as $borrowing)
                    <div class="gd-txn-item">
                        <div class="gd-txn-top">
                            <div>
                                <div class="gd-txn-name">{{ $borrowing->details?->first()?->item?->name ?? 'Barang Pinjaman' }}</div>
                                <div class="gd-txn-type">Kode: #{{ $borrowing->number ?? $borrowing->id }}</div>
                            </div>
                            <span class="gd-badge {{ $borrowing->status === 'borrowed' ? 'gd-badge-borrowed' : ($borrowing->status === 'returned' ? 'gd-badge-returned' : ($borrowing->status === 'approved' ? 'gd-badge-approved' : ($borrowing->status === 'rejected' ? 'gd-badge-rejected' : 'gd-badge-pending'))) }}">
                                {{ $borrowing->status === 'borrowed' ? 'DIPINJAM' : ($borrowing->status === 'returned' ? 'KEMBALI' : ($borrowing->status === 'approved' ? 'DISETUJUI' : ($borrowing->status === 'rejected' ? 'DITOLAK' : 'MENUNGGU'))) }}
                            </span>
                        </div>
                        <div class="gd-txn-meta">
                            <div style="display:flex;align-items:center;gap:5px;color:var(--gd-text-muted)">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $borrowing->borrowed_at?->diffForHumans() ?? $borrowing->created_at?->diffForHumans() ?? 'Baru saja' }}
                            </div>
                            <div>
                                @php
                                    $myRetDate = $borrowing->due_at ?? $borrowing->return_date;
                                @endphp
                                @if($myRetDate)
                                    Batas: {{ $myRetDate->format('d M Y') }} @if($borrowing->return_time) · {{ $borrowing->return_time }}@endif
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="gd-empty">
                        <svg xmlns="http://www.w3.org/2000/svg" class="gd-empty-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <div class="gd-empty-title">Belum ada peminjaman aktif</div>
                        <div class="gd-empty-sub">Riwayat pengajuan barang Anda akan muncul di sini</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- 5. Quick Actions Menu (Diposisikan di Bawah dengan Grid Kompak: Desktop 3 col, Mobile 2 col) --}}
    <div style="margin-bottom:20px">
        <div class="gd-section-head">
            <div class="gd-section-title-wrap">
                <span class="gd-section-indicator"></span>
                <h2 class="gd-section-title">Akses Cepat & Navigasi</h2>
            </div>
            <span class="gd-section-tag">Menu Utama</span>
        </div>
        <div class="gd-menu-grid">
            {{-- Permohonan Peminjaman (PRIORITY HIGHLIGHT) --}}
            <a href="{{ route('teacher.requests') }}" class="gd-menu-card gd-card-priority">
                <div class="gd-menu-icon" style="background:rgba(16,185,129,.14);color:#059669">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="gd-menu-title">Permohonan Siswa</div>
                <div class="gd-menu-desc">Kelola dan setujui pengajuan siswa bimbingan</div>
                @if(isset($pendingRequests) && $pendingRequests > 0)
                <div class="gd-menu-badge" style="background:#fef3c7;color:#b45309;border:1px solid #fde68a">
                    <span class="gd-badge-dot"></span>
                    <span>{{ $pendingRequests }} Menunggu</span>
                </div>
                @else
                <div class="gd-menu-badge" style="background:#ecfdf5;color:#059669;border:1px solid #a7f3d0">
                    <span class="gd-badge-dot"></span>
                    <span>Semua Bersih</span>
                </div>
                @endif
            </a>

            {{-- Siswa Bimbingan --}}
            <a href="{{ route('teacher.students') }}" class="gd-menu-card">
                <div class="gd-menu-icon" style="background:rgba(37,99,235,.12);color:#2563eb">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div class="gd-menu-title">Siswa Bimbingan</div>
                <div class="gd-menu-desc">Pantau profil dan aktivitas siswa binaan</div>
                @if(isset($myStudentsCount) && $myStudentsCount > 0)
                <div class="gd-menu-badge" style="background:rgba(37,99,235,.1);color:#2563eb;border:1px solid rgba(37,99,235,.2)">
                    <span class="gd-badge-dot"></span>
                    <span>{{ $myStudentsCount }} Siswa</span>
                </div>
                @endif
            </a>

            {{-- Peminjaman Aktif --}}
            <a href="{{ route('teacher.loans') }}" class="gd-menu-card">
                <div class="gd-menu-icon" style="background:rgba(2,132,199,.12);color:#0284c7">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </div>
                <div class="gd-menu-title">Peminjaman Aktif</div>
                <div class="gd-menu-desc">Monitor barang yang sedang dipinjam</div>
                @if($totalBorrowed > 0)
                <div class="gd-menu-badge" style="background:rgba(2,132,199,.12);color:#0284c7;border:1px solid rgba(2,132,199,.25)">
                    <span class="gd-badge-dot"></span>
                    <span>{{ $totalBorrowed }} Dipinjam</span>
                </div>
                @endif
            </a>

            {{-- Pengembalian --}}
            <a href="{{ route('teacher.returns') }}" class="gd-menu-card">
                <div class="gd-menu-icon" style="background:rgba(16,185,129,.12);color:#10b981">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="gd-menu-title">Pengembalian</div>
                <div class="gd-menu-desc">Verifikasi pengembalian barang dan kondisi barang</div>
            </a>

            {{-- Laporan & Riwayat --}}
            <a href="{{ route('teacher.reports') }}" class="gd-menu-card">
                <div class="gd-menu-icon" style="background:rgba(147,51,234,.12);color:#9333ea">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="gd-menu-title">Laporan & Riwayat</div>
                <div class="gd-menu-desc">Rekapitulasi sirkulasi dan catatan log peminjaman</div>
            </a>

            {{-- Katalog Barang --}}
            <a href="{{ route('teacher.barang') }}" class="gd-menu-card">
                <div class="gd-menu-icon" style="background:rgba(14,116,144,.12);color:#0e7490">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div class="gd-menu-title">Katalog Barang</div>
                <div class="gd-menu-desc">Daftar barang dan ketersediaan stok</div>
            </a>
        </div>
    </div>
</div>
