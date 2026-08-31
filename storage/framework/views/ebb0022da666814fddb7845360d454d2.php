<div>
    
    <div style="margin-bottom: 24px;">
        <h2 style="font-size: 22px; font-weight: 800; color: var(--text);">Katalog Barang</h2>
        <p style="font-size: 13px; color: var(--muted); margin-top: 4px;">Pilih barang yang ingin dipinjam</p>
    </div>

    
    <div style="background: var(--card); border-radius: 16px; border: 1px solid var(--border2); padding: 18px 20px; margin-bottom: 18px;">
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <div style="flex: 1;">
                <input type="text" wire:model.live.debounce.300ms="search" 
                       style="width: 100%; padding: 10px 14px; border: 1px solid var(--border2); border-radius: 10px; background: var(--input-bg); color: var(--text); font-size: 13px;"
                       placeholder="Cari barang...">
            </div>
            <div style="width: 100%;">
                <select wire:model.live="categoryFilter" 
                        style="width: 100%; padding: 10px 14px; border: 1px solid var(--border2); border-radius: 10px; background: var(--input-bg); color: var(--text); font-size: 13px;">
                    <option value="">Semua Kategori</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </select>
            </div>
        </div>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($items->count() > 0): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 16px;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div style="background: var(--card); border-radius: 14px; border: 1px solid var(--border2); overflow: hidden; transition: box-shadow .2s, transform .2s;">
                    <div style="aspect-ratio: 16/9; background: var(--bg3); position: relative;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->photo_path): ?>
                            <img src="<?php echo e(asset('storage/' . $item->photo_path)); ?>" alt="<?php echo e($item->name); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: var(--bg3);">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width: 48px; height: 48px; color: var(--subtle);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div style="position: absolute; top: 8px; right: 8px;">
                            <span style="padding: 4px 10px; background: rgba(5, 150, 105, 0.1); color: #059669; font-size: 11px; font-weight: 700; border-radius: 999px;">
                                Stok: <?php echo e($item->stock); ?>

                            </span>
                        </div>
                    </div>
                    <div style="padding: 16px;">
                        <div style="margin-bottom: 8px;">
                            <span style="font-size: 11px; color: #1d4ed8; font-weight: 600;"><?php echo e($item->category->name ?? 'Uncategorized'); ?></span>
                        </div>
                        <h3 style="font-weight: 700; color: var(--text); margin-bottom: 4px;"><?php echo e($item->name); ?></h3>
                        <p style="font-size: 12px; color: var(--muted); margin-bottom: 8px;"><?php echo e($item->code); ?></p>
                        <div style="display: flex; align-items: center; gap: 8px; font-size: 11px; color: var(--subtle); margin-bottom: 12px;">
                            <span>Kondisi: <?php echo e($item->condition); ?></span>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->teacher): ?>
                                <span>| Guru: <?php echo e($item->teacher->name); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->description): ?>
                            <p style="font-size: 11px; color: var(--subtle); margin-bottom: 12px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?php echo e($item->description); ?></p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <button wire:click="openBorrowModal(<?php echo e($item->id); ?>)" 
                                style="width: 100%; padding: 10px 16px; background: #1d4ed8; color: #fff; border-radius: 10px; font-weight: 600; font-size: 13px; cursor: pointer; border: none; transition: background .2s;">
                            Pinjam Barang
                        </button>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>

        
        <div style="margin-top: 24px;">
            <?php echo e($items->links()); ?>

        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 48px 24px; color: var(--subtle);">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 64px; height: 64px; margin-bottom: 16px; color: var(--subtle);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            <p style="font-size: 16px; font-weight: 600;">Tidak ada barang yang ditemukan</p>
            <p style="font-size: 13px; margin-top: 8px;">Coba kata kunci atau filter lain</p>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showBorrowModal && $selectedItem): ?>
        <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 50; padding: 16px;">
            <div style="background: var(--card); border-radius: 16px; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto;">
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('borrowing-form', ['itemId' => $selectedItem->id]);

$__keyOuter = $__key ?? null;

$__key = $selectedItem->id;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-3933520104-0', $__key);

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
<?php /**PATH C:\Users\ASUS\SIPBARV2\resources\views\livewire\item-catalog.blade.php ENDPATH**/ ?>