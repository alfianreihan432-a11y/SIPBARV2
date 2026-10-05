<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ \App\Models\SiteSetting::get('site_title', 'SIPBAR – Sistem Informasi Pengelolaan Barang') }}</title>
@include('partials.favicon')
{{-- Anti-flash: apply theme class BEFORE any CSS renders --}}
<script>
  (function(){
    var t = localStorage.getItem('sipbar-theme');
    var d = window.matchMedia('(prefers-color-scheme: dark)').matches;
    if(t === 'dark' || (!t && d)) document.documentElement.classList.add('dark');
  })();
</script>
@vite(['resources/css/app.css','resources/js/app.js'])
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth;font-family:'Inter',ui-sans-serif,system-ui,sans-serif}
body{background:var(--bg);color:var(--text);-webkit-font-smoothing:antialiased;overflow-x:hidden;transition:background .3s,color .3s}

/* ─── LIGHT MODE TOKENS ─── */
:root{
  --primary:#1d4ed8;--primary-hover:#1e40af;--primary-light:#3b82f6;
  --accent:#f59e0b;--accent-hover:#fb923c;
  --bg:#ffffff;
  --bg2:#f8fafc;
  --bg3:#f1f5f9;
  --surface:#f8fafc;
  --border:#e2e8f0;
  --border2:#cbd5e1;
  --card:#ffffff;
  --text:#0f172a;
  --text2:#1e293b;
  --muted:#475569;
  --subtle:#64748b;
  --nav-bg:rgba(255,255,255,.95);
  --nav-border:rgba(226,232,240,.9);
  --shadow:0 4px 12px rgba(29,78,216,.08);
  --shadow-lg:0 12px 32px rgba(29,78,216,.12);
}

/* ─── DARK MODE TOKENS (REVISI: SOLID, TENANG, MINIM EFEK) ─── */
html.dark{
  --primary:#7aa2f7;--primary-hover:#93c5fd;--primary-light:rgba(122,162,247,.15);
  --accent:#7aa2f7;--accent-hover:#93c5fd;
  --bg:#0f172a;
  --bg2:#111827;
  --bg3:#1e293b;
  --surface:#1e293b;
  --border:rgba(148,163,184,.16);
  --border2:rgba(148,163,184,.20);
  --card:#1e293b;
  --text:#f1f5f9;
  --text2:#a8b3c7;
  --muted:#8b98ad;
  --subtle:#8b98ad;
  --nav-bg:rgba(15,23,42,.92);
  --nav-border:rgba(148,163,184,.15);
  --shadow:0 1px 2px rgba(0,0,0,.3);
  --shadow-lg:0 4px 12px rgba(0,0,0,.4);

  /* Solid section backgrounds (alternating calm dark tones) */
  --dark-bg-fitur:#0f172a;
  --dark-bg-stats:#111827;
  --dark-bg-tentang:#0f172a;
  --dark-bg-bantuan:#111827;
  --dark-bg-footer:#0b1220;

  --dark-card-bg:#1e293b;
  --dark-card-border:rgba(148,163,184,.16);
}

/* ─── NAVBAR ─── */
.site-header{position:sticky;top:0;z-index:100}
.nav{
  position:relative;
  background:rgba(255,255,255,0.85);
  backdrop-filter:blur(16px);
  -webkit-backdrop-filter:blur(16px);
  border-bottom:1px solid rgba(226,232,240,0.8);
  transition:all .3s cubic-bezier(0.4,0,0.2,1);
}
.nav.scrolled{
  background:rgba(255,255,255,0.96);
  border-bottom-color:#e2e8f0;
  box-shadow:0 10px 30px rgba(0,0,0,0.06);
}
.nav-inner{
  max-width:1200px;
  margin:0 auto;
  padding:0 24px;
  height:76px;
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:20px;
}

/* 1. Logo & Brand */
.nav-brand{
  display:inline-flex;
  align-items:center;
  gap:14px;
  text-decoration:none;
  flex-shrink:0;
  transition:opacity .2s;
}
.nav-brand:hover{opacity:.9}
.nav-logo-wrap{
  width:48px; height:48px; border-radius:50%;
  background:#ffffff;
  display:flex; align-items:center; justify-content:center;
  flex-shrink:0;
  box-shadow:0 2px 10px rgba(0,0,0,.10), 0 0 0 1.5px rgba(0,0,0,.06);
  transition:transform .25s ease, box-shadow .25s ease;
  overflow:hidden;
  padding:3px;
}
.nav-brand:hover .nav-logo-wrap{transform:scale(1.04); box-shadow:0 4px 16px rgba(29,78,216,.18)}
.nav-brand-img{
  width:100%; height:100%;
  object-fit:contain;
  border-radius:50%;
}
.nav-brand-text{display:flex;flex-direction:column;gap:1.5px}
.nav-brand-title{
  font-family:'Plus Jakarta Sans',sans-serif;
  font-size:21px;
  font-weight:800;
  color:#1d4ed8;
  letter-spacing:-.02em;
  line-height:1.15;
}
.nav-brand-subtitle{
  font-size:11px;
  font-weight:700;
  color:#64748b;
  letter-spacing:.09em;
  text-transform:uppercase;
  line-height:1.2;
}

/* 2. Menu Navigasi (Tengah) */
.nav-links{
  display:flex;
  align-items:center;
  gap:6px;
  background:rgba(241,245,249,0.7);
  padding:5px 8px;
  border-radius:14px;
  border:1px solid rgba(226,232,240,0.8);
}
.nav-links a{
  position:relative;
  padding:7px 16px;
  font-family:'Plus Jakarta Sans',sans-serif;
  font-size:13.5px;
  font-weight:600;
  color:#475569;
  text-decoration:none;
  border-radius:10px;
  transition:all .2s cubic-bezier(0.4,0,0.2,1);
}
.nav-links a:hover{
  color:#1d4ed8;
  background:rgba(255,255,255,0.9);
}
.nav-links a.active{
  color:#1d4ed8;
  font-weight:700;
  background:#ffffff;
  box-shadow:0 2px 8px rgba(29,78,216,0.12);
}

/* 3. Actions (Kanan Desktop) */
.nav-actions{
  display:flex;
  align-items:center;
  gap:12px;
}
.theme-toggle{
  display:flex;
  align-items:center;
  justify-content:center;
  width:44px;
  height:44px;
  min-width:44px;
  min-height:44px;
  border-radius:12px;
  border:1.5px solid var(--border);
  background:var(--card);
  cursor:pointer;
  transition:all .25s ease;
  flex-shrink:0;
  color:var(--text);
  box-shadow:0 2px 6px rgba(0,0,0,0.03);
}
.theme-toggle:hover{
  border-color:#2563eb;
  color:#2563eb;
  background:var(--bg2);
  transform:translateY(-1px);
  box-shadow:0 6px 16px rgba(37,99,235,0.12);
}
.theme-toggle svg{
  transition:transform .3s ease,opacity .2s ease;
}
.theme-toggle:active svg{transform:rotate(45deg) scale(0.9)}

/* ─── VIEW TRANSITIONS & THEME ANIMATION ─── */
::view-transition-old(root), ::view-transition-new(root) { animation: none; mix-blend-mode: normal; }
::view-transition-old(root) { z-index: 1; }
::view-transition-new(root) { z-index: 2; }
html.theme-fade, html.theme-fade * {
  transition: background-color .35s ease, color .35s ease, border-color .35s ease, fill .35s ease, stroke .35s ease !important;
}
@media (prefers-reduced-motion: reduce) {
  ::view-transition-group(*), ::view-transition-old(*), ::view-transition-new(*) { animation: none !important; }
}

