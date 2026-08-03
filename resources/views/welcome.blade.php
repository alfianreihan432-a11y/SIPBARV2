
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIPBAR – Sistem Informasi Pengelolaan Barang</title>
@vite(['resources/css/app.css','resources/js/app.js'])
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth;font-family:'Instrument Sans',ui-sans-serif,system-ui,sans-serif}
body{background:#fff;color:#0f172a;-webkit-font-smoothing:antialiased;overflow-x:hidden}

/* ─── TOKENS ─── */
:root{
  --blue:#2563eb;--blue-dark:#1d4ed8;--blue-light:#3b82f6;
  --indigo:#4f46e5;--cyan:#06b6d4;
  --surface:#f8fafc;--border:#e2e8f0;
  --text:#0f172a;--muted:#64748b;--subtle:#94a3b8;
}

/* ─── NAVBAR ─── */
.nav{position:sticky;top:0;z-index:100;background:rgba(255,255,255,.85);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);border-bottom:1px solid rgba(226,232,240,.6)}
.nav-inner{max-width:1200px;margin:0 auto;padding:0 24px;height:64px;display:flex;align-items:center;gap:40px}
.nav-logo{display:flex;align-items:center;gap:10px;text-decoration:none;flex-shrink:0}
.nav-logo-box{width:36px;height:36px;background:linear-gradient(135deg,var(--blue-dark),var(--cyan));border-radius:10px;display:flex;align-items:center;justify-content:center}
.nav-logo-name{font-size:15px;font-weight:800;color:var(--text)}
.nav-logo-sub{font-size:10px;color:var(--muted)}
.nav-links{display:flex;align-items:center;gap:4px;margin-left:8px}
.nav-links a{padding:6px 14px;font-size:14px;font-weight:500;color:var(--muted);text-decoration:none;border-radius:8px;transition:all .15s}
.nav-links a:hover{background:#f1f5f9;color:var(--text)}
.nav-links a.active{color:var(--blue);font-weight:600}
.nav-actions{display:flex;align-items:center;gap:8px;margin-left:auto}
.btn-ghost{display:inline-flex;align-items:center;gap:7px;padding:8px 18px;font-size:13px;font-weight:600;color:var(--text);border:1.5px solid var(--border);border-radius:10px;text-decoration:none;background:#fff;transition:all .15s}
.btn-ghost:hover{border-color:#94a3b8;background:#f8fafc}
.btn-primary{display:inline-flex;align-items:center;gap:7px;padding:8px 20px;font-size:13px;font-weight:700;color:#fff;background:var(--blue-dark);border-radius:10px;text-decoration:none;border:1.5px solid transparent;box-shadow:0 4px 14px rgba(29,78,216,.3);transition:all .15s}
.btn-primary:hover{background:#1e40af;box-shadow:0 6px 20px rgba(29,78,216,.4);transform:translateY(-1px)}
.nav-ham{display:none;background:none;border:none;cursor:pointer;color:var(--muted);padding:6px;border-radius:8px}
.nav-ham:hover{background:#f1f5f9}
.nav-mobile{display:none;padding:12px 24px 18px;border-top:1px solid var(--border);flex-direction:column;gap:4px}
.nav-mobile.open{display:flex}
.nav-mobile a{padding:10px 12px;font-size:14px;font-weight:500;color:var(--muted);text-decoration:none;border-radius:8px}
.nav-mobile a:hover{background:#f1f5f9;color:var(--text)}
.nav-mobile-actions{display:flex;gap:8px;margin-top:8px}
.nav-mobile-actions a{flex:1;text-align:center;padding:10px;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none}
</style>
<style>
/* ─── HERO ─── */
.hero{position:relative;background:linear-gradient(135deg,#0f172a 0%,#1e1b4b 35%,#1d4ed8 70%,#0284c7 100%);overflow:hidden;padding:90px 24px 0}
.hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 80% 50% at 60% 40%,rgba(99,102,241,.25) 0%,transparent 60%)}
.hero-blob{position:absolute;border-radius:50%;filter:blur(72px);pointer-events:none}
.hero-blob-1{width:500px;height:500px;background:rgba(37,99,235,.35);top:-120px;right:5%;animation:float1 8s ease-in-out infinite}
.hero-blob-2{width:360px;height:360px;background:rgba(6,182,212,.25);bottom:-60px;left:10%;animation:float2 10s ease-in-out infinite}
.hero-blob-3{width:200px;height:200px;background:rgba(139,92,246,.3);top:40%;left:30%;animation:float1 12s ease-in-out infinite reverse}
@keyframes float1{0%,100%{transform:translateY(0)}50%{transform:translateY(-28px)}}
@keyframes float2{0%,100%{transform:translateY(0)}50%{transform:translateY(22px)}}
.hero-inner{max-width:1200px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center;position:relative;z-index:2}
.hero-badge{display:inline-flex;align-items:center;gap:8px;padding:6px 16px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:999px;font-size:12px;font-weight:600;color:rgba(255,255,255,.85);margin-bottom:24px;backdrop-filter:blur(8px)}
.hero-badge-pulse{width:8px;height:8px;background:#4ade80;border-radius:50%;box-shadow:0 0 0 0 rgba(74,222,128,.5);animation:pulse-ring 2s infinite}
@keyframes pulse-ring{0%{box-shadow:0 0 0 0 rgba(74,222,128,.5)}70%{box-shadow:0 0 0 8px rgba(74,222,128,0)}100%{box-shadow:0 0 0 0 rgba(74,222,128,0)}}
.hero-h1{font-size:52px;font-weight:900;line-height:1.1;letter-spacing:-.02em;color:#fff;margin-bottom:20px}
.hero-h1 em{font-style:normal;background:linear-gradient(90deg,#93c5fd,#67e8f9);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.hero-p{font-size:17px;color:rgba(255,255,255,.65);line-height:1.75;margin-bottom:36px;max-width:440px}
.hero-btns{display:flex;gap:14px;flex-wrap:wrap;margin-bottom:52px}
.hero-btn-main{display:inline-flex;align-items:center;gap:8px;padding:14px 28px;background:#fff;color:#1d4ed8;font-size:15px;font-weight:700;border-radius:12px;text-decoration:none;box-shadow:0 8px 24px rgba(0,0,0,.2);transition:all .2s}
.hero-btn-main:hover{transform:translateY(-2px);box-shadow:0 12px 32px rgba(0,0,0,.25)}
.hero-btn-alt{display:inline-flex;align-items:center;gap:8px;padding:14px 24px;background:rgba(255,255,255,.1);color:#fff;font-size:15px;font-weight:600;border-radius:12px;border:1.5px solid rgba(255,255,255,.25);text-decoration:none;backdrop-filter:blur(8px);transition:all .2s}
.hero-btn-alt:hover{background:rgba(255,255,255,.18)}
.hero-trust{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
.trust-item{display:flex;flex-direction:column;align-items:center;text-align:center;gap:8px}
.trust-icon{width:44px;height:44px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(8px)}
.trust-label{font-size:12px;font-weight:700;color:#fff}
.trust-sub{font-size:11px;color:rgba(255,255,255,.5);line-height:1.4}
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
.mc-sidebar{width:90px;background:rgba(15,23,42,.5);padding:10px 6px;display:flex;flex-direction:column;gap:2px}
.mc-menu{padding:7px 8px;border-radius:6px;font-size:9px;color:rgba(255,255,255,.5);display:flex;align-items:center;gap:6px;cursor:default}
.mc-menu.act{background:var(--blue);color:#fff}
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
.mc-avatar{width:18px;height:18px;background:rgba(37,99,235,.5);border-radius:4px}
.mc-name{font-size:9px;color:rgba(255,255,255,.7);font-weight:600}
.mc-cat{font-size:8px;color:rgba(255,255,255,.35)}
.mc-pill{font-size:8px;padding:2px 6px;border-radius:999px;font-weight:600}
.pill-green{background:rgba(52,211,153,.15);color:#34d399}
.pill-red{background:rgba(248,113,113,.15);color:#f87171}
/* Hero bottom wave */
.hero-wave{display:block;width:100%;height:70px;margin-bottom:-2px}
</style>
<style>
/* ─── SECTIONS ─── */
.section{padding:96px 24px}
.section-inner{max-width:1200px;margin:0 auto}
.section-eyebrow{display:inline-flex;align-items:center;gap:6px;font-size:12px;font-weight:700;color:var(--blue);letter-spacing:.1em;text-transform:uppercase;margin-bottom:12px}
.section-eyebrow-dot{width:6px;height:6px;background:var(--blue);border-radius:50%}
.section-h2{font-size:40px;font-weight:900;line-height:1.15;letter-spacing:-.02em;color:var(--text);margin-bottom:14px}
.section-h2 em{font-style:normal;color:var(--blue)}
.section-lead{font-size:17px;color:var(--muted);line-height:1.7;max-width:540px;margin:0 auto}
.section-head{text-align:center;margin-bottom:60px}
/* ─── CATEGORIES ─── */
.cat-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:16px}
.cat-card{background:#fff;border:2px solid var(--border);border-radius:20px;padding:28px 18px 22px;text-align:center;cursor:pointer;transition:all .25s;position:relative;overflow:hidden}
.cat-card::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,var(--blue),var(--cyan));opacity:0;transition:opacity .25s}
.cat-card:hover{border-color:var(--blue);transform:translateY(-4px);box-shadow:0 12px 32px rgba(37,99,235,.15)}
.cat-card:hover::before{opacity:.04}
.cat-icon-wrap{width:56px;height:56px;background:linear-gradient(135deg,#eff6ff,#dbeafe);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;transition:all .25s}
.cat-card:hover .cat-icon-wrap{background:linear-gradient(135deg,var(--blue),var(--cyan));box-shadow:0 8px 20px rgba(37,99,235,.3)}
.cat-card:hover .cat-icon-wrap svg{color:#fff !important}
.cat-name{font-size:13px;font-weight:700;color:var(--text);margin-bottom:6px;transition:color .2s}
.cat-desc{font-size:11px;color:var(--subtle);line-height:1.5;margin-bottom:14px}
.cat-link{font-size:12px;font-weight:700;color:var(--blue);text-decoration:none;display:inline-flex;align-items:center;gap:4px;transition:gap .15s}
.cat-link:hover{gap:8px}
/* ─── FEATURES ─── */
.feat-bg{background:var(--surface)}
.feat-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:18px}
.feat-card{background:#fff;border:1px solid var(--border);border-radius:18px;padding:28px 22px;text-align:center;transition:all .25s}
.feat-card:hover{transform:translateY(-6px);box-shadow:0 20px 48px rgba(37,99,235,.1);border-color:rgba(37,99,235,.2)}
.feat-icon-wrap{width:56px;height:56px;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px}
.feat-name{font-size:14px;font-weight:700;color:var(--text);margin-bottom:8px}
.feat-desc{font-size:13px;color:var(--muted);line-height:1.65}
/* ─── STATS ─── */
.stats-bg{background:linear-gradient(135deg,#0f172a 0%,#1e1b4b 50%,#1d4ed8 100%);position:relative;overflow:hidden}
.stats-bg::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 80% at 80% 50%,rgba(6,182,212,.2),transparent 60%)}
.stats-inner{max-width:1200px;margin:0 auto;position:relative;z-index:1;display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center}
.stats-left{}
.stats-eyebrow{font-size:12px;font-weight:700;color:#93c5fd;letter-spacing:.1em;text-transform:uppercase;margin-bottom:12px}
.stats-h2{font-size:36px;font-weight:900;color:#fff;line-height:1.2;margin-bottom:14px}
.stats-p{font-size:15px;color:rgba(255,255,255,.55);line-height:1.7}
.stats-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:20px}
.stat-block{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:16px;padding:24px;backdrop-filter:blur(8px);transition:all .2s}
.stat-block:hover{background:rgba(255,255,255,.1);transform:translateY(-2px)}
.stat-icon-b{width:40px;height:40px;border-radius:10px;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;margin-bottom:14px}
.stat-num-b{font-size:32px;font-weight:900;color:#fff;line-height:1}
.stat-lbl-b{font-size:13px;color:rgba(255,255,255,.5);margin-top:6px}
</style>
<style>
/* ─── ABOUT ─── */
.about-grid{display:grid;grid-template-columns:1fr 1fr;gap:72px;align-items:center}
.about-visual{position:relative}
.about-img-wrap{background:linear-gradient(135deg,#dbeafe 0%,#bfdbfe 50%,#a5f3fc 100%);border-radius:24px;overflow:hidden;height:380px;position:relative;box-shadow:0 24px 64px rgba(37,99,235,.15)}
.about-sky{position:absolute;top:0;left:0;right:0;height:150px;background:linear-gradient(180deg,#7dd3fc,#bfdbfe)}
.about-cloud{position:absolute;background:rgba(255,255,255,.85);border-radius:999px}
.about-building{position:absolute;bottom:60px;left:50%;transform:translateX(-50%);width:78%}
.about-building-body{background:#e2e8f0;border-radius:8px 8px 0 0;padding:14px 10px 0}
.about-building-roof{background:#cbd5e1;height:16px;border-radius:6px 6px 0 0}
.about-win-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:3px;padding:10px 6px 4px}
.about-win{height:26px;background:#93c5fd;border-radius:3px;opacity:.8}
.about-door{width:28px;height:32px;background:#94a3b8;border-radius:4px 4px 0 0;margin:0 auto}
.about-grass{position:absolute;bottom:0;left:0;right:0;height:60px;background:linear-gradient(180deg,#86efac,#4ade80);opacity:.7}
.quote-float{position:absolute;bottom:20px;right:16px;background:#fff;border-radius:14px;padding:16px;max-width:210px;box-shadow:0 12px 32px rgba(0,0,0,.12)}
.quote-q{font-size:30px;font-family:Georgia,serif;color:var(--blue);line-height:1}
.quote-text{font-size:12px;color:var(--muted);font-style:italic;line-height:1.5}
.quote-author{font-size:12px;font-weight:700;color:var(--blue);margin-top:8px}
.about-badge{position:absolute;top:20px;left:16px;background:#fff;border-radius:12px;padding:12px 16px;box-shadow:0 8px 24px rgba(0,0,0,.1);display:flex;align-items:center;gap:10px}
.about-badge-icon{width:36px;height:36px;background:linear-gradient(135deg,var(--blue),var(--cyan));border-radius:10px;display:flex;align-items:center;justify-content:center}
.about-badge-val{font-size:18px;font-weight:900;color:var(--text)}
.about-badge-lbl{font-size:11px;color:var(--muted)}
.check-list{list-style:none;display:flex;flex-direction:column;gap:14px;margin:24px 0 36px}
.check-list li{display:flex;align-items:flex-start;gap:12px;font-size:15px;color:var(--muted);line-height:1.5}
.check-dot{width:22px;height:22px;background:linear-gradient(135deg,var(--blue),var(--cyan));border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;box-shadow:0 4px 10px rgba(37,99,235,.3)}
/* ─── CTA ─── */
.cta-wrap{background:linear-gradient(135deg,#1e3a8a,#1d4ed8,#0284c7);padding:80px 24px;position:relative;overflow:hidden}
.cta-wrap::before{content:'';position:absolute;top:-80px;right:-80px;width:350px;height:350px;background:rgba(255,255,255,.04);border-radius:50%}
.cta-wrap::after{content:'';position:absolute;bottom:-60px;left:-40px;width:280px;height:280px;background:rgba(255,255,255,.04);border-radius:50%}
.cta-inner{max-width:1200px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;gap:32px;flex-wrap:wrap;position:relative;z-index:1}
.cta-badge-wrap{display:flex;align-items:center;gap:20px}
.cta-icon-box{width:60px;height:60px;background:rgba(255,255,255,.15);border-radius:18px;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid rgba(255,255,255,.2)}
.cta-h3{font-size:28px;font-weight:900;color:#fff;line-height:1.25}
.cta-p{font-size:15px;color:rgba(255,255,255,.65);margin-top:6px}
.cta-btn{display:inline-flex;align-items:center;gap:10px;padding:16px 36px;background:#fff;color:#1d4ed8;font-size:15px;font-weight:800;border-radius:14px;text-decoration:none;box-shadow:0 8px 24px rgba(0,0,0,.2);transition:all .2s;white-space:nowrap;flex-shrink:0}
.cta-btn:hover{transform:translateY(-2px);box-shadow:0 12px 32px rgba(0,0,0,.25)}
/* ─── FOOTER ─── */
.footer{background:#0a0f1e;color:#94a3b8;padding:72px 24px 32px}
.footer-inner{max-width:1200px;margin:0 auto}
.footer-grid{display:grid;grid-template-columns:2.2fr 1fr 1fr 1fr 1.4fr;gap:44px;margin-bottom:56px}
.footer-brand-name{font-size:15px;font-weight:800;color:#f1f5f9}
.footer-brand-sub{font-size:11px;color:#334155}
.footer-desc{font-size:13px;line-height:1.75;margin-bottom:20px;color:#475569;max-width:260px}
.footer-logo-wrap{display:flex;align-items:center;gap:10px;margin-bottom:16px}
.footer-logo-box{width:36px;height:36px;background:linear-gradient(135deg,var(--blue-dark),var(--cyan));border-radius:10px;display:flex;align-items:center;justify-content:center}
.social-row{display:flex;gap:8px}
.social-btn{width:32px;height:32px;background:#1e293b;border-radius:8px;display:flex;align-items:center;justify-content:center;transition:background .2s;color:#64748b}
.social-btn:hover{background:var(--blue);color:#fff}
.footer-heading{font-size:13px;font-weight:800;color:#e2e8f0;margin-bottom:18px;letter-spacing:.03em}
.footer-list{list-style:none;display:flex;flex-direction:column;gap:10px}
.footer-list a{font-size:13px;color:#475569;text-decoration:none;transition:color .15s;display:flex;align-items:center;gap:6px}
.footer-list a:hover{color:#e2e8f0}
.footer-contact{font-size:13px;color:#475569;display:flex;align-items:flex-start;gap:8px;margin-bottom:10px}
.footer-contact svg{color:#3b82f6;flex-shrink:0;margin-top:2px}
.footer-divider{border:none;border-top:1px solid #1e293b;margin-bottom:24px}
.footer-copy{text-align:center;font-size:12px;color:#334155}
/* ─── RESPONSIVE ─── */
@media(max-width:1024px){.hero-inner,.stats-inner,.about-grid{grid-template-columns:1fr}.hero{padding-bottom:60px}.hero-trust{grid-template-columns:repeat(2,1fr)}.cat-grid{grid-template-columns:repeat(3,1fr)}.feat-grid{grid-template-columns:repeat(3,1fr)}.footer-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:768px){.nav-links,.nav-actions{display:none}.nav-ham{display:block}.hero-h1{font-size:36px}.cat-grid{grid-template-columns:repeat(2,1fr)}.feat-grid{grid-template-columns:repeat(2,1fr)}.stats-grid{grid-template-columns:repeat(2,1fr)}.section-h2{font-size:28px}.cta-inner{flex-direction:column;text-align:center}}
@media(max-width:480px){.cat-grid,.feat-grid{grid-template-columns:1fr}.hero-h1{font-size:30px}.footer-grid{grid-template-columns:1fr}}
</style>
</head>
<body>

{{-- ═══════════════ NAVBAR ═══════════════ --}}
<header>
<nav class="nav">
  <div class="nav-inner">
    <a href="{{ route('home') }}" class="nav-logo">
      <div class="nav-logo-box">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 42" style="width:18px;height:18px;fill:#fff"><path fill-rule="evenodd" clip-rule="evenodd" d="M17.2 5.633 8.6.855 0 5.633v26.51l16.2 9 16.2-9v-8.442l7.6-4.223V9.856l-8.6-4.777-8.6 4.777V18.3l-5.6 3.111V5.633Z"/></svg>
      </div>
      <div>
        <div class="nav-logo-name">SIPBAR</div>
        <div class="nav-logo-sub">Sistem Inventaris</div>
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
      @auth
        <a href="{{ route('dashboard') }}" class="btn-primary">Dashboard</a>
      @else
        <a href="{{ route('login') }}" class="btn-ghost">
          <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
          Masuk
        </a>
        <a href="{{ route('register') }}" class="btn-primary">Daftar Gratis</a>
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
      <a href="{{ route('login') }}" style="background:#f1f5f9;color:#334155">Masuk</a>
      <a href="{{ route('register') }}" style="background:#1d4ed8;color:#fff">Daftar</a>
    </div>
  </div>
</nav>
</header>

{{-- ═══════════════ HERO ═══════════════ --}}
<section class="hero" id="beranda">
  <div class="hero-blob hero-blob-1"></div>
  <div class="hero-blob hero-blob-2"></div>
  <div class="hero-blob hero-blob-3"></div>
  <div class="hero-inner">
    <div>
      <div class="hero-badge"><span class="hero-badge-pulse"></span>Sistem Inventaris Modern</div>
      <h1 class="hero-h1">Kelola Inventaris<br><em>Lebih Mudah,</em><br>Cepat &amp; Terorganisir</h1>
      <p class="hero-p">Sistem inventaris berbasis web untuk memudahkan pengelolaan barang, peminjaman, dan laporan secara efisien, akurat, dan transparan.</p>
      <div class="hero-btns">
        <a href="{{ route('register') }}" class="hero-btn-main">
          Mulai Sekarang
          <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
        <a href="#fitur" class="hero-btn-alt">
          <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          Lihat Demo
        </a>
      </div>
      <div class="hero-trust">
        @foreach([
          ['M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z','Aman & Terpercaya','Data terenkripsi'],
          ['M13 10V3L4 14h7v7l9-11h-7z','Efisien','Proses lebih cepat'],
          ['M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z','Laporan Akurat','Data real-time'],
          ['M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z','Berbasis Cloud','Akses kapan saja'],
        ] as $t)
        <div class="trust-item">
          <div class="trust-icon">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#93c5fd" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $t[0] }}"/></svg>
          </div>
          <div class="trust-label">{{ $t[1] }}</div>
          <div class="trust-sub">{{ $t[2] }}</div>
        </div>
        @endforeach
      </div>
    </div>
    {{-- Dashboard Mockup --}}
    <div class="hero-mockup">
      <div class="mockup-glow"></div>
      <div class="mockup-card">
        <div class="mc-header">
          <div class="mc-dots">
            <div class="mc-dot" style="background:#ff5f56"></div>
            <div class="mc-dot" style="background:#ffbd2e"></div>
            <div class="mc-dot" style="background:#27c93f"></div>
          </div>
          <div style="font-size:10px;color:rgba(255,255,255,.4)">SIPBAR Dashboard</div>
          <div style="width:22px;height:22px;border-radius:50%;background:rgba(37,99,235,.5)"></div>
        </div>
        <div class="mc-body">
          <div class="mc-sidebar">
            @foreach(['Dashboard','Barang','Kategori','Peminjaman','Pengembalian','Laporan','Pengguna','Pengajuan'] as $m)
            <div class="mc-menu {{ $m==='Dashboard'?'act':'' }}">
              <div class="mc-menu-dot"></div>{{ $m }}
            </div>
            @endforeach
          </div>
          <div class="mc-content">
            <div style="font-size:9px;color:rgba(255,255,255,.5);margin-bottom:8px">Selamat datang, Admin! &nbsp;·&nbsp; Kelola inventaris dengan mudah.</div>
            <div class="mc-stats">
              <div class="mc-stat"><div class="mc-stat-label">Total Barang</div><div class="mc-stat-val">1.256</div><div class="mc-stat-chg" style="color:#34d399">+17%</div></div>
              <div class="mc-stat"><div class="mc-stat-label">Tersedia</div><div class="mc-stat-val">892</div><div class="mc-stat-chg" style="color:#f87171">-8%</div></div>
              <div class="mc-stat"><div class="mc-stat-label">Dipinjam</div><div class="mc-stat-val">234</div><div class="mc-stat-chg" style="color:#f87171">-1%</div></div>
              <div class="mc-stat"><div class="mc-stat-label">Kategori</div><div class="mc-stat-val">24</div><div class="mc-stat-chg" style="color:rgba(255,255,255,.4)">Total</div></div>
            </div>
            <div class="mc-chart">
              <div class="mc-chart-label">Grafik Peminjaman – 6 Bulan</div>
              <svg viewBox="0 0 240 50" style="width:100%;height:44px">
                <defs><linearGradient id="cg" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#2563eb" stop-opacity=".3"/><stop offset="100%" stop-color="#2563eb" stop-opacity="0"/></linearGradient></defs>
                <polygon fill="url(#cg)" points="0,44 40,34 80,38 120,16 160,24 200,10 240,16 240,50 0,50"/>
                <polyline fill="none" stroke="#3b82f6" stroke-width="2" stroke-linejoin="round" points="0,44 40,34 80,38 120,16 160,24 200,10 240,16"/>
                @foreach([[0,44],[40,34],[80,38],[120,16],[160,24],[200,10],[240,16]] as $p)
                <circle cx="{{ $p[0] }}" cy="{{ $p[1] }}" r="2.5" fill="#3b82f6" stroke="#0f172a" stroke-width="1.5"/>
                @endforeach
              </svg>
            </div>
            <div class="mc-items">
              <div class="mc-items-label">Barang Terbaru</div>
              @foreach([['Proyektor Epson','Elektronik','Tersedia',true],['Meja Guru','Furnitur','Tersedia',true],['Bola Basket','Olahraga','Dipinjam',false],['Mikroskop','Laboratorium','Tersedia',true]] as $r)
              <div class="mc-row">
                <div class="mc-row-left"><div class="mc-avatar"></div><div><div class="mc-name">{{ $r[0] }}</div><div class="mc-cat">{{ $r[1] }}</div></div></div>
                <span class="mc-pill {{ $r[3]?'pill-green':'pill-red' }}">{{ $r[2] }}</span>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <svg class="hero-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none"><path fill="#ffffff" d="M0,40 C360,80 1080,0 1440,40 L1440,70 L0,70 Z"/></svg>
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
        ['Manajemen Barang','Kelola data barang dengan mudah — tambah, ubah, hapus, dan lacak stok secara real-time.','M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4','#eff6ff','#2563eb'],
        ['Peminjaman Barang','Catat dan pantau peminjaman barang secara sistematis dengan notifikasi jatuh tempo.','M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4','#ecfdf5','#059669'],
        ['Pengembalian','Proses pengembalian lebih cepat dengan verifikasi kondisi barang yang terstruktur.','M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6','#fffbeb','#d97706'],
        ['Laporan & Statistik','Laporan inventaris lengkap dengan grafik dan statistik penggunaan barang.','M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z','#fdf4ff','#9333ea'],
        ['Manajemen Pengguna','Atur hak akses pengguna (Admin, Guru, Siswa) sesuai peran dan tanggung jawab.','M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z','#fff1f2','#e11d48'],
      ];
      @endphp
      @foreach($feats as $f)
      <div class="feat-card">
        <div class="feat-icon-wrap" style="background:{{ $f[3] }}">
          <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:{{ $f[4] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f[2] }}"/></svg>
        </div>
        <div class="feat-name">{{ $f[0] }}</div>
        <div class="feat-desc">{{ $f[1] }}</div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══════════════ STATS ═══════════════ --}}
<section class="section stats-bg">
  <div class="stats-inner">
    <div class="stats-left">
      <div class="stats-eyebrow">Data Inventaris</div>
      <h2 class="stats-h2">Inventaris Sekolah<br>dalam Angka</h2>
      <p class="stats-p">Kelola ribuan data inventaris dengan sistem yang terintegrasi, terpercaya, dan mudah digunakan.</p>
    </div>
    <div class="stats-grid">
      @foreach([
        ['1.256+','Total Barang Terdata','M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4','#3b82f6'],
        ['24','Kategori Barang','M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z','#06b6d4'],
        ['156+','Pengguna Aktif','M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z','#a78bfa'],
        ['892+','Transaksi Peminjaman','M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4','#34d399'],
      ] as $s)
      <div class="stat-block">
        <div class="stat-icon-b">
          <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:{{ $s[3] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s[2] }}"/></svg>
        </div>
        <div class="stat-num-b">{{ $s[0] }}</div>
        <div class="stat-lbl-b">{{ $s[1] }}</div>
      </div>
      @endforeach
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

{{-- ═══════════════ CTA ═══════════════ --}}
<section class="cta-wrap">
  <div class="cta-inner">
    <div class="cta-badge-wrap">
      <div class="cta-icon-box">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 42" style="width:28px;height:28px;fill:#fff"><path fill-rule="evenodd" clip-rule="evenodd" d="M17.2 5.633 8.6.855 0 5.633v26.51l16.2 9 16.2-9v-8.442l7.6-4.223V9.856l-8.6-4.777-8.6 4.777V18.3l-5.6 3.111V5.633Z"/></svg>
      </div>
      <div>
        <div class="cta-h3">Siap kelola inventaris sekolah<br>dengan lebih baik?</div>
        <div class="cta-p">Bergabung sekarang dan rasakan kemudahan mengelola inventaris Anda.</div>
      </div>
    </div>
    <a href="{{ route('register') }}" class="cta-btn">
      Mulai Sekarang
      <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
    </a>
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
window.addEventListener('resize',()=>{if(window.innerWidth>768)document.getElementById('navMob').classList.remove('open')});
</script>
</body>
</html>
