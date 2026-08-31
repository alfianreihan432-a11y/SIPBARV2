<div>
<style>
/* ── Category Manager Styles ── */
.cm-wrap { display: flex; flex-direction: column; gap: 24px; }

/* Alert */
.cm-alert {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 16px; border-radius: 12px;
    background: rgba(16,185,129,.1); border: 1px solid rgba(16,185,129,.25);
    color: #10b981; font-size: 13px; font-weight: 600;
    animation: slideIn .3s ease;
}
.cm-alert-err { background: rgba(239,68,68,.1); border-color: rgba(239,68,68,.25); color: #f87171; }
@keyframes slideIn { from { opacity:0; transform:translateY(-8px) } to { opacity:1; transform:translateY(0) } }

/* Form Card */
.cm-form-card {
    background: var(--bg-card);
    border: 1px solid var(--border-alt);
    border-radius: 18px;
    padding: 24px 28px;
    box-shadow: var(--card-shadow);
}
.cm-form-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 20px; }
.cm-form-title { font-size: 18px; font-weight: 800; color: var(--text-primary); margin-bottom: 4px; }
.cm-form-sub { font-size: 13px; color: var(--text-muted); }
.cm-form-icon {
    width: 44px; height: 44px; background: rgba(37,99,235,.12);
    border: 1px solid rgba(37,99,235,.2); border-radius: 12px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}

