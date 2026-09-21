<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIPBAR Kepala Jurusan')</title>
    @include('partials.favicon')
    <script>
    (function(){
        var s=localStorage.getItem('sipbar-kajur-theme');
        var d=window.matchMedia('(prefers-color-scheme: dark)').matches;
        if(s==='dark'||(s===null&&d)) document.documentElement.classList.add('dark');
    })();
    </script>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        html,body{height:100%;font-family:'Instrument Sans',ui-sans-serif,system-ui,sans-serif;-webkit-font-smoothing:antialiased}
        :root, html.light {
            --bg: #fdf2f4;
            --bg2: #ffffff;
            --bg3: #fae6e9;
            --card: #ffffff;
            --border: #f0d5da;
            --border2: #e5bfc6;
            --text: #0f172a;
            --text2: #334155;
            --muted: #64748b;
            --subtle: #94a3b8;
            --topbar-bg: #ffffff;
            --topbar-bdr: #f0d5da;
            --content-bg: #fdf2f4;
            --panel-bg: #ffffff;
            --input-bg: #ffffff;
            --scrollbar: #e5bfc6;
            --sidebar-bg: #851e2a;
            --accent: #991b1b;
            --accent-hover: #7f1d1d;
            --accent-light: #fee2e2;
            --accent-text: #991b1b;
        }
        html.dark {
            --bg: #38151b;
            --bg2: #ffffff;
            --bg3: #4a1e26;
            --card: #ffffff;
            --border: #f0d5da;
            --border2: #5c242e;
            --text: #0f172a;
            --text2: #334155;
            --muted: #64748b;
            --subtle: #94a3b8;
            --topbar-bg: #2a0e13;
            --topbar-bdr: #481921;
            --content-bg: #38151b;
            --panel-bg: #ffffff;
            --input-bg: #ffffff;
            --scrollbar: #5c242e;
            --sidebar-bg: #20070b;
            --accent: #991b1b;
            --accent-hover: #7f1d1d;
            --accent-light: #fee2e2;
            --accent-text: #991b1b;
        }
        body{display:flex;background:var(--content-bg);color:var(--text);overflow:hidden;transition:background .25s,color .25s}
        .sidebar{width:220px;flex-shrink:0;background:var(--sidebar-bg);display:flex;flex-direction:column;height:100vh;position:fixed;left:0;top:0;z-index:40;transition:background .25s,transform .3s;box-shadow:2px 0 16px rgba(0,0,0,.08)}
        .sidebar-brand{display:flex;align-items:center;gap:14px;padding:20px 18px;border-bottom:1px solid rgba(255,255,255,.12);text-decoration:none}
        .sidebar-logo-wrap{width:48px;height:48px;border-radius:50%;background:rgba(255,255,255,.95);display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.3),0 0 0 1.5px rgba(255,255,255,.15);transition:transform .2s ease,box-shadow .2s ease}
        .sidebar-brand:hover .sidebar-logo-wrap{transform:scale(1.05);box-shadow:0 4px 14px rgba(0,0,0,.4)}
        .sidebar-brand-img{width:100%;height:100%;object-fit:cover}
        .brand-name{font-size:18px;font-weight:800;color:#ffffff;letter-spacing:-.02em;line-height:1.15}
        .brand-badge{font-size:9.5px;font-weight:800;color:#851e2a;background:rgba(255,255,255,.95);padding:2px 6px;border-radius:4px;letter-spacing:.05em;display:inline-block;margin-top:2px}
        html.dark .brand-badge{color:#20070b;background:rgba(255,255,255,.95)}
        .sidebar-nav{flex:1;padding:16px 12px;overflow-y:auto}
        .sidebar-nav::-webkit-scrollbar{width:0}
        .nav-item{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;font-size:13px;font-weight:500;color:rgba(255,255,255,.82);text-decoration:none;margin-bottom:4px;transition:all .18s ease;position:relative}
        .nav-item:hover{background:rgba(255,255,255,.10);color:#ffffff}
        .nav-item.active{background:rgba(255,255,255,.18);color:#ffffff;font-weight:700;border-left:3.5px solid #fca5a5;padding-left:9px}
        html.dark .nav-item.active{border-left-color:#fca5a5}
        .nav-icon{width:18px;height:18px;flex-shrink:0}
        .nav-badge{margin-left:auto;background:#ffffff;color:#851e2a;font-size:11px;font-weight:800;padding:2px 7px;border-radius:999px;box-shadow:0 2px 6px rgba(0,0,0,.15)}
        html.dark .nav-badge{color:#20070b}
        .sidebar-footer{padding:14px;border-top:1px solid rgba(255,255,255,.12);background:rgba(0,0,0,.08)}
        .user-card{display:flex;align-items:center;gap:10px;padding:9px 10px;border-radius:10px;background:rgba(255,255,255,.1);cursor:pointer;text-decoration:none;color:inherit;transition:background .15s}
        .user-card:hover{background:rgba(255,255,255,.16)}
        .user-avatar{width:34px;height:34px;border-radius:50%;background:#991b1b;border:1.5px solid rgba(255,255,255,.35);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;color:#ffffff;flex-shrink:0}
        html.dark .user-avatar{background:#7f1d1d}
        .user-name{font-size:12.5px;font-weight:700;color:#ffffff;line-height:1.2}
        .user-role{font-size:10.5px;color:rgba(255,255,255,.75)}
        .logout-btn{display:flex;align-items:center;gap:9px;padding:9px 12px;border-radius:10px;margin-top:8px;font-size:13px;font-weight:600;color:rgba(255,255,255,.75);cursor:pointer;text-decoration:none;transition:all .15s;background:none;border:none;width:100%}
        .logout-btn:hover{background:rgba(239,68,68,.25);color:#fca5a5}
        .main{margin-left:220px;flex:1;height:100vh;display:flex;flex-direction:column;overflow:hidden}
        .topbar{height:62px;background:var(--topbar-bg);border-bottom:1px solid var(--topbar-bdr);display:flex;align-items:center;padding:0 24px;gap:12px;flex-shrink:0;transition:background .25s,border-color .25s}
        .topbar-search{display:flex;align-items:center;gap:8px;background:var(--bg3);border:1px solid var(--border);border-radius:10px;padding:8px 14px;width:280px;transition:all .15s}
        .topbar-search:focus-within{border-color:var(--accent)}
        .topbar-search input{background:none;border:none;outline:none;font-size:13px;color:var(--text);width:100%}
        .topbar-search input::placeholder{color:var(--subtle)}
        html.dark .topbar-search{background:rgba(255,255,255,.08);border-color:rgba(255,255,255,.12)}
        html.dark .topbar-search input{color:#ffffff}
        html.dark .topbar-search input::placeholder{color:rgba(255,255,255,.5)}
        .topbar-right{margin-left:auto;display:flex;align-items:center;gap:10px}
        .topbar-icon-wrap{position:relative}
        .topbar-icon{width:38px;height:38px;background:var(--bg3);border:1px solid var(--border);border-radius:10px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--muted);transition:all .15s;position:relative}
        .topbar-icon:hover{background:var(--border);color:var(--text);border-color:var(--border2)}
        html.dark .topbar-icon{background:rgba(255,255,255,.08);border-color:rgba(255,255,255,.12);color:rgba(255,255,255,.8)}
        html.dark .topbar-icon:hover{background:rgba(255,255,255,.15);color:#ffffff}
        .notif-dot{position:absolute;top:7px;right:8px;width:7px;height:7px;background:var(--accent);border-radius:50%;border:2px solid var(--topbar-bg);transition:border-color .25s}

        /* Topbar User Button & Dropdown */
        .topbar-user-wrap{position:relative}
        .topbar-user{display:flex;align-items:center;gap:8px;padding:4px 12px 4px 4px;background:var(--bg3);border:1px solid var(--border);border-radius:999px;cursor:pointer;color:inherit;transition:all .15s;font-family:inherit;font-size:inherit}
        .topbar-user:hover,.topbar-user.active{border-color:var(--border2);background:var(--border)}
        html.dark .topbar-user{background:rgba(255,255,255,.08);border-color:rgba(255,255,255,.12);color:#ffffff}
        html.dark .topbar-user:hover,html.dark .topbar-user.active{background:rgba(255,255,255,.15)}
        .topbar-avatar{width:32px;height:32px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:11.5px;font-weight:700;color:#ffffff}
        .topbar-uname{font-size:13px;font-weight:600;color:var(--text2)}
        html.dark .topbar-uname{color:#ffffff}
        .chevron-icon{transition:transform .2s ease}
        .topbar-user.active .chevron-icon{transform:rotate(180deg)}

        /* Dropdown Menu Styles */
        .profile-dropdown-menu,.notif-dropdown-menu{
            position:absolute;
            top:calc(100% + 8px);
            right:0;
            width:260px;
            background:#ffffff;
            border:1px solid #f0d5da;
            border-radius:14px;
            box-shadow:0 12px 30px -4px rgba(0,0,0,0.16),0 4px 12px -2px rgba(0,0,0,0.08);
            z-index:100;
            padding:8px;
            display:none;
        }
        .profile-dropdown-menu.show,.notif-dropdown-menu.show{
            display:block;
            animation:dropdownFade .15s ease-out;
        }
        @keyframes dropdownFade{
            from{opacity:0;transform:translateY(-6px)}
            to{opacity:1;transform:translateY(0)}
        }

        .pdm-header{padding:10px 12px;display:flex;align-items:center;gap:10px}
        .pdm-avatar{width:36px;height:36px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;color:#fff;flex-shrink:0}
        .pdm-info{flex:1;min-width:0}
        .pdm-name{font-size:13px;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .pdm-role{font-size:11px;color:var(--accent);font-weight:600}
        .pdm-divider{height:1px;background:#f0d5da;margin:6px 0}
        .pdm-links{display:flex;flex-direction:column;gap:2px}
        .pdm-link{display:flex;align-items:center;gap:10px;padding:8px 12px;border-radius:8px;font-size:12.5px;font-weight:500;color:#334155;text-decoration:none;transition:all .15s}
        .pdm-link:hover{background:#fae6e9;color:var(--accent-text)}
        .pdm-link-icon{width:16px;height:16px;color:#64748b;flex-shrink:0}
        .pdm-logout-form{margin-top:2px}
        .pdm-logout-btn{display:flex;align-items:center;gap:10px;padding:8px 12px;border-radius:8px;font-size:12.5px;font-weight:600;color:var(--accent);background:none;border:none;width:100%;cursor:pointer;transition:all .15s;font-family:inherit;text-align:left}
        .pdm-logout-btn:hover{background:var(--accent-light);color:var(--accent-hover)}

        .t-sun{display:none}.t-moon{display:block}
        html.dark .t-sun{display:block}html.dark .t-moon{display:none}
        .content{flex:1;overflow-y:auto;min-height:0;padding:24px 28px;background:var(--content-bg);transition:background .25s}
        .content::-webkit-scrollbar{width:5px}
        .content::-webkit-scrollbar-thumb{background:var(--scrollbar);border-radius:3px}

        /* ── Hamburger & Mobile Drawer ── */
        .hamburger-btn {
            display: none;
            align-items: center;
            justify-content: center;
            min-width: 44px;
            min-height: 44px;
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: var(--bg3);
            border: 1px solid var(--border);
            color: var(--text);
            cursor: pointer;
            flex-shrink: 0;
            transition: all .15s;
        }
        .hamburger-btn:hover { background: var(--border); color: var(--accent-text); }
        html.dark .hamburger-btn { background: rgba(255,255,255,.08); border-color: rgba(255,255,255,.12); color: #ffffff; }
        html.dark .hamburger-btn:hover { background: rgba(255,255,255,.15); }
        .sidebar-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,.55);
            backdrop-filter: blur(3px); -webkit-backdrop-filter: blur(3px);
            z-index: 45; display: none; opacity: 0;
            transition: opacity .25s ease;
        }
        .sidebar-overlay.active { display: block; opacity: 1; }

        /* Ensure card and content elements stay clean white with dark text across all pages in both modes */
        .stat-card,
        .section-card,
        .create-report-card,
        .profile-hero,
        .stat-box,
        .info-card,
        .qr-scanner-card,
        .verify-card {
            background: #ffffff !important;
            color: #0f172a !important;
            border: 1px solid #f0d5da !important;
        }
        html.dark .stat-card,
        html.dark .section-card,
        html.dark .create-report-card,
        html.dark .profile-hero,
        html.dark .stat-box,
        html.dark .info-card,
        html.dark .qr-scanner-card,
        html.dark .verify-card {
            box-shadow: 0 4px 18px rgba(0,0,0,0.22), 0 1px 4px rgba(0,0,0,0.12) !important;
        }

        @media (max-width: 900px) {
            .sidebar {
                position: fixed;
                left: 0; top: 0;
                transform: translateX(-100%);
                z-index: 50;
                box-shadow: 4px 0 30px rgba(0,0,0,.35);
            }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .hamburger-btn { display: flex; }
            .topbar { padding: 0 16px; gap: 8px; }
            .topbar-search { display: none; }
            .content { padding: 16px 14px; }
        }
    </style>
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <aside class="sidebar" id="sidebar">
        <a href="{{ route('kajur.dashboard') }}" class="sidebar-brand">
            <div class="sidebar-logo-wrap">
                <img src="{{ $siteLogoDashboard ?? '/logossmkn1.png' }}" alt="{{ $siteName ?? 'SIPBAR' }}" class="sidebar-brand-img">
            </div>
            <div>
                <div class="brand-name">SIPBAR</div>
                <span class="brand-badge">KEPALA JURUSAN</span>
            </div>
        </a>
        <nav class="sidebar-nav">
            @php
            $jurusanNama = auth()->check() && auth()->user()->jurusan ? auth()->user()->jurusan->nama : '';
            $kajurJurusanId = auth()->check() ? auth()->user()->jurusan_id : null;
            $pendingApprovalsCount = $kajurJurusanId
                ? \App\Models\BorrowingRequest::where('tipe_peminjam', 'guru')
                    ->whereHas('user', fn($q) => $q->where('jurusan_id', $kajurJurusanId))
                    ->where('status', \App\Models\BorrowingRequest::STATUS_PENDING)
                    ->count()
                : 0;
            $kajurAuthId = auth()->id();
            $pendingReturnsCount = \App\Models\ItemReturn::guru()
                ->where(function ($query) use ($kajurAuthId, $kajurJurusanId) {
                    $query->where('kajur_id', $kajurAuthId);
                    if ($kajurJurusanId) {
                        $query->orWhereHas('user', fn($uq) => $uq->where('jurusan_id', $kajurJurusanId));
                    }
                })
                ->where('status', \App\Models\ItemReturn::STATUS_MENUNGGU)
                ->count();

            $menus = [
                ['Dashboard', 'kajur.dashboard', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', null],
                ['Permohonan Peminjaman', 'kajur.pending-approvals', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'pending_approvals', $pendingApprovalsCount],
                ['Scan QR', 'kajur.qr-scanner', 'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z', null],
                ['Pengembalian', 'kajur.pending-returns', 'M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6', 'pending_returns', $pendingReturnsCount],
                ['Riwayat Peminjaman', 'kajur.history', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', null],
                ['Pelaporan', 'kajur.reporting', 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', null],
                ['Profil', 'kajur.profile', 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', null],
            ];
            @endphp
            @foreach($menus as $m)
            <a href="{{ route($m[1]) }}" class="nav-item {{ request()->routeIs($m[1]) ? 'active' : '' }}" data-menu="{{ $m[3] ?? '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $m[2] }}"/></svg>
                {{ $m[0] }}
                @if($m[3])
                    <span class="nav-badge" data-badge="{{ $m[3] }}" style="display: {{ $m[4] > 0 ? 'inline-flex' : 'none' }}">{{ $m[4] }}</span>
                @endif
            </a>
            @endforeach
        </nav>
        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">{{ auth()->check() ? strtoupper(substr(auth()->user()->name,0,2)) : 'KJ' }}</div>
                <div>
                    <div class="user-name">{{ auth()->check() ? auth()->user()->name : 'Kepala Jurusan' }}</div>
                    <div class="user-role">Kepala Jurusan {{ $jurusanNama }}</div>
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

    <div class="main">
        <div class="topbar">
            <button type="button" id="hamburgerBtn" class="hamburger-btn" title="Buka Menu" aria-label="Toggle Menu">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="topbar-right">
                <a href="{{ route('home') }}" class="topbar-icon" title="Beranda" style="text-decoration:none">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </a>
                <button class="topbar-icon" id="themeBtn" title="Ganti Mode (Alt+D)">
                    <svg class="t-moon" xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg class="t-sun" xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10A5 5 0 0012 7z"/></svg>
                </button>
                <div class="topbar-user-wrap">
                    <button type="button" id="profileBtn" class="topbar-user">
                        <div class="topbar-avatar">{{ auth()->check() ? strtoupper(substr(auth()->user()->name,0,2)) : 'KJ' }}</div>
                        <div class="topbar-uname">{{ auth()->check() ? explode(' ',auth()->user()->name)[0] : 'Kajur' }}</div>
                        <svg class="chevron-icon" xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;color:var(--subtle);margin-left:2px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="profileDropdown" class="profile-dropdown-menu">
                        <div class="pdm-header">
                            <div class="pdm-avatar">{{ auth()->check() ? strtoupper(substr(auth()->user()->name,0,2)) : 'KJ' }}</div>
                            <div class="pdm-info">
                                <div class="pdm-name">{{ auth()->check() ? auth()->user()->name : 'Kepala Jurusan' }}</div>
                                <div class="pdm-role">Kepala Jurusan {{ $jurusanNama }}</div>
                            </div>
                        </div>
                        <div class="pdm-divider"></div>
                        <div class="pdm-links">
                            <form method="POST" action="{{ route('logout') }}" class="pdm-logout-form">
                                @csrf
                                <button type="submit" class="pdm-logout-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="pdm-link-icon" style="color:var(--accent)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    <span>Log Out</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            @if(session('error'))
                <div style="background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3);color:#dc2626;padding:12px 16px;border-radius:10px;margin-bottom:20px;font-size:13.5px;font-weight:600;display:flex;align-items:center;gap:8px;">
                    <svg style="width:18px;height:18px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div style="background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.3);color:#059669;padding:12px 16px;border-radius:10px;margin-bottom:20px;font-size:13.5px;font-weight:600;display:flex;align-items:center;gap:8px;">
                    <svg style="width:18px;height:18px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @yield('content')
        </div>
    </div>

    <script>
    // Theme toggle
    (function(){
        var KEY='sipbar-kajur-theme',html=document.documentElement,btn=document.getElementById('themeBtn');
        function apply(dark){
            if(dark) html.classList.add('dark'); else html.classList.remove('dark');
            if(btn) btn.title=dark?'Mode Terang (Alt+D)':'Mode Gelap (Alt+D)';
        }
        var saved=localStorage.getItem(KEY),d=window.matchMedia('(prefers-color-scheme: dark)').matches;
        apply(saved==='dark'||(saved===null&&d));
        function toggle(){
            var isDark=!html.classList.contains('dark');
            localStorage.setItem(KEY,isDark?'dark':'light');
            apply(isDark);
            if(btn){btn.style.transform='rotate(20deg) scale(.85)';setTimeout(function(){btn.style.transform=''},250);}
        }
        if(btn) btn.addEventListener('click',toggle);
        document.addEventListener('keydown',function(e){if(e.altKey&&e.key==='d')toggle();});
    })();

    // Dropdown functionality
    (function(){
        var activeDropdown=null;

        function toggleDropdown(btn,dropdown){
            if(activeDropdown && activeDropdown!==dropdown){
                activeDropdown.classList.remove('show');
            }
            dropdown.classList.toggle('show');
            activeDropdown=dropdown.classList.contains('show')?dropdown:null;
        }

        // Profile dropdown
        var profileBtn=document.getElementById('profileBtn');
        var profileDropdown=document.getElementById('profileDropdown');
        if(profileBtn&&profileDropdown){
            profileBtn.addEventListener('click',function(e){
                e.stopPropagation();
                toggleDropdown(profileBtn,profileDropdown);
            });
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click',function(){
            if(activeDropdown){
                activeDropdown.classList.remove('show');
                activeDropdown=null;
            }
        });
    })();

    // Mobile sidebar toggle
    (function(){
        var hamburger=document.getElementById('hamburgerBtn');
        var sidebar=document.getElementById('sidebar');
        var overlay=document.getElementById('sidebarOverlay');
        
        if(hamburger){
            hamburger.addEventListener('click',function(e){
                e.stopPropagation();
                sidebar.classList.toggle('open');
                overlay.classList.toggle('active');
            });
        }
        
        if(overlay){
            overlay.addEventListener('click',function(){
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
            });
        }
    })();
    </script>
    @stack('scripts')
</body>
</html>