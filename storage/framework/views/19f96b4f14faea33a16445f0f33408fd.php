
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIPBAR – Sistem Informasi Pengelolaan Barang</title>

<script>
  (function(){
    var t = localStorage.getItem('sipbar-theme');
    var d = window.matchMedia('(prefers-color-scheme: dark)').matches;
    if(t === 'dark' || (!t && d)) document.documentElement.classList.add('dark');
  })();
</script>
<?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css','resources/js/app.js']); ?>
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

/* ─── DARK MODE TOKENS ─── */
html.dark{
  --primary:#3b82f6;--primary-hover:#60a5fa;--primary-light:#60a5fa;
  --accent:#f59e0b;--accent-hover:#fb923c;
  --bg:#0b1328;
  --bg2:#101a33;
  --bg3:#162447;
  --surface:#101a33;
  --border:#1e3158;
  --border2:#192949;
  --card:rgba(18,28,52,0.85);
  --text:#f0f6ff;
  --text2:#dce9ff;
  --muted:#94a3b8;
  --subtle:#94a3b8;
  --nav-bg:rgba(10,17,34,.95);
  --nav-border:rgba(30,49,88,.8);
  --shadow:0 4px 16px rgba(0,0,0,.4);
  --shadow-lg:0 12px 36px rgba(0,0,0,.5);

  /* Unified Continuous Blue Gradient for all sections */
  --dark-gradient-blue: linear-gradient(180deg, #0d1a38 0%, #070e1e 100%);
  --dark-bg-fitur: linear-gradient(180deg, #0d1a38 0%, #0b152d 100%);
  --dark-bg-stats: linear-gradient(180deg, #0b152d 0%, #091124 100%);
  --dark-bg-tentang: linear-gradient(180deg, #091124 0%, #070d1c 100%);
  --dark-bg-footer: linear-gradient(180deg, #070d1c 0%, #050a14 100%);

  --dark-card-bg: rgba(18, 29, 54, 0.75);
  --dark-card-border: rgba(59, 130, 246, 0.2);
}

/* ─── NAVBAR ─── */
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
  width:54px; height:54px; border-radius:50%;
  background:#ffffff;
  display:flex; align-items:center; justify-content:center;
  flex-shrink:0;
  box-shadow:0 2px 10px rgba(0,0,0,.10), 0 0 0 1.5px rgba(0,0,0,.06);
  transition:transform .25s ease, box-shadow .25s ease;
  overflow:hidden; padding:4px;
}
.nav-brand:hover .nav-logo-wrap{transform:scale(1.04); box-shadow:0 4px 16px rgba(29,78,216,.18)}
.nav-brand-img{
  width:100%; height:100%;
  object-fit:contain;
}
html.dark .nav-logo-wrap{
  background:rgba(255,255,255,.92);
  box-shadow:0 2px 10px rgba(0,0,0,.4), 0 0 0 1.5px rgba(255,255,255,.12);
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
  box-shadow:0 2px 6px rgba(0,0,0,0.03);
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
.nav-ham.active .ham-line:nth-child(1){transform:translateY(7.2px) rotate(45deg)}
.nav-ham.active .ham-line:nth-child(2){opacity:0;transform:scaleX(0)}
.nav-ham.active .ham-line:nth-child(3){transform:translateY(-7.2px) rotate(-45deg)}

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
  animation:slideDownNav .25s cubic-bezier(0.4,0,0.2,1);
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
.nav-mob-theme-row{
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:11px 16px;
  border-radius:10px;
  background:var(--bg3);
  border:1px solid var(--border);
  min-height:44px;
}
.nav-mob-theme-info{
  display:flex;
  align-items:center;
  gap:10px;
  font-size:13px;
  font-weight:700;
  color:var(--text);
}
.nav-mob-theme-btn{
  display:inline-flex;
  align-items:center;
  gap:6px;
  padding:6px 12px;
  border-radius:8px;
  border:1px solid var(--border);
  background:var(--card);
  color:var(--text);
  font-size:12px;
  font-weight:700;
  cursor:pointer;
  transition:all .2s;
}
.nav-mob-login{
  display:flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  text-align:center;
  padding:12px;
  min-height:46px;
  border-radius:12px;
  font-family:'Plus Jakarta Sans',sans-serif;
  font-size:14px;
  font-weight:700;
  text-decoration:none;
  background:linear-gradient(135deg,#1d4ed8 0%,#2563eb 100%);
  color:#fff;
  box-shadow:0 4px 14px rgba(29,78,216,0.25);
  transition:all .2s ease;
}
.icon-sun{display:none}
.icon-moon{display:block}
html.dark .icon-sun{display:block}
html.dark .icon-moon{display:none}
.mob-t-sun{display:none}
.mob-t-moon{display:inline-flex;align-items:center;gap:4px}
html.dark .mob-t-sun{display:inline-flex;align-items:center;gap:4px}
html.dark .mob-t-moon{display:none}
</style>
<style>
/* ─── HERO ─── */
.hero{position:relative;background:linear-gradient(135deg,rgba(29,78,216,0.85),rgba(37,99,235,0.80)),url('/build/assets/smkTop.png');background-size:cover;background-position:center;overflow:hidden;padding:100px 24px 70px;min-height:550px;display:flex;align-items:center}
.hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 70% 50% at 50% 40%,rgba(59,130,246,.1) 0%,transparent 70%)}
.hero-inner{max-width:1200px;margin:0 auto;display:flex;flex-direction:column;align-items:center;text-align:center;position:relative;z-index:2}
.hero-badge{display:inline-flex;align-items:center;gap:8px;padding:6px 16px;background:#1d4ed8;border:1px solid rgba(255,255,255,.3);border-radius:999px;font-size:11px;font-weight:700;color:#ffffff;margin-bottom:20px;box-shadow:0 4px 16px rgba(29,78,216,.5);letter-spacing:.02em;text-transform:uppercase}
.hero-badge-pulse{width:6px;height:6px;background:#ffffff;border-radius:50%;opacity:.8}
.hero-h1{font-size:48px;font-weight:800;line-height:1.15;letter-spacing:-.02em;color:#fff;margin-bottom:20px;font-family:'Inter',sans-serif;text-shadow:0 2px 16px rgba(0,0,0,.3)}
.hero-h1 em{font-style:normal;color:#fff}
.hero-p{font-size:17px;color:#f8fafc;line-height:1.65;margin-bottom:32px;max-width:580px;margin-left:auto;margin-right:auto;text-shadow:0 1px 2px rgba(0,0,0,.2);font-weight:400}
.hero-btns{display:flex;gap:16px;flex-wrap:wrap;justify-content:center;margin-bottom:40px}
.hero-btn-main{display:inline-flex;align-items:center;gap:10px;padding:13px 30px;background:rgba(255,255,255,.15);color:#ffffff;font-size:15px;font-weight:700;border-radius:12px;text-decoration:none;border:2px solid rgba(255,255,255,.3);backdrop-filter:blur(8px);transition:all .2s}
.hero-btn-main:hover{background:rgba(255,255,255,.25);border-color:rgba(255,255,255,.5);transform:translateY(-2px)}
.hero-btn-alt{display:inline-flex;align-items:center;gap:10px;padding:13px 28px;background:rgba(255,255,255,.15);color:#fff;font-size:15px;font-weight:600;border-radius:12px;border:2px solid rgba(255,255,255,.3);text-decoration:none;transition:all .2s}
.hero-btn-alt:hover{background:rgba(255,255,255,.25);transform:translateY(-2px)}
.hero-trust{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
.trust-item{display:flex;flex-direction:column;align-items:center;text-align:center;gap:8px}
.trust-icon{width:44px;height:44px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center}
.trust-label{font-size:12px;font-weight:700;color:#fff}
.trust-sub{font-size:11px;color:#e2e8f0;line-height:1.4}
</style>
<style>
/* ─── MOCKUP ─── */
.hero-mockup{position:relative;z-index:2}
.mockup-glow{position:absolute;inset:-30px;background:radial-gradient(ellipse at center,rgba(37,99,235,.4),transparent 70%);border-radius:50%;z-index:0}
.mockup-card{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.15);border-radius:20px;overflow:hidden;backdrop-filter:blur(12px);box-shadow:0 32px 80px rgba(0,0,0,.4),0 0 0 1px rgba(255,255,255,.08);position:relative;z-index:1}
.mc-header{background:rgba(255,255,255,.08);border-bottom:1px solid rgba(255,255,255,.1);padding:10px 14px;display:flex;align-items:center;justify-content:space-between}
.mc-dots{display:flex;gap:5px}
.mc-dot{width:9px;height:9px;border-radius:50%}
.mc-body{display:flex;min-height:300px}
.mc-sidebar{width:90px;background:rgba(29,78,216,.7);padding:10px 6px;display:flex;flex-direction:column;gap:2px}
.mc-menu{padding:7px 8px;border-radius:6px;font-size:9px;color:rgba(255,255,255,.5);display:flex;align-items:center;gap:6px;cursor:default}
.mc-menu.act{background:rgba(255,255,255,.25);color:#fff;font-weight:700}
.mc-menu-dot{width:6px;height:6px;background:currentColor;border-radius:1.5px;flex-shrink:0;opacity:.6}
.mc-menu.act .mc-menu-dot{opacity:1}
.mc-content{flex:1;padding:12px;background:rgba(248,250,252,.04)}
.mc-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:6px;margin-bottom:10px}
.mc-stat{background:rgba(255,255,255,.08);border-radius:8px;padding:8px;border:1px solid rgba(255,255,255,.06)}
.mc-stat-label{font-size:8px;color:rgba(255,255,255,.45);margin-bottom:3px}
.mc-stat-val{font-size:13px;font-weight:800;color:#fff}
.mc-stat-chg{font-size:8px;margin-top:2px}
.mc-chart{background:rgba(255,255,255,.06);border-radius:8px;padding:8px;border:1px solid rgba(255,255,255,.06);margin-bottom:10px}
.mc-chart-label{font-size:8px;color:rgba(255,255,255,.45);margin-bottom:6px}
.mc-items{background:rgba(255,255,255,.06);border-radius:8px;padding:8px;border:1px solid rgba(255,255,255,.06)}
.mc-items-label{font-size:8px;color:rgba(255,255,255,.45);margin-bottom:6px;font-weight:700}
.mc-row{display:flex;align-items:center;justify-content:space-between;padding:4px 0;border-bottom:1px solid rgba(255,255,255,.04)}
.mc-row:last-child{border-bottom:none}
.mc-row-left{display:flex;align-items:center;gap:6px}
.mc-avatar{width:18px;height:18px;background:rgba(96,165,250,.6);border-radius:4px}
.mc-name{font-size:9px;color:rgba(255,255,255,.7);font-weight:600}
.mc-cat{font-size:8px;color:rgba(255,255,255,.35)}
.mc-pill{font-size:8px;padding:2px 6px;border-radius:999px;font-weight:600}
.pill-green{background:rgba(52,211,153,.15);color:#34d399}
.pill-red{background:rgba(248,113,113,.15);color:#f87171}
</style>
<style>
/* ─── SECTIONS ─── */
.section{padding:80px 24px}
.section-inner{max-width:1200px;margin:0 auto}
.section-eyebrow{display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:700;color:var(--blue);letter-spacing:.08em;text-transform:uppercase;margin-bottom:12px}
.section-eyebrow-dot{width:6px;height:6px;background:var(--blue);border-radius:50%}
.section-h2{font-size:36px;font-weight:800;line-height:1.2;letter-spacing:-.01em;color:var(--text);margin-bottom:12px}
.section-h2 em{font-style:normal;color:var(--blue)}
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
/* ─── FEATURES (BENTO GRID) ─── */
.feat-bg{background:linear-gradient(180deg,#f8fafc 0%,#ffffff 100%);position:relative}
.feat-bg::before{content:'';position:absolute;top:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,#e2e8f0 50%,transparent)}
.feat-bento{display:grid;grid-template-columns:repeat(12,1fr);gap:24px}
.feat-bento-card{background:#ffffff;border:1.5px solid #e5e7eb;border-radius:20px;padding:32px 28px;text-align:left;transition:all .3s cubic-bezier(0.4,0,0.2,1);position:relative;overflow:hidden;cursor:pointer;text-decoration:none;display:flex;flex-direction:column;justify-content:space-between}
.feat-bento-card:hover{transform:translateY(-5px);border-color:#2563eb;box-shadow:0 16px 36px rgba(37,99,235,.08)}
.feat-card-hero{grid-column:span 7;background:linear-gradient(135deg,#ffffff 0%,#f0f7ff 100%);border-color:#dbeafe}
.feat-card-side{grid-column:span 5}
.feat-card-sub{grid-column:span 4}

.feat-top-meta{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px}
.feat-index{font-family:'Plus Jakarta Sans',sans-serif;font-size:12px;font-weight:800;letter-spacing:.05em;color:#2563eb;background:rgba(37,99,235,.08);padding:4px 10px;border-radius:999px}
.feat-badge-pill{font-size:11px;font-weight:700;padding:4px 10px;border-radius:999px;background:#eff6ff;color:#1d4ed8;border:1px solid rgba(29,78,216,.15)}
.feat-icon-duo{width:48px;height:48px;border-radius:14px;background:#eff6ff;border:1px solid #dbeafe;display:flex;align-items:center;justify-content:center;color:#1d4ed8;transition:all .3s}
.feat-bento-card:hover .feat-icon-duo{background:#1d4ed8;color:#fff;border-color:#1d4ed8;transform:scale(1.05)}

.feat-title{font-family:'Plus Jakarta Sans',sans-serif;font-size:18px;font-weight:800;color:#0f172a;margin-bottom:10px;line-height:1.3;letter-spacing:-.02em;transition:color .2s}
.feat-bento-card:hover .feat-title{color:#1d4ed8}
.feat-card-hero .feat-title{font-size:22px}
.feat-summary{font-size:14px;color:#64748b;line-height:1.65;font-weight:400;margin-bottom:20px}

.feat-workflow-flow{display:flex;align-items:center;gap:8px;background:rgba(255,255,255,.9);border:1px solid #dbeafe;border-radius:14px;padding:12px 14px;margin-top:16px;margin-bottom:20px}
.feat-flow-step{display:flex;align-items:center;gap:6px;font-size:12px;font-weight:700;color:#1e293b}
.feat-flow-dot{width:20px;height:20px;border-radius:6px;background:#1d4ed8;color:#fff;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:800}
.feat-flow-arrow{color:#94a3b8}

.feat-action-link{display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:700;color:#1d4ed8;margin-top:auto;transition:gap .2s}
.feat-bento-card:hover .feat-action-link{gap:10px;color:#1e40af}

/* ─── STATS / DATA INVENTARIS ─── */
.stats-bg{background:#ffffff;position:relative;overflow:hidden;padding:70px 24px}
.stats-bg::before{content:'';position:absolute;top:0;left:0;width:6px;height:100%;background:#f59e0b}
.stats-inner{max-width:1200px;margin:0 auto;position:relative;z-index:1;display:grid;grid-template-columns:1fr 1.1fr;gap:50px;align-items:center}
.stats-eyebrow{display:inline-flex;align-items:center;gap:8px;font-size:11px;font-weight:700;color:#1d4ed8;letter-spacing:.08em;text-transform:uppercase;margin-bottom:12px;background:rgba(29,78,216,.08);padding:6px 16px;border-radius:999px;border:1px solid rgba(29,78,216,.15)}
.stats-eyebrow-pulse{width:6px;height:6px;border-radius:50%;background:#f59e0b}
.stats-h2{font-family:'Plus Jakarta Sans',sans-serif;font-size:34px;font-weight:800;color:#0f172a;line-height:1.2;margin-bottom:14px;letter-spacing:-.01em}
.stats-h2 em{font-style:normal;color:#f59e0b}
.stats-p{font-size:16px;color:#4b5563;line-height:1.65;margin-bottom:24px;font-weight:400}
.stats-cta-btn{display:inline-flex;align-items:center;gap:8px;padding:12px 24px;border-radius:12px;background:#1d4ed8;color:#ffffff;font-size:14px;font-weight:700;text-decoration:none;transition:all .2s;box-shadow:0 4px 12px rgba(29,78,216,.25)}
.stats-cta-btn:hover{transform:translateY(-2px);background:#1e40af;box-shadow:0 6px 16px rgba(29,78,216,.35)}
.stats-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:16px}

/* Solid Rectangle Cards */
.stat-block{background:#ffffff;border:1px solid #e5e7eb;border-radius:16px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,.04);transition:all .2s ease;position:relative;overflow:hidden}
.stat-block:hover{transform:translateY(-3px);box-shadow:0 8px 20px rgba(0,0,0,.08);border-color:#cbd5e1}
.stat-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px}
.stat-icon-b{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.stat-trend{font-size:11px;font-weight:700;padding:4px 10px;border-radius:999px}
.stat-num-b{font-family:'Plus Jakarta Sans',sans-serif;font-size:32px;font-weight:800;color:#0f172a;line-height:1;letter-spacing:-.02em}
.stat-lbl-b{font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:700;color:#1e293b;margin-top:8px;letter-spacing:-.01em}
.stat-sub-b{font-size:12px;font-weight:500;color:#64748b;margin-top:3px}
</style>
<style>
/* ─── ABOUT SECTION REDESIGNED ─── */
.about-grid-redesigned{display:grid;grid-template-columns:1fr 1px 1.05fr;gap:0;align-items:stretch;position:relative}
.about-content-redesigned{position:relative;padding-right:48px;display:flex;flex-direction:column}

/* Decorative large number */
.decorative-number{position:absolute;top:-60px;left:-20px;font-family:'Plus Jakarta Sans',sans-serif;font-size:180px;font-weight:900;color:rgba(29,78,216,.04);line-height:1;z-index:0;pointer-events:none}

/* Eyebrow label */
.about-eyebrow-redesigned{display:inline-flex;align-items:center;gap:8px;font-size:11px;font-weight:700;color:#1d4ed8;letter-spacing:.12em;text-transform:uppercase;margin-bottom:16px;position:relative;z-index:1}
.eyebrow-dot{width:6px;height:6px;background:#1d4ed8;border-radius:50%}

/* Headline with hierarchy */
.about-headline-redesigned{font-family:'Plus Jakarta Sans',sans-serif;font-size:36px;font-weight:800;line-height:1.2;color:#0f172a;margin-bottom:16px;position:relative;z-index:1;letter-spacing:-.01em}
.headline-accent{color:#1d4ed8;font-style:normal}

/* Description */
.about-desc-redesigned{font-size:16px;color:#64748b;line-height:1.75;margin-bottom:40px;position:relative;z-index:1;max-width:520px}

/* Feature Cards Grid */
.feature-cards-redesigned{display:grid;grid-template-columns:repeat(2,1fr);gap:16px;position:relative;z-index:1}

/* Feature Card */
.feature-card{background:#ffffff;border:1px solid #e5e7eb;border-radius:14px;padding:20px;transition:all .3s cubic-bezier(0.4,0,0.2,1);display:flex;flex-direction:column;gap:10px;position:relative;overflow:hidden}
.feature-card::before{content:'';position:absolute;top:0;left:0;width:3px;height:100%;background:#e5e7eb;transition:background .3s}
.feature-card:hover{transform:translateY(-4px);border-color:#1d4ed8;box-shadow:0 12px 32px rgba(29,78,216,.12)}
.feature-card:hover::before{background:#1d4ed8}

/* Primary Card (highlighted) */
.feature-card-primary{background:linear-gradient(135deg,#1d4ed8 0%,#2563eb 100%);border-color:#1d4ed8}
.feature-card-primary::before{background:rgba(255,255,255,.3)}
.feature-card-primary:hover{box-shadow:0 16px 40px rgba(29,78,216,.25)}
.feature-card-primary .card-num{color:#fff}
.feature-card-primary .card-title{color:#fff}
.feature-card-primary .card-desc{color:rgba(255,255,255,.9)}
.feature-card-primary .card-icon{background:rgba(255,255,255,.2);color:#fff}

/* Card Header with icon and number */
.card-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:4px}
.card-icon{width:36px;height:36px;background:#eff6ff;border:1px solid #dbeafe;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#1d4ed8;flex-shrink:0;transition:all .3s}
.feature-card:hover .card-icon{background:#1d4ed8;color:#fff;border-color:#1d4ed8}
.card-num{font-family:'Plus Jakarta Sans',sans-serif;font-size:12px;font-weight:800;color:#1d4ed8;letter-spacing:.05em}

/* Card Content */
.card-title{font-family:'Plus Jakarta Sans',sans-serif;font-size:15px;font-weight:700;color:#0f172a;line-height:1.3;margin:0}
.card-desc{font-size:13px;color:#64748b;line-height:1.55;margin:0;font-weight:400}

/* Vertical Divider */
.vertical-divider{width:1px;background:linear-gradient(to bottom,transparent,#e2e8f0 20%,#e2e8f0 80%,transparent);position:relative}

/* Visual Column */
.about-visual-redesigned{padding-left:48px;display:flex;align-items:center}
.school-photo-frame-redesigned{background:linear-gradient(145deg,#ffffff 0%,#f8fafc 100%);border:1.5px solid #e2e8f0;border-radius:24px;padding:20px;box-shadow:0 24px 64px rgba(29,78,216,.1);position:relative;overflow:hidden;width:100%}
.photo-wrapper{position:relative;border-radius:20px;overflow:hidden;aspect-ratio:4/3}
.school-photo{width:100%;height:100%;object-fit:cover;transition:transform .5s cubic-bezier(0.4,0,0.2,1)}
.photo-wrapper:hover .school-photo{transform:scale(1.05)}
.photo-gradient{position:absolute;inset:0;background:linear-gradient(to top,rgba(15,23,42,.95) 0%,rgba(15,23,42,.5) 35%,transparent 65%);pointer-events:none}

/* Glassmorphism Badge */
.glass-badge{position:absolute;top:16px;right:16px;background:rgba(255,255,255,.85);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-radius:14px;padding:10px 16px;border:1px solid rgba(255,255,255,.4);box-shadow:0 8px 24px rgba(0,0,0,.12);display:flex;align-items:center;gap:10px;transition:all .3s ease}
.photo-wrapper:hover .glass-badge{background:rgba(255,255,255,.95);box-shadow:0 12px 32px rgba(0,0,0,.18)}
.badge-icon{width:28px;height:28px;background:linear-gradient(135deg,#1d4ed8 0%,#2563eb 100%);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;flex-shrink:0;box-shadow:0 4px 12px rgba(29,78,216,.3)}
.badge-content{display:flex;flex-direction:column;gap:2px}
.badge-year{font-size:10px;font-weight:800;color:#1d4ed8;letter-spacing:.08em;text-transform:uppercase}
.badge-name{font-size:12px;font-weight:700;color:#0f172a;line-height:1.2}

/* Caption */
.photo-caption{position:absolute;bottom:0;left:0;right:0;padding:20px 24px;color:#fff;z-index:1}
.caption-label{font-family:'Plus Jakarta Sans',sans-serif;font-size:16px;font-weight:700;margin-bottom:6px;line-height:1.3}
.caption-sub{font-size:13px;color:rgba(255,255,255,.9);line-height:1.5;max-width:90%}
/* ─── FOOTER ─── */
.footer{background:#ffffff;color:#475569;padding:60px 24px 28px;border-top:1px solid #e2e8f0}
.footer-inner{max-width:1200px;margin:0 auto}
.footer-grid{display:grid;grid-template-columns:2fr 1fr 1.2fr 1.2fr;gap:48px;margin-bottom:48px}
.footer-brand-name{font-size:15px;font-weight:800;color:#0f172a;letter-spacing:-.01em}
.footer-brand-sub{font-size:11px;color:#64748b;font-weight:500}
.footer-desc{font-size:13px;line-height:1.65;margin-bottom:18px;color:#475569;max-width:280px;font-weight:400}
.footer-logo-wrap{display:flex;align-items:center;gap:10px;margin-bottom:14px}
.footer-logo-box{width:34px;height:34px;background:#1d4ed8;border-radius:10px;display:flex;align-items:center;justify-content:center}
.social-row{display:flex;gap:8px}
.social-btn{width:32px;height:32px;background:#f1f5f9;border-radius:8px;display:flex;align-items:center;justify-content:center;transition:all .2s;color:#475569;border:1px solid #e2e8f0}
.social-btn:hover{background:#1d4ed8;color:#fff;border-color:#1d4ed8}
.footer-heading{font-size:13px;font-weight:700;color:#0f172a;margin-bottom:16px;letter-spacing:.01em}
.footer-list{list-style:none;display:flex;flex-direction:column;gap:10px}
.footer-list a{font-size:13px;color:#4b5563;text-decoration:none;transition:color .15s;display:flex;align-items:center;gap:6px;font-weight:400}
.footer-list a:hover{color:#1d4ed8}
.footer-divider{border:none;border-top:1px solid #e5e7eb;margin-bottom:20px}
.footer-copy{text-align:center;font-size:12px;color:#6b7280;font-weight:400}
/* ─── RESPONSIVE ─── */
@media(max-width:1024px){
  .hero-inner,.stats-inner{grid-template-columns:1fr}
  .hero{padding-bottom:50px}
  .hero-trust{grid-template-columns:repeat(2,1fr)}
  .cat-grid{grid-template-columns:repeat(3,1fr)}
  .feat-bento{grid-template-columns:repeat(6,1fr)}
  .feat-card-hero{grid-column:span 6}
  .feat-card-side{grid-column:span 6}
  .feat-card-sub{grid-column:span 3}
  .about-grid-modern{grid-template-columns:1fr;gap:40px}
  .footer-grid{grid-template-columns:repeat(2,1fr)}
  .section{padding:60px 24px}
}
@media(max-width:768px){
  .nav-inner{height:68px;padding:0 16px;gap:12px}
  .nav-links,.nav-actions{display:none !important}
  .nav-mobile-ctrls{display:flex !important}
  .nav-ham{display:flex !important}
  .nav-brand{gap:10px}
  .nav-logo-wrap{width:46px;height:46px}
  .nav-brand-title{font-size:18px}
  .nav-brand-subtitle{font-size:9.5px}
  .hero-h1{font-size:34px}
  .section-h2{font-size:26px}
  .stats-h2{font-size:26px}
  .cat-grid{grid-template-columns:repeat(2,1fr)}
  .feat-bento{grid-template-columns:1fr}
  .feat-card-hero,.feat-card-side,.feat-card-sub{grid-column:span 1}
  .stats-grid{grid-template-columns:repeat(2,1fr)}
  
  /* About section responsive */
  .about-grid-redesigned{grid-template-columns:1fr;gap:40px}
  .vertical-divider{display:none}
  .about-content-redesigned{padding-right:0}
  .about-visual-redesigned{padding-left:0}
  .decorative-number{font-size:120px;top:-40px;left:-10px}
  .about-headline-redesigned{font-size:28px}
  .feature-cards-redesigned{grid-template-columns:1fr;gap:12px}
  .glass-badge{top:12px;right:12px;padding:8px 12px}
  .badge-icon{width:24px;height:24px}
  .caption-label{font-size:14px}
  .caption-sub{font-size:12px}
}
@media(max-width:480px){
  .nav-inner{height:64px;padding:0 14px}
  .nav-brand-title{font-size:17px}
  .nav-brand-subtitle{font-size:9px}
  .cat-grid{grid-template-columns:1fr}
  .hero-h1{font-size:30px}
  .section-h2{font-size:22px}
  .stats-h2{font-size:22px}
  .stats-grid{grid-template-columns:1fr}
  .footer-grid{grid-template-columns:1fr}
  .section{padding:48px 16px}
  .feat-workflow-flow{flex-direction:column;align-items:flex-start}
  
  /* About section mobile */
  .decorative-number{font-size:100px;top:-30px;left:-5px}
  .about-headline-redesigned{font-size:24px}
  .card-icon{width:32px;height:32px}
  .glass-badge{top:10px;right:10px;padding:6px 10px}
  .badge-icon{width:22px;height:22px}
  .badge-year{font-size:9px}
  .badge-name{font-size:11px}
  .caption-label{font-size:13px}
  .caption-sub{font-size:11px}
}
/* ════════════════════════════════════════
   DARK MODE OVERRIDES — SEMUA ELEMEN
════════════════════════════════════════ */

/* ── NAVBAR (DARK MODE) ── */
html.dark .nav {
  background: rgba(11, 19, 40, 0.85) !important;
  border-bottom: 1px solid rgba(59, 130, 246, 0.18) !important;
  backdrop-filter: blur(16px) !important;
}
html.dark .nav.scrolled {
  background: rgba(11, 19, 40, 0.96) !important;
  border-bottom-color: rgba(59, 130, 246, 0.28) !important;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5) !important;
}
html.dark .nav-brand-title { color: #60a5fa !important }
html.dark .nav-brand-subtitle { color: #94a3b8 !important }
html.dark .nav-links {
  background: rgba(18, 29, 54, 0.75) !important;
  border-color: rgba(59, 130, 246, 0.2) !important;
}
html.dark .nav-links a { color: #94a3b8 !important }
html.dark .nav-links a:hover {
  background: rgba(59, 130, 246, 0.15) !important;
  color: #f0f6ff !important;
}
html.dark .nav-links a.active {
  background: rgba(59, 130, 246, 0.25) !important;
  color: #60a5fa !important;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3) !important;
}
html.dark .theme-toggle {
  background: rgba(18, 29, 54, 0.8) !important;
  border-color: rgba(59, 130, 246, 0.25) !important;
  color: #94a3b8 !important;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2) !important;
}
html.dark .theme-toggle:hover {
  background: rgba(59, 130, 246, 0.2) !important;
  border-color: #60a5fa !important;
  color: #60a5fa !important;
  box-shadow: 0 6px 18px rgba(59, 130, 246, 0.25) !important;
}
html.dark .btn-nav-cta {
  background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%) !important;
  box-shadow: 0 4px 16px rgba(37, 99, 235, 0.4) !important;
}
html.dark .btn-nav-cta:hover {
  box-shadow: 0 8px 24px rgba(37, 99, 235, 0.55) !important;
}
html.dark .nav-ham {
  border-color: rgba(59, 130, 246, 0.25) !important;
}
html.dark .ham-line {
  background: #94a3b8 !important;
}
html.dark .nav-mobile {
  background: rgba(11, 19, 40, 0.98) !important;
  border-top-color: rgba(59, 130, 246, 0.2) !important;
  box-shadow: 0 16px 32px rgba(0, 0, 0, 0.6) !important;
}
html.dark .nav-mobile-links a { color: #94a3b8 !important }
html.dark .nav-mobile-links a:hover {
  background: rgba(59, 130, 246, 0.15) !important;
  color: #f0f6ff !important;
}
html.dark .nav-mob-login {
  background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%) !important;
  box-shadow: 0 4px 16px rgba(37, 99, 235, 0.35) !important;
}

/* ── SECTION COMMON & SEAMLESS GRADIENTS ── */
html.dark .section { background: var(--dark-gradient-blue) !important }
html.dark #fitur,
html.dark .feat-bg { background: var(--dark-bg-fitur) !important }
html.dark .feat-bg::before { background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.2) 50%, transparent) !important }

html.dark #data-inventaris,
html.dark .stats-bg { background: var(--dark-bg-stats) !important }

html.dark #tentang { background: var(--dark-bg-tentang) !important }

html.dark .section-eyebrow { color: #60a5fa !important }
html.dark .section-eyebrow-dot { background: #60a5fa !important }
html.dark .section-h2 { color: #f0f6ff !important }
html.dark .section-h2 em { color: #60a5fa !important }
html.dark .section-lead { color: #94a3b8 !important }

/* ── KATEGORI ── */
html.dark .cat-card {
  background: var(--dark-card-bg) !important;
  border-color: var(--dark-card-border) !important;
  backdrop-filter: blur(10px) !important;
  box-shadow: 0 4px 16px rgba(0,0,0,.3) !important;
}
html.dark .cat-card:hover {
  border-color: #60a5fa !important;
  box-shadow: 0 12px 32px rgba(96,165,250,.2) !important;
}
html.dark .cat-icon-wrap { background: rgba(59, 130, 246, 0.15) !important }
html.dark .cat-icon-wrap svg { color: #60a5fa !important }
html.dark .cat-card:hover .cat-icon-wrap { background: #3b82f6 !important }
html.dark .cat-card:hover .cat-icon-wrap svg { color: #fff !important }
html.dark .cat-name { color: #f0f6ff !important }
html.dark .cat-desc { color: #94a3b8 !important }
html.dark .cat-link { color: #60a5fa !important }

/* ── FITUR (DARK MODE) ── */
html.dark .feat-bento-card{
  background: var(--dark-card-bg) !important;
  border-color: var(--dark-card-border) !important;
  backdrop-filter: blur(12px) !important;
  box-shadow: 0 4px 16px rgba(0,0,0,.3) !important;
}
html.dark .feat-bento-card:hover{
  border-color: #3b82f6 !important;
  box-shadow: 0 16px 36px rgba(0,0,0,.5), 0 0 20px rgba(59, 130, 246, 0.2) !important;
}
html.dark .feat-card-hero{
  background: linear-gradient(135deg, rgba(26, 42, 77, 0.85) 0%, rgba(18, 29, 54, 0.8) 100%) !important;
  border-color: rgba(59, 130, 246, 0.35) !important;
}
html.dark .feat-index{
  color: #60a5fa !important;
  background: rgba(59, 130, 246, 0.15) !important;
}
html.dark .feat-badge-pill{
  background: rgba(59, 130, 246, 0.15) !important;
  color: #93c5fd !important;
  border-color: rgba(59, 130, 246, 0.25) !important;
}
html.dark .feat-icon-duo{
  background: rgba(59, 130, 246, 0.12) !important;
  border-color: rgba(59, 130, 246, 0.25) !important;
  color: #60a5fa !important;
}
html.dark .feat-bento-card:hover .feat-icon-duo{
  background: #3b82f6 !important;
  color: #ffffff !important;
  border-color: #3b82f6 !important;
}
html.dark .feat-title{ color: #f0f6ff !important }
html.dark .feat-bento-card:hover .feat-title{ color: #60a5fa !important }
html.dark .feat-summary{ color: #94a3b8 !important }
html.dark .feat-workflow-flow{
  background: rgba(14, 23, 44, 0.8) !important;
  border-color: rgba(59, 130, 246, 0.25) !important;
}
html.dark .feat-flow-step{ color: #dce9ff !important }
html.dark .feat-action-link{ color: #60a5fa !important }
html.dark .feat-bento-card:hover .feat-action-link{ color: #93c5fd !important }

/* ── STATS SECTION ── */
html.dark .stats-eyebrow { background: rgba(59,130,246,.15) !important; color: #60a5fa !important; border-color: rgba(59,130,246,.3) !important }
html.dark .stats-eyebrow-pulse { background: #fb923c !important }
html.dark .stats-h2 { color: #f0f6ff !important }
html.dark .stats-h2 em { color: #fb923c !important }
html.dark .stats-p { color: #94a3b8 !important }
html.dark .stats-cta-btn { background: linear-gradient(135deg, #2563eb, #3b82f6) !important; color: #ffffff !important; box-shadow: 0 4px 14px rgba(37,99,235,.4) !important }
html.dark .stats-cta-btn:hover { background: linear-gradient(135deg, #1d4ed8, #2563eb) !important; box-shadow: 0 6px 20px rgba(37,99,235,.5) !important }
html.dark .stat-block {
  background: var(--dark-card-bg) !important;
  border-color: var(--dark-card-border) !important;
  backdrop-filter: blur(12px) !important;
  box-shadow: 0 4px 16px rgba(0,0,0,.3) !important;
}
html.dark .stat-block:hover { 
  border-color: #3b82f6 !important;
  box-shadow: 0 8px 24px rgba(59,130,246,.25) !important;
}
html.dark .stat-icon-b {
  filter: brightness(1.1) !important;
}
html.dark .stat-num-b { color: #f0f6ff !important }
html.dark .stat-lbl-b { color: #dce9ff !important }
html.dark .stat-sub-b { color: #94a3b8 !important }
html.dark .stat-block:nth-child(1) .stat-trend { background: rgba(59, 130, 246, 0.2) !important; color: #93c5fd !important }
html.dark .stat-block:nth-child(2) .stat-trend { background: rgba(14, 165, 233, 0.2) !important; color: #7dd3fc !important }
html.dark .stat-block:nth-child(3) .stat-trend { background: rgba(168, 85, 247, 0.2) !important; color: #d8b4fe !important }
html.dark .stat-block:nth-child(4) .stat-trend { background: rgba(16, 185, 129, 0.2) !important; color: #6ee7b7 !important }

/* ── TENTANG (DARK MODE) ── */
/* ── ABOUT SECTION (DARK MODE) ── */
html.dark .decorative-number{color:rgba(96,165,250,.06)}
html.dark .about-eyebrow-redesigned{color:#60a5fa}
html.dark .eyebrow-dot{background:#60a5fa}
html.dark .about-headline-redesigned{color:#f0f6ff}
html.dark .headline-accent{color:#60a5fa}
html.dark .about-desc-redesigned{color:#94a3b8}

html.dark .feature-card{
  background:var(--dark-card-bg);
  border-color:var(--dark-card-border);
  backdrop-filter:blur(10px)
}
html.dark .feature-card::before{background:rgba(59,130,246,.2)}
html.dark .feature-card:hover{border-color:#60a5fa;box-shadow:0 12px 32px rgba(59,130,250,.2)}
html.dark .feature-card:hover::before{background:#60a5fa}
html.dark .card-icon{background:rgba(59,130,246,.12);border-color:rgba(59,130,246,.25);color:#60a5fa}
html.dark .feature-card:hover .card-icon{background:#3b82f6;color:#fff;border-color:#3b82f6}
html.dark .card-num{color:#60a5fa}
html.dark .card-title{color:#f0f6ff}
html.dark .card-desc{color:#94a3b8}

html.dark .feature-card-primary{background:linear-gradient(135deg,#2563eb 0%,#3b82f6 100%);border-color:#3b82f6}
html.dark .feature-card-primary::before{background:rgba(255,255,255,.25)}
html.dark .feature-card-primary:hover{box-shadow:0 16px 40px rgba(59,130,250,.35)}

html.dark .vertical-divider{background:linear-gradient(to bottom,transparent,rgba(59,130,246,.2) 20%,rgba(59,130,246,.2) 80%,transparent)}

html.dark .school-photo-frame-redesigned{
  background:linear-gradient(145deg,rgba(20,32,60,.9) 0%,rgba(13,22,42,.95) 100%);
  border-color:rgba(59,130,246,.25);
  box-shadow:0 24px 64px rgba(0,0,0,.6)
}
html.dark .glass-badge{
  background:rgba(18,29,54,.9);
  border-color:rgba(59,130,246,.3);
  box-shadow:0 8px 24px rgba(0,0,0,.4)
}
html.dark .glass-badge:hover{background:rgba(18,29,54,.95)}
html.dark .badge-icon{
  background:linear-gradient(135deg,#3b82f6 0%,#60a5fa 100%);
  box-shadow:0 4px 12px rgba(59,130,246,.4)
}
html.dark .badge-year{color:#60a5fa}
html.dark .badge-name{color:#f0f6ff}
html.dark .about-badge-lbl { color: #94a3b8 !important }
html.dark .about-badge-icon { background: linear-gradient(135deg, #3b82f6, #60a5fa) !important }

/* ── FOOTER ── */
html.dark .footer { background: var(--dark-bg-footer) !important; border-top: 1px solid rgba(59, 130, 246, 0.15) !important }
html.dark .footer-logo-box { background: #3b82f6 !important }
html.dark .footer-brand-name { color: #f0f6ff !important }
html.dark .footer-brand-sub { color: #94a3b8 !important }
html.dark .footer-desc { color: #94a3b8 !important }
html.dark .footer-heading { color: #dce9ff !important }
html.dark .footer-list a { color: #94a3b8 !important }
html.dark .footer-list a:hover { color: #60a5fa !important }
html.dark .footer-divider { border-top-color: rgba(59, 130, 246, 0.15) !important }
html.dark .footer-copy { color: #64748b !important }
html.dark .social-btn { background: rgba(22, 36, 65, 0.6) !important; border-color: rgba(59, 130, 246, 0.25) !important; color: #94a3b8 !important }
html.dark .social-btn:hover { background: #3b82f6 !important; border-color: #3b82f6 !important; color: #fff !important; box-shadow: 0 4px 12px rgba(59,130,246,.3) !important }

/* ── MISC ── */
html.dark .hero-h1 { text-shadow:0 2px 20px rgba(0,0,0,.5) }
html.dark .hero-badge { background:#1e40af !important; color:#ffffff !important; border-color:rgba(255,255,255,.4) !important }
html.dark .hero-badge-pulse { background:#ffffff !important; opacity:.9 !important }
html.dark .hero { background:linear-gradient(135deg,rgba(30,64,175,0.85),rgba(37,99,235,0.80)),url('/build/assets/smkTop.png') !important }
html.dark .hero-btn-main { background:rgba(255,255,255,.2) !important; color:#ffffff !important; border-color:rgba(255,255,255,.4) !important }
html.dark .hero-btn-main:hover { background:rgba(255,255,255,.3) !important; border-color:rgba(255,255,255,.6) !important }
/* ─── MOBILE NAV LINKS ─── */
.nav-mob-login{background:var(--bg3);color:var(--text2)}
.nav-mob-register{background:#1d4ed8;color:#fff}
</style>
</head>
<body>


<header class="site-header">
<nav class="nav">
  <div class="nav-inner">
    
    <a href="<?php echo e(route('home')); ?>" class="nav-brand" aria-label="SIPBAR Homepage">
      <div class="nav-logo-wrap">
        <img src="/build/assets/logosmkn.png" alt="Logo SMKN 1 Bangsri" class="nav-brand-img">
      </div>
      <div class="nav-brand-text">
        <div class="nav-brand-title">SIPBAR</div>
        <div class="nav-brand-subtitle">SMKN 1 BANGSRI</div>
      </div>
    </a>

    
    <div class="nav-links">
      <a href="#beranda" class="active">Beranda</a>
      <a href="#fitur">Fitur</a>
      <a href="#data-inventaris">Inventaris</a>
      <a href="#tentang">Tentang</a>
      <a href="#kontak">Bantuan</a>
    </div>

    
    <div class="nav-actions">
      
      <button type="button" class="theme-toggle theme-toggle-btn" aria-label="Ganti Tema Tampilan" title="Ganti Tema">
        
        <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10A5 5 0 0012 7z"/>
        </svg>
        
        <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
        </svg>
      </button>

      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
        <a href="<?php echo e(route('dashboard')); ?>" class="btn-nav-cta">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
          <span>Dashboard</span>
        </a>
      <?php else: ?>
        <a href="<?php echo e(route('login')); ?>" class="btn-nav-cta">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
          <span>Masuk</span>
        </a>
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <div class="nav-mobile-ctrls">
      
      <button type="button" class="theme-toggle theme-toggle-btn" aria-label="Ganti Tema Tampilan" title="Ganti Tema">
        <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10A5 5 0 0012 7z"/>
        </svg>
        <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
        </svg>
      </button>

      
      <button type="button" class="nav-ham" id="navHamBtn" aria-label="Buka Menu Navigasi" aria-expanded="false" title="Menu Navigasi">
        <span class="ham-line"></span>
        <span class="ham-line"></span>
        <span class="ham-line"></span>
      </button>
    </div>
  </div>

  
  <div id="navMob" class="nav-mobile">
    <div class="nav-mobile-links">
      <a href="#beranda" onclick="closeNavMob()">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        <span>Beranda</span>
      </a>
      <a href="#fitur" onclick="closeNavMob()">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        <span>Fitur Utama</span>
      </a>
      <a href="#data-inventaris" onclick="closeNavMob()">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        <span>Katalog Inventaris</span>
      </a>
      <a href="#tentang" onclick="closeNavMob()">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>Tentang SIPBAR</span>
      </a>
      <a href="#kontak" onclick="closeNavMob()">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        <span>Bantuan & Kontak</span>
      </a>
    </div>

    <div class="nav-mob-divider"></div>

    
    <div class="nav-mob-theme-row">
      <div class="nav-mob-theme-info">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
        <span>Tema Tampilan</span>
      </div>
      <button type="button" class="nav-mob-theme-btn theme-toggle-btn" aria-label="Ganti Tema">
        <span class="mob-t-sun">
          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10A5 5 0 0012 7z"/></svg>
          Mode Terang
        </span>
        <span class="mob-t-moon">
          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
          Mode Gelap
        </span>
      </button>
    </div>

    <div class="nav-mobile-footer">
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
        <a href="<?php echo e(route('dashboard')); ?>" class="nav-mob-login">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
          <span>Masuk Dashboard</span>
        </a>
      <?php else: ?>
        <a href="<?php echo e(route('login')); ?>" class="nav-mob-login">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
          <span>Masuk ke SIPBAR</span>
        </a>
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
  </div>
</nav>
</header>


<section class="hero" id="beranda">
  <div class="hero-inner">
    <div>
      <div class="hero-badge"><span class="hero-badge-pulse"></span>Sistem Inventaris Modern</div>
      <h1 class="hero-h1">Kelola Inventaris<br><em>Lebih Mudah</em> & Efisien</h1>
      <p class="hero-p">Platform web modern untuk mengelola inventaris sekolah secara digital, transparan, dan terintegrasi.</p>
    </div>
  </div>
</section>


<section class="section feat-bg" id="fitur">
  <div class="section-inner">
    <div class="section-head">
      <div class="section-eyebrow"><span class="section-eyebrow-dot"></span>Kapabilitas Sistem</div>
      <h2 class="section-h2">Tata Kelola Inventaris <em>Cepat & Terintegrasi</em></h2>
      <p class="section-lead">Mulai dari pengajuan siswa, approval guru secara instan, hingga serah-terima barang dengan QR code.</p>
    </div>

    <div class="feat-bento">
      
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
      <a href="<?php echo e(route('loans.index')); ?>" class="feat-bento-card feat-card-hero">
      <?php else: ?>
      <a href="<?php echo e(route('login')); ?>" class="feat-bento-card feat-card-hero">
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div>
          <div class="feat-top-meta">
            <span class="feat-index">01 / WORKFLOW UTAMA</span>
            <span class="feat-badge-pill">Approval Cepat & QR</span>
          </div>
          <div class="feat-title">Sirkulasi Peminjaman Digital & Validasi QR Code</div>
          <p class="feat-summary">Siswa dapat mengajukan peminjaman secara mandiri melalui web. Guru pembimbing memberikan persetujuan seketika lewat tautan resmi, dan pengambilan barang divalidasi dengan pemindaian QR Code di ruang sarpras tanpa formulir kertas.</p>
          
          
          <div class="feat-workflow-flow">
            <div class="feat-flow-step">
              <span class="feat-flow-dot">1</span>
              <span>Pengajuan Siswa</span>
            </div>
            <svg class="feat-flow-arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            <div class="feat-flow-step">
              <span class="feat-flow-dot">2</span>
              <span>Approval Guru</span>
            </div>
            <svg class="feat-flow-arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            <div class="feat-flow-step">
              <span class="feat-flow-dot">3</span>
              <span>Scan QR Sarpras</span>
            </div>
          </div>
        </div>
        <div class="feat-action-link">
          <span>Pelajari Alur Peminjaman</span>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </div>
      </a>

      
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
      <a href="<?php echo e(route('inventory.index')); ?>" class="feat-bento-card feat-card-side">
      <?php else: ?>
      <a href="<?php echo e(route('login')); ?>" class="feat-bento-card feat-card-side">
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div>
          <div class="feat-top-meta">
            <span class="feat-index">02</span>
            <div class="feat-icon-duo">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
          </div>
          <div class="feat-title">Manajemen Stok & Tracking Aset</div>
          <p class="feat-summary">Katalogisasi seluruh barang sekolah secara terstruktur lengkap dengan kategori, nomor seri, status kondisi fisik, dan lokasi penempatan.</p>
        </div>
        <div class="feat-action-link">
          <span>Kelola Inventaris</span>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </div>
      </a>

      
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
      <a href="<?php echo e(route('returns.index')); ?>" class="feat-bento-card feat-card-sub">
      <?php else: ?>
      <a href="<?php echo e(route('login')); ?>" class="feat-bento-card feat-card-sub">
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div>
          <div class="feat-top-meta">
            <span class="feat-index">03</span>
            <div class="feat-icon-duo">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
          </div>
          <div class="feat-title">Verifikasi Pengembalian</div>
          <p class="feat-summary">Pengecekan kondisi fisik barang saat dikembalikan memastikan aset sekolah tetap terawat dan pencatatan mutasi berlangsung transparan.</p>
        </div>
        <div class="feat-action-link">
          <span>Lihat Pengembalian</span>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </div>
      </a>

      
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
      <a href="<?php echo e(route('reports.index')); ?>" class="feat-bento-card feat-card-sub">
      <?php else: ?>
      <a href="<?php echo e(route('login')); ?>" class="feat-bento-card feat-card-sub">
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div>
          <div class="feat-top-meta">
            <span class="feat-index">04</span>
            <div class="feat-icon-duo">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
          </div>
          <div class="feat-title">Audit & Rekapitulasi Otomatis</div>
          <p class="feat-summary">Hasilkan laporan sirkulasi barang berkala dan statistik frekuensi pemakaian untuk kebutuhan evaluasi dan audit sarana prasarana sekolah.</p>
        </div>
        <div class="feat-action-link">
          <span>Buka Laporan</span>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </div>
      </a>

      
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
      <a href="<?php echo e(route('users.index')); ?>" class="feat-bento-card feat-card-sub">
      <?php else: ?>
      <a href="<?php echo e(route('login')); ?>" class="feat-bento-card feat-card-sub">
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div>
          <div class="feat-top-meta">
            <span class="feat-index">05</span>
            <div class="feat-icon-duo">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
          </div>
          <div class="feat-title">Kontrol Akses Multi-Peran</div>
          <p class="feat-summary">Manajemen otorisasi yang fleksibel untuk Siswa, Guru Pembimbing, dan Tim Sarpras sesuai batas wewenang operasional masing-masing.</p>
        </div>
        <div class="feat-action-link">
          <span>Atur Pengguna</span>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </div>
      </a>
    </div>
  </div>
</section>


<?php
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
    
    return [
        'total_items' => $totalItems,
        'total_categories' => $totalCategories,
        'top_category' => $topCategoryName,
        'total_users' => $totalUsers,
        'user_breakdown' => $userBreakdown,
        'total_borrowings' => $totalBorrowings,
        'completion_rate' => $completionRate,
    ];
});
?>

<section class="section stats-bg" id="data-inventaris">
  <div class="stats-inner">
    <div class="stats-left">
      <div class="stats-eyebrow">
        <span class="stats-eyebrow-pulse"></span>
        Data Inventaris System
      </div>
      <h2 class="stats-h2">Inventaris Sekolah<br><em>dalam Real-Time Data</em></h2>
      <p class="stats-p">Kelola dan pantau seluruh aset fisik sekolah secara terintegrasi, transparan, dan dapat diakses dari mana saja dengan sistem inventaris modern.</p>
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
      <a href="<?php echo e(route('inventory.index')); ?>" class="stats-cta-btn">
        <span>Jelajahi Data Inventaris</span>
        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
      </a>
      <?php else: ?>
      <a href="<?php echo e(route('login')); ?>" class="stats-cta-btn">
        <span>Jelajahi Data Inventaris</span>
        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
      </a>
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <div class="stats-grid">
      
      <div class="stat-block">
        <div class="stat-header">
          <div class="stat-icon-b" style="background:#1d4ed8">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#ffffff" fill="none" viewBox="0 0 24 24" stroke="#ffffff"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
          </div>
          <span class="stat-trend" style="background:#dbeafe;color:#1e40af">Real-time</span>
        </div>
        <div class="stat-num-b"><?php echo e(number_format($stats['total_items'], 0, ',', '.')); ?></div>
        <div class="stat-lbl-b">Total Barang Terdata</div>
        <div class="stat-sub-b">Terintegrasi seluruh unit</div>
      </div>

      
      <div class="stat-block">
        <div class="stat-header">
          <div class="stat-icon-b" style="background:#0891b2">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#ffffff" fill="none" viewBox="0 0 24 24" stroke="#ffffff"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
          </div>
          <span class="stat-trend" style="background:#e0f2fe;color:#0369a1">Terstruktur</span>
        </div>
        <div class="stat-num-b"><?php echo e(number_format($stats['total_categories'], 0, ',', '.')); ?></div>
        <div class="stat-lbl-b">Kategori Aset</div>
        <div class="stat-sub-b"><?php echo e($stats['top_category']); ?> terbanyak</div>
      </div>

      
      <div class="stat-block">
        <div class="stat-header">
          <div class="stat-icon-b" style="background:#7c3aed">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#ffffff" fill="none" viewBox="0 0 24 24" stroke="#ffffff"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          </div>
          <span class="stat-trend" style="background:#f3e8ff;color:#6b21a8">Tersinkron</span>
        </div>
        <div class="stat-num-b"><?php echo e(number_format($stats['total_users'], 0, ',', '.')); ?></div>
        <div class="stat-lbl-b">Pengguna Aktif</div>
        <div class="stat-sub-b"><?php echo e($stats['user_breakdown']); ?></div>
      </div>

      
      <div class="stat-block">
        <div class="stat-header">
          <div class="stat-icon-b" style="background:#059669">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#ffffff" fill="none" viewBox="0 0 24 24" stroke="#ffffff"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
          </div>
          <span class="stat-trend" style="background:#d1fae5;color:#065f46"><?php echo e($stats['completion_rate']); ?>% Selesai</span>
        </div>
        <div class="stat-num-b"><?php echo e(number_format($stats['total_borrowings'], 0, ',', '.')); ?></div>
        <div class="stat-lbl-b">Sirkulasi Peminjaman</div>
        <div class="stat-sub-b">Proses approval cepat</div>
      </div>
    </div>
  </div>
</section>


<section class="section" id="tentang">
  <div class="section-inner">
    <div class="about-grid-redesigned">
      
      <div class="about-content-redesigned">
        
        <div class="decorative-number">04</div>
        
        <div class="about-eyebrow-redesigned">
          <span class="eyebrow-dot"></span>
          Tentang Platform SIPBAR
        </div>
        
        <h2 class="about-headline-redesigned">
          Membangun Sistem Peminjaman Barang yang <span class="headline-accent">Terintegrasi</span>
        </h2>
        
        <p class="about-desc-redesigned">
          SIPBAR mentransformasi pencatatan inventaris sekolah konvensional menjadi ekosistem digital yang terintegrasi, transparan, dan dapat diakses dari mana saja.
        </p>
        
        
        <div class="feature-cards-redesigned">
          
          <div class="feature-card feature-card-primary">
            <div class="card-header">
              <div class="card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <span class="card-num">01</span>
            </div>
            <h3 class="card-title">Integrasi</h3>
            <p class="card-desc">Persetujuan cepat tanpa kertas — guru dapat menyetujui peminjaman langsung dari smartphone.</p>
          </div>

          
          <div class="feature-card">
            <div class="card-header">
              <div class="card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
              </div>
              <span class="card-num">02</span>
            </div>
            <h3 class="card-title">Akurasi</h3>
            <p class="card-desc">Inventaris real-time — stok aset bertambah/berkurang otomatis setiap transaksi terverifikasi.</p>
          </div>

          
          <div class="feature-card">
            <div class="card-header">
              <div class="card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
              </div>
              <span class="card-num">03</span>
            </div>
            <h3 class="card-title">Akuntabilitas</h3>
            <p class="card-desc">Riwayat & log transparan — setiap pergerakan barang memiliki jejak audit lengkap.</p>
          </div>

          
          <div class="feature-card">
            <div class="card-header">
              <div class="card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
              </div>
              <span class="card-num">04</span>
            </div>
            <h3 class="card-title">Aksesibilitas</h3>
            <p class="card-desc">Akses fleksibel multi-perangkat — responsif di PC, tablet, maupun ponsel.</p>
          </div>
        </div>
      </div>

      
      <div class="vertical-divider"></div>

      
      <div class="about-visual-redesigned">
        <div class="school-photo-frame-redesigned">
          <div class="photo-wrapper">
            <img 
              src="/build/assets/smkTop.png" 
              alt="Gedung SMKN 1 Bangsri" 
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
                <span class="badge-year">2026</span>
                <span class="badge-name">SMKN 1 BANGSRI</span>
              </div>
            </div>
            
            <div class="photo-caption">
              <div class="caption-label">Gedung Utama Sekolah</div>
              <div class="caption-sub">Pusat kegiatan belajar mengajar dan inovasi digital</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<footer class="footer" id="kontak">
  <div class="footer-inner">
    <div class="footer-grid">
      <div>
        <div class="footer-logo-wrap">
          <div class="footer-logo-box" style="width:48px;height:48px;border-radius:50%;background:#ffffff;display:flex;align-items:center;justify-content:center;overflow:hidden;padding:4px;box-shadow:0 2px 8px rgba(0,0,0,.15);flex-shrink:0;"><img src="/build/assets/logosmkn.png" alt="Logo SMKN 1 Bangsri" style="width:100%;height:100%;object-fit:contain;"></div>
          <div><div class="footer-brand-name">SIPBAR</div><div class="footer-brand-sub">SMKN 1 BANGSRI</div></div>
        </div>
        <p class="footer-desc">Sistem inventaris berbasis web yang lebih efektif, efisien, dan transparan untuk sekolah.</p>
        <div class="social-row">
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z','M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5','M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
          <a href="#" class="social-btn"><svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="<?php echo e($i); ?>"/></svg></a>
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
      </div>
      <div>
        <div class="footer-heading">Menu</div>
        <ul class="footer-list"><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['Beranda','Fitur','Tentang']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?><li><a href="#"><?php echo e($m); ?></a></li><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?></ul>
      </div>
      <div>
        <div class="footer-heading">Fitur</div>
        <ul class="footer-list"><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['Manajemen Barang','Peminjaman','Pengembalian','Laporan','Pengguna']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?><li><a href="#"><?php echo e($f); ?></a></li><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?></ul>
      </div>
      <div>
        <div class="footer-heading">Bantuan</div>
        <ul class="footer-list"><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['Panduan Penggunaan','FAQ','Kebijakan Privasi','Syarat & Ketentuan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?><li><a href="#"><?php echo e($b); ?></a></li><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?></ul>
      </div>
    </div>
    <hr class="footer-divider">
    <p class="footer-copy">&copy; <?php echo e(date('Y')); ?> SIPBAR – Sistem Informasi Pengelolaan Barang. All rights reserved.</p>
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

  // ─── Toggle Theme function ───
  function updateThemeBtnTitles(isDark){
    themeBtns.forEach(function(b){
      b.title = isDark ? 'Ganti ke Mode Terang' : 'Ganti ke Mode Gelap';
    });
  }

  function toggleTheme(){
    var isDark = html.classList.toggle('dark');
    localStorage.setItem('sipbar-theme', isDark ? 'dark' : 'light');
    updateThemeBtnTitles(isDark);
    themeBtns.forEach(function(b){
      b.style.transform = 'rotate(25deg) scale(0.9)';
      setTimeout(function(){ b.style.transform = ''; }, 250);
    });
  }

  themeBtns.forEach(function(b){
    b.addEventListener('click', toggleTheme);
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
    if(e.altKey && e.key === 'd') toggleTheme();
    if(e.key === 'Escape' && navMob && navMob.classList.contains('open')) closeNavMob();
  });

  // ─── Close mobile nav on resize ───
  window.addEventListener('resize', function(){
    if(window.innerWidth > 768 && navMob && navMob.classList.contains('open')){
      window.closeNavMob();
    }
  });

  // ─── Active nav link on scroll ───
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
  }, {rootMargin:'-30% 0px -60% 0px'});
  sections.forEach(function(s){ observer.observe(s); });
});
</script>
</body>
</html>
<?php /**PATH C:\Users\Dell\SIPBARV2\resources\views/welcome.blade.php ENDPATH**/ ?>