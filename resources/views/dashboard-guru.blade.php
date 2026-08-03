<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Guru – SIPBAR</title>
<script>
(function(){
    var s=localStorage.getItem('sipbar-guru-theme');
    var d=window.matchMedia('(prefers-color-scheme: dark)').matches;
    if(s==='dark'||(s===null&&d)) document.documentElement.classList.add('dark');
})();
</script>
@vite(['resources/css/app.css','resources/js/app.js'])
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html,body{height:100%;font-family:'Instrument Sans',ui-sans-serif,system-ui,sans-serif;-webkit-font-smoothing:antialiased}

/* ══ LIGHT TOKENS ══ */
:root{
    --bg:#f0f7ff;--bg2:#fff;--bg3:#e2eeff;
    --card:#fff;--border:#dbeafe;--border2:#e2e8f0;
    --text:#0f172a;--text2:#1e293b;--muted:#64748b;--subtle:#94a3b8;
    --topbar-bg:#fff;--topbar-bdr:#e2e8f0;--content-bg:#f0f7ff;
    --panel-bg:#fff;--input-bg:#f8fafc;--scrollbar:#cbd5e1;
    --sidebar-bg:#0f766e; /* teal untuk guru */
    --accent:#0f766e;--accent-light:#ccfbf1;--accent-text:#0f766e;
}
/* ══ DARK TOKENS ══ */
html.dark{
    --bg:#0d1117;--bg2:#111827;--bg3:#1a2535;
    --card:#111827;--border:#1e3a5f;--border2:#1e293b;
    --text:#f1f5f9;--text2:#e2e8f0;--muted:#94a3b8;--subtle:#64748b;
    --topbar-bg:#0d1117;--topbar-bdr:#1e293b;--content-bg:#080e18;
    --panel-bg:#111827;--input-bg:#0d1117;--scrollbar:#334155;
    --sidebar-bg:#064e3b;
    --accent:#10b981;--accent-light:rgba(16,185,129,.15);--accent-text:#34d399;
}
body{display:flex;background:var(--content-bg);color:var(--text);overflow:hidden;transition:background .25s,color .25s}

