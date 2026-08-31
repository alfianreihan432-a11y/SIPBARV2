<?php $__env->startSection('title', 'Form Pengajuan Pengembalian – SIPBAR'); ?>
<?php $__env->startSection('page-heading', 'Ajukan Pengembalian'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .form-container {
        max-width: 760px;
        margin: 0 auto;
    }

    .form-panel {
        background: var(--card);
        border: 1px solid var(--border2);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
    }

    .item-summary-card {
        background: var(--bg3);
        border: 1px solid var(--border2);
        border-radius: 12px;
        padding: 16px 18px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    @media (max-width: 640px) {
        .item-summary-card { flex-direction: column; align-items: flex-start; gap: 10px; }
    }

    .form-group {
        margin-bottom: 20px;
    }
    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 8px;
    }
    .form-label .required {
        color: #ef4444;
    }
    .form-hint {
        font-size: 11px;
        color: var(--muted);
        margin-top: 4px;
    }

    /* Radio Condition Grid */
    .condition-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }
    @media (max-width: 640px) {
        .condition-grid { grid-template-columns: repeat(2, 1fr); }
    }

    .condition-option {
        position: relative;
    }
    .condition-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
    .condition-card {
        border: 1.5px solid var(--border2);
        border-radius: 10px;
        padding: 14px 10px;
        text-align: center;
        cursor: pointer;
        background: var(--bg3);
        transition: all .2s;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
    }
    .condition-card:hover {
        border-color: #1d4ed8;
    }
    .condition-option input[type="radio"]:checked + .condition-card {
        border-color: #1d4ed8;
        background: rgba(29, 78, 216, 0.08);
        box-shadow: 0 0 0 2px rgba(29, 78, 216, 0.2);
    }
    .condition-card-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--text);
    }
    .condition-card-sub {
        font-size: 10px;
        color: var(--muted);
    }

    /* Form Controls */
    .form-control {
        width: 100%;
        background: var(--input-bg);
        border: 1.5px solid var(--border2);
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13px;
        color: var(--text);
        outline: none;
        transition: border-color .2s, box-shadow .2s;
        font-family: inherit;
    }
    .form-control:focus {
        border-color: #1d4ed8;
        box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.15);
    }

    /* Upload Zone */
    .upload-zone {
        border: 2px dashed var(--border2);
        border-radius: 12px;
        padding: 24px 20px;
        text-align: center;
        cursor: pointer;
        background: var(--bg3);
        transition: all .2s;
        position: relative;
    }
    .upload-zone:hover {
        border-color: #1d4ed8;
        background: rgba(29, 78, 216, 0.04);
    }
    .upload-icon {
        width: 42px;
        height: 42px;
        color: #1d4ed8;
        margin: 0 auto 8px;
    }
    .upload-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--text);
    }
    .upload-sub {
        font-size: 11px;
        color: var(--muted);
        margin-top: 2px;
    }
    #photoInput {
        display: none;
    }

    /* Image Preview Container */
    .preview-wrapper {
        display: none;
        margin-top: 14px;
        position: relative;
        text-align: center;
    }
    .preview-img {
        max-height: 220px;
        border-radius: 10px;
        border: 1px solid var(--border2);
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    .remove-preview-btn {
        position: absolute;
        top: -8px;
        right: calc(50% - 110px);
        background: #ef4444;
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 26px;
        height: 26px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }

    /* Actions */
    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 28px;
        padding-top: 18px;
        border-top: 1px solid var(--border2);
    }
    .btn-secondary {
        padding: 10px 18px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 700;
        color: var(--muted);
        text-decoration: none;
        background: var(--bg3);
        border: 1px solid var(--border2);
        transition: all .15s;
    }
    .btn-secondary:hover {
        background: var(--border2);
        color: var(--text);
    }
    .btn-submit-cta {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f59e0b;
        color: #0f172a;
        font-size: 13px;
        font-weight: 800;
        padding: 10px 22px;
        border-radius: 9px;
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(245, 158, 11, 0.25);
        transition: all .2s;
    }
    .btn-submit-cta:hover {
        background: #d97706;
        color: #ffffff;
        transform: translateY(-1px);
    }
</style>

