@extends('layouts.superadmin')

@section('title', 'Kelola Landing Page')
@section('page-heading', 'Kelola Landing Page')

@section('content')
<div style="display:flex;flex-direction:column;gap:22px">

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
            <div style="font-size:13px;color:var(--text-muted)">Edit semua konten landing page yang tampil untuk publik. Perubahan langsung terlihat tanpa perlu deploy ulang.</div>
        </div>
    </div>

    @if(session('success'))
        <div style="background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.3);color:var(--color-success);padding:12px 16px;border-radius:10px;margin-bottom:20px;font-size:13.5px;font-weight:600;display:flex;align-items:center;gap:8px;">
            <svg style="width:18px;height:18px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background:rgba(248,113,113,0.12);border:1px solid rgba(248,113,113,0.35);color:var(--color-danger);padding:12px 16px;border-radius:10px;font-size:13px;">
            <ul style="margin:0;padding-left:18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tabs Navigation --}}
    <div style="background:var(--bg-card);border:1px solid var(--border-alt);border-radius:16px;padding:6px;display:flex;gap:4px;flex-wrap:wrap;">
        <button onclick="showTab('general')" id="tab-general" class="tab-btn active" style="flex:1;min-width:120px;padding:10px 16px;border:none;border-radius:12px;font-size:13px;font-weight:600;cursor:pointer;background:var(--blue-dark);color:#ffffff !important;transition:all .2s;">
            Umum
        </button>
        <button onclick="showTab('hero')" id="tab-hero" class="tab-btn" style="flex:1;min-width:120px;padding:10px 16px;border:none;border-radius:12px;font-size:13px;font-weight:600;cursor:pointer;background:transparent;color:var(--text-muted);transition:all .2s;">
            Hero
        </button>
        <button onclick="showTab('features')" id="tab-features" class="tab-btn" style="flex:1;min-width:120px;padding:10px 16px;border:none;border-radius:12px;font-size:13px;font-weight:600;cursor:pointer;background:transparent;color:var(--text-muted);transition:all .2s;">
            Fitur
        </button>
        <button onclick="showTab('stats')" id="tab-stats" class="tab-btn" style="flex:1;min-width:120px;padding:10px 16px;border:none;border-radius:12px;font-size:13px;font-weight:600;cursor:pointer;background:transparent;color:var(--text-muted);transition:all .2s;">
            Statistik
        </button>
        <button onclick="showTab('about')" id="tab-about" class="tab-btn" style="flex:1;min-width:120px;padding:10px 16px;border:none;border-radius:12px;font-size:13px;font-weight:600;cursor:pointer;background:transparent;color:var(--text-muted);transition:all .2s;">
            Tentang
        </button>
        <button onclick="showTab('footer')" id="tab-footer" class="tab-btn" style="flex:1;min-width:120px;padding:10px 16px;border:none;border-radius:12px;font-size:13px;font-weight:600;cursor:pointer;background:transparent;color:var(--text-muted);transition:all .2s;">
            Footer
        </button>
        <button onclick="showTab('contact')" id="tab-contact" class="tab-btn" style="flex:1;min-width:120px;padding:10px 16px;border:none;border-radius:12px;font-size:13px;font-weight:600;cursor:pointer;background:transparent;color:var(--text-muted);transition:all .2s;">
            Kontak
        </button>
        <button onclick="showTab('navigation')" id="tab-navigation" class="tab-btn" style="flex:1;min-width:120px;padding:10px 16px;border:none;border-radius:12px;font-size:13px;font-weight:600;cursor:pointer;background:transparent;color:var(--text-muted);transition:all .2s;">
            Navigasi
        </button>
    </div>

    {{-- Tab Content Panels --}}
    <div style="background:var(--bg-card);border:1px solid var(--border-alt);border-radius:16px;padding:24px;box-shadow:var(--card-shadow);">

        {{-- General Settings Panel --}}
        <div id="panel-general" class="tab-panel">
            <h3 style="font-size:18px;font-weight:800;color:var(--text-primary);margin-bottom:20px;">Pengaturan Umum</h3>
            <form action="{{ route('superadmin.landing-page.update-general') }}" method="POST" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:20px;">
                @csrf
                
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Nama Sistem</label>
                        <input type="text" name="site_name" value="{{ $settings['general']['site_name'] ?? 'SIPBAR' }}" 
                               style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Subjudul Sistem</label>
                        <input type="text" name="site_subtitle" value="{{ $settings['general']['site_subtitle'] ?? 'SMKN 1 BANGSRI' }}" 
                               style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                    </div>
                </div>
                
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Judul Halaman (SEO)</label>
                    <input type="text" name="site_title" value="{{ $settings['general']['site_title'] ?? 'SIPBAR – Sistem Informasi Pengelolaan Barang' }}" 
                           style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                </div>
                
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Logo Sistem</label>
                    <div style="display:flex;align-items:center;gap:14px;margin-bottom:10px;">
                        <img src="{{ $settings['general']['site_logo'] ?? '/logossmkn1.png' }}" alt="Logo saat ini" style="width:56px;height:56px;object-fit:contain;border-radius:12px;background:#fff;padding:4px;">
                    </div>
                    <input type="file" name="site_logo" accept="image/*" 
                           style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                    <p style="font-size:12px;color:var(--text-muted);margin-top:6px;">Format: JPG, PNG, GIF, WEBP, SVG. Maks 2MB.</p>
                </div>
                
                <button type="submit" style="padding:12px 24px;background:var(--blue-dark);color:#ffffff !important;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;transition:all .2s;">
                    Simpan Pengaturan Umum
                </button>
            </form>
        </div>

        {{-- Hero Settings Panel --}}
        <div id="panel-hero" class="tab-panel" style="display:none;">
            <h3 style="font-size:18px;font-weight:800;color:var(--text-primary);margin-bottom:20px;">Section Hero</h3>
            <form action="{{ route('superadmin.landing-page.update-hero') }}" method="POST" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:20px;">
                @csrf
                
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Badge Hero</label>
                    <input type="text" name="hero_badge" value="{{ $settings['hero']['hero_badge'] ?? 'Sistem Inventaris Modern' }}" 
                           style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                </div>
                
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Judul Hero (HTML diizinkan)</label>
                    <textarea name="hero_title" rows="2" 
                              style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;resize:vertical;">{{ $settings['hero']['hero_title'] ?? 'Kelola Inventaris<br><em>Lebih Mudah</em> & Efisien' }}</textarea>
                </div>
                
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Deskripsi Hero</label>
                    <textarea name="hero_description" rows="3" 
                              style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;resize:vertical;">{{ $settings['hero']['hero_description'] ?? 'Platform web modern untuk mengelola inventaris sekolah secara digital, transparan, dan terintegrasi.' }}</textarea>
                </div>
                
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Background Hero</label>
                    <input type="file" name="hero_background" accept="image/*" 
                           style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                    <p style="font-size:12px;color:var(--text-muted);margin-top:6px;">Format: JPG, PNG, GIF. Max 5MB. Background saat ini: <code>{{ $settings['hero']['hero_background'] ?? '/sekolaheskasaba.jpeg' }}</code></p>
                </div>
                
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Teks Tombol CTA Utama</label>
                        <input type="text" name="hero_cta_text" value="{{ $settings['hero']['hero_cta_text'] ?? 'Dashboard' }}" 
                               style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Teks Tombol CTA Alternatif</label>
                        <input type="text" name="hero_cta_alt_text" value="{{ $settings['hero']['hero_cta_alt_text'] ?? 'Pelajari Lebih Lanjut' }}" 
                               style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                    </div>
                </div>
                
                <button type="submit" style="padding:12px 24px;background:var(--blue-dark);color:#ffffff !important;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;transition:all .2s;">
                    Simpan Section Hero
                </button>
            </form>
        </div>

        {{-- Features Settings Panel --}}
        <div id="panel-features" class="tab-panel" style="display:none;">
            <h3 style="font-size:18px;font-weight:800;color:var(--text-primary);margin-bottom:20px;">Section Fitur</h3>
            <form action="{{ route('superadmin.landing-page.update-features') }}" method="POST" style="display:flex;flex-direction:column;gap:20px;">
                @csrf
                
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Label Fitur</label>
                    <input type="text" name="features_eyebrow" value="{{ $settings['features']['features_eyebrow'] ?? 'Kapabilitas Sistem' }}" 
                           style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                </div>
                
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Judul Section Fitur (HTML diizinkan)</label>
                    <textarea name="features_title" rows="2" 
                              style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;resize:vertical;">{{ $settings['features']['features_title'] ?? 'Tata Kelola Inventaris <em>Cepat & Terintegrasi</em>' }}</textarea>
                </div>
                
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Deskripsi Section Fitur</label>
                    <textarea name="features_description" rows="3" 
                              style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;resize:vertical;">{{ $settings['features']['features_description'] ?? 'Mulai dari pengajuan siswa, approval guru secara instan, hingga serah-terima barang dengan QR code.' }}</textarea>
                </div>
                
                <button type="submit" style="padding:12px 24px;background:var(--blue-dark);color:#ffffff !important;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;transition:all .2s;">
                    Simpan Section Fitur
                </button>
            </form>
            
            <hr style="border:none;border-top:1px solid var(--border-alt);margin:30px 0;">
            
            <h4 style="font-size:16px;font-weight:700;color:var(--text-primary);margin-bottom:16px;">Kartu Fitur</h4>
            <form action="{{ route('superadmin.landing-page.update-feature-cards') }}" method="POST" style="display:flex;flex-direction:column;gap:20px;">
                @csrf
                
                @for($i = 0; $i < 5; $i++)
                    @php $card = $featureCards[$i] ?? []; $icon = $card['icon'] ?? ''; $routeName = $card['route'] ?? ''; $wf = $card['workflow'] ?? []; @endphp
                    <div style="background:var(--bg-card-subtle);border:1px solid var(--border-subtle);border-radius:12px;padding:16px;">
                        <h5 style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:12px;">Kartu {{ $i + 1 }}</h5>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:var(--text-primary);margin-bottom:6px;">Judul</label>
                                <input type="text" name="feature_title_{{ $i }}" value="{{ $card['title'] ?? '' }}" 
                                       style="width:100%;padding:8px 12px;border:1.5px solid var(--border-alt);border-radius:8px;background:var(--input-bg);color:var(--text-primary);font-size:12px;outline:none;">
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:var(--text-primary);margin-bottom:6px;">Icon</label>
                                <select name="feature_icon_{{ $i }}" 
                                        style="width:100%;padding:8px 12px;border:1.5px solid var(--border-alt);border-radius:8px;background:var(--input-bg);color:var(--text-primary);font-size:12px;outline:none;">
                                    <option value="qr" {{ $icon === 'qr' ? 'selected' : '' }}>QR Code</option>
                                    <option value="inventory" {{ $icon === 'inventory' ? 'selected' : '' }}>Inventory</option>
                                    <option value="return" {{ $icon === 'return' ? 'selected' : '' }}>Return</option>
                                    <option value="report" {{ $icon === 'report' ? 'selected' : '' }}>Report</option>
                                    <option value="users" {{ $icon === 'users' ? 'selected' : '' }}>Users</option>
                                    <option value="notification" {{ $icon === 'notification' ? 'selected' : '' }}>Notification</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label style="display:block;font-size:12px;font-weight:600;color:var(--text-primary);margin-bottom:6px;">Deskripsi</label>
                            <textarea name="feature_description_{{ $i }}" rows="2" 
                                      style="width:100%;padding:8px 12px;border:1.5px solid var(--border-alt);border-radius:8px;background:var(--input-bg);color:var(--text-primary);font-size:12px;outline:none;resize:vertical;">{{ $card['description'] ?? '' }}</textarea>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin:12px 0;">
                            <input type="text" name="feature_workflow_1_{{ $i }}" value="{{ $wf[0] ?? '' }}" placeholder="Alur 1 (opsional)" style="width:100%;padding:8px 12px;border:1.5px solid var(--border-alt);border-radius:8px;background:var(--input-bg);color:var(--text-primary);font-size:12px;outline:none;">
                            <input type="text" name="feature_workflow_2_{{ $i }}" value="{{ $wf[1] ?? '' }}" placeholder="Alur 2 (opsional)" style="width:100%;padding:8px 12px;border:1.5px solid var(--border-alt);border-radius:8px;background:var(--input-bg);color:var(--text-primary);font-size:12px;outline:none;">
                            <input type="text" name="feature_workflow_3_{{ $i }}" value="{{ $wf[2] ?? '' }}" placeholder="Alur 3 (opsional)" style="width:100%;padding:8px 12px;border:1.5px solid var(--border-alt);border-radius:8px;background:var(--input-bg);color:var(--text-primary);font-size:12px;outline:none;">
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:var(--text-primary);margin-bottom:6px;">Teks Link</label>
                                <input type="text" name="feature_link_text_{{ $i }}" value="{{ $card['link_text'] ?? '' }}" 
                                       style="width:100%;padding:8px 12px;border:1.5px solid var(--border-alt);border-radius:8px;background:var(--input-bg);color:var(--text-primary);font-size:12px;outline:none;">
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:var(--text-primary);margin-bottom:6px;">Route</label>
                                <select name="feature_route_{{ $i }}" 
                                        style="width:100%;padding:8px 12px;border:1.5px solid var(--border-alt);border-radius:8px;background:var(--input-bg);color:var(--text-primary);font-size:12px;outline:none;">
                                    <option value="loans.index" {{ $routeName === 'loans.index' ? 'selected' : '' }}>Peminjaman</option>
                                    <option value="inventory.index" {{ $routeName === 'inventory.index' ? 'selected' : '' }}>Inventaris</option>
                                    <option value="returns.index" {{ $routeName === 'returns.index' ? 'selected' : '' }}>Pengembalian</option>
                                    <option value="reports.index" {{ $routeName === 'reports.index' ? 'selected' : '' }}>Laporan</option>
                                    <option value="users.index" {{ $routeName === 'users.index' ? 'selected' : '' }}>Pengguna</option>
                                    <option value="dashboard" {{ $routeName === 'dashboard' ? 'selected' : '' }}>Dashboard</option>
                                </select>
                            </div>
                        </div>
                    </div>
                @endfor
                
                <button type="submit" style="padding:12px 24px;background:var(--blue-dark);color:#ffffff !important;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;transition:all .2s;">
                    Simpan Kartu Fitur
                </button>
            </form>
        </div>

        {{-- Stats Settings Panel --}}
        <div id="panel-stats" class="tab-panel" style="display:none;">
            <h3 style="font-size:18px;font-weight:800;color:var(--text-primary);margin-bottom:20px;">Section Statistik</h3>
            <form action="{{ route('superadmin.landing-page.update-stats') }}" method="POST" style="display:flex;flex-direction:column;gap:20px;">
                @csrf
                
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Label Statistik</label>
                    <input type="text" name="stats_eyebrow" value="{{ $settings['stats']['stats_eyebrow'] ?? 'Data Inventaris' }}" 
                           style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                </div>
                
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Judul Section Statistik (HTML diizinkan)</label>
                    <textarea name="stats_title" rows="2" 
                              style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;resize:vertical;">{{ $settings['stats']['stats_title'] ?? 'Statistik <em>Real-time</em> & Transparan' }}</textarea>
                </div>
                
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Deskripsi Section Statistik</label>
                    <textarea name="stats_description" rows="3" 
                              style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;resize:vertical;">{{ $settings['stats']['stats_description'] ?? 'Pantau kondisi inventaris, peminjaman aktif, dan pengembalian barang dalam satu dashboard terpusat.' }}</textarea>
                </div>
                
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Teks Tombol CTA</label>
                    <input type="text" name="stats_cta_text" value="{{ $settings['stats']['stats_cta_text'] ?? 'Lihat Statistik Lengkap' }}" 
                           style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                </div>
                
                <button type="submit" style="padding:12px 24px;background:var(--blue-dark);color:#ffffff !important;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;transition:all .2s;">
                    Simpan Section Statistik
                </button>
            </form>
            
            <hr style="border:none;border-top:1px solid var(--border-alt);margin:30px 0;">
            
            <h4 style="font-size:16px;font-weight:700;color:var(--text-primary);margin-bottom:8px;">Label Kartu Statistik</h4>
            <p style="font-size:12px;color:var(--text-muted);margin-bottom:16px;">Angka (jumlah barang, kategori, pengguna, sirkulasi) tetap diambil otomatis dari database.</p>
            <form action="{{ route('superadmin.landing-page.update-stats-data') }}" method="POST" style="display:flex;flex-direction:column;gap:20px;">
                @csrf
                
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;">
                    @for($i = 0; $i < 4; $i++)
                        @php $stat = $statsData[$i] ?? []; @endphp
                        <div style="background:var(--bg-card-subtle);border:1px solid var(--border-subtle);border-radius:12px;padding:16px;">
                            <h5 style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:12px;">Kartu {{ $i + 1 }}</h5>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:var(--text-primary);margin-bottom:6px;">Label</label>
                                <input type="text" name="stat_label_{{ $i }}" value="{{ $stat['label'] ?? '' }}" 
                                       style="width:100%;padding:8px 12px;border:1.5px solid var(--border-alt);border-radius:8px;background:var(--input-bg);color:var(--text-primary);font-size:12px;outline:none;">
                            </div>
                            <div style="margin-top:10px;">
                                <label style="display:block;font-size:12px;font-weight:600;color:var(--text-primary);margin-bottom:6px;">Sublabel</label>
                                <input type="text" name="stat_sublabel_{{ $i }}" value="{{ $stat['sublabel'] ?? '' }}" 
                                       style="width:100%;padding:8px 12px;border:1.5px solid var(--border-alt);border-radius:8px;background:var(--input-bg);color:var(--text-primary);font-size:12px;outline:none;">
                            </div>
                            <div style="margin-top:10px;">
                                <label style="display:block;font-size:12px;font-weight:600;color:var(--text-primary);margin-bottom:6px;">Badge / Trend</label>
                                <input type="text" name="stat_trend_{{ $i }}" value="{{ $stat['trend'] ?? '' }}" 
                                       style="width:100%;padding:8px 12px;border:1.5px solid var(--border-alt);border-radius:8px;background:var(--input-bg);color:var(--text-primary);font-size:12px;outline:none;">
                            </div>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:10px;">
                                <div>
                                    <label style="display:block;font-size:12px;font-weight:600;color:var(--text-primary);margin-bottom:6px;">Icon</label>
                                    <select name="stat_icon_{{ $i }}" 
                                            style="width:100%;padding:8px 12px;border:1.5px solid var(--border-alt);border-radius:8px;background:var(--input-bg);color:var(--text-primary);font-size:12px;outline:none;">
                                        <option value="box" {{ ($stat['icon'] ?? '') === 'box' ? 'selected' : '' }}>Box</option>
                                        <option value="tag" {{ ($stat['icon'] ?? '') === 'tag' ? 'selected' : '' }}>Tag</option>
                                        <option value="users" {{ ($stat['icon'] ?? '') === 'users' ? 'selected' : '' }}>Users</option>
                                        <option value="swap" {{ ($stat['icon'] ?? '') === 'swap' ? 'selected' : '' }}>Swap</option>
                                        <option value="clock" {{ ($stat['icon'] ?? '') === 'clock' ? 'selected' : '' }}>Clock</option>
                                        <option value="check" {{ ($stat['icon'] ?? '') === 'check' ? 'selected' : '' }}>Check</option>
                                    </select>
                                </div>
                                <div>
                                    <label style="display:block;font-size:12px;font-weight:600;color:var(--text-primary);margin-bottom:6px;">Warna</label>
                                    <select name="stat_color_{{ $i }}" 
                                            style="width:100%;padding:8px 12px;border:1.5px solid var(--border-alt);border-radius:8px;background:var(--input-bg);color:var(--text-primary);font-size:12px;outline:none;">
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
                
                <button type="submit" style="padding:12px 24px;background:var(--blue-dark);color:#ffffff !important;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;transition:all .2s;">
                    Simpan Data Statistik
                </button>
            </form>
        </div>

        {{-- About Settings Panel --}}
        <div id="panel-about" class="tab-panel" style="display:none;">
            <h3 style="font-size:18px;font-weight:800;color:var(--text-primary);margin-bottom:20px;">Section Tentang</h3>
            <form action="{{ route('superadmin.landing-page.update-about') }}" method="POST" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:20px;">
                @csrf
                
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Label Tentang</label>
                    <input type="text" name="about_eyebrow" value="{{ $settings['about']['about_eyebrow'] ?? 'Tentang SIPBAR' }}" 
                           style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                </div>
                
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Judul Section Tentang (HTML diizinkan)</label>
                    <textarea name="about_title" rows="2" 
                              style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;resize:vertical;">{{ $settings['about']['about_title'] ?? 'Platform <em>Digitalisasi</em> Inventaris Sekolah' }}</textarea>
                </div>
                
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Deskripsi Section Tentang</label>
                    <textarea name="about_description" rows="3" 
                              style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;resize:vertical;">{{ $settings['about']['about_description'] ?? 'SIPBAR membantu sekolah mengelola inventaris secara modern dengan fitur QR code, approval otomatis, dan laporan real-time. Solusi lengkap untuk manajemen sarana prasarana.' }}</textarea>
                </div>
                
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Gambar Section Tentang</label>
                    <input type="file" name="about_image" accept="image/*" 
                           style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                    <p style="font-size:12px;color:var(--text-muted);margin-top:6px;">Format: JPG, PNG, GIF. Max 5MB. Gambar saat ini: <code>{{ $settings['about']['about_image'] ?? '/school-placeholder.jpg' }}</code></p>
                </div>
                
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Tahun Badge</label>
                        <input type="text" name="about_badge_year" value="{{ $settings['about']['about_badge_year'] ?? '2024' }}" 
                               style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Nama Badge</label>
                        <input type="text" name="about_badge_name" value="{{ $settings['about']['about_badge_name'] ?? 'SMKN 1 Bangsri' }}" 
                               style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                    </div>
                </div>
                
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Label Caption Gambar</label>
                        <input type="text" name="about_caption_label" value="{{ $settings['about']['about_caption_label'] ?? 'Komitmen Kualitas' }}" 
                               style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Sub Caption Gambar</label>
                        <input type="text" name="about_caption_sub" value="{{ $settings['about']['about_caption_sub'] ?? 'Menghadirkan solusi inventaris terbaik untuk mendukung pembelajaran yang berkualitas.' }}" 
                               style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                    </div>
                </div>
                
                <button type="submit" style="padding:12px 24px;background:var(--blue-dark);color:#ffffff !important;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;transition:all .2s;">
                    Simpan Section Tentang
                </button>
            </form>
            
            <hr style="border:none;border-top:1px solid var(--border-alt);margin:30px 0;">
            
            <h4 style="font-size:16px;font-weight:700;color:var(--text-primary);margin-bottom:16px;">Fitur Tentang</h4>
            <form action="{{ route('superadmin.landing-page.update-about-features') }}" method="POST" style="display:flex;flex-direction:column;gap:20px;">
                @csrf
                
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;">
                    @for($i = 0; $i < 4; $i++)
                        @php $af = $aboutFeatures[$i] ?? []; @endphp
                        <div style="background:var(--bg-card-subtle);border:1px solid var(--border-subtle);border-radius:12px;padding:16px;">
                            <h5 style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:12px;">Fitur {{ $i + 1 }}</h5>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:var(--text-primary);margin-bottom:6px;">Judul</label>
                                <input type="text" name="about_feature_title_{{ $i }}" value="{{ $af['title'] ?? '' }}" 
                                       style="width:100%;padding:8px 12px;border:1.5px solid var(--border-alt);border-radius:8px;background:var(--input-bg);color:var(--text-primary);font-size:12px;outline:none;">
                            </div>
                            <div>
                                <label style="display:block;font-size:12px;font-weight:600;color:var(--text-primary);margin-bottom:6px;">Deskripsi</label>
                                <textarea name="about_feature_description_{{ $i }}" rows="2" 
                                          style="width:100%;padding:8px 12px;border:1.5px solid var(--border-alt);border-radius:8px;background:var(--input-bg);color:var(--text-primary);font-size:12px;outline:none;resize:vertical;">{{ $af['description'] ?? '' }}</textarea>
                            </div>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                                <div>
                                    <label style="display:block;font-size:12px;font-weight:600;color:var(--text-primary);margin-bottom:6px;">Icon</label>
                                    <select name="about_feature_icon_{{ $i }}" 
                                            style="width:100%;padding:8px 12px;border:1.5px solid var(--border-alt);border-radius:8px;background:var(--input-bg);color:var(--text-primary);font-size:12px;outline:none;">
                                        <option value="check" {{ ($af['icon'] ?? '') === 'check' ? 'selected' : '' }}>Check</option>
                                        <option value="lightning" {{ ($af['icon'] ?? '') === 'lightning' ? 'selected' : '' }}>Lightning</option>
                                        <option value="clipboard" {{ ($af['icon'] ?? '') === 'clipboard' ? 'selected' : '' }}>Clipboard</option>
                                        <option value="device" {{ ($af['icon'] ?? '') === 'device' ? 'selected' : '' }}>Device</option>
                                        <option value="qr" {{ ($af['icon'] ?? '') === 'qr' ? 'selected' : '' }}>QR Code</option>
                                        <option value="shield" {{ ($af['icon'] ?? '') === 'shield' ? 'selected' : '' }}>Shield</option>
                                    </select>
                                </div>
                                <div>
                                    <label style="display:block;font-size:12px;font-weight:600;color:var(--text-primary);margin-bottom:6px;">Jadikan Kartu Utama?</label>
                                    <select name="about_feature_primary_{{ $i }}" 
                                            style="width:100%;padding:8px 12px;border:1.5px solid var(--border-alt);border-radius:8px;background:var(--input-bg);color:var(--text-primary);font-size:12px;outline:none;">
                                        <option value="0" {{ empty($af['primary']) ? 'selected' : '' }}>Tidak</option>
                                        <option value="1" {{ !empty($af['primary']) ? 'selected' : '' }}>Ya</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
                
                <button type="submit" style="padding:12px 24px;background:var(--blue-dark);color:#ffffff !important;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;transition:all .2s;">
                    Simpan Fitur Tentang
                </button>
            </form>
        </div>

        {{-- Footer Settings Panel --}}
        <div id="panel-footer" class="tab-panel" style="display:none;">
            <h3 style="font-size:18px;font-weight:800;color:var(--text-primary);margin-bottom:20px;">Section Footer</h3>
            <form action="{{ route('superadmin.landing-page.update-footer') }}" method="POST" style="display:flex;flex-direction:column;gap:20px;">
                @csrf
                
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Nama Brand Footer</label>
                        <input type="text" name="footer_brand_name" value="{{ $settings['footer']['footer_brand_name'] ?? 'SIPBAR' }}" 
                               style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Subjudul Brand Footer</label>
                        <input type="text" name="footer_brand_subtitle" value="{{ $settings['footer']['footer_brand_subtitle'] ?? 'SMKN 1 BANGSRI' }}" 
                               style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                    </div>
                </div>
                
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Deskripsi Footer</label>
                    <textarea name="footer_description" rows="3" 
                              style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;resize:vertical;">{{ $settings['footer']['footer_description'] ?? 'Sistem Informasi Pengelolaan Barang - Solusi modern untuk manajemen inventaris sekolah secara digital dan terintegrasi.' }}</textarea>
                </div>
                
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Teks Copyright</label>
                    <input type="text" name="footer_copyright" value="{{ $settings['footer']['footer_copyright'] ?? '© 2024 SIPBAR SMKN 1 Bangsri. All rights reserved.' }}" 
                           style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Judul Kolom Menu</label>
                        <input type="text" name="footer_heading_menu" value="{{ $settings['footer']['footer_heading_menu'] ?? 'Menu' }}" style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Judul Kolom Fitur</label>
                        <input type="text" name="footer_heading_features" value="{{ $settings['footer']['footer_heading_features'] ?? 'Fitur' }}" style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Judul Kolom Bantuan</label>
                        <input type="text" name="footer_heading_help" value="{{ $settings['footer']['footer_heading_help'] ?? 'Bantuan' }}" style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                    </div>
                </div>
                
                <button type="submit" style="padding:12px 24px;background:var(--blue-dark);color:#ffffff !important;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;transition:all .2s;">
                    Simpan Footer
                </button>
            </form>
            
            <hr style="border:none;border-top:1px solid var(--border-alt);margin:30px 0;">
            
            <h4 style="font-size:16px;font-weight:700;color:var(--text-primary);margin-bottom:16px;">Link Footer</h4>
            <form action="{{ route('superadmin.landing-page.update-footer-links') }}" method="POST" style="display:flex;flex-direction:column;gap:20px;">
                @csrf
                
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:20px;">
                    <div>
                        <h5 style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:12px;">Navigasi</h5>
                        @for($i = 0; $i < 4; $i++)
                            <div style="margin-bottom:12px;">
                                <input type="text" name="nav_link_text_{{ $i }}" value="{{ $footerLinks['navigation'][$i]['text'] ?? '' }}" placeholder="Teks Link {{ $i + 1 }}" 
                                       style="width:100%;padding:8px 12px;border:1.5px solid var(--border-alt);border-radius:8px;background:var(--input-bg);color:var(--text-primary);font-size:12px;outline:none;margin-bottom:6px;">
                                <input type="text" name="nav_link_url_{{ $i }}" value="{{ $footerLinks['navigation'][$i]['url'] ?? '' }}" placeholder="URL Link {{ $i + 1 }}" 
                                       style="width:100%;padding:8px 12px;border:1.5px solid var(--border-alt);border-radius:8px;background:var(--input-bg);color:var(--text-primary);font-size:12px;outline:none;">
                            </div>
                        @endfor
                    </div>
                    <div>
                        <h5 style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:12px;">Fitur</h5>
                        @for($i = 0; $i < 5; $i++)
                            <div style="margin-bottom:12px;">
                                <input type="text" name="info_link_text_{{ $i }}" value="{{ $footerLinks['information'][$i]['text'] ?? '' }}" placeholder="Teks Link {{ $i + 1 }}" 
                                       style="width:100%;padding:8px 12px;border:1.5px solid var(--border-alt);border-radius:8px;background:var(--input-bg);color:var(--text-primary);font-size:12px;outline:none;margin-bottom:6px;">
                                <input type="text" name="info_link_url_{{ $i }}" value="{{ $footerLinks['information'][$i]['url'] ?? '' }}" placeholder="URL Link {{ $i + 1 }}" 
                                       style="width:100%;padding:8px 12px;border:1.5px solid var(--border-alt);border-radius:8px;background:var(--input-bg);color:var(--text-primary);font-size:12px;outline:none;">
                            </div>
                        @endfor
                    </div>
                </div>
                
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                    <div>
                        <h5 style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:12px;">Bantuan</h5>
                        @for($i = 0; $i < 4; $i++)
                            <div style="margin-bottom:12px;">
                                <input type="text" name="legal_link_text_{{ $i }}" value="{{ $footerLinks['legal'][$i]['text'] ?? '' }}" placeholder="Teks Link {{ $i + 1 }}" 
                                       style="width:100%;padding:8px 12px;border:1.5px solid var(--border-alt);border-radius:8px;background:var(--input-bg);color:var(--text-primary);font-size:12px;outline:none;margin-bottom:6px;">
                                <input type="text" name="legal_link_url_{{ $i }}" value="{{ $footerLinks['legal'][$i]['url'] ?? '' }}" placeholder="URL Link {{ $i + 1 }}" 
                                       style="width:100%;padding:8px 12px;border:1.5px solid var(--border-alt);border-radius:8px;background:var(--input-bg);color:var(--text-primary);font-size:12px;outline:none;">
                            </div>
                        @endfor
                    </div>
                    <div>
                        <h5 style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:12px;">Social Media</h5>
                        @for($i = 0; $i < 3; $i++)
                            <div style="margin-bottom:12px;">
                                <input type="text" name="social_link_text_{{ $i }}" value="{{ $footerLinks['social'][$i]['text'] ?? '' }}" placeholder="Teks Link {{ $i + 1 }}" 
                                       style="width:100%;padding:8px 12px;border:1.5px solid var(--border-alt);border-radius:8px;background:var(--input-bg);color:var(--text-primary);font-size:12px;outline:none;margin-bottom:6px;">
                                <input type="text" name="social_link_url_{{ $i }}" value="{{ $footerLinks['social'][$i]['url'] ?? '' }}" placeholder="URL Link {{ $i + 1 }}" 
                                       style="width:100%;padding:8px 12px;border:1.5px solid var(--border-alt);border-radius:8px;background:var(--input-bg);color:var(--text-primary);font-size:12px;outline:none;margin-bottom:6px;">
                                <select name="social_link_icon_{{ $i }}" 
                                        style="width:100%;padding:8px 12px;border:1.5px solid var(--border-alt);border-radius:8px;background:var(--input-bg);color:var(--text-primary);font-size:12px;outline:none;">
                                    <option value="instagram" {{ ($footerLinks['social'][$i]['icon'] ?? '') === 'instagram' ? 'selected' : '' }}>Instagram</option>
                                    <option value="facebook" {{ ($footerLinks['social'][$i]['icon'] ?? '') === 'facebook' ? 'selected' : '' }}>Facebook</option>
                                    <option value="twitter" {{ ($footerLinks['social'][$i]['icon'] ?? '') === 'twitter' ? 'selected' : '' }}>Twitter</option>
                                </select>
                            </div>
                        @endfor
                    </div>
                </div>
                
                <button type="submit" style="padding:12px 24px;background:var(--blue-dark);color:#ffffff !important;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;transition:all .2s;">
                    Simpan Link Footer
                </button>
            </form>
        </div>

        {{-- Contact Settings Panel --}}
        <div id="panel-contact" class="tab-panel" style="display:none;">
            <h3 style="font-size:18px;font-weight:800;color:var(--text-primary);margin-bottom:20px;">Informasi Kontak</h3>
            <form action="{{ route('superadmin.landing-page.update-contact') }}" method="POST" style="display:flex;flex-direction:column;gap:20px;">
                @csrf
                
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Email Kontak</label>
                    <input type="email" name="contact_email" value="{{ $settings['contact']['contact_email'] ?? 'info@smkn1bangsri.sch.id' }}" 
                           style="width:100%;padding:10px 14px;border:1.1px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                </div>
                
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Telepon Kontak</label>
                    <input type="text" name="contact_phone" value="{{ $settings['contact']['contact_phone'] ?? '(0291) 123-4567' }}" 
                           style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                </div>
                
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Alamat Kontak</label>
                    <textarea name="contact_address" rows="2" 
                              style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;resize:vertical;">{{ $settings['contact']['contact_address'] ?? 'Jl. Raya Bangsri No. 123, Jepara, Jawa Tengah' }}</textarea>
                </div>
                
                <button type="submit" style="padding:12px 24px;background:var(--blue-dark);color:#ffffff !important;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;transition:all .2s;">
                    Simpan Informasi Kontak
                </button>
            </form>
        </div>

        {{-- Navigation Settings Panel --}}
        <div id="panel-navigation" class="tab-panel" style="display:none;">
            <h3 style="font-size:18px;font-weight:800;color:var(--text-primary);margin-bottom:20px;">Navigasi Utama</h3>
            <form action="{{ route('superadmin.landing-page.update-navigation') }}" method="POST" style="display:flex;flex-direction:column;gap:20px;">
                @csrf
                
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Link Beranda</label>
                        <input type="text" name="nav_link_home" value="{{ $settings['navigation']['nav_link_home'] ?? 'Beranda' }}" 
                               style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Link Fitur</label>
                        <input type="text" name="nav_link_features" value="{{ $settings['navigation']['nav_link_features'] ?? 'Fitur' }}" 
                               style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Link Inventaris</label>
                        <input type="text" name="nav_link_inventory" value="{{ $settings['navigation']['nav_link_inventory'] ?? 'Inventaris' }}" 
                               style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Link Tentang</label>
                        <input type="text" name="nav_link_about" value="{{ $settings['navigation']['nav_link_about'] ?? 'Tentang' }}" 
                               style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Link Bantuan</label>
                        <input type="text" name="nav_link_help" value="{{ $settings['navigation']['nav_link_help'] ?? 'Bantuan' }}" 
                               style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Fitur (Mobile)</label>
                        <input type="text" name="nav_link_features_mobile" value="{{ $settings['navigation']['nav_link_features_mobile'] ?? 'Fitur Utama' }}" 
                               style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Inventaris (Mobile)</label>
                        <input type="text" name="nav_link_inventory_mobile" value="{{ $settings['navigation']['nav_link_inventory_mobile'] ?? 'Katalog Inventaris' }}" 
                               style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Tentang (Mobile)</label>
                        <input type="text" name="nav_link_about_mobile" value="{{ $settings['navigation']['nav_link_about_mobile'] ?? 'Tentang SIPBAR' }}" 
                               style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Bantuan (Mobile)</label>
                        <input type="text" name="nav_link_help_mobile" value="{{ $settings['navigation']['nav_link_help_mobile'] ?? 'Bantuan & Kontak' }}" 
                               style="width:100%;padding:10px 14px;border:1.5px solid var(--border-alt);border-radius:10px;background:var(--input-bg);color:var(--text-primary);font-size:13px;outline:none;">
                    </div>
                </div>
                
                <button type="submit" style="padding:12px 24px;background:var(--blue-dark);color:#ffffff !important;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;transition:all .2s;">
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
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
        btn.style.background = 'transparent';
        btn.style.color = 'var(--text-muted)';
    });
    
    // Show selected panel
    document.getElementById('panel-' + tabName).style.display = 'block';
    
    // Add active class to selected tab
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.add('active');
    activeTab.style.background = 'var(--blue-dark)';
    activeTab.style.color = '#ffffff !important';
}
</script>
@endsection