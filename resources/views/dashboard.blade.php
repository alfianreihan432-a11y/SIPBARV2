<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard – SIPBAR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; -webkit-font-smoothing: antialiased; }
        body { display: flex; background: #0f172a; color: #e2e8f0; overflow: hidden; }

        /* ========== SIDEBAR ========== */
        .sidebar {
            width: 220px;
            flex-shrink: 0;
            background: #0f172a;
            border-right: 1px solid #1e293b;
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: fixed;
            left: 0; top: 0;
            z-index: 40;
            transition: transform .3s;
        }
        .sidebar-brand {
            display: flex; align-items: center; gap: 10px;
            padding: 20px 20px 16px;
            border-bottom: 1px solid #1e293b;
            text-decoration: none;
        }
        .brand-icon {
            width: 34px; height: 34px;
            background: #1d4ed8;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .brand-name { font-size: 14px; font-weight: 700; color: #f1f5f9; line-height: 1.2; }
        .brand-sub  { font-size: 10px; color: #475569; line-height: 1.2; }

        .sidebar-search {
            margin: 14px 14px 8px;
            display: flex; align-items: center; gap: 8px;
            background: #1e293b; border: 1px solid #334155;
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
        .sidebar-nav::-webkit-scrollbar-thumb { background: #334155; border-radius: 2px; }

        .nav-group-label {
            font-size: 10px; font-weight: 700; color: #475569;
            letter-spacing: .08em; text-transform: uppercase;
            padding: 14px 10px 6px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 10px; border-radius: 8px;
            font-size: 13px; font-weight: 500; color: #94a3b8;
            text-decoration: none; cursor: pointer;
            transition: all .15s; margin-bottom: 2px;
            white-space: nowrap;
        }
        .nav-item:hover { background: #1e293b; color: #e2e8f0; }
        .nav-item.active {
            background: #1d4ed8;
            color: #fff;
            box-shadow: 0 4px 12px rgba(29, 78, 216, .35);
        }
        .nav-item.active .nav-icon { color: #fff; }
        .nav-icon { width: 16px; height: 16px; flex-shrink: 0; }
        .nav-badge {
            margin-left: auto;
            background: #1d4ed8; color: #fff;
            font-size: 10px; font-weight: 700;
            padding: 1px 6px; border-radius: 999px;
        }
        .nav-item.active .nav-badge { background: rgba(255,255,255,.25); }

        .sidebar-footer {
            padding: 14px;
            border-top: 1px solid #1e293b;
        }
        .user-card {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 10px; border-radius: 10px;
            background: #1e293b; cursor: pointer;
        }
        .user-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: linear-gradient(135deg, #1d4ed8, #06b6d4);
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; color: #fff;
            flex-shrink: 0;
        }
        .user-name  { font-size: 12px; font-weight: 700; color: #f1f5f9; }
        .user-role  { font-size: 10px; color: #64748b; }
        .user-caret { margin-left: auto; color: #475569; }

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
            height: 60px;
            background: #0f172a;
            border-bottom: 1px solid #1e293b;
            display: flex; align-items: center;
            padding: 0 24px; gap: 12px;
            flex-shrink: 0;
        }
        .breadcrumb {
            display: flex; align-items: center; gap: 6px;
            font-size: 12px; color: #475569;
        }
        .breadcrumb span { color: #94a3b8; }
        .breadcrumb .active { color: #e2e8f0; font-weight: 600; }
        .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 10px; }
        .topbar-icon-btn {
            width: 34px; height: 34px;
            background: #1e293b; border: 1px solid #334155;
            border-radius: 8px; display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: #64748b; transition: all .15s;
        }
        .topbar-icon-btn:hover { background: #334155; color: #e2e8f0; }
        .notif-btn { position: relative; }
        .notif-dot {
            position: absolute; top: 6px; right: 7px;
            width: 7px; height: 7px; background: #ef4444;
            border-radius: 50%; border: 2px solid #0f172a;
        }

        /* ========== CONTENT ========== */
        .content {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
        }
        .content::-webkit-scrollbar { width: 4px; }
        .content::-webkit-scrollbar-track { background: transparent; }
        .content::-webkit-scrollbar-thumb { background: #334155; border-radius: 2px; }

        /* Greeting */
        .greeting-row {
            display: flex; align-items: flex-start; justify-content: space-between;
            margin-bottom: 24px;
        }
        .greeting-title { font-size: 26px; font-weight: 800; color: #f1f5f9; margin-bottom: 4px; }
        .greeting-sub   { font-size: 13px; color: #64748b; }

        /* Gauge card */
        .gauge-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 16px;
            padding: 20px 24px;
            text-align: center;
            min-width: 160px;
        }
        .gauge-title { font-size: 11px; color: #64748b; margin-bottom: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; }
        .gauge-svg   { width: 120px; height: 70px; }
        .gauge-val   { font-size: 24px; font-weight: 800; color: #f1f5f9; margin: 4px 0 2px; }
        .gauge-label { font-size: 11px; color: #64748b; }

        /* ========== STAT CARDS ========== */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 14px;
            padding: 18px 20px;
            display: flex; align-items: center; gap: 14px;
            transition: border-color .2s, transform .2s;
        }
        .stat-card:hover { border-color: #1d4ed8; transform: translateY(-2px); }
        .stat-icon-box {
            width: 44px; height: 44px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .stat-num   { font-size: 22px; font-weight: 800; color: #f1f5f9; line-height: 1; margin-bottom: 3px; }
        .stat-label { font-size: 12px; color: #64748b; }
        .stat-change { font-size: 11px; font-weight: 600; margin-top: 3px; }
        .up   { color: #34d399; }
        .down { color: #f87171; }

        /* ========== BOTTOM GRID ========== */
        .bottom-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1.2fr;
            gap: 16px;
            margin-bottom: 20px;
        }

        /* Panel base */
        .panel {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 14px;
            padding: 18px 20px;
        }
        .panel-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 14px;
        }
        .panel-title { font-size: 14px; font-weight: 700; color: #f1f5f9; }
        .panel-more  {
            background: none; border: none; cursor: pointer;
            color: #475569; display: flex; align-items: center; gap: 4px;
            font-size: 12px; padding: 4px 8px; border-radius: 6px;
            transition: color .15s, background .15s;
        }
        .panel-more:hover { color: #94a3b8; background: #334155; }

        /* Schedule / Transaksi list */
        .txn-item {
            background: #0f172a;
            border: 1px solid #1e293b;
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 10px;
        }
        .txn-item:last-child { margin-bottom: 0; }
        .txn-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 6px; }
        .txn-name { font-size: 13px; font-weight: 700; color: #f1f5f9; }
        .txn-type { font-size: 11px; color: #64748b; margin-top: 2px; }
        .txn-link-btn {
            width: 28px; height: 28px; border-radius: 8px;
            background: #1e293b; border: 1px solid #334155;
            display: flex; align-items: center; justify-content: center;
            color: #64748b; flex-shrink: 0; cursor: pointer;
        }
        .txn-meta {
            display: flex; align-items: center; justify-content: space-between;
            font-size: 11px; color: #475569;
        }
        .txn-user { display: flex; align-items: center; gap: 6px; }
        .txn-avatar {
            width: 20px; height: 20px; border-radius: 50%;
            background: linear-gradient(135deg, #1d4ed8, #06b6d4);
            display: flex; align-items: center; justify-content: center;
            font-size: 9px; font-weight: 700; color: #fff;
        }

        /* Chart panel */
        .chart-wrap { position: relative; }
        .chart-big-num {
            font-size: 32px; font-weight: 800; color: #f1f5f9; line-height: 1;
        }
        .chart-big-label { font-size: 11px; color: #64748b; margin-top: 3px; }
        .chart-svg { width: 100%; height: 110px; margin-top: 10px; }

        /* Status bars */
        .status-grid {
            display: grid; grid-template-columns: 1fr 1fr 1fr;
            gap: 10px; margin-top: 4px;
        }
        .status-bar-item { text-align: center; }
        .bar-wrap {
            height: 70px; display: flex;
            align-items: flex-end; justify-content: center; margin-bottom: 6px;
        }
        .bar {
            width: 32px; border-radius: 6px 6px 0 0;
            transition: height .6s ease;
        }
        .bar-val  { font-size: 13px; font-weight: 800; color: #f1f5f9; }
        .bar-label { font-size: 10px; color: #64748b; margin-top: 2px; }

        /* Leave / Info mini cards */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        .info-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 14px 16px;
            transition: border-color .2s;
        }
        .info-card:hover { border-color: #1d4ed8; }
        .info-card-label { font-size: 11px; color: #64748b; margin-bottom: 6px; }
        .info-card-val   { font-size: 18px; font-weight: 800; color: #f1f5f9; margin-bottom: 8px; }
        .info-card-link  {
            display: flex; align-items: center; gap: 4px;
            font-size: 11px; color: #1d4ed8; font-weight: 600;
            cursor: pointer; text-decoration: none;
        }

        /* ========== TABLE ========== */
        .table-panel {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 8px;
        }
        .table-header-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid #0f172a;
        }
        .table-search {
            display: flex; align-items: center; gap: 8px;
            background: #0f172a; border: 1px solid #334155;
            border-radius: 8px; padding: 6px 12px;
        }
        .table-search input {
            background: none; border: none; outline: none;
            font-size: 12px; color: #94a3b8; width: 140px;
        }
        .table-search input::placeholder { color: #475569; }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            padding: 10px 16px;
            font-size: 11px; font-weight: 700; color: #475569;
            text-align: left; letter-spacing: .06em; text-transform: uppercase;
            background: #0f172a; border-bottom: 1px solid #1e293b;
        }
        tbody tr {
            border-bottom: 1px solid #0f172a;
            transition: background .15s;
        }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: rgba(29,78,216,.06); }
        tbody td {
            padding: 12px 16px;
            font-size: 13px; color: #94a3b8;
        }
        .td-name { display: flex; align-items: center; gap: 10px; }
        .td-avatar {
            width: 32px; height: 32px; border-radius: 8px;
            background: #0f172a;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .td-name-text { font-size: 13px; font-weight: 600; color: #e2e8f0; }
        .td-sub { font-size: 11px; color: #475569; }
        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 10px; border-radius: 999px;
            font-size: 11px; font-weight: 600;
        }
        .badge-available  { background: rgba(52,211,153,.1); color: #34d399; }
        .badge-borrowed   { background: rgba(248,113,113,.1); color: #f87171; }
        .badge-maintenance{ background: rgba(251,191,36,.1);  color: #fbbf24; }
        .action-btn {
            background: none; border: 1px solid #334155;
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

        <a href="#" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            Barang
            <span class="nav-badge">1.256</span>
        </a>

        <a href="#" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            Kategori
        </a>

        <a href="#" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            Peminjaman
            <span class="nav-badge">12</span>
        </a>

        <a href="#" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
            Pengembalian
        </a>

        <div class="nav-group-label">Laporan</div>

        <a href="#" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Laporan
        </a>

        <a href="#" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
            Statistik
        </a>

        <div class="nav-group-label">Pengaturan</div>

        <a href="#" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Pengguna
        </a>

        <a href="#" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Pengaturan
        </a>
    </nav>

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

        {{-- Greeting + Gauge --}}
        <div class="greeting-row">
            <div>
                <div class="greeting-title">
                    @php
                        $hour = now()->hour;
                        $greet = $hour < 12 ? 'Selamat Pagi' : ($hour < 17 ? 'Selamat Siang' : 'Selamat Malam');
                    @endphp
                    {{ $greet }}, {{ auth()->check() ? explode(' ', auth()->user()->name)[0] : 'Admin' }} 👋
                </div>
                <div class="greeting-sub">{{ now()->translatedFormat('l, d F Y') }}</div>
            </div>
            <div class="gauge-card">
                <div class="gauge-title">Kondisi Barang Baik</div>
                <svg class="gauge-svg" viewBox="0 0 120 70">
                    <defs>
                        <linearGradient id="gaugeGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#1d4ed8"/>
                            <stop offset="100%" stop-color="#06b6d4"/>
                        </linearGradient>
                    </defs>
                    <path d="M10,65 A50,50 0 0,1 110,65" fill="none" stroke="#1e293b" stroke-width="10" stroke-linecap="round"/>
                    <path d="M10,65 A50,50 0 0,1 110,65" fill="none" stroke="url(#gaugeGrad)" stroke-width="10" stroke-linecap="round" stroke-dasharray="157" stroke-dashoffset="32"/>
                    <text x="60" y="58" text-anchor="middle" font-size="16" font-weight="800" fill="#f1f5f9">80%</text>
                    <text x="12" y="70" font-size="7" fill="#475569">0</text>
                    <text x="104" y="70" font-size="7" fill="#475569">100</text>
                </svg>
                <div class="gauge-label">Kondisi Barang Baik</div>
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="stat-grid">
            @php
            $stats = [
                ['1.256', 'Total Barang', '+17%', true,  '#1d4ed8', '#dbeafe', 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                ['234',   'Sedang Dipinjam', '+5%', true, '#0891b2', '#cffafe', 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                ['892',   'Barang Tersedia', '-3%', false,'#059669', '#d1fae5', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['24',    'Kategori', '—', null,   '#7c3aed', '#ede9fe', 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
            ];
            @endphp
            @foreach($stats as $s)
            <div class="stat-card">
                <div class="stat-icon-box" style="background:{{ $s[4] }}1a">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:{{ $s[4] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s[6] }}"/>
                    </svg>
                </div>
                <div>
                    <div class="stat-num">{{ $s[0] }}</div>
                    <div class="stat-label">{{ $s[1] }}</div>
                    @if($s[3] !== null)
                    <div class="stat-change {{ $s[3] ? 'up' : 'down' }}">
                        {{ $s[3] ? '↑' : '↓' }} {{ $s[2] }} bulan ini
                    </div>
                    @else
                    <div class="stat-change" style="color:#475569">Total keseluruhan</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Bottom Grid: Transaksi | Chart | Status Bar --}}
        <div class="bottom-grid">

            {{-- Transaksi Terbaru --}}
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Transaksi Terbaru</div>
                        <div style="font-size:11px;color:#475569;margin-top:2px">Peminjaman hari ini</div>
                    </div>
                    <button class="panel-more">··· </button>
                </div>

                {{-- Tabs --}}
                <div style="display:flex;gap:6px;margin-bottom:14px">
                    @foreach(['Semua','Dipinjam','Kembali'] as $t)
                    <button onclick="setTab(this)" style="padding:5px 12px;border-radius:6px;font-size:11px;font-weight:600;border:1px solid #334155;cursor:pointer;transition:all .15s;background:{{ $t === 'Semua' ? '#1d4ed8' : '#0f172a' }};color:{{ $t === 'Semua' ? '#fff' : '#64748b' }}">{{ $t }}</button>
                    @endforeach
                </div>

                @php
                $txns = [
                    ['Proyektor Epson', 'Dipinjam – Ruang Kelas 9A', 'Hari ini 08:00 – 10:00', 'Ahmad S.', 'AS', '#1d4ed8'],
                    ['Bola Basket x3', 'Dipinjam – Ekstrakurikuler', 'Hari ini 14:00 – 16:00', 'Budi R.', 'BR', '#059669'],
                    ['Laptop Dell x2', 'Dipinjam – Lab Komputer', 'Hari ini 10:00 – 12:00', 'Citra L.', 'CL', '#7c3aed'],
                ];
                @endphp
                @foreach($txns as $txn)
                <div class="txn-item">
                    <div class="txn-top">
                        <div>
                            <div class="txn-name">{{ $txn[0] }}</div>
                            <div class="txn-type">{{ $txn[1] }}</div>
                        </div>
                        <div class="txn-link-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </div>
                    </div>
                    <div class="txn-meta">
                        <div style="display:flex;align-items:center;gap:5px;color:#475569">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $txn[2] }}
                        </div>
                        <div class="txn-user">
                            <div class="txn-avatar" style="background:linear-gradient(135deg,{{ $txn[5] }},#06b6d4)">{{ $txn[4] }}</div>
                            <span style="font-size:11px;color:#64748b">{{ $txn[3] }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Chart --}}
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Grafik Peminjaman</div>
                        <div style="font-size:11px;color:#475569;margin-top:2px">6 Bulan Terakhir</div>
                    </div>
                    <button class="panel-more">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </button>
                </div>
                <div class="chart-big-num">70,32%</div>
                <div class="chart-big-label">Rata-rata Utilisasi Barang</div>

                <svg class="chart-svg" viewBox="0 0 280 110" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="lineGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#1d4ed8" stop-opacity=".25"/>
                            <stop offset="100%" stop-color="#1d4ed8" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    {{-- Grid lines --}}
                    @foreach([20,45,70,95] as $y)
                    <line x1="0" y1="{{ $y }}" x2="280" y2="{{ $y }}" stroke="#1e293b" stroke-width="1"/>
                    @endforeach
                    {{-- Area --}}
                    <polygon fill="url(#lineGrad)" points="0,85 46,70 92,75 138,35 184,50 230,20 280,35 280,110 0,110"/>
                    {{-- Line --}}
                    <polyline fill="none" stroke="#1d4ed8" stroke-width="2.5" stroke-linejoin="round" stroke-linecap="round" points="0,85 46,70 92,75 138,35 184,50 230,20 280,35"/>
                    {{-- Dots --}}
                    @foreach([[0,85],[46,70],[92,75],[138,35],[184,50],[230,20],[280,35]] as $p)
                    <circle cx="{{ $p[0] }}" cy="{{ $p[1] }}" r="4" fill="#1d4ed8" stroke="#0f172a" stroke-width="2"/>
                    @endforeach
                    {{-- Labels --}}
                    @foreach(['Jul','Agu','Sep','Okt','Nov','Des'] as $i => $m)
                    <text x="{{ 23 + $i * 46 }}" y="108" text-anchor="middle" font-size="8" fill="#475569">{{ $m }}</text>
                    @endforeach
                </svg>
            </div>

            {{-- Status Bar --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">Status Barang</div>
                    <button class="panel-more">···</button>
                </div>
                <div style="display:flex;align-items:baseline;gap:8px;margin-bottom:16px">
                    <span style="font-size:28px;font-weight:800;color:#f1f5f9">892</span>
                    <span style="font-size:12px;color:#34d399;font-weight:600">Tersedia</span>
                </div>
                <div class="status-grid">
                    @php
                    $bars = [
                        ['Tersedia', 892, 70, '#1d4ed8'],
                        ['Dipinjam', 234, 46, '#06b6d4'],
                        ['Rusak',     98, 28, '#334155'],
                    ];
                    @endphp
                    @foreach($bars as $b)
                    <div class="status-bar-item">
                        <div class="bar-wrap">
                            <div class="bar" style="height:{{ $b[2] }}px;background:{{ $b[3] }};width:32px;border-radius:6px 6px 0 0"></div>
                        </div>
                        <div class="bar-val">{{ $b[1] }}</div>
                        <div class="bar-label">{{ $b[0] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Info Mini Cards --}}
        <div class="info-grid">
            @php
            $infos = [
                ['Barang Masuk Bulan Ini', '48 Unit',  'Lihat Detail', '#1d4ed8'],
                ['Barang Keluar Bulan Ini','32 Unit',  'Lihat Detail', '#0891b2'],
                ['Peminjaman Aktif',       '12 Transaksi','Lihat Semua','#059669'],
                ['Jatuh Tempo Hari Ini',   '3 Item',   'Cek Sekarang', '#f59e0b'],
                ['Total Pengguna Aktif',   '156 Orang','Kelola User',  '#7c3aed'],
            ];
            @endphp
            @foreach($infos as $inf)
            <div class="info-card">
                <div class="info-card-label">{{ $inf[0] }}</div>
                <div class="info-card-val" style="color:{{ $inf[3] }}">{{ $inf[1] }}</div>
                <a href="#" class="info-card-link" style="color:{{ $inf[3] }}">
                    {{ $inf[2] }}
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>
            @endforeach
        </div>

        {{-- Table: Daftar Barang --}}
        <div class="table-panel">
            <div class="table-header-row">
                <div class="panel-title">Daftar Barang</div>
                <div style="display:flex;align-items:center;gap:10px">
                    <div class="table-search">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;color:#475569" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" placeholder="Cari barang...">
                    </div>
                    <button class="panel-more">···</button>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Kode</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Terakhir Diperbarui</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $items = [
                        ['Proyektor Epson EB-S41', 'BRG-0001', 'Elektronik',    'Ruang AV',        'available',   '03 Agu 2026'],
                        ['Meja Guru Jati',          'BRG-0002', 'Furnitur',      'Kelas X-IPA-1',   'available',   '01 Agu 2026'],
                        ['Bola Basket Spalding',    'BRG-0003', 'Olahraga',      'Gudang Olahraga', 'borrowed',    '02 Agu 2026'],
                        ['Laptop Dell Inspiron',    'BRG-0004', 'Elektronik',    'Lab Komputer',    'borrowed',    '03 Agu 2026'],
                        ['Mikroskop Olympus',       'BRG-0005', 'Laboratorium',  'Lab IPA',         'available',   '31 Jul 2026'],
                        ['Kursi Plastik',           'BRG-0006', 'Furnitur',      'Kelas XII-IPS-2', 'maintenance', '30 Jul 2026'],
                    ];
                    $icons = [
                        'Elektronik'   => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                        'Furnitur'     => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                        'Olahraga'     => 'M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0',
                        'Laboratorium' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z',
                    ];
                    $statusMap = ['available' => ['Tersedia','badge-available'], 'borrowed' => ['Dipinjam','badge-borrowed'], 'maintenance' => ['Perbaikan','badge-maintenance']];
                    @endphp
                    @foreach($items as $item)
                    <tr>
                        <td>
                            <div class="td-name">
                                <div class="td-avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;color:#1d4ed8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icons[$item[2]] ?? $icons['Elektronik'] }}"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="td-name-text">{{ $item[0] }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-family:monospace;font-size:12px;color:#64748b">{{ $item[1] }}</td>
                        <td>{{ $item[2] }}</td>
                        <td>{{ $item[3] }}</td>
                        <td>
                            <span class="badge {{ $statusMap[$item[4]][1] }}">
                                <span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block"></span>
                                {{ $statusMap[$item[4]][0] }}
                            </span>
                        </td>
                        <td>{{ $item[5] }}</td>
                        <td>
                            <button class="action-btn">···</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

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
            b.style.background = '#0f172a';
            b.style.color = '#64748b';
        });
        el.style.background = '#1d4ed8';
        el.style.color = '#fff';
    }
</script>
</body>
</html>
