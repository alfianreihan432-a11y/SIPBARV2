

<?php $__env->startSection('title', 'Edit Peminjaman – SIPBAR'); ?>

<?php $__env->startSection('content'); ?>
<style>
    input[type="date"]::-webkit-calendar-picker-indicator,
    input[type="time"]::-webkit-calendar-picker-indicator {
        cursor: pointer;
        opacity: 0.6;
        transition: opacity 0.2s;
        filter: invert(0.5);
    }
    html.dark input[type="date"]::-webkit-calendar-picker-indicator,
    html.dark input[type="time"]::-webkit-calendar-picker-indicator {
        filter: invert(0.8);
    }
    input[type="date"]::-webkit-calendar-picker-indicator:hover,
    input[type="time"]::-webkit-calendar-picker-indicator:hover {
        opacity: 1;
    }
</style>

<div class="page-header">
    <div class="page-header-left">
        <div class="page-title">Edit Peminjaman</div>
        <div class="page-subtitle">Perbarui detail pengajuan peminjaman yang masih menunggu persetujuan.</div>
    </div>
    <a href="<?php echo e(route('student.loans')); ?>" class="s-btn s-btn--ghost">← Kembali</a>
</div>

<div class="s-card" style="max-width: 900px; margin: 0 auto;">
    <div class="s-card-header">
        <div>
            <div class="s-card-title">Form Edit Permohonan</div>
            <div class="s-card-sub">Ubah tujuan, guru pembimbing, jadwal, dan jumlah pinjaman.</div>
        </div>
    </div>

    <form method="POST" action="<?php echo e(route('student.loans.update', $borrowing->id)); ?>" style="padding: 20px 18px 8px;">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        
        <div style="background: var(--bg3); border-radius: 14px; padding: 18px; margin-bottom: 20px; border: 1px solid var(--border2);">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px; color: var(--primary);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <h4 style="font-size: 13px; font-weight: 700; color: var(--text);">Informasi Barang</h4>
            </div>
            <p style="font-weight: 600; color: var(--text); font-size: 14px; background: var(--card); padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border2);"><?php echo e($borrowing->item?->name ?? 'Barang tidak tersedia'); ?></p>
        </div>

        
        <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;">
                Jumlah Barang *
            </label>
            <input type="number" name="quantity" value="<?php echo e(old('quantity', $borrowing->quantity)); ?>" min="1" class="s-filter-input" required>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;">
                Keperluan Peminjaman *
            </label>
            <textarea name="purpose" rows="3" class="s-filter-input" style="resize: vertical;" required><?php echo e(old('purpose', $borrowing->purpose)); ?></textarea>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['purpose'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div style="background: var(--bg3); border-radius: 12px; padding: 16px; margin-bottom: 16px; border: 1px solid var(--border2);">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text); margin-bottom: 6px;">Tanggal Pinjam *</label>
                    <input type="date" name="borrow_date" value="<?php echo e(old('borrow_date', $borrowing->borrow_date?->format('Y-m-d'))); ?>" class="s-filter-input" required>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['borrow_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text); margin-bottom: 6px;">Tanggal Kembali *</label>
                    <input type="date" name="return_date" value="<?php echo e(old('return_date', $borrowing->return_date?->format('Y-m-d'))); ?>" class="s-filter-input" required>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['return_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text); margin-bottom: 6px;">Jam Kembali *</label>
                <input type="time" name="return_time" value="<?php echo e(old('return_time', $borrowing->return_time ?? '14:00')); ?>" class="s-filter-input" required>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['return_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        
        <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;">
                Guru Penanggung Jawab *
            </label>
            <select name="teacher_id" class="s-filter-input" required>
                <option value="">-- Pilih Guru --</option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <option value="<?php echo e($teacher->id); ?>" <?php echo e(old('teacher_id', $borrowing->teacher_id) == $teacher->id ? 'selected' : ''); ?>>
                        <?php echo e($teacher->name); ?>

                    </option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </select>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['teacher_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div style="margin-bottom: 24px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;">
                Catatan (Opsional)
            </label>
            <textarea name="notes" rows="2" class="s-filter-input" style="resize: vertical;" placeholder="Catatan tambahan..."><?php echo e(old('notes', $borrowing->notes)); ?></textarea>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
            <div style="margin-bottom:16px;padding:12px 16px;border-radius:10px;background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);color:#ef4444;font-size:13px;display:flex;align-items:center;gap:8px;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Mohon periksa kembali data yang Anda masukkan.
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div style="display: flex; gap: 12px;">
            <a href="<?php echo e(route('student.loans')); ?>" class="s-btn s-btn--secondary" style="flex: 1; justify-content: center; text-decoration: none;">
                Batal
            </a>
            <button type="submit" class="s-btn s-btn--primary" style="flex: 1; justify-content: center;">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ASUS\SIPBARV2\resources\views/pages/siswa/loans-edit.blade.php ENDPATH**/ ?>