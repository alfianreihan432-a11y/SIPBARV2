<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIPBAR Admin')</title>
    {{-- Anti-flash: apply theme BEFORE CSS renders --}}
    <script>
    (function(){
        var s=localStorage.getItem('sipbar-dash-theme');
        if(s==='light') document.documentElement.classList.add('light');
        else document.documentElement.classList.remove('light');
    })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if (class_exists('Livewire\Livewire'))
        @livewireStyles
    @endif
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        
        /* ══════ DARK MODE TOKENS (default) ══════ */
        :root {
            --bg-main: #0f172a;
            --bg-alt: #1e293b;
            --bg-card: #1e293b;
            --bg-card-subtle: #0f172a;
            --bg-hover: #334155;
            --border-main: #1e293b;
            --border-alt: #334155;
            --border-subtle: rgba(148,163,184,0.14);
            --text-primary: #f1f5f9;
            --text-secondary: #e2e8f0;
            --text-muted: #94a3b8;
            --text-subtle: #475569;
            --blue: #3b82f6;
            --blue-dark: #1d4ed8;
            --sidebar-bg: #0f172a;
            --topbar-bg: #0f172a;
            --content-bg: #0a1020;
            --scrollbar: #334155;
            --notif-border: #0f172a;
            --table-head-bg: #060c18;
            --table-hover: rgba(30,41,59,.6);
            --input-bg: #1e293b;
            --input-border: rgba(148,163,184,0.14);
            --hero-bg: #0f172a;
            --card-shadow: 0 4px 20px rgba(0,0,0,0.25);
        }

        /* ══════ LIGHT MODE TOKENS ══════ */
        html.light {
            --bg-main: #f8fafc;
            --bg-alt: #ffffff;
            --bg-card: #ffffff;
            --bg-card-subtle: #f1f5f9;
            --bg-hover: #e2e8f0;
            --border-main: #e2e8f0;
            --border-alt: #cbd5e1;
            --border-subtle: #cbd5e1;
            --text-primary: #0f172a;
            --text-secondary: #1e293b;
            --text-muted: #475569;
            --text-subtle: #64748b;
            --blue: #2563eb;
            --blue-dark: #1d4ed8;
            --topbar-bg: #ffffff;
            --sidebar-bg: #ffffff;
            --content-bg: #f1f5f9;
            --scrollbar: #cbd5e1;
            --notif-border: #ffffff;
            --table-head-bg: #f8fafc;
            --table-hover: #f1f5f9;
            --input-bg: #ffffff;
            --input-border: #cbd5e1;
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
            gap: 10px;
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--border-main);
            text-decoration: none;
            color: var(--text-primary);
        }
        .brand-icon {
            width: 34px;
            height: 34px;
            background: var(--blue-dark);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .brand-name { font-size: 14px; font-weight: 700; color: var(--text-primary); line-height: 1.2; }
        .brand-sub { font-size: 10px; color: var(--text-muted); line-height: 1.2; }
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
        .nav-item.active { background: var(--blue-dark); color: #fff; box-shadow: 0 4px 12px rgba(29,78,216,.4); }
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
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            border-radius: 10px;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(29,78,216,.4);
            transition: all .2s;
        }
        .sidebar-cta-btn:hover {
            background: var(--blue);
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

        @media(max-width: 960px) {
            .sidebar { 
                position: fixed; 
                transform: translateX(-100%);
                width: 220px;
            }
            .sidebar.open { transform: translateX(0); }
            .sidebar.collapsed { width: 220px; }
            .main { margin-left: 0; }
            .main.expanded { margin-left: 0; }
            .topbar { padding: 0 18px; }
        }

        /* ══════════════════════════════════════════════════════════════
           GLOBAL PREMIUM SELECT / DROPDOWN UPGRADE — ADMIN PANEL
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
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%233b82f6' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") !important;
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

        /* Hover state */
        select:hover,
        .im-select:hover,
        .im-select-field:hover {
            border-color: var(--blue) !important;
            box-shadow: 0 0 0 2px rgba(59,130,246,0.1) !important;
        }

        /* Focus state — glowing blue ring */
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
    <aside class="sidebar" id="sidebar">
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            <div class="brand-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 42" style="width:18px;height:18px;fill:#fff">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M17.2 5.633 8.6.855 0 5.633v26.51l16.2 9 16.2-9v-8.442l7.6-4.223V9.856l-8.6-4.777-8.6 4.777V18.3l-5.6 3.111V5.633Z"/>
                </svg>
            </div>
            <div>
                <div class="brand-name">SIPBAR</div>
                <div class="brand-sub">Sistem Inventaris</div>
            </div>
        </a>

        <div class="sidebar-search">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;color:var(--text-muted);flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" placeholder="Cari di sini...">
        </div>

        <nav class="sidebar-nav">
            <div class="nav-group-label">Menu Utama</div>

            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>

            <a href="{{ route('inventory.index') }}" class="nav-item {{ request()->routeIs('inventory.index') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Barang
                <span class="nav-badge">{{ \App\Models\Item::count() }}</span>
            </a>

            <a href="{{ route('kelola-barang.index') }}" class="nav-item {{ request()->routeIs('kelola-barang.index') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Kelola Barang
            </a>

            <a href="{{ route('categories.index') }}" class="nav-item {{ request()->routeIs('categories.index') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Kategori
            </a>

            <a href="{{ route('loans.index') }}" class="nav-item {{ request()->routeIs('loans.index') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                Peminjaman
                @if(\App\Models\Borrowing::where('status','pending')->count() > 0)
                <span class="nav-badge">{{ \App\Models\Borrowing::where('status','pending')->count() }}</span>
                @endif
            </a>

            @php
                $pendingReturnCount = \App\Models\ItemReturn::where('status', 'menunggu')->count();
            @endphp
            <a href="{{ route('returns.index') }}" class="nav-item {{ request()->routeIs('returns.index', 'admin.returns.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                Pengembalian
                @if($pendingReturnCount > 0)
                <span class="nav-badge" style="background:#f59e0b;color:#0f172a;">{{ $pendingReturnCount }}</span>
                @endif
            </a>

            <div class="nav-group-label">Laporan</div>

            <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Laporan
            </a>

            <a href="{{ route('statistics.index') }}" class="nav-item {{ request()->routeIs('statistics.index') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                Statistik
            </a>

            <div class="nav-group-label">Pengaturan</div>

            <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.index') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Pengguna
            </a>

            <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.edit','security.edit','appearance.edit') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Pengaturan
            </a>
        </nav>

        <div class="sidebar-cta">
            <div class="sidebar-cta-label">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Guru / Siswa
            </div>
            <a href="{{ route('users.index') }}" class="sidebar-cta-btn">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Kelola Akun Pengguna
            </a>
        </div>

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">{{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'AD' }}</div>
                <div>
                    <div class="user-name">{{ auth()->check() ? auth()->user()->name : 'Administrator' }}</div>
                    <div class="user-role">Administrator</div>
                </div>
                <div class="user-caret">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>
        </div>
    </aside>

    <div class="main">
        <div class="topbar">
            <button class="topbar-icon-btn" onclick="document.getElementById('sidebar').classList.toggle('open')" style="display:none" id="hamburgerBtn">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
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
                <div class="topbar-icon-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div class="topbar-icon-btn notif-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <div class="notif-dot"></div>
                </div>
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
        // Responsive hamburger
        function checkWidth() {
            const btn = document.getElementById('hamburgerBtn');
            if (window.innerWidth <= 960) {
                btn.style.display = 'flex';
            } else {
                btn.style.display = 'none';
                document.getElementById('sidebar').classList.remove('open');
            }
        }
        checkWidth();
        window.addEventListener('resize', checkWidth);

        // Close sidebar on outside click (mobile)
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const btn = document.getElementById('hamburgerBtn');
            if (window.innerWidth <= 960 && sidebar.classList.contains('open')) {
                if (!sidebar.contains(e.target) && !btn.contains(e.target)) {
                    sidebar.classList.remove('open');
                }
            }
        });

        // ── THEME TOGGLE ──
        (function(){
            var KEY = 'sipbar-dash-theme';
            var html = document.documentElement;
            var sun  = document.getElementById('iconSun');
            var moon = document.getElementById('iconMoon');
            var btn  = document.getElementById('themeBtn');

            function applyTheme(isLight) {
                if (isLight) {
                    html.classList.add('light');
                    if(sun)  sun.style.display  = 'block';
                    if(moon) moon.style.display = 'none';
                    if(btn)  btn.title = 'Mode Gelap (Alt+D)';
                } else {
                    html.classList.remove('light');
                    if(sun)  sun.style.display  = 'none';
                    if(moon) moon.style.display = 'block';
                    if(btn)  btn.title = 'Mode Terang (Alt+D)';
                }
            }

            function toggle() {
                var isLight = !html.classList.contains('light');
                localStorage.setItem(KEY, isLight ? 'light' : 'dark');
                applyTheme(isLight);
                if(btn){ btn.style.transform='rotate(20deg) scale(.85)'; setTimeout(function(){btn.style.transform=''},250); }
            }

            // Init from storage
            var saved = localStorage.getItem(KEY);
            applyTheme(saved === 'light');

            if(btn) btn.addEventListener('click', toggle);
            document.addEventListener('keydown', function(e){ if(e.altKey && e.key==='d') toggle(); });
        })();
    </script>
</body>
</html>
