<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIPBAR Siswa')</title>
    {{-- Anti-flash --}}
    <script>
    (function(){
        var s=localStorage.getItem('sipbar-siswa-theme');
        var d=window.matchMedia('(prefers-color-scheme: dark)').matches;
        if(s==='dark'||(s===null&&d)) document.documentElement.classList.add('dark');
    })();
    </script>
    {{-- Google Fonts: Space Grotesk + Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ═══════════════════════════════════════════════
           SIPBAR SISWA — DESIGN SYSTEM v2
           Font: Space Grotesk (heading) + Inter (body)
        ═══════════════════════════════════════════════ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body {
            height: 100%;
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
            font-size: 14px;
        }

        /* ══ LIGHT MODE TOKENS ══ */
        :root {
            --font-head:       'Space Grotesk', sans-serif;
            --font-body:       'Inter', sans-serif;

            /* Surfaces */
            --bg:              #f0f4f8;
            --bg2:             #ffffff;
            --bg3:             #e8edf2;
            --card:            #ffffff;
            --card2:           #f8fafc;
            --border:          #dde3ea;
            --border2:         #e4e9ef;

            /* Text */
            --text:            #0d1829;
            --text2:           #2d3748;
            --muted:           #5a6a7e;
            --subtle:          #8898aa;

            /* Primary (Blue) */
            --primary:         #2563eb;
            --primary-dark:    #1d4ed8;
            --primary-light:   #eff6ff;
            --primary-muted:   #bfdbfe;

            /* Sidebar */
            --sidebar-bg:      #1e3a8a;
            --sidebar-dark:    #162d6e;
            --sidebar-accent:  #3b82f6;

            /* Topbar */
            --topbar-bg:       #ffffff;
            --topbar-bdr:      #e4e9ef;

            /* Content */
            --content-bg:      #f0f4f8;
            --panel-bg:        #ffffff;
            --input-bg:        #ffffff;
            --scrollbar:       #c8d3e0;

            /* ── Semantic Status Colors ── */
            --s-pending:       #d97706;
            --s-pending-bg:    #fffbeb;
            --s-pending-bdr:   #fde68a;
            --s-approved:      #2563eb;
            --s-approved-bg:   #eff6ff;
            --s-approved-bdr:  #bfdbfe;
            --s-borrowed:      #0891b2;
            --s-borrowed-bg:   #ecfeff;
            --s-borrowed-bdr:  #a5f3fc;
            --s-returned:      #059669;
            --s-returned-bg:   #ecfdf5;
            --s-returned-bdr:  #a7f3d0;
            --s-rejected:      #dc2626;
            --s-rejected-bg:   #fef2f2;
            --s-rejected-bdr:  #fecaca;
        }

        /* ══ DARK MODE TOKENS ══ */
        html.dark {
            --bg:              #0c1220;
            --bg2:             #111827;
            --bg3:             #1a2436;
            --card:            #131e2e;
            --card2:           #0f1a28;
            --border:          #1e2d45;
            --border2:         #243349;

            --text:            #f0f6ff;
            --text2:           #c9d8ef;
            --muted:           #7b93b4;
            --subtle:          #4d6480;

            --primary:         #3b82f6;
            --primary-dark:    #2563eb;
            --primary-light:   rgba(59,130,246,.15);
            --primary-muted:   rgba(59,130,246,.3);

            --sidebar-bg:      #0f1e3c;
            --sidebar-dark:    #091530;
            --sidebar-accent:  #60a5fa;

            --topbar-bg:       #0c1220;
            --topbar-bdr:      #1e2d45;
            --content-bg:      #0a1020;
            --panel-bg:        #131e2e;
            --input-bg:        #111827;
            --scrollbar:       #243349;

            /* Status dark */
            --s-pending:       #f59e0b;
            --s-pending-bg:    rgba(245,158,11,.12);
            --s-pending-bdr:   rgba(245,158,11,.25);
            --s-approved:      #60a5fa;
            --s-approved-bg:   rgba(96,165,250,.12);
            --s-approved-bdr:  rgba(96,165,250,.25);
            --s-borrowed:      #22d3ee;
            --s-borrowed-bg:   rgba(34,211,238,.10);
            --s-borrowed-bdr:  rgba(34,211,238,.22);
            --s-returned:      #34d399;
            --s-returned-bg:   rgba(52,211,153,.10);
            --s-returned-bdr:  rgba(52,211,153,.22);
            --s-rejected:      #f87171;
            --s-rejected-bg:   rgba(248,113,113,.10);
            --s-rejected-bdr:  rgba(248,113,113,.22);
        }

        body { display: flex; background: var(--content-bg); color: var(--text); overflow: hidden; transition: background .25s, color .25s; }

        /* ═══════════════════════════════
           SIDEBAR
        ═══════════════════════════════ */
        .sidebar {
            width: 224px; flex-shrink: 0;
            background: var(--sidebar-bg);
            display: flex; flex-direction: column;
            height: 100vh; position: fixed; left: 0; top: 0; z-index: 40;
            transition: background .25s, transform .3s;
            box-shadow: 2px 0 20px rgba(0,0,0,.18);
        }
        .sidebar.closed { transform: translateX(-100%); }
        .sidebar-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,.55);
            z-index: 35; display: none; backdrop-filter: blur(2px);
        }
        .sidebar-overlay.active { display: block; }

        /* Brand */
        .sidebar-brand {
            display: flex; align-items: center; gap: 14px;
            padding: 20px 18px 18px;
            border-bottom: 1px solid rgba(255,255,255,.1);
            text-decoration: none;
        }
        .sidebar-logo-wrap {
            width: 46px; height: 46px; border-radius: 50%;
            background: rgba(255,255,255,.95);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; overflow: hidden; padding: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,.3), 0 0 0 1.5px rgba(255,255,255,.15);
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .sidebar-brand:hover .sidebar-logo-wrap { transform: scale(1.05); box-shadow: 0 4px 14px rgba(0,0,0,.4); }
        .sidebar-brand-img {
            width: 100%; height: 100%;
            object-fit: contain;
        }
        .brand-name {
            font-family: var(--font-head); font-size: 18px; font-weight: 800;
            color: #fff; letter-spacing: -.02em; line-height: 1.15;
        }
        .brand-badge {
            font-size: 9.5px; font-weight: 700; color: var(--sidebar-accent);
            background: rgba(255,255,255,.95); padding: 2px 6px;
            border-radius: 4px; letter-spacing: .06em; display: inline-block; margin-top: 3px;
        }

        /* Nav */
        .sidebar-nav { flex: 1; padding: 14px 12px 10px; overflow-y: auto; }
        .sidebar-nav::-webkit-scrollbar { width: 0; }

        .nav-group-label {
            font-size: 9.5px; font-weight: 700; color: rgba(255,255,255,.38);
            letter-spacing: .1em; text-transform: uppercase;
            padding: 12px 12px 6px; margin-bottom: 2px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 10px;
            font-size: 13px; font-weight: 500; color: rgba(255,255,255,.78);
            text-decoration: none; margin-bottom: 2px;
            transition: all .15s ease; position: relative;
        }
        .nav-item:hover {
            background: rgba(255,255,255,.1); color: #fff;
        }
        .nav-item.active {
            background: rgba(59,130,246,.3);
            color: #fff; font-weight: 600;
            border-left: 3px solid var(--sidebar-accent);
            padding-left: 9px;
        }
        .nav-icon { width: 17px; height: 17px; flex-shrink: 0; transition: transform .15s; }
        .nav-item:hover .nav-icon { transform: translateX(1px); }
        .nav-badge {
            margin-left: auto; background: #ef4444; color: #fff;
            font-size: 10px; font-weight: 700; padding: 1px 6px;
            border-radius: 999px; min-width: 18px; text-align: center;
        }

        /* CTA Pinjam */
        .nav-cta {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; margin-top: 10px;
            background: rgba(59,130,246,.25); border: 1px solid rgba(59,130,246,.4);
            border-radius: 10px; text-decoration: none; transition: all .15s;
        }
        .nav-cta:hover { background: rgba(59,130,246,.4); transform: translateX(1px); }
        .nav-cta-icon { width: 30px; height: 30px; background: rgba(255,255,255,.18); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .nav-cta-label { font-size: 13px; font-weight: 700; color: #fff; }
        .nav-cta-sub { font-size: 10px; color: rgba(255,255,255,.7); margin-top: 1px; }

        /* Footer */
        .sidebar-footer { padding: 12px; border-top: 1px solid rgba(255,255,255,.1); }
        .user-card {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 10px; border-radius: 10px;
            background: rgba(255,255,255,.08); cursor: pointer;
            text-decoration: none; color: inherit; transition: background .15s;
        }
        .user-card:hover { background: rgba(255,255,255,.14); }
        .user-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--sidebar-accent); border: 2px solid rgba(255,255,255,.3);
            display: flex; align-items: center; justify-content: center;
            font-family: var(--font-head); font-size: 12px; font-weight: 700; color: #fff; flex-shrink: 0;
        }
        .user-name { font-size: 12.5px; font-weight: 700; color: #fff; line-height: 1.2; }
        .user-role { font-size: 10px; color: rgba(255,255,255,.55); }
        .logout-btn {
            display: flex; align-items: center; gap: 9px;
            padding: 9px 12px; border-radius: 10px; margin-top: 6px;
            font-size: 12.5px; font-weight: 500; color: rgba(255,255,255,.6);
            cursor: pointer; background: none; border: none; width: 100%;
            font-family: var(--font-body); transition: all .15s;
        }
        .logout-btn:hover { background: rgba(239,68,68,.2); color: #fca5a5; }

        /* ═══════════════════════════════
           MAIN LAYOUT
        ═══════════════════════════════ */
        .main { margin-left: 224px; flex: 1; height: 100vh; display: flex; flex-direction: column; overflow: hidden; transition: margin-left .3s; }
        .main.expanded { margin-left: 0; }

        /* ═══════════════════════════════
           TOPBAR
        ═══════════════════════════════ */
        .topbar {
            height: 62px; background: var(--topbar-bg);
            border-bottom: 1px solid var(--topbar-bdr);
            display: flex; align-items: center;
            padding: 0 24px; gap: 12px; flex-shrink: 0;
            transition: background .25s;
        }
        .topbar-search {
            display: flex; align-items: center; gap: 8px;
            background: var(--bg3); border: 1px solid var(--border2);
            border-radius: 10px; padding: 8px 14px; width: 260px; transition: border-color .15s;
        }
        .topbar-search:focus-within { border-color: var(--primary); }
        .topbar-search input {
            background: none; border: none; outline: none;
            font-size: 13px; color: var(--text); width: 100%; font-family: var(--font-body);
        }
        .topbar-search input::placeholder { color: var(--subtle); }
        .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 8px; }
        .topbar-icon {
            width: 38px; height: 38px; background: var(--bg3);
            border: 1px solid var(--border2); border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--muted); transition: all .15s; position: relative;
        }
        .topbar-icon:hover { background: var(--border2); color: var(--text); border-color: var(--primary); }
        .notif-dot {
            position: absolute; top: 7px; right: 8px;
            width: 7px; height: 7px; background: #ef4444;
            border-radius: 50%; border: 2px solid var(--topbar-bg); transition: border-color .25s;
        }
        /* Profile dropdown */
        .topbar-user-wrap { position: relative; }
        .topbar-user {
            display: flex; align-items: center; gap: 8px;
            padding: 4px 12px 4px 4px;
            background: var(--bg3); border: 1px solid var(--border2);
            border-radius: 999px; cursor: pointer; color: inherit;
            transition: all .15s; font-family: inherit; font-size: inherit;
        }
        .topbar-user:hover, .topbar-user.active { border-color: var(--primary); background: var(--border2); }
        .topbar-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: var(--primary-dark);
            display: flex; align-items: center; justify-content: center;
            font-family: var(--font-head); font-size: 11.5px; font-weight: 700; color: #fff;
        }
        .topbar-uname { font-size: 13px; font-weight: 600; color: var(--text2); }
        .chevron-icon { transition: transform .2s ease; }
        .topbar-user.active .chevron-icon { transform: rotate(180deg); }

        /* Profile Dropdown */
        .profile-dropdown-menu {
            position: absolute; top: calc(100% + 8px); right: 0; width: 260px;
            background: var(--card); border: 1px solid var(--border);
            border-radius: 14px; box-shadow: 0 12px 30px -4px rgba(0,0,0,.14), 0 4px 12px -2px rgba(0,0,0,.08);
            z-index: 100; padding: 8px; display: none;
        }
        .profile-dropdown-menu.show { display: block; animation: dropdownFade .15s ease-out; }
        @keyframes dropdownFade { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }
        .pdm-header { padding: 10px 12px; display: flex; align-items: center; gap: 10px; }
        .pdm-avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--primary-dark); display: flex; align-items: center; justify-content: center; font-family: var(--font-head); font-size: 13px; font-weight: 800; color: #fff; flex-shrink: 0; }
        .pdm-info { flex: 1; min-width: 0; }
        .pdm-name { font-size: 13px; font-weight: 700; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .pdm-role { font-size: 11px; color: var(--primary); font-weight: 600; }
        .pdm-divider { height: 1px; background: var(--border2); margin: 6px 0; }
        .pdm-link { display: flex; align-items: center; gap: 10px; padding: 8px 12px; border-radius: 8px; font-size: 12.5px; font-weight: 500; color: var(--text2); text-decoration: none; transition: all .15s; }
        .pdm-link:hover { background: var(--bg3); color: var(--primary); }
        .pdm-link-icon { width: 16px; height: 16px; color: var(--muted); flex-shrink: 0; }
        .pdm-logout-btn { display: flex; align-items: center; gap: 10px; padding: 8px 12px; border-radius: 8px; font-size: 12.5px; font-weight: 600; color: #dc2626; background: none; border: none; width: 100%; cursor: pointer; transition: all .15s; font-family: inherit; text-align: left; }
        .pdm-logout-btn:hover { background: rgba(239,68,68,.1); color: #b91c1c; }

        /* Theme toggle icons */
        .t-sun { display: none; } .t-moon { display: block; }
        html.dark .t-sun { display: block; } html.dark .t-moon { display: none; }

        /* Hamburger */
        .hamburger-btn {
            display: none; width: 38px; height: 38px;
            background: var(--bg3); border: 1px solid var(--border2);
            border-radius: 10px; align-items: center; justify-content: center;
            cursor: pointer; color: var(--muted); transition: all .15s;
        }
        .hamburger-btn:hover { background: var(--border2); color: var(--text); }

        /* ═══════════════════════════════
           CONTENT
        ═══════════════════════════════ */
        .content {
            flex: 1; overflow-y: auto; min-height: 0;
            padding: 24px 28px; background: var(--content-bg); transition: background .25s;
        }
        .content::-webkit-scrollbar { width: 5px; }
        .content::-webkit-scrollbar-thumb { background: var(--scrollbar); border-radius: 3px; }

        /* ═══════════════════════════════
           DESIGN SYSTEM COMPONENTS
        ═══════════════════════════════ */

        /* Page Header */
        .page-header {
            display: flex; align-items: flex-start; justify-content: space-between;
            margin-bottom: 24px; gap: 16px; flex-wrap: wrap;
        }
        .page-header-left { flex: 1; }
        .page-title {
            font-family: var(--font-head); font-size: 22px; font-weight: 800;
            color: var(--text); letter-spacing: -.02em; line-height: 1.2;
            display: flex; align-items: center; gap: 10px;
        }
        .page-title-count {
            font-family: var(--font-body); font-size: 12px; font-weight: 600;
            color: var(--muted); background: var(--bg3); border: 1px solid var(--border2);
            padding: 2px 10px; border-radius: 999px;
        }
        .page-subtitle { font-size: 13px; color: var(--muted); margin-top: 4px; line-height: 1.5; }

        /* Card */
        .s-card {
            background: var(--card); border: 1px solid var(--border2);
            border-radius: 16px; padding: 20px 22px;
            box-shadow: 0 1px 4px rgba(0,0,0,.04);
            transition: box-shadow .2s;
        }
        .s-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.07); }
        .s-card--flat { box-shadow: none; }
        .s-card--flat:hover { box-shadow: 0 2px 8px rgba(0,0,0,.05); }

        /* Card header */
        .s-card-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 18px;
        }
        .s-card-title {
            font-family: var(--font-head); font-size: 15px; font-weight: 700;
            color: var(--text); letter-spacing: -.01em;
        }
        .s-card-sub { font-size: 12px; color: var(--muted); margin-top: 2px; }
        .s-card-action { font-size: 12px; color: var(--primary); font-weight: 600; text-decoration: none; cursor: pointer; transition: color .15s; }
        .s-card-action:hover { color: var(--primary-dark); }

        /* Stat Card */
        .s-stat {
            background: var(--card); border: 1px solid var(--border2);
            border-radius: 14px; padding: 18px 20px;
            display: flex; align-items: center; gap: 14px;
            transition: all .2s; box-shadow: 0 1px 4px rgba(0,0,0,.04);
        }
        .s-stat:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.09); }
        .s-stat-icon {
            width: 44px; height: 44px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .s-stat-body { flex: 1; min-width: 0; }
        .s-stat-num {
            font-family: var(--font-head); font-size: 26px; font-weight: 800;
            color: var(--text); line-height: 1; letter-spacing: -.02em;
        }
        .s-stat-label { font-size: 12px; color: var(--muted); margin-top: 4px; font-weight: 500; }
        .s-stat-pill {
            font-size: 10px; font-weight: 700; padding: 2px 8px;
            border-radius: 999px; margin-top: 5px; display: inline-block;
        }

        /* Status Badges */
        .s-badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 10px; border-radius: 7px;
            font-size: 11px; font-weight: 700; letter-spacing: .02em;
            white-space: nowrap;
        }
        .s-badge-dot { width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }
        .s-badge--pending  { background: var(--s-pending-bg);  color: var(--s-pending);  border: 1px solid var(--s-pending-bdr); }
        .s-badge--approved { background: var(--s-approved-bg); color: var(--s-approved); border: 1px solid var(--s-approved-bdr); }
        .s-badge--borrowed { background: var(--s-borrowed-bg); color: var(--s-borrowed); border: 1px solid var(--s-borrowed-bdr); }
        .s-badge--returned { background: var(--s-returned-bg); color: var(--s-returned); border: 1px solid var(--s-returned-bdr); }
        .s-badge--rejected { background: var(--s-rejected-bg); color: var(--s-rejected); border: 1px solid var(--s-rejected-bdr); }

        /* Loan Row (dengan left-border accent) */
        .s-loan-row {
            display: flex; align-items: flex-start; gap: 14px;
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid var(--border2);
            background: var(--card2);
            margin-bottom: 10px;
            border-left: 4px solid var(--border2);
            transition: all .15s;
        }
        .s-loan-row:last-child { margin-bottom: 0; }
        .s-loan-row:hover { background: var(--bg3); border-color: var(--border); }
        .s-loan-row--pending  { border-left-color: var(--s-pending); }
        .s-loan-row--approved { border-left-color: var(--s-approved); }
        .s-loan-row--borrowed { border-left-color: var(--s-borrowed); }
        .s-loan-row--returned { border-left-color: var(--s-returned); }
        .s-loan-row--rejected { border-left-color: var(--s-rejected); }
        .s-loan-icon {
            width: 40px; height: 40px; border-radius: 10px;
            background: var(--bg3); display: flex; align-items: center;
            justify-content: center; flex-shrink: 0;
        }
        .s-loan-content { flex: 1; min-width: 0; }
        .s-loan-name { font-size: 13.5px; font-weight: 700; color: var(--text); line-height: 1.3; }
        .s-loan-code { font-size: 11px; color: var(--subtle); margin-top: 2px; }
        .s-loan-meta {
            display: flex; align-items: center; flex-wrap: wrap; gap: 12px;
            margin-top: 8px; font-size: 11.5px; color: var(--muted);
        }
        .s-loan-meta-item { display: flex; align-items: center; gap: 4px; }
        .s-loan-right { flex-shrink: 0; display: flex; flex-direction: column; align-items: flex-end; gap: 8px; }
        .s-loan-time { font-size: 10.5px; color: var(--subtle); }

        /* Buttons */
        .s-btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 18px; border-radius: 10px;
            font-size: 13px; font-weight: 600; font-family: var(--font-body);
            border: none; cursor: pointer; text-decoration: none;
            transition: all .2s; white-space: nowrap;
        }
        .s-btn--primary {
            background: var(--primary); color: #fff;
            box-shadow: 0 2px 8px rgba(37,99,235,.25);
        }
        .s-btn--primary:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(37,99,235,.35); }
        .s-btn--secondary {
            background: var(--bg3); color: var(--text2);
            border: 1px solid var(--border2);
        }
        .s-btn--secondary:hover { background: var(--border2); color: var(--text); }
        .s-btn--ghost { background: transparent; color: var(--primary); border: 1px solid var(--primary-muted); }
        .s-btn--ghost:hover { background: var(--primary-light); }
        .s-btn--danger { background: rgba(220,38,38,.1); color: #dc2626; border: 1px solid rgba(220,38,38,.2); }
        .s-btn--danger:hover { background: rgba(220,38,38,.18); }
        .s-btn--sm { padding: 6px 12px; font-size: 12px; border-radius: 8px; }
        .s-btn--icon { padding: 8px; border-radius: 9px; }

        /* Empty State */
        .s-empty {
            text-align: center; padding: 56px 24px;
            display: flex; flex-direction: column; align-items: center; gap: 4px;
        }
        .s-empty-icon-wrap {
            width: 72px; height: 72px; border-radius: 18px;
            background: var(--bg3); border: 1px solid var(--border2);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 16px; color: var(--subtle);
        }
        .s-empty-title {
            font-family: var(--font-head); font-size: 16px; font-weight: 700;
            color: var(--text); margin-bottom: 6px;
        }
        .s-empty-sub { font-size: 13px; color: var(--muted); margin-bottom: 20px; max-width: 320px; line-height: 1.6; }

        /* Filter Bar */
        .s-filter-bar {
            background: var(--card); border: 1px solid var(--border2);
            border-radius: 14px; padding: 16px 20px; margin-bottom: 20px;
        }
        .s-filter-grid { display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end; }
        .s-filter-item { display: flex; flex-direction: column; gap: 6px; flex: 1; min-width: 140px; }
        .s-filter-label { font-size: 11.5px; font-weight: 600; color: var(--text2); }
        .s-filter-input {
            width: 100%; padding: 9px 12px;
            background: var(--input-bg); border: 1px solid var(--border2);
            border-radius: 9px; font-size: 13px; color: var(--text);
            font-family: var(--font-body); transition: border-color .15s;
        }
        .s-filter-input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-light); }

        /* Info grid (for detail views) */
        .s-info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .s-info-item {
            padding: 14px 16px; background: var(--card2);
            border: 1px solid var(--border2); border-radius: 10px;
        }
        .s-info-label { font-size: 11px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; margin-bottom: 5px; }
        .s-info-value { font-size: 14px; font-weight: 600; color: var(--text); }

        /* Divider */
        .s-divider { height: 1px; background: var(--border2); margin: 16px 0; }

        /* ═══════════════════════════════
           RESPONSIVE
        ═══════════════════════════════ */
        @media (max-width: 1024px) {
            .s-info-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .hamburger-btn { display: flex; }
            .topbar-search { display: none; }
            .content { padding: 16px; }
            .page-header { flex-direction: column; gap: 12px; }
        }
        @media (max-width: 480px) {
            .s-filter-grid { flex-direction: column; }
            .s-filter-item { min-width: auto; }
        }
    </style>
