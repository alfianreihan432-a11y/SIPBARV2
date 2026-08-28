<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk – SIPBAR</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
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

        /* ── Admin-style Select Component ── */
        select,
        .im-select,
        .im-select-field {
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%232563eb' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 12px center !important;
            background-size: 15px 15px !important;
            padding-right: 38px !important;
            background-color: #ffffff !important;
            border: 1.5px solid #cbd5e1 !important;
            border-radius: 10px !important;
            color: #0f172a !important;
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            outline: none !important;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08) !important;
        }
        select:hover,
        .im-select:hover,
        .im-select-field:hover {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 2px rgba(37,99,235,0.1) !important;
        }
        select:focus,
        .im-select:focus,
        .im-select-field:focus {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 3.5px rgba(37,99,235,0.18) !important;
            background-color: #ffffff !important;
        }
        select option {
            background: #ffffff !important;
            color: #0f172a !important;
            font-weight: 500;
            padding: 8px 12px;
        }
        .im-select-field {
            width: 100% !important;
            padding-top: 10px !important;
            padding-bottom: 10px !important;
            padding-left: 13px !important;
            font-size: 13px !important;
        }

        /* ── Custom Admin Select Dropdown with Icons ── */
        .admin-select-wrap {
            position: relative;
            width: 100%;
        }
        .admin-select-trigger {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 10px 38px 10px 13px;
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            color: #0f172a;
            cursor: pointer;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            /* Custom chevron down SVG arrow in blue */
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%232563eb' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 15px 15px;
        }
        .admin-select-trigger:hover {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37,99,235,0.1);
        }
        .admin-select-trigger:focus,
        .admin-select-wrap.open .admin-select-trigger {
            border-color: #2563eb;
            box-shadow: 0 0 0 3.5px rgba(37,99,235,0.18);
            background-color: #ffffff;
        }
        .admin-select-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            z-index: 9999;
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            padding: 6px;
            box-shadow: 0 12px 36px -4px rgba(37,99,235,0.25), 0 4px 16px rgba(0,0,0,0.08);
            max-height: 300px;
            overflow-y: auto;
        }
        .admin-select-dropdown::-webkit-scrollbar { width: 4px; }
        .admin-select-dropdown::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .admin-select-wrap.open .admin-select-dropdown {
            display: block;
            animation: adminSelectFadeIn 0.18s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes adminSelectFadeIn {
            from { opacity: 0; transform: translateY(-6px) scale(0.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .admin-select-option {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
        }
        .admin-select-option:hover {
            background-color: rgba(37,99,235,0.08);
            color: #2563eb;
        }
        .admin-select-option.selected {
            background-color: rgba(37,99,235,0.12);
            color: #2563eb;
        }
    </style>

</head>
<body>
<div class="login-wrapper">
    <div class="login-card">

        
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

        
        <div class="login-right">
            <a href="<?php echo e(route('home')); ?>" class="back-link">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Beranda
            </a>

            <div class="right-star">✦</div>
            <div class="right-title">Masuk ke Akun</div>
            <div class="right-sub">Akses sistem inventaris sekolah dengan mudah, cepat, dan aman.</div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
                <div style="background:#f0fdf4;border:1px solid #86efac;border-radius:8px;padding:10px 14px;font-size:13px;color:#16a34a;margin-bottom:16px">
                    <?php echo e(session('status')); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px 14px;margin-bottom:16px">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div style="font-size:13px;color:#dc2626;margin-bottom:4px"><?php echo e($error); ?></div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <form method="POST" action="<?php echo e(route('login.store')); ?>" id="loginForm">
                <?php echo csrf_field(); ?>

                
                            

                
                <div class="form-group" id="identifierGroup">
                    <label class="form-label" for="email">Email</label>
                    <div class="password-wrap">
                        <input type="email" id="email" name="email"
                            class="form-control <?php echo e($errors->has('email') ? 'is-invalid' : ''); ?>"
                            placeholder="nama@sipbar.sch.id"
                            value="<?php echo e(old('email')); ?>"
                            autocomplete="email">
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error-msg">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <?php echo e($message); ?>

                    </div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div class="form-group" id="password-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="password-wrap">
                        <input type="password" id="password" name="password"
                            class="form-control <?php echo e($errors->has('password') ? 'is-invalid' : ''); ?>"
                            placeholder="••••••••••"
                            autocomplete="current-password">
                        <button type="button" class="password-toggle" onclick="togglePassword()" aria-label="Tampilkan password">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error-msg">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <?php echo e($message); ?>

                    </div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div class="row-between">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                        Ingat saya
                    </label>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('password.request')): ?>
                        <a href="<?php echo e(route('password.request')); ?>" class="forgot-link">Lupa password?</a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <button type="submit" class="btn-login">Masuk</button>

                
                <div style="display:flex;align-items:center;gap:12px;margin:20px 0 0">
                    <div style="flex:1;height:1px;background:#e2e8f0"></div>
                    <span style="font-size:12px;color:#94a3b8;white-space:nowrap;font-weight:500">atau masuk dengan</span>
                    <div style="flex:1;height:1px;background:#e2e8f0"></div>
                </div>
            </form>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('sipintu_error')): ?>
            <div style="display:flex;align-items:flex-start;gap:10px;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:12px 14px;margin-top:12px">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#ef4444;flex-shrink:0;margin-top:1px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span style="font-size:12px;color:#dc2626;line-height:1.5"><?php echo e(session('sipintu_error')); ?></span>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <a href="<?php echo e(route('sipintu.oauth.redirect')); ?>"
               id="btn-sipintu"
               style="display:flex;align-items:center;justify-content:center;gap:10px;width:100%;margin-top:12px;padding:12px 16px;background:#fff;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;font-weight:700;color:#374151;text-decoration:none;transition:all .2s;box-shadow:0 2px 8px rgba(0,0,0,.05)"
               onmouseover="this.style.borderColor='#6366f1';this.style.color='#6366f1';this.style.boxShadow='0 4px 14px rgba(99,102,241,.15)'"
               onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#374151';this.style.boxShadow='0 2px 8px rgba(0,0,0,.05)'">
                
                <span style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;background:linear-gradient(135deg,#6366f1,#3b82f6);border-radius:6px;flex-shrink:0">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                </span>
                Login dengan SiPintu
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;margin-left:auto;opacity:.4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>

            <p style="font-size:11px;color:#94a3b8;text-align:center;margin-top:10px;line-height:1.5">
                SSO terpusat via <strong style="color:#6366f1">SiPintu Identity Gateway</strong> — masuk sekali untuk semua sistem sekolah.
            </p>

        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const pw = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');
        const isHidden = pw.type === 'password';
        pw.type = isHidden ? 'text' : 'password';
        icon.innerHTML = isHidden
            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21"/>'
            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
    }

</script>
</body>
</html>
<?php /**PATH C:\Users\Dell\SIPBARV2\resources\views/pages/auth/login.blade.php ENDPATH**/ ?>