/* Form Grid */
.cm-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
@media (max-width: 640px) {
    .cm-grid { grid-template-columns: 1fr; }
    .cm-form-card { padding: 18px 16px; }
}
.cm-field { display: flex; flex-direction: column; gap: 6px; }
.cm-field.full { grid-column: 1 / -1; }
.cm-label { font-size: 11px; font-weight: 700; color: var(--text-muted); letter-spacing: .05em; text-transform: uppercase; }
.cm-input, .cm-select, .cm-textarea {
    background: var(--input-bg);
    border: 1.5px solid var(--input-border);
    border-radius: 10px;
    padding: 10px 13px;
    font-size: 13px; color: var(--text-primary);
    outline: none; width: 100%;
    transition: border-color .2s, box-shadow .2s;
    font-family: inherit;
}
.cm-input:focus, .cm-select:focus, .cm-textarea:focus {
    border-color: var(--blue);
    box-shadow: 0 0 0 3px rgba(59,130,246,.15);
}
.cm-input::placeholder, .cm-textarea::placeholder { color: var(--text-subtle); }
.cm-select option { background: var(--bg-card); color: var(--text-primary); }
.cm-textarea { resize: vertical; min-height: 90px; }
.cm-color-row { display: flex; align-items: center; gap: 10px; }
.cm-color-input { width: 52px; height: 42px; padding: 3px; border-radius: 8px; border: 1.5px solid var(--input-border); background: var(--input-bg); cursor: pointer; }
.cm-color-preview {
    flex: 1; height: 42px; border-radius: 8px;
    display: flex; align-items: center; padding: 0 14px;
    font-size: 13px; font-weight: 600; color: var(--text-primary);
    border: 1.5px solid var(--input-border);
}
.cm-error { font-size: 11px; color: #f87171; margin-top: 2px; }

/* Buttons */
.cm-actions { display: flex; align-items: center; gap: 10px; margin-top: 20px; padding-top: 18px; border-top: 1px solid var(--border-subtle); flex-wrap: wrap; }
.cm-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 20px; border-radius: 10px; font-size: 13px; font-weight: 700;
    cursor: pointer; border: none; transition: all .2s; text-decoration: none;
}
.cm-btn-primary { background: var(--blue-dark); color: #fff; box-shadow: 0 4px 12px rgba(29,78,216,.3); }
.cm-btn-primary:hover { background: var(--blue); transform: translateY(-1px); }
.cm-btn-secondary { background: var(--bg-card-subtle); color: var(--text-muted); border: 1px solid var(--border-subtle); }
.cm-btn-secondary:hover { background: var(--bg-hover); color: var(--text-primary); }
.cm-btn-danger { background: rgba(239,68,68,.1); color: #f87171; border: 1px solid rgba(239,68,68,.2); padding: 7px 12px; font-size: 12px; border-radius: 8px; }
.cm-btn-danger:hover { background: #ef4444; color: #fff; }
.cm-btn-edit { background: rgba(59,130,246,.1); color: var(--blue); border: 1px solid rgba(59,130,246,.2); padding: 7px 12px; font-size: 12px; border-radius: 8px; }
.cm-btn-edit:hover { background: var(--blue); color: #fff; }
.cm-editing-badge { display: inline-flex; align-items: center; gap: 6px; background: rgba(245,158,11,.1); border: 1px solid rgba(245,158,11,.2); color: #f59e0b; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 999px; }

/* Table Card */
.cm-table-card {
    background: var(--bg-card);
    border: 1px solid var(--border-alt);
    border-radius: 18px;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    box-shadow: var(--card-shadow);
}
.cm-table-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 22px; border-bottom: 1px solid var(--border-subtle);
    flex-wrap: wrap; gap: 10px;
}
.cm-table-title { font-size: 16px; font-weight: 800; color: var(--text-primary); }
.cm-count { font-size: 11px; background: rgba(37,99,235,.12); color: var(--blue); border: 1px solid rgba(37,99,235,.2); padding: 3px 10px; border-radius: 999px; font-weight: 700; }
.cm-table-search { display: flex; align-items: center; gap: 8px; background: var(--input-bg); border: 1.5px solid var(--input-border); border-radius: 10px; padding: 7px 12px; }
.cm-table-search input { background: none; border: none; outline: none; font-size: 13px; color: var(--text-primary); width: 200px; }
.cm-table-search input::placeholder { color: var(--text-subtle); }
.cm-empty { padding: 56px 24px; text-align: center; }
.cm-empty-icon { font-size: 44px; margin-bottom: 12px; }
.cm-empty-text { font-size: 13px; color: var(--text-muted); }

table.cm-table { width: 100%; border-collapse: collapse; }
table.cm-table thead th {
    padding: 11px 18px; font-size: 10px; font-weight: 800;
    color: var(--text-subtle); letter-spacing: .08em; text-transform: uppercase;
    background: var(--table-head-bg); text-align: left;
    border-bottom: 1px solid var(--border-subtle);
}
table.cm-table tbody tr { border-bottom: 1px solid var(--border-subtle); transition: background .15s; }
table.cm-table tbody tr:last-child { border-bottom: none; }
table.cm-table tbody tr:hover { background: var(--table-hover); }
table.cm-table tbody td { padding: 13px 18px; font-size: 13px; color: var(--text-secondary); vertical-align: middle; }
.cm-cat-name { font-size: 13px; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 8px; }
.cm-color-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 4px 10px; border-radius: 999px;
    font-size: 11px; font-weight: 700;
}
.cm-color-dot { width: 8px; height: 8px; border-radius: 50%; }
.cm-actions-cell { display: flex; gap: 6px; align-items: center; }
@media (max-width: 768px) { .cm-grid { grid-template-columns: 1fr; } }
</style>

<div class="cm-wrap" <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'category-manager'; ?>wire:key="category-manager">

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
    <div class="cm-alert">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <?php echo e(session('message')); ?>

    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div class="cm-form-card">
        <div class="cm-form-header">
            <div>
                <div class="cm-form-title">
                    <?php echo e($editingId ? 'Edit Kategori' : 'Tambah Kategori Baru'); ?>

                </div>
                <div class="cm-form-sub">
                    <?php echo e($editingId ? 'Perbarui data kategori inventaris.' : 'Tambahkan kategori baru untuk mengelompokkan barang inventaris.'); ?>

                </div>
            </div>
            <div style="display:flex;align-items:center;gap:10px">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editingId): ?>
                <div class="cm-editing-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Mode Edit
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="cm-form-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#3b82f6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
            </div>
        </div>

        <form wire:submit.prevent="save">
            <div class="cm-grid">
                
                <div class="cm-field">
                    <label class="cm-label">Nama Kategori <span style="color:#ef4444">*</span></label>
                    <input wire:model="name" type="text" class="cm-input" placeholder="Contoh: Elektronik, Furnitur...">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="cm-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div class="cm-field">
                    <label class="cm-label">Kode / Nama Ikon</label>
                    <input wire:model="icon" type="text" class="cm-input" placeholder="Contoh: Tag, Box, Desktop">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['icon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="cm-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div class="cm-field">
                    <label class="cm-label">Warna Kategori</label>
                    <div class="cm-color-row">
                        <input wire:model.live="color" type="color" class="cm-color-input" value="<?php echo e($color ?? '#3b82f6'); ?>">
                        <div class="cm-color-preview" style="background:<?php echo e($color ?? '#3b82f6'); ?>1a;border-color:<?php echo e($color ?? '#3b82f6'); ?>40;color:<?php echo e($color ?? '#3b82f6'); ?>">
                            <span style="width:10px;height:10px;border-radius:50%;background:<?php echo e($color ?? '#3b82f6'); ?>;display:inline-block;margin-right:8px"></span>
                            <?php echo e($color ?? '#3b82f6'); ?>

                        </div>
                    </div>
                </div>

                
                <div class="cm-field">
                    <label class="cm-label">Deskripsi</label>
                    <textarea wire:model="description" class="cm-textarea" placeholder="Deskripsi singkat kategori ini..." rows="3"></textarea>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="cm-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <div class="cm-actions">
                <button type="submit" class="cm-btn cm-btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editingId): ?>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        <?php else: ?>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </svg>
                    <?php echo e($editingId ? 'Simpan Perubahan' : 'Tambah Kategori'); ?>

                </button>
                <button type="button" wire:click="resetForm" class="cm-btn cm-btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Reset
                </button>
                <div style="margin-left:auto;font-size:12px;color:#475569;display:flex;align-items:center;gap:5px">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;color:#64748b" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Tanda <span style="color:#ef4444">*</span> wajib diisi
                </div>
            </div>
        </form>
    </div>

    
    <div class="cm-table-card">
        <div class="cm-table-header">
            <div style="display:flex;align-items:center;gap:12px">
                <div class="cm-table-title">Daftar Kategori</div>
                <span class="cm-count"><?php echo e($categories->count()); ?> kategori</span>
            </div>
            <div class="cm-table-search">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;color:#475569;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari kategori...">
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($categories->isEmpty()): ?>
        <div class="cm-empty">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-800 text-blue-400 border border-slate-700/60 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <div class="cm-empty-text">Belum ada kategori. Tambahkan kategori pertama di atas.</div>
        </div>
        <?php else: ?>
        <div style="overflow-x:auto">
            <table class="cm-table">
                <thead>
                    <tr>
                        <th style="width:40px">#</th>
                        <th>Nama & Ikon</th>
                        <th>Deskripsi</th>
                        <th>Warna</th>
                        <th>Jumlah Barang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'cat-'.e($cat->id).''; ?>wire:key="cat-<?php echo e($cat->id); ?>">
                        <td style="color:#475569;font-size:12px"><?php echo e($i + 1); ?></td>
                        <td>
                            <div class="cm-cat-name">
                                <div class="h-7 w-7 rounded-lg flex items-center justify-center text-xs font-bold text-white shrink-0" style="background:<?php echo e($cat->color ?? '#3b82f6'); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                </div>
                                <?php echo e($cat->name); ?>

                            </div>
                        </td>
                        <td style="max-width:220px">
                            <span style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;font-size:13px;line-height:1.5">
                                <?php echo e($cat->description ?: '—'); ?>

                            </span>
                        </td>
                        <td>
                            <span class="cm-color-badge" style="background:<?php echo e($cat->color ?? '#3b82f6'); ?>1a;color:<?php echo e($cat->color ?? '#3b82f6'); ?>">
                                <span class="cm-color-dot" style="background:<?php echo e($cat->color ?? '#3b82f6'); ?>"></span>
                                <?php echo e($cat->color ?? '#3b82f6'); ?>

                            </span>
                        </td>
                        <td>
                            <span style="font-size:13px;font-weight:700;color:#60a5fa">
                                <?php echo e($cat->items_count ?? ($cat->items ? $cat->items->count() : 0)); ?> barang
                            </span>
                        </td>
                        <td>
                            <div class="cm-actions-cell">
                                <button wire:click="edit(<?php echo e($cat->id); ?>)" class="cm-btn cm-btn-edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </button>
                                <button wire:click="delete(<?php echo e($cat->id); ?>)"
                                    wire:confirm="Hapus kategori '<?php echo e($cat->name); ?>'? Aksi ini tidak dapat dibatalkan."
                                    class="cm-btn cm-btn-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
</div>
<?php /**PATH C:\Users\ASUS\SIPBARV2\resources\views/livewire/category-manager.blade.php ENDPATH**/ ?>