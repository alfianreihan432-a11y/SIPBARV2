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
<?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css','resources/js/app.js']); ?>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html,body{height:100%;font-family:'Instrument Sans',ui-sans-serif,system-ui,sans-serif;-webkit-font-smoothing:antialiased}

/* ══ LIGHT TOKENS (GURU) ══ */
:root{
    --bg:#f8fafc;--bg2:#ffffff;--bg3:#f1f5f9;
    --card:#ffffff;--border:#e2e8f0;--border2:#e2e8f0;
    --text:#0f172a;--text2:#1e293b;--muted:#475569;--subtle:#64748b;
    --topbar-bg:#ffffff;--topbar-bdr:#e2e8f0;--content-bg:#f8fafc;
    --panel-bg:#ffffff;--input-bg:#f8fafc;--scrollbar:#a7f3d0;
    --sidebar-bg:#065f46; /* Solid Clear Emerald Green */
    --accent:#10b981;--accent-light:#ecfdf5;--accent-text:#047857;
}
/* ══ DARK TOKENS (GURU) ══ */
html.dark{
    --bg:#091210;--bg2:#0f201d;--bg3:#162e2a;
    --card:#0f201d;--border:#1d3d37;--border2:#162e2a;
    --text:#f0fdf4;--text2:#dcfce7;--muted:#86efac;--subtle:#6ee7b7;
    --topbar-bg:#091210;--topbar-bdr:#1d3d37;--content-bg:#060d0b;
    --panel-bg:#0f201d;--input-bg:#091210;--scrollbar:#134e4a;
    --sidebar-bg:#042f24; /* Solid Deep Emerald */
    --accent:#10b981;--accent-light:rgba(16,185,129,.18);--accent-text:#34d399;
}
body{display:flex;background:var(--content-bg);color:var(--text);overflow:hidden;transition:background .25s,color .25s}

