<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIPBAR Siswa')</title>
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

        :root {
            --bg:          #f8fafc;
            --bg2:         #ffffff;
            --bg3:         #f1f5f9;
            --card:        #ffffff;
            --border:      #e2e8f0;
            --border2:     #e2e8f0;
            --text:        #0f172a;
            --text2:       #334155;
            --muted:       #64748b;
            --subtle:      #94a3b8;
            --primary:     #2563eb;
            --primary-dark:#1d4ed8;
            --topbar-bg:   #ffffff;
            --topbar-bdr:  #e2e8f0;
            --content-bg:  #f8fafc;
            --panel-bg:    #ffffff;
            --input-bg:    #ffffff;
            --scrollbar:   #cbd5e1;
            --sidebar-bg:  #0a192f;
        }

        html.dark {
            --bg:          #0f172a;
            --bg2:         #1e293b;
            --bg3:         #334155;
            --card:        #1e293b;
            --border:      #334155;
            --border2:     #334155;
            --text:        #f8fafc;
            --text2:       #e2e8f0;
            --muted:       #94a3b8;
            --subtle:      #64748b;
            --primary:     #3b82f6;
            --primary-dark:#2563eb;
            --topbar-bg:   #0f172a;
            --topbar-bdr:  #334155;
            --content-bg:  #0f172a;
            --panel-bg:    #1e293b;
            --input-bg:    #1e293b;
            --scrollbar:   #475569;
            --sidebar-bg:  #0a192f;
        }

        body { display: flex; background: var(--content-bg); color: var(--text); overflow: hidden; transition: background .25s, color .25s; }

        .sidebar {
            width: 200px; flex-shrink: 0;
            background: var(--sidebar-bg);
            display: flex; flex-direction: column;
            height: 100vh; position: fixed; left: 0; top: 0; z-index: 40;
            transition: background .25s, transform .3s;
            transform: translateX(0);
        }
        .sidebar.closed { transform: translateX(-100%); }
        .sidebar-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,.5);
            z-index: 35; display: none;
        }
        .sidebar-overlay.active { display: block; }
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
        .nav-item.active { background: var(--primary-dark); color: #fff; font-weight: 700; }
        .nav-icon { width: 16px; height: 16px; flex-shrink: 0; }
        .nav-badge { margin-left: auto; background: var(--primary); color: #fff; font-size: 10px; font-weight: 800; padding: 1px 7px; border-radius: 999px; }

        .sidebar-footer { padding: 14px; border-top: 1px solid rgba(255,255,255,.12); }
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

        .main { margin-left: 200px; flex: 1; height: 100vh; display: flex; flex-direction: column; overflow: hidden; transition: margin-left .3s; }
        .main.expanded { margin-left: 0; }
        .content { flex: 1; overflow-y: auto; min-height: 0; padding: 22px 24px; }
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
            background: var(--primary-dark);
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700; color: #fff;
        }
        .topbar-uname { font-size: 13px; font-weight: 600; color: var(--text2); }
        .t-sun { display: none }
        .t-moon { display: block }
        html.dark .t-sun  { display: block }
        html.dark .t-moon { display: none }

        .hamburger-btn {
            display: flex; width: 36px; height: 36px;
            background: var(--bg3); border: 1px solid var(--border2);
            border-radius: 10px; align-items: center; justify-content: center;
            cursor: pointer; color: var(--muted); transition: all .15s;
        }
        .hamburger-btn:hover { background: var(--border2); color: var(--text); }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .topbar-search { display: none; }
            .content { padding: 16px; }
        }
    </style>
</head>
<body>
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
            $activeBorrowedCount = auth()->check() ? \App\Models\BorrowingRequest::where('user_id', auth()->id())->whereIn('status', ['borrowed', 'approved'])->count() : null;
            $navItems = [
                ['Dashboard',       'student.dashboard', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', null],
                ['Katalog Barang',  'student.catalog',  'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',     null],
                ['Peminjaman',      'student.loans',    'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4',                      null],
                ['Pengembalian',    'student.returns.index', 'M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6',                         $activeBorrowedCount > 0 ? (string)$activeBorrowedCount : null],
                ['Pengumuman',      'student.announcements', 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', null],
                ['Profil',          'student.profile',  'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',   null],
            ];
            @endphp
            @foreach($navItems as $nav)
            <a href="{{ route($nav[1]) }}" class="nav-item {{ request()->routeIs($nav[1]) || (str_starts_with($nav[1], 'student.returns') && request()->routeIs('student.returns.*')) ? 'active' : '' }}">
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
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="main">
        <div class="topbar">
            <button id="hamburgerBtn" class="hamburger-btn" title="Toggle Menu">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <div class="topbar-search">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;color:#94a3b8;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" placeholder="Cari barang atau riwayat...">
            </div>
            <div class="topbar-right">
                {{-- CTA Kembali ke Beranda --}}
                <a href="{{ route('home') }}" class="topbar-icon" title="Kembali ke Beranda" style="text-decoration:none">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </a>
                {{-- Theme Toggle Button --}}
                <button id="themeToggle" class="topbar-icon" title="Toggle Theme">
                    <svg class="t-sun" xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10A5 5 0 0012 7z"/>
                    </svg>
                    <svg class="t-moon" xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>
                @include('partials.notif-widget', ['iconClass' => 'topbar-icon'])
                <div class="topbar-user">
                    <div class="topbar-avatar">{{ auth()->check() ? strtoupper(substr(auth()->user()->name,0,2)) : 'SI' }}</div>
                    <div class="topbar-uname">{{ auth()->check() ? explode(' ',auth()->user()->name)[0] : 'Siswa' }}</div>
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;color:#94a3b8;margin-left:2px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>
        </div>

        <div class="content">
            @yield('content')
        </div>
    </div>

    <script>
        // Theme Toggle
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;
        const themeKey = 'sipbar-siswa-theme';

        themeToggle.addEventListener('click', () => {
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem(themeKey, 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem(themeKey, 'dark');
            }
        });

        // Mobile Sidebar Toggle
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const main = document.querySelector('.main');

        function toggleSidebar() {
            sidebar.classList.toggle('open');
            sidebarOverlay.classList.toggle('active');
            main.classList.toggle('expanded');
        }

        hamburgerBtn.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);
    </script>
    @stack('scripts')
</body>
</html>
