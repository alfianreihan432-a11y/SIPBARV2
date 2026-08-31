<div>
    
    <div class="page-header">
        <div class="page-header-left">
            <div class="page-title">
                Katalog Barang
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($items->total() > 0): ?>
                <span class="page-title-count"><?php echo e($items->total()); ?> item</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="page-subtitle">Pilih barang yang ingin kamu pinjam dari inventaris sekolah</div>
        </div>
    </div>

    
    <div class="s-card s-card--flat" style="margin-bottom:20px">
        <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
            <div style="flex:1;min-width:200px">
                <div class="s-filter-label" style="margin-bottom:6px">Cari Barang</div>
                <div style="position:relative">
                    <svg xmlns="http://www.w3.org/2000/svg" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);width:15px;height:15px;color:var(--subtle)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" wire:model.live.debounce.300ms="search"
                        style="width:100%;padding:9px 12px 9px 38px;background:var(--input-bg);border:1px solid var(--border2);border-radius:10px;font-size:13px;color:var(--text);font-family:var(--font-body);outline:none;transition:border-color .15s"
                        placeholder="Nama barang, kode, atau deskripsi...">
                </div>
            </div>
            <div style="min-width:180px">
                <div class="s-filter-label" style="margin-bottom:6px">Kategori</div>
                <select wire:model.live="categoryFilter"
                    style="width:100%;padding:9px 12px;background:var(--input-bg);border:1px solid var(--border2);border-radius:10px;font-size:13px;color:var(--text);font-family:var(--font-body);outline:none">
                    <option value="">Semua Kategori</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </select>
            </div>
        </div>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($items->count() > 0): ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;margin-bottom:24px">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div style="background:var(--card);border-radius:16px;border:1px solid var(--border2);overflow:hidden;transition:all .2s;display:flex;flex-direction:column"
                 onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.1)'"
                 onmouseout="this.style.transform='';this.style.boxShadow=''">
                
                <div style="aspect-ratio:4/3;background:var(--bg3);position:relative;overflow:hidden">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->photo_path): ?>
                        <img src="<?php echo e(asset('storage/' . $item->photo_path)); ?>" alt="<?php echo e($item->name); ?>"
                             style="width:100%;height:100%;object-fit:cover">
                    <?php else: ?>
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:40px;height:40px;color:var(--subtle)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    
                    <div style="position:absolute;top:10px;right:10px">
                        <span style="padding:4px 10px;background:<?php echo e($item->stock > 0 ? 'rgba(5,150,105,.85)' : 'rgba(220,38,38,.85)'); ?>;color:#fff;font-size:11px;font-weight:700;border-radius:999px;backdrop-filter:blur(4px)">
                            <?php echo e($item->stock > 0 ? 'Stok: '.$item->stock : 'Habis'); ?>

                        </span>
                    </div>
                    
                    <div style="position:absolute;top:10px;left:10px">
                        <span style="padding:3px 8px;background:rgba(0,0,0,.55);color:#fff;font-size:10px;font-weight:600;border-radius:5px;letter-spacing:.03em;backdrop-filter:blur(4px)">
                            <?php echo e($item->category->name ?? 'Umum'); ?>

                        </span>
                    </div>
                </div>

                
                <div style="padding:16px;flex:1;display:flex;flex-direction:column;gap:6px">
                    <div style="font-family:var(--font-head);font-size:14px;font-weight:700;color:var(--text);line-height:1.3"><?php echo e($item->name); ?></div>
                    <div style="font-size:11.5px;color:var(--subtle)"><?php echo e($item->code); ?></div>
                    <div style="display:flex;align-items:center;gap:8px;margin-top:2px">
                        <span style="font-size:11px;color:var(--muted);background:var(--bg3);border:1px solid var(--border2);padding:2px 8px;border-radius:5px">
                            Kondisi: <?php echo e($item->condition); ?>

                        </span>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->description): ?>
                    <div style="font-size:12px;color:var(--muted);margin-top:2px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;line-height:1.5">
                        <?php echo e($item->description); ?>

                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div style="margin-top:auto;padding-top:12px">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->stock > 0): ?>
                        <button wire:click="openBorrowModal(<?php echo e($item->id); ?>)"
                                class="s-btn s-btn--primary" style="width:100%;justify-content:center">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            Pinjam Barang
                        </button>
                        <?php else: ?>
                        <button disabled class="s-btn" style="width:100%;justify-content:center;background:var(--bg3);color:var(--subtle);cursor:not-allowed;border:1px solid var(--border2)">
                            Stok Habis
                        </button>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
        
        <div style="margin-top:8px"><?php echo e($items->links()); ?></div>
    <?php else: ?>
        <div class="s-card">
            <div class="s-empty">
                <div class="s-empty-icon-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:32px;height:32px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div class="s-empty-title">Barang tidak ditemukan</div>
                <div class="s-empty-sub">Coba ubah kata kunci atau pilih kategori lain</div>
                <button wire:click="$set('search','')" wire:click="$set('categoryFilter','')" class="s-btn s-btn--secondary">Reset Pencarian</button>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showBorrowModal && $selectedItem): ?>
    <div style="position:fixed;inset:0;background:rgba(0,0,0,.6);display:flex;align-items:center;justify-content:center;z-index:50;padding:16px;backdrop-filter:blur(3px)">
        <div style="background:var(--card);border-radius:20px;max-width:600px;width:100%;max-height:90vh;overflow-y:auto;box-shadow:0 24px 48px rgba(0,0,0,.3)">
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('borrowing-form', ['itemId' => $selectedItem->id]);

$__keyOuter = $__key ?? null;

$__key = 'borrowing-form-'.$selectedItem->id;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-22899217-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\Users\ASUS\SIPBARV2\resources\views/livewire/item-catalog.blade.php ENDPATH**/ ?>