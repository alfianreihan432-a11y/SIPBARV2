<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPBAR – Sistem Informasi Pengelolaan Barang</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
        body { background: #fff; color: #1e293b; -webkit-font-smoothing: antialiased; }

        /* Navbar */
        .sipbar-nav { position: sticky; top: 0; z-index: 50; background: #fff; border-bottom: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,.06); }
        .sipbar-nav-inner { max-width: 1280px; margin: 0 auto; padding: 0 24px; display: flex; align-items: center; justify-content: space-between; height: 64px; }
        .sipbar-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .sipbar-logo-box { width: 36px; height: 36px; background: #1d4ed8; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .sipbar-logo-title { font-size: 14px; font-weight: 700; color: #0f172a; line-height: 1.2; }
        .sipbar-logo-sub { font-size: 11px; color: #94a3b8; line-height: 1.2; }
        .sipbar-nav-links { display: flex; align-items: center; gap: 32px; }
        .sipbar-nav-links a { font-size: 14px; font-weight: 500; color: #475569; text-decoration: none; transition: color .2s; }
        .sipbar-nav-links a:hover, .sipbar-nav-links a.active { color: #1d4ed8; }
        .sipbar-nav-links a.active { border-bottom: 2px solid #1d4ed8; padding-bottom: 2px; }
        .sipbar-nav-actions { display: flex; align-items: center; gap: 10px; }
        .btn-outline { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border: 1.5px solid #cbd5e1; border-radius: 8px; font-size: 13px; font-weight: 500; color: #334155; text-decoration: none; background: #fff; transition: all .2s; }
        .btn-outline:hover { background: #f8fafc; border-color: #94a3b8; }
        .btn-primary { display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px; background: #1d4ed8; border-radius: 8px; font-size: 13px; font-weight: 600; color: #fff; text-decoration: none; transition: background .2s; }
        .btn-primary:hover { background: #1e40af; }
        .btn-primary-lg { display: inline-flex; align-items: center; gap: 8px; padding: 12px 28px; background: #fff; border-radius: 10px; font-size: 15px; font-weight: 700; color: #1d4ed8; text-decoration: none; box-shadow: 0 4px 12px rgba(0,0,0,.12); transition: background .2s; }
        .btn-primary-lg:hover { background: #eff6ff; }
        .btn-ghost-lg { display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; border: 1.5px solid rgba(255,255,255,.6); border-radius: 10px; font-size: 15px; font-weight: 500; color: #fff; text-decoration: none; transition: background .2s; }
        .btn-ghost-lg:hover { background: rgba(255,255,255,.1); }
        .btn-white-lg { display: inline-flex; align-items: center; gap: 8px; padding: 14px 36px; background: #fff; border-radius: 12px; font-size: 15px; font-weight: 700; color: #1d4ed8; text-decoration: none; box-shadow: 0 4px 16px rgba(0,0,0,.15); white-space: nowrap; transition: background .2s; }
        .btn-white-lg:hover { background: #eff6ff; }

        /* Hamburger / Mobile */
        .sipbar-hamburger { display: none; background: none; border: none; cursor: pointer; padding: 6px; border-radius: 6px; color: #475569; }
        .sipbar-hamburger:hover { background: #f1f5f9; }
        .sipbar-mobile-menu { display: none; background: #fff; border-top: 1px solid #e2e8f0; padding: 16px 24px 20px; }
        .sipbar-mobile-menu.open { display: block; }
        .sipbar-mobile-menu a { display: block; padding: 8px 0; font-size: 14px; font-weight: 500; color: #475569; text-decoration: none; border-bottom: 1px solid #f1f5f9; }
        .sipbar-mobile-menu a:last-child { border-bottom: none; }
        .sipbar-mobile-actions { display: flex; gap: 8px; margin-top: 12px; }
        .sipbar-mobile-actions a { flex: 1; text-align: center; padding: 10px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; }

        /* Hero */
        .hero { background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 55%, #2563eb 100%); color: #fff; overflow: hidden; }
        .hero-inner { max-width: 1280px; margin: 0 auto; padding: 72px 24px; display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: center; }
        .hero-badge { display: inline-flex; align-items: center; gap: 8px; padding: 6px 14px; background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.3); border-radius: 999px; font-size: 12px; font-weight: 600; color: #bfdbfe; margin-bottom: 24px; }
        .hero-badge-dot { width: 8px; height: 8px; background: #4ade80; border-radius: 50%; animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }
        .hero-title { font-size: 44px; font-weight: 800; line-height: 1.15; margin-bottom: 20px; }
        .hero-title span { color: #bfdbfe; }
        .hero-desc { font-size: 16px; color: #bfdbfe; line-height: 1.7; margin-bottom: 36px; max-width: 440px; }
        .hero-buttons { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 48px; }
        .hero-badges { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
        .hero-badge-item { display: flex; flex-direction: column; align-items: center; text-align: center; }
        .hero-badge-icon { width: 44px; height: 44px; background: rgba(255,255,255,.12); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 8px; }
        .hero-badge-label { font-size: 12px; font-weight: 600; color: #fff; margin-bottom: 2px; }
        .hero-badge-text { font-size: 11px; color: #93c5fd; line-height: 1.4; }

        /* Dashboard Mockup */
        .mockup { background: #fff; border-radius: 16px; box-shadow: 0 24px 64px rgba(0,0,0,.25); overflow: hidden; }
        .mockup-header { background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 10px 14px; display: flex; align-items: center; justify-content: space-between; }
        .mockup-body { display: flex; min-height: 340px; }
        .mockup-sidebar { width: 100px; background: #1e3a8a; padding: 8px 4px; display: flex; flex-direction: column; gap: 2px; }
        .mockup-menu-item { display: flex; align-items: center; gap: 6px; padding: 6px 8px; border-radius: 4px; font-size: 10px; color: #93c5fd; cursor: default; }
        .mockup-menu-item.active { background: #2563eb; color: #fff; }
        .mockup-content { flex: 1; padding: 12px; background: #f8fafc; overflow: hidden; }
        .mockup-stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; margin-bottom: 10px; }
        .mockup-stat { background: #fff; border-radius: 6px; padding: 8px; box-shadow: 0 1px 3px rgba(0,0,0,.06); }
        .mockup-stat-label { font-size: 9px; color: #94a3b8; margin-bottom: 2px; }
        .mockup-stat-val { font-size: 13px; font-weight: 700; color: #0f172a; }
        .mockup-stat-change { font-size: 9px; }
        .mockup-chart { background: #fff; border-radius: 6px; padding: 8px; box-shadow: 0 1px 3px rgba(0,0,0,.06); margin-bottom: 10px; }
        .mockup-table { background: #fff; border-radius: 6px; padding: 8px; box-shadow: 0 1px 3px rgba(0,0,0,.06); }
        .mockup-row { display: flex; align-items: center; justify-content: space-between; padding: 5px 0; border-bottom: 1px solid #f1f5f9; }
        .mockup-row:last-child { border-bottom: none; }
        .badge-green { background: #dcfce7; color: #16a34a; font-size: 9px; padding: 2px 6px; border-radius: 999px; }
        .badge-red { background: #fee2e2; color: #dc2626; font-size: 9px; padding: 2px 6px; border-radius: 999px; }

        /* Section commons */
        .section { padding: 80px 24px; }
        .section-inner { max-width: 1280px; margin: 0 auto; }
        .section-tag { display: block; font-size: 12px; font-weight: 700; color: #2563eb; letter-spacing: .1em; text-transform: uppercase; margin-bottom: 8px; }
        .section-title { font-size: 32px; font-weight: 800; color: #0f172a; margin-bottom: 12px; }
        .section-sub { font-size: 15px; color: #64748b; max-width: 520px; margin: 0 auto; line-height: 1.7; }
        .section-head { text-align: center; margin-bottom: 52px; }

        /* Category grid */
        .cat-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 16px; }
        .cat-card { background: #fff; border: 2px solid #e2e8f0; border-radius: 16px; padding: 24px 16px; text-align: center; cursor: pointer; transition: all .2s; }
        .cat-card:hover { border-color: #2563eb; transform: translateY(-3px); box-shadow: 0 8px 24px rgba(37,99,235,.12); }
        .cat-icon { width: 52px; height: 52px; background: #eff6ff; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; }
        .cat-name { font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 6px; }
        .cat-desc { font-size: 12px; color: #94a3b8; line-height: 1.5; margin-bottom: 12px; }
        .cat-link { font-size: 12px; font-weight: 700; color: #2563eb; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
        .cat-link:hover { text-decoration: underline; }

        /* Feature grid */
        .feat-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 20px; }
        .feat-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 28px 20px; text-align: center; transition: transform .25s; }
        .feat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,.08); }
        .feat-icon { width: 52px; height: 52px; background: #eff6ff; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; }
        .feat-name { font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 8px; }
        .feat-desc { font-size: 13px; color: #64748b; line-height: 1.6; }

        /* Stats banner */
        .stats-banner { background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 100%); color: #fff; padding: 64px 24px; }
        .stats-inner { max-width: 1280px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: center; }
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }
        .stat-num { font-size: 36px; font-weight: 800; color: #fff; }
        .stat-label { font-size: 13px; color: #93c5fd; margin-top: 4px; }

        /* About */
        .about-inner { max-width: 1280px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: center; }
        .check-list { list-style: none; margin: 20px 0 32px; display: flex; flex-direction: column; gap: 12px; }
        .check-list li { display: flex; align-items: flex-start; gap: 10px; font-size: 14px; color: #475569; }
        .check-dot { width: 20px; height: 20px; background: #2563eb; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px; }
        .school-visual { background: linear-gradient(to bottom, #bfdbfe, #dbeafe); border-radius: 20px; height: 380px; position: relative; overflow: hidden; box-shadow: 0 20px 48px rgba(0,0,0,.1); }
        .sky { position: absolute; top: 0; left: 0; right: 0; height: 140px; background: linear-gradient(to bottom, #7dd3fc, #bfdbfe); }
        .cloud { position: absolute; background: rgba(255,255,255,.85); border-radius: 999px; }
        .building { position: absolute; bottom: 60px; left: 50%; transform: translateX(-50%); width: 80%; }
        .building-body { background: #e2e8f0; border-radius: 8px 8px 0 0; padding: 16px 12px 0; }
        .building-roof { background: #cbd5e1; height: 18px; border-radius: 8px 8px 0 0; margin-bottom: 0; }
        .win-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; padding: 12px 8px 4px; }
        .win { height: 28px; background: #93c5fd; border-radius: 3px; opacity: .8; }
        .building-door { width: 32px; height: 36px; background: #94a3b8; border-radius: 4px 4px 0 0; margin: 0 auto; }
        .grass { position: absolute; bottom: 0; left: 0; right: 0; height: 60px; background: linear-gradient(to top, #86efac, #bbf7d0); }
        .quote-card { position: absolute; bottom: 16px; right: 16px; background: #fff; border-radius: 12px; padding: 16px; max-width: 220px; box-shadow: 0 8px 24px rgba(0,0,0,.12); }
        .quote-mark { font-size: 28px; font-family: Georgia, serif; color: #2563eb; line-height: 1; margin-bottom: 4px; }
        .quote-text { font-size: 12px; color: #475569; font-style: italic; line-height: 1.5; }
        .quote-author { font-size: 12px; font-weight: 700; color: #1d4ed8; margin-top: 6px; }

        /* CTA Banner */
        .cta-banner { background: #1d4ed8; padding: 56px 24px; }
        .cta-inner { max-width: 1280px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; gap: 32px; flex-wrap: wrap; }
        .cta-text h3 { font-size: 26px; font-weight: 800; color: #fff; margin-bottom: 6px; }
        .cta-text p { font-size: 15px; color: #bfdbfe; }
        .cta-left { display: flex; align-items: center; gap: 20px; }
        .cta-icon-box { width: 56px; height: 56px; background: rgba(255,255,255,.15); border-radius: 16px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

        /* Footer */
        .footer { background: #0f172a; color: #94a3b8; padding: 64px 24px 32px; }
        .footer-inner { max-width: 1280px; margin: 0 auto; }
        .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 1.4fr; gap: 40px; margin-bottom: 48px; }
        .footer-logo { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; }
        .footer-logo-box { width: 36px; height: 36px; background: #2563eb; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
        .footer-brand-name { font-size: 14px; font-weight: 700; color: #f1f5f9; }
        .footer-brand-sub { font-size: 11px; color: #475569; }
        .footer-desc { font-size: 13px; line-height: 1.7; margin-bottom: 20px; color: #64748b; }
        .social-links { display: flex; gap: 8px; }
        .social-link { width: 32px; height: 32px; background: #1e293b; border-radius: 8px; display: flex; align-items: center; justify-content: center; transition: background .2s; }
        .social-link:hover { background: #2563eb; }
        .footer-heading { font-size: 14px; font-weight: 700; color: #f1f5f9; margin-bottom: 16px; }
        .footer-list { list-style: none; display: flex; flex-direction: column; gap: 8px; }
        .footer-list a { font-size: 13px; color: #64748b; text-decoration: none; transition: color .2s; }
        .footer-list a:hover { color: #f1f5f9; }
        .contact-item { display: flex; align-items: flex-start; gap: 8px; font-size: 13px; color: #64748b; margin-bottom: 8px; }
        .contact-item svg { width: 14px; height: 14px; color: #60a5fa; margin-top: 2px; flex-shrink: 0; }
        .footer-divider { border: none; border-top: 1px solid #1e293b; margin-bottom: 24px; }
        .footer-copy { text-align: center; font-size: 12px; color: #475569; }

        /* Responsive */
        @media (max-width: 1024px) {
            .hero-inner { grid-template-columns: 1fr; gap: 40px; }
            .mockup { display: none; }
            .hero-badges { grid-template-columns: repeat(2, 1fr); }
            .cat-grid { grid-template-columns: repeat(3, 1fr); }
            .feat-grid { grid-template-columns: repeat(3, 1fr); }
            .stats-inner { grid-template-columns: 1fr; }
            .about-inner { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 768px) {
            .sipbar-nav-links, .sipbar-nav-actions { display: none; }
            .sipbar-hamburger { display: block; }
            .hero-title { font-size: 30px; }
            .cat-grid { grid-template-columns: repeat(2, 1fr); }
            .feat-grid { grid-template-columns: repeat(2, 1fr); }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .section-title { font-size: 26px; }
            .cta-inner { flex-direction: column; text-align: center; }
            .footer-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

{{-- ==================== NAVBAR ==================== --}}
<nav class="sipbar-nav">
    <div class="sipbar-nav-inner">
        <a href="#" class="sipbar-logo">
            <div class="sipbar-logo-box">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 42" style="width:18px;height:18px;fill:#fff">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M17.2 5.633 8.6.855 0 5.633v26.51l16.2 9 16.2-9v-8.442l7.6-4.223V9.856l-8.6-4.777-8.6 4.777V18.3l-5.6 3.111V5.633ZM38 18.301l-5.6 3.11v-6.157l5.6-3.11V18.3Zm-1.06-7.856-5.54 3.078-5.54-3.079 5.54-3.078 5.54 3.079ZM24.8 18.3v-6.157l5.6 3.111v6.158L24.8 18.3Zm-1 1.732 5.54 3.078-13.14 7.302-5.54-3.078 13.14-7.3v-.002Zm-16.2 7.89 7.6 4.222V38.3L2 30.966V7.92l5.6 3.111v16.892ZM8.6 9.3 3.06 6.222 8.6 3.143l5.54 3.08L8.6 9.3Zm21.8 15.51-13.2 7.334V38.3l13.2-7.334v-6.156ZM9.6 11.034l5.6-3.11v14.6l-5.6 3.11v-14.6Z"/>
                </svg>
            </div>
            <div>
                <div class="sipbar-logo-title">SIPBAR</div>
                <div class="sipbar-logo-sub">Sistem Inventaris</div>
            </div>
        </a>

        <nav class="sipbar-nav-links">
            <a href="#beranda" class="active">Beranda</a>
            <a href="#kategori">Kategori</a>
            <a href="#fitur">Fitur</a>
            <a href="#tentang">Tentang</a>
            <a href="#kontak">Kontak</a>
        </nav>

        <div class="sipbar-nav-actions">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-primary">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-outline">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="btn-primary">Daftar Gratis</a>
            @endauth
        </div>

        <button class="sipbar-hamburger" onclick="document.getElementById('mobileNav').classList.toggle('open')" aria-label="Menu">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>

    <div id="mobileNav" class="sipbar-mobile-menu">
        <a href="#beranda">Beranda</a>
        <a href="#kategori">Kategori</a>
        <a href="#fitur">Fitur</a>
        <a href="#tentang">Tentang</a>
        <a href="#kontak">Kontak</a>
        <div class="sipbar-mobile-actions">
            <a href="{{ route('login') }}" style="background:#f1f5f9;color:#334155;">Masuk</a>
            <a href="{{ route('register') }}" style="background:#1d4ed8;color:#fff;">Daftar Gratis</a>
        </div>
    </div>
</nav>

{{-- ==================== HERO ==================== --}}
<section class="hero" id="beranda">
    <div class="hero-inner">
        {{-- Left --}}
        <div>
            <div class="hero-badge">
                <span class="hero-badge-dot"></span>
                Sistem Inventaris Modern
            </div>
            <h1 class="hero-title">
                Kelola Inventaris<br>
                <span>Lebih Mudah, Cepat</span><br>
                dan Terorganisir
            </h1>
            <p class="hero-desc">Sistem inventaris berbasis web untuk memudahkan pengelolaan barang, peminjaman, dan laporan secara efisien, akurat, dan transparan.</p>

            <div class="hero-buttons">
                <a href="{{ route('register') }}" class="btn-primary-lg">
                    Mulai Sekarang
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="#fitur" class="btn-ghost-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Lihat Demo
                </a>
            </div>

            <div class="hero-badges">
                <div class="hero-badge-item">
                    <div class="hero-badge-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#93c5fd" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div class="hero-badge-label">Aman & Terpercaya</div>
                    <div class="hero-badge-text">Data terenkripsi dan terlindungi</div>
                </div>
                <div class="hero-badge-item">
                    <div class="hero-badge-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#93c5fd" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div class="hero-badge-label">Efisien</div>
                    <div class="hero-badge-text">Data inventaris lebih cepat dan mudah</div>
                </div>
                <div class="hero-badge-item">
                    <div class="hero-badge-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#93c5fd" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div class="hero-badge-label">Laporan Akurat</div>
                    <div class="hero-badge-text">Dapatkan laporan akurat dan tepat</div>
                </div>
                <div class="hero-badge-item">
                    <div class="hero-badge-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#93c5fd" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>
                    </div>
                    <div class="hero-badge-label">Berbasis Cloud</div>
                    <div class="hero-badge-text">Akses data di mana saja</div>
                </div>
            </div>
        </div>

        {{-- Right: Dashboard Mockup --}}
        <div class="mockup">
            <div class="mockup-header">
                <div style="display:flex;align-items:center;gap:8px">
                    <div style="width:24px;height:24px;background:#1d4ed8;border-radius:5px;display:flex;align-items:center;justify-content:center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 42" style="width:12px;height:12px;fill:#fff"><path fill-rule="evenodd" clip-rule="evenodd" d="M17.2 5.633 8.6.855 0 5.633v26.51l16.2 9 16.2-9v-8.442Z"/></svg>
                    </div>
                    <span style="font-size:11px;font-weight:700;color:#334155">Dashboard</span>
                </div>
                <div style="display:flex;align-items:center;gap:6px">
                    <div style="width:22px;height:22px;background:#e2e8f0;border-radius:50%"></div>
                    <div style="width:22px;height:22px;background:#2563eb;border-radius:50%;display:flex;align-items:center;justify-content:center">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                </div>
            </div>
            <div class="mockup-body">
                <div class="mockup-sidebar">
                    @foreach(['Dashboard','Barang','Kategori','Peminjaman','Pengembalian','Laporan','Pengguna','Pengajuan'] as $menu)
                    <div class="mockup-menu-item {{ $menu === 'Dashboard' ? 'active' : '' }}">
                        <div style="width:8px;height:8px;background:{{ $menu === 'Dashboard' ? '#93c5fd' : 'rgba(147,197,253,.4)' }};border-radius:2px;flex-shrink:0"></div>
                        <span>{{ $menu }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="mockup-content">
                    <p style="font-size:10px;color:#64748b;margin-bottom:2px">Selamat datang, Admin!</p>
                    <p style="font-size:9px;color:#94a3b8;margin-bottom:10px">Kelola inventaris dengan mudah dan efisien.</p>

                    <div class="mockup-stat-grid">
                        <div class="mockup-stat"><div class="mockup-stat-label">Total Barang</div><div class="mockup-stat-val">1.256</div><div class="mockup-stat-change" style="color:#16a34a">+17%</div></div>
                        <div class="mockup-stat"><div class="mockup-stat-label">Tersedia</div><div class="mockup-stat-val">892</div><div class="mockup-stat-change" style="color:#dc2626">-8%</div></div>
                        <div class="mockup-stat"><div class="mockup-stat-label">Dipinjam</div><div class="mockup-stat-val">234</div><div class="mockup-stat-change" style="color:#dc2626">-1%</div></div>
                        <div class="mockup-stat"><div class="mockup-stat-label">Kategori</div><div class="mockup-stat-val">24</div><div class="mockup-stat-change" style="color:#94a3b8">Total</div></div>
                    </div>

                    <div class="mockup-chart">
                        <div style="font-size:9px;color:#64748b;margin-bottom:6px">Grafis Peminjaman</div>
                        <svg viewBox="0 0 240 55" style="width:100%;height:48px">
                            <defs><linearGradient id="chartFill" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#2563eb" stop-opacity=".2"/><stop offset="100%" stop-color="#2563eb" stop-opacity="0"/></linearGradient></defs>
                            <polygon fill="url(#chartFill)" points="0,50 30,38 60,43 90,18 120,26 155,12 200,20 240,16 240,55 0,55"/>
                            <polyline fill="none" stroke="#2563eb" stroke-width="2" stroke-linejoin="round" points="0,50 30,38 60,43 90,18 120,26 155,12 200,20 240,16"/>
                            @foreach([[30,38],[90,18],[155,12],[240,16]] as $p)
                            <circle cx="{{ $p[0] }}" cy="{{ $p[1] }}" r="3" fill="#2563eb"/>
                            @endforeach
                        </svg>
                    </div>

                    <div class="mockup-table">
                        <div style="font-size:9px;font-weight:700;color:#334155;margin-bottom:6px">Barang Terbaru</div>
                        @foreach([['Proyektor Epson','Elektronik',true],['Meja Guru','Furnitur',true],['Bola Basket','Olahraga',false],['Mikroskop Binokuler','Laboratorium',true]] as $item)
                        <div class="mockup-row">
                            <div style="display:flex;align-items:center;gap:6px">
                                <div style="width:20px;height:20px;background:#eff6ff;border-radius:4px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                    <div style="width:8px;height:8px;background:#93c5fd;border-radius:2px"></div>
                                </div>
                                <div>
                                    <div style="font-size:9px;font-weight:600;color:#334155">{{ $item[0] }}</div>
                                    <div style="font-size:9px;color:#94a3b8">{{ $item[1] }}</div>
                                </div>
                            </div>
                            <span class="{{ $item[2] ? 'badge-green' : 'badge-red' }}">{{ $item[2] ? 'Tersedia' : 'Dipinjam' }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ==================== KATEGORI ==================== --}}
<section class="section" id="kategori" style="background:#fff">
    <div class="section-inner">
        <div class="section-head">
            <span class="section-tag">Kategori Inventaris</span>
            <h2 class="section-title">Kelompok Barang Inventaris</h2>
            <p class="section-sub">Inventaris dikelompokkan dalam kategori untuk memudahkan pengelolaan dan pencarian barang.</p>
        </div>
        <div class="cat-grid">
            @php
            $cats = [
                ['Elektronik','Peralatan elektronik seperti komputer, proyektor, printer, dan lainnya.','M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                ['Furnitur','Meja, kursi, lemari, rak, dan perabot sekolah lainnya.','M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                ['Buku & Referensi','Buku pelajaran, referensi, modul, dan bahan bacaan.','M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                ['Laboratorium','Alat dan bahan praktikum laboratorium IPA, Kimia, Biologi, dll.','M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z'],
                ['Olahraga','Peralatan olahraga dan perlengkapan kegiatan ekstrakurikuler.','M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4'],
                ['Lainnya','Kategori inventaris lainnya yang tidak termasuk di atas.','M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
            ];
            @endphp
            @foreach($cats as $cat)
            <div class="cat-card">
                <div class="cat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#2563eb" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $cat[2] }}"/>
                    </svg>
                </div>
                <div class="cat-name">{{ $cat[0] }}</div>
                <div class="cat-desc">{{ $cat[1] }}</div>
                <a href="{{ route('login') }}" class="cat-link">
                    Lihat Barang
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ==================== FITUR ==================== --}}
<section class="section" id="fitur" style="background:#f8fafc">
    <div class="section-inner">
        <div class="section-head">
            <span class="section-tag">Fitur Unggulan</span>
            <h2 class="section-title">Fitur yang Memudahkan Pengelolaan</h2>
            <p class="section-sub">Berbagai fitur lengkap untuk membantu pengelolaan inventaris lebih efektif.</p>
        </div>
        <div class="feat-grid">
            @php
            $feats = [
                ['Manajemen Barang','Kelola data barang dengan mudah, mulai dari tambah, ubah, hingga hapus.','M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                ['Peminjaman Barang','Catat peminjaman barang secara sistematis dan terstruktur.','M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                ['Pengembalian Barang','Proses pengembalian barang lebih cepat dengan status yang jelas.','M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6'],
                ['Laporan & Statistik','Laporan inventaris lengkap dan statistik penggunaan barang.','M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['Manajemen Pengguna','Atur hak akses pengguna sesuai peran dan tanggung jawab.','M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ];
            @endphp
            @foreach($feats as $f)
            <div class="feat-card">
                <div class="feat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#2563eb" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f[2] }}"/>
                    </svg>
                </div>
                <div class="feat-name">{{ $f[0] }}</div>
                <div class="feat-desc">{{ $f[1] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ==================== STATS ==================== --}}
<section class="stats-banner">
    <div class="stats-inner">
        <div>
            <span class="section-tag" style="color:#93c5fd">Data Inventaris</span>
            <h2 style="font-size:28px;font-weight:800;color:#fff;margin:8px 0 12px">Inventaris dalam Angka</h2>
            <p style="font-size:15px;color:#93c5fd;line-height:1.7;max-width:380px">Kelola ribuan data inventaris dengan sistem yang terintegrasi dan terpercaya.</p>
        </div>
        <div class="stats-grid">
            @foreach([['1.256+','Total Barang'],['24','Kategori'],['156+','Pengguna Aktif'],['892+','Transaksi Peminjaman']] as $s)
            <div style="text-align:center">
                <div class="stat-num">{{ $s[0] }}</div>
                <div class="stat-label">{{ $s[1] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ==================== TENTANG ==================== --}}
<section class="section" id="tentang" style="background:#fff">
    <div class="about-inner">
        <div>
            <span class="section-tag">Tentang Kami</span>
            <h2 class="section-title">Sistem Inventaris untuk<br>Sekolah Modern</h2>
            <p style="font-size:15px;color:#64748b;line-height:1.7;margin-top:12px">SIPBAR adalah sistem berbasis web yang dirancang untuk membantu sekolah mengelola data inventaris secara digital, efisien, dan transparan.</p>
            <ul class="check-list">
                @foreach(['Mudah digunakan oleh semua pengguna','Sistem aman dan data terjaga','Dukungan penuh untuk kebutuhan sekolah','Akses fleksibel kapan saja dan di mana saja'] as $point)
                <li>
                    <div class="check-dot">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    {{ $point }}
                </li>
                @endforeach
            </ul>
        </div>

        <div class="school-visual">
            <div class="sky"></div>
            <div class="cloud" style="top:14px;left:32px;width:72px;height:24px"></div>
            <div class="cloud" style="top:20px;left:60px;width:56px;height:18px;opacity:.7"></div>
            <div class="cloud" style="top:10px;right:40px;width:88px;height:28px;opacity:.8"></div>
            <div class="building" style="bottom:56px">
                <div class="building-body">
                    <div class="building-roof"></div>
                    <div class="win-grid">
                        @for($i=0; $i<14; $i++)
                        <div class="win"></div>
                        @endfor
                    </div>
                    <div class="building-door"></div>
                </div>
            </div>
            <div class="grass"></div>
            <div class="quote-card">
                <div class="quote-mark">"</div>
                <div class="quote-text">Wujudkan sekolah yang lebih terorganisir dengan sistem inventaris modern.</div>
                <div class="quote-author">— SIPBAR</div>
            </div>
        </div>
    </div>
</section>

{{-- ==================== CTA ==================== --}}
<section class="cta-banner">
    <div class="cta-inner">
        <div class="cta-left">
            <div class="cta-icon-box">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 42" style="width:28px;height:28px;fill:#fff">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M17.2 5.633 8.6.855 0 5.633v26.51l16.2 9 16.2-9v-8.442l7.6-4.223V9.856l-8.6-4.777-8.6 4.777V18.3l-5.6 3.111V5.633Z"/>
                </svg>
            </div>
            <div class="cta-text">
                <h3>Siap kelola inventaris sekolah dengan lebih baik?</h3>
                <p>Bergabung sekarang dan rasakan kemudahan mengelola inventaris Anda.</p>
            </div>
        </div>
        <a href="{{ route('register') }}" class="btn-white-lg">
            Mulai Sekarang
            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</section>

{{-- ==================== FOOTER ==================== --}}
<footer class="footer" id="kontak">
    <div class="footer-inner">
        <div class="footer-grid">
            {{-- Brand --}}
            <div>
                <div class="footer-logo">
                    <div class="footer-logo-box">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 42" style="width:18px;height:18px;fill:#fff">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M17.2 5.633 8.6.855 0 5.633v26.51l16.2 9 16.2-9v-8.442l7.6-4.223V9.856l-8.6-4.777-8.6 4.777V18.3l-5.6 3.111V5.633Z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="footer-brand-name">SIPBAR</div>
                        <div class="footer-brand-sub">Sistem Inventaris</div>
                    </div>
                </div>
                <p class="footer-desc">Sistem inventaris berbasis web yang lebih efektif, efisien, dan transparan untuk sekolah.</p>
                <div class="social-links">
                    @foreach([
                        'M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z',
                        'M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z',
                        'M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z',
                        'M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 00-1.95 1.96A29 29 0 001 12a29 29 0 00.46 5.58A2.78 2.78 0 003.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 001.95-1.95A29 29 0 0023 12a29 29 0 00-.46-5.58z'
                    ] as $icon)
                    <a href="#" class="social-link">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;color:#94a3b8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Menu --}}
            <div>
                <div class="footer-heading">Menu</div>
                <ul class="footer-list">
                    @foreach(['Beranda','Kategori','Fitur','Tentang','Kontak'] as $m)
                    <li><a href="#{{ strtolower(str_replace(' ','',iconv('UTF-8','ASCII//TRANSLIT',$m))) }}">{{ $m }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Fitur --}}
            <div>
                <div class="footer-heading">Fitur</div>
                <ul class="footer-list">
                    @foreach(['Manajemen Barang','Peminjaman','Pengembalian','Laporan','Pengguna'] as $f)
                    <li><a href="#">{{ $f }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Bantuan --}}
            <div>
                <div class="footer-heading">Bantuan</div>
                <ul class="footer-list">
                    @foreach(['Panduan Penggunaan','FAQ','Kebijakan Privasi','Syarat & Ketentuan'] as $b)
                    <li><a href="#">{{ $b }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Kontak --}}
            <div>
                <div class="footer-heading">Kontak</div>
                <div class="contact-item">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:#60a5fa;width:14px;height:14px;flex-shrink:0;margin-top:2px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    0812-3456-7890
                </div>
                <div class="contact-item">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:#60a5fa;width:14px;height:14px;flex-shrink:0;margin-top:2px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    info@sipbar.sch.id
                </div>
                <div class="contact-item">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:#60a5fa;width:14px;height:14px;flex-shrink:0;margin-top:2px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Jl. Pendidikan No. 10,<br>Kota Pendidikan, Indonesia</span>
                </div>
            </div>
        </div>

        <hr class="footer-divider">
        <p class="footer-copy">&copy; {{ date('Y') }} SIPBAR – Sistem Informasi Pengelolaan Barang. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
