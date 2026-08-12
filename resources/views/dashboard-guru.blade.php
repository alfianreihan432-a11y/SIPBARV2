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
        <livewire:teacher-dashboard />
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

    // Notification dropdown
    var notifBtn=document.getElementById('notifBtn');
    var notifDropdown=document.getElementById('notifDropdown');
    if(notifBtn&&notifDropdown){
        notifBtn.addEventListener('click',function(e){
            e.stopPropagation();
            toggleDropdown(notifBtn,notifDropdown);
        });
    }

    // Mail placeholder
    var mailBtn=document.getElementById('mailBtn');
    if(mailBtn){
        mailBtn.addEventListener('click',function(){
            alert('Fitur Pesan akan segera hadir!\n\nDi sini Anda akan dapat:\n• Mengirim pesan ke siswa\n• Menerima notifikasi pesan\n• Berkomunikasi dengan admin');
        });
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click',function(){
        if(activeDropdown){
            activeDropdown.classList.remove('show');
            activeDropdown=null;
        }
    });

    // Prevent dropdown from closing when clicking inside
    document.querySelectorAll('.dropdown').forEach(function(dropdown){
        dropdown.addEventListener('click',function(e){
            e.stopPropagation();
        });
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
            overlay.classList.toggle('show');
        });
    }
    
    if(overlay){
        overlay.addEventListener('click',function(){
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        });
    }
})();
</script>
</body>
</html>
