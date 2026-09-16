<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIPBAR Superadmin')</title>
    {{-- ══════════════════════════════════════════════════════════════
        CONSOLIDATED THEME SYSTEM - Bug #2 Fix
        Single source of truth for theme management
        Supports: localStorage, system preference fallback, Livewire
    ══════════════════════════════════════════════════════════════ --}}
    <script>
    (function(){
        var KEY = 'sipbar-superadmin-theme';

        // ── Get initial theme with system preference fallback ──
        function getInitialTheme() {
            var saved = localStorage.getItem(KEY);

            // If user explicitly set theme, use it
            if (saved === 'light' || saved === 'dark') {
                return saved;
            }

            // Check if user has theme in admin layout (for consistency)
            var adminTheme = localStorage.getItem('sipbar-dash-theme');
            if (adminTheme === 'light' || adminTheme === 'dark') {
                return adminTheme;
            }

            // Fallback to system preference
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
                return 'light';
            }

            // Default to dark
            return 'dark';
        }

        // ── Apply theme IMMEDIATELY (before CSS loads) ──
        var initialTheme = getInitialTheme();
        if (initialTheme === 'light') {
            document.documentElement.classList.add('light');
        } else {
            document.documentElement.classList.remove('light');
        }

        // Store theme manager globally for use in toggle script
        window.__sipbarTheme = {
            current: initialTheme,
            key: KEY
        };
    })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if (class_exists('Livewire\Livewire'))
        @livewireStyles
    @endif
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        
        /* ══════ DARK MODE TOKENS (default) ══════ */
        /* Bug #3 Fix: Improved contrast ratios for WCAG AA compliance */
        :root {
            --bg-main: #000000;
            --bg-alt: #111111;
            --bg-card: #111111;
            --bg-card-subtle: #000000;
            --bg-hover: #222222;
            --border-main: #333333;
            --border-alt: #444444;
            --border-subtle: rgba(255,255,255,0.1);
            --text-primary: #ffffff;
            --text-secondary: #e0e0e0;
            --text-muted: #b0b0b0;              /* Improved from #a0a0a0 → Better contrast */
            --text-subtle: #8a8a8a;              /* Improved from #707070 → Better contrast */
            --blue: #3b82f6;                     /* Fixed: Use actual blue for better contrast */
            --blue-dark: #2563eb;               /* Fixed: Use darker blue for button backgrounds */
            --sidebar-bg: #000000;
            --topbar-bg: #000000;
            --content-bg: #050505;
            --scrollbar: #333333;
            --notif-border: #000000;
            --table-head-bg: #1a1a1a;           /* Improved from #111111 → Better contrast with text */
            --table-hover: rgba(34,34,34,0.6);
            --input-bg: #111111;
            
            /* Badge & Status Colors - Improved for dark mode */
            --color-success: #10b981;            /* Green - visible on dark */
            --color-warning: #fbbf24;            /* Amber - improved from #f59e0b */
            --color-danger: #f87171;             /* Red - visible on dark */
            --color-info: #60a5fa;               /* Blue - visible on dark */
            --color-pending: #fbbf24;            /* Amber */
            --color-approved: #60a5fa;           /* Blue */
            --color-borrowed: #eab308;           /* Yellow */
            --color-returned: #10b981;           /* Green */
            --color-rejected: #f87171;           /* Red */
            --color-overdue: #ef4444;            /* Bright red */
            --input-border: rgba(255,255,255,0.15);
            --hero-bg: #000000;
            --card-shadow: 0 4px 20px rgba(0,0,0,0.5);
        }

        /* ══════ LIGHT MODE TOKENS ══════ */
        html.light {
            --bg-main: #ffffff;
            --bg-alt: #f8f8f8;
            --bg-card: #ffffff;
            --bg-card-subtle: #f8f8f8;
            --bg-hover: #f0f0f0;
            --border-main: #e0e0e0;
            --border-alt: #d0d0d0;
            --border-subtle: rgba(0,0,0,0.08);
            --text-primary: #000000;
            --text-secondary: #1a1a1a;
            --text-muted: #606060;
            --text-subtle: #909090;
            --blue: #2563eb;                     /* Fixed: Use actual blue for better contrast */
            --blue-dark: #1d4ed8;               /* Fixed: Use darker blue for button backgrounds */
            --sidebar-bg: #ffffff;
            --topbar-bg: #ffffff;
            --content-bg: #fafafa;
            --scrollbar: #d0d0d0;
            --notif-border: #ffffff;
            --table-head-bg: #f8f8f8;
            --table-hover: rgba(240,240,240,0.6);
            --input-bg: #ffffff;
            --input-border: rgba(0,0,0,0.12);
            --hero-bg: #ffffff;
            --card-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        html, body { height: 100%; min-height: 100%; font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; -webkit-font-smoothing: antialiased; }
        body { display: flex; background: var(--content-bg); color: var(--text-primary); overflow: hidden; height: 100vh; transition: background .25s, color .25s; }
        a { color: inherit; }

        /* ========== SIDEBAR ========== */
        .sidebar {
            width: 220px;
            flex-shrink: 0;
            background: var(--sidebar-bg);
            color: var(--text-primary);
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 40;
            border-right: 1px solid var(--border-main);
            transition: transform .3s, background .25s, color .25s, border-color .25s;
        }
        .sidebar.collapsed { width: 0; overflow: hidden; transition: width .3s ease; }
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 20px 18px 16px;
            border-bottom: 1px solid var(--border-main);
            text-decoration: none;
            color: var(--text-primary);
        }
        .sidebar-logo-wrap {
            width: 48px; height: 48px; border-radius: 50%;
            background: rgba(255,255,255,.92);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,.35), 0 0 0 1.5px rgba(255,255,255,.12);
            transition: transform .2s ease, box-shadow .2s ease;
        }
        html.light .sidebar-logo-wrap {
            background: rgba(255,255,255,.98);
            box-shadow: 0 2px 8px rgba(0,0,0,.12), 0 0 0 1.5px rgba(0,0,0,.07);
        }
        .sidebar-brand:hover .sidebar-logo-wrap { transform: scale(1.05); box-shadow: 0 4px 14px rgba(0,0,0,.4); }
        .sidebar-brand-img { width: 100%; height: 100%; object-fit: cover; }
        .brand-name { font-size: 16px; font-weight: 800; color: var(--text-primary); line-height: 1.15; }
        .brand-sub { font-size: 10px; font-weight: 700; color: var(--text-muted); line-height: 1.2; letter-spacing: .08em; text-transform: uppercase; }
        .sidebar-search {
            margin: 14px 14px 8px;
            display: flex; align-items: center; gap: 8px;
            background: var(--bg-card-subtle); border: 1px solid var(--border-subtle);
            border-radius: 8px; padding: 7px 10px;
        }
        .sidebar-search input {
            background: none; border: none; outline: none;
            font-size: 12px; color: var(--text-primary); width: 100%;
        }
        .sidebar-search input::placeholder { color: var(--text-muted); }
        .sidebar-nav { flex: 1; overflow-y: auto; padding: 8px 10px; }
        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: var(--scrollbar); border-radius: 2px; }
        .nav-group-label {
            font-size: 10px;
            font-weight: 700;
            color: var(--text-subtle);
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: 14px 10px 6px;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            color: var(--text-muted);
            margin-bottom: 2px;
            cursor: pointer;
            transition: all .15s;
            white-space: nowrap;
        }
        .nav-item:hover { background: var(--bg-hover); color: var(--text-primary); }
        .nav-item.active { background: var(--blue-dark); color: #fff; box-shadow: 0 4px 12px rgba(255,255,255,.2); }
        .nav-item.active .nav-icon { color: #fff; }
        .nav-icon { width: 16px; height: 16px; flex-shrink: 0; display: grid; place-items: center; }
        .nav-badge {
            margin-left: auto;
            background: var(--blue-dark);
            color: #fff;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            padding: 1px 6px;
        }
        .nav-item.active .nav-badge { background: rgba(255,255,255,.25); }
        .sidebar-footer {
            padding: 14px;
            border-top: 1px solid var(--border-main);
        }
        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: 10px;
            background: var(--bg-card-subtle);
            border: 1px solid var(--border-subtle);
            cursor: pointer;
        }
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--blue-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }
        .user-name { font-size: 12px; font-weight: 700; color: var(--text-primary); }
        .user-role { font-size: 10px; color: var(--text-muted); }
        .user-caret { margin-left: auto; color: var(--text-subtle); }
        .sidebar-cta {
            margin: 0 12px 12px;
            padding: 14px 16px;
            background: var(--bg-card-subtle);
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
        }
        .sidebar-cta-label {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 10px;
            font-weight: 700;
            color: var(--blue);
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .sidebar-cta-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            width: 100%;
            padding: 10px 14px;
            background: var(--blue-dark);
            color: #ffffff !important;
            font-size: 12px;
            font-weight: 700;
            border-radius: 10px;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(59,130,246,.3);
            transition: all .2s;
        }
        .sidebar-cta-btn:hover {
            background: var(--blue);
            color: #ffffff !important;
            transform: translateY(-1px);
        }

        /* ========== MAIN & TOPBAR ========== */
        .main { margin-left: 220px; flex: 1; height: 100vh; max-height: 100vh; display: flex; flex-direction: column; background: var(--content-bg); overflow: hidden; transition: background .25s; }
        .main.expanded { margin-left: 0; transition: margin-left .3s ease; }
        .topbar {
            height: 60px;
            background: var(--topbar-bg);
            border-bottom: 1px solid var(--border-main);
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 12px;
            flex-shrink: 0;
            transition: background .25s, border-color .25s;
        }
        .breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--text-muted); }
        .breadcrumb .active { color: var(--text-primary); font-weight: 600; }
        .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 8px; }
        .topbar-icon-btn {
            width: 34px;
            height: 34px;
            background: var(--bg-card);
            border: 1px solid var(--border-alt);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-muted);
            transition: all .15s;
        }
        .topbar-icon-btn:hover { background: var(--bg-hover); color: var(--text-primary); }
        .notif-btn { position: relative; }
        .notif-dot {
            position: absolute;
            top: 6px;
            right: 7px;
            width: 7px;
            height: 7px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid var(--notif-border);
            transition: border-color .25s;
        }
        .content {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
            background: var(--content-bg);
            transition: background .25s;
            /* Ensure content area can grow and scroll independently */
            min-height: 0;
        }
        .content::-webkit-scrollbar { width: 5px; }
        .content::-webkit-scrollbar-track { background: transparent; }
        .content::-webkit-scrollbar-thumb { background: var(--scrollbar); border-radius: 3px; }
        .content::-webkit-scrollbar-thumb:hover { background: var(--border-alt); }

        /* Panels & Clean Headers */
        .panel {
            background: var(--bg-card);
            border: 1px solid var(--border-alt);
            border-radius: 18px;
            padding: 24px;
            box-shadow: var(--card-shadow);
            transition: background .25s, border-color .25s;
        }
        .panel-title { font-size: 18px; font-weight: 800; color: var(--text-primary); margin-bottom: 10px; }
        .panel-text { color: var(--text-muted); line-height: 1.7; font-size: 13px; }
        .action-link { display: inline-flex; align-items: center; gap: 8px; color: var(--blue); font-weight: 700; text-decoration: none; margin-top: 16px; font-size: 13px; }

        /* ── Universal Table Responsive Helper ── */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* ── Hamburger & Mobile Overlay ── */
        .admin-hamburger-btn {
            display: none;
            align-items: center;
            justify-content: center;
            min-width: 44px;
            min-height: 44px;
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: var(--bg-card);
            border: 1px solid var(--border-alt);
            color: var(--text-primary);
            cursor: pointer;
            flex-shrink: 0;
            transition: all .15s;
        }
        .admin-hamburger-btn:hover { background: var(--bg-hover); }

        .sidebar-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,.55);
            backdrop-filter: blur(3px); -webkit-backdrop-filter: blur(3px);
            z-index: 45; display: none; opacity: 0;
            transition: opacity .25s ease;
        }
        .sidebar-overlay.active { display: block; opacity: 1; }

        @media(max-width: 960px) {
            .sidebar { 
                position: fixed; 
                left: 0; top: 0;
                transform: translateX(-100%);
                width: 220px;
                z-index: 50;
                box-shadow: 4px 0 30px rgba(0,0,0,.35);
                transition: transform .3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .sidebar.open { transform: translateX(0); }
            .sidebar.collapsed { width: 220px; }
            .main { margin-left: 0; }
            .main.expanded { margin-left: 0; }
            .admin-hamburger-btn { display: flex !important; }
            .topbar { padding: 0 16px; gap: 8px; }
            .content { padding: 16px 14px; }
        }

        @media(max-width: 480px) {
            .breadcrumb { display: none; }
            .topbar-right { gap: 6px; }
            .topbar-icon-btn { width: 38px; height: 38px; }
            .content { padding: 14px 12px; }
        }

        /* ══════════════════════════════════════════════════════════════
           GLOBAL PREMIUM SELECT / DROPDOWN UPGRADE — SUPERADMIN PANEL
           Applies to: .im-select, .im-select-field, select (generic)
        ══════════════════════════════════════════════════════════════ */

        /* Base select reset + custom appearance */
        select,
        .im-select,
        .im-select-field {
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;

            /* Custom chevron down SVG arrow in var(--blue) */
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%232563eb' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 12px center !important;
            background-size: 15px 15px !important;

            padding-right: 38px !important;
            background-color: var(--input-bg) !important;
            border: 1.5px solid var(--input-border) !important;
            border-radius: 10px !important;
            color: var(--text-primary) !important;
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            outline: none !important;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08) !important;
        }

        /* Light mode: blue arrow */
        html.light select,
        html.light .im-select,
        html.light .im-select-field {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%232563eb' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") !important;
        }

        /* Dark mode: light blue arrow */
        html.dark select,
        html.dark .im-select,
        html.dark .im-select-field {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2360a5fa' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") !important;
        }

        /* Hover state */
        select:hover,
        .im-select:hover,
        .im-select-field:hover {
            border-color: var(--blue) !important;
            box-shadow: 0 0 0 2px rgba(59,130,246,0.1) !important;
        }

        /* Focus state — glowing ring */
        select:focus,
        .im-select:focus,
        .im-select-field:focus {
            border-color: var(--blue) !important;
            box-shadow: 0 0 0 3.5px rgba(59,130,246,0.18) !important;
            background-color: var(--input-bg) !important;
        }

        /* Option list styling (best-effort, browsers limit this) */
        select option {
            background: var(--bg-card) !important;
            color: var(--text-primary) !important;
            font-weight: 500;
            padding: 8px 12px;
        }

        /* Size-specific padding overrides for .im-select (toolbar filters) */
        .im-select {
            padding-top: 9px !important;
            padding-bottom: 9px !important;
            padding-left: 12px !important;
            font-size: 12px !important;
        }

        /* Size-specific padding overrides for .im-select-field (forms) */
        .im-select-field {
            width: 100% !important;
            padding-top: 10px !important;
            padding-bottom: 10px !important;
            padding-left: 13px !important;
            font-size: 13px !important;
        }

        /* ── Elegant select label container (optional wrapper) ── */
        .admin-select-wrap {
            position: relative;
            display: inline-block;
            width: 100%;
        }
        .admin-select-wrap select {
            width: 100% !important;
        }

        /* ── Subtle animated underline on focus for extra polish ── */
        select:focus::after,
        .im-select:focus::after,
        .im-select-field:focus::after {
            content: '';
            position: absolute;
            bottom: 0; left: 12px; right: 12px;
            height: 2px;
            background: var(--blue);
            border-radius: 1px;
            animation: selectFocusLine 0.2s ease;
        }
        @keyframes selectFocusLine {
            from { transform: scaleX(0); opacity: 0; }
            to   { transform: scaleX(1); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <aside class="sidebar" id="sidebar">
        <a href="{{ route('superadmin.dashboard') }}" class="sidebar-brand">
            <div class="sidebar-logo-wrap">
                <img src="{{ $siteLogo ?? '/logossmkn1.png' }}" alt="{{ $siteName ?? 'SIPBAR' }}" class="sidebar-brand-img">
            </div>
            <div>
                <div class="brand-name">{{ $siteName ?? 'SIPBAR' }}</div>
                <div class="brand-sub">SUPERADMIN</div>
            </div>
        </a>

        <div class="sidebar-search">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;color:var(--text-muted);flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" placeholder="Cari di sini...">
        </div>

        <nav class="sidebar-nav">
            <div class="nav-group-label">Menu Utama</div>

            <a href="{{ route('superadmin.dashboard') }}" class="nav-item {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>

            <a href="{{ route('superadmin.qr-scanner') }}" class="nav-item {{ request()->routeIs('superadmin.qr-scanner') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-5v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V8a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1zm12 0h2a1 1 0 001-1V8a1 1 0 00-1-1h-2a1 1 0 00-1 1v1a1 1 0 001 1zM5 20h2a1 1 0 001-1v-1a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1z"/></svg>
                Scan QR Siswa
            </a>

            <a href="{{ route('superadmin.inventory') }}" class="nav-item {{ request()->routeIs('superadmin.inventory') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Barang
            </a>

            <a href="{{ route('superadmin.manage-items') }}" class="nav-item {{ request()->routeIs('superadmin.manage-items') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Kelola Barang
            </a>

            <a href="{{ route('superadmin.categories') }}" class="nav-item {{ request()->routeIs('superadmin.categories') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Kategori
            </a>

            <a href="{{ route('superadmin.loans') }}" class="nav-item {{ request()->routeIs('superadmin.loans') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                Peminjaman
                @if(\App\Models\Borrowing::where('status','pending')->count() > 0)
                <span class="nav-badge">{{ \App\Models\Borrowing::where('status','pending')->count() }}</span>
                @endif
            </a>

            @php
                $pendingReturnCount = \App\Models\ItemReturn::where('status', 'menunggu')->count();
            @endphp
            <a href="{{ route('superadmin.returns') }}" class="nav-item {{ request()->routeIs('superadmin.returns') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                Pengembalian
                @if($pendingReturnCount > 0)
                <span class="nav-badge" style="background:var(--color-warning);color:#0f172a;">{{ $pendingReturnCount }}</span>
                @endif
            </a>

            <div class="nav-group-label">Laporan</div>

            <a href="{{ route('superadmin.reports') }}" class="nav-item {{ request()->routeIs('superadmin.reports') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Laporan
            </a>

            <a href="{{ route('superadmin.laporan-jurusan') }}" class="nav-item {{ request()->routeIs('superadmin.laporan-jurusan*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Laporan Jurusan
            </a>

            <a href="{{ route('superadmin.laporan-admin') }}" class="nav-item {{ request()->routeIs('superadmin.laporan-admin*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Laporan dari Admin
            </a>

            @php
                try {
                    $unreadTeacherReportCount = \App\Models\LaporanGuru::where('status', 'belum_dibaca')->count();
                } catch (\Exception $e) {
                    $unreadTeacherReportCount = 0;
                }
            @endphp
            <a href="{{ route('superadmin.laporan-guru.index') }}" class="nav-item {{ request()->routeIs('superadmin.laporan-guru*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Laporan Guru
                @if($unreadTeacherReportCount > 0)
                <span class="nav-badge" style="background:var(--color-warning);color:#0f172a;">{{ $unreadTeacherReportCount }}</span>
                @endif
            </a>

            <a href="{{ route('superadmin.statistics') }}" class="nav-item {{ request()->routeIs('superadmin.statistics') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                Statistik
            </a>

            <div class="nav-group-label">Pengaturan</div>

            <a href="{{ route('superadmin.users') }}" class="nav-item {{ request()->routeIs('superadmin.users') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Pengguna
            </a>

            <a href="{{ route('superadmin.landing-page.index') }}" class="nav-item {{ request()->routeIs('superadmin.landing-page*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Kelola Landing Page
            </a>

            <a href="{{ route('superadmin.settings') }}" class="nav-item {{ request()->routeIs('superadmin.settings') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Pengaturan
            </a>
        </nav>

        <div class="sidebar-cta">
            <div class="sidebar-cta-label">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Guru / Siswa
            </div>
            <a href="{{ route('superadmin.users') }}" class="sidebar-cta-btn">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Kelola Akun Pengguna
            </a>
        </div>

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">{{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'SA' }}</div>
                <div>
                    <div class="user-name">{{ auth()->check() ? auth()->user()->name : 'Superadmin' }}</div>
                    <div class="user-role">Superadmin</div>
                </div>
                <div class="user-caret">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>
        </div>
    </aside>

    <div class="main">
        <div class="topbar">
            <button type="button" class="admin-hamburger-btn" id="hamburgerBtn" title="Buka Menu" aria-label="Toggle Menu">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="breadcrumb">
                <span>Home</span>
                <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="active">@yield('page-heading', 'Dashboard')</span>
            </div>
            <div class="topbar-right">
                {{-- CTA Kembali ke Beranda --}}
                <a href="{{ route('home') }}" class="topbar-icon-btn" title="Kembali ke Beranda" style="text-decoration:none">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </a>
                {{-- Theme toggle --}}
                <button class="topbar-icon-btn" id="themeBtn" title="Ganti Mode (Alt+D)" aria-label="Toggle tema" style="position:relative">
                    <svg id="iconMoon" xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg id="iconSun"  xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;display:none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10A5 5 0 0012 7z"/></svg>
                </button>
                @include('partials.notif-widget', ['iconClass' => 'topbar-icon-btn'])
                <form method="POST" action="{{ route('logout') }}" style="display:inline">
                    @csrf
                    <button type="submit" class="topbar-icon-btn" title="Logout">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
        <div class="content">
            @yield('content')
        </div>
    </div>

    @if (class_exists('Livewire\Livewire'))
        @livewireScripts
    @endif
    <script>
        // Responsive sidebar drawer toggle
        (function(){
            var hamburger = document.getElementById('hamburgerBtn');
            var sidebar   = document.getElementById('sidebar');
            var overlay   = document.getElementById('sidebarOverlay');

            function toggleSidebar(e) {
                if (e) e.stopPropagation();
                if (sidebar) sidebar.classList.toggle('open');
                if (overlay) overlay.classList.toggle('active');
            }

            function closeSidebar() {
                if (sidebar) sidebar.classList.remove('open');
                if (overlay) overlay.classList.remove('active');
            }

            if (hamburger) hamburger.addEventListener('click', toggleSidebar);
            if (overlay)   overlay.addEventListener('click', closeSidebar);

            // Close on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar && sidebar.classList.contains('open')) {
                    closeSidebar();
                }
            });

            // Auto-close when clicking links inside sidebar on mobile
            if (sidebar) {
                sidebar.querySelectorAll('a.nav-item, a.sidebar-cta-btn').forEach(function(link){
                    link.addEventListener('click', function(){
                        if (window.innerWidth <= 960) {
                            closeSidebar();
                        }
                    });
                });
            }
        })();

        // ══════════════════════════════════════════════════════════════
        // CONSOLIDATED THEME TOGGLE - Bug #2 Fix
        // Uses global __sipbarTheme from head script
        // Supports: Manual toggle, keyboard shortcut, Livewire navigation
        // ══════════════════════════════════════════════════════════════
        (function(){
            var html = document.documentElement;
            var sun  = document.getElementById('iconSun');
            var moon = document.getElementById('iconMoon');
            var btn  = document.getElementById('themeBtn');
            
            // Use consolidated theme manager from head
            var themeManager = window.__sipbarTheme || { 
                current: 'dark', 
                key: 'sipbar-superadmin-theme' 
            };

            // ── Apply theme to UI elements ──
            function applyTheme(theme) {
                var isLight = (theme === 'light');
                
                // Update HTML class
                if (isLight) {
                    html.classList.add('light');
                } else {
                    html.classList.remove('light');
                }
                
                // Update icons
                if (sun && moon) {
                    sun.style.display  = isLight ? 'block' : 'none';
                    moon.style.display = isLight ? 'none' : 'block';
                }
                
                // Update button title
                if (btn) {
                    btn.title = isLight ? 'Mode Gelap (Alt+D)' : 'Mode Terang (Alt+D)';
                }
                
                // Update global state
                themeManager.current = theme;
                
                // Debug log (remove in production)
                console.log('[Theme] Applied:', theme);
            }

            // ── Toggle theme ──
            function toggleTheme() {
                var newTheme = (themeManager.current === 'light') ? 'dark' : 'light';

                // Save to both localStorage keys for consistency
                localStorage.setItem(themeManager.key, newTheme);
                localStorage.setItem('sipbar-dash-theme', newTheme);

                // Apply immediately
                applyTheme(newTheme);

                // Visual feedback
                if (btn) {
                    btn.style.transform = 'rotate(20deg) scale(.85)';
                    setTimeout(function(){ btn.style.transform = ''; }, 250);
                }

                console.log('[Theme] Toggled to:', newTheme);
            }

            // ── Initialize theme on page load ──
            function initTheme() {
                // Get theme from localStorage with system preference fallback
                var saved = localStorage.getItem(themeManager.key);
                var initialTheme;
                
                if (saved === 'light' || saved === 'dark') {
                    initialTheme = saved;
                } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
                    initialTheme = 'light';
                } else {
                    initialTheme = 'dark';
                }
                
                applyTheme(initialTheme);
                console.log('[Theme] Initialized:', initialTheme);
            }

            // ── Event Listeners ──
            
            // Click event
            if (btn) {
                btn.addEventListener('click', toggleTheme);
            }
            
            // Keyboard shortcut (Alt+D)
            document.addEventListener('keydown', function(e) {
                if (e.altKey && e.key === 'd') {
                    e.preventDefault();
                    toggleTheme();
                }
            });

            // ── Livewire Navigation Support ──
            // Re-apply theme after Livewire navigates to new page
            document.addEventListener('livewire:navigated', function() {
                console.log('[Theme] Livewire navigated, re-applying theme');
                initTheme();
            });

            // Turbo/Turbolinks support (if used)
            document.addEventListener('turbo:load', function() {
                console.log('[Theme] Turbo loaded, re-applying theme');
                initTheme();
            });

            // ── System Preference Change Listener ──
            // Auto-switch if user changes system dark mode preference
            if (window.matchMedia) {
                var darkModeQuery = window.matchMedia('(prefers-color-scheme: dark)');
                
                // Only auto-switch if user hasn't explicitly set preference
                darkModeQuery.addEventListener('change', function(e) {
                    var saved = localStorage.getItem(themeManager.key);
                    
                    // Only auto-switch if no explicit preference saved
                    if (!saved || saved === '') {
                        var newTheme = e.matches ? 'dark' : 'light';
                        console.log('[Theme] System preference changed to:', newTheme);
                        applyTheme(newTheme);
                    }
                });
            }

            // ── Initialize on DOMContentLoaded (backup) ──
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initTheme);
            } else {
                initTheme();
            }
        })();
    </script>
</body>
</html>