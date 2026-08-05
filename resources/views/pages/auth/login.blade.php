<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk – SIPBAR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; background: #eef2ff; -webkit-font-smoothing: antialiased; }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: #eef2ff;
        }

        .login-card {
            display: flex;
            width: 100%;
            max-width: 900px;
            min-height: 560px;
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 24px 80px rgba(99, 102, 241, 0.12), 0 4px 24px rgba(0,0,0,.06);
            overflow: hidden;
        }

        /* ---- Left Panel ---- */
        .login-left {
            width: 340px;
            flex-shrink: 0;
            background: linear-gradient(145deg, #a78bfa 0%, #6366f1 40%, #3b82f6 75%, #06b6d4 100%);
            padding: 36px 32px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        .login-left::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 200px; height: 200px;
            background: rgba(255,255,255,.08);
            border-radius: 50%;
        }
        .login-left::after {
            content: '';
            position: absolute;
            bottom: -80px; left: -40px;
            width: 240px; height: 240px;
            background: rgba(255,255,255,.05);
            border-radius: 50%;
        }
        .left-star {
            width: 40px; height: 40px;
            background: rgba(255,255,255,.25);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; color: #fff; font-weight: 900;
            position: relative; z-index: 1;
        }
        .left-bottom { position: relative; z-index: 1; }
        .left-tagline {
            font-size: 12px; font-weight: 500;
            color: rgba(255,255,255,.75);
            margin-bottom: 10px;
        }
        .left-headline {
            font-size: 24px; font-weight: 800;
            color: #fff; line-height: 1.3;
        }
        .left-dots {
            display: flex; gap: 6px; margin-top: 24px;
        }
        .left-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: rgba(255,255,255,.4);
        }
        .left-dot.active { background: #fff; width: 22px; border-radius: 4px; }

        /* ---- Right Panel ---- */
        .login-right {
            flex: 1;
            padding: 48px 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .right-star {
            font-size: 26px; color: #6366f1; font-weight: 900;
            margin-bottom: 12px; line-height: 1;
        }
        .right-title {
            font-size: 28px; font-weight: 800;
            color: #0f172a; margin-bottom: 8px;
        }
        .right-sub {
            font-size: 14px; color: #64748b;
            line-height: 1.6; margin-bottom: 32px;
            max-width: 320px;
        }

        /* Form */
        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block;
            font-size: 13px; font-weight: 600;
            color: #374151; margin-bottom: 6px;
        }
        .form-control {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px; color: #0f172a;
            background: #fff;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
            appearance: none;
            -webkit-appearance: none;
        }
        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .12);
        }
        .form-control::placeholder { color: #94a3b8; }
        .form-control.is-invalid { border-color: #ef4444; }

        .form-select-wrap { position: relative; }
        .form-select-wrap::after {
            content: '';
            position: absolute;
            right: 14px; top: 50%;
            transform: translateY(-50%);
            width: 0; height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 6px solid #94a3b8;
            pointer-events: none;
        }

        .password-wrap { position: relative; }
        .password-toggle {
            position: absolute; right: 12px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            cursor: pointer; color: #94a3b8;
            padding: 4px; line-height: 1;
        }
        .password-toggle:hover { color: #6366f1; }

        .error-msg {
            font-size: 12px; color: #ef4444;
            margin-top: 5px; display: flex;
            align-items: center; gap: 4px;
        }

        .btn-login {
            width: 100%;
            padding: 13px;
            background: #6366f1;
            color: #fff;
            font-size: 15px; font-weight: 700;
            border: none; border-radius: 10px;
            cursor: pointer;
            transition: background .2s, transform .1s, box-shadow .2s;
            margin-top: 8px;
            box-shadow: 0 4px 14px rgba(99, 102, 241, .35);
        }
        .btn-login:hover { background: #4f46e5; box-shadow: 0 6px 20px rgba(99, 102, 241, .4); }
        .btn-login:active { transform: scale(.99); }

        .forgot-link {
            font-size: 12px; color: #6366f1;
            text-decoration: none; font-weight: 600;
        }
        .forgot-link:hover { text-decoration: underline; }

        .row-between {
            display: flex; align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .remember-label {
            display: flex; align-items: center; gap: 7px;
            font-size: 13px; color: #475569; cursor: pointer;
        }
        .remember-label input[type="checkbox"] {
            width: 15px; height: 15px;
            accent-color: #6366f1; cursor: pointer;
        }

        /* Dummy creds box */
        .dummy-box {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 24px;
        }
        .dummy-title {
            font-size: 11px; font-weight: 700;
            color: #0284c7; margin-bottom: 8px;
            display: flex; align-items: center; gap: 5px;
            text-transform: uppercase; letter-spacing: .05em;
        }
        .dummy-row {
            display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 4px;
        }
        .dummy-chip {
            font-size: 11px; background: #e0f2fe;
            color: #0369a1; padding: 2px 8px;
            border-radius: 999px; font-weight: 500;
        }
        .dummy-chip.role { background: #e0e7ff; color: #4338ca; }

        /* Back link */
        .back-link {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 13px; color: #64748b;
            text-decoration: none; margin-bottom: 28px;
            transition: color .2s;
        }
        .back-link:hover { color: #6366f1; }

        @media (max-width: 768px) {
            .login-left { display: none; }
            .login-card { max-width: 480px; }
            .login-right { padding: 36px 28px; }
        }
        @media (max-width: 480px) {
            .login-right { padding: 28px 20px; }
            .login-wrapper { padding: 16px; }
        }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="login-card">

        {{-- ===== LEFT PANEL ===== --}}
        <div class="login-left">
            <div class="left-star">✦</div>
            <div class="left-bottom">
                <div class="left-tagline">Selamat datang di</div>
                <div class="left-headline">Kelola inventaris sekolah lebih mudah dan terorganisir</div>
                <div class="left-dots">
                    <div class="left-dot active"></div>
                    <div class="left-dot"></div>
                    <div class="left-dot"></div>
                </div>
            </div>
        </div>

        {{-- ===== RIGHT PANEL ===== --}}
        <div class="login-right">
            <a href="{{ route('home') }}" class="back-link">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Beranda
            </a>

            <div class="right-star">✦</div>
            <div class="right-title">Masuk ke Akun</div>
            <div class="right-sub">Akses sistem inventaris sekolah dengan mudah, cepat, dan aman.</div>

            {{-- Dummy Credentials Info --}}
            <div class="dummy-box">
                <div class="dummy-title">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Demo Credentials
                </div>
                <div class="dummy-row">
                    <span class="dummy-chip role">Admin</span>
                    <span class="dummy-chip">Email: admin@sipbar.sch.id</span>
                    <span class="dummy-chip">Password: admin123</span>
                </div>
                <div class="dummy-row">
                    <span class="dummy-chip role">Guru</span>
                    <span class="dummy-chip">NIP: 198505152010011001</span>
                    <span class="dummy-chip">No. HP: sesuai data guru</span>
                </div>
                <div class="dummy-row">
                    <span class="dummy-chip role">Siswa</span>
                    <span class="dummy-chip">NIS: 2024001</span>
                    <span class="dummy-chip">No. HP: sesuai data siswa</span>
                </div>
            </div>

            {{-- Session status --}}
            @if (session('status'))
                <div style="background:#f0fdf4;border:1px solid #86efac;border-radius:8px;padding:10px 14px;font-size:13px;color:#16a34a;margin-bottom:16px">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login.store') }}" id="loginForm">
                @csrf

                {{-- Role Dropdown --}}
                <div class="form-group">
                    <label class="form-label" for="role">Login sebagai</label>
                    <div class="form-select-wrap">
                        <select name="role" id="role" class="form-control" onchange="handleRoleChange(this.value)" required>
                            <option value="admin" {{ old('role', 'admin') === 'admin' ? 'selected' : '' }}>👤 Admin</option>
                            <option value="guru"  {{ old('role') === 'guru'  ? 'selected' : '' }}>👨‍🏫 Guru</option>
                            <option value="siswa" {{ old('role') === 'siswa' ? 'selected' : '' }}>🎓 Siswa</option>
                        </select>
                    </div>
                </div>

                {{-- Dynamic Identifier Field --}}
                <div class="form-group" id="identifierGroup">

                    {{-- ADMIN: Email --}}
                    <div id="field-admin" style="{{ old('role','admin') !== 'admin' ? 'display:none' : '' }}">
                        <label class="form-label" for="email">Email</label>
                        <div class="password-wrap">
                            <input type="email" id="email" name="email"
                                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                placeholder="admin@sipbar.sch.id"
                                value="{{ old('email') }}"
                                autocomplete="email">
                        </div>
                        @error('email')<div class="error-msg">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </div>@enderror
                    </div>

                    {{-- GURU: NIP --}}
                    <div id="field-guru" style="{{ old('role','admin') !== 'guru' ? 'display:none' : '' }}">
                        <label class="form-label" for="nip">NIP (Nomor Induk Pegawai)</label>
                        <div class="password-wrap">
                            <input type="text" id="nip" name="nip"
                                class="form-control {{ $errors->has('nip') ? 'is-invalid' : '' }}"
                                placeholder="Contoh: 198505152010011001"
                                value="{{ old('nip') }}"
                                autocomplete="off">
                        </div>
                        @error('nip')<div class="error-msg">{{ $message }}</div>@enderror
                    </div>

                    {{-- SISWA: NIS --}}
                    <div id="field-siswa" style="{{ old('role','admin') !== 'siswa' ? 'display:none' : '' }}">
                        <label class="form-label" for="nis">NIS (Nomor Induk Siswa)</label>
                        <div class="password-wrap">
                            <input type="text" id="nis" name="nis"
                                class="form-control {{ $errors->has('nis') ? 'is-invalid' : '' }}"
                                placeholder="Contoh: 2024001"
                                value="{{ old('nis') }}"
                                autocomplete="off">
                        </div>
                        @error('nis')<div class="error-msg">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Password (admin only) --}}
                <div class="form-group" id="password-group" style="{{ old('role','admin') !== 'admin' ? 'display:none' : '' }}">
                    <label class="form-label" for="password">Password</label>
                    <div class="password-wrap">
                        <input type="password" id="password" name="password"
                            class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                            placeholder="••••••••••"
                            autocomplete="current-password">
                        <button type="button" class="password-toggle" onclick="togglePassword()" aria-label="Tampilkan password">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    @error('password')<div class="error-msg">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </div>@enderror
                </div>

                {{-- Nomor HP (guru & siswa only) --}}
                <div class="form-group" id="tgl-lahir-group" style="{{ old('role','admin') === 'admin' ? 'display:none' : '' }}">
                    <label class="form-label" for="phone">Nomor HP (WhatsApp)</label>
                    <div class="password-wrap">
                        <input type="tel" id="phone" name="phone"
                            class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                            placeholder="Contoh: 08123456789"
                            value="{{ old('phone') }}"
                            autocomplete="tel">
                    </div>
                    @error('phone')<div class="error-msg">{{ $message }}</div>@enderror
                    <div style="font-size:11px;color:#94a3b8;margin-top:4px">Format: 08xxx, 628xxx, atau +628xxx</div>
                </div>

                {{-- Remember + Forgot --}}
                <div class="row-between">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Ingat saya
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a>
                    @endif
                </div>

                <button type="submit" class="btn-login">Masuk</button>
            </form>
        </div>
    </div>
</div>

<script>
    // ── Role switcher ──
    function handleRoleChange(role) {
        // Reset semua field visibility
        document.getElementById('field-admin').style.display    = 'none';
        document.getElementById('field-guru').style.display     = 'none';
        document.getElementById('field-siswa').style.display    = 'none';
        document.getElementById('password-group').style.display = 'none';
        document.getElementById('tgl-lahir-group').style.display= 'none';

        if (role === 'admin') {
            document.getElementById('field-admin').style.display    = '';
            document.getElementById('password-group').style.display = '';
        } else if (role === 'guru') {
            document.getElementById('field-guru').style.display      = '';
            document.getElementById('tgl-lahir-group').style.display = '';
        } else if (role === 'siswa') {
            document.getElementById('field-siswa').style.display     = '';
            document.getElementById('tgl-lahir-group').style.display = '';
        }
    }

    function togglePassword() {
        const pw = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');
        const isHidden = pw.type === 'password';
        pw.type = isHidden ? 'text' : 'password';
        icon.innerHTML = isHidden
            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21"/>'
            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
    }

    // Init on page load
    document.addEventListener('DOMContentLoaded', function () {
        const role = document.getElementById('role').value || 'admin';
        handleRoleChange(role);
    });
</script>
</body>
</html>
