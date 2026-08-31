<?php $__env->startSection('title', 'Ajukan Pengembalian – SIPBAR'); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width: 760px; margin: 0 auto;">
    
    <div style="margin-bottom: 16px;">
        <a href="<?php echo e(route('student.returns.index')); ?>" class="s-btn s-btn--secondary s-btn--sm">
            &larr; Kembali ke Barang Saya
        </a>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
        <div style="background: var(--s-rejected-bg); border: 1px solid var(--s-rejected-bdr); color: var(--s-rejected); padding: 14px 18px; border-radius: 10px; margin-bottom: 18px; font-size: 13px;">
            <div style="font-weight: 700; margin-bottom: 4px;">Terdapat kesalahan pada input Anda:</div>
            <ul style="padding-left: 18px; margin: 0;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <li><?php echo e($error); ?></li>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </ul>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="s-card">
        <div class="s-card-header">
            <div>
                <div class="s-card-title">Form Pengajuan Pengembalian</div>
                <div class="s-card-sub">Lengkapi kondisi barang yang dikembalikan untuk diverifikasi oleh admin/petugas</div>
            </div>
        </div>

        
        <div style="background: var(--bg3); border: 1px solid var(--border2); border-radius: 12px; padding: 16px 18px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
            <div>
                <div style="font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em;">Barang yang Dipinjam</div>
                <div style="font-size: 16px; font-weight: 800; color: var(--text); margin-top: 2px;"><?php echo e($borrowing->item?->name ?? 'Barang #' . $borrowing->item_id); ?></div>
                <div style="font-size: 12px; color: var(--muted); margin-top: 2px;">
                    Kode: <strong style="color: var(--text)"><?php echo e($borrowing->item?->code ?? '-'); ?></strong> &bull; Qty: <strong style="color: var(--text)"><?php echo e($borrowing->quantity); ?> Unit</strong>
                </div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 11px; color: var(--muted);">Jatuh Tempo:</div>
                <div style="font-size: 13px; font-weight: 700; color: var(--text);">
                    <?php echo e($borrowing->return_date ? $borrowing->return_date->format('d M Y') : '-'); ?>

                </div>
            </div>
        </div>

        <form action="<?php echo e(route('student.returns.store')); ?>" method="POST" enctype="multipart/form-data" id="returnForm">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="borrowing_request_id" value="<?php echo e($borrowing->id); ?>">

            
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: var(--text); margin-bottom: 8px;">
                    Kondisi Fisik Barang Saat Dikembalikan <span style="color: var(--s-rejected)">*</span>
                </label>
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;" class="condition-grid">
                    <?php
                        $conditions = [
                            ['val' => 'baik', 'icon' => '✨', 'title' => 'Baik', 'sub' => 'Normal / Utuh'],
                            ['val' => 'rusak_ringan', 'icon' => '⚠️', 'title' => 'Rusak Ringan', 'sub' => 'Gores / Lecet'],
                            ['val' => 'rusak_berat', 'icon' => '❌', 'title' => 'Rusak Berat', 'sub' => 'Patah / Mati Total'],
                            ['val' => 'hilang', 'icon' => '🔍', 'title' => 'Hilang', 'sub' => 'Tidak Ditemukan'],
                        ];
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $conditions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <label style="position: relative; cursor: pointer;">
                        <input type="radio" name="kondisi_barang" value="<?php echo e($c['val']); ?>" <?php echo e(old('kondisi_barang', 'baik') === $c['val'] ? 'checked' : ''); ?> style="position: absolute; opacity: 0; width: 0; height: 0;" class="condition-radio">
                        <div class="condition-box" style="border: 1.5px solid var(--border2); border-radius: 10px; padding: 14px 10px; text-align: center; background: var(--bg3); transition: all .2s; display: flex; flex-direction: column; align-items: center; gap: 4px;">
                            <span style="font-size: 20px;"><?php echo e($c['icon']); ?></span>
                            <div style="font-size: 13px; font-weight: 700; color: var(--text);"><?php echo e($c['title']); ?></div>
                            <div style="font-size: 10px; color: var(--muted);"><?php echo e($c['sub']); ?></div>
                        </div>
                    </label>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
            <style>
                @media (max-width: 640px) { .condition-grid { grid-template-columns: repeat(2, 1fr) !important; } }
                .condition-radio:checked + .condition-box {
                    border-color: var(--primary) !important;
                    background: var(--primary-light) !important;
                    box-shadow: 0 0 0 2px var(--primary-muted) !important;
                }
            </style>

            
            <div style="margin-bottom: 20px;">
                <label for="catatan" style="display: block; font-size: 13px; font-weight: 700; color: var(--text); margin-bottom: 8px;">
                    Catatan Tambahan <span style="font-weight: normal; color: var(--muted);">(Opsional)</span>
                </label>
                <textarea 
                    name="catatan" 
                    id="catatan" 
                    rows="3" 
                    style="width: 100%; background: var(--input-bg); border: 1.5px solid var(--border2); border-radius: 10px; padding: 10px 14px; font-size: 13px; color: var(--text); outline: none; font-family: inherit;"
                    placeholder="Tuliskan keterangan mengenai kondisi barang, kelengkapan aksesoris, atau kendala pemakaian..."><?php echo e(old('catatan')); ?></textarea>
                <div style="font-size: 11px; color: var(--muted); margin-top: 4px;">Maksimal 1000 karakter.</div>
            </div>

            
            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: var(--text); margin-bottom: 8px;">
                    Foto Bukti Kondisi Fisik <span style="font-weight: normal; color: var(--muted);">(Opsional, Disarankan jika rusak)</span>
                </label>

                <div id="uploadZone" onclick="document.getElementById('photoInput').click()" style="border: 2px dashed var(--border2); border-radius: 12px; padding: 24px 20px; text-align: center; cursor: pointer; background: var(--bg3); transition: all .2s;">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 40px; height: 40px; color: var(--primary); margin: 0 auto 8px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <div style="font-size: 13px; font-weight: 700; color: var(--text);">Klik atau seret foto ke sini untuk mengunggah</div>
                    <div style="font-size: 11px; color: var(--muted); margin-top: 2px;">Format: JPG, JPEG, PNG, WEBP &bull; Maksimal 2MB</div>
                </div>

                <input type="file" name="foto_bukti" id="photoInput" accept="image/jpeg,image/png,image/webp" style="display: none;">

                
                <div id="previewWrapper" style="display: none; margin-top: 14px; position: relative; text-align: center;">
                    <img id="imagePreview" src="" alt="Preview Bukti" style="max-height: 220px; border-radius: 10px; border: 1px solid var(--border2); box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
                    <button type="button" id="removePhotoBtn" title="Hapus foto" style="position: absolute; top: -8px; right: calc(50% - 110px); background: #ef4444; color: #fff; border: none; border-radius: 50%; width: 26px; height: 26px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 6px rgba(0,0,0,0.2);">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <div style="font-size: 11px; color: var(--muted); margin-top: 6px;" id="fileNameDisplay"></div>
                </div>
            </div>

            
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 12px; padding-top: 18px; border-top: 1px solid var(--border2);">
                <a href="<?php echo e(route('student.returns.index')); ?>" class="s-btn s-btn--secondary">Batal</a>
                <button type="submit" class="s-btn s-btn--primary">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Kirim Pengajuan Pengembalian
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const photoInput = document.getElementById('photoInput');
        const uploadZone = document.getElementById('uploadZone');
        const previewWrapper = document.getElementById('previewWrapper');
        const imagePreview = document.getElementById('imagePreview');
        const removePhotoBtn = document.getElementById('removePhotoBtn');
        const fileNameDisplay = document.getElementById('fileNameDisplay');

        if (uploadZone && photoInput) {
            ['dragenter', 'dragover'].forEach(eventName => {
                uploadZone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    uploadZone.style.borderColor = 'var(--primary)';
                    uploadZone.style.background = 'var(--primary-light)';
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                uploadZone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    uploadZone.style.borderColor = 'var(--border2)';
                    uploadZone.style.background = 'var(--bg3)';
                }, false);
            });

            uploadZone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files.length > 0) {
                    photoInput.files = files;
                    showPreview(files[0]);
                }
            });

            photoInput.addEventListener('change', function () {
                if (this.files && this.files[0]) {
                    showPreview(this.files[0]);
                }
            });
        }

        function showPreview(file) {
            if (!file.type.match('image.*')) {
                alert('File yang dipilih harus berupa gambar (JPG, PNG, WEBP).');
                photoInput.value = '';
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal adalah 2MB.');
                photoInput.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.src = e.target.result;
                previewWrapper.style.display = 'block';
                uploadZone.style.display = 'none';
                fileNameDisplay.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
            };
            reader.readAsDataURL(file);
        }

        if (removePhotoBtn) {
            removePhotoBtn.addEventListener('click', function () {
                photoInput.value = '';
                imagePreview.src = '';
                previewWrapper.style.display = 'none';
                uploadZone.style.display = 'block';
            });
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ASUS\SIPBARV2\resources\views/pages/siswa/returns/create.blade.php ENDPATH**/ ?>