/* ══ SIDEBAR ══ */
.sidebar{width:220px;flex-shrink:0;background:var(--sidebar-bg);display:flex;flex-direction:column;height:100vh;position:fixed;left:0;top:0;z-index:40;transition:background .25s,transform .3s;box-shadow:2px 0 16px rgba(0,0,0,.08)}
.sidebar-brand{display:flex;align-items:center;gap:14px;padding:20px 18px;border-bottom:1px solid rgba(255,255,255,.12);text-decoration:none}
.sidebar-logo-wrap{width:46px;height:46px;border-radius:50%;background:rgba(255,255,255,.95);display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;padding:4px;box-shadow:0 2px 8px rgba(0,0,0,.3),0 0 0 1.5px rgba(255,255,255,.15);transition:transform .2s ease,box-shadow .2s ease}
.sidebar-brand:hover .sidebar-logo-wrap{transform:scale(1.05);box-shadow:0 4px 14px rgba(0,0,0,.4)}
.sidebar-brand-img{width:100%;height:100%;object-fit:contain}
.brand-name{font-size:18px;font-weight:800;color:#ffffff;letter-spacing:-.02em;line-height:1.15}
.brand-badge{font-size:9.5px;font-weight:800;color:#10b981;background:rgba(255,255,255,.95);padding:2px 6px;border-radius:4px;letter-spacing:.05em;display:inline-block;margin-top:2px}
.sidebar-nav{flex:1;padding:16px 12px;overflow-y:auto}
.sidebar-nav::-webkit-scrollbar{width:0}
.nav-item{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;font-size:13px;font-weight:500;color:rgba(255,255,255,.82);text-decoration:none;margin-bottom:4px;transition:all .18s ease;position:relative}
.nav-item:hover{background:rgba(255,255,255,.12);color:#ffffff}
.nav-item.active{background:rgba(255,255,255,.22);color:#ffffff;font-weight:700;border-left:3.5px solid #34d399;padding-left:9px}
.nav-icon{width:18px;height:18px;flex-shrink:0}
.nav-badge{margin-left:auto;background:#ffffff;color:#065f46;font-size:11px;font-weight:800;padding:2px 7px;border-radius:999px;box-shadow:0 2px 6px rgba(0,0,0,.18)}
.sidebar-footer{padding:14px;border-top:1px solid rgba(255,255,255,.12);background:rgba(0,0,0,.08)}
.user-card{display:flex;align-items:center;gap:10px;padding:9px 10px;border-radius:10px;background:rgba(255,255,255,.1);cursor:pointer;text-decoration:none;color:inherit;transition:background .15s}
.user-card:hover{background:rgba(255,255,255,.16)}
.user-avatar{width:34px;height:34px;border-radius:50%;background:#10b981;border:1.5px solid rgba(255,255,255,.4);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;color:#ffffff;flex-shrink:0}
.user-name{font-size:12.5px;font-weight:700;color:#ffffff;line-height:1.2}
.user-role{font-size:10.5px;color:rgba(255,255,255,.75)}
.logout-btn{display:flex;align-items:center;gap:9px;padding:9px 12px;border-radius:10px;margin-top:8px;font-size:13px;font-weight:600;color:rgba(255,255,255,.75);cursor:pointer;text-decoration:none;transition:all .15s;background:none;border:none;width:100%}
.logout-btn:hover{background:rgba(239,68,68,.25);color:#fca5a5}

/* ══ MAIN ══ */
.main{margin-left:220px;flex:1;height:100vh;display:flex;flex-direction:column;overflow:hidden}

/* ══ TOPBAR ══ */
.topbar{height:62px;background:var(--topbar-bg);border-bottom:1px solid var(--topbar-bdr);display:flex;align-items:center;padding:0 24px;gap:12px;flex-shrink:0;transition:background .25s}
.topbar-search{display:flex;align-items:center;gap:8px;background:var(--input-bg);border:1px solid var(--border2);border-radius:10px;padding:8px 14px;width:280px;transition:border-color .15s}
.topbar-search:focus-within{border-color:var(--accent)}
.topbar-search input{background:none;border:none;outline:none;font-size:13px;color:var(--text);width:100%}
.topbar-search input::placeholder{color:var(--subtle)}
.topbar-right{margin-left:auto;display:flex;align-items:center;gap:10px}
.topbar-icon-wrap{position:relative}
.topbar-icon{width:38px;height:38px;background:var(--bg3);border:1px solid var(--border2);border-radius:10px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--muted);transition:all .15s;position:relative}
.topbar-icon:hover{background:var(--border2);color:var(--text);border-color:var(--accent)}
.notif-dot{position:absolute;top:7px;right:8px;width:7px;height:7px;background:#ef4444;border-radius:50%;border:2px solid var(--topbar-bg);transition:border-color .25s}

/* Topbar User Button & Dropdown */
.topbar-user-wrap{position:relative}
.topbar-user{display:flex;align-items:center;gap:8px;padding:4px 12px 4px 4px;background:var(--bg3);border:1px solid var(--border2);border-radius:999px;cursor:pointer;color:inherit;transition:all .15s;font-family:inherit;font-size:inherit}
.topbar-user:hover,.topbar-user.active{border-color:var(--accent);background:var(--border2)}
.topbar-avatar{width:32px;height:32px;border-radius:50%;background:#065f46;display:flex;align-items:center;justify-content:center;font-size:11.5px;font-weight:700;color:#ffffff}
.topbar-uname{font-size:13px;font-weight:600;color:var(--text2)}
.chevron-icon{transition:transform .2s ease}
.topbar-user.active .chevron-icon{transform:rotate(180deg)}

/* Dropdown Menu Styles */
.profile-dropdown-menu,.notif-dropdown-menu{
    position:absolute;
    top:calc(100% + 8px);
    right:0;
    width:260px;
    background:var(--card);
    border:1px solid var(--border);
    border-radius:14px;
    box-shadow:0 12px 30px -4px rgba(0,0,0,0.12),0 4px 12px -2px rgba(0,0,0,0.06);
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
.pdm-avatar{width:36px;height:36px;border-radius:50%;background:#065f46;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;color:#fff;flex-shrink:0}
.pdm-info{flex:1;min-width:0}
.pdm-name{font-size:13px;font-weight:700;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.pdm-role{font-size:11px;color:#059669;font-weight:600}
.pdm-divider{height:1px;background:var(--border2);margin:6px 0}
.pdm-links{display:flex;flex-direction:column;gap:2px}
.pdm-link{display:flex;align-items:center;gap:10px;padding:8px 12px;border-radius:8px;font-size:12.5px;font-weight:500;color:var(--text2);text-decoration:none;transition:all .15s}
.pdm-link:hover{background:var(--bg3);color:var(--accent-text)}
.pdm-link-icon{width:16px;height:16px;color:var(--muted);flex-shrink:0}
.pdm-logout-form{margin-top:2px}
.pdm-logout-btn{display:flex;align-items:center;gap:10px;padding:8px 12px;border-radius:8px;font-size:12.5px;font-weight:600;color:#dc2626;background:none;border:none;width:100%;cursor:pointer;transition:all .15s;font-family:inherit;text-align:left}
.pdm-logout-btn:hover{background:rgba(239,68,68,0.1);color:#b91c1c}

/* Notif Dropdown Styles */
.notif-dropdown-menu{width:300px}
.ndm-header{display:flex;align-items:center;justify-content:space-between;padding:10px 12px;border-bottom:1px solid var(--border2)}
.ndm-title{font-size:13px;font-weight:700;color:var(--text)}
.ndm-badge{font-size:10px;font-weight:700;background:var(--accent-light);color:var(--accent-text);padding:2px 6px;border-radius:999px}
.ndm-list{padding:6px 0;display:flex;flex-direction:column;gap:2px}
.ndm-item{display:flex;align-items:flex-start;gap:10px;padding:8px 12px;border-radius:8px;text-decoration:none;transition:background .15s}
.ndm-item:hover{background:var(--bg3)}
.ndm-dot{width:7px;height:7px;border-radius:50%;background:#f59e0b;margin-top:5px;flex-shrink:0}
.ndm-dot.blue{background:#2563eb}
.ndm-text{font-size:12px;font-weight:600;color:var(--text2);line-height:1.4}
.ndm-time{font-size:10px;color:var(--subtle);margin-top:2px}

.t-sun{display:none}.t-moon{display:block}
html.dark .t-sun{display:block}html.dark .t-moon{display:none}

/* ══ CONTENT ══ */
.content{flex:1;overflow-y:auto;padding:24px 28px;background:var(--content-bg);transition:background .25s}
.content::-webkit-scrollbar{width:5px}
.content::-webkit-scrollbar-thumb{background:var(--scrollbar);border-radius:3px}

/* ══ RESPONSIVE ══ */
.sidebar-overlay{position:fixed;inset:0;background:rgba(0,0,0,.55);backdrop-filter:blur(3px);-webkit-backdrop-filter:blur(3px);z-index:45;display:none;opacity:0;transition:opacity .25s ease}
.sidebar-overlay.show{display:block;opacity:1}
@media(max-width:900px){
    .sidebar{position:fixed;left:0;top:0;transform:translateX(-100%);z-index:50;box-shadow:4px 0 30px rgba(0,0,0,.35)}
    .sidebar.open{transform:translateX(0)}
    .main{margin-left:0}
    .hamburger-btn{display:flex !important}
    .topbar-search{display:none}
    .topbar{padding:0 16px;gap:8px}
    .content{padding:16px 14px}
}
</style>
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>


<aside class="sidebar" id="sidebar">
    <a href="<?php echo e(route('teacher.dashboard')); ?>" class="sidebar-brand">
        <div class="sidebar-logo-wrap">
            <img src="/build/assets/logosmkn.png" alt="Logo SMKN 1 Bangsri" class="sidebar-brand-img">
        </div>
        <div>
            <div class="brand-name">SIPBAR</div>
            <span class="brand-badge">PANEL GURU</span>
        </div>
    </a>
    <nav class="sidebar-nav">
        <?php
        $menus = [
            ['Dashboard',        'teacher.dashboard', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', null],
            ['Permohonan',       'teacher.requests', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', '5'],
            ['Siswa Bimbingan',  'teacher.students', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', null],
            ['Peminjaman Aktif', 'teacher.loans',    'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4', '3'],
            ['Pengembalian',     'teacher.returns',  'M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6', null],
            ['Laporan',          'teacher.reports',  'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', null],
            ['Profil',           'teacher.profile',  'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', null],
        ];
        ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <a href="<?php echo e(route($m[1])); ?>" class="nav-item <?php echo e(request()->routeIs($m[1]) ? 'active' : ''); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($m[2]); ?>"/></svg>
            <?php echo e($m[0]); ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($m[3]): ?> <span class="nav-badge"><?php echo e($m[3]); ?></span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </a>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </nav>
    <div class="sidebar-footer">
        <a href="<?php echo e(route('teacher.profile')); ?>" class="user-card">
            <div class="user-avatar"><?php echo e(auth()->check() ? strtoupper(substr(auth()->user()->name,0,2)) : 'GR'); ?></div>
            <div>
                <div class="user-name"><?php echo e(auth()->check() ? auth()->user()->name : 'Budi Santoso'); ?></div>
                <div class="user-role">Guru Pembimbing</div>
            </div>
        </a>
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="logout-btn">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Log Out
            </button>
        </form>
    </div>
</aside>


<div class="main">
    
    <div class="topbar">
        <button type="button" id="hamburgerBtn" class="hamburger-btn" title="Buka Menu" aria-label="Toggle Menu" style="display:none;align-items:center;justify-content:center;width:44px;height:44px;min-width:44px;min-height:44px;border-radius:10px;background:var(--bg3);border:1px solid var(--border2);color:var(--text);cursor:pointer;flex-shrink:0;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <div class="topbar-search">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;color:var(--subtle);flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" placeholder="Cari siswa atau permohonan...">
        </div>
        <div class="topbar-right">
            
            <button class="topbar-icon" id="themeBtn" title="Ganti Mode (Alt+D)">
                <svg class="t-moon" xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                <svg class="t-sun" xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10A5 5 0 0012 7z"/></svg>
            </button>
            <div class="topbar-icon" id="mailBtn" title="Pesan" style="cursor:pointer">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            
            <div class="topbar-icon-wrap">
                <button type="button" class="topbar-icon" id="notifBtn" title="Notifikasi">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <div class="notif-dot"></div>
                </button>
                <div id="notifDropdown" class="notif-dropdown-menu">
                    <div class="ndm-header">
                        <span class="ndm-title">Notifikasi Guru</span>
                        <span class="ndm-badge">2 Menunggu</span>
                    </div>
                    <div class="ndm-list">
                        <a href="<?php echo e(route('teacher.requests')); ?>" class="ndm-item">
                            <div class="ndm-dot"></div>
                            <div>
                                <div class="ndm-text">Permohonan peminjaman siswa perlu approval</div>
                                <div class="ndm-time">Baru saja</div>
                            </div>
                        </a>
                        <a href="<?php echo e(route('teacher.loans')); ?>" class="ndm-item">
                            <div class="ndm-dot blue"></div>
                            <div>
                                <div class="ndm-text">Jadwal pengembalian barang aktif hari ini</div>
                                <div class="ndm-time">1 jam yang lalu</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="topbar-user-wrap">
                <button type="button" id="profileBtn" class="topbar-user">
                    <div class="topbar-avatar"><?php echo e(auth()->check() ? strtoupper(substr(auth()->user()->name,0,2)) : 'GR'); ?></div>
                    <div class="topbar-uname"><?php echo e(auth()->check() ? explode(' ',auth()->user()->name)[0] : 'Guru'); ?></div>
                    <svg class="chevron-icon" xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;color:var(--subtle);margin-left:2px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="profileDropdown" class="profile-dropdown-menu">
                    <div class="pdm-header">
                        <div class="pdm-avatar"><?php echo e(auth()->check() ? strtoupper(substr(auth()->user()->name,0,2)) : 'GR'); ?></div>
                        <div class="pdm-info">
                            <div class="pdm-name"><?php echo e(auth()->check() ? auth()->user()->name : 'Budi Santoso'); ?></div>
                            <div class="pdm-role">Guru Pembimbing</div>
                        </div>
                    </div>
                    <div class="pdm-divider"></div>
                    <div class="pdm-links">
                        <a href="<?php echo e(route('teacher.profile')); ?>" class="pdm-link">
                            <svg xmlns="http://www.w3.org/2000/svg" class="pdm-link-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>Profil Guru</span>
                        </a>
                        <a href="<?php echo e(route('teacher.requests')); ?>" class="pdm-link">
                            <svg xmlns="http://www.w3.org/2000/svg" class="pdm-link-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Kelola Permohonan</span>
                        </a>
                    </div>
                    <div class="pdm-divider"></div>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="pdm-logout-form">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="pdm-logout-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" class="pdm-link-icon" style="color:#ef4444" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            <span>Log Out</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <div class="content">
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('teacher-dashboard', []);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-226776881-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>
    </div>
</div>

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
<?php /**PATH C:\Users\ASUS\SIPBARV2\resources\views/dashboard-guru.blade.php ENDPATH**/ ?>