<div class="borrowing-form-container" style="background: var(--card); border-radius: 20px; border: 1px solid var(--border2); padding: 28px; box-shadow: 0 4px 24px rgba(0,0,0,0.06);">
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

        @media (max-width: 768px) {
            .borrowing-form-container { padding: 20px !important; border-radius: 16px !important; }
            .item-image { width: 70px !important; height: 70px !important; }
            .form-header { flex-direction: column; align-items: flex-start !important; gap: 16px !important; }
            .close-btn { position: absolute; top: 20px; right: 20px; }
            .dates-grid { grid-template-columns: 1fr !important; gap: 12px !important; }
            .student-data-grid { grid-template-columns: 1fr !important; gap: 12px !important; }
        }

        @media (max-width: 480px) {
            .borrowing-form-container { padding: 16px !important; }
            .form-title { font-size: 18px !important; }
            .submit-buttons { flex-direction: column !important; }
            .submit-buttons button { width: 100% !important; }
        }
    </style>

    
    <div class="form-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; position: relative;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 42px; height: 42px; background: var(--primary-light); border: 1px solid var(--primary-muted); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px; color: var(--primary);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h2 style="font-family: var(--font-head); font-size: 20px; font-weight: 800; color: var(--text);">Form Pengajuan Peminjaman</h2>
        </div>
        <button type="button" wire:click="close" class="close-btn" style="background: var(--bg3); border: 1px solid var(--border2); cursor: pointer; color: var(--muted); padding: 8px; border-radius: 9px; transition: all .2s; display: flex; align-items: center; justify-content: center;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    
    <div style="background: var(--bg3); border-radius: 14px; padding: 18px; margin-bottom: 20px; border: 1px solid var(--border2);">
        <div style="display: flex; align-items: flex-start; gap: 16px;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->photo_path): ?>
                <img src="<?php echo e(asset('storage/' . $item->photo_path)); ?>" alt="<?php echo e($item->name); ?>" class="item-image" style="width: 80px; height: 80px; object-fit: cover; border-radius: 10px; border: 1px solid var(--border2);">
            <?php else: ?>
                <div class="item-image" style="width: 80px; height: 80px; background: var(--card); border: 1px solid var(--border2); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 36px; height: 36px; color: var(--subtle);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div style="flex: 1;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                    <h3 style="font-family: var(--font-head); font-weight: 700; color: var(--text); font-size: 15px;"><?php echo e($item->name); ?></h3>
                </div>
                <p style="font-size: 12px; color: var(--muted); margin-bottom: 2px;"><?php echo e($item->category->name ?? 'Kategori Umum'); ?> &bull; Kode: <strong style="color: var(--text)"><?php echo e($item->code); ?></strong></p>
                <div style="display: flex; gap: 14px; margin-top: 6px; font-size: 12px; color: var(--muted);">
                    <div>Stok: <strong style="color: var(--text)"><?php echo e($item->stock); ?></strong> unit</div>
                    <div>Kondisi: <strong style="color: var(--text)"><?php echo e($item->condition); ?></strong></div>
                </div>
            </div>
        </div>
    </div>

    
    <div style="background: var(--card2); border-radius: 14px; padding: 18px; margin-bottom: 20px; border: 1px solid var(--border2);">
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px; color: var(--primary);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <h4 style="font-size: 13px; font-weight: 700; color: var(--text);">Data Siswa (Peminjam)</h4>
        </div>
        <div class="student-data-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
            <div>
                <label style="display: block; font-size: 11px; color: var(--muted); margin-bottom: 4px; font-weight: 600;">Nama</label>
                <p style="font-weight: 600; color: var(--text); font-size: 13px; background: var(--bg3); padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border2);"><?php echo e(auth()->user()->name); ?></p>
            </div>
            <div>
                <label style="display: block; font-size: 11px; color: var(--muted); margin-bottom: 4px; font-weight: 600;">NIS</label>
                <p style="font-weight: 600; color: var(--text); font-size: 13px; background: var(--bg3); padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border2);"><?php echo e(auth()->user()->nis ?? '-'); ?></p>
            </div>
            <div>
                <label style="display: block; font-size: 11px; color: var(--muted); margin-bottom: 4px; font-weight: 600;">Kelas</label>
                <p style="font-weight: 600; color: var(--text); font-size: 13px; background: var(--bg3); padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border2);"><?php echo e(auth()->user()->kelas ?? '-'); ?></p>
            </div>
            <div>
                <label style="display: block; font-size: 11px; color: var(--muted); margin-bottom: 4px; font-weight: 600;">Jurusan</label>
                <p style="font-weight: 600; color: var(--text); font-size: 13px; background: var(--bg3); padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border2);"><?php echo e(auth()->user()->jurusan ?? '-'); ?></p>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="submit">
        <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;">
                Nomor HP Siswa *
            </label>
            <input type="text" wire:model="student_phone" class="s-filter-input" placeholder="Contoh: 08123456789" autocomplete="off">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['student_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        
        <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;">
                Jumlah Barang *
            </label>
            <input type="number" wire:model="quantity" min="1" max="<?php echo e($item->stock); ?>" class="s-filter-input">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <p style="font-size: 11px; color: var(--subtle); margin-top: 4px;">Maksimal tersedia: <?php echo e($item->stock); ?> unit</p>
        </div>

        
        <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;">
                Keperluan Peminjaman *
            </label>
            <textarea wire:model="purpose" rows="3" class="s-filter-input" style="resize: vertical;" placeholder="Jelaskan keperluan peminjaman barang..."></textarea>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['purpose'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div style="background: var(--bg3); border-radius: 12px; padding: 16px; margin-bottom: 16px; border: 1px solid var(--border2);">
            <div class="dates-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text); margin-bottom: 6px;">Tanggal Pinjam *</label>
                    <input type="date" wire:model="borrow_date" class="s-filter-input">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['borrow_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text); margin-bottom: 6px;">Tanggal Kembali *</label>
                    <input type="date" wire:model="return_date" class="s-filter-input">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['return_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text); margin-bottom: 6px;">Jam Kembali *</label>
                <input type="time" wire:model="return_time" class="s-filter-input">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['return_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        
        <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;">
                Guru Penanggung Jawab *
            </label>
            <select wire:model="teacher_id" class="s-filter-input">
                <option value="">-- Pilih Guru --</option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <option value="<?php echo e($teacher->id); ?>"><?php echo e($teacher->name); ?> (<?php echo e($teacher->jabatan ?? 'Guru'); ?>)</option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </select>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['teacher_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div style="margin-bottom: 24px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;">
                Catatan (Opsional)
            </label>
            <textarea wire:model="notes" rows="2" class="s-filter-input" style="resize: vertical;" placeholder="Catatan tambahan..."></textarea>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 4px;"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div class="submit-buttons" style="display: flex; gap: 12px;">
            <button type="button" wire:click="close" class="s-btn s-btn--secondary" style="flex: 1; justify-content: center;">
                Batal
            </button>
            <button type="submit" class="s-btn s-btn--primary" style="flex: 1; justify-content: center;">
                Kirim Pengajuan
            </button>
        </div>
    </form>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div style="margin-top: 16px; padding: 12px 16px; background: var(--s-returned-bg); border: 1px solid var(--s-returned-bdr); border-radius: 10px; color: var(--s-returned); font-size: 13px; display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\Users\ASUS\SIPBARV2\resources\views/livewire/borrowing-form.blade.php ENDPATH**/ ?>