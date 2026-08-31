<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SIPBAR - Sistem Inventaris Barang Sekolah</title>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900">
        <header class="border-b border-slate-200 bg-white/80 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-sky-600">SIPBAR</p>
                    <h1 class="text-lg font-semibold">Sistem Inventaris Barang Sekolah</h1>
                </div>
                <nav class="flex items-center gap-4 text-sm font-medium">
                    <a href="#fitur" class="text-slate-600 hover:text-sky-600">Fitur</a>
                    <a href="#profil" class="text-slate-600 hover:text-sky-600">Profil</a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('login')): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                            <a href="<?php echo e(route('dashboard')); ?>" class="rounded-full bg-sky-600 px-4 py-2 text-white">Dashboard</a>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="rounded-full border border-slate-300 px-4 py-2 text-slate-700">Login</a>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('register')): ?>
                                <a href="<?php echo e(route('register')); ?>" class="rounded-full bg-slate-900 px-4 py-2 text-white">Daftar</a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-6 py-16">
            <section class="grid items-center gap-10 lg:grid-cols-2">
                <div>
                    <p class="mb-4 inline-flex rounded-full bg-sky-100 px-3 py-1 text-sm font-semibold text-sky-700">Modern • Aman • Terukur</p>
                    <h2 class="text-4xl font-semibold leading-tight sm:text-5xl">Kelola inventaris sekolah dengan dashboard yang profesional.</h2>
                    <p class="mt-6 text-lg text-slate-600">SIPBAR membantu sekolah memantau barang, peminjaman, pemeliharaan, serta laporan secara real-time tanpa ribet.</p>
                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="/inventory" class="rounded-full bg-sky-600 px-6 py-3 font-semibold text-white shadow-lg shadow-sky-200">Lihat Inventaris</a>
                        <a href="<?php echo e(route('register')); ?>" class="rounded-full border border-slate-300 px-6 py-3 font-semibold text-slate-700">Daftar Sekarang</a>
                    </div>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl bg-sky-50 p-5">
                            <p class="text-sm text-slate-500">Total Barang</p>
                            <p class="mt-2 text-3xl font-semibold text-sky-700">120</p>
                        </div>
                        <div class="rounded-2xl bg-emerald-50 p-5">
                            <p class="text-sm text-slate-500">Barang Dipinjam</p>
                            <p class="mt-2 text-3xl font-semibold text-emerald-700">24</p>
                        </div>
                        <div class="rounded-2xl bg-amber-50 p-5">
                            <p class="text-sm text-slate-500">Maintenance</p>
                            <p class="mt-2 text-3xl font-semibold text-amber-700">8</p>
                        </div>
                        <div class="rounded-2xl bg-rose-50 p-5">
                            <p class="text-sm text-slate-500">Rusak</p>
                            <p class="mt-2 text-3xl font-semibold text-rose-700">3</p>
                        </div>
                    </div>
                </div>
            </section>

            <section id="fitur" class="mt-20 grid gap-6 md:grid-cols-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [['title'=>'CRUD Inventaris','desc'=>'Tambah, ubah, hapus, dan telusuri aset sekolah.'],['title'=>'Peminjaman & Pengembalian','desc'=>'Kelola status pinjam, kembali, dan approval.'],['title'=>'Laporan & Export','desc'=>'Siapkan laporan PDF dan Excel dengan cepat.']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="text-xl font-semibold"><?php echo e($feature['title']); ?></h3>
                        <p class="mt-3 text-slate-600"><?php echo e($feature['desc']); ?></p>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </section>
        </main>

        <footer class="border-t border-slate-200 bg-white/80 py-8">
            <div class="mx-auto max-w-7xl px-6 text-center text-sm text-slate-600">
                © <?php echo e(date('Y')); ?> SIPBAR. Dibuat untuk sekolah modern yang ingin mengelola aset dengan lebih baik.
            </div>
        </footer>
    </body>
</html>
<?php /**PATH C:\Users\ASUS\SIPBARV2\resources\views\welcome-new.blade.php ENDPATH**/ ?>