</head>
<body>
    {{-- ===== SIDEBAR ===== --}}
    <aside class="sidebar" id="sidebar">
        <a href="{{ route('student.dashboard') }}" class="sidebar-brand">
            <div class="sidebar-logo-wrap">
                <img src="/build/assets/logosmkn.png" alt="Logo SMKN 1 Bangsri" class="sidebar-brand-img">
            </div>
            <div>
                <div class="brand-name">SIPBAR</div>
                <span class="brand-badge">PANEL SISWA</span>
            </div>
        </a>

        <nav class="sidebar-nav">
            @php
            $activeBorrowedCount = auth()->check()
                ? \App\Models\BorrowingRequest::where('user_id', auth()->id())->whereIn('status', ['borrowed', 'approved'])->count()
                : null;
            @endphp

            <div class="nav-group-label">Menu Utama</div>

            <a href="{{ route('student.dashboard') }}" class="nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('student.catalog') }}" class="nav-item {{ request()->routeIs('student.catalog') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Katalog Barang
            </a>

            <div class="nav-group-label" style="margin-top:8px">Aktivitas</div>

            <a href="{{ route('student.loans') }}" class="nav-item {{ request()->routeIs('student.loans') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                Peminjaman
            </a>
            <a href="{{ route('student.returns.index') }}" class="nav-item {{ request()->routeIs('student.returns.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                Pengembalian
                @if($activeBorrowedCount > 0)
                <span class="nav-badge">{{ $activeBorrowedCount }}</span>
                @endif
            </a>
            <a href="{{ route('student.history') }}" class="nav-item {{ request()->routeIs('student.history') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Riwayat
            </a>
            <a href="{{ route('student.announcements') }}" class="nav-item {{ request()->routeIs('student.announcements') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                QR Barang
            </a>

            <div class="nav-group-label" style="margin-top:8px">Akun</div>

            <a href="{{ route('student.profile') }}" class="nav-item {{ request()->routeIs('student.profile') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profil Saya
            </a>

            {{-- CTA Pinjam Barang --}}
            <a href="{{ route('student.catalog') }}" class="nav-cta">
                <div class="nav-cta-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </div>
                <div>
                    <div class="nav-cta-label">Pinjam Barang</div>
                    <div class="nav-cta-sub">Buka katalog sekarang</div>
                </div>
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('student.profile') }}" class="user-card">
                @if(auth()->check() && auth()->user()->hasProfilePhoto())
                    <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="user-avatar" style="object-fit:cover">
                @else
                    <div class="user-avatar">{{ auth()->check() ? strtoupper(substr(auth()->user()->name,0,2)) : 'SI' }}</div>
                @endif
                <div>
                    <div class="user-name">{{ auth()->check() ? auth()->user()->name : 'Siswa' }}</div>
                    <div class="user-role">Peminjam Barang</div>
                </div>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- ===== MAIN ===== --}}
    <div class="main" id="main">
        {{-- TOPBAR --}}
        <div class="topbar">
            <button id="hamburgerBtn" class="hamburger-btn" title="Toggle Menu">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="topbar-search">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;color:var(--subtle);flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" placeholder="Cari barang atau riwayat...">
            </div>
            <div class="topbar-right">
                <a href="{{ route('home') }}" class="topbar-icon" title="Beranda" style="text-decoration:none">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </a>
                <button id="themeToggle" class="topbar-icon" title="Ganti Tema (Alt+D)">
                    <svg class="t-sun" xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <svg class="t-moon" xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>
                @include('partials.notif-widget', ['iconClass' => 'topbar-icon'])
                {{-- Profile Dropdown --}}
                <div class="topbar-user-wrap">
                    <button type="button" id="profileBtn" class="topbar-user">
                        @if(auth()->check() && auth()->user()->hasProfilePhoto())
                            <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="topbar-avatar" style="object-fit:cover">
                        @else
                            <div class="topbar-avatar">{{ auth()->check() ? strtoupper(substr(auth()->user()->name,0,2)) : 'SI' }}</div>
                        @endif
                        <div class="topbar-uname">{{ auth()->check() ? explode(' ',auth()->user()->name)[0] : 'Siswa' }}</div>
                        <svg class="chevron-icon" xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;color:var(--subtle);margin-left:2px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="profileDropdown" class="profile-dropdown-menu">
                        <div class="pdm-header">
                            @if(auth()->check() && auth()->user()->hasProfilePhoto())
                                <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="pdm-avatar" style="object-fit:cover">
                            @else
                                <div class="pdm-avatar">{{ auth()->check() ? strtoupper(substr(auth()->user()->name,0,2)) : 'SI' }}</div>
                            @endif
                            <div class="pdm-info">
                                <div class="pdm-name">{{ auth()->check() ? auth()->user()->name : 'Siswa' }}</div>
                                <div class="pdm-role">Peminjam Barang</div>
                            </div>
                        </div>
                        <div class="pdm-divider"></div>
                        <a href="{{ route('student.profile') }}" class="pdm-link">
                            <svg xmlns="http://www.w3.org/2000/svg" class="pdm-link-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Profil Saya
                        </a>
                        <a href="{{ route('student.loans') }}" class="pdm-link">
                            <svg xmlns="http://www.w3.org/2000/svg" class="pdm-link-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            Peminjaman Saya
                        </a>
                        <div class="pdm-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="pdm-logout-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" class="pdm-link-icon" style="color:#ef4444" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            @yield('content')
        </div>
    </div>

    <script>
    (function(){
        // Theme
        var KEY='sipbar-siswa-theme', html=document.documentElement;
        var btn=document.getElementById('themeToggle');
        function applyTheme(dark){
            if(dark) html.classList.add('dark'); else html.classList.remove('dark');
        }
        var saved=localStorage.getItem(KEY), sys=window.matchMedia('(prefers-color-scheme: dark)').matches;
        applyTheme(saved==='dark'||(saved===null&&sys));
        if(btn) btn.addEventListener('click',function(){
            var isDark=!html.classList.contains('dark');
            localStorage.setItem(KEY,isDark?'dark':'light');
            applyTheme(isDark);
            btn.style.transform='rotate(20deg) scale(.85)';
            setTimeout(function(){btn.style.transform=''},250);
        });
        document.addEventListener('keydown',function(e){
            if(e.altKey&&e.key==='d'){
                var isDark=!html.classList.contains('dark');
                localStorage.setItem(KEY,isDark?'dark':'light');
                applyTheme(isDark);
            }
        });

        // Sidebar mobile
        var hamburger=document.getElementById('hamburgerBtn');
        var sidebar=document.getElementById('sidebar');
        var overlay=document.getElementById('sidebarOverlay');
        var main=document.getElementById('main');
        function toggleSidebar(){
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }
        if(hamburger) hamburger.addEventListener('click',toggleSidebar);
        if(overlay)   overlay.addEventListener('click',toggleSidebar);

        // Profile dropdown
        var profileBtn=document.getElementById('profileBtn');
        var profileDropdown=document.getElementById('profileDropdown');
        if(profileBtn&&profileDropdown){
            profileBtn.addEventListener('click',function(e){
                e.stopPropagation();
                var isOpen=profileDropdown.classList.contains('show');
                profileDropdown.classList.toggle('show');
                profileBtn.classList.toggle('active',!isOpen);
            });
            profileDropdown.addEventListener('click',function(e){e.stopPropagation();});
        }
        document.addEventListener('click',function(){
            if(profileDropdown&&profileDropdown.classList.contains('show')){
                profileDropdown.classList.remove('show');
                if(profileBtn) profileBtn.classList.remove('active');
            }
        });
    })();
    </script>
    @stack('scripts')
</body>
</html>
