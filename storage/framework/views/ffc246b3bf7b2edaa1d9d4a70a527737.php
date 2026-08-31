<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPBAR - Sistem Informasi Pengelolaan Barang</title>
    <script>
        (function(){
            var t=localStorage.getItem('sipbar-theme');
            if(t==='dark'||(t===null&&window.matchMedia('(prefers-color-scheme: dark)').matches))
                document.documentElement.classList.add('dark');
        })();
    </script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
        /* CSS TERBATAS: 2-3 WARNA UTAMA + NETRAL */
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        html{scroll-behavior:smooth;font-family:'Inter',ui-sans-serif,system-ui,sans-serif}
        body{background:var(--bg);color:var(--text);line-height:1.6;overflow-x:hidden}

        /* TOKENS: 2 WARNA UTAMA (BLUE + TEAL) + NETRAL */
        :root{
            --primary:#1d4ed8;
            --secondary:#0d9488;
            --bg:#ffffff;
            --bg-alt:#f8fafc;
            --text:#0f172a;
            --text-muted:#64748b;
            --border:#e2e8f0;
            --card:#ffffff;
        }
        html.dark{
            --primary:#3b82f6;
            --secondary:#14b8a6;
            --bg:#0f172a;
            --bg-alt:#1e293b;
            --text:#f1f5f9;
            --text-muted:#94a3b8;
            --border:#334155;
            --card:#1e293b;
        }

        /* NAVBAR - CLEAN & SIMPLE */
        .navbar{position:sticky;top:0;z-index:50;background:var(--bg);border-bottom:1px solid var(--border);backdrop-filter:blur(8px)}
        .nav-container{max-width:1200px;margin:0 auto;padding:0 1.5rem;display:flex;align-items:center;justify-content:space-between;height:70px}
        .logo{display:flex;align-items:center;gap:0.75rem;text-decoration:none}
        .logo-icon{width:40px;height:40px;background:var(--primary);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff}
        .logo-text{font-size:1.25rem;font-weight:700;color:var(--text)}
        .nav-links{display:flex;gap:2rem;align-items:center}
        .nav-link{color:var(--text-muted);text-decoration:none;font-size:0.9rem;font-weight:500;transition:color 0.2s}
        .nav-link:hover{color:var(--text)}
        .nav-cta{display:flex;gap:0.75rem;align-items:center}
        .btn{padding:0.625rem 1.25rem;border-radius:8px;font-weight:600;text-decoration:none;transition:all 0.2s;font-size:0.9rem;border:none;cursor:pointer}
        .btn-primary{background:var(--primary);color:#fff}
        .btn-primary:hover{filter:brightness(1.1);transform:translateY(-1px)}
        .btn-outline{border:1.5px solid var(--border);color:var(--text);background:transparent}
        .btn-outline:hover{background:var(--bg-alt)}
        .theme-btn{width:40px;height:40px;border-radius:8px;background:var(--bg-alt);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;cursor:pointer}
        .theme-btn:hover{background:var(--border)}
        .moon{display:block}.sun{display:none}
        html.dark .moon{display:none}html.dark .sun{display:block}

        /* HERO - CLEAN & FOCUSED */
        .hero{padding:5rem 1.5rem 4rem;background:var(--bg-alt)}
        .hero-container{max-width:1200px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center}
        .hero-content h1{font-size:3rem;font-weight:800;line-height:1.2;margin-bottom:1.5rem;color:var(--text)}
        .hero-content .highlight{color:var(--primary)}
        .hero-content p{font-size:1.125rem;color:var(--text-muted);margin-bottom:2rem}
        .hero-actions{display:flex;gap:1rem}
        .hero-visual{position:relative;height:400px;background:var(--card);border-radius:12px;border:1px solid var(--border);overflow:hidden}
        .visual-mockup{position:absolute;inset:20px;background:linear-gradient(135deg,#f1f5f9 0%,#e2e8f0 100%);border-radius:8px;padding:20px}
        .mockup-header{height:40px;background:#fff;border-radius:6px;margin-bottom:10px;display:flex;align-items:center;padding:0 15px;gap:6px}
        .dot{width:8px;height:8px;border-radius:50%}
        .mockup-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
        .card-mock{background:#fff;height:80px;border-radius:6px;border:1px solid #e2e8f0}

        /* FEATURES - GRID SIMPLE */
        .features{padding:5rem 1.5rem;background:var(--bg)}
        .features-container{max-width:1200px;margin:0 auto}
        .section-header{text-align:center;margin-bottom:3rem}
        .section-header h2{font-size:2.5rem;font-weight:800;margin-bottom:1rem;color:var(--text)}
        .section-header p{font-size:1.125rem;color:var(--text-muted)}
        .features-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:2rem}
        .feature-card{padding:2rem;background:var(--card);border:1px solid var(--border);border-radius:12px;text-align:center}
        .feature-icon{width:60px;height:60px;margin:0 auto 1.5rem;background:var(--primary);border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff}
        .feature-card h3{font-size:1.25rem;font-weight:700;margin-bottom:0.75rem;color:var(--text)}
        .feature-card p{color:var(--text-muted);font-size:0.9rem}

        /* STATS - NO GRADIENT */
        .stats{padding:5rem 1.5rem;background:var(--primary);color:#fff}
        .stats-container{max-width:1200px;margin:0 auto;display:grid;grid-template-columns:repeat(4,1fr);gap:2rem;text-align:center}
        .stat-item h3{font-size:3rem;font-weight:800;margin-bottom:0.5rem}
        .stat-item p{font-size:1rem;opacity:0.9}

        /* CTA - SOLID COLOR */
        .cta{padding:5rem 1.5rem;background:var(--bg-alt)}
        .cta-container{max-width:800px;margin:0 auto;text-align:center;padding:4rem;background:var(--card);border-radius:16px;border:1px solid var(--border)}
        .cta-container h2{font-size:2.5rem;font-weight:800;margin-bottom:1rem;color:var(--text)}
        .cta-container p{font-size:1.125rem;color:var(--text-muted);margin-bottom:2rem}

        /* FOOTER - MINIMAL */
        .footer{padding:3rem 1.5rem 2rem;background:var(--bg);border-top:1px solid var(--border)}
        .footer-container{max-width:1200px;margin:0 auto;display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:3rem}
        .footer-col h4{font-size:1rem;font-weight:700;margin-bottom:1rem;color:var(--text)}
        .footer-col ul{list-style:none}
        .footer-col li{margin-bottom:0.5rem}
        .footer-col a{color:var(--text-muted);text-decoration:none;font-size:0.9rem}
        .footer-col a:hover{color:var(--text)}
        .footer-bottom{margin-top:3rem;padding-top:2rem;border-top:1px solid var(--border);text-align:center;color:var(--text-muted);font-size:0.875rem}

        /* RESPONSIVE */
        @media(max-width:768px){
            .nav-links,.hero-visual{display:none}
            .hero-container{grid-template-columns:1fr}
            .features-grid{grid-template-columns:1fr}
            .stats-container{grid-template-columns:repeat(2,1fr)}
            .footer-container{grid-template-columns:1fr}
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="/" class="logo">
                <div class="logo-icon">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <span class="logo-text">SIPBAR</span>
            </a>
            <div class="nav-links">
                <a href="#beranda" class="nav-link">Beranda</a>
                <a href="#fitur" class="nav-link">Fitur</a>
                <a href="#tentang" class="nav-link">Tentang</a>
                <a href="#kontak" class="nav-link">Kontak</a>
            </div>
            <div class="nav-cta">
                <button class="theme-btn" id="themeToggle">
                    <svg class="moon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg class="sun" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-primary">Dashboard</a>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-outline">Masuk</a>
                    <a href="<?php echo e(route('register')); ?>" class="btn btn-primary">Daftar</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero" id="beranda">
        <div class="hero-container">
            <div class="hero-content">
                <h1>Kelola <span class="highlight">Inventaris</span> Sekolah Lebih Mudah</h1>
                <p>Sistem informasi pengelolaan barang terintegrasi untuk SMKN 1 Bangsri. Pantau, pinjam, dan kelola aset sekolah secara digital.</p>
                <div class="hero-actions">
                    <a href="<?php echo e(route('register')); ?>" class="btn btn-primary">Mulai Sekarang</a>
                    <a href="#fitur" class="btn btn-outline">Pelajari Lebih Lanjut</a>
                </div>
            </div>
            <div class="hero-visual">
                <div class="visual-mockup">
                    <div class="mockup-header">
                        <div class="dot" style="background:#ef4444"></div>
                        <div class="dot" style="background:#f59e0b"></div>
                        <div class="dot" style="background:#10b981"></div>
                    </div>
                    <div class="mockup-grid">
                        <div class="card-mock"></div>
                        <div class="card-mock"></div>
                        <div class="card-mock"></div>
                        <div class="card-mock"></div>
                        <div class="card-mock"></div>
                        <div class="card-mock"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="features" id="fitur">
        <div class="features-container">
            <div class="section-header">
                <h2>Fitur Unggulan</h2>
                <p>Solusi lengkap untuk manajemen inventaris sekolah</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3>Peminjaman Digital</h3>
                    <p>Ajukan peminjaman barang secara online dengan persetujuan otomatis dari guru pembimbing</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                    </div>
                    <h3>QR Code System</h3>
                    <p>Proses checkout dan checkin barang lebih cepat dengan teknologi QR Code</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3>Laporan Real-time</h3>
                    <p>Dashboard analitik dan laporan inventaris yang dapat diakses kapan saja</p>
                </div>
            </div>
        </div>
    </section>

    <!-- STATS -->
    <section class="stats">
        <div class="stats-container">
            <div class="stat-item">
                <h3>500+</h3>
                <p>Barang Terdaftar</p>
            </div>
            <div class="stat-item">
                <h3>200+</h3>
                <p>Pengguna Aktif</p>
            </div>
            <div class="stat-item">
                <h3>1000+</h3>
                <p>Transaksi Selesai</p>
            </div>
            <div class="stat-item">
                <h3>98%</h3>
                <p>Kepuasan Pengguna</p>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta">
        <div class="cta-container">
            <h2>Siap Memulai?</h2>
            <p>Bergabunglah dengan SIPBAR dan kelola inventaris sekolah dengan lebih efisien</p>
            <a href="<?php echo e(route('register')); ?>" class="btn btn-primary">Daftar Sekarang</a>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer" id="kontak">
        <div class="footer-container">
            <div class="footer-col">
                <h4>SIPBAR</h4>
                <p style="color:var(--text-muted);font-size:0.9rem;line-height:1.7">Sistem Informasi Pengelolaan Barang untuk SMKN 1 Bangsri. Memudahkan pengelolaan inventaris sekolah secara digital.</p>
            </div>
            <div class="footer-col">
                <h4>Navigasi</h4>
                <ul>
                    <li><a href="#beranda">Beranda</a></li>
                    <li><a href="#fitur">Fitur</a></li>
                    <li><a href="#tentang">Tentang</a></li>
                    <li><a href="#kontak">Kontak</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Layanan</h4>
                <ul>
                    <li><a href="<?php echo e(route('login')); ?>">Login</a></li>
                    <li><a href="<?php echo e(route('register')); ?>">Daftar</a></li>
                    <li><a href="#">Bantuan</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Kontak</h4>
                <ul>
                    <li><a href="mailto:sipbar@smkn1bangsri.sch.id">sipbar@smkn1bangsri.sch.id</a></li>
                    <li><a href="tel:+62xxx">+62 xxx xxx xxx</a></li>
                    <li><a href="#">SMKN 1 Bangsri</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 SIPBAR - SMKN 1 Bangsri. All rights reserved.</p>
        </div>
    </footer>

    <!-- THEME TOGGLE SCRIPT -->
    <script>
        const toggle=document.getElementById('themeToggle');
        const html=document.documentElement;
        const key='sipbar-theme';
        toggle.addEventListener('click',()=>{
            if(html.classList.contains('dark')){
                html.classList.remove('dark');
                localStorage.setItem(key,'light');
            }else{
                html.classList.add('dark');
                localStorage.setItem(key,'dark');
            }
        });
    </script>
</body>
</html>
<?php /**PATH C:\Users\ASUS\SIPBARV2\resources\views\welcome-new-v2.blade.php ENDPATH**/ ?>