/* ══ SIDEBAR ══ */
.sidebar{width:210px;flex-shrink:0;background:var(--sidebar-bg);display:flex;flex-direction:column;height:100vh;position:fixed;left:0;top:0;z-index:40;transition:background .25s,transform .3s}
.sidebar-brand{display:flex;align-items:center;gap:10px;padding:22px 20px 18px;border-bottom:1px solid rgba(255,255,255,.12);text-decoration:none}
.brand-icon{width:32px;height:32px;background:rgba(255,255,255,.2);border-radius:8px;display:flex;align-items:center;justify-content:center}
.brand-name{font-size:15px;font-weight:800;color:#fff}
.brand-sub{font-size:10px;color:rgba(255,255,255,.6)}
.sidebar-nav{flex:1;padding:14px 12px;overflow-y:auto}
.sidebar-nav::-webkit-scrollbar{width:0}
.nav-item{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:10px;font-size:13px;font-weight:500;color:rgba(255,255,255,.65);text-decoration:none;margin-bottom:3px;transition:all .15s}
.nav-item:hover{background:rgba(255,255,255,.12);color:#fff}
.nav-item.active{background:rgba(255,255,255,.2);color:#fff;font-weight:700}
.nav-icon{width:16px;height:16px;flex-shrink:0}
.nav-badge{margin-left:auto;background:#fff;color:var(--accent);font-size:10px;font-weight:800;padding:1px 7px;border-radius:999px}
.sidebar-footer{padding:14px;border-top:1px solid rgba(255,255,255,.12)}
.user-card{display:flex;align-items:center;gap:9px;padding:8px 10px;border-radius:10px;background:rgba(255,255,255,.1);cursor:pointer}
.user-avatar{width:32px;height:32px;border-radius:50%;background:rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;flex-shrink:0}
.user-name{font-size:12px;font-weight:700;color:#fff;line-height:1.2}
.user-role{font-size:10px;color:rgba(255,255,255,.6)}
.logout-btn{display:flex;align-items:center;gap:9px;padding:9px 12px;border-radius:10px;margin-top:8px;font-size:13px;font-weight:500;color:rgba(255,255,255,.65);cursor:pointer;text-decoration:none;transition:all .15s;background:none;border:none;width:100%}
.logout-btn:hover{background:rgba(255,255,255,.12);color:#fff}

/* ══ MAIN ══ */
.main{margin-left:210px;flex:1;height:100vh;display:flex;flex-direction:column;overflow:hidden}

/* ══ TOPBAR ══ */
.topbar{height:58px;background:var(--topbar-bg);border-bottom:1px solid var(--topbar-bdr);display:flex;align-items:center;padding:0 24px;gap:12px;flex-shrink:0;transition:background .25s}
.topbar-search{display:flex;align-items:center;gap:8px;background:var(--input-bg);border:1px solid var(--border2);border-radius:10px;padding:7px 14px;width:280px}
.topbar-search input{background:none;border:none;outline:none;font-size:13px;color:var(--text);width:100%}
.topbar-search input::placeholder{color:var(--subtle)}
.topbar-right{margin-left:auto;display:flex;align-items:center;gap:10px}
.topbar-icon{width:36px;height:36px;background:var(--bg3);border:1px solid var(--border2);border-radius:10px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--muted);transition:all .15s;position:relative}
.topbar-icon:hover{background:var(--border2);color:var(--text)}
.notif-dot{position:absolute;top:7px;right:8px;width:7px;height:7px;background:#ef4444;border-radius:50%;border:2px solid var(--topbar-bg);transition:border-color .25s}
.topbar-user{display:flex;align-items:center;gap:8px;padding:4px 10px 4px 4px;background:var(--bg3);border:1px solid var(--border2);border-radius:999px;cursor:pointer}
.topbar-avatar{width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#0f766e,#06b6d4);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff}
.topbar-uname{font-size:13px;font-weight:600;color:var(--text2)}
.t-sun{display:none}.t-moon{display:block}
html.dark .t-sun{display:block}html.dark .t-moon{display:none}

/* ══ CONTENT ══ */
.content{flex:1;overflow-y:auto;padding:22px 24px;background:var(--content-bg);transition:background .25s}
.content::-webkit-scrollbar{width:4px}
.content::-webkit-scrollbar-thumb{background:var(--scrollbar);border-radius:2px}
.content-grid{display:grid;grid-template-columns:1fr 300px;gap:18px}

/* ══ HERO CARD ══ */
.hero-card{background:linear-gradient(135deg,#0f766e 0%,#0d9488 55%,#06b6d4 100%);border-radius:18px;padding:24px 28px;display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;overflow:hidden;position:relative}
.hero-card::before{content:'';position:absolute;top:-40px;right:80px;width:180px;height:180px;background:rgba(255,255,255,.06);border-radius:50%}
.hero-title{font-size:22px;font-weight:800;color:#fff;margin-bottom:4px}
.hero-sub{font-size:13px;color:rgba(255,255,255,.8);margin-bottom:14px;max-width:380px;line-height:1.5}
.hero-link{display:inline-flex;align-items:center;gap:6px;font-size:12px;font-weight:700;color:#fff;background:rgba(255,255,255,.2);padding:6px 14px;border-radius:999px;text-decoration:none;transition:background .2s}
.hero-link:hover{background:rgba(255,255,255,.32)}
.hero-illustration{width:110px;height:90px;flex-shrink:0}

/* ══ STAT CARDS ══ */
.stat-row{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:18px}
.stat-card{background:var(--card);border-radius:14px;padding:18px 20px;border:1px solid var(--border2);transition:box-shadow .2s,transform .2s;cursor:default}
.stat-card:hover{box-shadow:0 8px 24px rgba(15,118,110,.1);transform:translateY(-2px)}
.stat-card-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:10px}
.stat-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center}
.stat-num{font-size:26px;font-weight:800;line-height:1}
.stat-label{font-size:12px;color:var(--muted);margin-top:4px}
.stat-badge{font-size:11px;font-weight:700;padding:3px 8px;border-radius:999px}

/* ══ PANEL ══ */
.panel{background:var(--panel-bg);border-radius:16px;border:1px solid var(--border2);padding:18px 20px;margin-bottom:18px}
.panel:last-child{margin-bottom:0}
.panel-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px}
.panel-title{font-size:14px;font-weight:700;color:var(--text)}
.panel-action{font-size:12px;color:var(--accent);font-weight:600;text-decoration:none;cursor:pointer;display:flex;align-items:center;gap:4px}

/* ══ PERMOHONAN / REQUEST ITEM ══ */
.req-item{background:var(--bg);border:1px solid var(--border2);border-radius:12px;padding:14px 16px;margin-bottom:10px;transition:border-color .15s}
.req-item:last-child{margin-bottom:0}
.req-item:hover{border-color:var(--accent)}
.req-top{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:8px}
.req-student{display:flex;align-items:center;gap:9px}
.req-avatar{width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#0f766e,#06b6d4);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;flex-shrink:0}
.req-student-name{font-size:13px;font-weight:700;color:var(--text)}
.req-student-class{font-size:11px;color:var(--muted)}
.req-badge{font-size:10px;font-weight:700;padding:3px 8px;border-radius:999px;white-space:nowrap}
.badge-pending{background:rgba(245,158,11,.12);color:#f59e0b}
.badge-approved{background:rgba(16,185,129,.12);color:#10b981}
.badge-rejected{background:rgba(239,68,68,.12);color:#ef4444}
.req-item-name{font-size:13px;font-weight:600;color:var(--text2);margin-bottom:4px}
.req-item-detail{font-size:11px;color:var(--muted);display:flex;align-items:center;gap:12px}
.req-actions{display:flex;gap:8px;margin-top:10px}
.btn-approve{display:inline-flex;align-items:center;gap:5px;padding:6px 14px;background:var(--accent-light);color:var(--accent-text);border:1px solid transparent;border-radius:8px;font-size:12px;font-weight:700;cursor:pointer;transition:all .15s}
.btn-approve:hover{background:var(--accent);color:#fff}
.btn-reject{display:inline-flex;align-items:center;gap:5px;padding:6px 14px;background:rgba(239,68,68,.08);color:#ef4444;border:1px solid transparent;border-radius:8px;font-size:12px;font-weight:700;cursor:pointer;transition:all .15s}
.btn-reject:hover{background:#ef4444;color:#fff}
.btn-detail{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:var(--bg3);color:var(--muted);border:1px solid var(--border2);border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;margin-left:auto;transition:all .15s}
.btn-detail:hover{color:var(--text);border-color:var(--muted)}

/* ══ SISWA BIMBINGAN ══ */
.student-item{display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--border2)}
.student-item:last-child{border-bottom:none}
.student-ava{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;flex-shrink:0;background:linear-gradient(135deg,#0f766e,#2563eb)}
.student-name{font-size:13px;font-weight:700;color:var(--text)}
.student-nis{font-size:11px;color:var(--muted)}
.student-status{margin-left:auto;font-size:11px;font-weight:600;padding:3px 10px;border-radius:999px}
.s-active{background:rgba(16,185,129,.1);color:#10b981}
.s-borrow{background:rgba(59,130,246,.1);color:#3b82f6}

/* ══ RIGHT COL ══ */
.right-col{display:flex;flex-direction:column;gap:16px}

/* ══ CHART ══ */
.chart-bar-row{display:flex;align-items:flex-end;gap:6px;height:80px}
.chart-col{display:flex;flex-direction:column;align-items:center;gap:4px;flex:1}
.chart-bar-fill{width:100%;border-radius:4px 4px 0 0;min-height:4px}
.chart-bar-lbl{font-size:9px;color:var(--subtle);text-align:center}
.chart-bar-val{font-size:10px;font-weight:700;color:var(--muted)}

/* ══ ACTIVITY FEED ══ */
.activity-item{display:flex;align-items:flex-start;gap:10px;padding:9px 0;border-bottom:1px solid var(--border2)}
.activity-item:last-child{border-bottom:none}
.activity-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;margin-top:4px}
.activity-text{font-size:12px;color:var(--text2);line-height:1.5;flex:1}
.activity-time{font-size:10px;color:var(--subtle);white-space:nowrap;margin-top:2px}

/* ══ SCHEDULE PENGEMBALIAN ══ */
.sched-item{background:var(--bg);border:1px solid var(--border2);border-radius:10px;padding:10px 12px;margin-bottom:8px}
.sched-item.warn{border-color:#f59e0b;background:rgba(245,158,11,.05)}
.sched-item.over{border-color:#ef4444;background:rgba(239,68,68,.05)}
.sched-name{font-size:12px;font-weight:700;color:var(--text);margin-bottom:2px}
.sched-detail{font-size:11px;color:var(--muted)}
.sched-tag{font-size:10px;font-weight:700;padding:2px 7px;border-radius:999px}
.tag-ok{background:rgba(16,185,129,.1);color:#10b981}
.tag-warn{background:rgba(245,158,11,.1);color:#f59e0b}
.tag-over{background:rgba(239,68,68,.1);color:#ef4444}

/* ══ RESPONSIVE ══ */
@media(max-width:1100px){.content-grid{grid-template-columns:1fr}.stat-row{grid-template-columns:repeat(2,1fr)}}
@media(max-width:800px){.sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}.main{margin-left:0}.stat-row{grid-template-columns:repeat(2,1fr)}}
</style>
</head>
<body>

{{-- ===== SIDEBAR ===== --}}
<aside class="sidebar" id="sidebar">
    <a href="{{ route('teacher.dashboard') }}" class="sidebar-brand">
        <div class="brand-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 42" style="width:18px;height:18px;fill:#fff"><path fill-rule="evenodd" clip-rule="evenodd" d="M17.2 5.633 8.6.855 0 5.633v26.51l16.2 9 16.2-9v-8.442l7.6-4.223V9.856l-8.6-4.777-8.6 4.777V18.3l-5.6 3.111V5.633Z"/></svg>
        </div>
        <div><div class="brand-name">SIPBAR</div><div class="brand-sub">Sistem Inventaris</div></div>
    </a>
    <nav class="sidebar-nav">
        @php
        $menus = [
            ['Dashboard',        'teacher.dashboard', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', null],
            ['Permohonan',       'teacher.requests', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', '5'],
            ['Siswa Bimbingan',  'teacher.students', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', null],
            ['Peminjaman Aktif', 'teacher.loans',    'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4', '3'],
            ['Pengembalian',     'teacher.returns',  'M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6', null],
            ['Laporan',          'teacher.reports',  'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', null],
            ['Profil',           'profile.edit',     'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', null],
        ];
        @endphp
        @foreach($menus as $m)
        <a href="{{ route($m[1]) }}" class="nav-item {{ request()->routeIs($m[1]) ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $m[2] }}"/></svg>
            {{ $m[0] }}
            @if($m[3]) <span class="nav-badge">{{ $m[3] }}</span> @endif
        </a>
        @endforeach
    </nav>
    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">{{ auth()->check() ? strtoupper(substr(auth()->user()->name,0,2)) : 'GR' }}</div>
            <div>
                <div class="user-name">{{ auth()->check() ? auth()->user()->name : 'Budi Santoso' }}</div>
                <div class="user-role">Guru Pembimbing</div>
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
            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;color:var(--subtle);flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" placeholder="Cari siswa atau permohonan...">
        </div>
        <div class="topbar-right">
            {{-- Theme toggle --}}
            <button class="topbar-icon" id="themeBtn" title="Ganti Mode (Alt+D)">
                <svg class="t-moon" xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                <svg class="t-sun" xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10A5 5 0 0012 7z"/></svg>
            </button>
            <div class="topbar-icon">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <div class="topbar-icon" style="position:relative">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                <div class="notif-dot"></div>
            </div>
            <div class="topbar-user">
                <div class="topbar-avatar">{{ auth()->check() ? strtoupper(substr(auth()->user()->name,0,2)) : 'GR' }}</div>
                <div class="topbar-uname">{{ auth()->check() ? explode(' ',auth()->user()->name)[0] : 'Guru' }}</div>
                <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;color:var(--subtle);margin-left:2px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="content">
        {{-- Hero --}}
        <div class="hero-card">
            <div>
                <div class="hero-title">Selamat Datang, {{ auth()->check() ? explode(' ',auth()->user()->name)[0] : 'Bapak/Ibu' }}! 👋</div>
                <div class="hero-sub">Anda memiliki <strong style="color:#fff">5 permohonan peminjaman</strong> dari siswa yang menunggu persetujuan Anda hari ini.</div>
                <a href="#" class="hero-link">
                    Lihat Permohonan
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
            <div class="hero-illustration">
                <svg viewBox="0 0 110 90" style="width:100%;height:100%">
                    <rect x="10" y="20" width="90" height="60" rx="6" fill="rgba(255,255,255,.12)"/>
                    <rect x="20" y="10" width="70" height="50" rx="5" fill="rgba(255,255,255,.2)"/>
                    <rect x="28" y="18" width="50" height="4" rx="2" fill="rgba(255,255,255,.5)"/>
                    <rect x="28" y="25" width="35" height="3" rx="1.5" fill="rgba(255,255,255,.35)"/>
                    <rect x="28" y="31" width="42" height="3" rx="1.5" fill="rgba(255,255,255,.35)"/>
                    <circle cx="75" cy="18" r="12" fill="rgba(255,255,255,.2)"/>
                    <path d="M70 18 L74 22 L81 14" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    <rect x="28" y="40" width="18" height="12" rx="3" fill="rgba(255,255,255,.15)"/>
                    <rect x="50" y="40" width="18" height="12" rx="3" fill="rgba(16,185,129,.4)"/>
                    <rect x="72" y="40" width="18" height="12" rx="3" fill="rgba(255,255,255,.12)"/>
                </svg>
            </div>
        </div>

        {{-- Stat Row --}}
        <div class="stat-row">
            @php
            $stats = [
                ['5',  'Menunggu Approval', '#f59e0b','#fffbeb','Permohonan','M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['28', 'Siswa Bimbingan',   '#0f766e','#ccfbf1','Siswa',    'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['8',  'Peminjaman Aktif',  '#3b82f6','#eff6ff','Barang',   'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                ['2',  'Jatuh Tempo Dekat', '#ef4444','#fef2f2','Hari Ini', 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
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

        {{-- CONTENT GRID --}}
        <div class="content-grid">
            {{-- LEFT --}}
            <div>
                {{-- Permohonan Peminjaman --}}
                <div class="panel">
                    <div class="panel-header">
                        <div>
                            <div class="panel-title">Permohonan Peminjaman</div>
                            <div style="font-size:11px;color:var(--muted);margin-top:2px">Menunggu persetujuan Anda</div>
                        </div>
                        <div style="display:flex;gap:6px">
                            @foreach(['Semua','Menunggu','Disetujui','Ditolak'] as $t)
                            <button onclick="filterReq(this,'{{ strtolower($t) }}')"
                                style="padding:4px 10px;border-radius:6px;font-size:11px;font-weight:600;border:1px solid var(--border2);cursor:pointer;transition:all .15s;background:{{ $t==='Semua'?'var(--accent)':'var(--bg3)' }};color:{{ $t==='Semua'?'#fff':'var(--muted)' }}">
                                {{ $t }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                    @php
                    $requests = [
                        ['Ahmad Fauzi','XII IPA 1','AF','Proyektor Epson EB-S41','3 Agu 2026 – 5 Agu 2026','Presentasi PKL','pending'],
                        ['Siti Rahayu','XI IPS 2','SR','Laptop Dell Inspiron','3 Agu 2026 – 4 Agu 2026','Ujian Praktik TIK','pending'],
                        ['Budi Pratama','XII IPA 1','BP','Bola Basket × 2','3 Agu 2026 – 3 Agu 2026','Latihan Ekskul','approved'],
                        ['Dina Kusuma','X IPA 3','DK','Mikroskop Olympus','4 Agu 2026 – 4 Agu 2026','Praktikum Biologi','pending'],
                        ['Rizki Aditya','XI IPA 2','RA','Kamera DSLR','5 Agu 2026 – 7 Agu 2026','Dokumentasi Lomba','rejected'],
                    ];
                    $badgeMap = ['pending'=>['badge-pending','Menunggu'],'approved'=>['badge-approved','Disetujui'],'rejected'=>['badge-rejected','Ditolak']];
                    @endphp

                    @foreach($requests as $r)
                    <div class="req-item" data-status="{{ $r[6] }}">
                        <div class="req-top">
                            <div class="req-student">
                                <div class="req-avatar">{{ $r[2] }}</div>
                                <div>
                                    <div class="req-student-name">{{ $r[0] }}</div>
                                    <div class="req-student-class">Kelas {{ $r[1] }}</div>
                                </div>
                            </div>
                            <span class="req-badge {{ $badgeMap[$r[6]][0] }}">
                                @if($r[6]==='pending')
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:10px;height:10px;display:inline;margin-right:2px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @elseif($r[6]==='approved')
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:10px;height:10px;display:inline;margin-right:2px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:10px;height:10px;display:inline;margin-right:2px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                @endif
                                {{ $badgeMap[$r[6]][1] }}
                            </span>
                        </div>
                        <div class="req-item-name">📦 {{ $r[3] }}</div>
                        <div class="req-item-detail">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px;display:inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $r[4] }}
                            </span>
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px;display:inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                Keperluan: {{ $r[5] }}
                            </span>
                        </div>
                        @if($r[6] === 'pending')
                        <div class="req-actions">
                            <button class="btn-approve" onclick="approveReq(this)">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Setujui
                            </button>
                            <button class="btn-reject" onclick="rejectReq(this)">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Tolak
                            </button>
                            <button class="btn-detail">Detail</button>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                {{-- Siswa Bimbingan --}}
                <div class="panel">
                    <div class="panel-header">
                        <div class="panel-title">Daftar Siswa Bimbingan</div>
                        <a href="#" class="panel-action">
                            Lihat Semua
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                    @php
                    $students = [
                        ['Ahmad Fauzi',   '2024001','XII IPA 1','Meminjam','#3b82f6','AF'],
                        ['Siti Rahayu',   '2024002','XI IPS 2', 'Aktif',   '#10b981','SR'],
                        ['Budi Pratama',  '2024003','XII IPA 1','Meminjam','#3b82f6','BP'],
                        ['Dina Kusuma',   '2024004','X IPA 3',  'Aktif',   '#10b981','DK'],
                        ['Rizki Aditya',  '2024005','XI IPA 2', 'Aktif',   '#10b981','RA'],
                        ['Putri Ananda',  '2024006','XII IPS 1','Aktif',   '#10b981','PA'],
                    ];
                    @endphp
                    @foreach($students as $st)
                    <div class="student-item">
                        <div class="student-ava">{{ $st[5] }}</div>
                        <div>
                            <div class="student-name">{{ $st[0] }}</div>
                            <div class="student-nis">NIS: {{ $st[1] }} • {{ $st[2] }}</div>
                        </div>
                        <div class="student-status {{ $st[3]==='Meminjam' ? 's-borrow' : 's-active' }}" style="margin-left:auto">
                            {{ $st[3] }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- RIGHT COLUMN --}}
            <div class="right-col">
                {{-- Aktivitas terbaru --}}
                <div class="panel">
                    <div class="panel-header">
                        <div class="panel-title">Aktivitas Terkini</div>
                        <span style="font-size:11px;background:var(--bg3);color:var(--muted);padding:3px 10px;border-radius:999px">Hari ini</span>
                    </div>
                    @php
                    $activities = [
                        ['Ahmad Fauzi mengajukan permohonan Proyektor Epson','2 menit lalu','#f59e0b'],
                        ['Siti Rahayu mengajukan permohonan Laptop Dell','15 menit lalu','#f59e0b'],
                        ['Anda menyetujui peminjaman Bola Basket (Budi P.)','1 jam lalu','#10b981'],
                        ['Rizki Aditya: permohonan Kamera DSLR ditolak','2 jam lalu','#ef4444'],
                        ['Dina Kusuma mengembalikan Mikroskop tepat waktu','3 jam lalu','#3b82f6'],
                    ];
                    @endphp
                    @foreach($activities as $act)
                    <div class="activity-item">
                        <div class="activity-dot" style="background:{{ $act[2] }}"></div>
                        <div style="flex:1">
                            <div class="activity-text">{{ $act[0] }}</div>
                            <div class="activity-time">{{ $act[1] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Grafik persetujuan --}}
                <div class="panel">
                    <div class="panel-header">
                        <div class="panel-title">Grafik Permohonan</div>
                        <span style="font-size:10px;color:var(--muted)">6 Bulan</span>
                    </div>
                    <div style="font-size:26px;font-weight:900;color:var(--text);line-height:1">42</div>
                    <div style="font-size:11px;color:var(--muted);margin-bottom:12px">Total permohonan disetujui</div>
                    <div class="chart-bar-row">
                        @php $bars=[[3,'Jan',60],[2,'Feb',40],[5,'Mar',100],[4,'Apr',80],[6,'Mei',120],[4,'Jun',80]]; $mx=120; @endphp
                        @foreach($bars as $b)
                        <div class="chart-col">
                            <div class="chart-bar-val">{{ $b[0] }}</div>
                            <div class="chart-bar-fill" style="height:{{ round($b[2]/$mx*68) }}px;background:{{ $b[2]==120?'var(--accent)':'rgba(15,118,110,.35)' }}"></div>
                            <div class="chart-bar-lbl">{{ $b[1] }}</div>
                        </div>
                        @endforeach
                    </div>
                    <div style="display:flex;gap:12px;margin-top:12px;font-size:11px">
                        <span style="display:flex;align-items:center;gap:5px;color:var(--muted)"><span style="width:8px;height:8px;background:var(--accent);border-radius:2px"></span>Disetujui</span>
                        <span style="display:flex;align-items:center;gap:5px;color:var(--muted)"><span style="width:8px;height:8px;background:#ef4444;border-radius:2px"></span>Ditolak: 5</span>
                    </div>
                </div>

                {{-- Jadwal Pengembalian --}}
                <div class="panel">
                    <div class="panel-header">
                        <div class="panel-title">Monitor Pengembalian</div>
                        <a href="#" class="panel-action">Semua</a>
                    </div>
                    @php
                    $schedules = [
                        ['Budi Pratama','Bola Basket × 2','Hari ini 17:00','warn'],
                        ['Ahmad Fauzi','Proyektor Epson','5 Agu 2026','ok'],
                        ['Siti Rahayu','Laptop Dell','4 Agu 2026','ok'],
                        ['Dina Kusuma','Mikroskop','Terlambat 1 hari','over'],
                    ];
                    $tagMap=['ok'=>['tag-ok','Tepat Waktu'],'warn'=>['tag-warn','Jatuh Tempo'],'over'=>['tag-over','Terlambat']];
                    @endphp
                    @foreach($schedules as $sc)
                    <div class="sched-item {{ $sc[3] }}">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:3px">
                            <div class="sched-name">{{ $sc[0] }}</div>
                            <span class="sched-tag {{ $tagMap[$sc[3]][0] }}">{{ $tagMap[$sc[3]][1] }}</span>
                        </div>
                        <div class="sched-detail">
                            {{ $sc[1] }} &nbsp;•&nbsp;
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:10px;height:10px;display:inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $sc[2] }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>{{-- /content-grid --}}
    </div>{{-- /content --}}
</div>{{-- /main --}}

<script>
// Theme toggle
(function(){
    var KEY='sipbar-guru-theme',html=document.documentElement,btn=document.getElementById('themeBtn');
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

// Filter permohonan
function filterReq(el, status){
    el.closest('div').querySelectorAll('button').forEach(b=>{
        b.style.background='var(--bg3)'; b.style.color='var(--muted)';
    });
    el.style.background='var(--accent)'; el.style.color='#fff';
    document.querySelectorAll('.req-item').forEach(item=>{
        item.style.display=(status==='semua'||item.dataset.status===status)?'block':'none';
    });
}

// Approve/Reject (UI only — real logic via backend)
function approveReq(btn){
    var item=btn.closest('.req-item');
    item.querySelector('.req-badge').className='req-badge badge-approved';
    item.querySelector('.req-badge').innerHTML='<svg xmlns="http://www.w3.org/2000/svg" style="width:10px;height:10px;display:inline;margin-right:2px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Disetujui';
    item.querySelector('.req-actions').remove();
    item.dataset.status='approved';
}
function rejectReq(btn){
    var item=btn.closest('.req-item');
    item.querySelector('.req-badge').className='req-badge badge-rejected';
    item.querySelector('.req-badge').innerHTML='<svg xmlns="http://www.w3.org/2000/svg" style="width:10px;height:10px;display:inline;margin-right:2px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>Ditolak';
    item.querySelector('.req-actions').remove();
    item.dataset.status='rejected';
    item.style.opacity='.6';
}

// Mobile sidebar
window.addEventListener('resize',function(){if(window.innerWidth>800)document.getElementById('sidebar').classList.remove('open');});
</script>
</body>
</html>