.btn-nav-cta{
  display:inline-flex;
  align-items:center;
  gap:8px;
  padding:10px 22px;
  min-height:44px;
  font-family:'Plus Jakarta Sans',sans-serif;
  font-size:13.5px;
  font-weight:700;
  color:#ffffff;
  background:linear-gradient(135deg,#1d4ed8 0%,#2563eb 100%);
  border-radius:12px;
  text-decoration:none;
  border:none;
  box-shadow:0 4px 14px rgba(29,78,216,0.28);
  transition:all .25s cubic-bezier(0.4,0,0.2,1);
}
.btn-nav-cta:hover{
  transform:translateY(-2px);
  box-shadow:0 8px 22px rgba(29,78,216,0.38);
  filter:brightness(1.06);
}
.btn-nav-cta:active{transform:translateY(0)}

/* 4. Mobile Controls (Theme Toggle & Hamburger) */
.nav-mobile-ctrls{
  display:none;
  align-items:center;
  gap:8px;
}
.nav-ham{
  display:none;
  align-items:center;
  justify-content:center;
  flex-direction:column;
  width:44px;
  height:44px;
  min-width:44px;
  min-height:44px;
  padding:0;
  border-radius:12px;
  border:1.5px solid var(--border);
  background:var(--card);
  color:var(--text);
  cursor:pointer;
  transition:all .2s ease;
  gap:5px;
}
.nav-ham:hover{
  border-color:#2563eb;
  color:#2563eb;
  background:var(--bg2);
}
.ham-line{
  display:block;
  width:20px;
  height:2.2px;
  background:currentColor;
  border-radius:2px;
  transition:transform .3s cubic-bezier(0.4,0,0.2,1), opacity .2s ease;
  transform-origin:center;
}
.nav-ham.active .ham-line:nth-child(1){
  transform:translateY(7.2px) rotate(45deg);
}
.nav-ham.active .ham-line:nth-child(2){
  opacity:0;
  transform:scaleX(0);
}
.nav-ham.active .ham-line:nth-child(3){
  transform:translateY(-7.2px) rotate(-45deg);
}

/* 5. Mobile Drawer */
.nav-mobile{
  display:none;
  padding:16px 20px 20px;
  border-top:1px solid var(--border);
  flex-direction:column;
  gap:12px;
  background:var(--nav-bg);
  backdrop-filter:blur(20px);
  -webkit-backdrop-filter:blur(20px);
  box-shadow:0 16px 36px rgba(0,0,0,0.12);
  animation:slideDownNav .25s cubic-bezier(0.16,1,0.3,1);
}
@keyframes slideDownNav{
  from{opacity:0;transform:translateY(-10px)}
  to{opacity:1;transform:translateY(0)}
}
.nav-mobile.open{display:flex}
.nav-mobile-links{display:flex;flex-direction:column;gap:6px}
.nav-mobile-links a{
  display:flex;
  align-items:center;
  gap:10px;
  padding:11px 16px;
  min-height:44px;
  font-family:'Plus Jakarta Sans',sans-serif;
  font-size:14px;
  font-weight:600;
  color:var(--text);
  text-decoration:none;
  border-radius:10px;
  background:var(--bg3);
  transition:all .15s ease;
}
.nav-mobile-links a:hover{
  background:rgba(37,99,235,0.12);
  color:#1d4ed8;
}
.nav-mob-divider{
  height:1px;
  background:var(--border);
  margin:2px 0;
}
.nav-mob-login{
  display:flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  padding:12px;
  min-height:46px;
  border-radius:12px;
  font-family:'Plus Jakarta Sans',sans-serif;
  font-size:14px;
  font-weight:700;
  text-decoration:none;
  background:linear-gradient(135deg,#1d4ed8 0%,#2563eb 100%);
  color:#ffffff;
  box-shadow:0 4px 14px rgba(29,78,216,0.25);
  transition:all .2s;
}
.nav-mob-login:hover{filter:brightness(1.06)}

/* 6. Theme Icon Toggle Visibility */
.icon-sun{display:none}
.icon-moon{display:block}
html.dark .icon-sun{display:block}
html.dark .icon-moon{display:none}



/* ─── HERO ─── */
.hero{
  position:relative;
  padding:110px 24px 90px;
  text-align:center;
  overflow:hidden;
}
.hero::before{
  content:'';
  position:absolute;
  inset:-8px;
  background-image:linear-gradient(135deg,rgba(255,255,255,.88),rgba(255,255,255,.75)),url('{{ \App\Models\SiteSetting::get('hero_background', '/sekolaheskasaba.jpeg') }}');
  background-size:cover;
  background-position:center;
  filter:blur(1px) saturate(1.1);
  z-index:1;
  pointer-events:none;
}
.hero-inner{max-width:820px;margin:0 auto;position:relative;z-index:2}
.hero-badge{
  display:inline-flex;align-items:center;gap:8px;padding:6px 18px;
  background:rgba(255,255,255,.92);border:1px solid rgba(29,78,216,.2);
  border-radius:999px;color:#1d4ed8;font-size:13px;font-weight:700;
  letter-spacing:.04em;margin-bottom:28px;backdrop-filter:blur(8px);
  box-shadow:0 2px 8px rgba(29,78,216,.08);
}
.hero-badge-pulse{width:6px;height:6px;background:#1d4ed8;border-radius:50%;opacity:.8}
.hero-h1{font-size:48px;font-weight:800;line-height:1.15;letter-spacing:-.02em;color:#0f172a;margin-bottom:20px;font-family:'Inter',sans-serif;text-shadow:none}
.hero-h1 em{font-style:normal;color:#1d4ed8}
.hero-p{font-size:17px;color:#475569;line-height:1.65;margin-bottom:24px;max-width:580px;margin-left:auto;margin-right:auto;text-shadow:none;font-weight:400}

/* ─── COMMON SECTION ─── */
.section{padding:80px 24px}
.section-inner{max-width:1200px;margin:0 auto}
.section-eyebrow{display:inline-flex;align-items:center;gap:8px;font-size:11px;font-weight:700;color:#1d4ed8;letter-spacing:.1em;text-transform:uppercase;margin-bottom:12px;background:rgba(29,78,216,.08);padding:6px 16px;border-radius:999px;border:1px solid rgba(29,78,216,.15)}
.section-eyebrow-dot{width:6px;height:6px;border-radius:50%;background:#1d4ed8}
.section-h2{font-family:'Plus Jakarta Sans',sans-serif;font-size:36px;font-weight:800;line-height:1.2;letter-spacing:-.02em;color:#0f172a;margin-bottom:14px}
.section-h2 em{font-style:normal;color:#1d4ed8}
.section-lead{font-size:17px;color:var(--muted);line-height:1.65;max-width:540px;margin:0 auto;font-weight:400}
.section-head{text-align:center;margin-bottom:50px}

/* ─── CATEGORIES ─── */
.cat-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:16px}
.cat-card{background:var(--card);border:2px solid var(--border);border-radius:16px;padding:24px 16px 20px;text-align:center;cursor:pointer;transition:all .2s;position:relative;overflow:hidden}
.cat-card:hover{border-color:#1d4ed8;transform:translateY(-4px);box-shadow:0 12px 32px rgba(29,78,216,.12)}
.cat-icon-wrap{width:52px;height:52px;background:var(--bg2);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;transition:all .2s}
.cat-card:hover .cat-icon-wrap{background:#1d4ed8}
.cat-card:hover .cat-icon-wrap svg{color:#fff !important}
.cat-name{font-size:13px;font-weight:700;color:var(--text);margin-bottom:6px;letter-spacing:-.01em}
.cat-desc{font-size:12px;color:var(--muted);line-height:1.5;margin-bottom:12px;font-weight:400}
.cat-link{font-size:12px;font-weight:700;color:#1d4ed8;text-decoration:none;display:inline-flex;align-items:center;gap:4px;transition:gap .15s}
.cat-link:hover{gap:8px}

/* ─── FEATURES (ALUR PEMINJAMAN 4 LANGKAH) ─── */
.feat-bg{background:linear-gradient(180deg,#f8fafc 0%,#ffffff 100%);position:relative}
.feat-bg::before{content:'';position:absolute;top:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,#e2e8f0 50%,transparent)}

/* Desktop: 4 kolom sejajar dengan align-items: stretch */
.alur-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:24px;position:relative;margin-top:40px;align-items:stretch}

.alur-card{background:#ffffff;border:1px solid #e2e8f0;border-radius:20px;padding:24px 20px;text-align:center;transition:all .3s cubic-bezier(0.4,0,0.2,1);position:relative;box-shadow:0 2px 8px rgba(0,0,0,.04),0 1px 3px rgba(0,0,0,.02);display:flex;flex-direction:column;align-items:center}
.alur-card:hover{transform:translateY(-4px);border-color:#cbd5e1;box-shadow:0 8px 24px rgba(0,0,0,.08),0 4px 12px rgba(0,0,0,.04)}

/* Tile ikon besar dengan gradien biru dan glow radial */
.alur-icon-tile{width:64px;height:64px;border-radius:16px;background:linear-gradient(135deg,#1d4ed8 0%,#2563eb 100%);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;position:relative;box-shadow:0 4px 12px rgba(29,78,216,.2);transition:all .3s ease}
.alur-icon-tile::before{content:'';position:absolute;inset:-8px;background:radial-gradient(circle,rgba(29,78,216,.15) 0%,transparent 70%);border-radius:24px;opacity:0;transition:opacity .3s ease}
.alur-card:hover .alur-icon-tile{transform:scale(1.05);box-shadow:0 6px 16px rgba(29,78,216,.3)}
.alur-card:hover .alur-icon-tile::before{opacity:1}
.alur-icon-tile svg{width:28px;height:28px;color:#ffffff}

/* Nomor badge kecil di pojok kanan atas tile */
.alur-num-badge{position:absolute;top:-6px;right:-6px;width:24px;height:24px;border-radius:999px;background:#ffffff;border:2px solid #1d4ed8;display:flex;align-items:center;justify-content:center;font-family:'Plus Jakarta Sans',sans-serif;font-size:12px;font-weight:800;color:#1d4ed8;box-shadow:0 2px 6px rgba(0,0,0,.1)}

/* Label "Inti sistem" pill di pojok kanan atas kartu */
.alur-badge-core{position:absolute;top:12px;right:12px;display:inline-flex;align-items:center;gap:4px;padding:4px 10px;background:#eff6ff;border:1px solid #dbeafe;border-radius:999px;font-size:10px;font-weight:700;color:#1d4ed8;letter-spacing:.04em;text-transform:uppercase;z-index:3}

/* Judul & keterangan */
.alur-title{font-family:'Plus Jakarta Sans',sans-serif;font-size:18px;font-weight:600;color:#0f172a;margin:0 0 6px 0;line-height:1.3;letter-spacing:-.01em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.alur-card:hover .alur-title{color:#1d4ed8}
.alur-desc{font-size:14px;color:#64748b;line-height:1.4;font-weight:400;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}

/* Chevron/panah penghubung antar-kartu (desktop only) */
.alur-connector{position:absolute;top:60px;right:-12px;width:20px;height:20px;color:#94a3b8;z-index:1;display:flex;align-items:center;justify-content:center;background:#ffffff;border-radius:50%;border:1px solid #e2e8f0;box-shadow:0 2px 6px rgba(0,0,0,.04)}
.alur-connector svg{width:12px;height:12px}
.alur-card:last-child .alur-connector{display:none}

/* Chip strip tanpa panel pembungkus */
.alur-chips-panel{margin-top:28px;display:flex;align-items:center;justify-content:center;gap:12px;flex-wrap:wrap;padding:16px 20px;background:#eff6ff;border-radius:16px;border:1px solid #dbeafe}
.alur-chip{display:inline-flex;align-items:center;gap:6px;padding:6px 14px;background:#ffffff;border:1px solid #e2e8f0;border-radius:999px;font-size:12px;font-weight:600;color:#475569;transition:all .2s ease}
.alur-chip:hover{background:#f8fafc;border-color:#2563eb;color:#1d4ed8}
.alur-chip svg{width:14px;height:14px;color:#1d4ed8}

/* Tablet: grid 2x2 */
@media(max-width:1023px){
  .alur-grid{grid-template-columns:repeat(2,1fr);gap:20px}
  .alur-connector{display:none}
}

/* HP: timeline vertikal */
@media(max-width:640px){
  .alur-grid{display:flex;flex-direction:column;gap:16px;position:relative;padding-left:0}
  .alur-card{border:1px solid #e2e8f0;border-radius:16px;padding:16px 16px 16px 64px;text-align:left;position:relative}
  .alur-card:hover{transform:none}
  
  /* Garis vertikal tipis di kiri */
  .alur-grid::before{content:'';position:absolute;left:27px;top:60px;bottom:0;width:2px;background:#e2e8f0;display:block}
  
  /* Tile ikon ke kiri */
  .alur-icon-tile{position:absolute;left:0;top:12px;margin:0;width:54px;height:54px}
  .alur-icon-tile svg{width:26px;height:26px}
  .alur-num-badge{top:-4px;right:-4px;width:20px;height:20px;font-size:10px}
  
  .alur-title{font-size:16px;margin:0 0 4px 0}
  .alur-desc{font-size:13px;margin:0}
  .alur-badge-core{top:8px;right:8px}
  
  .alur-chips-panel{flex-direction:column;align-items:flex-start;gap:10px;padding:14px 18px;margin-top:24px}
  .alur-chip{width:100%;justify-content:center}
}

/* ─── STATS / DATA INVENTARIS ─── */
.stats-bg{background:#ffffff;position:relative;overflow:hidden;padding:70px 24px}
.stats-inner{max-width:1200px;margin:0 auto;position:relative;z-index:1;display:grid;grid-template-columns:1fr 1.1fr;gap:50px;align-items:center}
.stats-eyebrow{display:inline-flex;align-items:center;gap:8px;font-size:11px;font-weight:700;color:#1d4ed8;letter-spacing:.08em;text-transform:uppercase;margin-bottom:12px;background:rgba(29,78,216,.08);padding:6px 16px;border-radius:999px;border:1px solid rgba(29,78,216,.15)}
.stats-eyebrow-pulse{width:6px;height:6px;border-radius:50%;background:#2563eb}
.stats-h2{font-family:'Plus Jakarta Sans',sans-serif;font-size:34px;font-weight:800;color:#0f172a;line-height:1.2;margin-bottom:14px;letter-spacing:-.01em}
.stats-h2 em{font-style:normal;color:#2563eb}
.stats-p{font-size:16px;color:#4b5563;line-height:1.65;margin-bottom:24px;font-weight:400}
.stats-cta-btn{display:none !important}
.stats-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:16px}
.stat-block{background:#ffffff;border:1px solid #e5e7eb;border-radius:16px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,.04);transition:all .2s ease;position:relative;overflow:hidden}
.stat-block:hover{transform:translateY(-3px);box-shadow:0 8px 20px rgba(0,0,0,.08);border-color:#cbd5e1}
.stat-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px}
.stat-icon-b{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.stat-trend{font-size:11px;font-weight:700;padding:4px 10px;border-radius:999px}
.stat-num-b{font-family:'Plus Jakarta Sans',sans-serif;font-size:32px;font-weight:800;color:#0f172a;line-height:1;letter-spacing:-.02em}
.stat-lbl-b{font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:700;color:#1e293b;margin-top:8px;letter-spacing:-.01em}
.stat-sub-b{font-size:12px;font-weight:500;color:#64748b;margin-top:3px}

/* ─── ABOUT SECTION ─── */
.about-grid-redesigned{display:grid;grid-template-columns:1fr 1.15fr;gap:48px;align-items:center;position:relative}
.about-content-redesigned{position:relative;display:flex;flex-direction:column;gap:16px}
.about-eyebrow-redesigned{display:inline-flex;align-items:center;gap:8px;font-size:11px;font-weight:700;color:#1d4ed8;letter-spacing:.12em;text-transform:uppercase;position:relative;z-index:1}
.eyebrow-dot{width:6px;height:6px;background:#1d4ed8;border-radius:50%}
.about-headline-redesigned{font-family:'Plus Jakarta Sans',sans-serif;font-size:36px;font-weight:800;line-height:1.25;color:#0f172a;position:relative;z-index:1;letter-spacing:-.01em;margin:0}
.headline-accent{color:#1d4ed8;font-style:normal}
.about-desc-redesigned{font-size:16px;color:#64748b;line-height:1.8;position:relative;z-index:1;margin:0}
.about-visual-redesigned{display:flex;align-items:center;width:100%}
.school-photo-frame-redesigned{background:linear-gradient(145deg,#ffffff 0%,#f8fafc 100%);border:1.5px solid #e2e8f0;border-radius:24px;padding:16px;box-shadow:0 20px 48px rgba(29,78,216,.08);position:relative;overflow:hidden;width:100%}
.photo-wrapper{position:relative;border-radius:18px;overflow:hidden;aspect-ratio:16/10;width:100%}
.school-photo{width:100%;height:100%;object-fit:cover;object-position:center 30%;transition:transform .5s cubic-bezier(0.4,0,0.2,1);display:block}
.photo-wrapper:hover .school-photo{transform:scale(1.04)}
.photo-gradient{position:absolute;inset:0;background:linear-gradient(to top,rgba(15,23,42,.95) 0%,rgba(15,23,42,.4) 35%,transparent 65%);pointer-events:none}
.glass-badge{position:absolute;top:16px;right:16px;background:rgba(255,255,255,.88);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-radius:14px;padding:10px 16px;border:1px solid rgba(255,255,255,.4);box-shadow:0 8px 24px rgba(0,0,0,.12);display:flex;align-items:center;gap:10px;transition:all .3s ease;z-index:2}
.photo-wrapper:hover .glass-badge{background:rgba(255,255,255,.96);box-shadow:0 12px 32px rgba(0,0,0,.18)}
.badge-icon{width:28px;height:28px;background:linear-gradient(135deg,#1d4ed8 0%,#2563eb 100%);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;flex-shrink:0;box-shadow:0 4px 12px rgba(29,78,216,.3)}
.badge-content{display:flex;flex-direction:column;gap:2px}
.badge-year{font-size:10px;font-weight:800;color:#1d4ed8;letter-spacing:.08em;text-transform:uppercase}
.badge-name{font-size:12px;font-weight:700;color:#0f172a;line-height:1.2}
.photo-caption{position:absolute;bottom:0;left:0;right:0;padding:20px 24px;color:#fff;z-index:2}
.caption-label{font-family:'Plus Jakarta Sans',sans-serif;font-size:16px;font-weight:700;margin-bottom:6px;line-height:1.3}
.caption-sub{font-size:13px;color:rgba(255,255,255,.9);line-height:1.5;max-width:90%}

/* ─── FAQ PREVIEW (SECTION BANTUAN) ─── */
.faq-preview-sec{background:#f8fafc;padding:80px 24px;position:relative}
.faq-preview-inner{max-width:860px;margin:0 auto}
.faq-preview-head{text-align:center;margin-bottom:44px}
.faq-preview-list{display:flex;flex-direction:column;gap:12px;margin-bottom:28px}
.faq-preview-item{background:#ffffff;border:1.5px solid #e2e8f0;border-radius:14px;overflow:hidden;transition:border-color .2s,box-shadow .2s}
.faq-preview-item:hover{border-color:#2563eb}
.faq-preview-item.faq-open{border-color:#2563eb;box-shadow:0 4px 16px rgba(37,99,235,.08)}
.faq-preview-q{width:100%;padding:18px 22px;display:flex;align-items:center;justify-content:space-between;gap:16px;background:none;border:none;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;font-size:15px;font-weight:700;color:var(--text);text-align:left;transition:background .15s}
.faq-preview-q:hover{background:rgba(37,99,235,.04)}
.faq-preview-item.faq-open .faq-preview-q{color:#1d4ed8;background:rgba(37,99,235,.05)}
.faq-preview-icon{width:28px;height:28px;border-radius:8px;background:rgba(37,99,235,.08);border:1px solid rgba(37,99,235,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#2563eb;transition:transform .25s ease,background .25s ease,color .25s ease}
.faq-preview-item.faq-open .faq-preview-icon{background:#2563eb;color:#fff;border-color:#2563eb;transform:rotate(180deg)}
.faq-preview-a{display:none;padding:0 22px 18px;border-top:1px solid var(--border);animation:fadeSlideIn .2s ease}
.faq-preview-item.faq-open .faq-preview-a{display:block}
@keyframes fadeSlideIn{from{opacity:0;transform:translateY(-4px)}to{opacity:1;transform:translateY(0)}}
.faq-preview-steps{margin:14px 0 0;display:flex;flex-direction:column;gap:8px}
.faq-preview-step{display:flex;align-items:flex-start;gap:12px;font-size:14px;color:var(--text2);line-height:1.6}
.faq-preview-step-num{width:22px;height:22px;border-radius:6px;background:#1d4ed8;color:#fff;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;flex-shrink:0;margin-top:2px}
.faq-preview-note{margin-top:12px;padding:10px 14px;background:rgba(37,99,235,.06);border:1px solid rgba(37,99,235,.15);border-radius:8px;font-size:13px;color:var(--muted);line-height:1.55}
.faq-preview-more{text-align:center;margin-top:24px}
.faq-preview-link{font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:600;color:#1d4ed8;text-decoration:none;display:inline-flex;align-items:center;gap:6px;transition:gap .2s,color .2s}
.faq-preview-link:hover{gap:9px;color:#1e40af;text-decoration:underline}

/* ─── FOOTER ─── */
.footer{background:#ffffff;color:#475569;padding:72px 24px 32px;border-top:1px solid #e2e8f0;position:relative}
.footer-inner{max-width:1200px;margin:0 auto}
.footer-grid{display:grid;grid-template-columns:1.8fr 1fr;gap:48px 60px;margin-bottom:48px;align-items:start}
.footer-brand{display:flex;flex-direction:column;align-items:flex-start}
.footer-logo-wrap{display:flex;align-items:center;gap:12px;margin-bottom:16px}
.footer-logo-box{width:46px;height:46px;border-radius:50%;background:#ffffff;display:flex;align-items:center;justify-content:center;overflow:hidden;padding:4px;box-shadow:0 2px 10px rgba(0,0,0,.08);border:1px solid #e2e8f0;flex-shrink:0}
.footer-brand-name{font-family:'Plus Jakarta Sans',sans-serif;font-size:17px;font-weight:800;color:#0f172a;letter-spacing:-.01em;line-height:1.2}
.footer-brand-sub{font-size:11px;color:#1d4ed8;font-weight:700;letter-spacing:.06em;text-transform:uppercase}
.footer-desc{font-size:13.5px;line-height:1.7;color:#64748b;max-width:340px;font-weight:400;margin:0 0 12px 0}
.footer-brand-extra{margin-top:2px}
.footer-help-sublink{font-size:13px;font-weight:600;color:#1d4ed8;text-decoration:none;transition:color .2s}
.footer-help-sublink:hover{color:#1e40af;text-decoration:underline}
.footer-col{display:flex;flex-direction:column}
.footer-heading{font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;font-weight:700;color:#0f172a;margin-bottom:18px;letter-spacing:.04em;text-transform:uppercase}
.footer-list{list-style:none;display:flex;flex-direction:column;gap:12px;margin:0;padding:0}
.footer-list li{margin:0;padding:0}
.footer-list a{font-size:13.5px;color:#64748b;text-decoration:none;transition:all .2s ease;display:inline-flex;align-items:center;font-weight:500}
.footer-list a:hover{color:#1d4ed8;transform:translateX(4px)}
.footer-divider{border:none;border-top:1px solid #e2e8f0;margin-bottom:24px}
.footer-bottom{display:flex;align-items:center;justify-content:center;flex-wrap:wrap;gap:12px}
.footer-copy{text-align:center;font-size:12.5px;color:#64748b;font-weight:400;margin:0}

/* ─── RESPONSIVE ─── */
@media(max-width:1024px){
  .cat-grid{grid-template-columns:repeat(3,1fr)}
  .about-grid-redesigned{grid-template-columns:1fr;gap:36px}
  .about-content-redesigned{padding-right:0}
  .about-visual-redesigned{padding-left:0}
  .footer-grid{grid-template-columns:1fr 1fr;gap:32px 40px}
  .section{padding:60px 24px}
}
@media(max-width:768px){
  .nav-inner{height:68px;padding:0 16px;gap:12px}
  .nav-links,.nav-actions{display:none !important}
  .nav-mobile-ctrls{display:flex !important}
  .nav-ham{display:flex !important}
  .hero{padding:70px 16px 50px}
  .hero-h1{font-size:32px}
  .hero-p{font-size:15px}
  .cat-grid{grid-template-columns:repeat(2,1fr)}
  .stats-inner{grid-template-columns:1fr;gap:36px}
  .stats-h2{font-size:26px}
  .section-h2{font-size:26px}
  .about-headline-redesigned{font-size:26px}
  .faq-preview-sec{padding:60px 16px}
  .footer{padding:48px 20px 24px}
  .footer-grid{grid-template-columns:1fr;gap:28px;margin-bottom:36px}
}
@media(max-width:480px){
  .hero-h1{font-size:26px}
  .section-h2{font-size:22px}
  .about-headline-redesigned{font-size:22px}
  .stats-h2{font-size:22px}
  .stats-grid{grid-template-columns:1fr}
  .section{padding:48px 16px}
}

/* ═══════════════════════════════════════════════════════════════════
   ─── DARK MODE: GLOBAL GRADIENT BACKGROUND (VIBRANT TOP-RIGHT GLOW) ───
   ═══════════════════════════════════════════════════════════════════ */
html.dark body{
  background:#000004 !important;
  position:relative !important;
  isolation:isolate !important;
  min-height:100vh;
}
html.dark body::before{
  content:"";
  position:fixed;
  inset:0;
  z-index:-1;
  pointer-events:none;
  background:
    radial-gradient(circle at 100% 0%, rgba(59,130,246,0.06) 0%, transparent 50%),
    #060a14 !important;
}

#dark-bg{
  display:none;
}

/* All section wrappers go transparent in dark mode so the gradient shows through seamlessly (EXCEPT .hero) */
html.dark #kategori,
html.dark #fitur,
html.dark .feat-bg,
html.dark #data-inventaris,
html.dark .stats-bg,
html.dark #tentang,
html.dark .section,
html.dark #bantuan,
html.dark .faq-preview-sec,
html.dark .footer{
  background-color:transparent !important;
  background-image:none !important;
}
html.dark .feat-bg::before{display:none !important}
html.dark .stats-bg::before{display:none !important}

/* ═══════════════════════════════════════════════════════════════════
   ─── DARK MODE OVERRIDES (DARK NAVY GLASS CARDS, HERO VIGNETTE) ───
   ═══════════════════════════════════════════════════════════════════ */

/* 1. Navbar */
html.dark .nav{
  background:rgba(6,18,48,.85) !important;
  backdrop-filter:blur(16px) !important;
  -webkit-backdrop-filter:blur(16px) !important;
  border-bottom:1px solid rgba(59,130,246,.18) !important;
}
html.dark .nav.scrolled{
  background:rgba(6,18,48,.94) !important;
  border-bottom-color:rgba(59,130,246,.25) !important;
  box-shadow:0 2px 8px rgba(0,0,0,.3) !important;
}
html.dark .nav-brand-title{color:#8ab4ff !important}
html.dark .nav-brand-subtitle{color:#8fa3c4 !important}
html.dark .nav-logo-wrap{
  background:rgba(255,255,255,.92) !important;
  box-shadow:0 2px 6px rgba(0,0,0,.3) !important;
}
html.dark .nav-links{
  background:rgba(6,18,48,.75) !important;
  border-color:rgba(59,130,246,.18) !important;
}
html.dark .nav-links a{color:#8fa3c4 !important}
html.dark .nav-links a:hover{
  background:rgba(59,130,246,.10) !important;
  color:#f4f8ff !important;
}
html.dark .nav-links a.active{
  background:#2563eb !important;
  color:#ffffff !important;
  box-shadow:none !important;
}
html.dark .theme-toggle{
  background:rgba(6,18,48,.75) !important;
  border-color:rgba(59,130,246,.18) !important;
  color:#8fa3c4 !important;
  box-shadow:none !important;
}
html.dark .theme-toggle:hover{
  background:rgba(59,130,246,.10) !important;
  border-color:#8ab4ff !important;
  color:#8ab4ff !important;
}
html.dark .btn-nav-cta{
  background:#2563eb !important;
  color:#ffffff !important;
  box-shadow:none !important;
}
html.dark .btn-nav-cta:hover{
  background:#1d4ed8 !important;
  transform:none !important;
  box-shadow:none !important;
}
html.dark .nav-ham{
  background:rgba(6,18,48,.75) !important;
  border-color:rgba(59,130,246,.18) !important;
  color:#8fa3c4 !important;
}
html.dark .ham-line{background:#8fa3c4 !important}
html.dark .nav-mobile{
  background:rgba(6,18,48,.96) !important;
  border-top-color:rgba(59,130,246,.18) !important;
  box-shadow:0 8px 24px rgba(0,0,0,.3) !important;
}
html.dark .nav-mobile-links a{
  background:rgba(12,26,62,.85) !important;
  border:1px solid rgba(59,130,246,.15) !important;
  color:#8fa3c4 !important;
}
html.dark .nav-mobile-links a:hover{
  background:rgba(59,130,246,.10) !important;
  color:#f4f8ff !important;
}
html.dark .nav-mob-login{
  background:#2563eb !important;
  color:#ffffff !important;
  box-shadow:none !important;
}

/* 2. Hero Section (Photo Banner with smooth dark fade & vignette) */
html.dark .hero{
  background:none !important;
}
html.dark .hero::before{
  background-image:linear-gradient(180deg, rgba(0,10,30,.55) 0%, rgba(0,10,30,.45) 45%, rgba(0,4,20,.85) 100%), url('{{ \App\Models\SiteSetting::get('hero_background', '/sekolaheskasaba.jpeg') }}') !important;
  filter:blur(1px) saturate(1.1) !important;
}
html.dark .hero::after{
  content:"";
  position:absolute;
  inset:0;
  box-shadow:inset 0 0 120px rgba(0,0,0,.55);
  pointer-events:none;
  z-index:1;
}
html.dark .hero-badge{
  background:rgba(6,18,48,.85) !important;
  border-color:rgba(59,130,246,.25) !important;
  color:#f4f8ff !important;
  backdrop-filter:blur(8px) !important;
  -webkit-backdrop-filter:blur(8px) !important;
  box-shadow:none !important;
}
html.dark .hero-badge-pulse{background:#8ab4ff !important;opacity:1 !important}
html.dark .hero-h1{
  color:#ffffff !important;
  text-shadow:0 1px 8px rgba(0,0,0,.2) !important;
}
html.dark .hero-h1 em{color:#ffffff !important}
html.dark .hero-p{
  color:#f8fafc !important;
  text-shadow:0 1px 2px rgba(0,0,0,.15) !important;
}

/* 3. Section Headings (Outside Cards - High Contrast on Dark Gradient) */
html.dark #fitur{border-top:none !important}
html.dark .stats-bg{border-top:none !important;border-bottom:none !important}

html.dark .section-eyebrow{
  background:rgba(59,130,246,.10) !important;
  border-color:rgba(59,130,246,.25) !important;
  color:#8ab4ff !important;
}
html.dark .section-eyebrow-dot{background:#8ab4ff !important}
html.dark .section-h2{color:#f4f8ff !important}
html.dark .section-h2 em{color:#8ab4ff !important}
html.dark .section-lead{color:#b4c3dc !important}

/* 4. Alur Peminjaman Cards (Dark Navy Glass with Blur) */
html.dark .alur-card{
  background:rgba(6,18,48,.72) !important;
  backdrop-filter:blur(10px) !important;
  -webkit-backdrop-filter:blur(10px) !important;
  border:1px solid rgba(59,130,246,.14) !important;
  box-shadow:0 2px 8px rgba(0,0,0,.3) !important;
}
html.dark .alur-card:hover{
  border-color:rgba(59,130,246,.3) !important;
  transform:translateY(-2px) !important;
  box-shadow:0 4px 16px rgba(0,0,0,.3) !important;
}
html.dark .alur-icon-tile{
  background:linear-gradient(135deg,rgba(37,99,235,.6) 0%,rgba(59,130,246,.5) 100%) !important;
  box-shadow:0 2px 8px rgba(0,0,0,.3) !important;
}
html.dark .alur-card:hover .alur-icon-tile{
  box-shadow:0 4px 12px rgba(0,0,0,.35) !important;
}
html.dark .alur-icon-tile::before{
  display:none !important;
}
html.dark .alur-num-badge{
  background:rgba(6,18,48,.85) !important;
  border-color:rgba(59,130,246,.25) !important;
  color:#8ab4ff !important;
  box-shadow:0 2px 6px rgba(0,0,0,.25) !important;
}
html.dark .alur-badge-core{
  background:rgba(59,130,246,.10) !important;
  border-color:rgba(59,130,246,.25) !important;
  color:#8ab4ff !important;
}
html.dark .alur-connector{
  background:rgba(6,18,48,.85) !important;
  border-color:rgba(59,130,246,.14) !important;
  color:#8fa3c4 !important;
  box-shadow:0 2px 6px rgba(0,0,0,.25) !important;
}
html.dark .alur-title{color:#f4f8ff !important}
html.dark .alur-card:hover .alur-title{color:#8ab4ff !important}
html.dark .alur-desc{color:#b4c3dc !important}
html.dark .alur-grid::before{
  background:rgba(59,130,246,.14) !important;
}
html.dark .alur-chips-panel{
  background:rgba(0,10,30,.55) !important;
  border-color:rgba(59,130,246,.14) !important;
}
html.dark .alur-chip{
  background:rgba(6,18,48,.72) !important;
  border-color:rgba(59,130,246,.14) !important;
  color:#b4c3dc !important;
}
html.dark .alur-chip:hover{
  background:rgba(59,130,246,.10) !important;
  border-color:rgba(59,130,246,.25) !important;
  color:#8ab4ff !important;
}
html.dark .alur-chip svg{color:#8ab4ff !important}

/* 5. Stats Section (Dark Navy Glass with Blur & Pastel Icons) */
html.dark .stats-eyebrow{
  background:rgba(59,130,246,.10) !important;
  border-color:rgba(59,130,246,.25) !important;
  color:#8ab4ff !important;
}
html.dark .stats-eyebrow-pulse{background:#8ab4ff !important}
html.dark .stats-h2{color:#f4f8ff !important}
html.dark .stats-h2 em{color:#8ab4ff !important}
html.dark .stats-p{color:#b4c3dc !important}

html.dark .stat-block{
  background:rgba(6,18,48,.72) !important;
  backdrop-filter:blur(10px) !important;
  -webkit-backdrop-filter:blur(10px) !important;
  border:1px solid rgba(59,130,246,.14) !important;
  box-shadow:0 2px 8px rgba(0,0,0,.3) !important;
}
html.dark .stat-block:hover{
  border-color:rgba(59,130,246,.3) !important;
  box-shadow:0 4px 16px rgba(0,0,0,.3) !important;
}
html.dark .stat-num-b{color:#f4f8ff !important}
html.dark .stat-lbl-b{color:#dbe7ff !important}
html.dark .stat-sub-b{color:#8fa3c4 !important}

html.dark .stat-block:nth-child(1) .stat-icon-b{
  background:rgba(37,99,235,.14) !important;
  border:1px solid rgba(59,130,246,.22) !important;
}
html.dark .stat-block:nth-child(1) .stat-icon-b svg{
  stroke:#8ab4ff !important;
}
html.dark .stat-block:nth-child(1) .stat-trend{
  background:rgba(59,130,246,.10) !important;
  color:#8ab4ff !important;
  border:1px solid rgba(59,130,246,.25) !important;
}

html.dark .stat-block:nth-child(2) .stat-icon-b{
  background:rgba(14,165,233,.14) !important;
  border:1px solid rgba(56,189,248,.22) !important;
}
html.dark .stat-block:nth-child(2) .stat-icon-b svg{
  stroke:#7dd3fc !important;
}
html.dark .stat-block:nth-child(2) .stat-trend{
  background:rgba(14,165,233,.10) !important;
  color:#7dd3fc !important;
  border:1px solid rgba(56,189,248,.25) !important;
}

html.dark .stat-block:nth-child(3) .stat-icon-b{
  background:rgba(59,130,246,.14) !important;
  border:1px solid rgba(96,165,250,.22) !important;
}
html.dark .stat-block:nth-child(3) .stat-icon-b svg{
  stroke:#93c5fd !important;
}
html.dark .stat-block:nth-child(3) .stat-trend{
  background:rgba(59,130,246,.10) !important;
  color:#93c5fd !important;
  border:1px solid rgba(96,165,250,.25) !important;
}

html.dark .stat-block:nth-child(4) .stat-icon-b{
  background:rgba(16,185,129,.14) !important;
  border:1px solid rgba(52,211,153,.22) !important;
}
html.dark .stat-block:nth-child(4) .stat-icon-b svg{
  stroke:#6ee7b7 !important;
}
html.dark .stat-block:nth-child(4) .stat-trend{
  background:rgba(16,185,129,.10) !important;
  color:#6ee7b7 !important;
  border:1px solid rgba(52,211,153,.25) !important;
}

/* 6. Tentang Section */
html.dark .decorative-number{color:rgba(59,130,246,.05) !important}
html.dark .about-eyebrow-redesigned{color:#8ab4ff !important}
html.dark .eyebrow-dot{background:#8ab4ff !important}
html.dark .about-headline-redesigned{color:#f4f8ff !important}
html.dark .headline-accent{color:#8ab4ff !important}
html.dark .about-desc-redesigned{color:#b4c3dc !important}

html.dark .school-photo-frame-redesigned{
  background:rgba(6,18,48,.85) !important;
  border:1px solid rgba(59,130,246,.22) !important;
  box-shadow:0 8px 24px rgba(0,0,0,.3) !important;
}
html.dark .photo-gradient{
  background:linear-gradient(180deg, rgba(0,0,0,0) 40%, rgba(0,8,24,.75) 100%) !important;
  box-shadow:inset 0 0 40px rgba(0,0,0,.3) !important;
}
html.dark .glass-badge{
  background:rgba(6,18,48,.90) !important;
  border:1px solid rgba(59,130,246,.25) !important;
  box-shadow:0 2px 8px rgba(0,0,0,.25) !important;
}
html.dark .photo-wrapper:hover .glass-badge{
  background:rgba(6,18,48,.96) !important;
  box-shadow:0 4px 12px rgba(0,0,0,.3) !important;
}
html.dark .badge-icon{
  background:rgba(59,130,246,.15) !important;
  border:1px solid rgba(59,130,246,.25) !important;
  color:#8ab4ff !important;
  box-shadow:none !important;
}
html.dark .badge-year{color:#8ab4ff !important}
html.dark .badge-name{color:#f4f8ff !important}

/* 7. FAQ Preview (Section Bantuan) in Dark Mode: Dark Navy Cards */
html.dark .faq-preview-sec{
  border-top:none !important;
  border-bottom:none !important;
}
html.dark .faq-preview-item{
  background:rgba(6,18,48,.85) !important;
  border:1px solid rgba(59,130,246,.14) !important;
  box-shadow:0 2px 8px rgba(0,0,0,.3) !important;
}
html.dark .faq-preview-item:hover{
  border-color:rgba(59,130,246,.3) !important;
}
html.dark .faq-preview-item.faq-open{
  border-color:rgba(59,130,246,.3) !important;
  box-shadow:0 4px 14px rgba(0,0,0,.3) !important;
}
html.dark .faq-preview-q{color:#f4f8ff !important}
html.dark .faq-preview-q:hover{background:rgba(59,130,246,.05) !important}
html.dark .faq-preview-item.faq-open .faq-preview-q{
  color:#8ab4ff !important;
  background:rgba(59,130,246,.08) !important;
}
html.dark .faq-preview-icon{
  background:rgba(59,130,246,.10) !important;
  border-color:rgba(59,130,246,.22) !important;
  color:#8ab4ff !important;
}
html.dark .faq-preview-item.faq-open .faq-preview-icon{
  background:#2563eb !important;
  color:#ffffff !important;
  border-color:#2563eb !important;
}
html.dark .faq-preview-a{
  background:rgba(0,10,30,.45) !important;
  border-top:1px solid rgba(59,130,246,.12) !important;
}
html.dark .faq-preview-step{color:#b4c3dc !important}
html.dark .faq-preview-step-num{background:#2563eb !important;color:#ffffff !important}
html.dark .faq-preview-note{
  background:rgba(59,130,246,.08) !important;
  border-color:rgba(59,130,246,.18) !important;
  color:#dbe7ff !important;
}
html.dark .faq-preview-link{color:#8ab4ff !important}
html.dark .faq-preview-link:hover{color:#93c5fd !important}

/* 8. Footer in Dark Mode */
html.dark .footer{
  border-top:none !important;
}
html.dark .footer-logo-box{
  background:rgba(255,255,255,.95) !important;
  border-color:rgba(255,255,255,.8) !important;
  box-shadow:none !important;
}
html.dark .footer-brand-name{color:#f4f8ff !important}
html.dark .footer-brand-sub{color:#8ab4ff !important}
html.dark .footer-desc{color:#b4c3dc !important}
html.dark .footer-help-sublink{color:#8ab4ff !important}
html.dark .footer-help-sublink:hover{color:#93c5fd !important}
html.dark .footer-heading{color:#f4f8ff !important}
html.dark .footer-list a{color:#b4c3dc !important}
html.dark .footer-list a:hover{color:#8ab4ff !important}
html.dark .footer-divider{border-top-color:rgba(59,130,246,.15) !important}
html.dark .footer-copy{color:#8fa3c4 !important}
</style>
</head>
<body>
{{-- Dark mode global gradient background (fixed, z-index:-1) --}}
<div id="dark-bg" aria-hidden="true"></div>


{{-- ═══════════════ NAVBAR ═══════════════ --}}
<header class="site-header">
<nav class="nav">
  <div class="nav-inner">
    {{-- Brand Logo & Text --}}
    <a href="{{ route('home') }}" class="nav-brand" aria-label="{{ \App\Models\SiteSetting::get('site_name', 'SIPBAR') }} Homepage">
      <div class="nav-logo-wrap">
        <img src="{{ $siteLogoLanding ?? (\App\Models\SiteSetting::get('site_logo_landing') ?: \App\Models\SiteSetting::get('site_logo', '/logossmkn1.png')) }}" alt="Logo {{ \App\Models\SiteSetting::get('site_subtitle', 'SMKN 1 Bangsri') }}" class="nav-brand-img">
      </div>
      <div class="nav-brand-text">
        <div class="nav-brand-title">{{ \App\Models\SiteSetting::get('site_name', 'SIPBAR') }}</div>
        <div class="nav-brand-subtitle">{{ \App\Models\SiteSetting::get('site_subtitle', 'SMKN 1 BANGSRI') }}</div>
      </div>
    </a>

    {{-- Center Navigation Links --}}
    <div class="nav-links">
      <a href="#beranda">{{ \App\Models\SiteSetting::get('nav_link_home', 'Beranda') }}</a>
      <a href="#fitur">{{ \App\Models\SiteSetting::get('nav_link_features', 'Fitur') }}</a>
      <a href="#data-inventaris">{{ \App\Models\SiteSetting::get('nav_link_inventory', 'Katalog Barang') }}</a>
      <a href="#tentang">{{ \App\Models\SiteSetting::get('nav_link_about', 'Tentang') }}</a>
      <a href="#bantuan">{{ \App\Models\SiteSetting::get('nav_link_help', 'Bantuan') }}</a>
    </div>

    {{-- Right Actions: Desktop Theme Toggle & Login CTA --}}
    <div class="nav-actions">
      <button type="button" class="theme-toggle theme-toggle-btn" aria-label="Ganti Tema" title="Ganti Tema">
        <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10A5 5 0 0012 7z"/>
        </svg>
        <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
        </svg>
      </button>

      @auth
        <a href="{{ route('dashboard') }}" class="btn-nav-cta">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
          </svg>
          <span>Dashboard</span>
        </a>
      @else
        <a href="{{ route('login') }}" class="btn-nav-cta">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
          </svg>
          <span>Masuk</span>
        </a>
      @endauth
    </div>

    {{-- Mobile Controls (Theme Toggle & Hamburger) --}}
    <div class="nav-mobile-ctrls">
      <button type="button" class="theme-toggle theme-toggle-btn" aria-label="Ganti Tema" title="Ganti Tema">
        <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10A5 5 0 0012 7z"/>
        </svg>
        <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
        </svg>
      </button>
      <button type="button" class="nav-ham" id="navHamBtn" aria-label="Buka Menu" aria-expanded="false">
        <span class="ham-line"></span>
        <span class="ham-line"></span>
        <span class="ham-line"></span>
      </button>
    </div>
  </div>

  {{-- Mobile Drawer Menu --}}
  <div id="navMob" class="nav-mobile">
    <div class="nav-mobile-links">
      <a href="#beranda" onclick="closeNavMob()">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        <span>{{ \App\Models\SiteSetting::get('nav_link_home_mobile', 'Beranda') }}</span>
      </a>
      <a href="#fitur" onclick="closeNavMob()">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
        <span>{{ \App\Models\SiteSetting::get('nav_link_features_mobile', 'Fitur Unggulan') }}</span>
      </a>
      <a href="#data-inventaris" onclick="closeNavMob()">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        <span>{{ \App\Models\SiteSetting::get('nav_link_inventory_mobile', 'Katalog Barang') }}</span>
      </a>
      <a href="#tentang" onclick="closeNavMob()">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>{{ \App\Models\SiteSetting::get('nav_link_about_mobile', 'Tentang SIPBAR') }}</span>
      </a>
      <a href="#bantuan" onclick="closeNavMob()">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        <span>{{ \App\Models\SiteSetting::get('nav_link_help_mobile', 'Bantuan & FAQ') }}</span>
      </a>
    </div>

    <div class="nav-mobile-footer">
      @auth
        <a href="{{ route('dashboard') }}" class="nav-mob-login">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
          <span>Masuk Dashboard</span>
        </a>
      @else
        <a href="{{ route('login') }}" class="nav-mob-login">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
          <span>Masuk ke {{ \App\Models\SiteSetting::get('site_name', 'SIPBAR') }}</span>
        </a>
      @endauth
    </div>
  </div>
</nav>
</header>

{{-- ═══════════════ HERO ═══════════════ --}}
<section class="hero" id="beranda">
  <div class="hero-inner">
    <div>
      <div class="hero-badge"><span class="hero-badge-pulse"></span>{{ \App\Models\SiteSetting::get('hero_badge', 'Sistem Peminjaman Online') }}</div>
      <h1 class="hero-h1">{!! \App\Models\SiteSetting::html('hero_title', 'Pinjam Barang Sekolah<br><em>Lebih Mudah</em> & Cepat') !!}</h1>
      <p class="hero-p">{{ \App\Models\SiteSetting::get('hero_description', 'Platform digital terintegrasi untuk peminjaman sarana dan alat praktik sekolah dengan persetujuan online & QR code.') }}</p>
    </div>
  </div>
</section>

{{-- ═══════════════ ALUR PEMINJAMAN 4 LANGKAH ═══════════════ --}}
@php
// Hardcode 4 langkah alur peminjaman
$alurLangkah = [
  [
    'num' => '1',
    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>',
    'title' => 'Ajukan',
    'desc' => 'Pilih barang & ajukan',
    'isCore' => false
  ],
  [
    'num' => '2',
    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    'title' => 'Disetujui',
    'desc' => 'Guru setujui via WA',
    'isCore' => false
  ],
  [
    'num' => '3',
    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>',
    'title' => 'Ambil',
    'desc' => 'Scan QR saat ambil',
    'isCore' => true
  ],
  [
    'num' => '4',
    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>',
    'title' => 'Kembalikan',
    'desc' => 'Scan QR saat kembali',
    'isCore' => false
  ]
];


@endphp

<section class="section feat-bg" id="fitur" style="padding:48px 24px;">
  <div class="section-inner">
    <div class="section-head">
      <div class="section-eyebrow"><span class="section-eyebrow-dot"></span>ALUR PEMINJAMAN</div>
      <h2 class="section-h2">Pinjam Barang dalam 4 Langkah</h2>
      <p class="section-lead">Proses peminjaman barang sekolah jadi lebih cepat dan transparan</p>
    </div>

    <div class="alur-grid" data-alur-observe>
      @foreach($alurLangkah as $langkah)
      <div class="alur-card alur-fade-up">
        @if($langkah['isCore'])
        <div class="alur-badge-core">
          <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
          Inti sistem
        </div>
        @endif
        <div class="alur-icon-tile">
          <span class="alur-num-badge">{{ $langkah['num'] }}</span>
          {!! $langkah['icon'] !!}
        </div>
        <h3 class="alur-title">{{ $langkah['title'] }}</h3>
        <p class="alur-desc">{{ $langkah['desc'] }}</p>
        <div class="alur-connector">
          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<script>
(function() {
  // Stagger fade-up animation with reduced-motion support
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (prefersReducedMotion) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const grid = entry.target;
        const cards = grid.querySelectorAll('.alur-card');
        
        // Stagger cards with 80ms delay
        cards.forEach((card, index) => {
          setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
          }, index * 80);
        });
        
        observer.unobserve(grid);
      }
    });
  }, { threshold: 0.2 });

  const grid = document.querySelector('[data-alur-observe]');
  if (grid) {
    // Initialize cards as hidden
    const cards = grid.querySelectorAll('.alur-card');
    cards.forEach(card => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    });
    observer.observe(grid);
  }
})();
</script>

{{-- ═══════════════ STATS & DATA INVENTARIS ═══════════════ --}}
@php
use Illuminate\Support\Facades\Cache;
use App\Models\Item;
use App\Models\Category;
use App\Models\User;
use App\Models\BorrowingRequest;

// Cache statistics for 15 minutes to improve performance
$stats = Cache::remember('homepage_stats', 900, function () {
    $totalItems = Item::count();
    $totalCategories = Category::count();

    // Get top category name
    $topCategory = Category::withCount('items')
        ->orderBy('items_count', 'desc')
        ->first();
    $topCategoryName = $topCategory ? $topCategory->name : 'Berbagai kategori';

    // Count users by role
    $usersByRole = User::selectRaw('
        COUNT(*) as total,
        SUM(CASE WHEN id IN (SELECT model_id FROM model_has_roles WHERE role_id = (SELECT id FROM roles WHERE name = "siswa")) THEN 1 ELSE 0 END) as siswa,
        SUM(CASE WHEN id IN (SELECT model_id FROM model_has_roles WHERE role_id = (SELECT id FROM roles WHERE name = "guru")) THEN 1 ELSE 0 END) as guru,
        SUM(CASE WHEN id IN (SELECT model_id FROM model_has_roles WHERE role_id = (SELECT id FROM roles WHERE name = "admin")) THEN 1 ELSE 0 END) as admin
    ')->first();

    $totalUsers = $usersByRole->total ?? 0;
    $userBreakdown = sprintf('%d Siswa, %d Guru', $usersByRole->siswa ?? 0, $usersByRole->guru ?? 0);

    // Total borrowing transactions
    $totalBorrowings = BorrowingRequest::count();

    // Calculate completion rate
    $completedBorrowings = BorrowingRequest::where('status', BorrowingRequest::STATUS_RETURNED)->count();
    $completionRate = $totalBorrowings > 0 ? round(($completedBorrowings / $totalBorrowings) * 100, 1) : 0;

    $readyItems = Item::where('status', 'available')->count();

    return [
        'total_items' => $totalItems,
        'ready_items' => $readyItems,
        'total_categories' => $totalCategories,
        'top_category' => $topCategoryName,
        'total_users' => $totalUsers,
        'user_breakdown' => $userBreakdown,
        'total_borrowings' => $totalBorrowings,
        'completion_rate' => $completionRate,
    ];
});

$statsCards = \App\Models\SiteSetting::getJson('stats_cards', []);
$stat = function (int $i, string $key, $default = '') use ($statsCards) {
    return $statsCards[$i][$key] ?? $default;
};
@endphp

<section class="stats-bg" id="data-inventaris">
  <div class="stats-inner">

    <div>
      <div class="stats-eyebrow">
        <span class="stats-eyebrow-pulse"></span>
        {{ \App\Models\SiteSetting::get('stats_eyebrow', 'Transparansi Data') }}
      </div>
      <h2 class="stats-h2">{!! \App\Models\SiteSetting::html('stats_title', 'Ketersediaan Barang & Peminjaman<br><em>dalam Real-Time</em>') !!}</h2>
      <p class="stats-p">{{ \App\Models\SiteSetting::get('stats_description', 'Pantau ketersediaan barang dan riwayat sirkulasi peminjaman sekolah secara terintegrasi, transparan, dan real-time.') }}</p>
    </div>
    <div class="stats-grid">
      {{-- Total Items --}}
      <div class="stat-block">
        <div class="stat-header">
          <div class="stat-icon-b" style="background:#1d4ed8">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#ffffff" fill="none" viewBox="0 0 24 24" stroke="#ffffff"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
          </div>
          <span class="stat-trend" style="background:#eff6ff;color:#1d4ed8">{{ $stat(0, 'trend', 'Tersedia') }}</span>
        </div>
        <div class="stat-num-b">{{ number_format($stats['total_items'] ?? 0, 0, ',', '.') }}</div>
        <div class="stat-lbl-b">{{ $stat(0, 'label', 'Total Unit Barang') }}</div>
        <div class="stat-sub-b">{{ number_format($stats['ready_items'] ?? 0, 0, ',', '.') }} {{ $stat(0, 'sublabel', 'siap digunakan') }}</div>
      </div>

      {{-- Categories --}}
      <div class="stat-block">
        <div class="stat-header">
          <div class="stat-icon-b" style="background:#0284c7">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#ffffff" fill="none" viewBox="0 0 24 24" stroke="#ffffff"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
          </div>
          <span class="stat-trend" style="background:#e0f2fe;color:#0369a1">{{ $stat(1, 'trend', 'Terstruktur') }}</span>
        </div>
        <div class="stat-num-b">{{ number_format($stats['total_categories'] ?? 0, 0, ',', '.') }}</div>
        <div class="stat-lbl-b">{{ $stat(1, 'label', 'Kategori Barang') }}</div>
        <div class="stat-sub-b">{{ $stats['top_category'] ?? 'Berbagai Kategori' }} {{ $stat(1, 'sublabel', 'terbanyak') }}</div>
      </div>

      {{-- Total Users --}}
      <div class="stat-block">
        <div class="stat-header">
          <div class="stat-icon-b" style="background:#7c3aed">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#ffffff" fill="none" viewBox="0 0 24 24" stroke="#ffffff"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          </div>
          <span class="stat-trend" style="background:#f3e8ff;color:#6b21a8">{{ $stat(2, 'trend', 'Tersinkron') }}</span>
        </div>
        <div class="stat-num-b">{{ number_format($stats['total_users'] ?? 0, 0, ',', '.') }}</div>
        <div class="stat-lbl-b">{{ $stat(2, 'label', 'Pengguna Aktif') }}</div>
        <div class="stat-sub-b">{{ trim((string) $stat(2, 'sublabel')) !== '' ? $stat(2, 'sublabel') : ($stats['user_breakdown'] ?? '') }}</div>
      </div>

      {{-- Total Borrowings --}}
      <div class="stat-block">
        <div class="stat-header">
          <div class="stat-icon-b" style="background:#059669">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#ffffff" fill="none" viewBox="0 0 24 24" stroke="#ffffff"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
          </div>
          <span class="stat-trend" style="background:#d1fae5;color:#065f46">{{ $stats['completion_rate'] ?? 0 }}% {{ $stat(3, 'trend', 'Selesai') }}</span>
        </div>
        <div class="stat-num-b">{{ number_format($stats['total_borrowings'] ?? 0, 0, ',', '.') }}</div>
        <div class="stat-lbl-b">{{ $stat(3, 'label', 'Sirkulasi Peminjaman') }}</div>
        <div class="stat-sub-b">{{ $stat(3, 'sublabel', 'Proses approval cepat') }}</div>
      </div>
    </div>
  </div>
</section>

{{-- ═══════════════ TENTANG KAMI ═══════════════ --}}
<section class="section" id="tentang">
  <div class="section-inner">
    <div class="about-grid-redesigned">
      {{-- Left column: Content --}}
      <div class="about-content-redesigned">
        <div class="about-eyebrow-redesigned">
          <span class="eyebrow-dot"></span>
          {{ \App\Models\SiteSetting::get('about_eyebrow', 'Tentang Platform SIPBAR') }}
        </div>

        <h2 class="about-headline-redesigned">
          {!! \App\Models\SiteSetting::html('about_title', 'Membangun Sistem Peminjaman Barang yang <span class="headline-accent">Terintegrasi</span>') !!}
        </h2>

        <p class="about-desc-redesigned">
          {{ \App\Models\SiteSetting::get('about_description', 'SIPBAR mentransformasi proses peminjaman alat dan barang sekolah konvensional menjadi ekosistem digital yang terintegrasi, transparan, dan mudah dipantau.') }}
        </p>
      </div>

      {{-- Right column: Photo frame --}}
      <div class="about-visual-redesigned">
        <div class="school-photo-frame-redesigned">
          <div class="photo-wrapper">
            <img
              src="{{ \App\Models\SiteSetting::get('about_image', '/sekolaheskasaba.jpeg') }}"
              alt="{{ \App\Models\SiteSetting::get('about_caption_label', 'Foto Gedung SMKN 1 Bangsri') }}"
              class="school-photo"
            />
            <div class="photo-gradient"></div>

            <div class="glass-badge">
              <div class="badge-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
              </div>
              <div class="badge-content">
                <span class="badge-year">{{ \App\Models\SiteSetting::get('about_badge_year', '2026') }}</span>
                <span class="badge-name">{{ \App\Models\SiteSetting::get('about_badge_name', 'SMKN 1 BANGSRI') }}</span>
              </div>
            </div>

            <div class="photo-caption">
              <div class="caption-label">{{ \App\Models\SiteSetting::get('about_caption_label', 'Gedung Utama Sekolah') }}</div>
              <div class="caption-sub">{{ \App\Models\SiteSetting::get('about_caption_sub', 'Pusat operasional dan tata kelola sarana prasarana SMKN 1 BANGSRI') }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ═══════════════ FAQ RINGKAS (SECTION BANTUAN) ═══════════════ --}}
<section class="faq-preview-sec" id="bantuan">
  <div class="faq-preview-inner">
    <div class="faq-preview-head">
      <div class="section-eyebrow"><span class="section-eyebrow-dot"></span>Bantuan & FAQ</div>
      <h2 class="section-h2">Pertanyaan yang <em>Sering Diajukan</em></h2>
      <p class="section-lead">Jawaban cepat atas kendala umum seputar akun, peminjaman, persetujuan guru, dan pengembalian di SIPBAR.</p>
    </div>

    <div class="faq-preview-list">
      {{-- FAQ 1: Akun & Login --}}
      <div class="faq-preview-item">
        <button type="button" class="faq-preview-q" aria-expanded="false">
          <span>Lupa password atau tidak bisa login ke akun SIPBAR?</span>
          <span class="faq-preview-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
          </span>
        </button>
        <div class="faq-preview-a">
          <div class="faq-preview-steps">
            <div class="faq-preview-step">
              <span class="faq-preview-step-num">1</span>
              <span>Pastikan username / NISN / email dan password yang dimasukkan sudah sesuai (perhatikan huruf besar/kecil).</span>
            </div>
            <div class="faq-preview-step">
              <span class="faq-preview-step-num">2</span>
              <span>Jika tetap tidak bisa masuk, hubungi petugas / admin sekolah untuk melakukan reset password akun Anda.</span>
            </div>
            <div class="faq-preview-step">
              <span class="faq-preview-step-num">3</span>
              <span>Setelah di-reset oleh petugas, masuk kembali melalui halaman <strong>Login</strong> dengan password baru.</span>
            </div>
          </div>
          <div class="faq-preview-note">Catatan: Perubahan data kredensial siswa dan guru dikelola langsung oleh petugas / admin sekolah.</div>
        </div>
      </div>

      {{-- FAQ 2: Katalog & Stok --}}
      <div class="faq-preview-item">
        <button type="button" class="faq-preview-q" aria-expanded="false">
          <span>Barang yang ingin dipinjam tidak muncul di katalog atau stok habis?</span>
          <span class="faq-preview-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
          </span>
        </button>
        <div class="faq-preview-a">
          <div class="faq-preview-steps">
            <div class="faq-preview-step">
              <span class="faq-preview-step-num">1</span>
              <span>Periksa ketersediaan barang di Katalog Barang; barang dengan stok 0 sedang dipinjam oleh peminjam lain atau sedang dalam pemeliharaan.</span>
            </div>
            <div class="faq-preview-step">
              <span class="faq-preview-step-num">2</span>
              <span>Cek kembali secara berkala saat barang telah selesai dikembalikan ke tempat penyimpanan barang.</span>
            </div>
            <div class="faq-preview-step">
              <span class="faq-preview-step-num">3</span>
              <span>Jika barang mendesak untuk kegiatan KBM/praktik, hubungi petugas / admin sekolah secara langsung.</span>
            </div>
          </div>
        </div>
      </div>

      {{-- FAQ 3: Status Peminjaman Pending --}}
      <div class="faq-preview-item">
        <button type="button" class="faq-preview-q" aria-expanded="false">
          <span>Status pengajuan peminjaman masih tertahan di status "Menunggu Persetujuan"?</span>
          <span class="faq-preview-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
          </span>
        </button>
        <div class="faq-preview-a">
          <div class="faq-preview-steps">
            <div class="faq-preview-step">
              <span class="faq-preview-step-num">1</span>
              <span>Buka menu <strong>Riwayat Peminjaman</strong> di akun Siswa dan periksa Guru Pembimbing yang Anda pilih saat pengajuan.</span>
            </div>
            <div class="faq-preview-step">
              <span class="faq-preview-step-num">2</span>
              <span>Ingatkan atau hubungi Guru Pembimbing terkait untuk memeriksa link persetujuan / Dashboard Guru beliau.</span>
            </div>
            <div class="faq-preview-step">
              <span class="faq-preview-step-num">3</span>
              <span>Setelah Guru menyetujui, status akan berubah dan QR Code pengambilan barang siap ditampilkan.</span>
            </div>
          </div>
        </div>
      </div>

      {{-- FAQ 4: Guru tidak terima link persetujuan --}}
      <div class="faq-preview-item">
        <button type="button" class="faq-preview-q" aria-expanded="false">
          <span>Guru tidak menerima tautan persetujuan (approval) atau link tidak terbuka?</span>
          <span class="faq-preview-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
          </span>
        </button>
        <div class="faq-preview-a">
          <div class="faq-preview-steps">
            <div class="faq-preview-step">
              <span class="faq-preview-step-num">1</span>
              <span>Siswa dapat membuka detail pengajuan di akunnya, lalu menyalin tautan persetujuan (magic link) untuk dikirimkan ke Guru bersangkutan.</span>
            </div>
            <div class="faq-preview-step">
              <span class="faq-preview-step-num">2</span>
              <span>Alternatif: Guru dapat login ke SIPBAR dan membuka <strong>Dashboard Guru</strong> untuk melihat pengajuan yang menunggu approval.</span>
            </div>
            <div class="faq-preview-step">
              <span class="faq-preview-step-num">3</span>
              <span>Guru menekan tombol <strong>Setujui</strong> atau <strong>Tolak</strong> pada item permohonan siswa.</span>
            </div>
          </div>
        </div>
      </div>

      {{-- FAQ 5: Alur Pengembalian --}}
      <div class="faq-preview-item">
        <button type="button" class="faq-preview-q" aria-expanded="false">
          <span>Bagaimana alur dan tata cara pengembalian barang yang sedang dipinjam?</span>
          <span class="faq-preview-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
          </span>
        </button>
        <div class="faq-preview-a">
          <div class="faq-preview-steps">
            <div class="faq-preview-step">
              <span class="faq-preview-step-num">1</span>
              <span>Bawa barang fisik dalam kondisi lengkap dan bersih ke tempat pengembalian barang sekolah.</span>
            </div>
            <div class="faq-preview-step">
              <span class="faq-preview-step-num">2</span>
              <span>Buka menu <strong>Pengembalian</strong> pada akun Siswa dan tunjukkan nomor / kode transaksi pengembalian kepada petugas / admin.</span>
            </div>
            <div class="faq-preview-step">
              <span class="faq-preview-step-num">3</span>
              <span>Petugas / admin memverifikasi kondisi fisik barang dan menyelesaikan transaksi pengembalian di sistem.</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Link teks kecil biasa ke halaman penuh --}}
    <div class="faq-preview-more">
      <a href="{{ route('faq') }}" class="faq-preview-link">
        <span>Lihat semua pertanyaan</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
      </a>
    </div>
  </div>
</section>

{{-- ═══════════════ FOOTER ═══════════════ --}}
<footer class="footer" id="kontak">
  <div class="footer-inner">
    <div class="footer-grid">
      {{-- Brand Column --}}
      <div class="footer-brand">
        <div class="footer-logo-wrap">
          <div class="footer-logo-box">
            <img src="{{ $siteLogoLanding ?? (\App\Models\SiteSetting::get('site_logo_landing') ?: \App\Models\SiteSetting::get('site_logo', '/logossmkn1.png')) }}" alt="Logo {{ \App\Models\SiteSetting::get('site_subtitle', 'SMKN 1 Bangsri') }}" style="width:100%;height:100%;object-fit:contain;">
          </div>
          <div>
            <div class="footer-brand-name">{{ \App\Models\SiteSetting::get('footer_brand_name', 'SIPBAR') }}</div>
            <div class="footer-brand-sub">{{ \App\Models\SiteSetting::get('footer_brand_subtitle', 'SMKN 1 BANGSRI') }}</div>
          </div>
        </div>
        <p class="footer-desc">{{ \App\Models\SiteSetting::get('footer_description', 'Sistem peminjaman barang berbasis web yang lebih efektif, efisien, dan transparan untuk sekolah.') }}</p>
        <div class="footer-brand-extra">
          <a href="{{ route('faq') }}" class="footer-help-sublink">Pusat Bantuan</a>
        </div>
      </div>

      {{-- Bantuan Column --}}
      <div class="footer-col">
        <div class="footer-heading">Bantuan</div>
        <ul class="footer-list">
          <li><a href="{{ route('faq') }}">FAQ</a></li>
          <li><a href="#bantuan">Bantuan Singkat</a></li>
        </ul>
      </div>
    </div>

    <hr class="footer-divider">

    <div class="footer-bottom">
      <p class="footer-copy">{{ \App\Models\SiteSetting::get('footer_copyright', '© '.date('Y').' SIPBAR – Sistem Informasi Pengelolaan Barang. All rights reserved.') }}</p>
    </div>
  </div>
</footer>

<script>
(function(){
  // ─── Apply saved theme IMMEDIATELY (no flash) ───
  var saved = localStorage.getItem('sipbar-theme');
  var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  if(saved === 'dark' || (!saved && prefersDark)){
    document.documentElement.classList.add('dark');
  }
})();

document.addEventListener('DOMContentLoaded', function(){
  var html = document.documentElement;
  var nav = document.querySelector('.nav');
  var navHam = document.getElementById('navHamBtn');
  var navMob = document.getElementById('navMob');
  var themeBtns = document.querySelectorAll('.theme-toggle-btn');

  // ─── Scroll Effect for Navbar ───
  function handleNavScroll(){
    if(window.scrollY > 20){
      nav && nav.classList.add('scrolled');
    } else {
      nav && nav.classList.remove('scrolled');
    }
  }
  window.addEventListener('scroll', handleNavScroll, {passive: true});
  handleNavScroll(); // initialize

  // ─── Theme Transition & Toggle ───
  var isThemeTransitioning = false;

  function updateThemeBtnTitles(isDark){
    themeBtns.forEach(function(b){
      b.title = isDark ? 'Ganti ke Mode Terang' : 'Ganti ke Mode Gelap';
    });
  }

  function applyTheme(toDark, x, y) {
    if (isThemeTransitioning) return;
    function change() {
      html.classList.toggle('dark', toDark);
      localStorage.setItem('sipbar-theme', toDark ? 'dark' : 'light');
      updateThemeBtnTitles(toDark);
    }
    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduce) { change(); return; }
    if (!document.startViewTransition) {
      isThemeTransitioning = true;
      html.classList.add('theme-fade');
      change();
      setTimeout(function () {
        html.classList.remove('theme-fade');
        isThemeTransitioning = false;
      }, 400);
      return;
    }
    isThemeTransitioning = true;
    var r = Math.hypot(Math.max(x, window.innerWidth - x), Math.max(y, window.innerHeight - y));
    var t = document.startViewTransition(change);
    t.ready.then(function () {
      var anim = html.animate(
        { clipPath: ['circle(0px at ' + x + 'px ' + y + 'px)', 'circle(' + r + 'px at ' + x + 'px ' + y + 'px)'] },
        { duration: 500, easing: 'cubic-bezier(.4,0,.2,1)', pseudoElement: '::view-transition-new(root)' }
      );
      anim.onfinish = function () { isThemeTransitioning = false; };
      anim.oncancel = function () { isThemeTransitioning = false; };
    }).catch(function () {
      isThemeTransitioning = false;
    });
    t.finished.finally(function () {
      isThemeTransitioning = false;
    });
  }

  themeBtns.forEach(function(btn){
    btn.addEventListener('click', function(e){
      var b = e.currentTarget.getBoundingClientRect();
      var toDark = !html.classList.contains('dark');
      applyTheme(toDark, b.left + b.width / 2, b.top + b.height / 2);
    });
  });
  updateThemeBtnTitles(html.classList.contains('dark'));

  // ─── Mobile Hamburger Toggle ───
  if(navHam && navMob){
    navHam.addEventListener('click', function(e){
      e.stopPropagation();
      var isOpen = navMob.classList.toggle('open');
      navHam.classList.toggle('active', isOpen);
      navHam.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    // Close on outside click
    document.addEventListener('click', function(e){
      if(navMob.classList.contains('open') && !navMob.contains(e.target) && !navHam.contains(e.target)){
        closeNavMob();
      }
    });
  }

  // ─── Helper to close mobile menu ───
  window.closeNavMob = function(){
    if(navMob) navMob.classList.remove('open');
    if(navHam) {
      navHam.classList.remove('active');
      navHam.setAttribute('aria-expanded', 'false');
    }
  };

  // ─── Keyboard shortcut: Alt + D ───
  document.addEventListener('keydown', function(e){
    if(e.altKey && e.key.toLowerCase() === 'd') {
      var toDark = !html.classList.contains('dark');
      applyTheme(toDark, window.innerWidth / 2, window.innerHeight / 2);
    }
    if(e.key === 'Escape' && navMob && navMob.classList.contains('open')) closeNavMob();
  });

  // ─── Close mobile nav on resize ───
  window.addEventListener('resize', function(){
    if(window.innerWidth > 768 && navMob && navMob.classList.contains('open')){
      window.closeNavMob();
    }
  });

  // ─── Accordion Toggle for Section Bantuan ───
  document.querySelectorAll('.faq-preview-q').forEach(function(btn){
    btn.addEventListener('click', function(){
      var item = this.closest('.faq-preview-item');
      var wasOpen = item.classList.contains('faq-open');
      if(wasOpen){
        item.classList.remove('faq-open');
        this.setAttribute('aria-expanded', 'false');
      } else {
        item.classList.add('faq-open');
        this.setAttribute('aria-expanded', 'true');
      }
    });
  });

  // ─── Active nav link on scroll (Scrollspy including #bantuan) ───
  var sections = document.querySelectorAll('section[id], footer[id]');
  var navLinks = document.querySelectorAll('.nav-links a');
  var observer = new IntersectionObserver(function(entries){
    entries.forEach(function(entry){
      if(entry.isIntersecting){
        navLinks.forEach(function(a){ a.classList.remove('active'); });
        var active = document.querySelector('.nav-links a[href="#'+entry.target.id+'"]');
        if(active) active.classList.add('active');
      }
    });
  }, {rootMargin:'-25% 0px -55% 0px'});
  sections.forEach(function(s){ observer.observe(s); });
});
</script>
</body>
</html>