<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa – SIPBAR</title>
    {{-- Anti-flash --}}
    <script>
    (function(){
        var s=localStorage.getItem('sipbar-siswa-theme');
        var d=window.matchMedia('(prefers-color-scheme: dark)').matches;
        if(s==='dark'||(s===null&&d)) document.documentElement.classList.add('dark');
    })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; -webkit-font-smoothing: antialiased; }

        /* ══════ LIGHT MODE TOKENS (default) ══════ */
        :root {
            --bg:          #f0f7ff;
            --bg2:         #ffffff;
            --bg3:         #e2eeff;
            --card:        #ffffff;
            --border:      #dbeafe;
            --border2:     #e2e8f0;
            --text:        #0f172a;
            --text2:       #1e293b;
            --muted:       #64748b;
            --subtle:      #94a3b8;
            --topbar-bg:   #ffffff;
            --topbar-bdr:  #e2e8f0;
            --content-bg:  #f0f7ff;
            --panel-bg:    #ffffff;
            --input-bg:    #f8fafc;
            --scrollbar:   #cbd5e1;
            --sidebar-bg:  #1d4ed8;
        }

        /* ══════ DARK MODE TOKENS ══════ */
        html.dark {
            --bg:          #0d1117;
            --bg2:         #111827;
            --bg3:         #1a2535;
            --card:        #111827;
            --border:      #1e3a5f;
            --border2:     #1e293b;
            --text:        #f1f5f9;
            --text2:       #e2e8f0;
            --muted:       #94a3b8;
            --subtle:      #64748b;
            --topbar-bg:   #0d1117;
            --topbar-bdr:  #1e293b;
            --content-bg:  #080e18;
            --panel-bg:    #111827;
            --input-bg:    #0d1117;
            --scrollbar:   #334155;
            --sidebar-bg:  #162040;
        }

        body { display: flex; background: var(--content-bg); color: var(--text); overflow: hidden; transition: background .25s, color .25s; }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 200px; flex-shrink: 0;
            background: var(--sidebar-bg);
            display: flex; flex-direction: column;
            height: 100vh; position: fixed; left: 0; top: 0; z-index: 40;
            transition: background .25s, transform .3s;
        }
        .sidebar-brand {
            display: flex; align-items: center; gap: 10px;
            padding: 22px 20px 18px; border-bottom: 1px solid rgba(255,255,255,.12);
            text-decoration: none;
        }
        .brand-icon {
            width: 32px; height: 32px; background: rgba(255,255,255,.2);
            border-radius: 8px; display: flex; align-items: center; justify-content: center;
        }
        .brand-name { font-size: 15px; font-weight: 800; color: #fff; }
        .brand-sub  { font-size: 10px; color: rgba(255,255,255,.6); }

        .sidebar-nav { flex: 1; padding: 14px 12px; overflow-y: auto; }
        .sidebar-nav::-webkit-scrollbar { width: 0; }

        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 10px;
            font-size: 13px; font-weight: 500; color: rgba(255,255,255,.65);
            text-decoration: none; margin-bottom: 3px;
            transition: all .15s;
        }
        .nav-item:hover { background: rgba(255,255,255,.12); color: #fff; }
        .nav-item.active { background: rgba(255,255,255,.2); color: #fff; font-weight: 700; }
        .nav-icon { width: 16px; height: 16px; flex-shrink: 0; }
        .nav-badge {
            margin-left: auto; background: #fff; color: #1d4ed8;
            font-size: 10px; font-weight: 800;
            padding: 1px 7px; border-radius: 999px;
        }

        .sidebar-footer {
            padding: 14px; border-top: 1px solid rgba(255,255,255,.12);
        }
        .user-card {
            display: flex; align-items: center; gap: 9px;
            padding: 8px 10px; border-radius: 10px;
            background: rgba(255,255,255,.1); cursor: pointer;
        }
        .user-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: rgba(255,255,255,.25);
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; color: #fff; flex-shrink: 0;
        }
        .user-name { font-size: 12px; font-weight: 700; color: #fff; line-height: 1.2; }
        .user-role { font-size: 10px; color: rgba(255,255,255,.6); }

        .logout-btn {
            display: flex; align-items: center; gap: 9px;
            padding: 9px 12px; border-radius: 10px; margin-top: 8px;
            font-size: 13px; font-weight: 500; color: rgba(255,255,255,.65);
            cursor: pointer; text-decoration: none;
            transition: all .15s; background: none; border: none; width: 100%;
        }
        .logout-btn:hover { background: rgba(255,255,255,.12); color: #fff; }

        /* ===== MAIN ===== */
        .main { margin-left: 200px; flex: 1; height: 100vh; display: flex; flex-direction: column; overflow: hidden; }

        /* ===== TOPBAR ===== */
        .topbar {
            height: 58px; background: var(--topbar-bg);
            border-bottom: 1px solid var(--topbar-bdr);
            display: flex; align-items: center;
            padding: 0 24px; gap: 12px; flex-shrink: 0;
            transition: background .25s;
        }
        .topbar-search {
            display: flex; align-items: center; gap: 8px;
            background: var(--input-bg); border: 1px solid var(--border2);
            border-radius: 10px; padding: 7px 14px; width: 260px;
        }
        .topbar-search input {
            background: none; border: none; outline: none;
            font-size: 13px; color: var(--text); width: 100%;
        }
        .topbar-search input::placeholder { color: var(--subtle); }
        .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 10px; }
        .topbar-icon {
            width: 36px; height: 36px; background: var(--bg3);
            border: 1px solid var(--border2); border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--muted); transition: all .15s; position: relative;
        }
        .topbar-icon:hover { background: var(--border2); color: var(--text); }
        .notif-dot {
            position: absolute; top: 7px; right: 8px;
            width: 7px; height: 7px; background: #ef4444;
            border-radius: 50%; border: 2px solid var(--topbar-bg);
            transition: border-color .25s;
        }
        .topbar-user {
            display: flex; align-items: center; gap: 8px;
            padding: 4px 10px 4px 4px;
            background: var(--bg3); border: 1px solid var(--border2);
            border-radius: 999px; cursor: pointer;
        }
        .topbar-avatar {
            width: 30px; height: 30px; border-radius: 50%;
            background: linear-gradient(135deg, #1d4ed8, #06b6d4);
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700; color: #fff;
        }
        .topbar-uname { font-size: 13px; font-weight: 600; color: var(--text2); }
        /* Theme toggle icon visibility */
        .t-sun { display: none }
        .t-moon { display: block }
        html.dark .t-sun  { display: block }
        html.dark .t-moon { display: none }
    </style>
</head>
<body>
    <style>
        /* ===== CONTENT LAYOUT ===== */
        .content { flex: 1; overflow-y: auto; padding: 22px 24px; background: var(--content-bg); transition: background .25s; }
        .content::-webkit-scrollbar { width: 4px; }
        .content::-webkit-scrollbar-thumb { background: var(--scrollbar); border-radius: 2px; }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 280px;
            gap: 18px;
        }

        /* ===== HERO CARD ===== */
        .hero-card {
            background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 60%, #06b6d4 100%);
            border-radius: 18px; padding: 24px 28px;
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 18px; overflow: hidden; position: relative;
        }
        .hero-card::before {
            content: ''; position: absolute;
            top: -40px; right: 100px;
            width: 160px; height: 160px;
            background: rgba(255,255,255,.06); border-radius: 50%;
        }
        .hero-title { font-size: 22px; font-weight: 800; color: #fff; margin-bottom: 4px; }
        .hero-sub   { font-size: 13px; color: rgba(255,255,255,.75); margin-bottom: 14px; }
        .hero-link  {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 12px; font-weight: 700; color: #fff;
            background: rgba(255,255,255,.2); padding: 6px 14px;
            border-radius: 999px; text-decoration: none;
            transition: background .2s;
        }
        .hero-link:hover { background: rgba(255,255,255,.3); }
        .hero-illustration {
            width: 110px; height: 90px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
        }

        /* ===== STAT CARDS ===== */
        .stat-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 18px; }
        .stat-card {
            background: var(--card); border-radius: 14px; padding: 18px 20px;
            border: 1px solid var(--border2); transition: box-shadow .2s, transform .2s;
        }
        .stat-card:hover { box-shadow: 0 8px 24px rgba(29,78,216,.1); transform: translateY(-2px); }
        .stat-card-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
        .stat-icon {
            width: 40px; height: 40px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .stat-num   { font-size: 26px; font-weight: 800; color: var(--text); line-height: 1; }
        .stat-label { font-size: 12px; color: var(--muted); margin-top: 4px; }
        .stat-badge {
            font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 999px;
        }

        /* ===== PERFORMANCE ===== */
        .panel {
            background: var(--panel-bg); border-radius: 16px;
            border: 1px solid var(--border2); padding: 18px 20px;
            margin-bottom: 18px;
        }
        .panel:last-child { margin-bottom: 0; }
        .panel-header {
            display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;
        }
        .panel-title { font-size: 14px; font-weight: 700; color: var(--text); }
        .panel-action { font-size: 12px; color: #2563eb; font-weight: 600; text-decoration: none; cursor: pointer; }

        /* Bar chart */
        .bar-chart { display: flex; align-items: flex-end; gap: 6px; height: 80px; margin-bottom: 10px; }
        .bar-col { display: flex; flex-direction: column; align-items: center; gap: 4px; flex: 1; }
        .bar-fill { width: 100%; border-radius: 4px 4px 0 0; min-height: 4px; transition: height .4s; }
        .bar-lbl { font-size: 9px; color: #94a3b8; text-align: center; line-height: 1.2; }

        /* Donut */
        .donut-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
        .donut-item { display: flex; flex-direction: column; align-items: center; gap: 6px; }
        .donut-svg { width: 60px; height: 60px; }
        .donut-val { font-size: 14px; font-weight: 800; color: #0f172a; }
        .donut-lbl { font-size: 11px; color: #64748b; text-align: center; line-height: 1.3; }

        /* ===== LINKED LIST (Barang Dipinjam) ===== */
        .borrow-item {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 0; border-bottom: 1px solid #f1f5f9;
        }
        .borrow-item:last-child { border-bottom: none; }
        .borrow-icon {
            width: 38px; height: 38px; border-radius: 10px;
            background: #eff6ff; display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .borrow-name { font-size: 13px; font-weight: 700; color: #0f172a; }
        .borrow-cat  { font-size: 11px; color: #64748b; }
        .borrow-actions { margin-left: auto; display: flex; gap: 6px; }
        .borrow-btn {
            width: 28px; height: 28px; border-radius: 7px;
            background: #f1f5f9; border: 1px solid #e2e8f0;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: #64748b; transition: all .15s;
        }
        .borrow-btn:hover { background: #1d4ed8; color: #fff; border-color: #1d4ed8; }
        .borrow-due { font-size: 11px; color: #94a3b8; white-space: nowrap; }

        /* ===== RIGHT PANEL ===== */
        .right-col { display: flex; flex-direction: column; gap: 16px; }

        /* Calendar / Schedule */
        .schedule-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
        .today-badge {
            font-size: 11px; font-weight: 700; color: #1d4ed8;
            background: #eff6ff; padding: 3px 10px; border-radius: 999px;
        }
        .schedule-item {
            background: #fff; border-radius: 12px;
            border: 1px solid #e2e8f0; padding: 12px 14px;
            margin-bottom: 8px;
        }
        .schedule-item.active { background: #1d4ed8; border-color: #1d4ed8; }
        .schedule-item.active .sch-name { color: #fff; }
        .schedule-item.active .sch-meta { color: rgba(255,255,255,.7); }
        .sch-name { font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 3px; }
        .sch-meta { font-size: 11px; color: #64748b; display: flex; align-items: center; gap: 4px; }
        .sch-dot  { width: 6px; height: 6px; background: #1d4ed8; border-radius: 50%; }
        .schedule-item.active .sch-dot { background: rgba(255,255,255,.7); }

        /* Upcoming Events */
        .event-item { display: flex; align-items: flex-start; gap: 10px; padding: 10px 0; border-bottom: 1px solid #f1f5f9; }
        .event-item:last-child { border-bottom: none; }
        .event-icon {
            width: 36px; height: 36px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .event-name { font-size: 13px; font-weight: 600; color: #0f172a; line-height: 1.35; margin-bottom: 3px; }
        .event-date { font-size: 11px; color: #94a3b8; }
        .event-more { margin-left: auto; color: #94a3b8; cursor: pointer; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1100px) {
            .content-grid { grid-template-columns: 1fr; }
            .right-col { display: grid; grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 800px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .stat-row { grid-template-columns: 1fr 1fr; }
            .right-col { display: flex; flex-direction: column; }
        }
        @media (max-width: 500px) {
            .stat-row { grid-template-columns: 1fr; }
            .donut-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>

{{-- ===== SIDEBAR ===== --}}
<aside class="sidebar" id="sidebar">
    <a href="{{ route('student.dashboard') }}" class="sidebar-brand">
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

    <nav class="sidebar-nav">
        @php
        $navItems = [
            ['Dashboard',       'student.dashboard', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', null],
            ['Katalog Barang',  'student.catalog',  'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',     null],
            ['Peminjaman',      'student.loans',    'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4',                      '3'],
            ['Riwayat',         'student.history',  'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',                             null],
            ['Pengumuman',      'student.announcements', 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', '2'],
            ['Profil',          'profile.edit',     'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',   null],
        ];
        @endphp
        @foreach($navItems as $nav)
        <a href="{{ route($nav[1]) }}" class="nav-item {{ request()->routeIs($nav[1]) ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $nav[2] }}"/>
            </svg>
            {{ $nav[0] }}
            @if($nav[3])
            <span class="nav-badge">{{ $nav[3] }}</span>
            @endif
        </a>
        @endforeach
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">{{ auth()->check() ? strtoupper(substr(auth()->user()->name,0,2)) : 'SI' }}</div>
            <div>
                <div class="user-name">{{ auth()->check() ? auth()->user()->name : 'Siswa' }}</div>
                <div class="user-role">Peminjam</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Log Out
            </button>
        </form>
    </div>
</aside>

{{-- ===== MAIN ===== --}}
<div class="main">
    {{-- TOPBAR --}}
    <div class="topbar">
        <div class="topbar-search">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;color:#94a3b8;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" placeholder="Cari barang atau riwayat...">
        </div>
        <div class="topbar-right">
            <div class="topbar-icon" style="position:relative">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <div class="topbar-icon" style="position:relative">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                <div class="notif-dot"></div>
            </div>
            <div class="topbar-user">
                <div class="topbar-avatar">{{ auth()->check() ? strtoupper(substr(auth()->user()->name,0,2)) : 'SI' }}</div>
                <div class="topbar-uname">{{ auth()->check() ? explode(' ',auth()->user()->name)[0] : 'Siswa' }}</div>
                <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;color:#94a3b8;margin-left:2px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="content">
        <div class="content-grid">

            {{-- LEFT COLUMN --}}
            <div>
                {{-- Hero Card --}}
                <div class="hero-card">
                    <div>
                        <div class="hero-title">Halo, {{ auth()->check() ? explode(' ',auth()->user()->name)[0] : 'Siswa' }}! 👋</div>
                        <div class="hero-sub">Kamu memiliki <strong style="color:#fff">3 peminjaman aktif</strong>. Segera kembalikan tepat waktu!</div>
                        <a href="#" class="hero-link">Lihat Peminjaman
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                    <div class="hero-illustration">
                        <svg viewBox="0 0 110 90" style="width:100%;height:100%">
                            <rect x="15" y="45" width="80" height="40" rx="6" fill="rgba(255,255,255,.15)"/>
                            <rect x="25" y="30" width="60" height="35" rx="5" fill="rgba(255,255,255,.25)"/>
                            <rect x="32" y="37" width="46" height="4" rx="2" fill="rgba(255,255,255,.5)"/>
                            <rect x="32" y="44" width="30" height="3" rx="1.5" fill="rgba(255,255,255,.35)"/>
                            <rect x="32" y="50" width="38" height="3" rx="1.5" fill="rgba(255,255,255,.35)"/>
                            <circle cx="75" cy="22" r="14" fill="rgba(255,255,255,.2)"/>
                            <path d="M68 22 L72 26 L82 18" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                            <rect x="40" y="68" width="30" height="3" rx="1.5" fill="rgba(255,255,255,.2)"/>
                        </svg>
                    </div>
                </div>

                {{-- Stat Row --}}
                <div class="stat-row">
                    @php
                    $stats = [
                        ['3',  'Dipinjam Aktif',   '#1d4ed8', '#eff6ff', 'Barang',  'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                        ['12', 'Total Riwayat',    '#059669', '#ecfdf5', 'Pinjaman','M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['1',  'Hampir Jatuh Tempo','#f59e0b', '#fffbeb', 'Hari Ini','M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
                    ];
                    @endphp
                    @foreach($stats as $s)
                    <div class="stat-card">
                        <div class="stat-card-top">
                            <div class="stat-icon" style="background:{{ $s[3] }}">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:{{ $s[2] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s[5] }}"/>
                                </svg>
                            </div>
                            <span class="stat-badge" style="background:{{ $s[3] }};color:{{ $s[2] }}">{{ $s[4] }}</span>
                        </div>
                        <div class="stat-num" style="color:{{ $s[2] }}">{{ $s[0] }}</div>
                        <div class="stat-label">{{ $s[1] }}</div>
                    </div>
                    @endforeach
                </div>

                {{-- Performance: Bar Chart --}}
                <div class="panel">
                    <div class="panel-header">
                        <div class="panel-title">Aktivitas Peminjaman</div>
                        <select style="font-size:12px;border:1px solid #e2e8f0;border-radius:8px;padding:4px 10px;color:#475569;outline:none;cursor:pointer">
                            <option>6 Bulan Terakhir</option>
                            <option>Tahun Ini</option>
                        </select>
                    </div>
                    @php
                    $barData = [
                        ['Jan',2,40],['Feb',1,20],['Mar',3,60],['Apr',2,40],['Mei',4,80],['Jun',3,60],
                    ];
                    @endphp
                    <div class="bar-chart">
                        @foreach($barData as $b)
                        <div class="bar-col">
                            <div style="font-size:10px;color:#64748b;margin-bottom:3px">{{ $b[1] }}</div>
                            <div class="bar-fill" style="height:{{ $b[2] }}px;background:{{ $b[2] >= 80 ? '#1d4ed8' : ($b[2] >= 60 ? '#60a5fa' : '#bfdbfe') }}"></div>
                            <div class="bar-lbl">{{ $b[0] }}</div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Donut charts --}}
                    <div style="border-top:1px solid #f1f5f9;padding-top:14px;margin-top:4px">
                        <div style="font-size:12px;font-weight:700;color:#475569;margin-bottom:12px">Kategori Pinjaman</div>
                        <div class="donut-grid">
                            @php
                            $donuts = [
                                ['Elektronik', 75, '#1d4ed8'],
                                ['Furnitur',   15, '#06b6d4'],
                                ['Olahraga',   10, '#f59e0b'],
                            ];
                            @endphp
                            @foreach($donuts as $d)
                            <div class="donut-item">
                                <svg class="donut-svg" viewBox="0 0 42 42">
                                    <circle cx="21" cy="21" r="15.915" fill="none" stroke="#f1f5f9" stroke-width="5"/>
                                    <circle cx="21" cy="21" r="15.915" fill="none" stroke="{{ $d[2] }}" stroke-width="5"
                                        stroke-dasharray="{{ $d[1] }} {{ 100 - $d[1] }}"
                                        stroke-dashoffset="25" stroke-linecap="round"/>
                                    <text x="21" y="25" text-anchor="middle" font-size="8" font-weight="800" fill="#0f172a">{{ $d[1] }}%</text>
                                </svg>
                                <div class="donut-lbl">{{ $d[0] }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Barang Sedang Dipinjam --}}
                <div class="panel">
                    <div class="panel-header">
                        <div class="panel-title">Barang Sedang Dipinjam</div>
                        <a href="#" class="panel-action">Lihat Semua</a>
                    </div>
                    @php
                    $borrowed = [
                        ['Proyektor Epson EB-S41', 'Elektronik',  'Kembalikan: 5 Agu 2026',  'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', '#eff6ff','#1d4ed8'],
                        ['Laptop Dell Inspiron',   'Elektronik',  'Kembalikan: 7 Agu 2026',  'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', '#eff6ff','#1d4ed8'],
                        ['Bola Basket Spalding',   'Olahraga',    'Kembalikan: 4 Agu 2026',  'M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0', '#fffbeb','#f59e0b'],
                    ];
                    @endphp
                    @foreach($borrowed as $b)
                    <div class="borrow-item">
                        <div class="borrow-icon" style="background:{{ $b[4] }}">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:{{ $b[5] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $b[3] }}"/>
                            </svg>
                        </div>
                        <div>
                            <div class="borrow-name">{{ $b[0] }}</div>
                            <div class="borrow-cat">{{ $b[1] }}</div>
                        </div>
                        <div style="margin-left:auto;display:flex;flex-direction:column;align-items:flex-end;gap:6px">
                            <div class="borrow-due" style="color:{{ str_contains($b[2],'4 Agu') ? '#f59e0b' : '#94a3b8' }}">{{ $b[2] }}</div>
                            <div style="display:flex;gap:5px">
                                <div class="borrow-btn" title="Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </div>
                                <div class="borrow-btn" title="Perpanjang">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- RIGHT COLUMN --}}
            <div class="right-col">
                {{-- Jadwal Pengembalian --}}
                <div class="panel">
                    <div class="schedule-header">
                        <div class="panel-title">Jadwal Pengembalian</div>
                        <span class="today-badge">Hari ini</span>
                    </div>
                    <div class="schedule-item active">
                        <div class="sch-name">Proyektor Epson</div>
                        <div class="sch-meta">
                            <div class="sch-dot"></div>
                            Batas: 5 Agu 2026, 12:00
                        </div>
                    </div>
                    <div class="schedule-item">
                        <div class="sch-name">Laptop Dell Inspiron</div>
                        <div class="sch-meta">
                            <div class="sch-dot"></div>
                            Batas: 7 Agu 2026, 14:00
                        </div>
                    </div>
                    <div class="schedule-item">
                        <div class="sch-name">Bola Basket Spalding</div>
                        <div class="sch-meta">
                            <div class="sch-dot" style="background:#f59e0b"></div>
                            <span style="color:#f59e0b;font-weight:600">Jatuh Tempo Hari Ini!</span>
                        </div>
                    </div>
                </div>

                {{-- Status Peminjaman (mini donut) --}}
                <div class="panel">
                    <div class="panel-header">
                        <div class="panel-title">Status Peminjaman</div>
                    </div>
                    <div style="display:flex;align-items:center;justify-content:center;margin:8px 0 16px">
                        <svg viewBox="0 0 42 42" style="width:100px;height:100px">
                            <circle cx="21" cy="21" r="15.915" fill="none" stroke="#f1f5f9" stroke-width="6"/>
                            <circle cx="21" cy="21" r="15.915" fill="none" stroke="#1d4ed8" stroke-width="6"
                                stroke-dasharray="60 40" stroke-dashoffset="25" stroke-linecap="round"/>
                            <circle cx="21" cy="21" r="15.915" fill="none" stroke="#06b6d4" stroke-width="6"
                                stroke-dasharray="25 75" stroke-dashoffset="-35" stroke-linecap="round"/>
                            <circle cx="21" cy="21" r="15.915" fill="none" stroke="#f59e0b" stroke-width="6"
                                stroke-dasharray="15 85" stroke-dashoffset="-60" stroke-linecap="round"/>
                            <text x="21" y="24" text-anchor="middle" font-size="7" font-weight="800" fill="#0f172a">12 Total</text>
                        </svg>
                    </div>
                    @foreach([['Sedang Dipinjam','3','#1d4ed8','#eff6ff'],['Sudah Kembali','8','#059669','#ecfdf5'],['Terlambat','1','#f59e0b','#fffbeb']] as $st)
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:7px 0;border-bottom:1px solid #f8fafc">
                        <div style="display:flex;align-items:center;gap:8px">
                            <div style="width:8px;height:8px;border-radius:50%;background:{{ $st[2] }}"></div>
                            <span style="font-size:12px;color:#475569">{{ $st[0] }}</span>
                        </div>
                        <span style="font-size:13px;font-weight:800;color:{{ $st[2] }}">{{ $st[1] }}</span>
                    </div>
                    @endforeach
                </div>

                {{-- Upcoming: Pengumuman --}}
                <div class="panel">
                    <div class="panel-header">
                        <div class="panel-title">Pengumuman</div>
                        <a href="#" class="panel-action">Lihat semua</a>
                    </div>
                    @php
                    $events = [
                        ['Peminjaman Buku Paket Dibuka','Mulai 5 Agu 2026 – Kunjungi perpustakaan','#eff6ff','#1d4ed8','M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13'],
                        ['Libur Nasional 17 Agustus','Pengembalian barang diperpanjang otomatis','#ecfdf5','#059669','M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        ['Stok Proyektor Terbatas','Hanya tersisa 2 unit — ajukan sekarang!','#fffbeb','#f59e0b','M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
                    ];
                    @endphp
                    @foreach($events as $ev)
                    <div class="event-item">
                        <div class="event-icon" style="background:{{ $ev[2] }}">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:{{ $ev[3] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $ev[4] }}"/>
                            </svg>
                        </div>
                        <div style="flex:1;min-width:0">
                            <div class="event-name">{{ $ev[0] }}</div>
                            <div class="event-date">{{ $ev[1] }}</div>
                        </div>
                        <div class="event-more">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>{{-- /content-grid --}}
    </div>{{-- /content --}}
</div>{{-- /main --}}

<script>
    window.addEventListener('resize', () => {
        if (window.innerWidth > 800) document.getElementById('sidebar').classList.remove('open');
    });
</script>
</body>
</html>
