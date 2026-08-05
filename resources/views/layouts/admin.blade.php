<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIPBAR Admin')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if (class_exists('Livewire\Livewire'))
        @livewireStyles
    @endif
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { min-height: 100%; font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; background: #060b16; color: #e2e8f0; }
        body { display: flex; }
        :root {
            --bg: #0b1224;
            --panel: #0f172a;
            --panel-border: rgba(148,163,184,.18);
            --text: #e2e8f0;
            --muted: #94a3b8;
            --accent: #2563eb;
            --accent-soft: rgba(37,99,235,.16);
            --border: rgba(148,163,184,.08);
        }
        a { color: inherit; }
        .sidebar {
            width: 240px;
            flex-shrink: 0;
            background: linear-gradient(180deg,#0f172a,#111827);
            color: #e2e8f0;
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 40;
            border-right: 1px solid rgba(255,255,255,.05);
        }
        .sidebar.collapsed {
            width: 0;
            overflow: hidden;
            transition: width .3s ease;
        }
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 24px 22px;
            border-bottom: 1px solid rgba(255,255,255,.06);
            text-decoration: none;
            color: inherit;
        }
        .brand-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg,#3b82f6,#06b6d4);
            display: grid;
            place-items: center;
            color: #fff;
            font-weight: 800;
        }
        .brand-name { font-size: 15px; font-weight: 800; }
        .brand-sub { font-size: 11px; color: #94a3b8; }
        .sidebar-nav { flex: 1; overflow-y: auto; padding: 18px 12px 16px; }
        .sidebar-nav::-webkit-scrollbar { width: 8px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(148,163,184,.18); border-radius: 999px; }
        .nav-group-label {
            font-size: 10px;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: .1em;
            text-transform: uppercase;
            margin: 18px 0 10px;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 14px;
            font-size: 14px;
            text-decoration: none;
            color: #cbd5e1;
            margin-bottom: 6px;
        }
        .nav-item:hover { background: rgba(255,255,255,.06); color: #fff; }
        .nav-item.active { background: var(--accent-soft); color: #fff; }
        .nav-icon { width: 18px; height: 18px; flex-shrink: 0; display: grid; place-items: center; }
        .nav-badge {
            margin-left: auto;
            background: rgba(37,99,235,.16);
            color: #2563eb;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 7px;
        }
        .sidebar-footer {
            padding: 16px 18px 22px;
            border-top: 1px solid rgba(255,255,255,.06);
        }
        .user-card {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.06);
            border-radius: 16px;
            padding: 14px;
        }
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg,#3b82f6,#06b6d4);
            color: #fff;
            display: grid;
            place-items: center;
            font-size: 12px;
            font-weight: 800;
        }
        .user-name { font-size: 13px; font-weight: 700; color: #fff; }
        .user-role { font-size: 11px; color: #94a3b8; }
        .main { margin-left: 240px; flex: 1; min-height: 100vh; display: flex; flex-direction: column; background: #060b16; }
        .main.expanded { margin-left: 0; transition: margin-left .3s ease; }
        .topbar {
            height: 70px;
            background: rgba(15,23,42,.96);
            border-bottom: 1px solid rgba(148,163,184,.12);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            backdrop-filter: blur(10px);
        }
        .page-title { font-size: 20px; font-weight: 800; color: #fff; }
        .breadcrumb { display: flex; align-items: center; gap: 8px; font-size: 12px; color: #94a3b8; }
        .breadcrumb .active { color: #fff; font-weight: 600; }
        .content {
            flex: 1;
            overflow: auto;
            padding: 28px;
            background: linear-gradient(180deg,#060b16,#090f1a 100%);
        }
        .panel {
            background: rgba(15,23,42,.9);
            border: 1px solid var(--panel-border);
            border-radius: 24px;
            padding: 28px;
            box-shadow: 0 30px 60px rgba(0,0,0,.25);
        }
        .panel-title { font-size: 18px; font-weight: 800; color: #fff; margin-bottom: 14px; }
        .panel-text { color: #cbd5e1; line-height: 1.8; }
        .action-link { display: inline-flex; align-items: center; gap: 8px; color: var(--accent); font-weight: 700; text-decoration: none; margin-top: 18px; }
        .topbar-right { display: flex; align-items: center; gap: 12px; }
        .topbar-button {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            border: 1px solid rgba(148,163,184,.16);
            display: grid;
            place-items: center;
            background: rgba(255,255,255,.05);
            color: #cbd5e1;
            cursor: pointer;
            transition: background .2s ease, transform .2s ease;
        }
        .topbar-button:hover { background: rgba(255,255,255,.1); transform: translateY(-1px); }
        .topbar-button[title] { position: relative; }
        .sidebar-cta {
            margin: 16px 18px 0;
            padding: 16px;
            background: rgba(37,99,235,.08);
            border: 1px solid rgba(37,99,235,.18);
            border-radius: 16px;
            color: #eff6ff;
        }
        .sidebar-cta .cta-label {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #bfdbfe;
            margin-bottom: 10px;
            display: block;
        }
        .sidebar-cta .cta-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 12px 14px;
            border-radius: 14px;
            background: #2563eb;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
        }
        .sidebar-cta .cta-button:hover {
            background: #1d4ed8;
        }
        @media(max-width: 960px) {
            .sidebar { 
                position: fixed; 
                transform: translateX(-100%);
                width: 240px;
            }
            .sidebar.open { transform: translateX(0); }
            .sidebar.collapsed { width: 240px; }
            .main { margin-left: 0; }
            .main.expanded { margin-left: 0; }
            .topbar { padding: 0 18px; }
        }
    </style>
</head>
<body>
    <aside class="sidebar" id="sidebar">
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            <div class="brand-icon">S</div>
            <div>
                <div class="brand-name">SIPBAR</div>
                <div class="brand-sub">Sistem Inventaris</div>
            </div>
        </a>
        <nav class="sidebar-nav">
            <div class="nav-group-label">Menu Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-icon">🏠</span>
                Dashboard
            </a>
            <a href="{{ route('inventory.index') }}" class="nav-item {{ request()->routeIs('inventory.index') ? 'active' : '' }}">
                <span class="nav-icon">📦</span>
                Barang
            </a>
            <a href="{{ route('categories.index') }}" class="nav-item {{ request()->routeIs('categories.index') ? 'active' : '' }}">
                <span class="nav-icon">🗂️</span>
                Kategori
            </a>
            <a href="{{ route('loans.index') }}" class="nav-item {{ request()->routeIs('loans.index') ? 'active' : '' }}">
                <span class="nav-icon">📥</span>
                Peminjaman
            </a>
            <a href="{{ route('returns.index') }}" class="nav-item {{ request()->routeIs('returns.index') ? 'active' : '' }}">
                <span class="nav-icon">📤</span>
                Pengembalian
            </a>
            <div class="nav-group-label">Laporan</div>
            <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                <span class="nav-icon">📊</span>
                Laporan
            </a>
            <a href="{{ route('statistics.index') }}" class="nav-item {{ request()->routeIs('statistics.index') ? 'active' : '' }}">
                <span class="nav-icon">📈</span>
                Statistik
            </a>
            <div class="nav-group-label">Pengaturan</div>
            <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.index') ? 'active' : '' }}">
                <span class="nav-icon">👥</span>
                Pengguna
            </a>
            <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                <span class="nav-icon">⚙️</span>
                Pengaturan
            </a>
        </nav>
        <div class="sidebar-cta">
            <span class="cta-label">Tambah Guru / Siswa</span>
            <a href="{{ route('users.index') }}" class="cta-button">Kelola akun pengguna</a>
        </div>
        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">{{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'A' }}</div>
                <div>
                    <div class="user-name">{{ auth()->check() ? auth()->user()->name : 'Administrator' }}</div>
                    <div class="user-role">Administrator</div>
                </div>
            </div>
        </div>
    </aside>

    <div class="main">
        <div class="topbar">
            <div>
                <div class="page-title">@yield('page-heading', 'Dashboard')</div>
                <div class="breadcrumb">
                    <span>Home</span>
                    <span>›</span>
                    <span class="active">@yield('page-heading', 'Dashboard')</span>
                </div>
            </div>
            <div class="topbar-right">
                <button type="button" class="topbar-button" onclick="toggleSidebar()" aria-label="Toggle sidebar">☰</button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="topbar-button" title="Logout">🚪</button>
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
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const main = document.querySelector('.main');
            
            if (window.innerWidth <= 960) {
                sidebar.classList.toggle('open');
            } else {
                sidebar.classList.toggle('collapsed');
                main.classList.toggle('expanded');
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            }
        }
        
        window.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const main = document.querySelector('.main');
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            
            if (isCollapsed && window.innerWidth > 960) {
                sidebar.classList.add('collapsed');
                main.classList.add('expanded');
            }
        });
        
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const main = document.querySelector('.main');
            
            if (window.innerWidth > 960) {
                sidebar.classList.remove('open');
                const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (isCollapsed) {
                    sidebar.classList.add('collapsed');
                    main.classList.add('expanded');
                } else {
                    sidebar.classList.remove('collapsed');
                    main.classList.remove('expanded');
                }
            } else {
                sidebar.classList.remove('collapsed');
                main.classList.remove('expanded');
            }
        });
    </script>
</body>
</html>
