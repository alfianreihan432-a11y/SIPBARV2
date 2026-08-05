<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard – SIPBAR</title>
    {{-- Anti-flash: apply theme BEFORE CSS renders --}}
    <script>
    (function(){
        var s=localStorage.getItem('sipbar-dash-theme');
        var d=window.matchMedia('(prefers-color-scheme: dark)').matches;
        if(s==='light') document.documentElement.classList.add('light');
        else if(s==='dark'||s===null) document.documentElement.classList.remove('light');
    })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; -webkit-font-smoothing: antialiased; }

        /* ══════ DARK MODE TOKENS (default) ══════ */
        :root {
            --bg:       #0f172a;
            --bg2:      #1e293b;
            --bg3:      #334155;
            --card:     #1e293b;
            --border:   #1e293b;
            --border2:  #334155;
            --text:     #f1f5f9;
            --text2:    #e2e8f0;
            --muted:    #94a3b8;
            --subtle:   #475569;
            --blue:     #3b82f6;
            --blue-d:   #1d4ed8;
            --topbar:   #0f172a;
            --sidebar:  #0f172a;
            --content:  #0a1020;
            --scrollbar:#334155;
            --notif-border: #0f172a;
        }

        /* ══════ LIGHT MODE TOKENS ══════ */
        html.light {
            --bg:       #f0f7ff;
            --bg2:      #ffffff;
            --bg3:      #e8f1fe;
            --card:     #ffffff;
            --border:   #dbeafe;
            --border2:  #e2e8f0;
            --text:     #0f172a;
            --text2:    #1e293b;
            --muted:    #64748b;
            --subtle:   #94a3b8;
            --blue:     #2563eb;
            --blue-d:   #1d4ed8;
            --topbar:   #ffffff;
            --sidebar:  #0f172a;
            --content:  #f0f7ff;
            --scrollbar:#cbd5e1;
            --notif-border: #ffffff;
        }

        body { display: flex; background: var(--content); color: var(--text); overflow: hidden; transition: background .25s, color .25s; }

        /* ========== SIDEBAR ========== */
        .sidebar {
            width: 220px; flex-shrink: 0;
            background: var(--sidebar);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            height: 100vh; position: fixed; left: 0; top: 0; z-index: 40;
            transition: transform .3s, background .25s;
        }
        .sidebar-brand {
            display: flex; align-items: center; gap: 10px;
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--border);
            text-decoration: none;
        }
        .brand-icon {
            width: 34px; height: 34px; background: var(--blue-d);
            border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .brand-name { font-size: 14px; font-weight: 700; color: #f1f5f9; line-height: 1.2; }
        .brand-sub  { font-size: 10px; color: #64748b; line-height: 1.2; }

        .sidebar-search {
            margin: 14px 14px 8px;
            display: flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1);
            border-radius: 8px; padding: 7px 10px;
        }
        .sidebar-search input {
            background: none; border: none; outline: none;
            font-size: 12px; color: #94a3b8; width: 100%;
        }
        .sidebar-search input::placeholder { color: #475569; }

        .sidebar-nav { flex: 1; overflow-y: auto; padding: 8px 10px; }
        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); border-radius: 2px; }

        .nav-group-label {
            font-size: 10px; font-weight: 700; color: rgba(255,255,255,.3);
            letter-spacing: .08em; text-transform: uppercase; padding: 14px 10px 6px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 10px; border-radius: 8px;
            font-size: 13px; font-weight: 500; color: rgba(255,255,255,.55);
            text-decoration: none; cursor: pointer;
            transition: all .15s; margin-bottom: 2px; white-space: nowrap;
        }
        .nav-item:hover { background: rgba(255,255,255,.08); color: #fff; }
        .nav-item.active { background: var(--blue-d); color: #fff; box-shadow: 0 4px 12px rgba(29,78,216,.4); }
        .nav-item.active .nav-icon { color: #fff; }
        .nav-icon { width: 16px; height: 16px; flex-shrink: 0; }
        .nav-badge {
            margin-left: auto; background: var(--blue-d); color: #fff;
            font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 999px;
        }
        .nav-item.active .nav-badge { background: rgba(255,255,255,.25); }

        .sidebar-footer { padding: 14px; border-top: 1px solid rgba(255,255,255,.08); }
        .user-card {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 10px; border-radius: 10px;
            background: rgba(255,255,255,.07); cursor: pointer;
        }
        .user-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: linear-gradient(135deg, #1d4ed8, #06b6d4);
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; color: #fff; flex-shrink: 0;
        }
        .user-name  { font-size: 12px; font-weight: 700; color: #f1f5f9; }
        .user-role  { font-size: 10px; color: #64748b; }
        .user-caret { margin-left: auto; color: #475569; }

        /* ===== SIDEBAR CTA ===== */
        .sidebar-cta {
            margin: 0 12px 12px;
            padding: 14px 16px;
            background: rgba(29,78,216,.12);
            border: 1px solid rgba(59,130,246,.25);
            border-radius: 14px;
        }
        .sidebar-cta-label {
            display: flex; align-items: center; gap: 5px;
            font-size: 10px; font-weight: 700; color: #93c5fd;
            letter-spacing: .08em; text-transform: uppercase;
            margin-bottom: 10px;
        }
        .sidebar-cta-btn {
            display: flex; align-items: center; justify-content: center; gap: 7px;
            width: 100%; padding: 10px 14px;
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            color: #fff; font-size: 12px; font-weight: 700;
            border-radius: 10px; text-decoration: none;
            box-shadow: 0 4px 12px rgba(29,78,216,.4);
            transition: all .2s;
        }
        .sidebar-cta-btn:hover {
            background: linear-gradient(135deg, #1e40af, #1d4ed8);
            box-shadow: 0 6px 16px rgba(29,78,216,.5);
            transform: translateY(-1px);
        }

        /* ========== MAIN ========== */
        .main {
            margin-left: 220px;
            flex: 1;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* ========== TOPBAR ========== */
        .topbar {
            height: 60px; background: var(--topbar);
            border-bottom: 1px solid var(--border2);
            display: flex; align-items: center;
            padding: 0 24px; gap: 12px; flex-shrink: 0;
            transition: background .25s;
        }
        .breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--muted); }
        .breadcrumb span { color: var(--muted); }
        .breadcrumb .active { color: var(--text); font-weight: 600; }
        .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 8px; }
        .topbar-icon-btn {
            width: 34px; height: 34px;
            background: var(--card); border: 1px solid var(--border2);
            border-radius: 8px; display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--muted); transition: all .15s;
        }
        .topbar-icon-btn:hover { background: var(--bg3); color: var(--text); }
        .notif-btn { position: relative; }
        .notif-dot {
            position: absolute; top: 6px; right: 7px;
            width: 7px; height: 7px; background: #ef4444;
            border-radius: 50%; border: 2px solid var(--topbar);
            transition: border-color .25s;
        }

        /* ========== CONTENT ========== */
        .content { flex: 1; overflow-y: auto; padding: 24px; background: var(--content); transition: background .25s; }
        .content::-webkit-scrollbar { width: 4px; }
        .content::-webkit-scrollbar-track { background: transparent; }
        .content::-webkit-scrollbar-thumb { background: var(--scrollbar); border-radius: 2px; }

        /* Greeting */
        .greeting-row { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; }
        .greeting-title { font-size: 26px; font-weight: 800; color: var(--text); margin-bottom: 4px; }
        .greeting-sub   { font-size: 13px; color: var(--muted); }

        /* Gauge card */
        .gauge-card {
            background: var(--card); border: 1px solid var(--border2);
            border-radius: 16px; padding: 20px 24px; text-align: center; min-width: 160px;
        }
        .gauge-title { font-size: 11px; color: var(--muted); margin-bottom: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; }
        .gauge-val   { font-size: 24px; font-weight: 800; color: var(--text); margin: 4px 0 2px; }
        .gauge-label { font-size: 11px; color: var(--muted); }

        /* ========== STAT CARDS ========== */
        .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 20px; }
        .stat-card {
            background: var(--card); border: 1px solid var(--border2);
            border-radius: 14px; padding: 18px 20px;
            display: flex; align-items: center; gap: 14px;
            transition: border-color .2s, transform .2s;
        }
        .stat-card:hover { border-color: var(--blue); transform: translateY(-2px); }
        .stat-icon-box { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .stat-num   { font-size: 22px; font-weight: 800; color: var(--text); line-height: 1; margin-bottom: 3px; }
        .stat-label { font-size: 12px; color: var(--muted); }
        .stat-change { font-size: 11px; font-weight: 600; margin-top: 3px; }
        .up   { color: #34d399; }
        .down { color: #f87171; }

        /* ========== BOTTOM GRID ========== */
        .bottom-grid { display: grid; grid-template-columns: 1fr 1fr 1.2fr; gap: 16px; margin-bottom: 20px; }

        /* Panel base */
        .panel { background: var(--card); border: 1px solid var(--border2); border-radius: 14px; padding: 18px 20px; }
        .panel-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
        .panel-title { font-size: 14px; font-weight: 700; color: var(--text); }
        .panel-more {
            background: none; border: none; cursor: pointer;
            color: var(--subtle); display: flex; align-items: center; gap: 4px;
            font-size: 12px; padding: 4px 8px; border-radius: 6px; transition: color .15s, background .15s;
        }
        .panel-more:hover { color: var(--muted); background: var(--bg3); }

        /* Transaksi list */
        .txn-item { background: var(--bg); border: 1px solid var(--border); border-radius: 10px; padding: 12px 14px; margin-bottom: 10px; }
        .txn-item:last-child { margin-bottom: 0; }
        .txn-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 6px; }
        .txn-name { font-size: 13px; font-weight: 700; color: var(--text); }
        .txn-type { font-size: 11px; color: var(--muted); margin-top: 2px; }
        .txn-link-btn {
            width: 28px; height: 28px; border-radius: 8px;
            background: var(--card); border: 1px solid var(--border2);
            display: flex; align-items: center; justify-content: center;
            color: var(--muted); flex-shrink: 0; cursor: pointer;
        }
        .txn-meta { display: flex; align-items: center; justify-content: space-between; font-size: 11px; color: var(--subtle); }
        .txn-user { display: flex; align-items: center; gap: 6px; }
        .txn-avatar { width: 20px; height: 20px; border-radius: 50%; background: linear-gradient(135deg, #1d4ed8, #06b6d4); display: flex; align-items: center; justify-content: center; font-size: 9px; font-weight: 700; color: #fff; }

        /* Chart panel */
        .chart-big-num { font-size: 32px; font-weight: 800; color: var(--text); line-height: 1; }
        .chart-big-label { font-size: 11px; color: var(--muted); margin-top: 3px; }
        .chart-svg { width: 100%; height: 110px; margin-top: 10px; }

        /* Status bars */
        .status-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-top: 4px; }
        .status-bar-item { text-align: center; }
        .bar-wrap { height: 70px; display: flex; align-items: flex-end; justify-content: center; margin-bottom: 6px; }
        .bar { width: 32px; border-radius: 6px 6px 0 0; transition: height .6s ease; }
        .bar-val   { font-size: 13px; font-weight: 800; color: var(--text); }
        .bar-label { font-size: 10px; color: var(--muted); margin-top: 2px; }

        /* Info mini cards */
        .info-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 12px; margin-bottom: 20px; }
        .info-card { background: var(--card); border: 1px solid var(--border2); border-radius: 12px; padding: 14px 16px; transition: border-color .2s; }
        .info-card:hover { border-color: var(--blue); }
        .info-card-label { font-size: 11px; color: var(--muted); margin-bottom: 6px; }
        .info-card-val   { font-size: 18px; font-weight: 800; color: var(--text); margin-bottom: 8px; }
        .info-card-link  { display: flex; align-items: center; gap: 4px; font-size: 11px; color: var(--blue); font-weight: 600; cursor: pointer; text-decoration: none; }

        /* ========== TABLE ========== */
        .table-panel { background: var(--card); border: 1px solid var(--border2); border-radius: 14px; overflow: hidden; margin-bottom: 8px; }
        .table-header-row { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid var(--bg); }
        .table-search { display: flex; align-items: center; gap: 8px; background: var(--bg); border: 1px solid var(--border2); border-radius: 8px; padding: 6px 12px; }
        .table-search input { background: none; border: none; outline: none; font-size: 12px; color: var(--muted); width: 140px; }
        .table-search input::placeholder { color: var(--subtle); }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            padding: 10px 16px; font-size: 11px; font-weight: 700; color: var(--subtle);
            text-align: left; letter-spacing: .06em; text-transform: uppercase;
            background: var(--bg); border-bottom: 1px solid var(--border);
        }
        tbody tr { border-bottom: 1px solid var(--bg); transition: background .15s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: rgba(59,130,246,.05); }
        tbody td { padding: 12px 16px; font-size: 13px; color: var(--muted); }
        .td-name { display: flex; align-items: center; gap: 10px; }
        .td-avatar { width: 32px; height: 32px; border-radius: 8px; background: var(--bg); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .td-name-text { font-size: 13px; font-weight: 600; color: var(--text); }
        .td-sub { font-size: 11px; color: var(--subtle); }
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 999px; font-size: 11px; font-weight: 600; }
        .badge-available  { background: rgba(52,211,153,.12); color: #34d399; }
        .badge-borrowed   { background: rgba(248,113,113,.12); color: #f87171; }
        .badge-maintenance{ background: rgba(251,191,36,.12);  color: #fbbf24; }
        .action-btn {
            background: none; border: 1px solid var(--border2);
            border-radius: 6px; padding: 4px 8px;
            color: #64748b; font-size: 12px; cursor: pointer;
            transition: all .15s;
        }
        .action-btn:hover { background: #334155; color: #e2e8f0; }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 1200px) {
            .info-grid { grid-template-columns: repeat(3, 1fr); }
            .bottom-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 900px) {
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
            .bottom-grid { grid-template-columns: 1fr; }
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
        }
        @media (max-width: 600px) {
            .stat-grid { grid-template-columns: 1fr 1fr; }
            .info-grid  { grid-template-columns: 1fr 1fr; }
            .greeting-row { flex-direction: column; gap: 14px; }
        }
    </style>
</head>
<body>

{{-- ========== SIDEBAR ========== --}}
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
        <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;color:#475569;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" placeholder="Cari di sini...">
    </div>

    <nav class="sidebar-nav">
        <div class="nav-group-label">Menu Utama</div>

        <a href="{{ route('dashboard') }}" class="nav-item active">
            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
        </a>

        <a href="{{ route('inventory.index') }}" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            Barang
            <span class="nav-badge">1.256</span>
        </a>

        <a href="{{ route('categories.index') }}" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            Kategori
        </a>

        <a href="{{ route('loans.index') }}" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            Peminjaman
            <span class="nav-badge">12</span>
        </a>

        <a href="{{ route('returns.index') }}" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
            Pengembalian
        </a>

        <div class="nav-group-label">Laporan</div>

        <a href="{{ route('reports.index') }}" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Laporan
        </a>

        <a href="{{ route('statistics.index') }}" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
            Statistik
        </a>

        <div class="nav-group-label">Pengaturan</div>

        <a href="{{ route('users.index') }}" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Pengguna
        </a>

        <a href="{{ route('profile.edit') }}" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Pengaturan
        </a>
    </nav>

    {{-- ===== SIDEBAR CTA ===== --}}
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
            <div class="user-avatar">{{ auth()->check() ? auth()->user()->initials() : 'AD' }}</div>
            <div>
                <div class="user-name">{{ auth()->check() ? auth()->user()->name : 'Admin' }}</div>
                <div class="user-role">Administrator</div>
            </div>
            <div class="user-caret">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>
        </div>
    </div>
</aside>

{{-- ========== MAIN ========== --}}
<div class="main">

    {{-- TOPBAR --}}
    <div class="topbar">
        <button class="topbar-icon-btn" onclick="document.getElementById('sidebar').classList.toggle('open')" style="display:none" id="hamburgerBtn">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <div class="breadcrumb">
            <span>Home</span>
            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="active">Dashboard</span>
        </div>
        <div class="topbar-right">
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

    {{-- CONTENT --}}
    <div class="content">
        <livewire:dashboard />
    </div>{{-- /content --}}
</div>{{-- /main --}}

<script>
    // Responsive hamburger
    function checkWidth() {
        const btn = document.getElementById('hamburgerBtn');
        if (window.innerWidth <= 900) {
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
        if (window.innerWidth <= 900 && sidebar.classList.contains('open')) {
            if (!sidebar.contains(e.target) && !btn.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        }
    });

    // Tab switching (visual only)
    function setTab(el) {
        el.closest('div').querySelectorAll('button').forEach(b => {
            b.style.background = 'var(--bg)';
            b.style.color = 'var(--muted)';
        });
        el.style.background = '#1d4ed8';
        el.style.color = '#fff';
    }

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