<div class="form-container">
    
    <div style="margin-bottom: 16px;">
        <a href="<?php echo e(route('student.returns.index')); ?>" style="color: var(--muted); text-decoration: none; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
            &larr; Kembali ke Barang Saya
        </a>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 14px 18px; border-radius: 10px; margin-bottom: 18px; font-size: 13px;">
            <div style="font-weight: 700; margin-bottom: 4px;">Terdapat kesalahan pada input Anda:</div>
            <ul style="padding-left: 18px; margin: 0;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <li><?php echo e($error); ?></li>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </ul>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="form-panel">
        <h2 style="font-size: 18px; font-weight: 800; color: var(--text); margin-bottom: 4px;">Form Pengajuan Pengembalian</h2>
        <p style="font-size: 12px; color: var(--muted); margin-bottom: 20px;">Lengkapi kondisi barang yang dikembalikan untuk diverifikasi oleh admin/petugas.</p>

        
        <div class="item-summary-card">
            <div>
                <div style="font-size: 10px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em;">Barang yang Dipinjam</div>
                <div style="font-size: 16px; font-weight: 800; color: var(--text); margin-top: 2px;"><?php echo e($borrowing->item?->name ?? 'Barang #' . $borrowing->item_id); ?></div>
                <div style="font-size: 12px; color: var(--muted); margin-top: 2px;">
                    Kode: <strong><?php echo e($borrowing->item?->code ?? '-'); ?></strong> &bull; Qty: <strong><?php echo e($borrowing->quantity); ?> Unit</strong>
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

            
            <div class="form-group">
                <label class="form-label">
                    Kondisi Fisik Barang Saat Dikembalikan <span class="required">*</span>
                </label>
                <div class="condition-grid">
                    <label class="condition-option">
                        <input type="radio" name="kondisi_barang" value="baik" <?php echo e(old('kondisi_barang', 'baik') === 'baik' ? 'checked' : ''); ?>>
                        <div class="condition-card">
                            <span style="font-size: 20px;">✨</span>
                            <div class="condition-card-title">Baik</div>
                            <div class="condition-card-sub">Normal / Utuh</div>
                        </div>
                    </label>

                    <label class="condition-option">
                        <input type="radio" name="kondisi_barang" value="rusak_ringan" <?php echo e(old('kondisi_barang') === 'rusak_ringan' ? 'checked' : ''); ?>>
                        <div class="condition-card">
                            <span style="font-size: 20px;">⚠️</span>
                            <div class="condition-card-title">Rusak Ringan</div>
                            <div class="condition-card-sub">Gores / Lecet</div>
                        </div>
                    </label>

                    <label class="condition-option">
                        <input type="radio" name="kondisi_barang" value="rusak_berat" <?php echo e(old('kondisi_barang') === 'rusak_berat' ? 'checked' : ''); ?>>
                        <div class="condition-card">
                            <span style="font-size: 20px;">❌</span>
                            <div class="condition-card-title">Rusak Berat</div>
                            <div class="condition-card-sub">Patah / Mati Total</div>
                        </div>
                    </label>

                    <label class="condition-option">
                        <input type="radio" name="kondisi_barang" value="hilang" <?php echo e(old('kondisi_barang') === 'hilang' ? 'checked' : ''); ?>>
                        <div class="condition-card">
                            <span style="font-size: 20px;">🔍</span>
                            <div class="condition-card-title">Hilang</div>
                            <div class="condition-card-sub">Tidak Ditemukan</div>
                        </div>
                    </label>
                </div>
            </div>

            
            <div class="form-group">
                <label for="catatan" class="form-label">
                    Catatan Tambahan <span style="font-weight: normal; color: var(--muted);">(Opsional)</span>
                </label>
                <textarea 
                    name="catatan" 
                    id="catatan" 
                    rows="3" 
                    class="form-control" 
                    placeholder="Tuliskan keterangan mengenai kondisi barang, kelengkapan aksesoris, atau kendala pemakaian..."><?php echo e(old('catatan')); ?></textarea>
                <div class="form-hint">Maksimal 1000 karakter.</div>
            </div>

            
            <div class="form-group">
                <label class="form-label">
                    Foto Bukti Kondisi Fisik <span style="font-weight: normal; color: var(--muted);">(Opsional, Disarankan jika rusak)</span>
                </label>

                <div class="upload-zone" id="uploadZone" onclick="document.getElementById('photoInput').click()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="upload-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <div class="upload-title">Klik atau seret foto ke sini untuk mengunggah</div>
                    <div class="upload-sub">Format: JPG, JPEG, PNG, WEBP &bull; Maksimal 2MB</div>
                </div>

                <input type="file" name="foto_bukti" id="photoInput" accept="image/jpeg,image/png,image/webp">

                
                <div class="preview-wrapper" id="previewWrapper">
                    <img id="imagePreview" src="" alt="Preview Bukti" class="preview-img">
                    <button type="button" class="remove-preview-btn" id="removePhotoBtn" title="Hapus foto">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <div style="font-size: 11px; color: var(--muted); margin-top: 6px;" id="fileNameDisplay"></div>
                </div>
            </div>

            
            <div class="form-actions">
                <a href="<?php echo e(route('student.returns.index')); ?>" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-submit-cta">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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

        // Drag & drop support
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                uploadZone.style.borderColor = '#1d4ed8';
                uploadZone.style.background = 'rgba(29, 78, 216, 0.08)';
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                uploadZone.style.borderColor = '';
                uploadZone.style.background = '';
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

        removePhotoBtn.addEventListener('click', function () {
            photoInput.value = '';
            imagePreview.src = '';
            previewWrapper.style.display = 'none';
            uploadZone.style.display = 'block';
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ASUS\SIPBARV2\resources\views\pages\siswa\returns\create.blade.php ENDPATH**/ ?>