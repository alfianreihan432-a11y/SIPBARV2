
<div <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'user-manager'; ?>wire:key="user-manager">
<style>
.um{display:flex;flex-direction:column;gap:20px}
/* ── TABS ── */
.um-tabs{display:flex;gap:0;background:var(--bg-card-subtle);border:1px solid var(--border-subtle);border-radius:14px;padding:4px;width:fit-content}
.um-tab{display:flex;align-items:center;gap:8px;padding:9px 20px;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;border:none;background:none;color:var(--text-muted);transition:all .2s}
.um-tab:hover{color:var(--text-primary);background:var(--bg-hover)}
.um-tab.active{color:#fff}
.um-tab-siswa.active{background:var(--blue-dark);box-shadow:0 4px 12px rgba(29,78,216,.3)}
.um-tab-guru.active{background:#0f766e;box-shadow:0 4px 12px rgba(15,118,110,.3)}
/* ── FORM CARD ── */
.um-card{background:var(--bg-card);border:1px solid var(--border-alt);border-radius:18px;overflow:hidden;box-shadow:var(--card-shadow)}
.um-card-top{padding:20px 24px 18px;border-bottom:1px solid var(--border-subtle)}
.um-card-title{font-size:17px;font-weight:800;color:var(--text-primary);margin-bottom:3px}
.um-card-sub{font-size:12px;color:var(--text-muted)}
.um-card-body{padding:22px 24px}
/* ── FIELDS ── */
.um-grid{display:grid;gap:16px}
.um-grid-2{grid-template-columns:1fr 1fr}
.um-grid-3{grid-template-columns:1fr 1fr 1fr}
.um-field{display:flex;flex-direction:column;gap:6px}
.um-label{font-size:11px;font-weight:700;color:var(--text-muted);letter-spacing:.06em;text-transform:uppercase;display:flex;align-items:center;gap:5px}
.um-req{color:#f87171;font-size:13px}
.um-input-wrap{position:relative}
.um-input-wrap svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);width:15px;height:15px;color:var(--text-subtle);pointer-events:none;flex-shrink:0}
.um-input{width:100%;background:var(--input-bg);border:1.5px solid var(--input-border);border-radius:10px;padding:10px 14px 10px 38px;font-size:13px;color:var(--text-primary);outline:none;transition:border-color .2s,box-shadow .2s;font-family:inherit}
.um-input:focus{border-color:var(--blue);box-shadow:0 0 0 3px rgba(59,130,246,.13)}
.um-input::placeholder{color:var(--text-subtle)}
.um-hint{font-size:11px;color:var(--text-subtle);margin-top:2px}
.um-error{font-size:12px;color:#f87171;display:flex;align-items:center;gap:4px;margin-top:3px}
/* ── ALERT ── */
.um-alert{display:flex;align-items:flex-start;gap:12px;padding:14px 18px;border-radius:12px;font-size:13px;animation:umIn .3s ease;margin-bottom:4px}
.um-alert-ok{background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);color:#10b981}
.um-alert-creds{background:rgba(59,130,246,.08);border:1px solid rgba(59,130,246,.2);color:var(--blue);margin-top:8px}
@keyframes umIn{from{opacity:0;transform:translateY(-6px)}to{opacity:1;transform:translateY(0)}}
.creds-box{background:var(--bg-card-subtle);border:1px solid var(--border-subtle);border-radius:8px;padding:10px 14px;margin-top:8px;font-family:monospace;font-size:12px;line-height:2;color:var(--blue)}
/* ── ACTIONS ── */
.um-actions{display:flex;align-items:center;gap:10px;padding:16px 24px;border-top:1px solid var(--border-subtle)}
.um-btn{display:inline-flex;align-items:center;gap:7px;padding:9px 20px;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;border:none;transition:all .2s}
.um-btn-siswa{background:var(--blue-dark);color:#fff;box-shadow:0 4px 12px rgba(29,78,216,.3)}
.um-btn-siswa:hover{background:var(--blue);transform:translateY(-1px)}
.um-btn-guru{background:#0f766e;color:#fff;box-shadow:0 4px 12px rgba(15,118,110,.3)}
.um-btn-guru:hover{background:#0d9488;transform:translateY(-1px)}
.um-btn-secondary{background:var(--bg-card-subtle);color:var(--text-muted);border:1px solid var(--border-subtle)}
.um-btn-secondary:hover{background:var(--bg-hover);color:var(--text-primary)}
.um-edit-badge{display:inline-flex;align-items:center;gap:5px;background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.2);color:#f59e0b;font-size:11px;font-weight:700;padding:4px 10px;border-radius:999px;margin-left:auto}
/* ── TABLE ── */
.um-table-card{background:var(--bg-card);border:1px solid var(--border-alt);border-radius:18px;overflow:hidden;box-shadow:var(--card-shadow)}
.um-table-header{display:flex;align-items:center;justify-content:space-between;padding:16px 22px;border-bottom:1px solid var(--border-subtle);flex-wrap:wrap;gap:10px}
.um-table-title{font-size:15px;font-weight:800;color:var(--text-primary)}
.um-count{font-size:11px;background:rgba(29,78,216,.14);color:var(--blue);border:1px solid rgba(29,78,216,.2);padding:3px 10px;border-radius:999px;font-weight:700}
.um-search-wrap{display:flex;align-items:center;gap:8px;background:var(--input-bg);border:1.5px solid var(--input-border);border-radius:10px;padding:7px 12px}
.um-search-wrap input{background:none;border:none;outline:none;font-size:13px;color:var(--text-primary);width:200px}
.um-search-wrap input::placeholder{color:var(--text-subtle)}
table.umt{width:100%;border-collapse:collapse}
table.umt thead th{padding:11px 18px;font-size:10px;font-weight:700;color:var(--text-subtle);letter-spacing:.08em;text-transform:uppercase;background:var(--table-head-bg);text-align:left;border-bottom:1px solid var(--border-subtle)}
table.umt tbody tr{border-bottom:1px solid var(--border-subtle);transition:background .15s}
table.umt tbody tr:last-child{border-bottom:none}
table.umt tbody tr:hover{background:var(--table-hover)}
table.umt tbody td{padding:13px 18px;font-size:13px;color:var(--text-secondary);vertical-align:middle}
.umt-avatar{width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;flex-shrink:0}
.umt-name{font-size:13px;font-weight:700;color:var(--text-primary);margin-bottom:2px}
.umt-sub{font-size:11px;color:var(--text-muted);font-family:monospace}
.umt-chip{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:7px;font-size:11px;font-weight:700}
.chip-siswa{background:rgba(59,130,246,.12);color:var(--blue);border:1px solid rgba(59,130,246,.18)}
.chip-guru{background:rgba(15,118,110,.12);color:#10b981;border:1px solid rgba(15,118,110,.18)}
.chip-admin{background:rgba(239,68,68,.12);color:#f87171;border:1px solid rgba(239,68,68,.18)}
.chip-other{background:rgba(148,163,184,.1);color:var(--text-muted);border:1px solid rgba(148,163,184,.15)}
.umt-btn{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border-radius:8px;font-size:12px;font-weight:700;cursor:pointer;border:none;transition:all .2s}
.umt-btn-edit{background:rgba(59,130,246,.1);color:var(--blue);border:1px solid rgba(59,130,246,.18)}
.umt-btn-edit:hover{background:var(--blue);color:#fff;border-color:var(--blue)}
.umt-btn-del{background:rgba(239,68,68,.08);color:#f87171;border:1px solid rgba(239,68,68,.15)}
.umt-btn-del:hover{background:#ef4444;color:#fff;border-color:#ef4444}
.um-empty{padding:56px;text-align:center;color:var(--text-muted);font-size:13px}
@media(max-width:800px){.um-grid-2,.um-grid-3{grid-template-columns:1fr}.um-tabs{width:100%}.um-tab{flex:1;justify-content:center}}
</style>

<div class="um">


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
<div class="um-alert um-alert-ok">
    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;flex-shrink:0;margin-top:1px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <div>
        <div style="font-weight:700">Berhasil!</div>
        <div style="margin-top:2px;opacity:.85"><?php echo e(session('message')); ?></div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('generated_email')): ?>
        <div class="creds-box">
            📧 Email: <?php echo e(session('generated_email')); ?><br>
            🔑 Password: <?php echo e(session('generated_password')); ?>

        </div>
        <div style="font-size:11px;opacity:.7;margin-top:6px">⚠️ Catat kredensial ini dan berikan ke pengguna. Password dapat diganti setelah login.</div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('error')): ?>
<div class="um-alert" style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);color:#ef4444">
    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;flex-shrink:0;margin-top:1px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <div>
        <div style="font-weight:700">Error!</div>
        <div style="margin-top:2px;opacity:.85"><?php echo e(session('error')); ?></div>
    </div>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


<div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap">
    <div class="um-tabs">
        <button class="um-tab um-tab-siswa <?php echo e($activeTab === 'siswa' ? 'active' : ''); ?>"
            wire:click="setTab('siswa')" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13"/></svg>
            Tambah Siswa
        </button>
        <button class="um-tab um-tab-guru <?php echo e($activeTab === 'guru' ? 'active' : ''); ?>"
            wire:click="setTab('guru')" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Tambah Guru
        </button>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editingId): ?>
    <div class="um-edit-badge" style="margin-left:0">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Mode Edit — <?php echo e(ucfirst($activeTab)); ?>

    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>


<div class="um-card">
    <div class="um-card-top">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab === 'siswa'): ?>
        <div style="display:flex;align-items:center;gap:12px">
            <div style="width:42px;height:42px;background:linear-gradient(135deg,#1d4ed8,#2563eb);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(29,78,216,.4)">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
            </div>
            <div>
                <div class="um-card-title"><?php echo e($editingId ? 'Edit Data Siswa' : 'Form Tambah Siswa'); ?></div>
                <div class="um-card-sub">Nama lengkap, NIS, kelas, dan tanggal lahir wajib diisi</div>
            </div>
        </div>
        <?php else: ?>
        <div style="display:flex;align-items:center;gap:12px">
            <div style="width:42px;height:42px;background:linear-gradient(135deg,#0f766e,#0d9488);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(15,118,110,.4)">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1"/></svg>
            </div>
            <div>
                <div class="um-card-title"><?php echo e($editingId ? 'Edit Data Guru' : 'Form Tambah Guru'); ?></div>
                <div class="um-card-sub">Nama lengkap, NIP, dan tanggal lahir wajib diisi</div>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <form wire:submit.prevent="save">
        <div class="um-card-body">

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sijunaMessage): ?>
            <div class="um-alert <?php echo e($sijunaSuccess ? 'um-alert-ok' : ''); ?>" style="<?php echo e(!$sijunaSuccess ? 'background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);color:#f87171;' : ''); ?>margin-bottom:18px">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;flex-shrink:0;margin-top:1px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($sijunaSuccess ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'); ?>"/></svg>
                <div style="font-size:12px;font-weight:600"><?php echo e($sijunaMessage); ?></div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab === 'siswa'): ?>
            
            <div class="um-grid um-grid-2">
                
                <div class="um-field" style="grid-column:1/-1">
                    <label class="um-label" style="justify-content:space-between">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px;display:inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1"/></svg>
                            NIS (Nomor Induk Siswa) <span class="um-req">*</span>
                        </span>
                        <button type="button" wire:click="fetchSijunaData" class="um-btn" style="padding:4px 10px;font-size:11px;background:rgba(99,102,241,.12);color:#6366f1;border:1px solid rgba(99,102,241,.25)">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Impor dari SIJUNA
                        </button>
                    </label>
                    <div class="um-input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                        <input wire:model="nis" type="text" class="um-input" placeholder="Ketik NIS lalu klik 'Impor dari SIJUNA'..." name="siswa_nis" autocomplete="off">
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nis'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="um-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="um-hint">NIS digunakan sebagai username login siswa & pencarian data SIJUNA</div>
                </div>

                
                <div class="um-field">
                    <label class="um-label">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Nama Lengkap <span class="um-req">*</span>
                    </label>
                    <div class="um-input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <input wire:model="name" type="text" class="um-input" placeholder="Nama lengkap siswa" autocomplete="off">
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="um-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div class="um-field">
                    <label class="um-label">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
                        Kelas <span class="um-req">*</span>
                    </label>
                    <div class="um-input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1"/></svg>
                        <input wire:model="kelas" type="text" class="um-input" placeholder="Contoh: XII IPA 1" name="siswa_kelas" autocomplete="off">
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['kelas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="um-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div class="um-field">
                    <label class="um-label">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
                        Jurusan <span class="um-req">*</span>
                    </label>
                    <div class="um-input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        <input wire:model="jurusan" type="text" class="um-input" placeholder="Contoh: IPA / IPS / Kejuruan" name="siswa_jurusan" autocomplete="off">
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['jurusan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="um-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div class="um-field">
                    <label class="um-label">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Tanggal Lahir <span class="um-req">*</span>
                    </label>
                    <div class="um-input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <input wire:model="tanggal_lahir" type="date" class="um-input" autocomplete="off" name="siswa_tanggal_lahir">
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['tanggal_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="um-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div class="um-field">
                    <label class="um-label">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        Nomor HP <span class="um-req">*</span>
                    </label>
                    <div class="um-input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <input wire:model="phone" type="text" class="um-input" placeholder="Contoh: 08123456789" autocomplete="off" name="siswa_phone" id="siswa_phone">
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="um-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="um-hint">Format: 08xxx, 628xxx, atau +628xxx</div>
                </div>
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$editingId): ?>
            <div style="display:flex;align-items:flex-start;gap:10px;background:rgba(29,78,216,.07);border:1px solid rgba(29,78,216,.18);border-radius:11px;padding:12px 16px;margin-top:16px;font-size:12px;color:#60a5fa">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;flex-shrink:0;margin-top:1px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>Email dan password akan dibuat otomatis dari NIS. Format: <strong>2024001@sipbar.sch.id</strong> / password: <strong>siswa2024001</strong></div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php elseif($activeTab === 'guru'): ?>
            
            <div class="um-grid um-grid-2">
                
                <div class="um-field" style="grid-column:1/-1">
                    <label class="um-label" style="justify-content:space-between">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px;display:inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1"/></svg>
                            NIP (Nomor Induk Pegawai) <span class="um-req">*</span>
                        </span>
                        <button type="button" wire:click="fetchSijunaData" class="um-btn" style="padding:4px 10px;font-size:11px;background:rgba(15,118,110,.12);color:#10b981;border:1px solid rgba(15,118,110,.25)">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Impor dari SIJUNA
                        </button>
                    </label>
                    <div class="um-input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                        <input wire:model="nip" type="text" class="um-input" placeholder="Ketik NIP lalu klik 'Impor dari SIJUNA'..." name="guru_nip" autocomplete="off">
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nip'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="um-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="um-hint">NIP digunakan sebagai username login guru & pencarian data SIJUNA</div>
                </div>

                
                <div class="um-field" style="grid-column:1/-1">
                    <label class="um-label">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Nama Lengkap <span class="um-req">*</span>
                    </label>
                    <div class="um-input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <input wire:model="name" type="text" class="um-input" placeholder="Nama lengkap guru beserta gelar" autocomplete="off">
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="um-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div class="um-field">
                    <label class="um-label">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Jabatan <span class="um-req">*</span>
                    </label>
                    <div class="um-input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <input wire:model="jabatan" type="text" class="um-input" placeholder="Contoh: Guru Matematika / Kepala Sekolah" name="guru_jabatan" autocomplete="off">
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['jabatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="um-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div class="um-field">
                    <label class="um-label">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Tanggal Lahir <span class="um-req">*</span>
                    </label>
                    <div class="um-input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <input wire:model="tanggal_lahir" type="date" class="um-input" autocomplete="off" name="guru_tanggal_lahir">
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['tanggal_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="um-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div class="um-field">
                    <label class="um-label">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        Nomor HP <span class="um-req">*</span>
                    </label>
                    <div class="um-input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <input wire:model="phone" type="text" class="um-input" placeholder="Contoh: 08123456789" autocomplete="off" name="guru_phone" id="guru_phone">
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="um-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="um-hint">Format: 08xxx, 628xxx, atau +628xxx</div>
                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$editingId): ?>
            <div style="display:flex;align-items:flex-start;gap:10px;background:rgba(15,118,110,.07);border:1px solid rgba(15,118,110,.2);border-radius:11px;padding:12px 16px;margin-top:16px;font-size:12px;color:#34d399">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;flex-shrink:0;margin-top:1px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>Email dan password akan dibuat otomatis dari NIP. Format: <strong>198505152010011001@sipbar.sch.id</strong> / password: <strong>guru198505152010011001</strong></div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </div>

        <div class="um-actions">
            <button type="submit" class="um-btn <?php echo e($activeTab === 'siswa' ? 'um-btn-siswa' : 'um-btn-guru'); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editingId): ?><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    <?php else: ?><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </svg>
                <?php echo e($editingId ? 'Simpan Perubahan' : ($activeTab === 'siswa' ? 'Tambah Siswa' : 'Tambah Guru')); ?>

            </button>
            <button type="button" wire:click="resetForm" class="um-btn um-btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Reset
            </button>
            <span style="margin-left:auto;font-size:12px;color:#334155"><span style="color:#f87171">*</span> Wajib diisi</span>
        </div>
    </form>
</div>


<div class="um-table-card">
    <div class="um-table-header">
        <div style="display:flex;align-items:center;gap:10px">
            <span class="um-table-title">Daftar Pengguna</span>
            <span class="um-count"><?php echo e($users->count()); ?> pengguna</span>
        </div>
        <div class="um-search-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;color:#334155;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" placeholder="Cari nama, NIS, NIP..." oninput="umFilter(this.value)" id="umSearch">
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($users->isEmpty()): ?>
    <div class="um-empty">
        <div style="font-size:40px;margin-bottom:10px">👥</div>
        <div>Belum ada pengguna. Gunakan form di atas untuk menambahkan.</div>
    </div>
    <?php else: ?>
    <div style="overflow-x:auto">
        <table class="umt" id="umTable">
            <thead>
                <tr>
                    <th style="width:36px">#</th>
                    <th>Pengguna</th>
                    <th>Peran</th>
                    <th>ID Khusus</th>
                    <th>Kelas / Jabatan</th>
                    <th>Tgl. Lahir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php
                    $uRole  = $u->roles->first()?->name ?? 'user';
                    $chipCls = match($uRole) {
                        'siswa'  => 'chip-siswa',
                        'guru'   => 'chip-guru',
                        'admin','super-admin' => 'chip-admin',
                        default  => 'chip-other',
                    };
                    $avatarBg = match($uRole) {
                        'siswa'  => 'linear-gradient(135deg,#1d4ed8,#06b6d4)',
                        'guru'   => 'linear-gradient(135deg,#0f766e,#06b6d4)',
                        default  => 'linear-gradient(135deg,#7c3aed,#06b6d4)',
                    };
                    $emoji = match($uRole) {
                        'siswa'  => '🎓','guru' => '👨‍🏫','admin','super-admin' => '👤',
                        default  => '🔧'
                    };
                ?>
                <tr <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'u-'.e($u->id).''; ?>wire:key="u-<?php echo e($u->id); ?>">
                    <td style="color:#334155;font-size:12px"><?php echo e($i+1); ?></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div class="umt-avatar" style="background:<?php echo e($avatarBg); ?>"><?php echo e(strtoupper(substr($u->name,0,2))); ?></div>
                            <div>
                                <div class="umt-name"><?php echo e($u->name); ?></div>
                                <div class="umt-sub"><?php echo e($u->email); ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="umt-chip <?php echo e($chipCls); ?>">
                            <?php echo e($emoji); ?> <?php echo e(ucfirst($uRole)); ?>

                        </span>
                    </td>
                    <td style="font-family:monospace;font-size:12px;color:#475569">
                        <?php echo e($uRole === 'siswa' ? ($u->nis ?? '—') : ($uRole === 'guru' ? ($u->nip ?? '—') : '—')); ?>

                    </td>
                    <td style="font-size:13px;color:#475569">
                        <?php echo e($uRole === 'siswa' ? ($u->kelas ?? '—') : '—'); ?>

                    </td>
                    <td style="font-size:12px;color:#475569">
                        <?php echo e($u->tanggal_lahir ? \Carbon\Carbon::parse($u->tanggal_lahir)->format('d M Y') : '—'); ?>

                    </td>
                    <td>
                        <div style="display:flex;gap:7px">
                            <button wire:click="edit(<?php echo e($u->id); ?>)" class="umt-btn umt-btn-edit">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </button>
                            <button wire:click="delete(<?php echo e($u->id); ?>)" wire:confirm="Hapus '<?php echo e($u->name); ?>'?" class="umt-btn umt-btn-del">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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

<script>
function umFilter(q) {
    document.querySelectorAll('#umTable tbody tr').forEach(function(r){
        r.style.display = r.textContent.toLowerCase().includes(q.toLowerCase()) ? '' : 'none';
    });
}
</script>
</div>
<?php /**PATH C:\Users\ASUS\SIPBARV2\resources\views\livewire\user-manager.blade.php ENDPATH**/ ?>