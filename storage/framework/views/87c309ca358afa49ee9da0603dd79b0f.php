<?php $__env->startSection('title', 'Pengaturan - Profil'); ?>
<?php $__env->startSection('page-heading', 'Pengaturan'); ?>

<?php $__env->startSection('content'); ?>
<div style="display:flex;flex-direction:column;gap:20px">

    
    <div style="background:var(--bg-card);border:1px solid var(--border-alt);border-radius:18px;padding:22px 28px;display:flex;align-items:center;gap:18px;box-shadow:var(--card-shadow)">
        <div style="width:48px;height:48px;background:var(--blue-dark);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
            <div style="font-size:11px;font-weight:700;color:var(--blue);letter-spacing:.1em;text-transform:uppercase;margin-bottom:4px">Akun & Preferensi</div>
            <div style="font-size:19px;font-weight:800;color:var(--text-primary);margin-bottom:4px">Pengaturan</div>
            <div style="font-size:13px;color:var(--text-muted)">Kelola profil, keamanan, dan tampilan akun Anda.</div>
        </div>
    </div>

    
    <?php echo $__env->make('partials.settings-tabs', ['active' => 'profile'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status') === 'profile-updated'): ?>
    <div style="display:flex;align-items:center;gap:10px;padding:13px 18px;border-radius:12px;background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);color:#10b981;font-size:13px;font-weight:600">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        Profil berhasil diperbarui.
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div style="background:var(--bg-card);border:1px solid var(--border-alt);border-radius:18px;box-shadow:var(--card-shadow);overflow:hidden">
        <div style="padding:22px 28px;border-bottom:1px solid var(--border-subtle)">
            <div style="font-size:16px;font-weight:800;color:var(--text-primary);margin-bottom:3px">Informasi Profil</div>
            <div style="font-size:13px;color:var(--text-muted)">Perbarui nama dan alamat email Anda.</div>
        </div>
        <form method="POST" action="<?php echo e(route('settings.profile.update')); ?>" style="padding:24px 28px;max-width:520px">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>

            <div style="display:flex;flex-direction:column;gap:6px;margin-bottom:18px">
                <label for="name" style="font-size:11px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--text-muted)">Nama Lengkap <span style="color:#f87171">*</span></label>
                <input id="name" name="name" type="text" value="<?php echo e(old('name', auth()->user()->name)); ?>" required autocomplete="name"
                    style="width:100%;background:var(--input-bg);border:1.5px solid <?php echo e($errors->has('name') ? '#f87171' : 'var(--input-border)'); ?>;border-radius:10px;padding:10px 14px;font-size:14px;color:var(--text-primary);outline:none;transition:border-color .2s;font-family:inherit">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p style="font-size:12px;color:#f87171;margin-top:3px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div style="display:flex;flex-direction:column;gap:6px;margin-bottom:24px">
                <label for="email" style="font-size:11px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--text-muted)">Alamat Email <span style="color:#f87171">*</span></label>
                <input id="email" name="email" type="email" value="<?php echo e(old('email', auth()->user()->email)); ?>" required autocomplete="email"
                    style="width:100%;background:var(--input-bg);border:1.5px solid <?php echo e($errors->has('email') ? '#f87171' : 'var(--input-border)'); ?>;border-radius:10px;padding:10px 14px;font-size:14px;color:var(--text-primary);outline:none;transition:border-color .2s;font-family:inherit">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p style="font-size:12px;color:#f87171;margin-top:3px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div style="padding-top:18px;border-top:1px solid var(--border-subtle)">
                <button type="submit" style="display:inline-flex;align-items:center;gap:7px;background:var(--blue-dark);color:#fff;border:none;border-radius:10px;padding:10px 22px;font-size:13px;font-weight:700;cursor:pointer;box-shadow:0 4px 12px rgba(29,78,216,.3);transition:all .2s">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    
    <div style="background:var(--bg-card);border:1px solid var(--border-alt);border-radius:18px;box-shadow:var(--card-shadow);overflow:hidden">
        <div style="padding:22px 28px;border-bottom:1px solid var(--border-subtle)">
            <div style="font-size:16px;font-weight:800;color:var(--text-primary);margin-bottom:3px">Ubah Kata Sandi</div>
            <div style="font-size:13px;color:var(--text-muted)">Pastikan menggunakan kata sandi yang kuat dan unik.</div>
        </div>
        <form method="POST" action="<?php echo e(route('settings.password.update')); ?>" style="padding:24px 28px;max-width:520px">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status') === 'password-updated'): ?>
            <div style="display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:10px;background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);color:#10b981;font-size:13px;font-weight:600;margin-bottom:18px">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Kata sandi berhasil diperbarui.
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div style="display:flex;flex-direction:column;gap:6px;margin-bottom:16px">
                <label for="current_password" style="font-size:11px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--text-muted)">Kata Sandi Saat Ini</label>
                <input id="current_password" name="current_password" type="password" autocomplete="current-password"
                    style="width:100%;background:var(--input-bg);border:1.5px solid <?php echo e($errors->has('current_password') ? '#f87171' : 'var(--input-border)'); ?>;border-radius:10px;padding:10px 14px;font-size:14px;color:var(--text-primary);outline:none;font-family:inherit">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p style="font-size:12px;color:#f87171;margin-top:3px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div style="display:flex;flex-direction:column;gap:6px;margin-bottom:16px">
                <label for="password" style="font-size:11px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--text-muted)">Kata Sandi Baru</label>
                <input id="password" name="password" type="password" autocomplete="new-password"
                    style="width:100%;background:var(--input-bg);border:1.5px solid <?php echo e($errors->has('password') ? '#f87171' : 'var(--input-border)'); ?>;border-radius:10px;padding:10px 14px;font-size:14px;color:var(--text-primary);outline:none;font-family:inherit">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p style="font-size:12px;color:#f87171;margin-top:3px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div style="display:flex;flex-direction:column;gap:6px;margin-bottom:24px">
                <label for="password_confirmation" style="font-size:11px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--text-muted)">Konfirmasi Kata Sandi Baru</label>
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                    style="width:100%;background:var(--input-bg);border:1.5px solid var(--input-border);border-radius:10px;padding:10px 14px;font-size:14px;color:var(--text-primary);outline:none;font-family:inherit">
            </div>

            <div style="padding-top:18px;border-top:1px solid var(--border-subtle)">
                <button type="submit" style="display:inline-flex;align-items:center;gap:7px;background:var(--blue-dark);color:#fff;border:none;border-radius:10px;padding:10px 22px;font-size:13px;font-weight:700;cursor:pointer;box-shadow:0 4px 12px rgba(29,78,216,.3);transition:all .2s">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Perbarui Kata Sandi
                </button>
            </div>
        </form>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dell\SIPBARV2\resources\views/pages/admin/settings-profile.blade.php ENDPATH**/ ?>