@extends('layouts.superadmin')

@section('title', 'Kelola Landing Page')
@section('page-heading', 'Kelola Landing Page')

@section('content')
<style>
    /* ══════════════════════════════════════════════════════════════
       LANDING PAGE MANAGEMENT - ACCESSIBILITY & CONTRAST (WCAG AA)
       ══════════════════════════════════════════════════════════════ */
    .landing-mgmt-wrapper {
        display: flex;
        flex-direction: column;
        gap: 22px;
    }

    /* Tabs Navigation Styling */
    .landing-tabs-bar {
        background: var(--bg-card);
        border: 1px solid var(--border-alt);
        border-radius: 16px;
        padding: 6px;
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        box-shadow: var(--card-shadow);
    }

    .landing-tabs-bar .tab-btn {
        flex: 1;
        min-width: 110px;
        padding: 10px 16px;
        border: none;
        border-radius: 12px;
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        background: transparent;
        color: #94a3b8; /* Dark mode inactive - WCAG compliant */
        transition: all 0.2s ease;
        text-align: center;
        user-select: none;
    }

    html.light .landing-tabs-bar .tab-btn {
        color: #475569; /* Light mode inactive - WCAG compliant ratio 5.1:1 */
    }

    .landing-tabs-bar .tab-btn:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }

    .landing-tabs-bar .tab-btn.active {
        background: var(--blue-dark) !important;
        color: #ffffff !important;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.35);
    }

    /* Form Card Container */
    .landing-content-card {
        background: var(--bg-card);
        border: 1px solid var(--border-alt);
        border-radius: 16px;
        padding: 26px;
        box-shadow: var(--card-shadow);
    }

    /* Form Controls Contrast */
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
    }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid var(--border-alt);
        border-radius: 10px;
        background: var(--input-bg);
        color: var(--text-primary);
        font-size: 13px;
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
        font-family: inherit;
    }

    .form-control:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
    }

    .form-control::placeholder {
        color: #94a3b8;
    }

    html.light .form-control::placeholder {
        color: #64748b;
    }

    /* Helper text with WCAG AA compliance (>= 4.5:1) */
    .form-helper {
        font-size: 12px;
        color: #94a3b8;
        line-height: 1.5;
        margin-top: 4px;
    }

    html.light .form-helper {
        color: #475569;
        font-weight: 500;
    }

    .code-badge {
        display: inline-block;
        padding: 2px 6px;
        border-radius: 6px;
        font-size: 11.5px;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: #93c5fd;
    }

    html.light .code-badge {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        color: #1d4ed8;
    }

    /* Nested Sub-Cards (Fitur, Statistik, Link Footer) */
    .sub-card {
        background: var(--bg-card-subtle);
        border: 1px solid var(--border-alt);
        border-radius: 12px;
        padding: 18px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .sub-card-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Submit Button */
    .btn-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 24px;
        background: var(--blue-dark);
        color: #ffffff !important;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.15s ease, background-color 0.2s ease, box-shadow 0.2s ease;
        align-self: flex-start;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
    }

    .btn-submit:hover {
        opacity: 0.95;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    /* Logo Preview Box */
    .logo-preview-card {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 16px;
        background: var(--bg-card-subtle);
        border: 1px solid var(--border-alt);
        border-radius: 12px;
        margin-bottom: 10px;
    }

    .logo-preview-img-wrap {
        width: 58px;
        height: 58px;
        background: #ffffff;
        border: 1.5px solid var(--border-alt);
        border-radius: 10px;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
    }

    .logo-preview-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
</style>

<div class="landing-mgmt-wrapper">

    {{-- Hero Header --}}
    <div style="background:var(--bg-card);border:1px solid var(--border-alt);border-radius:18px;padding:24px 28px;display:flex;align-items:center;gap:18px;box-shadow:var(--card-shadow);flex-wrap:wrap">
        <div style="width:52px;height:52px;background:var(--blue-dark);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#ffffff !important" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2h2.828l5.586-5.586z"/>
            </svg>
        </div>
        <div>
            <div style="font-size:11px;font-weight:700;color:var(--blue);letter-spacing:.1em;text-transform:uppercase;margin-bottom:4px">Manajemen Konten</div>
            <div style="font-size:20px;font-weight:800;color:var(--text-primary);margin-bottom:4px">Kelola Landing Page</div>
            <div class="form-helper" style="font-size:13px;margin:0;">Edit semua konten landing page yang tampil untuk publik. Perubahan langsung tersimpan dan aktif secara real-time.</div>
        </div>
    </div>

    @if(session('success'))
        <div style="background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.35);color:var(--color-success);padding:12px 18px;border-radius:12px;font-size:13.5px;font-weight:600;display:flex;align-items:center;gap:10px;">
            <svg style="width:20px;height:20px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background:rgba(248,113,113,0.15);border:1px solid rgba(248,113,113,0.4);color:var(--color-danger);padding:14px 18px;border-radius:12px;font-size:13px;font-weight:500;">
            <div style="font-weight:700;margin-bottom:6px;">Terdapat kesalahan pada input:</div>
            <ul style="margin:0;padding-left:18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tabs Navigation --}}
    <div class="landing-tabs-bar" role="tablist" aria-label="Tab Pengaturan Landing Page">
        <button type="button" onclick="showTab('general')" id="tab-general" class="tab-btn active" role="tab" aria-selected="true" aria-controls="panel-general">
            Umum
        </button>
        <button type="button" onclick="showTab('hero')" id="tab-hero" class="tab-btn" role="tab" aria-selected="false" aria-controls="panel-hero">
            Hero
        </button>
        <button type="button" onclick="showTab('features')" id="tab-features" class="tab-btn" role="tab" aria-selected="false" aria-controls="panel-features">
            Fitur
        </button>
        <button type="button" onclick="showTab('stats')" id="tab-stats" class="tab-btn" role="tab" aria-selected="false" aria-controls="panel-stats">
            Statistik
        </button>
        <button type="button" onclick="showTab('about')" id="tab-about" class="tab-btn" role="tab" aria-selected="false" aria-controls="panel-about">
            Tentang
        </button>
        <button type="button" onclick="showTab('footer')" id="tab-footer" class="tab-btn" role="tab" aria-selected="false" aria-controls="panel-footer">
            Footer
        </button>
        <button type="button" onclick="showTab('contact')" id="tab-contact" class="tab-btn" role="tab" aria-selected="false" aria-controls="panel-contact">
            Kontak
        </button>
        <button type="button" onclick="showTab('navigation')" id="tab-navigation" class="tab-btn" role="tab" aria-selected="false" aria-controls="panel-navigation">
            Navigasi
        </button>
    </div>

    {{-- Tab Content Panels --}}
    <div class="landing-content-card">

        {{-- 1. General Settings Panel --}}
        <div id="panel-general" class="tab-panel" role="tabpanel" aria-labelledby="tab-general">
            <h3 style="font-size:18px;font-weight:800;color:var(--text-primary);margin-bottom:20px;">Pengaturan Umum</h3>
            <form action="{{ route('superadmin.landing-page.update-general') }}" method="POST" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:22px;">
                @csrf
                
                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:20px;">
                    <div class="form-group">
                        <label for="site_name" class="form-label">Nama Sistem</label>
                        <input type="text" id="site_name" name="site_name" value="{{ $settings['general']['site_name'] ?? 'SIPBAR' }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="site_subtitle" class="form-label">Subjudul Sistem</label>
                        <input type="text" id="site_subtitle" name="site_subtitle" value="{{ $settings['general']['site_subtitle'] ?? 'SMKN 1 BANGSRI' }}" class="form-control">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="site_title" class="form-label">Judul Halaman (SEO)</label>
                    <input type="text" id="site_title" name="site_title" value="{{ $settings['general']['site_title'] ?? 'SIPBAR – Sistem Informasi Pengelolaan Barang' }}" class="form-control">
                </div>
                
                <div style="border-top:1px solid var(--border-alt);padding-top:20px;display:flex;flex-direction:column;gap:20px;">
                    <div style="font-size:15px;font-weight:700;color:var(--text-primary);">Logo Sistem (3 Kategori Terpisah)</div>

                    {{-- Logo 1: Landing Page --}}
                    <div class="form-group">
                        <label for="site_logo_landing" class="form-label">1. Logo Landing Page</label>
                        <div class="logo-preview-card">
                            <div class="logo-preview-img-wrap">
                                <img id="preview-logo-landing" src="{{ $settings['general']['site_logo_landing'] ?? ($settings['general']['site_logo'] ?? '/logossmkn1.png') }}" alt="Logo Landing Page saat ini" class="logo-preview-img">
                            </div>
                            <div>
                                <div style="font-size:13px;font-weight:600;color:var(--text-primary);">Logo Navbar & Footer Landing Page</div>
                                <div class="form-helper">Digunakan khusus pada halaman beranda utama publik (<span class="code-badge">/</span>).</div>
                            </div>
                        </div>
                        <input type="file" id="site_logo_landing" name="site_logo_landing" accept="image/*" class="form-control" onchange="previewImage(this, 'preview-logo-landing')">
                        <p class="form-helper">Format: JPG, PNG, GIF, WEBP, SVG. Maks 2MB.</p>
                    </div>

                    {{-- Logo 2: Login Page --}}
                    <div class="form-group">
                        <label for="site_logo_login" class="form-label">2. Logo Halaman Login</label>
                        <div class="logo-preview-card">
                            <div class="logo-preview-img-wrap">
                                <img id="preview-logo-login" src="{{ $settings['general']['site_logo_login'] ?? ($settings['general']['site_logo'] ?? '/logossmkn1.png') }}" alt="Logo Halaman Login saat ini" class="logo-preview-img">
                            </div>
                            <div>
                                <div style="font-size:13px;font-weight:600;color:var(--text-primary);">Logo Halaman Masuk / Autentikasi</div>
                                <div class="form-helper">Digunakan di halaman login semua role (<span class="code-badge">/login</span>).</div>
                            </div>
                        </div>
                        <input type="file" id="site_logo_login" name="site_logo_login" accept="image/*" class="form-control" onchange="previewImage(this, 'preview-logo-login')">
                        <p class="form-helper">Format: JPG, PNG, GIF, WEBP, SVG. Maks 2MB.</p>
                    </div>

                    {{-- Logo 3: Dashboard Sidebar --}}
                    <div class="form-group">
                        <label for="site_logo_dashboard" class="form-label">3. Logo Dashboard (Semua Role)</label>
                        <div class="logo-preview-card">
                            <div class="logo-preview-img-wrap">
                                <img id="preview-logo-dashboard" src="{{ $settings['general']['site_logo_dashboard'] ?? ($settings['general']['site_logo'] ?? '/logossmkn1.png') }}" alt="Logo Dashboard saat ini" class="logo-preview-img">
                            </div>
                            <div>
                                <div style="font-size:13px;font-weight:600;color:var(--text-primary);">Logo Sidebar Semua Panel Dashboard</div>
                                <div class="form-helper">Digunakan di sidebar dashboard Siswa, Guru, Kepala Jurusan, Admin TU, dan Superadmin.</div>
                            </div>
                        </div>
                        <input type="file" id="site_logo_dashboard" name="site_logo_dashboard" accept="image/*" class="form-control" onchange="previewImage(this, 'preview-logo-dashboard')">
                        <p class="form-helper">Format: JPG, PNG, GIF, WEBP, SVG. Maks 2MB.</p>
                    </div>

                    {{-- Logo 4: Favicon (Tab Browser) --}}
                    <div class="form-group">
                        <label for="site_favicon" class="form-label">4. Favicon (Logo Tab Browser)</label>
                        <div class="logo-preview-card">
                            <div class="logo-preview-img-wrap" style="width:32px;height:32px;border-radius:50%;overflow:hidden;">
                                <img id="preview-favicon" src="{{ $settings['general']['site_favicon'] ?? '/favicon.ico' }}" alt="Favicon saat ini" class="logo-preview-img" style="border-radius:50%;">
                            </div>
                            <div>
                                <div style="font-size:13px;font-weight:600;color:var(--text-primary);">Logo Ikon Tab Browser <span style="font-size:11px;font-weight:500;color:#22c55e;background:rgba(34,197,94,0.12);padding:2px 8px;border-radius:99px;margin-left:6px;">● Otomatis bulat</span></div>
                                <div class="form-helper">Digunakan sebagai ikon di tab browser (favicon) untuk seluruh halaman aplikasi.</div>
                            </div>
                        </div>
                        <input type="file" id="site_favicon" name="site_favicon" accept=".png,.jpg,.jpeg,.gif,.webp" class="form-control" onchange="previewImage(this, 'preview-favicon')">
                        <p class="form-helper">Format: PNG, JPG, GIF, WEBP. Maks 2MB. Upload gambar persegi — otomatis di-crop <strong>bulat/lingkaran</strong> untuk favicon (16×16, 32×32, 48×48, 180×180px).</p>
                    </div>
                </div>
                
                <button type="submit" class="btn-submit">
                    <svg style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Pengaturan Umum
                </button>
            </form>
        </div>

        {{-- 2. Hero Settings Panel --}}
        <div id="panel-hero" class="tab-panel" style="display:none;" role="tabpanel" aria-labelledby="tab-hero">
            <h3 style="font-size:18px;font-weight:800;color:var(--text-primary);margin-bottom:20px;">Section Hero</h3>
            <form action="{{ route('superadmin.landing-page.update-hero') }}" method="POST" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:20px;">
                @csrf
                
                <div class="form-group">
                    <label for="hero_badge" class="form-label">Badge Hero</label>
                    <input type="text" id="hero_badge" name="hero_badge" value="{{ $settings['hero']['hero_badge'] ?? 'Sistem Inventaris Modern' }}" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="hero_title" class="form-label">Judul Hero (HTML diizinkan)</label>
                    <textarea id="hero_title" name="hero_title" rows="2" class="form-control" style="resize:vertical;">{{ $settings['hero']['hero_title'] ?? 'Kelola Inventaris<br><em>Lebih Mudah</em> & Efisien' }}</textarea>
                </div>
                
                <div class="form-group">
                    <label for="hero_description" class="form-label">Deskripsi Hero</label>
                    <textarea id="hero_description" name="hero_description" rows="3" class="form-control" style="resize:vertical;">{{ $settings['hero']['hero_description'] ?? 'Platform web modern untuk mengelola inventaris sekolah secara digital, transparan, dan terintegrasi.' }}</textarea>
                </div>
                
                <div class="form-group">
                    <label for="hero_background" class="form-label">Background Hero</label>
                    <input type="file" id="hero_background" name="hero_background" accept="image/*" class="form-control">
                    <p class="form-helper">Format: JPG, PNG, GIF, WEBP. Maks 5MB. Background saat ini: <span class="code-badge">{{ $settings['hero']['hero_background'] ?? '/sekolaheskasaba.jpeg' }}</span></p>
                </div>
                
                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:20px;">
                    <div class="form-group">
                        <label for="hero_cta_text" class="form-label">Teks Tombol CTA Utama</label>
                        <input type="text" id="hero_cta_text" name="hero_cta_text" value="{{ $settings['hero']['hero_cta_text'] ?? 'Dashboard' }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="hero_cta_alt_text" class="form-label">Teks Tombol CTA Alternatif</label>
                        <input type="text" id="hero_cta_alt_text" name="hero_cta_alt_text" value="{{ $settings['hero']['hero_cta_alt_text'] ?? 'Pelajari Lebih Lanjut' }}" class="form-control">
                    </div>
                </div>
                
                <button type="submit" class="btn-submit">
                    <svg style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Section Hero
                </button>
            </form>
        </div>

        {{-- 3. Features Settings Panel --}}
        <div id="panel-features" class="tab-panel" style="display:none;" role="tabpanel" aria-labelledby="tab-features">
            <h3 style="font-size:18px;font-weight:800;color:var(--text-primary);margin-bottom:20px;">Section Fitur</h3>
            <form action="{{ route('superadmin.landing-page.update-features') }}" method="POST" style="display:flex;flex-direction:column;gap:20px;">
                @csrf
                
                <div class="form-group">
                    <label for="features_eyebrow" class="form-label">Label Fitur</label>
                    <input type="text" id="features_eyebrow" name="features_eyebrow" value="{{ $settings['features']['features_eyebrow'] ?? 'Kapabilitas Sistem' }}" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="features_title" class="form-label">Judul Section Fitur (HTML diizinkan)</label>
                    <textarea id="features_title" name="features_title" rows="2" class="form-control" style="resize:vertical;">{{ $settings['features']['features_title'] ?? 'Tata Kelola Inventaris <em>Cepat & Terintegrasi</em>' }}</textarea>
                </div>
                
                <div class="form-group">
                    <label for="features_description" class="form-label">Deskripsi Section Fitur</label>
                    <textarea id="features_description" name="features_description" rows="3" class="form-control" style="resize:vertical;">{{ $settings['features']['features_description'] ?? 'Mulai dari pengajuan siswa, approval guru secara instan, hingga serah-terima barang dengan QR code.' }}</textarea>
                </div>
                
                <button type="submit" class="btn-submit">
                    <svg style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Section Fitur
                </button>
            </form>
            
            <hr style="border:none;border-top:1px solid var(--border-alt);margin:30px 0;">
            
            <h4 style="font-size:16px;font-weight:700;color:var(--text-primary);margin-bottom:16px;">Kartu Fitur</h4>
            <form action="{{ route('superadmin.landing-page.update-feature-cards') }}" method="POST" style="display:flex;flex-direction:column;gap:20px;">
                @csrf
                
                <div style="display:flex;flex-direction:column;gap:16px;">
                    @for($i = 0; $i < 5; $i++)
                        @php $card = $featureCards[$i] ?? []; $icon = $card['icon'] ?? ''; $routeName = $card['route'] ?? ''; $wf = $card['workflow'] ?? []; @endphp
                        <div class="sub-card">
                            <h5 class="sub-card-title">
                                <span style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:6px;background:var(--blue-dark);color:#fff;font-size:12px;">{{ $i + 1 }}</span>
                                Kartu Fitur {{ $i + 1 }}
                            </h5>
                            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));gap:16px;">
                                <div class="form-group">
                                    <label class="form-label">Judul Fitur</label>
                                    <input type="text" name="feature_title_{{ $i }}" value="{{ $card['title'] ?? '' }}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Icon</label>
                                    <select name="feature_icon_{{ $i }}" class="form-control">
                                        <option value="qr" {{ $icon === 'qr' ? 'selected' : '' }}>QR Code</option>
                                        <option value="inventory" {{ $icon === 'inventory' ? 'selected' : '' }}>Inventory</option>
                                        <option value="return" {{ $icon === 'return' ? 'selected' : '' }}>Return</option>
                                        <option value="report" {{ $icon === 'report' ? 'selected' : '' }}>Report</option>
                                        <option value="users" {{ $icon === 'users' ? 'selected' : '' }}>Users</option>
                                        <option value="notification" {{ $icon === 'notification' ? 'selected' : '' }}>Notification</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Deskripsi Fitur</label>
                                <textarea name="feature_description_{{ $i }}" rows="2" class="form-control" style="resize:vertical;">{{ $card['description'] ?? '' }}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Alur Kerja (Opsional 3 Langkah)</label>
                                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(160px, 1fr));gap:12px;">
                                    <input type="text" name="feature_workflow_1_{{ $i }}" value="{{ $wf[0] ?? '' }}" placeholder="Alur 1 (opsional)" class="form-control">
                                    <input type="text" name="feature_workflow_2_{{ $i }}" value="{{ $wf[1] ?? '' }}" placeholder="Alur 2 (opsional)" class="form-control">
                                    <input type="text" name="feature_workflow_3_{{ $i }}" value="{{ $wf[2] ?? '' }}" placeholder="Alur 3 (opsional)" class="form-control">
                                </div>
                            </div>
                            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));gap:16px;">
                                <div class="form-group">
                                    <label class="form-label">Teks Link</label>
                                    <input type="text" name="feature_link_text_{{ $i }}" value="{{ $card['link_text'] ?? '' }}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Target Route</label>
                                    <select name="feature_route_{{ $i }}" class="form-control">
                                        <option value="loans.index" {{ $routeName === 'loans.index' ? 'selected' : '' }}>Peminjaman (loans.index)</option>
                                        <option value="inventory.index" {{ $routeName === 'inventory.index' ? 'selected' : '' }}>Inventaris (inventory.index)</option>
                                        <option value="returns.index" {{ $routeName === 'returns.index' ? 'selected' : '' }}>Pengembalian (returns.index)</option>
                                        <option value="reports.index" {{ $routeName === 'reports.index' ? 'selected' : '' }}>Laporan (reports.index)</option>
                                        <option value="users.index" {{ $routeName === 'users.index' ? 'selected' : '' }}>Pengguna (users.index)</option>
                                        <option value="dashboard" {{ $routeName === 'dashboard' ? 'selected' : '' }}>Dashboard (dashboard)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
                
                <button type="submit" class="btn-submit">
                    <svg style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Kartu Fitur
                </button>
            </form>
        </div>

        {{-- 4. Stats Settings Panel --}}
        <div id="panel-stats" class="tab-panel" style="display:none;" role="tabpanel" aria-labelledby="tab-stats">
            <h3 style="font-size:18px;font-weight:800;color:var(--text-primary);margin-bottom:20px;">Section Statistik</h3>
            <form action="{{ route('superadmin.landing-page.update-stats') }}" method="POST" style="display:flex;flex-direction:column;gap:20px;">
                @csrf
                
                <div class="form-group">
                    <label for="stats_eyebrow" class="form-label">Label Statistik</label>
                    <input type="text" id="stats_eyebrow" name="stats_eyebrow" value="{{ $settings['stats']['stats_eyebrow'] ?? 'Data Inventaris' }}" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="stats_title" class="form-label">Judul Section Statistik (HTML diizinkan)</label>
                    <textarea id="stats_title" name="stats_title" rows="2" class="form-control" style="resize:vertical;">{{ $settings['stats']['stats_title'] ?? 'Statistik <em>Real-time</em> & Transparan' }}</textarea>
                </div>
                
                <div class="form-group">
                    <label for="stats_description" class="form-label">Deskripsi Section Statistik</label>
                    <textarea id="stats_description" name="stats_description" rows="3" class="form-control" style="resize:vertical;">{{ $settings['stats']['stats_description'] ?? 'Pantau kondisi inventaris, peminjaman aktif, dan pengembalian barang dalam satu dashboard terpusat.' }}</textarea>
                </div>
                
                <div class="form-group">
                    <label for="stats_cta_text" class="form-label">Teks Tombol CTA</label>
                    <input type="text" id="stats_cta_text" name="stats_cta_text" value="{{ $settings['stats']['stats_cta_text'] ?? 'Lihat Statistik Lengkap' }}" class="form-control">
                </div>
                
                <button type="submit" class="btn-submit">
                    <svg style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Section Statistik
                </button>
            </form>
            
            <hr style="border:none;border-top:1px solid var(--border-alt);margin:30px 0;">
            
            <h4 style="font-size:16px;font-weight:700;color:var(--text-primary);margin-bottom:8px;">Label Kartu Statistik</h4>
            <p class="form-helper" style="margin-bottom:16px;">Angka statistik (jumlah barang, kategori, pengguna, sirkulasi) tetap dihitung otomatis dari database.</p>
            <form action="{{ route('superadmin.landing-page.update-stats-data') }}" method="POST" style="display:flex;flex-direction:column;gap:20px;">
                @csrf
                
                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:16px;">
                    @for($i = 0; $i < 4; $i++)
                        @php $stat = $statsData[$i] ?? []; @endphp
                        <div class="sub-card">
                            <h5 class="sub-card-title">
                                <span style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:6px;background:var(--blue-dark);color:#fff;font-size:12px;">{{ $i + 1 }}</span>
                                Kartu Statistik {{ $i + 1 }}
                            </h5>
                            <div class="form-group">
                                <label class="form-label">Label</label>
                                <input type="text" name="stat_label_{{ $i }}" value="{{ $stat['label'] ?? '' }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Sublabel</label>
                                <input type="text" name="stat_sublabel_{{ $i }}" value="{{ $stat['sublabel'] ?? '' }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Badge / Trend</label>
                                <input type="text" name="stat_trend_{{ $i }}" value="{{ $stat['trend'] ?? '' }}" class="form-control">
                            </div>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                                <div class="form-group">
                                    <label class="form-label">Icon</label>
                                    <select name="stat_icon_{{ $i }}" class="form-control">
                                        <option value="box" {{ ($stat['icon'] ?? '') === 'box' ? 'selected' : '' }}>Box</option>
                                        <option value="tag" {{ ($stat['icon'] ?? '') === 'tag' ? 'selected' : '' }}>Tag</option>
                                        <option value="users" {{ ($stat['icon'] ?? '') === 'users' ? 'selected' : '' }}>Users</option>
                                        <option value="swap" {{ ($stat['icon'] ?? '') === 'swap' ? 'selected' : '' }}>Swap</option>
                                        <option value="clock" {{ ($stat['icon'] ?? '') === 'clock' ? 'selected' : '' }}>Clock</option>
                                        <option value="check" {{ ($stat['icon'] ?? '') === 'check' ? 'selected' : '' }}>Check</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Warna Aksen</label>
                                    <select name="stat_color_{{ $i }}" class="form-control">
                                        <option value="blue" {{ ($stat['color'] ?? '') === 'blue' ? 'selected' : '' }}>Biru</option>
                                        <option value="cyan" {{ ($stat['color'] ?? '') === 'cyan' ? 'selected' : '' }}>Cyan</option>
                                        <option value="green" {{ ($stat['color'] ?? '') === 'green' ? 'selected' : '' }}>Hijau</option>
                                        <option value="yellow" {{ ($stat['color'] ?? '') === 'yellow' ? 'selected' : '' }}>Kuning</option>
                                        <option value="purple" {{ ($stat['color'] ?? '') === 'purple' ? 'selected' : '' }}>Ungu</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
                
                <button type="submit" class="btn-submit">
                    <svg style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Data Statistik
                </button>
            </form>
        </div>

        {{-- 5. About Settings Panel --}}
        <div id="panel-about" class="tab-panel" style="display:none;" role="tabpanel" aria-labelledby="tab-about">
            <h3 style="font-size:18px;font-weight:800;color:var(--text-primary);margin-bottom:20px;">Section Tentang</h3>
            <form action="{{ route('superadmin.landing-page.update-about') }}" method="POST" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:20px;">
                @csrf
                
                <div class="form-group">
                    <label for="about_eyebrow" class="form-label">Label Tentang</label>
                    <input type="text" id="about_eyebrow" name="about_eyebrow" value="{{ $settings['about']['about_eyebrow'] ?? 'Tentang SIPBAR' }}" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="about_title" class="form-label">Judul Section Tentang (HTML diizinkan)</label>
                    <textarea id="about_title" name="about_title" rows="2" class="form-control" style="resize:vertical;">{{ $settings['about']['about_title'] ?? 'Platform <em>Digitalisasi</em> Inventaris Sekolah' }}</textarea>
                </div>
                
                <div class="form-group">
                    <label for="about_description" class="form-label">Deskripsi Section Tentang</label>
                    <textarea id="about_description" name="about_description" rows="3" class="form-control" style="resize:vertical;">{{ $settings['about']['about_description'] ?? 'SIPBAR membantu sekolah mengelola inventaris secara modern dengan fitur QR code, approval otomatis, dan laporan real-time. Solusi lengkap untuk manajemen sarana prasarana.' }}</textarea>
                </div>
                
                <div class="form-group">
                    <label for="about_image" class="form-label">Gambar Section Tentang</label>
                    <input type="file" id="about_image" name="about_image" accept="image/*" class="form-control">
                    <p class="form-helper">Format: JPG, PNG, GIF, WEBP. Maks 5MB. Gambar saat ini: <span class="code-badge">{{ $settings['about']['about_image'] ?? '/school-placeholder.jpg' }}</span></p>
                </div>
                
                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:20px;">
                    <div class="form-group">
                        <label for="about_badge_year" class="form-label">Tahun Badge</label>
                        <input type="text" id="about_badge_year" name="about_badge_year" value="{{ $settings['about']['about_badge_year'] ?? '2024' }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="about_badge_name" class="form-label">Nama Badge</label>
                        <input type="text" id="about_badge_name" name="about_badge_name" value="{{ $settings['about']['about_badge_name'] ?? 'SMKN 1 Bangsri' }}" class="form-control">
                    </div>
                </div>
                
                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:20px;">
                    <div class="form-group">
                        <label for="about_caption_label" class="form-label">Label Caption Gambar</label>
                        <input type="text" id="about_caption_label" name="about_caption_label" value="{{ $settings['about']['about_caption_label'] ?? 'Komitmen Kualitas' }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="about_caption_sub" class="form-label">Sub Caption Gambar</label>
                        <input type="text" id="about_caption_sub" name="about_caption_sub" value="{{ $settings['about']['about_caption_sub'] ?? 'Menghadirkan solusi inventaris terbaik untuk mendukung pembelajaran yang berkualitas.' }}" class="form-control">
                    </div>
                </div>
                
                <button type="submit" class="btn-submit">
                    <svg style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Section Tentang
                </button>
            </form>
            
            <hr style="border:none;border-top:1px solid var(--border-alt);margin:30px 0;">
            
            <h4 style="font-size:16px;font-weight:700;color:var(--text-primary);margin-bottom:16px;">Fitur Tentang</h4>
            <form action="{{ route('superadmin.landing-page.update-about-features') }}" method="POST" style="display:flex;flex-direction:column;gap:20px;">
                @csrf
                
                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:16px;">
                    @for($i = 0; $i < 4; $i++)
                        @php $af = $aboutFeatures[$i] ?? []; @endphp
                        <div class="sub-card">
                            <h5 class="sub-card-title">
                                <span style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:6px;background:var(--blue-dark);color:#fff;font-size:12px;">{{ $i + 1 }}</span>
                                Fitur Tentang {{ $i + 1 }}
                            </h5>
                            <div class="form-group">
                                <label class="form-label">Judul</label>
                                <input type="text" name="about_feature_title_{{ $i }}" value="{{ $af['title'] ?? '' }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="about_feature_description_{{ $i }}" rows="2" class="form-control" style="resize:vertical;">{{ $af['description'] ?? '' }}</textarea>
                            </div>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                                <div class="form-group">
                                    <label class="form-label">Icon</label>
                                    <select name="about_feature_icon_{{ $i }}" class="form-control">
                                        <option value="check" {{ ($af['icon'] ?? '') === 'check' ? 'selected' : '' }}>Check</option>
                                        <option value="lightning" {{ ($af['icon'] ?? '') === 'lightning' ? 'selected' : '' }}>Lightning</option>
                                        <option value="clipboard" {{ ($af['icon'] ?? '') === 'clipboard' ? 'selected' : '' }}>Clipboard</option>
                                        <option value="device" {{ ($af['icon'] ?? '') === 'device' ? 'selected' : '' }}>Device</option>
                                        <option value="qr" {{ ($af['icon'] ?? '') === 'qr' ? 'selected' : '' }}>QR Code</option>
                                        <option value="shield" {{ ($af['icon'] ?? '') === 'shield' ? 'selected' : '' }}>Shield</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Kartu Utama?</label>
                                    <select name="about_feature_primary_{{ $i }}" class="form-control">
                                        <option value="0" {{ empty($af['primary']) ? 'selected' : '' }}>Tidak</option>
                                        <option value="1" {{ !empty($af['primary']) ? 'selected' : '' }}>Ya</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
                
                <button type="submit" class="btn-submit">
                    <svg style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Fitur Tentang
                </button>
            </form>
        </div>

        {{-- 6. Footer Settings Panel --}}
        <div id="panel-footer" class="tab-panel" style="display:none;" role="tabpanel" aria-labelledby="tab-footer">
            <h3 style="font-size:18px;font-weight:800;color:var(--text-primary);margin-bottom:20px;">Section Footer</h3>
            <form action="{{ route('superadmin.landing-page.update-footer') }}" method="POST" style="display:flex;flex-direction:column;gap:20px;">
                @csrf
                
                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:20px;">
                    <div class="form-group">
                        <label for="footer_brand_name" class="form-label">Nama Brand Footer</label>
                        <input type="text" id="footer_brand_name" name="footer_brand_name" value="{{ $settings['footer']['footer_brand_name'] ?? 'SIPBAR' }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="footer_brand_subtitle" class="form-label">Subjudul Brand Footer</label>
                        <input type="text" id="footer_brand_subtitle" name="footer_brand_subtitle" value="{{ $settings['footer']['footer_brand_subtitle'] ?? 'SMKN 1 BANGSRI' }}" class="form-control">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="footer_description" class="form-label">Deskripsi Footer</label>
                    <textarea id="footer_description" name="footer_description" rows="3" class="form-control" style="resize:vertical;">{{ $settings['footer']['footer_description'] ?? 'Sistem Informasi Pengelolaan Barang - Solusi modern untuk manajemen inventaris sekolah secara digital dan terintegrasi.' }}</textarea>
                </div>
                
                <div class="form-group">
                    <label for="footer_copyright" class="form-label">Teks Copyright</label>
                    <input type="text" id="footer_copyright" name="footer_copyright" value="{{ $settings['footer']['footer_copyright'] ?? '© 2024 SIPBAR SMKN 1 Bangsri. All rights reserved.' }}" class="form-control">
                </div>

                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:16px;">
                    <div class="form-group">
                        <label for="footer_heading_menu" class="form-label">Judul Kolom Menu</label>
                        <input type="text" id="footer_heading_menu" name="footer_heading_menu" value="{{ $settings['footer']['footer_heading_menu'] ?? 'Menu' }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="footer_heading_features" class="form-label">Judul Kolom Fitur</label>
                        <input type="text" id="footer_heading_features" name="footer_heading_features" value="{{ $settings['footer']['footer_heading_features'] ?? 'Fitur' }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="footer_heading_help" class="form-label">Judul Kolom Bantuan</label>
                        <input type="text" id="footer_heading_help" name="footer_heading_help" value="{{ $settings['footer']['footer_heading_help'] ?? 'Bantuan' }}" class="form-control">
                    </div>
                </div>
                
                <button type="submit" class="btn-submit">
                    <svg style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Footer
                </button>
            </form>
            
            <hr style="border:none;border-top:1px solid var(--border-alt);margin:30px 0;">
            
            <h4 style="font-size:16px;font-weight:700;color:var(--text-primary);margin-bottom:16px;">Link Footer</h4>
            <form action="{{ route('superadmin.landing-page.update-footer-links') }}" method="POST" style="display:flex;flex-direction:column;gap:20px;">
                @csrf
                
                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:20px;">
                    <div class="sub-card">
                        <h5 class="sub-card-title">Kolom Navigasi</h5>
                        @for($i = 0; $i < 4; $i++)
                            <div style="display:flex;flex-direction:column;gap:6px;">
                                <input type="text" name="nav_link_text_{{ $i }}" value="{{ $footerLinks['navigation'][$i]['text'] ?? '' }}" placeholder="Teks Link {{ $i + 1 }}" class="form-control">
                                <input type="text" name="nav_link_url_{{ $i }}" value="{{ $footerLinks['navigation'][$i]['url'] ?? '' }}" placeholder="URL Link {{ $i + 1 }} (contoh: #beranda)" class="form-control">
                            </div>
                        @endfor
                    </div>
                    <div class="sub-card">
                        <h5 class="sub-card-title">Kolom Fitur</h5>
                        @for($i = 0; $i < 5; $i++)
                            <div style="display:flex;flex-direction:column;gap:6px;">
                                <input type="text" name="info_link_text_{{ $i }}" value="{{ $footerLinks['information'][$i]['text'] ?? '' }}" placeholder="Teks Link {{ $i + 1 }}" class="form-control">
                                <input type="text" name="info_link_url_{{ $i }}" value="{{ $footerLinks['information'][$i]['url'] ?? '' }}" placeholder="URL Link {{ $i + 1 }} (contoh: #fitur)" class="form-control">
                            </div>
                        @endfor
                    </div>
                </div>
                
                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:20px;">
                    <div class="sub-card">
                        <h5 class="sub-card-title">Kolom Bantuan & Legal</h5>
                        @for($i = 0; $i < 4; $i++)
                            <div style="display:flex;flex-direction:column;gap:6px;">
                                <input type="text" name="legal_link_text_{{ $i }}" value="{{ $footerLinks['legal'][$i]['text'] ?? '' }}" placeholder="Teks Link {{ $i + 1 }}" class="form-control">
                                <input type="text" name="legal_link_url_{{ $i }}" value="{{ $footerLinks['legal'][$i]['url'] ?? '' }}" placeholder="URL Link {{ $i + 1 }}" class="form-control">
                            </div>
                        @endfor
                    </div>
                    <div class="sub-card">
                        <h5 class="sub-card-title">Media Sosial</h5>
                        @for($i = 0; $i < 3; $i++)
                            <div style="display:flex;flex-direction:column;gap:6px;">
                                <input type="text" name="social_link_text_{{ $i }}" value="{{ $footerLinks['social'][$i]['text'] ?? '' }}" placeholder="Teks Label {{ $i + 1 }}" class="form-control">
                                <input type="text" name="social_link_url_{{ $i }}" value="{{ $footerLinks['social'][$i]['url'] ?? '' }}" placeholder="URL Profil {{ $i + 1 }}" class="form-control">
                                <select name="social_link_icon_{{ $i }}" class="form-control">
                                    <option value="instagram" {{ ($footerLinks['social'][$i]['icon'] ?? '') === 'instagram' ? 'selected' : '' }}>Instagram</option>
                                    <option value="facebook" {{ ($footerLinks['social'][$i]['icon'] ?? '') === 'facebook' ? 'selected' : '' }}>Facebook</option>
                                    <option value="twitter" {{ ($footerLinks['social'][$i]['icon'] ?? '') === 'twitter' ? 'selected' : '' }}>Twitter</option>
                                </select>
                            </div>
                        @endfor
                    </div>
                </div>
                
                <button type="submit" class="btn-submit">
                    <svg style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Link Footer
                </button>
            </form>
        </div>

        {{-- 7. Contact Settings Panel --}}
        <div id="panel-contact" class="tab-panel" style="display:none;" role="tabpanel" aria-labelledby="tab-contact">
            <h3 style="font-size:18px;font-weight:800;color:var(--text-primary);margin-bottom:20px;">Informasi Kontak</h3>
            <form action="{{ route('superadmin.landing-page.update-contact') }}" method="POST" style="display:flex;flex-direction:column;gap:20px;">
                @csrf
                
                <div class="form-group">
                    <label for="contact_email" class="form-label">Email Kontak</label>
                    <input type="email" id="contact_email" name="contact_email" value="{{ $settings['contact']['contact_email'] ?? 'info@smkn1bangsri.sch.id' }}" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="contact_phone" class="form-label">Telepon Kontak</label>
                    <input type="text" id="contact_phone" name="contact_phone" value="{{ $settings['contact']['contact_phone'] ?? '(0291) 123-4567' }}" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="contact_address" class="form-label">Alamat Kontak</label>
                    <textarea id="contact_address" name="contact_address" rows="2" class="form-control" style="resize:vertical;">{{ $settings['contact']['contact_address'] ?? 'Jl. Raya Bangsri No. 123, Jepara, Jawa Tengah' }}</textarea>
                </div>
                
                <button type="submit" class="btn-submit">
                    <svg style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Informasi Kontak
                </button>
            </form>
        </div>

        {{-- 8. Navigation Settings Panel --}}
        <div id="panel-navigation" class="tab-panel" style="display:none;" role="tabpanel" aria-labelledby="tab-navigation">
            <h3 style="font-size:18px;font-weight:800;color:var(--text-primary);margin-bottom:20px;">Navigasi Utama</h3>
            <form action="{{ route('superadmin.landing-page.update-navigation') }}" method="POST" style="display:flex;flex-direction:column;gap:20px;">
                @csrf
                
                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:16px;">
                    <div class="form-group">
                        <label for="nav_link_home" class="form-label">Link Beranda</label>
                        <input type="text" id="nav_link_home" name="nav_link_home" value="{{ $settings['navigation']['nav_link_home'] ?? 'Beranda' }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="nav_link_features" class="form-label">Link Fitur</label>
                        <input type="text" id="nav_link_features" name="nav_link_features" value="{{ $settings['navigation']['nav_link_features'] ?? 'Fitur' }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="nav_link_inventory" class="form-label">Link Inventaris</label>
                        <input type="text" id="nav_link_inventory" name="nav_link_inventory" value="{{ $settings['navigation']['nav_link_inventory'] ?? 'Inventaris' }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="nav_link_about" class="form-label">Link Tentang</label>
                        <input type="text" id="nav_link_about" name="nav_link_about" value="{{ $settings['navigation']['nav_link_about'] ?? 'Tentang' }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="nav_link_help" class="form-label">Link Bantuan</label>
                        <input type="text" id="nav_link_help" name="nav_link_help" value="{{ $settings['navigation']['nav_link_help'] ?? 'Bantuan' }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="nav_link_features_mobile" class="form-label">Fitur (Mobile)</label>
                        <input type="text" id="nav_link_features_mobile" name="nav_link_features_mobile" value="{{ $settings['navigation']['nav_link_features_mobile'] ?? 'Fitur Utama' }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="nav_link_inventory_mobile" class="form-label">Inventaris (Mobile)</label>
                        <input type="text" id="nav_link_inventory_mobile" name="nav_link_inventory_mobile" value="{{ $settings['navigation']['nav_link_inventory_mobile'] ?? 'Katalog Inventaris' }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="nav_link_about_mobile" class="form-label">Tentang (Mobile)</label>
                        <input type="text" id="nav_link_about_mobile" name="nav_link_about_mobile" value="{{ $settings['navigation']['nav_link_about_mobile'] ?? 'Tentang SIPBAR' }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="nav_link_help_mobile" class="form-label">Bantuan (Mobile)</label>
                        <input type="text" id="nav_link_help_mobile" name="nav_link_help_mobile" value="{{ $settings['navigation']['nav_link_help_mobile'] ?? 'Bantuan & Kontak' }}" class="form-control">
                    </div>
                </div>
                
                <button type="submit" class="btn-submit">
                    <svg style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Navigasi
                </button>
            </form>
        </div>

    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all panels
    document.querySelectorAll('.tab-panel').forEach(panel => {
        panel.style.display = 'none';
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.landing-tabs-bar .tab-btn').forEach(btn => {
        btn.classList.remove('active');
        btn.setAttribute('aria-selected', 'false');
    });
    
    // Show selected panel
    const targetPanel = document.getElementById('panel-' + tabName);
    if (targetPanel) {
        targetPanel.style.display = 'block';
    }
    
    // Add active class to selected tab
    const activeTab = document.getElementById('tab-' + tabName);
    if (activeTab) {
        activeTab.classList.add('active');
        activeTab.setAttribute('aria-selected', 'true');
    }

    // Save active tab in session storage for persistence across reloads
    try {
        sessionStorage.setItem('sipbar_landing_tab', tabName);
    } catch (e) {}
}

function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewEl = document.getElementById(previewId);
            if (previewEl) {
                previewEl.src = e.target.result;
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Restore previously opened tab on page reload
document.addEventListener('DOMContentLoaded', function() {
    try {
        const savedTab = sessionStorage.getItem('sipbar_landing_tab');
        if (savedTab && document.getElementById('panel-' + savedTab)) {
            showTab(savedTab);
        }
    } catch (e) {}
});
</script>
@endsection