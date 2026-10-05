<div>
    <style>
        :root {
            --gd-bg: #f8fafc;
            --gd-surface: #ffffff;
            --gd-border: #e2e8f0;
            --gd-text: #0f172a;
            --gd-muted: #64748b;
            --gd-accent: #10b981;
            --gd-accent-light: #ecfdf5;
            --gd-accent-border: #a7f3d0;
            --gd-shadow: 0 1px 3px rgba(0,0,0,.04), 0 4px 14px rgba(0,0,0,.02);
            --gd-shadow-hover: 0 10px 25px -5px rgba(5, 150, 105, 0.1), 0 8px 10px -6px rgba(5, 150, 105, 0.06);
            --gd-amber-bg: #fffbeb;
            --gd-amber-border: #fde68a;
            --gd-amber-text: #92400e;
            --gd-blue-bg: #eff6ff;
            --gd-blue-border: #bfdbfe;
            --gd-blue-text: #1e40af;
            --gd-purple-bg: #f5f3ff;
            --gd-purple-border: #ddd6fe;
            --gd-purple-text: #6b21a8;
            --gd-orange-bg: #ffedd5;
            --gd-orange-border: #fed7aa;
            --gd-orange-text: #9a3412;
        }

        html.dark {
            --gd-bg: #050806;
            --gd-surface: rgba(255, 255, 255, 0.03);
            --gd-border: rgba(16, 185, 129, 0.14);
            --gd-text: #ecfdf5;
            --gd-muted: #94a3b8;
            --gd-accent: #10b981;
            --gd-accent-light: rgba(16, 185, 129, 0.18);
            --gd-accent-border: rgba(16, 185, 129, 0.4);
            --gd-shadow: 0 4px 16px rgba(0,0,0,.5);
            --gd-shadow-hover: 0 0 24px rgba(16, 185, 129, 0.15), 0 10px 25px -5px rgba(16, 185, 129, 0.2), 0 8px 10px -6px rgba(16, 185, 129, 0.1);
            --gd-amber-bg: rgba(245, 158, 11, 0.08);
            --gd-amber-border: rgba(245, 158, 11, 0.3);
            --gd-amber-text: #fbbf24;
            --gd-blue-bg: rgba(37, 99, 235, 0.08);
            --gd-blue-border: rgba(37, 99, 235, 0.25);
            --gd-blue-text: #93c5fd;
            --gd-purple-bg: rgba(147, 51, 234, 0.08);
            --gd-purple-border: rgba(147, 51, 234, 0.25);
            --gd-purple-text: #c4b5fd;
            --gd-orange-bg: rgba(249, 115, 22, 0.08);
            --gd-orange-border: rgba(249, 115, 22, 0.25);
            --gd-orange-text: #fdba74;
        }

        html.dark body,
        html.dark .main,
        html.dark .content {
            background: #050806;
            background-image:
                radial-gradient(circle at 100% 0%, rgba(16, 185, 129, 0.14) 0%, transparent 60%),
                radial-gradient(circle at 0% 100%, rgba(5, 150, 105, 0.10) 0%, transparent 60%);
            background-attachment: fixed;
        }

        html.dark .gd-stat-card,
        html.dark .gd-menu-card,
        html.dark .gd-panel,
        html.dark .gd-greeting-card,
        html.dark .gd-action-card,
        html.dark .gd-alert-deadline {
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        html.dark .gd-stat-card:hover,
        html.dark .gd-menu-card:hover,
        html.dark .gd-txn-item:hover {
            border-color: rgba(16, 185, 129, 0.4);
        }

        html.dark .gd-stat-icon-box,
        html.dark .gd-menu-icon,
        html.dark .gd-action-icon,
        html.dark .gd-alert-icon {
            opacity: 0.9;
        }

        html.dark .gd-stat-icon-box svg,
        html.dark .gd-menu-icon svg,
        html.dark .gd-action-icon svg,
        html.dark .gd-alert-icon svg {
            opacity: 0.85;
        }

        * {
            transition: background-color 200ms ease, border-color 200ms ease, color 200ms ease, box-shadow 200ms ease;
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                transition: none !important;
            }
        }

        /* Greeting Header - Hero Card */
        .gd-greeting-card {
            background: linear-gradient(135deg, var(--gd-accent-light) 0%, var(--gd-surface) 100%);
            border: 1.5px solid var(--gd-accent-border);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: var(--gd-shadow);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        html.dark .gd-greeting-card {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.18) 0%, rgba(16, 185, 129, 0.04) 100%);
        }

        .gd-greeting-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .gd-avatar-badge {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--gd-accent), #047857);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 800;
            box-shadow: 0 4px 12px rgba(5, 150, 105, .25);
            flex-shrink: 0;
        }

        .gd-greeting-content {
            display: flex;
            flex-direction: column;
        }

        .gd-greeting-title {
            font-size: 26px;
            font-weight: 800;
            color: var(--gd-text);
            line-height: 1.2;
            letter-spacing: -0.02em;
            margin-bottom: 4px;
        }

        .gd-greeting-sub {
            font-size: 14px;
            color: var(--gd-muted);
            font-weight: 500;
        }

        .gd-greeting-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: all .2s ease;
        }

        .gd-greeting-pill-pending {
            background: var(--gd-amber-bg);
            color: var(--gd-amber-text);
            border: 1.5px solid var(--gd-amber-border);
            animation: gd-pulse-border 2s infinite ease-in-out;
        }

        .gd-greeting-pill-pending:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(217, 119, 6, .2);
        }

        .gd-greeting-pill-allgood {
            background: var(--gd-accent-light);
            color: var(--gd-accent);
            border: 1.5px solid var(--gd-accent-border);
        }

        @keyframes gd-pulse-border {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }

        /* Perlu Tindakan Card */
        .gd-action-card {
            background: var(--gd-amber-bg);
            border: 1.5px solid var(--gd-amber-border);
            border-radius: 16px;
            padding: 20px 24px;
            margin-bottom: 24px;
            box-shadow: var(--gd-shadow);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .gd-action-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .gd-action-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(245, 158, 11, 0.2);
            color: #d97706;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        html.dark .gd-action-icon {
            color: #fbbf24;
        }

        .gd-action-content {
            display: flex;
            flex-direction: column;
        }

        .gd-action-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--gd-amber-text);
            margin-bottom: 2px;
        }

        .gd-action-desc {
            font-size: 13px;
            color: var(--gd-muted);
        }

        .gd-action-btn {
            padding: 10px 20px;
            background: var(--gd-amber-text);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all .2s ease;
            white-space: nowrap;
        }

        .gd-action-btn:hover {
            background: #78350f;
            transform: translateY(-1px);
        }

        /* Section Header */
        .gd-section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .gd-section-title-wrap {
            display: flex;
            flex-direction: column;
        }

        .gd-section-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--gd-text);
            letter-spacing: -0.01em;
            margin: 0;
            line-height: 1.3;
        }

        .gd-section-desc {
            font-size: 13px;
            color: var(--gd-muted);
            margin-top: 2px;
            font-weight: 400;
        }

        .gd-section-link {
            font-size: 13px;
            font-weight: 600;
            color: var(--gd-accent);
            text-decoration: none;
            transition: color .2s ease;
        }

        .gd-section-link:hover {
            color: #047857;
            text-decoration: underline;
        }

        /* Statistics Grid */
        .gd-stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        @media (max-width: 1024px) {
            .gd-stat-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 640px) {
            .gd-stat-grid { grid-template-columns: 1fr; gap: 16px; }
        }

        .gd-stat-card {
            background: var(--gd-surface);
            border: 1.5px solid var(--gd-border);
            border-radius: 16px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 16px;
            transition: all .2s ease;
            box-shadow: var(--gd-shadow);
            position: relative;
        }

        .gd-stat-card:hover {
            border-color: var(--gd-accent);
            transform: translateY(-2px);
            box-shadow: var(--gd-shadow-hover);
        }

        .gd-stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .gd-stat-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .gd-stat-pill {
            font-size: 12px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 999px;
            white-space: nowrap;
        }

        .gd-stat-num {
            font-size: 36px;
            font-weight: 800;
            color: var(--gd-text);
            line-height: 1;
            margin-bottom: 4px;
            letter-spacing: -0.02em;
        }

        .gd-stat-label {
            font-size: 14px;
            color: var(--gd-muted);
            font-weight: 600;
            line-height: 1.4;
        }

        /* Quick Action Menu Grid */
        .gd-menu-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        @media (max-width: 1024px) {
            .gd-menu-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 640px) {
            .gd-menu-grid { grid-template-columns: 1fr; gap: 16px; }
        }

        .gd-menu-card {
            background: var(--gd-surface);
            border: 1.5px solid var(--gd-border);
            border-radius: 16px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            text-decoration: none;
            transition: all .22s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--gd-shadow);
            position: relative;
            overflow: hidden;
        }

        .gd-menu-card:hover {
            border-color: var(--gd-accent);
            transform: translateY(-3px);
            box-shadow: var(--gd-shadow-hover);
        }

        .gd-menu-card.gd-card-priority {
            border: 2px solid var(--gd-amber-border);
        }

        .gd-menu-card.gd-card-priority::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #f59e0b, #d97706);
        }

        .gd-menu-card.gd-card-priority:hover {
            border-color: #d97706;
            box-shadow: 0 10px 24px rgba(245, 158, 11, .22);
        }

        .gd-menu-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: transform .2s ease;
        }

        .gd-menu-card:hover .gd-menu-icon {
            transform: scale(1.08);
        }

        .gd-menu-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--gd-text);
            line-height: 1.3;
            letter-spacing: -0.01em;
        }

        .gd-menu-desc {
            font-size: 13px;
            color: var(--gd-muted);
            line-height: 1.5;
            font-weight: 400;
        }

        .gd-menu-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            margin-top: 4px;
            align-self: flex-start;
            white-space: nowrap;
        }

        .gd-badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        /* Activity Panels */
        .gd-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 24px;
        }

        @media (max-width: 960px) {
            .gd-grid-2 { grid-template-columns: 1fr; }
        }

        .gd-panel {
            background: var(--gd-surface);
            border: 1.5px solid var(--gd-border);
            border-radius: 16px;
            padding: 24px;
            box-shadow: var(--gd-shadow);
            display: flex;
            flex-direction: column;
        }

        .gd-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--gd-border);
            padding-bottom: 16px;
        }

        .gd-panel-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--gd-text);
            letter-spacing: -0.01em;
        }

        .gd-panel-sub {
            font-size: 13px;
            color: var(--gd-muted);
            margin-top: 4px;
            font-weight: 400;
        }

        .gd-txn-item {
            background: var(--gd-bg);
            border: 1px solid var(--gd-border);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            transition: all .15s ease;
        }

        .gd-txn-item:last-child { margin-bottom: 0; }

        .gd-txn-item:hover {
            border-color: var(--gd-accent);
            transform: translateX(2px);
        }

        .gd-txn-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 12px;
            gap: 12px;
        }

        .gd-txn-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--gd-text);
            line-height: 1.4;
        }

        .gd-txn-type {
            font-size: 13px;
            color: var(--gd-muted);
            margin-top: 4px;
        }

        .gd-txn-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            color: var(--gd-muted);
            padding-top: 12px;
            border-top: 1px dashed var(--gd-border);
            flex-wrap: wrap;
            gap: 8px;
        }

        .gd-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .02em;
            white-space: nowrap;
        }

        .gd-badge-pending {
            background: var(--gd-amber-bg);
            color: var(--gd-amber-text);
            border: 1px solid var(--gd-amber-border);
        }

        .gd-badge-approved {
            background: var(--gd-blue-bg);
            color: var(--gd-blue-text);
            border: 1px solid var(--gd-blue-border);
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
            background: var(--gd-accent-light);
            color: var(--gd-accent);
            border: 1px solid var(--gd-accent-border);
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
            padding: 48px 24px;
            color: var(--gd-muted);
        }

        .gd-empty-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 16px;
            color: var(--gd-muted);
            opacity: .5;
        }

        .gd-empty-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--gd-text);
            margin-bottom: 4px;
        }

        .gd-empty-sub {
            font-size: 13px;
            color: var(--gd-muted);
            margin-bottom: 16px;
        }

        .gd-empty-btn {
            padding: 10px 20px;
            background: var(--gd-accent);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all .2s ease;
        }

        .gd-empty-btn:hover {
            background: #047857;
            transform: translateY(-1px);
        }

        /* Deadline Alert */
        .gd-alert-deadline {
            background: var(--gd-amber-bg);
            border: 1.5px solid var(--gd-amber-border);
            border-radius: 16px;
            padding: 20px 24px;
            margin-bottom: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .gd-alert-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .gd-alert-title-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .gd-alert-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
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
            font-size: 15px;
            font-weight: 700;
            color: var(--gd-amber-text);
        }

        .gd-alert-desc {
            font-size: 13px;
            color: var(--gd-muted);
            margin-top: 2px;
        }

        .gd-alert-link {
            font-size: 13px;
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
            gap: 12px;
        }

        .gd-deadline-item {
            background: var(--gd-surface);
            border: 1px solid var(--gd-amber-border);
            border-radius: 12px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .gd-deadline-item-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--gd-text);
        }

        .gd-deadline-item-user {
            font-size: 12px;
            color: var(--gd-muted);
        }

        .gd-deadline-badge {
            font-size: 12px;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 8px;
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

        /* Focus styles for accessibility */
        a:focus-visible, button:focus-visible {
            outline: 2px solid var(--gd-accent);
            outline-offset: 2px;
        }
    </style>

    {{-- 1. Greeting & User Header Card --}}
    <div class="gd-greeting-card">
        <div class="gd-greeting-left">
            <div class="gd-avatar-badge">
                {{ strtoupper(substr(auth()->check() ? auth()->user()->name : 'G', 0, 1)) }}
            </div>
            <div class="gd-greeting-content">
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
                <a href="{{ route('teacher.requests') }}" class="gd-greeting-pill gd-greeting-pill-pending" aria-label="{{ $pendingRequests }} permohonan menunggu persetujuan">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ $pendingRequests }} Permohonan Menunggu</span>
                </a>
            @else
                <div class="gd-greeting-pill gd-greeting-pill-allgood">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M5 13l4 4L19 7"/></svg>
                    <span>Status Sirkulasi Normal</span>
                </div>
            @endif
        </div>
    </div>

    {{-- 2. Perlu Tindakan (Action Card) - Only if pending requests --}}
    @if(isset($pendingRequests) && $pendingRequests > 0)
    <div class="gd-action-card">
        <div class="gd-action-left">
            <div class="gd-action-icon">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="gd-action-content">
                <div class="gd-action-title">{{ $pendingRequests }} Permohonan Siswa Menunggu</div>
                <div class="gd-action-desc">Segera tinjau dan setujui pengajuan dari siswa bimbingan Anda</div>
            </div>
        </div>
        <a href="{{ route('teacher.requests') }}" class="gd-action-btn">Tinjau Sekarang →</a>
    </div>
    @endif

    {{-- 3. Quick Access Menu (Moved up for priority) --}}
    <div style="margin-bottom:24px">
        <div class="gd-section-head">
            <div class="gd-section-title-wrap">
                <h2 class="gd-section-title">Akses Cepat</h2>
                <div class="gd-section-desc">Menu utama untuk mengelola peminjaman dan aktivitas</div>
            </div>
        </div>
        <div class="gd-menu-grid">
            {{-- Permohonan Siswa (PRIORITY) --}}
            <a href="{{ route('teacher.requests') }}" class="gd-menu-card gd-card-priority">
                <div class="gd-menu-icon" style="background:rgba(245, 158, 11, .14);color:#d97706">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="gd-menu-title">Permohonan Siswa</div>
                <div class="gd-menu-desc">Kelola dan setujui pengajuan siswa bimbingan</div>
                @if(isset($pendingRequests) && $pendingRequests > 0)
                <div class="gd-menu-badge" style="background:var(--gd-amber-bg);color:var(--gd-amber-text);border:1px solid var(--gd-amber-border)">
                    <span class="gd-badge-dot"></span>
                    <span>{{ $pendingRequests }} Menunggu</span>
                </div>
                @endif
            </a>

            {{-- Siswa Bimbingan --}}
            <a href="{{ route('teacher.students') }}" class="gd-menu-card">
                <div class="gd-menu-icon" style="background:rgba(37,99,235,.12);color:#2563eb">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                </div>
                <div class="gd-menu-title">Siswa Bimbingan</div>
                <div class="gd-menu-desc">Pantau profil dan aktivitas siswa binaan</div>
                @if(isset($myStudentsCount) && $myStudentsCount > 0)
                <div class="gd-menu-badge" style="background:var(--gd-blue-bg);color:var(--gd-blue-text);border:1px solid var(--gd-blue-border)">
                    <span class="gd-badge-dot"></span>
                    <span>{{ $myStudentsCount }} Siswa</span>
                </div>
                @endif
            </a>

            {{-- Peminjaman Aktif --}}
            <a href="{{ route('teacher.loans') }}" class="gd-menu-card">
                <div class="gd-menu-icon" style="background:rgba(2,132,199,.12);color:#0284c7">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
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
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                </div>
                <div class="gd-menu-title">Pengembalian</div>
                <div class="gd-menu-desc">Verifikasi pengembalian barang dan kondisi barang</div>
            </a>

            {{-- Laporan & Riwayat --}}
            <a href="{{ route('teacher.reports') }}" class="gd-menu-card">
                <div class="gd-menu-icon" style="background:rgba(147,51,234,.12);color:#9333ea">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="gd-menu-title">Laporan & Riwayat</div>
                <div class="gd-menu-desc">Rekapitulasi sirkulasi dan catatan log peminjaman</div>
            </a>

            {{-- Katalog Barang --}}
            <a href="{{ route('teacher.barang') }}" class="gd-menu-card">
                <div class="gd-menu-icon" style="background:rgba(14,116,144,.12);color:#0e7490">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                </div>
                <div class="gd-menu-title">Katalog Barang</div>
                <div class="gd-menu-desc">Daftar barang dan ketersediaan stok</div>
            </a>
        </div>
    </div>

    {{-- 4. Statistics Grid --}}
    <div style="margin-bottom:24px">
        <div class="gd-section-head">
            <div class="gd-section-title-wrap">
                <h2 class="gd-section-title">Ringkasan Statistik</h2>
                <div class="gd-section-desc">Data real-time ketersediaan dan peminjaman barang</div>
            </div>
        </div>
        <div class="gd-stat-grid">
            {{-- Barang Tersedia --}}
            <div class="gd-stat-card">
                <div class="gd-stat-top">
                    <div class="gd-stat-icon-box" style="background:rgba(16,185,129,.12)">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#10b981" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="gd-stat-pill" style="background:var(--gd-accent-light);color:var(--gd-accent);border:1px solid var(--gd-accent-border)">Siap Pakai</span>
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
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#0284c7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    <span class="gd-stat-pill" style="background:var(--gd-blue-bg);color:var(--gd-blue-text);border:1px solid var(--gd-blue-border)">Aktif Guru</span>
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
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#059669" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <span class="gd-stat-pill" style="background:var(--gd-accent-light);color:#047857;border:1px solid var(--gd-accent-border)">Selesai</span>
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
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#6366f1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="gd-stat-pill" style="background:var(--gd-purple-bg);color:var(--gd-purple-text);border:1px solid var(--gd-purple-border)">Katalog</span>
                </div>
                <div>
                    <div class="gd-stat-num">{{ $departmentItems }}</div>
                    <div class="gd-stat-label">Total Item Terdata</div>
                </div>
            </div>
        </div>
    </div>

    {{-- 5. Deadline Alert --}}
    @if(isset($upcomingDeadlines) && $upcomingDeadlines->count() > 0)
    <div class="gd-alert-deadline">
        <div class="gd-alert-head">
            <div class="gd-alert-title-wrap">
                <div class="gd-alert-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <div class="gd-alert-title">Perhatian: Jatuh Tempo Pengembalian Segera (≤ 3 Hari)</div>
                    <div class="gd-alert-desc">Terdapat {{ $upcomingDeadlines->count() }} transaksi peminjaman aktif yang mendekati batas waktu.</div>
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

    {{-- 6. Activity Logs --}}
    <div style="margin-bottom:24px">
        <div class="gd-section-head">
            <div class="gd-section-title-wrap">
                <h2 class="gd-section-title">Aktivitas & Log Sirkulasi</h2>
                <div class="gd-section-desc">Transaksi peminjaman terbaru di sekolah dan peminjaman Anda</div>
            </div>
            <a href="{{ route('teacher.loans') }}" class="gd-section-link">Lihat Semua →</a>
        </div>
        <div class="gd-grid-2">
            {{-- Sirkulasi Sekolah --}}
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
                                <div class="gd-txn-type">Diajukan oleh: <strong style="color:var(--gd-text)">{{ $borrowing->user->name ?? 'Siswa' }}</strong></div>
                            </div>
                            <span class="gd-badge {{ $borrowing->status === 'borrowed' ? 'gd-badge-borrowed' : ($borrowing->status === 'returned' ? 'gd-badge-returned' : ($borrowing->status === 'approved' ? 'gd-badge-approved' : ($borrowing->status === 'rejected' ? 'gd-badge-rejected' : 'gd-badge-pending'))) }}">
                                {{ $borrowing->status === 'borrowed' ? 'DIPINJAM' : ($borrowing->status === 'returned' ? 'KEMBALI' : ($borrowing->status === 'approved' ? 'DISETUJUI' : ($borrowing->status === 'rejected' ? 'DITOLAK' : 'MENUNGGU'))) }}
                            </span>
                        </div>
                        <div class="gd-txn-meta">
                            <div style="display:flex;align-items:center;gap:6px;color:var(--gd-muted)">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
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
                            <div style="display:flex;align-items:center;gap:6px;color:var(--gd-muted)">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <div class="gd-empty-title">Belum ada peminjaman aktif</div>
                        <div class="gd-empty-sub">Riwayat pengajuan barang Anda akan muncul di sini</div>
                        <a href="{{ route('teacher.peminjaman-guru') }}" class="gd-empty-btn">Ajukan Peminjaman</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
