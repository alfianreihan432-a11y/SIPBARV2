<?php $__env->startSection('title', 'Riwayat Transaksi'); ?>
<?php $__env->startSection('page-heading', 'Riwayat Transaksi'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-4">
            <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Total</div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($stats['total']); ?></div>
        </div>
        <div class="bg-amber-50 dark:bg-amber-900/20 rounded-lg shadow-sm border border-amber-200 dark:border-amber-800 p-4">
            <div class="text-xs font-semibold text-amber-700 dark:text-amber-400 uppercase tracking-wide mb-1">Pending</div>
            <div class="text-2xl font-bold text-amber-900 dark:text-amber-300"><?php echo e($stats['pending']); ?></div>
        </div>
        <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-lg shadow-sm border border-emerald-200 dark:border-emerald-800 p-4">
            <div class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 uppercase tracking-wide mb-1">Disetujui</div>
            <div class="text-2xl font-bold text-emerald-900 dark:text-emerald-300"><?php echo e($stats['approved']); ?></div>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg shadow-sm border border-blue-200 dark:border-blue-800 p-4">
            <div class="text-xs font-semibold text-blue-700 dark:text-blue-400 uppercase tracking-wide mb-1">Dipinjam</div>
            <div class="text-2xl font-bold text-blue-900 dark:text-blue-300"><?php echo e($stats['borrowed']); ?></div>
        </div>
        <div class="bg-gray-50 dark:bg-slate-700 rounded-lg shadow-sm border border-gray-200 dark:border-slate-600 p-4">
            <div class="text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wide mb-1">Dikembalikan</div>
            <div class="text-2xl font-bold text-gray-900 dark:text-gray-300"><?php echo e($stats['returned']); ?></div>
        </div>
        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg shadow-sm border border-red-200 dark:border-red-800 p-4">
            <div class="text-xs font-semibold text-red-700 dark:text-red-400 uppercase tracking-wide mb-1">Ditolak</div>
            <div class="text-2xl font-bold text-red-900 dark:text-red-300"><?php echo e($stats['rejected']); ?></div>
        </div>
    </div>

    
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Filter & Pencarian</h2>
        
        <form method="GET" action="<?php echo e(route('transactions.index')); ?>" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama Siswa</label>
                <input type="text" 
                       name="student_name" 
                       value="<?php echo e(request('student_name')); ?>"
                       placeholder="Cari nama siswa..."
                       class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama Barang</label>
                <input type="text" 
                       name="item_name" 
                       value="<?php echo e(request('item_name')); ?>"
                       placeholder="Cari nama barang..."
                       class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</label>
                <select name="status" 
                        class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <option value="">Semua Status</option>
                    <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Pending</option>
                    <option value="approved" <?php echo e(request('status') === 'approved' ? 'selected' : ''); ?>>Disetujui</option>
                    <option value="borrowed" <?php echo e(request('status') === 'borrowed' ? 'selected' : ''); ?>>Dipinjam</option>
                    <option value="returned" <?php echo e(request('status') === 'returned' ? 'selected' : ''); ?>>Dikembalikan</option>
                    <option value="rejected" <?php echo e(request('status') === 'rejected' ? 'selected' : ''); ?>>Ditolak</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tanggal Dari</label>
                <input type="date" 
                       name="date_from" 
                       value="<?php echo e(request('date_from')); ?>"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tanggal Sampai</label>
                <input type="date" 
                       name="date_to" 
                       value="<?php echo e(request('date_to')); ?>"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>
            
            <div class="flex items-end gap-2">
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg font-semibold transition-colors">
                    Cari
                </button>
                <a href="<?php echo e(route('transactions.index')); ?>" 
                   class="px-4 py-2 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 font-semibold transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($transactions->isEmpty()): ?>
            <div class="text-center py-12">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-lg text-gray-500 dark:text-gray-400">Tidak ada transaksi ditemukan</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-slate-700 border-b border-gray-200 dark:border-slate-600">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Siswa</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">#<?php echo e($transaction->id); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white"><?php echo e($transaction->user->name); ?></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($transaction->user->kelas ?? 'N/A'); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white"><?php echo e($transaction->item->name); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"><?php echo e($transaction->quantity); ?> unit</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white"><?php echo e(\Carbon\Carbon::parse($transaction->borrow_date)->format('d M Y')); ?></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">s/d <?php echo e(\Carbon\Carbon::parse($transaction->return_date)->format('d M Y')); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 rounded text-xs font-bold
                                    <?php if($transaction->status === 'pending'): ?> bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300
                                    <?php elseif($transaction->status === 'approved'): ?> bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300
                                    <?php elseif($transaction->status === 'borrowed'): ?> bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                    <?php elseif($transaction->status === 'returned'): ?> bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300
                                    <?php elseif($transaction->status === 'rejected'): ?> bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                    <?php endif; ?>">
                                    <?php echo e(ucfirst($transaction->status)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="<?php echo e(route('transactions.show', $transaction->id)); ?>" 
                                   class="text-teal-600 hover:text-teal-800 dark:text-teal-400 dark:hover:text-teal-300 font-semibold">
                                    Detail →
                                </a>
                            </td>
                        </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            
            <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700">
                <?php echo e($transactions->links()); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ASUS\SIPBARV2\resources\views\pages\admin\transactions\index.blade.php ENDPATH**/ ?>