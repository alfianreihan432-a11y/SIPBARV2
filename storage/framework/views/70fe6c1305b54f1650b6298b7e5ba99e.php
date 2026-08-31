<div style="background: var(--card); border-radius: 16px; border: 1px solid var(--border2); padding: 24px;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
        <h2 style="font-size: 20px; font-weight: 800; color: var(--text);">Form Pengajuan Peminjaman</h2>
        <button type="button" wire:click="close" style="background: none; border: none; cursor: pointer; color: var(--subtle); padding: 8px; border-radius: 8px; transition: background .2s;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    
    <div style="background: rgba(29, 78, 216, 0.05); border-radius: 12px; padding: 16px; margin-bottom: 20px; border: 1px solid rgba(29, 78, 216, 0.1);">
        <div style="display: flex; align-items: flex-start; gap: 16px;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->photo_path): ?>
                <img src="<?php echo e(asset('storage/' . $item->photo_path)); ?>" alt="<?php echo e($item->name); ?>" style="width: 80px; height: 80px; object-fit: cover; border-radius: 10px;">
            <?php else: ?>
                <div style="width: 80px; height: 80px; background: var(--bg3); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 40px; height: 40px; color: var(--subtle);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div style="flex: 1;">
                <h3 style="font-weight: 700; color: var(--text); margin-bottom: 4px;"><?php echo e($item->name); ?></h3>
                <p style="font-size: 13px; color: var(--muted);"><?php echo e($item->category->name ?? 'Uncategorized'); ?></p>
                <p style="font-size: 13px; color: var(--muted);">Kode: <?php echo e($item->code); ?></p>
                <div style="display: flex; gap: 12px; margin-top: 4px;">
                    <span style="font-size: 13px; color: var(--muted);">Stok: <?php echo e($item->stock); ?></span>
                    <span style="font-size: 13px; color: var(--muted);">Kondisi: <?php echo e($item->condition); ?></span>
                </div>
            </div>
        </div>
    </div>

    
    <div style="background: var(--bg3); border-radius: 12px; padding: 16px; margin-bottom: 20px; border: 1px solid var(--border2);">
        <h4 style="font-weight: 700; color: var(--text); margin-bottom: 12px; font-size: 14px;">Data Siswa (Otomatis)</h4>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
            <div>
                <label style="display: block; font-size: 12px; color: var(--muted); margin-bottom: 4px;">Nama</label>
                <p style="font-weight: 600; color: var(--text); font-size: 13px;"><?php echo e(auth()->user()->name); ?></p>
            </div>
            <div>
                <label style="display: block; font-size: 12px; color: var(--muted); margin-bottom: 4px;">NIS</label>
                <p style="font-weight: 600; color: var(--text); font-size: 13px;"><?php echo e(auth()->user()->nis); ?></p>
            </div>
            <div>
                <label style="display: block; font-size: 12px; color: var(--muted); margin-bottom: 4px;">Kelas</label>
                <p style="font-weight: 600; color: var(--text); font-size: 13px;"><?php echo e(auth()->user()->kelas); ?></p>
            </div>
            <div>
                <label style="display: block; font-size: 12px; color: var(--muted); margin-bottom: 4px;">Jurusan</label>
                <p style="font-weight: 600; color: var(--text); font-size: 13px;"><?php echo e(auth()->user()->jurusan); ?></p>
            </div>
        </div>
    </div>

    <form wire:submit="submit">
        
        <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px;">Jumlah *</label>
            <input type="number" wire:model="quantity" min="1" max="<?php echo e($item->stock); ?>" 
                   style="width: 100%; padding: 10px 14px; border: 1px solid var(--border2); border-radius: 10px; background: var(--input-bg); color: var(--text); font-size: 13px;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span style="color: #ef4444; font-size: 12px; display: block; margin-top: 4px;"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <p style="font-size: 11px; color: var(--subtle); margin-top: 4px;">Maksimal: <?php echo e($item->stock); ?></p>
        </div>

        
        <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px;">Keperluan Peminjaman *</label>
            <textarea wire:model="purpose" rows="3" 
                      style="width: 100%; padding: 10px 14px; border: 1px solid var(--border2); border-radius: 10px; background: var(--input-bg); color: var(--text); font-size: 13px; resize: vertical;"
                      placeholder="Jelaskan keperluan peminjaman barang..."></textarea>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['purpose'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span style="color: #ef4444; font-size: 12px; display: block; margin-top: 4px;"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px;">Tanggal Pinjam *</label>
                <input type="date" wire:model="borrow_date" 
                       style="width: 100%; padding: 10px 14px; border: 1px solid var(--border2); border-radius: 10px; background: var(--input-bg); color: var(--text); font-size: 13px;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['borrow_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span style="color: #ef4444; font-size: 12px; display: block; margin-top: 4px;"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px;">Tanggal Kembali *</label>
                <input type="date" wire:model="return_date" 
                       style="width: 100%; padding: 10px 14px; border: 1px solid var(--border2); border-radius: 10px; background: var(--input-bg); color: var(--text); font-size: 13px;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['return_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span style="color: #ef4444; font-size: 12px; display: block; margin-top: 4px;"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        
        <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px;">Guru Penanggung Jawab *</label>
            <select wire:model="teacher_id" 
                    style="width: 100%; padding: 10px 14px; border: 1px solid var(--border2); border-radius: 10px; background: var(--input-bg); color: var(--text); font-size: 13px;">
                <option value="">Pilih Guru</option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <option value="<?php echo e($teacher->id); ?>"><?php echo e($teacher->name); ?> (<?php echo e($teacher->jabatan ?? 'Guru'); ?>)</option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </select>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['teacher_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span style="color: #ef4444; font-size: 12px; display: block; margin-top: 4px;"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div style="margin-bottom: 24px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px;">Catatan (Opsional)</label>
            <textarea wire:model="notes" rows="2" 
                      style="width: 100%; padding: 10px 14px; border: 1px solid var(--border2); border-radius: 10px; background: var(--input-bg); color: var(--text); font-size: 13px; resize: vertical;"
                      placeholder="Catatan tambahan..."></textarea>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span style="color: #ef4444; font-size: 12px; display: block; margin-top: 4px;"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div style="display: flex; gap: 12px;">
            <button type="button" wire:click="close"
                    style="flex: 1; padding: 12px 16px; border: 1px solid var(--border2); border-radius: 10px; color: var(--text); font-weight: 600; font-size: 13px; cursor: pointer; background: var(--bg3); transition: background .2s;">
                Batal
            </button>
            <button type="submit" 
                    style="flex: 1; padding: 12px 16px; background: #1d4ed8; color: #fff; border-radius: 10px; font-weight: 600; font-size: 13px; cursor: pointer; border: none; transition: background .2s;">
                Kirim Pengajuan
            </button>
        </div>
    </form>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div style="margin-top: 16px; padding: 12px 16px; background: rgba(5, 150, 105, 0.1); border: 1px solid rgba(5, 150, 105, 0.2); border-radius: 10px; color: #059669; font-size: 13px;">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\Users\ASUS\SIPBARV2\resources\views\livewire\borrowing-form.blade.php ENDPATH**/ ?>