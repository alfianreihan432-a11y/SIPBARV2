<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FAQ – {{ \App\Models\SiteSetting::get('site_name', 'SIPBAR') }} | Pusat Bantuan & Pertanyaan</title>
<meta name="description" content="Temukan jawaban dan solusi atas kendala seputar peminjaman, persetujuan guru, QR code, dan pengembalian barang di SIPBAR SMKN 1 BANGSRI.">
@include('partials.favicon')
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

:root{
  --primary:#1d4ed8;--primary-hover:#1e40af;--primary-light:#3b82f6;
  --bg:#ffffff;--bg2:#f8fafc;--bg3:#f1f5f9;--surface:#f8fafc;
  --border:#e2e8f0;--border2:#cbd5e1;--card:#ffffff;
  --text:#0f172a;--text2:#1e293b;--muted:#475569;--subtle:#64748b;
  --nav-bg:rgba(255,255,255,.95);--shadow:0 4px 12px rgba(29,78,216,.08);
}

html.dark{
  --primary:#7aa2f7;--primary-hover:#93c5fd;--primary-light:rgba(122,162,247,.15);
  --bg:#0f172a;--bg2:#111827;--bg3:#1e293b;--surface:#1e293b;
  --border:rgba(148,163,184,.16);--border2:rgba(148,163,184,.20);
  --card:#1e293b;
  --text:#f1f5f9;--text2:#a8b3c7;--muted:#8b98ad;--subtle:#8b98ad;
  --nav-bg:rgba(15,23,42,.92);--shadow:0 1px 2px rgba(0,0,0,.3);
  --dark-bg-footer:#0b1220;
  --dark-card-bg:#1e293b;--dark-card-border:rgba(148,163,184,.16);
}

.site-header{position:sticky;top:0;z-index:100}
.nav{
  position:relative;
  background:rgba(255,255,255,.85);
  backdrop-filter:blur(16px);
  -webkit-backdrop-filter:blur(16px);
  border-bottom:1px solid rgba(226,232,240,.8);
  transition:all .3s ease;
}
.nav.scrolled{
  background:rgba(255,255,255,.96);
  border-bottom-color:#e2e8f0;
  box-shadow:0 10px 30px rgba(0,0,0,.06);
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
  width:48px;height:48px;border-radius:50%;
  background:#fff;
  display:flex;align-items:center;justify-content:center;
  flex-shrink:0;
  box-shadow:0 2px 10px rgba(0,0,0,.1),0 0 0 1.5px rgba(0,0,0,.06);
  transition:transform .25s,box-shadow .25s;
  overflow:hidden;
  padding:3px;
}
.nav-brand:hover .nav-logo-wrap{transform:scale(1.04);box-shadow:0 4px 16px rgba(29,78,216,.18)}
.nav-brand-img{width:100%;height:100%;object-fit:contain;border-radius:50%}
.nav-brand-text{display:flex;flex-direction:column;gap:1.5px}
.nav-brand-title{
  font-family:'Plus Jakarta Sans',sans-serif;
  font-size:21px;font-weight:800;
  color:#1d4ed8;letter-spacing:-.02em;line-height:1.15;
}
.nav-brand-subtitle{
  font-size:11px;font-weight:700;
  color:#64748b;letter-spacing:.09em;text-transform:uppercase;line-height:1.2;
}

