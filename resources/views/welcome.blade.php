
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIPBAR – Sistem Informasi Pengelolaan Barang</title>
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
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
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
  --bg:#0f1117;
  --bg2:#141c2b;
  --bg3:#1a2540;
  --surface:#1a2236;
  --border:#2a3f6f;
  --border2:#243050;
  --card:#16203a;
  --text:#f0f6ff;
  --text2:#dce9ff;
  --muted:#94a3b8;
  --subtle:#94a3b8;
  --nav-bg:rgba(10,14,26,.97);
  --nav-border:rgba(42,63,111,.9);
  --shadow:0 4px 12px rgba(0,0,0,.3);
  --shadow-lg:0 12px 32px rgba(0,0,0,.4);
}

/* ─── NAVBAR ─── */
.nav{position:sticky;top:0;z-index:100;background:var(--nav-bg);backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);border-bottom:1px solid var(--nav-border);transition:background .3s}
.nav-inner{max-width:1200px;margin:0 auto;padding:0 24px;height:64px;display:flex;align-items:center;gap:40px}
.nav-logo{display:flex;align-items:center;gap:0;text-decoration:none;flex-shrink:0}
.nav-logo-box{width:36px;height:36px;background:#1d4ed8;border-radius:10px;display:flex;align-items:center;justify-content:center}
.nav-logo-name{font-size:15px;font-weight:800;color:#1d4ed8}
.nav-logo-sub{font-size:10px;color:var(--muted)}
.nav-links{display:flex;align-items:center;gap:4px;margin-left:8px}
.nav-links a{padding:6px 14px;font-size:14px;font-weight:500;color:var(--muted);text-decoration:none;border-radius:8px;transition:all .15s}
.nav-links a:hover{background:var(--bg3);color:var(--text)}
.nav-links a.active{color:#1d4ed8;font-weight:600}
.nav-actions{display:flex;align-items:center;gap:8px;margin-left:auto}
.btn-ghost{display:inline-flex;align-items:center;gap:7px;padding:8px 18px;font-size:13px;font-weight:600;color:var(--text);border:1.5px solid var(--border2);border-radius:10px;text-decoration:none;background:var(--card);transition:all .15s}
.btn-ghost:hover{border-color:#1d4ed8;color:#1d4ed8;background:var(--bg2)}
.btn-primary{display:inline-flex;align-items:center;gap:7px;padding:8px 20px;font-size:13px;font-weight:700;color:#fff;background:#1d4ed8;border-radius:10px;text-decoration:none;border:none;transition:all .15s}
.btn-primary:hover{background:#1e40af;transform:translateY(-1px)}
.nav-ham{display:none;background:none;border:none;cursor:pointer;color:var(--muted);padding:6px;border-radius:8px}
.nav-ham:hover{background:var(--bg3)}
.nav-mobile{display:none;padding:12px 24px 18px;border-top:1px solid var(--border2);flex-direction:column;gap:4px;background:var(--nav-bg)}
.nav-mobile.open{display:flex}
.nav-mobile a{padding:10px 12px;font-size:14px;font-weight:500;color:var(--muted);text-decoration:none;border-radius:8px}
.nav-mobile a:hover{background:var(--bg3);color:var(--text)}
.nav-mobile-actions{display:flex;gap:8px;margin-top:8px}
.nav-mobile-actions a{flex:1;text-align:center;padding:10px;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none}
/* ─── DARK TOGGLE BUTTON ─── */
.theme-toggle{display:flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:10px;border:1.5px solid var(--border2);background:var(--card);cursor:pointer;transition:all .2s,transform .25s;flex-shrink:0;color:var(--muted)}
.theme-toggle:hover{border-color:#1d4ed8;color:#1d4ed8;background:var(--bg2);transform:rotate(8deg)}
.icon-sun{display:none}
.icon-moon{display:block}
html.dark .icon-sun{display:block}
html.dark .icon-moon{display:none}
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
/* ─── FEATURES ─── */
.feat-bg{background:var(--surface)}
.feat-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:16px}
.feat-card{background:var(--card);border:1px solid var(--border2);border-radius:16px;padding:24px 20px;text-align:center;transition:all .2s}
.feat-card:hover{transform:translateY(-4px);box-shadow:0 8px 24px rgba(29,78,216,.08);border-color:#1d4ed8}
.feat-icon-wrap{width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px}
.feat-name{font-size:14px;font-weight:700;color:var(--text);margin-bottom:8px;letter-spacing:-.01em}
.feat-desc{font-size:13px;color:var(--muted);line-height:1.6;font-weight:400}
/* ─── STATS / DATA INVENTARIS ─── */
.stats-bg{background:#1d4ed8;position:relative;overflow:hidden;padding:70px 24px}
.stats-bg::before{content:'';position:absolute;top:0;left:0;width:6px;height:100%;background:#f59e0b}
.stats-inner{max-width:1200px;margin:0 auto;position:relative;z-index:1;display:grid;grid-template-columns:1fr 1.1fr;gap:50px;align-items:center}
.stats-eyebrow{display:inline-flex;align-items:center;gap:8px;font-size:11px;font-weight:700;color:#ffffff;letter-spacing:.08em;text-transform:uppercase;margin-bottom:12px;background:rgba(255,255,255,.15);padding:6px 16px;border-radius:999px;border:1px solid rgba(255,255,255,.2)}
.stats-eyebrow-pulse{width:6px;height:6px;border-radius:50%;background:#f59e0b}
.stats-h2{font-size:34px;font-weight:800;color:#fff;line-height:1.2;margin-bottom:14px;letter-spacing:-.01em}
.stats-h2 em{font-style:normal;color:#f59e0b}
.stats-p{font-size:16px;color:rgba(255,255,255,.9);line-height:1.65;margin-bottom:24px;font-weight:400}
.stats-cta-btn{display:inline-flex;align-items:center;gap:8px;padding:12px 24px;border-radius:12px;background:#ffffff;color:#1d4ed8;font-size:14px;font-weight:700;text-decoration:none;transition:all .2s}
.stats-cta-btn:hover{transform:translateY(-2px);background:#f8fafc}
.stats-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:16px}

/* Solid Rectangle Cards */
.stat-block{background:#ffffff;border:1px solid #e2e8f0;border-radius:16px;padding:20px;box-shadow:0 4px 12px rgba(0,0,0,.1);transition:all .2s ease;position:relative;overflow:hidden}
.stat-block:hover{transform:translateY(-3px);box-shadow:0 12px 28px rgba(0,0,0,.15)}
.stat-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px}
.stat-icon-b{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.stat-trend{font-size:11px;font-weight:700;padding:4px 10px;border-radius:999px}
.stat-num-b{font-size:32px;font-weight:800;color:#0f172a;line-height:1;letter-spacing:-.02em}
.stat-lbl-b{font-size:14px;font-weight:700;color:#1e293b;margin-top:8px;letter-spacing:-.01em}
.stat-sub-b{font-size:12px;font-weight:500;color:#64748b;margin-top:3px}
</style>
<style>
/* ─── ABOUT ─── */
.about-grid{display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center}
.about-visual{position:relative}
.about-img-wrap{background:linear-gradient(135deg,#dbeafe 0%,#bfdbfe 50%,#a5f3fc 100%);border-radius:20px;overflow:hidden;height:360px;position:relative;box-shadow:0 24px 64px rgba(37,99,235,.15)}
.about-sky{position:absolute;top:0;left:0;right:0;height:150px;background:linear-gradient(180deg,#7dd3fc,#bfdbfe)}
.about-cloud{position:absolute;background:rgba(255,255,255,.85);border-radius:999px}
.about-building{position:absolute;bottom:60px;left:50%;transform:translateX(-50%);width:78%}
.about-building-body{background:#e2e8f0;border-radius:8px 8px 0 0;padding:14px 10px 0}
.about-building-roof{background:#cbd5e1;height:16px;border-radius:6px 6px 0 0}
.about-win-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:3px;padding:10px 6px 4px}
.about-win{height:26px;background:#93c5fd;border-radius:3px;opacity:.8}
.about-door{width:28px;height:32px;background:#94a3b8;border-radius:4px 4px 0 0;margin:0 auto}
.about-grass{position:absolute;bottom:0;left:0;right:0;height:60px;background:linear-gradient(180deg,#86efac,#4ade80);opacity:.7}
.quote-float{position:absolute;bottom:20px;right:16px;background:#fff;border-radius:12px;padding:14px;max-width:200px;box-shadow:0 12px 32px rgba(0,0,0,.12)}
.quote-q{font-size:28px;font-family:Georgia,serif;color:#1d4ed8;line-height:1}
.quote-text{font-size:12px;color:#475569;font-style:italic;line-height:1.5;font-weight:400}
.quote-author{font-size:12px;font-weight:700;color:#1d4ed8;margin-top:8px}
.about-badge{position:absolute;top:20px;left:16px;background:#fff;border-radius:12px;padding:12px 14px;box-shadow:0 8px 24px rgba(0,0,0,.1);display:flex;align-items:center;gap:10px}
.about-badge-icon{width:34px;height:34px;background:linear-gradient(135deg,#1d4ed8,#3b82f6);border-radius:10px;display:flex;align-items:center;justify-content:center}
.about-badge-val{font-size:17px;font-weight:800;color:#0f172a}
.about-badge-lbl{font-size:11px;color:#475569;font-weight:500}
.check-list{list-style:none;display:flex;flex-direction:column;gap:12px;margin:20px 0 32px}
.check-list li{display:flex;align-items:flex-start;gap:12px;font-size:15px;color:#475569;line-height:1.5;font-weight:400}
.check-dot{width:20px;height:20px;background:linear-gradient(135deg,#1d4ed8,#3b82f6);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;box-shadow:0 4px 10px rgba(29,78,216,.3)}
/* ─── FOOTER ─── */
.footer{background:#0f172a;color:#94a3b8;padding:60px 24px 28px}
.footer-inner{max-width:1200px;margin:0 auto}
.footer-grid{display:grid;grid-template-columns:2.2fr 1fr 1fr 1fr 1.4fr;gap:40px;margin-bottom:48px}
.footer-brand-name{font-size:15px;font-weight:800;color:#f1f5f9;letter-spacing:-.01em}
.footer-brand-sub{font-size:11px;color:#64748b;font-weight:500}
.footer-desc{font-size:13px;line-height:1.65;margin-bottom:18px;color:#94a3b8;max-width:260px;font-weight:400}
.footer-logo-wrap{display:flex;align-items:center;gap:10px;margin-bottom:14px}
.footer-logo-box{width:34px;height:34px;background:#1d4ed8;border-radius:10px;display:flex;align-items:center;justify-content:center}
.social-row{display:flex;gap:8px}
.social-btn{width:32px;height:32px;background:#1e293b;border-radius:8px;display:flex;align-items:center;justify-content:center;transition:all .2s;color:#94a3b8;border:1px solid #1e293b}
.social-btn:hover{background:#1d4ed8;color:#fff;border-color:#1d4ed8}
.footer-heading{font-size:13px;font-weight:700;color:#e2e8f0;margin-bottom:16px;letter-spacing:.01em}
.footer-list{list-style:none;display:flex;flex-direction:column;gap:10px}
.footer-list a{font-size:13px;color:#94a3b8;text-decoration:none;transition:color .15s;display:flex;align-items:center;gap:6px;font-weight:400}
.footer-list a:hover{color:#e2e8f0}
.footer-contact{font-size:13px;color:#94a3b8;display:flex;align-items:flex-start;gap:8px;margin-bottom:10px;font-weight:400}
.footer-contact svg{color:#1d4ed8;flex-shrink:0;margin-top:2px}
.footer-divider{border:none;border-top:1px solid #1e293b;margin-bottom:20px}
.footer-copy{text-align:center;font-size:12px;color:#64748b;font-weight:400}
/* ─── RESPONSIVE ─── */
@media(max-width:1024px){.hero-inner,.stats-inner,.about-grid{grid-template-columns:1fr}.hero{padding-bottom:50px}.hero-trust{grid-template-columns:repeat(2,1fr)}.cat-grid{grid-template-columns:repeat(3,1fr)}.feat-grid{grid-template-columns:repeat(3,1fr)}.footer-grid{grid-template-columns:repeat(2,1fr)}.section{padding:60px 24px}}
@media(max-width:768px){.nav-links,.nav-actions{display:none}.nav-ham{display:block}.hero-h1{font-size:36px}.section-h2{font-size:28px}.stats-h2{font-size:28px}.cat-grid{grid-template-columns:repeat(2,1fr)}.feat-grid{grid-template-columns:repeat(2,1fr)}.stats-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:480px){.cat-grid,.feat-grid{grid-template-columns:1fr}.hero-h1{font-size:32px}.section-h2{font-size:24px}.stats-h2{font-size:24px}.footer-grid{grid-template-columns:1fr}.section{padding:50px 20px}}
/* ════════════════════════════════════════
   DARK MODE OVERRIDES — SEMUA ELEMEN
════════════════════════════════════════ */

/* ── NAVBAR ── */
html.dark .nav { background:rgba(10,14,26,.97) !important; border-bottom:1px solid #2a3f6f !important }
html.dark .nav-logo-box { background:#3b82f6 !important }
html.dark .nav-logo-name { color:#60a5fa !important }
html.dark .nav-logo-sub  { color:#94a3b8 !important }
html.dark .nav-links a   { color:#94a3b8 !important }
html.dark .nav-links a:hover { background:#1a2540 !important; color:#f0f6ff !important }
html.dark .nav-links a.active { color:#60a5fa !important; background:rgba(96,165,250,.1) !important }
html.dark .theme-toggle  { background:#16203a !important; border-color:#2a3f6f !important; color:#94a3b8 !important }
html.dark .theme-toggle:hover { border-color:#60a5fa !important; color:#60a5fa !important; background:#1a2540 !important }
html.dark .btn-ghost { background:#16203a !important; border-color:#2a3f6f !important; color:#dce9ff !important }
html.dark .btn-ghost:hover { border-color:#60a5fa !important; color:#60a5fa !important; background:#1a2540 !important }
html.dark .btn-primary { background:#3b82f6 !important }
html.dark .btn-primary:hover { background:#60a5fa !important }

/* Mobile nav */
html.dark .nav-mobile { background:rgba(10,14,26,.98) !important }
html.dark .nav-mobile a { color:#94a3b8 !important }
html.dark .nav-mobile a:hover { color:#f0f6ff !important; background:#1a2540 !important }
html.dark .nav-mob-login { background:#1a2540 !important; color:#dce9ff !important }
html.dark .nav-mob-register { background:#3b82f6 !important }

/* ── SECTION COMMON ── */
html.dark .section { background:#0f1117 !important }
html.dark .section-eyebrow { color:#60a5fa !important }
html.dark .section-eyebrow-dot { background:#60a5fa !important }
html.dark .section-h2 { color:#f0f6ff !important }
html.dark .section-h2 em { color:#60a5fa !important }
html.dark .section-lead { color:#94a3b8 !important }

/* ── KATEGORI ── */
html.dark .cat-card {
  background:#16203a !important;
  border-color:#2a3f6f !important;
  box-shadow:0 2px 12px rgba(0,0,0,.3) !important;
}
html.dark .cat-card:hover {
  border-color:#60a5fa !important;
  box-shadow:0 12px 32px rgba(96,165,250,.15) !important;
}
html.dark .cat-icon-wrap { background:#1a2540 !important }
html.dark .cat-icon-wrap svg { color:#60a5fa !important }
html.dark .cat-card:hover .cat-icon-wrap { background:#3b82f6 !important }
html.dark .cat-card:hover .cat-icon-wrap svg { color:#fff !important }
html.dark .cat-name { color:#f0f6ff !important }
html.dark .cat-desc { color:#94a3b8 !important }
html.dark .cat-link { color:#60a5fa !important }

/* ── FITUR ── */
html.dark .feat-bg { background:#141c2b !important }
html.dark .feat-card {
  background:#16203a !important;
  border-color:#243050 !important;
  box-shadow:0 2px 12px rgba(0,0,0,.25) !important;
}
html.dark .feat-card:hover {
  border-color:#60a5fa !important;
  box-shadow:0 8px 24px rgba(96,165,250,.15) !important;
}
html.dark .feat-icon-wrap { filter:brightness(0.85) saturate(1.2) }
html.dark .feat-name { color:#f0f6ff !important }
html.dark .feat-desc { color:#94a3b8 !important }

/* ── STATS SECTION ── */
html.dark .stats-bg { background:#1e40af !important }
html.dark .stat-block {
  background:rgba(255,255,255,.09) !important;
  border-color:rgba(255,255,255,.15) !important;
}
html.dark .stat-block:hover { 
  background:rgba(255,255,255,.12) !important;
  box-shadow:0 12px 28px rgba(0,0,0,.3) !important;
}
html.dark .stat-icon-b {
  filter:brightness(1.1) !important;
}
html.dark .stat-num-b { color:#fff !important }
html.dark .stat-lbl-b { color:rgba(255,255,255,.85) !important }
html.dark .stat-sub-b { color:rgba(255,255,255,.65) !important }
html.dark .stats-eyebrow { color:#bfdbfe !important; border-color:rgba(255,255,255,.25) !important }
html.dark .stats-eyebrow-pulse { background:#fb923c !important }
html.dark .stats-h2 { color:#fff !important }
html.dark .stats-h2 em { color:#fb923c !important }
html.dark .stats-p { color:rgba(255,255,255,.85) !important }
html.dark .stats-cta-btn { background:#f0f9ff !important; color:#1e40af !important }
html.dark .stats-cta-btn:hover { background:#fff !important }

/* ── TENTANG ── */
html.dark .about-grid > div > p { color:#94a3b8 !important }
html.dark .check-list li { color:#94a3b8 !important }
html.dark .quote-float { background:#1a2540 !important; box-shadow:0 8px 24px rgba(0,0,0,.5) !important }
html.dark .quote-q { color:#60a5fa !important }
html.dark .quote-text { color:#94a3b8 !important }
html.dark .quote-author { color:#60a5fa !important }
html.dark .about-badge { background:#1a2540 !important; box-shadow:0 8px 24px rgba(0,0,0,.5) !important }
html.dark .about-badge-val { color:#f0f6ff !important }
html.dark .about-badge-lbl { color:#94a3b8 !important }
html.dark .about-badge-icon { background:linear-gradient(135deg,#3b82f6,#60a5fa) !important }

/* ── FOOTER ── */
html.dark .footer { background:#070d1a !important; border-top-color:#1a2540 !important }
html.dark .footer-logo-box { background:#3b82f6 !important }
html.dark .footer-brand-name { color:#f0f6ff !important }
html.dark .footer-brand-sub { color:#94a3b8 !important }
html.dark .footer-desc { color:#94a3b8 !important }
html.dark .footer-heading { color:#dce9ff !important }
html.dark .footer-list a { color:#94a3b8 !important }
html.dark .footer-list a:hover { color:#cbd5e1 !important }
html.dark .footer-contact { color:#94a3b8 !important }
html.dark .footer-contact svg { color:#60a5fa !important }
html.dark .footer-divider { border-top-color:#1a2540 !important }
html.dark .footer-copy { color:#64748b !important }
html.dark .social-btn { background:#1a2540 !important; border-color:#243050 !important; color:#94a3b8 !important }
html.dark .social-btn:hover { background:#3b82f6 !important; border-color:#3b82f6 !important; color:#fff !important }

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

{{-- ═══════════════ NAVBAR ═══════════════ --}}
<header>
<nav class="nav">
  <div class="nav-inner">
    <a href="{{ route('home') }}" class="nav-logo">
      <img src="/build/assets/logosmkn1.png" alt="Logo Sekolah" style="width:75px;height:75px;object-fit:contain;">
      <div style="margin-left:-2px">
        <div class="nav-logo-name">SIPBAR</div>
        <div class="nav-logo-sub">SMKN 1 BANGSRI</div>
      </div>
    </a>
    <div class="nav-links">
      <a href="#beranda" class="active">Beranda</a>
      <a href="#kategori">Kategori</a>
      <a href="#fitur">Fitur</a>
      <a href="#tentang">Tentang</a>
      <a href="#kontak">Kontak</a>
    </div>
    <div class="nav-actions">
      {{-- DARK MODE TOGGLE --}}
      <button class="theme-toggle" id="themeToggle" aria-label="Toggle dark mode" title="Ganti tema">
        {{-- Sun icon (shown in dark mode) --}}
        <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10A5 5 0 0012 7z"/>
        </svg>
        {{-- Moon icon (shown in light mode) --}}
        <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
        </svg>
      </button>
      @auth
        <a href="{{ route('dashboard') }}" class="btn-primary">Dashboard</a>
      @else
        <a href="{{ route('login') }}" class="btn-primary">
          <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
          Masuk
        </a>
      @endauth
    </div>
    <button class="nav-ham" onclick="document.getElementById('navMob').classList.toggle('open')" aria-label="Menu">
      <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>
  </div>
  <div id="navMob" class="nav-mobile">
    <a href="#beranda">Beranda</a>
    <a href="#kategori">Kategori</a>
    <a href="#fitur">Fitur</a>
    <a href="#tentang">Tentang</a>
    <a href="#kontak">Kontak</a>
    <div class="nav-mobile-actions">
      <a href="{{ route('login') }}" class="nav-mob-login">Masuk</a>
    </div>
  </div>
</nav>
</header>

{{-- ═══════════════ HERO ═══════════════ --}}
<section class="hero" id="beranda">
  <div class="hero-inner">
    <div>
      <div class="hero-badge"><span class="hero-badge-pulse"></span>Sistem Inventaris Modern</div>
      <h1 class="hero-h1">Kelola Inventaris<br><em>Lebih Mudah</em> & Efisien</h1>
      <p class="hero-p">Platform web modern untuk mengelola inventaris sekolah secara digital, transparan, dan terintegrasi.</p>
      <div class="hero-btns">
        @auth
          <a href="{{ route('dashboard') }}" class="hero-btn-main">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            Dashboard
          </a>
        @else
          <a href="{{ route('login') }}" class="hero-btn-main">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
            Masuk
          </a>
        @endauth
      </div>
    </div>
  </div>
</section>

{{-- ═══════════════ KATEGORI ═══════════════ --}}
<section class="section" id="kategori">
  <div class="section-inner">
    <div class="section-head">
      <div class="section-eyebrow"><span class="section-eyebrow-dot"></span>Kategori Inventaris</div>
      <h2 class="section-h2">Kelompok <em>Barang Inventaris</em></h2>
      <p class="section-lead">Inventaris dikelompokkan dalam kategori untuk memudahkan pengelolaan dan pencarian barang.</p>
    </div>
    <div class="cat-grid">
      @php
      $cats=[
        ['Elektronik','Komputer, proyektor, printer, dan perangkat elektronik lainnya.','M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
        ['Furnitur','Meja, kursi, lemari, rak, dan perabot sekolah lainnya.','M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
        ['Buku & Referensi','Buku pelajaran, referensi, modul, dan bahan bacaan.','M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13'],
        ['Laboratorium','Alat dan bahan praktikum IPA, Kimia, Biologi, dll.','M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z'],
        ['Olahraga','Peralatan olahraga dan perlengkapan ekstrakurikuler.','M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07'],
        ['Lainnya','Kategori inventaris lainnya yang tidak termasuk di atas.','M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
      ];
      @endphp
      @foreach($cats as $c)
      <div class="cat-card">
        <div class="cat-icon-wrap">
          <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#2563eb" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $c[2] }}"/></svg>
        </div>
        <div class="cat-name">{{ $c[0] }}</div>
        <div class="cat-desc">{{ $c[1] }}</div>
        <a href="{{ route('login') }}" class="cat-link">Lihat Barang <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg></a>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══════════════ FITUR ═══════════════ --}}
<section class="section feat-bg" id="fitur">
  <div class="section-inner">
    <div class="section-head">
      <div class="section-eyebrow"><span class="section-eyebrow-dot"></span>Fitur Unggulan</div>
      <h2 class="section-h2">Fitur yang <em>Memudahkan</em> Pengelolaan</h2>
      <p class="section-lead">Berbagai fitur lengkap untuk membantu pengelolaan inventaris lebih efektif dan efisien.</p>
    </div>
    <div class="feat-grid">
      @php
      $feats=[
        ['Manajemen Barang','Kelola data barang dengan mudah — tambah, ubah, hapus, dan lacak stok secara real-time.','M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4','#eff6ff','#2563eb','inventory'],
        ['Peminjaman Barang','Catat dan pantau peminjaman barang secara sistematis dengan notifikasi jatuh tempo.','M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4','#ecfdf5','#059669','loans'],
        ['Pengembalian','Proses pengembalian lebih cepat dengan verifikasi kondisi barang yang terstruktur.','M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6','#fffbeb','#d97706','returns'],
        ['Laporan & Statistik','Laporan inventaris lengkap dengan grafik dan statistik penggunaan barang.','M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z','#fdf4ff','#9333ea','reports'],
        ['Manajemen Pengguna','Atur hak akses pengguna (Admin, Guru, Siswa) sesuai peran dan tanggung jawab.','M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z','#fff1f2','#e11d48','users'],
      ];
      @endphp
      @foreach($feats as $f)
      @auth
      <a href="{{ route($f[5] . '.index') }}" class="feat-card" style="text-decoration:none">
      @else
      <a href="{{ route('login') }}" class="feat-card" style="text-decoration:none">
      @endauth
        <div class="feat-icon-wrap" style="background:{{ $f[3] }}">
          <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:{{ $f[4] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f[2] }}"/></svg>
        </div>
        <div class="feat-name">{{ $f[0] }}</div>
        <div class="feat-desc">{{ $f[1] }}</div>
      </a>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══════════════ DATA INVENTARIS STATS ═══════════════ --}}
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
@endphp

<section class="section stats-bg" id="data-inventaris">
  <div class="stats-inner">
    <div class="stats-left">
      <div class="stats-eyebrow">
        <span class="stats-eyebrow-pulse"></span>
        Data Inventaris System
      </div>
      <h2 class="stats-h2">Inventaris Sekolah<br><em>dalam Real-Time Data</em></h2>
      <p class="stats-p">Kelola dan pantau seluruh aset fisik sekolah secara terintegrasi, transparan, dan dapat diakses dari mana saja dengan sistem inventaris modern.</p>
      @auth
      <a href="{{ route('inventory.index') }}" class="stats-cta-btn">
        <span>Jelajahi Data Inventaris</span>
        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
      </a>
      @else
      <a href="{{ route('login') }}" class="stats-cta-btn">
        <span>Jelajahi Data Inventaris</span>
        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
      </a>
      @endauth
    </div>
    <div class="stats-grid">
      {{-- Total Items --}}
      <div class="stat-block">
        <div class="stat-header">
          <div class="stat-icon-b" style="background:#1d4ed8">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#ffffff" fill="none" viewBox="0 0 24 24" stroke="#ffffff"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
          </div>
          <span class="stat-trend" style="background:#dbeafe;color:#1e40af">Real-time</span>
        </div>
        <div class="stat-num-b">{{ number_format($stats['total_items'], 0, ',', '.') }}</div>
        <div class="stat-lbl-b">Total Barang Terdata</div>
        <div class="stat-sub-b">Terintegrasi seluruh unit</div>
      </div>

      {{-- Total Categories --}}
      <div class="stat-block">
        <div class="stat-header">
          <div class="stat-icon-b" style="background:#0891b2">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#ffffff" fill="none" viewBox="0 0 24 24" stroke="#ffffff"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
          </div>
          <span class="stat-trend" style="background:#e0f2fe;color:#0369a1">Terstruktur</span>
        </div>
        <div class="stat-num-b">{{ number_format($stats['total_categories'], 0, ',', '.') }}</div>
        <div class="stat-lbl-b">Kategori Aset</div>
        <div class="stat-sub-b">{{ $stats['top_category'] }} terbanyak</div>
      </div>

      {{-- Total Users --}}
      <div class="stat-block">
        <div class="stat-header">
          <div class="stat-icon-b" style="background:#7c3aed">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#ffffff" fill="none" viewBox="0 0 24 24" stroke="#ffffff"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          </div>
          <span class="stat-trend" style="background:#f3e8ff;color:#6b21a8">Tersinkron</span>
        </div>
        <div class="stat-num-b">{{ number_format($stats['total_users'], 0, ',', '.') }}</div>
        <div class="stat-lbl-b">Pengguna Aktif</div>
        <div class="stat-sub-b">{{ $stats['user_breakdown'] }}</div>
      </div>

      {{-- Total Borrowings --}}
      <div class="stat-block">
        <div class="stat-header">
          <div class="stat-icon-b" style="background:#059669">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#ffffff" fill="none" viewBox="0 0 24 24" stroke="#ffffff"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
          </div>
          <span class="stat-trend" style="background:#d1fae5;color:#065f46">{{ $stats['completion_rate'] }}% Selesai</span>
        </div>
        <div class="stat-num-b">{{ number_format($stats['total_borrowings'], 0, ',', '.') }}</div>
        <div class="stat-lbl-b">Sirkulasi Peminjaman</div>
        <div class="stat-sub-b">Proses approval cepat</div>
      </div>
    </div>
  </div>
</section>

{{-- ═══════════════ TENTANG ═══════════════ --}}
<section class="section" id="tentang">
  <div class="section-inner">
    <div class="about-grid">
      <div>
        <div class="section-eyebrow"><span class="section-eyebrow-dot"></span>Tentang Kami</div>
        <h2 class="section-h2" style="text-align:left;max-width:none">Sistem Inventaris untuk<br><em>Sekolah Modern</em></h2>
        <p style="font-size:16px;color:var(--muted);line-height:1.75;margin-top:12px;max-width:460px">SIPBAR adalah sistem berbasis web yang dirancang untuk membantu sekolah mengelola data inventaris secara digital, efisien, dan transparan.</p>
        <ul class="check-list">
          @foreach(['Mudah digunakan oleh semua pengguna','Sistem aman dan data terjaga','Dukungan penuh untuk kebutuhan sekolah','Akses fleksibel kapan saja dan di mana saja'] as $p)
          <li>
            <div class="check-dot">
              <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            </div>
            {{ $p }}
          </li>
          @endforeach
        </ul>
        <a href="{{ route('register') }}" class="btn-primary" style="display:inline-flex;padding:13px 28px;font-size:14px">
          Mulai Sekarang
          <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
      </div>
      <div class="about-visual">
        <div class="about-img-wrap">
          <div class="about-sky"></div>
          <div class="about-cloud" style="top:16px;left:30px;width:70px;height:22px"></div>
          <div class="about-cloud" style="top:22px;left:54px;width:54px;height:16px;opacity:.7"></div>
          <div class="about-cloud" style="top:12px;right:36px;width:86px;height:26px;opacity:.85"></div>
          <div class="about-cloud" style="top:28px;right:60px;width:56px;height:18px;opacity:.6"></div>
          <div class="about-building">
            <div class="about-building-body">
              <div class="about-building-roof"></div>
              <div class="about-win-grid">@for($i=0;$i<14;$i++)<div class="about-win"></div>@endfor</div>
              <div class="about-door"></div>
            </div>
          </div>
          <div class="about-grass"></div>
          <div class="quote-float">
            <div class="quote-q">"</div>
            <div class="quote-text">Wujudkan sekolah yang lebih terorganisir dengan sistem inventaris modern.</div>
            <div class="quote-author">— SIPBAR</div>
          </div>
          <div class="about-badge">
            <div class="about-badge-icon">
              <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
              <div class="about-badge-val">99%</div>
              <div class="about-badge-lbl">Akurasi Data</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ═══════════════ FOOTER ═══════════════ --}}
<footer class="footer" id="kontak">
  <div class="footer-inner">
    <div class="footer-grid">
      <div>
        <div class="footer-logo-wrap">
          <div class="footer-logo-box"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 42" style="width:18px;height:18px;fill:#fff"><path fill-rule="evenodd" clip-rule="evenodd" d="M17.2 5.633 8.6.855 0 5.633v26.51l16.2 9 16.2-9v-8.442Z"/></svg></div>
          <div><div class="footer-brand-name">SIPBAR</div><div class="footer-brand-sub">Sistem Inventaris</div></div>
        </div>
        <p class="footer-desc">Sistem inventaris berbasis web yang lebih efektif, efisien, dan transparan untuk sekolah.</p>
        <div class="social-row">
          @foreach(['M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z','M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5','M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z'] as $i)
          <a href="#" class="social-btn"><svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $i }}"/></svg></a>
          @endforeach
        </div>
      </div>
      <div>
        <div class="footer-heading">Menu</div>
        <ul class="footer-list">@foreach(['Beranda','Kategori','Fitur','Tentang','Kontak'] as $m)<li><a href="#">{{ $m }}</a></li>@endforeach</ul>
      </div>
      <div>
        <div class="footer-heading">Fitur</div>
        <ul class="footer-list">@foreach(['Manajemen Barang','Peminjaman','Pengembalian','Laporan','Pengguna'] as $f)<li><a href="#">{{ $f }}</a></li>@endforeach</ul>
      </div>
      <div>
        <div class="footer-heading">Bantuan</div>
        <ul class="footer-list">@foreach(['Panduan Penggunaan','FAQ','Kebijakan Privasi','Syarat & Ketentuan'] as $b)<li><a href="#">{{ $b }}</a></li>@endforeach</ul>
      </div>
      <div>
        <div class="footer-heading">Kontak</div>
        <div class="footer-contact"><svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>0812-3456-7890</div>
        <div class="footer-contact"><svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>info@sipbar.sch.id</div>
        <div class="footer-contact"><svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg><span>Jl. Pendidikan No. 10,<br>Kota Pendidikan, Indonesia</span></div>
      </div>
    </div>
    <hr class="footer-divider">
    <p class="footer-copy">&copy; {{ date('Y') }} SIPBAR – Sistem Informasi Pengelolaan Barang. All rights reserved.</p>
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
  var btn = document.getElementById('themeToggle');
  var html = document.documentElement;

  // ─── Toggle function ───
  function toggleTheme(){
    var isDark = html.classList.toggle('dark');
    localStorage.setItem('sipbar-theme', isDark ? 'dark' : 'light');
    // Animate button
    btn.style.transform = 'rotate(20deg) scale(0.85)';
    setTimeout(function(){ btn.style.transform = ''; }, 250);
  }

  if(btn) btn.addEventListener('click', toggleTheme);

  // ─── Keyboard shortcut: Alt + D ───
  document.addEventListener('keydown', function(e){
    if(e.altKey && e.key === 'd') toggleTheme();
  });

  // ─── Close mobile nav on resize ───
  window.addEventListener('resize', function(){
    if(window.innerWidth > 768)
      document.getElementById('navMob').classList.remove('open');
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
  }, {rootMargin:'-40% 0px -55% 0px'});
  sections.forEach(function(s){ observer.observe(s); });
});
</script>

{{-- Prevent theme flash on load --}}
<script>
  // Already applied above in IIFE, this ensures btn icon state is correct
  if(document.documentElement.classList.contains('dark')){
    document.getElementById('themeToggle') && (document.getElementById('themeToggle').title = 'Mode Terang');
  }
</script>
</body>
</html>