.nav-links{
  display:flex;align-items:center;gap:6px;
  background:rgba(241,245,249,.7);
  padding:5px 8px;border-radius:14px;
  border:1px solid rgba(226,232,240,.8);
}
.nav-links a{
  position:relative;
  padding:7px 16px;
  font-family:'Plus Jakarta Sans',sans-serif;
  font-size:13.5px;font-weight:600;
  color:#475569;text-decoration:none;
  border-radius:10px;transition:all .2s;
}
.nav-links a:hover{color:#1d4ed8;background:rgba(255,255,255,.9)}
.nav-links a.active{
  color:#1d4ed8;font-weight:700;
  background:#fff;box-shadow:0 2px 8px rgba(29,78,216,.12);
}

.nav-actions{display:flex;align-items:center;gap:12px}
.theme-toggle{
  display:flex;align-items:center;justify-content:center;
  width:44px;height:44px;min-width:44px;min-height:44px;
  border-radius:12px;border:1.5px solid var(--border);
  background:var(--card);cursor:pointer;
  transition:all .25s;flex-shrink:0;color:var(--text);
  box-shadow:0 2px 6px rgba(0,0,0,.03);
}
.theme-toggle:hover{
  border-color:#2563eb;color:#2563eb;background:var(--bg2);
  transform:translateY(-1px);box-shadow:0 6px 16px rgba(37,99,235,.12);
}
.theme-toggle svg{transition:transform .3s,opacity .2s}

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
  display:inline-flex;align-items:center;gap:8px;
  padding:10px 22px;min-height:44px;
  font-family:'Plus Jakarta Sans',sans-serif;
  font-size:13.5px;font-weight:700;
  color:#fff;
  background:linear-gradient(135deg,#1d4ed8 0%,#2563eb 100%);
  border-radius:12px;text-decoration:none;border:none;
  box-shadow:0 4px 14px rgba(29,78,216,.28);transition:all .25s;
}
.btn-nav-cta:hover{transform:translateY(-2px);box-shadow:0 8px 22px rgba(29,78,216,.38);filter:brightness(1.06)}

.nav-mobile-ctrls{display:none;align-items:center;gap:8px}
.nav-ham{
  display:none;align-items:center;justify-content:center;flex-direction:column;
  width:44px;height:44px;min-width:44px;min-height:44px;
  padding:0;border-radius:12px;border:1.5px solid var(--border);
  background:var(--card);color:var(--text);cursor:pointer;
  transition:all .2s;gap:5px;
}
.nav-ham:hover{border-color:#2563eb;color:#2563eb;background:var(--bg2)}
.ham-line{display:block;width:20px;height:2.2px;background:currentColor;border-radius:2px;transition:transform .3s,opacity .2s;transform-origin:center}
.nav-ham.active .ham-line:nth-child(1){transform:translateY(7.2px) rotate(45deg)}
.nav-ham.active .ham-line:nth-child(2){opacity:0;transform:scaleX(0)}
.nav-ham.active .ham-line:nth-child(3){transform:translateY(-7.2px) rotate(-45deg)}

.nav-mobile{
  display:none;padding:16px 20px 20px;
  border-top:1px solid var(--border);flex-direction:column;gap:12px;
  background:var(--nav-bg);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);
  box-shadow:0 16px 36px rgba(0,0,0,.12);animation:slideDownNav .25s;
}
@keyframes slideDownNav{from{opacity:0;transform:translateY(-10px)}to{opacity:1;transform:translateY(0)}}
.nav-mobile.open{display:flex}
.nav-mobile-links{display:flex;flex-direction:column;gap:6px}
.nav-mobile-links a{
  display:flex;align-items:center;gap:10px;
  padding:11px 16px;min-height:44px;
  font-family:'Plus Jakarta Sans',sans-serif;
  font-size:14px;font-weight:600;
  color:var(--text);text-decoration:none;
  border-radius:10px;background:var(--bg3);transition:all .15s;
}
.nav-mobile-links a:hover{background:rgba(37,99,235,.12);color:#1d4ed8}
.nav-mob-divider{height:1px;background:var(--border);margin:2px 0}
.nav-mob-theme-row{display:flex;align-items:center;justify-content:space-between;padding:11px 16px;border-radius:10px;background:var(--bg3);border:1px solid var(--border);min-height:44px}
.nav-mob-theme-info{display:flex;align-items:center;gap:10px;font-size:13px;font-weight:700;color:var(--text)}
.nav-mob-theme-btn{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border-radius:8px;border:1px solid var(--border);background:var(--card);color:var(--text);font-size:12px;font-weight:700;cursor:pointer;transition:all .2s}
.nav-mob-login{display:flex;align-items:center;justify-content:center;gap:8px;padding:12px;min-height:46px;border-radius:12px;font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:700;text-decoration:none;background:linear-gradient(135deg,#1d4ed8 0%,#2563eb 100%);color:#fff;box-shadow:0 4px 14px rgba(29,78,216,.25);transition:all .2s}

.icon-sun{display:none}.icon-moon{display:block}
html.dark .icon-sun{display:block}html.dark .icon-moon{display:none}
.mob-t-sun{display:none}.mob-t-moon{display:inline-flex;align-items:center;gap:4px}
html.dark .mob-t-sun{display:inline-flex;align-items:center;gap:4px}html.dark .mob-t-moon{display:none}

/* ── FAQ PAGE STYLES ── */
.faq-page{min-height:80vh;background:var(--bg);padding:64px 24px 88px}
.faq-inner{max-width:860px;margin:0 auto}
.faq-hero{text-align:center;margin-bottom:48px}
.faq-eyebrow{display:inline-flex;align-items:center;gap:8px;font-size:11px;font-weight:700;color:#1d4ed8;letter-spacing:.1em;text-transform:uppercase;margin-bottom:16px;background:rgba(29,78,216,.08);padding:6px 16px;border-radius:999px;border:1px solid rgba(29,78,216,.15)}
.faq-eyebrow-dot{width:6px;height:6px;border-radius:50%;background:#1d4ed8}
.faq-h1{font-family:'Plus Jakarta Sans',sans-serif;font-size:38px;font-weight:800;line-height:1.2;letter-spacing:-.02em;color:var(--text);margin-bottom:14px}
.faq-h1 em{font-style:normal;color:#1d4ed8}
.faq-lead{font-size:16px;color:var(--muted);line-height:1.65;max-width:580px;margin:0 auto 32px}
.faq-search-wrap{position:relative;max-width:560px;margin:0 auto}
.faq-search-icon{position:absolute;left:16px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;width:20px;height:20px}
#faqSearch{width:100%;padding:14px 18px 14px 48px;font-size:15px;font-family:'Inter',sans-serif;color:var(--text);background:var(--card);border:1.5px solid var(--border2);border-radius:14px;outline:none;transition:border-color .2s,box-shadow .2s;box-shadow:0 2px 8px rgba(0,0,0,.04)}
#faqSearch::placeholder{color:var(--subtle)}
#faqSearch:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.15)}

.faq-categories{display:flex;flex-direction:column;gap:36px;margin-top:40px}
.faq-cat-block{transition:opacity .2s}
.faq-cat-title{font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:#1d4ed8;margin-bottom:16px;padding-left:2px;display:flex;align-items:center;gap:10px}
.faq-cat-title::before{content:'';display:block;width:4px;height:18px;background:#1d4ed8;border-radius:2px;flex-shrink:0}
.faq-list{display:flex;flex-direction:column;gap:12px}
.faq-item{background:var(--card);border:1.5px solid var(--border);border-radius:14px;overflow:hidden;transition:border-color .2s,box-shadow .2s}
.faq-item:hover{border-color:rgba(37,99,235,.5)}
.faq-item.faq-open{border-color:#2563eb;box-shadow:0 6px 20px rgba(37,99,235,.08)}
.faq-question{width:100%;padding:18px 22px;display:flex;align-items:center;justify-content:space-between;gap:16px;background:none;border:none;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;font-size:15px;font-weight:700;color:var(--text);text-align:left;transition:background .15s}
.faq-question:hover{background:rgba(37,99,235,.04)}
.faq-item.faq-open .faq-question{color:#1d4ed8;background:rgba(37,99,235,.05)}
.faq-q-icon{width:28px;height:28px;border-radius:8px;background:rgba(37,99,235,.08);border:1px solid rgba(37,99,235,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#2563eb;transition:transform .25s ease,background .25s ease,color .25s ease}
.faq-item.faq-open .faq-q-icon{background:#2563eb;color:#fff;border-color:#2563eb;transform:rotate(180deg)}
.faq-answer{display:none;padding:0 22px 20px;border-top:1px solid var(--border);animation:fadeSlideIn .2s ease}
.faq-item.faq-open .faq-answer{display:block}
@keyframes fadeSlideIn{from{opacity:0;transform:translateY(-4px)}to{opacity:1;transform:translateY(0)}}
.faq-steps{margin:14px 0 0;display:flex;flex-direction:column;gap:10px}
.faq-step{display:flex;align-items:flex-start;gap:12px;font-size:14px;color:var(--text2);line-height:1.6}
.faq-step-num{width:22px;height:22px;border-radius:6px;background:#1d4ed8;color:#fff;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;flex-shrink:0;margin-top:2px}
.faq-note{margin-top:14px;padding:12px 16px;background:rgba(37,99,235,.06);border:1px solid rgba(37,99,235,.15);border-radius:10px;font-size:13px;color:var(--muted);line-height:1.6}

#faqNotFound{display:none;text-align:center;padding:56px 24px;color:var(--muted)}
#faqNotFound svg{margin:0 auto 16px;opacity:.5}
#faqNotFound p{font-size:16px;font-weight:700;color:var(--text);margin-bottom:6px}
#faqNotFound span{font-size:13.5px}

/* ── FOOTER ── */
.footer{background:#fff;color:#475569;padding:72px 24px 32px;border-top:1px solid #e2e8f0;position:relative}
.footer-inner{max-width:1200px;margin:0 auto}
.footer-grid{display:grid;grid-template-columns:1.8fr 1fr;gap:48px 60px;margin-bottom:48px;align-items:start}
.footer-brand{display:flex;flex-direction:column;align-items:flex-start}
.footer-logo-wrap{display:flex;align-items:center;gap:12px;margin-bottom:16px}
.footer-logo-box{width:46px;height:46px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;overflow:hidden;padding:4px;box-shadow:0 2px 10px rgba(0,0,0,.08);border:1px solid #e2e8f0;flex-shrink:0}
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
.footer-list a{font-size:13.5px;color:#64748b;text-decoration:none;transition:all .2s;display:inline-flex;align-items:center;font-weight:500}
.footer-list a:hover{color:#1d4ed8;transform:translateX(4px)}
.footer-divider{border:none;border-top:1px solid #e2e8f0;margin-bottom:24px}
.footer-bottom{display:flex;align-items:center;justify-content:center;flex-wrap:wrap;gap:12px}
.footer-copy{text-align:center;font-size:12.5px;color:#64748b;font-weight:400;margin:0}

@media(max-width:768px){
  .nav-inner{height:68px;padding:0 16px;gap:12px}
  .nav-links,.nav-actions{display:none!important}
  .nav-mobile-ctrls{display:flex!important}
  .nav-ham{display:flex!important}
  .faq-h1{font-size:28px}
  .faq-page{padding:40px 16px 60px}
  .footer-grid{grid-template-columns:1fr;gap:28px}
}
@media(max-width:480px){
  .faq-h1{font-size:24px}
  .footer{padding:48px 20px 24px}
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
    radial-gradient(ellipse 85% 60% at 88% 0%, #0a84f0 0%, rgba(0,123,224,.85) 22%, rgba(0,90,190,.55) 45%, rgba(0,50,120,.25) 65%, transparent 82%),
    linear-gradient(180deg, #0068c8 0%, #003a80 22%, #00204f 38%, #050028 52%, #030014 68%, #00000a 85%, #000004 100%) !important;
}

html.dark .nav{
  background:rgba(6,18,48,.85) !important;
  backdrop-filter:blur(16px) !important;
  -webkit-backdrop-filter:blur(16px) !important;
  border-bottom:1px solid rgba(120,170,255,.18) !important;
}
html.dark .nav.scrolled{
  background:rgba(6,18,48,.94) !important;
  border-bottom-color:rgba(120,170,255,.25) !important;
  box-shadow:0 4px 16px rgba(0,0,0,.45) !important;
}
html.dark .nav-brand-title{color:#8ab4ff !important}
html.dark .nav-brand-subtitle{color:#8fa3c4 !important}
html.dark .nav-logo-wrap{
  background:rgba(255,255,255,.92) !important;
  box-shadow:0 2px 6px rgba(0,0,0,.4) !important;
}
html.dark .nav-links{
  background:rgba(6,18,48,.75) !important;
  border-color:rgba(120,170,255,.18) !important;
}
html.dark .nav-links a{color:#8fa3c4 !important}
html.dark .nav-links a:hover{
  background:rgba(96,150,255,.14) !important;
  color:#f4f8ff !important;
}
html.dark .nav-links a.active{
  background:#2563eb !important;
  color:#ffffff !important;
  box-shadow:none !important;
}
html.dark .theme-toggle{
  background:rgba(6,18,48,.75) !important;
  border-color:rgba(120,170,255,.18) !important;
  color:#8fa3c4 !important;
  box-shadow:none !important;
}
html.dark .theme-toggle:hover{
  background:rgba(96,150,255,.14) !important;
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
  border-color:rgba(120,170,255,.18) !important;
  color:#8fa3c4 !important;
}
html.dark .ham-line{background:#8fa3c4 !important}
html.dark .nav-mobile{
  background:rgba(6,18,48,.96) !important;
  border-top-color:rgba(120,170,255,.18) !important;
  box-shadow:0 8px 24px rgba(0,0,0,.5) !important;
}
html.dark .nav-mobile-links a{
  background:rgba(12,26,62,.85) !important;
  border:1px solid rgba(120,170,255,.15) !important;
  color:#8fa3c4 !important;
}
html.dark .nav-mobile-links a:hover{
  background:rgba(96,150,255,.16) !important;
  color:#f4f8ff !important;
}
html.dark .nav-mob-login{
  background:#2563eb !important;
  color:#ffffff !important;
  box-shadow:none !important;
}

html.dark .faq-page{
  background-color:transparent !important;
  background-image:none !important;
}
html.dark .faq-eyebrow{
  color:#8ab4ff !important;
  background:rgba(96,150,255,.14) !important;
  border-color:rgba(120,170,255,.25) !important;
}
html.dark .faq-eyebrow-dot{background:#8ab4ff !important}
html.dark .faq-h1{color:#f4f8ff !important}
html.dark .faq-h1 em{color:#8ab4ff !important}
html.dark .faq-lead{color:#b4c3dc !important}

html.dark #faqSearch{
  background:rgba(6,18,48,.85) !important;
  border:1.5px solid rgba(120,170,255,.25) !important;
  color:#f4f8ff !important;
  box-shadow:0 2px 8px rgba(0,0,0,.30) !important;
}
html.dark #faqSearch::placeholder{color:#8fa3c4 !important}
html.dark #faqSearch:focus{
  border-color:#8ab4ff !important;
  box-shadow:0 0 0 3px rgba(138,180,255,.20) !important;
}
html.dark .faq-search-icon{color:#8fa3c4 !important}

html.dark .faq-cat-title{color:#8ab4ff !important}
html.dark .faq-cat-title::before{background:#8ab4ff !important}

html.dark .faq-item{
  background:rgba(6,18,48,.85) !important;
  border:1px solid rgba(120,170,255,.18) !important;
  box-shadow:0 2px 8px rgba(0,0,0,.35) !important;
}
html.dark .faq-item:hover{border-color:rgba(120,170,255,.38) !important}
html.dark .faq-item.faq-open{
  border-color:rgba(120,170,255,.38) !important;
  box-shadow:0 4px 14px rgba(0,0,0,.45) !important;
}
html.dark .faq-question{color:#f4f8ff !important}
html.dark .faq-question:hover{background:rgba(96,150,255,.05) !important}
html.dark .faq-item.faq-open .faq-question{
  color:#8ab4ff !important;
  background:rgba(96,150,255,.08) !important;
}
html.dark .faq-q-icon{
  background:rgba(96,150,255,.14) !important;
  border-color:rgba(120,170,255,.22) !important;
  color:#8ab4ff !important;
}
html.dark .faq-item.faq-open .faq-q-icon{
  background:#2563eb !important;
  color:#ffffff !important;
  border-color:#2563eb !important;
}
html.dark .faq-answer{
  background:rgba(0,10,30,.45) !important;
  border-top:1px solid rgba(120,170,255,.12) !important;
}
html.dark .faq-step{color:#b4c3dc !important}
html.dark .faq-step-num{background:#2563eb !important;color:#ffffff !important}
html.dark .faq-note{
  background:rgba(96,150,255,.10) !important;
  border-color:rgba(120,170,255,.18) !important;
  color:#dbe7ff !important;
}
html.dark #faqNotFound p{color:#f4f8ff !important}
html.dark #faqNotFound span{color:#b4c3dc !important}

html.dark .footer{
  background-color:transparent !important;
  background-image:none !important;
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
html.dark .footer-divider{border-top-color:rgba(120,170,255,.15) !important}
html.dark .footer-copy{color:#8fa3c4 !important}
</style>
</head>
<body>
<header class="site-header">
<nav class="nav">
  <div class="nav-inner">
    <a href="{{ route('home') }}" class="nav-brand" aria-label="{{ \App\Models\SiteSetting::get('site_name', 'SIPBAR') }} Homepage">
      <div class="nav-logo-wrap">
        <img src="{{ \App\Models\SiteSetting::get('site_logo_landing') ?: \App\Models\SiteSetting::get('site_logo', '/logossmkn1.png') }}" alt="Logo" class="nav-brand-img">
      </div>
      <div class="nav-brand-text">
        <div class="nav-brand-title">{{ \App\Models\SiteSetting::get('site_name', 'SIPBAR') }}</div>
        <div class="nav-brand-subtitle">{{ \App\Models\SiteSetting::get('site_subtitle', 'SMKN 1 BANGSRI') }}</div>
      </div>
    </a>
    <div class="nav-links">
      <a href="{{ route('home') }}#beranda">{{ \App\Models\SiteSetting::get('nav_link_home', 'Beranda') }}</a>
      <a href="{{ route('home') }}#fitur">{{ \App\Models\SiteSetting::get('nav_link_features', 'Fitur') }}</a>
      <a href="{{ route('home') }}#data-inventaris">{{ \App\Models\SiteSetting::get('nav_link_inventory', 'Katalog Barang') }}</a>
      <a href="{{ route('home') }}#tentang">{{ \App\Models\SiteSetting::get('nav_link_about', 'Tentang') }}</a>
      <a href="{{ route('faq') }}" class="active">{{ \App\Models\SiteSetting::get('nav_link_help', 'Bantuan') }}</a>
    </div>
    <div class="nav-actions">
      <button type="button" class="theme-toggle theme-toggle-btn" aria-label="Ganti Tema" title="Ganti Tema">
        <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10A5 5 0 0012 7z"/></svg>
        <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
      </button>
      @auth
        <a href="{{ route('dashboard') }}" class="btn-nav-cta"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg><span>Dashboard</span></a>
      @else
        <a href="{{ route('login') }}" class="btn-nav-cta"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg><span>Masuk</span></a>
      @endauth
    </div>
    <div class="nav-mobile-ctrls">
      <button type="button" class="theme-toggle theme-toggle-btn" aria-label="Ganti Tema" title="Ganti Tema">
        <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10A5 5 0 0012 7z"/></svg>
        <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
      </button>
      <button type="button" class="nav-ham" id="navHamBtn" aria-label="Buka Menu" aria-expanded="false">
        <span class="ham-line"></span><span class="ham-line"></span><span class="ham-line"></span>
      </button>
    </div>
  </div>
  <div id="navMob" class="nav-mobile">
    <div class="nav-mobile-links">
      <a href="{{ route('home') }}#beranda" onclick="closeNavMob()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg><span>Beranda</span></a>
      <a href="{{ route('faq') }}" onclick="closeNavMob()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg><span>FAQ & Bantuan</span></a>
    </div>
    <div class="nav-mob-divider"></div>
    <div class="nav-mob-theme-row">
      <div class="nav-mob-theme-info"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg><span>Tema Tampilan</span></div>
      <button type="button" class="nav-mob-theme-btn theme-toggle-btn" aria-label="Ganti Tema">
        <span class="mob-t-sun"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10A5 5 0 0012 7z"/></svg>Mode Terang</span>
        <span class="mob-t-moon"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>Mode Gelap</span>
      </button>
    </div>
    <div class="nav-mobile-footer">
      @auth
        <a href="{{ route('dashboard') }}" class="nav-mob-login"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg><span>Masuk Dashboard</span></a>
      @else
        <a href="{{ route('login') }}" class="nav-mob-login"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg><span>Masuk ke {{ \App\Models\SiteSetting::get('site_name', 'SIPBAR') }}</span></a>
      @endauth
    </div>
  </div>
</nav>
</header>

<main class="faq-page">
  <div class="faq-inner">
    <div class="faq-hero">
      <div class="faq-eyebrow"><span class="faq-eyebrow-dot"></span>Pusat Bantuan & Panduan</div>
      <h1 class="faq-h1">Pertanyaan yang <em>Sering Diajukan</em></h1>
      <p class="faq-lead">Temukan langkah cepat dan solusi tepat atas kendala peminjaman, persetujuan, QR Code, maupun penggunaan sistem SIPBAR.</p>
      
      <div class="faq-search-wrap">
        <svg class="faq-search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" id="faqSearch" placeholder="Cari kendala atau kata kunci (contoh: lupa password, QR code, approval)..." autocomplete="off" aria-label="Cari pertanyaan FAQ">
      </div>
    </div>

    <div class="faq-categories" id="faqCategories">
      
      {{-- KATEGORI 1: Akun & Login --}}
      <section class="faq-cat-block" data-category="akun-login">
        <h2 class="faq-cat-title">Akun & Login</h2>
        <div class="faq-list">
          
          <div class="faq-item">
            <button type="button" class="faq-question" aria-expanded="false">
              <span>Lupa password atau tidak bisa login ke akun SIPBAR?</span>
              <span class="faq-q-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg></span>
            </button>
            <div class="faq-answer">
              <div class="faq-steps">
                <div class="faq-step">
                  <span class="faq-step-num">1</span>
                  <span>Pastikan username / NISN / email dan password yang dimasukkan sudah sesuai (perhatikan huruf besar/kecil).</span>
                </div>
                <div class="faq-step">
                  <span class="faq-step-num">2</span>
                  <span>Jika tetap tidak bisa masuk, hubungi petugas / admin sekolah untuk melakukan reset password akun Anda.</span>
                </div>
                <div class="faq-step">
                  <span class="faq-step-num">3</span>
                  <span>Setelah reset dilakukan oleh petugas, masuk kembali melalui halaman <strong>Login</strong> menggunakan kredensial baru.</span>
                </div>
              </div>
              <div class="faq-note">Catatan: Untuk keamanan sistem, perubahan kredensial akun siswa dan guru hanya dapat dilakukan melalui petugas / admin sekolah.</div>
            </div>
          </div>

          <div class="faq-item">
            <button type="button" class="faq-question" aria-expanded="false">
              <span>Sesi login tiba-tiba berakhir (Session Expired) saat mengisi formulir?</span>
              <span class="faq-q-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg></span>
            </button>
            <div class="faq-answer">
              <div class="faq-steps">
                <div class="faq-step">
                  <span class="faq-step-num">1</span>
                  <span>Refresh halaman browser Anda untuk memeriksa status sesi.</span>
                </div>
                <div class="faq-step">
                  <span class="faq-step-num">2</span>
                  <span>Buka halaman <strong>Masuk</strong> dan lakukan login kembali dengan akun Anda.</span>
                </div>
                <div class="faq-step">
                  <span class="faq-step-num">3</span>
                  <span>Lanjutkan pengisian form pengajuan atau transaksi yang tertunda.</span>
                </div>
              </div>
            </div>
          </div>

        </div>
      </section>

      {{-- KATEGORI 2: Peminjaman (Siswa) --}}
      <section class="faq-cat-block" data-category="peminjaman">
        <h2 class="faq-cat-title">Peminjaman (Siswa)</h2>
        <div class="faq-list">

          <div class="faq-item">
            <button type="button" class="faq-question" aria-expanded="false">
              <span>Barang yang ingin dipinjam tidak muncul di katalog atau stok habis?</span>
              <span class="faq-q-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg></span>
            </button>
            <div class="faq-answer">
              <div class="faq-steps">
                <div class="faq-step">
                  <span class="faq-step-num">1</span>
                  <span>Periksa status ketersediaan barang di menu Katalog Barang; barang dengan stok 0 sedang dipinjam oleh peminjam lain atau sedang dalam pemeliharaan.</span>
                </div>
                <div class="faq-step">
                  <span class="faq-step-num">2</span>
                  <span>Cek kembali secara berkala saat barang telah selesai dikembalikan ke tempat penyimpanan barang.</span>
                </div>
                <div class="faq-step">
                  <span class="faq-step-num">3</span>
                  <span>Jika barang dibutuhkan mendesak untuk kegiatan KBM/praktik, konfirmasi langsung ke petugas / admin sekolah.</span>
                </div>
              </div>
            </div>
          </div>

          <div class="faq-item">
            <button type="button" class="faq-question" aria-expanded="false">
              <span>Status pengajuan peminjaman masih tertahan di status "Menunggu Persetujuan"?</span>
              <span class="faq-q-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg></span>
            </button>
            <div class="faq-answer">
              <div class="faq-steps">
                <div class="faq-step">
                  <span class="faq-step-num">1</span>
                  <span>Buka menu <strong>Riwayat Peminjaman</strong> di akun Siswa dan lihat nama Guru Pembimbing yang Anda pilih saat pengajuan.</span>
                </div>
                <div class="faq-step">
                  <span class="faq-step-num">2</span>
                  <span>Ingatkan atau hubungi Guru Pembimbing terkait untuk memeriksa tautan persetujuan / Dashboard Guru beliau.</span>
                </div>
                <div class="faq-step">
                  <span class="faq-step-num">3</span>
                  <span>Setelah Guru menyetujui, status akan berubah dan QR Code pengambilan barang akan otomatis dibuat oleh sistem.</span>
                </div>
              </div>
            </div>
          </div>

          <div class="faq-item">
            <button type="button" class="faq-question" aria-expanded="false">
              <span>Pengajuan peminjaman ditolak oleh Guru atau Sarpras, bagaimana mengajukan ulang?</span>
              <span class="faq-q-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg></span>
            </button>
            <div class="faq-answer">
              <div class="faq-steps">
                <div class="faq-step">
                  <span class="faq-step-num">1</span>
                  <span>Buka detail peminjaman di riwayat akun Anda untuk membaca catatan atau alasan penolakan dari Guru / Sarpras.</span>
                </div>
                <div class="faq-step">
                  <span class="faq-step-num">2</span>
                  <span>Buat permohonan peminjaman baru dengan menyesuaikan jumlah barang, jadwal pemakaian, atau guru pembimbing yang tepat.</span>
                </div>
                <div class="faq-step">
                  <span class="faq-step-num">3</span>
                  <span>Kirim ulang form pengajuan peminjaman untuk diverifikasi kembali.</span>
                </div>
              </div>
            </div>
          </div>

        </div>
      </section>

      {{-- KATEGORI 3: Persetujuan (Guru / Kepala Jurusan) --}}
      <section class="faq-cat-block" data-category="persetujuan">
        <h2 class="faq-cat-title">Persetujuan (Guru / Kepala Jurusan)</h2>
        <div class="faq-list">

          <div class="faq-item">
            <button type="button" class="faq-question" aria-expanded="false">
              <span>Guru tidak menerima tautan persetujuan (approval) atau link tidak terbuka?</span>
              <span class="faq-q-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg></span>
            </button>
            <div class="faq-answer">
              <div class="faq-steps">
                <div class="faq-step">
                  <span class="faq-step-num">1</span>
                  <span>Siswa dapat membuka detail pengajuan di akunnya, lalu menyalin tautan persetujuan (magic link) secara langsung untuk dikirimkan ke Guru bersangkutan.</span>
                </div>
                <div class="faq-step">
                  <span class="faq-step-num">2</span>
                  <span>Alternatif: Guru dapat langsung login ke SIPBAR dan membuka menu <strong>Dashboard Guru</strong> untuk melihat daftar pengajuan yang memerlukan persetujuan.</span>
                </div>
                <div class="faq-step">
                  <span class="faq-step-num">3</span>
                  <span>Klik tombol <strong>Setujui</strong> atau <strong>Tolak</strong> pada item permohonan siswa yang bersangkutan.</span>
                </div>
              </div>
            </div>
          </div>

          <div class="faq-item">
            <button type="button" class="faq-question" aria-expanded="false">
              <span>Bagaimana cara Guru memberikan alasan ketika menolak permohonan peminjaman?</span>
              <span class="faq-q-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg></span>
            </button>
            <div class="faq-answer">
              <div class="faq-steps">
                <div class="faq-step">
                  <span class="faq-step-num">1</span>
                  <span>Buka halaman approval dari tautan persetujuan atau dashboard guru.</span>
                </div>
                <div class="faq-step">
                  <span class="faq-step-num">2</span>
                  <span>Pilih tombol <strong>Tolak</strong>, lalu masukkan catatan/alasan penolakan (misalnya: bentrok jadwal atau barang tidak sesuai kebutuhan praktik).</span>
                </div>
                <div class="faq-step">
                  <span class="faq-step-num">3</span>
                  <span>Konfirmasi penolakan agar siswa mendapatkan notifikasi dan dapat mengajukan revisi.</span>
                </div>
              </div>
            </div>
          </div>

        </div>
      </section>

      {{-- KATEGORI 4: QR Code & Pengambilan --}}
      <section class="faq-cat-block" data-category="qr-pengambilan">
        <h2 class="faq-cat-title">QR Code & Pengambilan</h2>
        <div class="faq-list">

          <div class="faq-item">
            <button type="button" class="faq-question" aria-expanded="false">
              <span>QR Code peminjaman tidak muncul setelah permohonan disetujui?</span>
              <span class="faq-q-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg></span>
            </button>
            <div class="faq-answer">
              <div class="faq-steps">
                <div class="faq-step">
                  <span class="faq-step-num">1</span>
                  <span>Buka menu <strong>Riwayat Peminjaman</strong> dan pastikan status pengajuan sudah <em>Disetujui Guru</em> / siap diambil.</span>
                </div>
                <div class="faq-step">
                  <span class="faq-step-num">2</span>
                  <span>Klik tombol <strong>Lihat Detail / QR</strong> pada baris transaksi peminjaman.</span>
                </div>
                <div class="faq-step">
                  <span class="faq-step-num">3</span>
                  <span>Jika kode QR belum ter-render, segarkan (refresh) halaman browser Anda.</span>
                </div>
              </div>
            </div>
          </div>

          <div class="faq-item">
            <button type="button" class="faq-question" aria-expanded="false">
              <span>QR Code tidak terbaca oleh scanner petugas / admin?</span>
              <span class="faq-q-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg></span>
            </button>
            <div class="faq-answer">
              <div class="faq-steps">
                <div class="faq-step">
                  <span class="faq-step-num">1</span>
                  <span>Tingkatkan kecerahan layar smartphone Anda agar QR Code dapat terbaca jelas oleh kamera pemindai.</span>
                </div>
                <div class="faq-step">
                  <span class="faq-step-num">2</span>
                  <span>Jika pemindaian tetap gagal, beritahukan <strong>Kode / Nomor Transaksi Peminjaman</strong> Anda kepada petugas / admin.</span>
                </div>
                <div class="faq-step">
                  <span class="faq-step-num">3</span>
                  <span>Petugas / admin akan memproses verifikasi penyerahan barang secara manual di sistem.</span>
                </div>
              </div>
            </div>
          </div>

          <div class="faq-item">
            <button type="button" class="faq-question" aria-expanded="false">
              <span>Barang belum diambil padahal jadwal peminjaman sudah lewat?</span>
              <span class="faq-q-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg></span>
            </button>
            <div class="faq-answer">
              <div class="faq-steps">
                <div class="faq-step">
                  <span class="faq-step-num">1</span>
                  <span>Segera datangi tempat pengambilan barang sekolah dan tunjukkan nomor transaksi peminjaman Anda.</span>
                </div>
                <div class="faq-step">
                  <span class="faq-step-num">2</span>
                  <span>Jika waktu penyerahan sudah kedaluwarsa dan dibatalkan otomatis oleh sistem, buat permohonan peminjaman baru sesuai jadwal pemakaian yang valid.</span>
                </div>
              </div>
            </div>
          </div>

        </div>
      </section>

      {{-- KATEGORI 5: Pengembalian --}}
      <section class="faq-cat-block" data-category="pengembalian">
        <h2 class="faq-cat-title">Pengembalian</h2>
        <div class="faq-list">

          <div class="faq-item">
            <button type="button" class="faq-question" aria-expanded="false">
              <span>Bagaimana alur dan tata cara pengembalian barang yang sedang dipinjam?</span>
              <span class="faq-q-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg></span>
            </button>
            <div class="faq-answer">
              <div class="faq-steps">
                <div class="faq-step">
                  <span class="faq-step-num">1</span>
                  <span>Bawa barang fisik dalam kondisi lengkap dan bersih ke tempat pengembalian barang sekolah.</span>
                </div>
                <div class="faq-step">
                  <span class="faq-step-num">2</span>
                  <span>Buka menu <strong>Pengembalian</strong> pada akun siswa dan tunjukkan QR Code / kode transaksi pengembalian kepada petugas / admin.</span>
                </div>
                <div class="faq-step">
                  <span class="faq-step-num">3</span>
                  <span>Petugas / admin akan memverifikasi fisik barang dan menyelesaikan transaksi pengembalian di sistem.</span>
                </div>
              </div>
            </div>
          </div>

          <div class="faq-item">
            <button type="button" class="faq-question" aria-expanded="false">
              <span>Kondisi barang rusak atau hilang saat masa peminjaman, apa yang harus dilakukan?</span>
              <span class="faq-q-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg></span>
            </button>
            <div class="faq-answer">
              <div class="faq-steps">
                <div class="faq-step">
                  <span class="faq-step-num">1</span>
                  <span>Laporkan kondisi sebenarnya secara jujur kepada petugas / admin sekolah saat proses pengembalian.</span>
                </div>
                <div class="faq-step">
                  <span class="faq-step-num">2</span>
                  <span>Petugas / admin akan mencatat status kondisi barang (Rusak Ringan, Rusak Berat, atau Hilang) di sistem.</span>
                </div>
                <div class="faq-step">
                  <span class="faq-step-num">3</span>
                  <span>Peminjam bersama Guru Pembimbing menyelesaikan kewajiban perbaikan atau penggantian sesuai tata tertib peminjaman barang sekolah.</span>
                </div>
              </div>
            </div>
          </div>

        </div>
      </section>

      {{-- KATEGORI 6: Tampilan & Perangkat --}}
      <section class="faq-cat-block" data-category="tampilan-perangkat">
        <h2 class="faq-cat-title">Tampilan & Perangkat</h2>
        <div class="faq-list">

          <div class="faq-item">
            <button type="button" class="faq-question" aria-expanded="false">
              <span>Bagaimana cara mengganti mode tampilan gelap (Dark Mode) atau terang?</span>
              <span class="faq-q-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg></span>
            </button>
            <div class="faq-answer">
              <div class="faq-steps">
                <div class="faq-step">
                  <span class="faq-step-num">1</span>
                  <span><strong>Pada Desktop:</strong> Klik tombol ikon matahari/bulan di sebelah kanan bilah navigasi (navbar) atas.</span>
                </div>
                <div class="faq-step">
                  <span class="faq-step-num">2</span>
                  <span><strong>Pada Smartphone:</strong> Tekan tombol menu hamburger (tiga garis), lalu klik tombol toggle <strong>Tema Tampilan</strong>.</span>
                </div>
                <div class="faq-step">
                  <span class="faq-step-num">3</span>
                  <span>Pilihan tema akan disimpan secara otomatis di browser perangkat Anda untuk kunjungan berikutnya.</span>
                </div>
              </div>
            </div>
          </div>

          <div class="faq-item">
            <button type="button" class="faq-question" aria-expanded="false">
              <span>Tampilan halaman tampak terpotong atau tabel sulit di-scroll pada layar ponsel?</span>
              <span class="faq-q-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg></span>
            </button>
            <div class="faq-answer">
              <div class="faq-steps">
                <div class="faq-step">
                  <span class="faq-step-num">1</span>
                  <span>Geser (swipe) jari Anda secara horizontal langsung pada area tabel untuk melihat kolom yang tersembunyi.</span>
                </div>
                <div class="faq-step">
                  <span class="faq-step-num">2</span>
                  <span>Gunakan peramban modern versi terbaru (seperti Google Chrome atau Safari mobile).</span>
                </div>
                <div class="faq-step">
                  <span class="faq-step-num">3</span>
                  <span>Jika diperlukan, ubah orientasi smartphone Anda menjadi mode lanskap (landscape).</span>
                </div>
              </div>
            </div>
          </div>

        </div>
      </section>

    </div>

    {{-- Pesan Hasil Pencarian Tidak Ditemukan --}}
    <div id="faqNotFound">
      <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      <p>Pertanyaan Tidak Ditemukan</p>
      <span>Maaf, tidak ada topik FAQ yang cocok dengan kata kunci pencarian Anda. Silakan hubungi petugas / admin sekolah secara langsung.</span>
    </div>

  </div>
</main>

<footer class="footer">
  <div class="footer-inner">
    <div class="footer-grid">
      <div class="footer-brand">
        <div class="footer-logo-wrap">
          <div class="footer-logo-box">
            <img src="{{ \App\Models\SiteSetting::get('site_logo_landing') ?: \App\Models\SiteSetting::get('site_logo', '/logossmkn1.png') }}" alt="Logo" class="nav-brand-img">
          </div>
          <div>
            <div class="footer-brand-name">{{ \App\Models\SiteSetting::get('site_name', 'SIPBAR') }}</div>
            <div class="footer-brand-sub">{{ \App\Models\SiteSetting::get('site_subtitle', 'SMKN 1 BANGSRI') }}</div>
          </div>
        </div>
        <p class="footer-desc">{{ \App\Models\SiteSetting::get('footer_description', 'Sistem peminjaman barang berbasis web yang lebih efektif, efisien, dan transparan untuk sekolah.') }}</p>
        <div class="footer-brand-extra">
          <a href="{{ route('home') }}" class="footer-help-sublink">&larr; Kembali ke Beranda</a>
        </div>
      </div>

      <div class="footer-col">
        <div class="footer-heading">Bantuan</div>
        <ul class="footer-list">
          <li><a href="{{ route('faq') }}">FAQ</a></li>
          <li><a href="{{ route('home') }}#bantuan">Bantuan Singkat</a></li>
        </ul>
      </div>
    </div>

    <hr class="footer-divider">
    <div class="footer-bottom">
      <p class="footer-copy">&copy; {{ date('Y') }} {{ \App\Models\SiteSetting::get('site_name', 'SIPBAR') }} {{ \App\Models\SiteSetting::get('site_subtitle', 'SMKN 1 BANGSRI') }}. Hak cipta dilindungi.</p>
    </div>
  </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function(){
  var html = document.documentElement;
  var nav = document.querySelector('.nav');
  var navHamBtn = document.getElementById('navHamBtn');
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
  handleNavScroll();

  // ─── Mobile Hamburger Toggle ───
  if(navHamBtn && navMob){
    navHamBtn.addEventListener('click', function(e){
      e.stopPropagation();
      var isOpen = navMob.classList.toggle('open');
      navHamBtn.classList.toggle('active', isOpen);
      navHamBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
    document.addEventListener('click', function(e){
      if(navMob.classList.contains('open') && !navMob.contains(e.target) && !navHamBtn.contains(e.target)){
        closeNavMob();
      }
    });
  }

  function closeNavMob(){
    if(navMob) navMob.classList.remove('open');
    if(navHamBtn){
      navHamBtn.classList.remove('active');
      navHamBtn.setAttribute('aria-expanded', 'false');
    }
  }
  window.closeNavMob = closeNavMob;

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
      setTimeout(function(){
        html.classList.remove('theme-fade');
        isThemeTransitioning = false;
      }, 400);
      return;
    }
    isThemeTransitioning = true;
    var r = Math.hypot(Math.max(x, window.innerWidth - x), Math.max(y, window.innerHeight - y));
    var t = document.startViewTransition(change);
    t.ready.then(function(){
      var anim = html.animate(
        { clipPath: ['circle(0px at ' + x + 'px ' + y + 'px)', 'circle(' + r + 'px at ' + x + 'px ' + y + 'px)'] },
        { duration: 500, easing: 'cubic-bezier(.4,0,.2,1)', pseudoElement: '::view-transition-new(root)' }
      );
      anim.onfinish = function(){ isThemeTransitioning = false; };
      anim.oncancel = function(){ isThemeTransitioning = false; };
    }).catch(function(){
      isThemeTransitioning = false;
    });
    t.finished.finally(function(){
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

  // ─── Keyboard shortcut: Alt + D ───
  document.addEventListener('keydown', function(e){
    if(e.altKey && e.key.toLowerCase() === 'd'){
      var toDark = !html.classList.contains('dark');
      applyTheme(toDark, window.innerWidth / 2, window.innerHeight / 2);
    }
    if(e.key === 'Escape' && navMob && navMob.classList.contains('open')) closeNavMob();
  });

  // ─── Close mobile nav on resize ───
  window.addEventListener('resize', function(){
    if(window.innerWidth > 768 && navMob && navMob.classList.contains('open')){
      closeNavMob();
    }
  });

  // ─── Accordion Logic ───
  document.querySelectorAll('.faq-question').forEach(function(btn){
    btn.addEventListener('click', function(){
      var item = this.closest('.faq-item');
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

  // ─── Client-side Search Filter ───
  var searchInput = document.getElementById('faqSearch');
  var notFoundEl = document.getElementById('faqNotFound');
  var catBlocks = document.querySelectorAll('.faq-cat-block');

  if(searchInput){
    searchInput.addEventListener('input', function(){
      var q = this.value.trim().toLowerCase();
      var totalVisible = 0;

      catBlocks.forEach(function(cat){
        var catVisible = 0;
        var items = cat.querySelectorAll('.faq-item');

        items.forEach(function(item){
          var questionText = (item.querySelector('.faq-question span') || {}).textContent || '';
          questionText = questionText.toLowerCase();
          var answerText = (item.querySelector('.faq-answer') || {}).textContent || '';
          answerText = answerText.toLowerCase();
          var match = !q || questionText.includes(q) || answerText.includes(q);

          if(match){
            item.style.display = '';
            catVisible++;
            totalVisible++;
            if(q.length > 2){
              item.classList.add('faq-open');
              item.querySelector('.faq-question').setAttribute('aria-expanded', 'true');
            }
          } else {
            item.style.display = 'none';
          }
        });

        cat.style.display = catVisible > 0 ? '' : 'none';
      });

      if(notFoundEl){
        notFoundEl.style.display = totalVisible === 0 ? 'block' : 'none';
      }
    });
  }
});
</script>
</body>
</html>