<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPBAR - Inventaris</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900">
    <div class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.14),_transparent_40%)] px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="mb-8 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-sky-600">SIPBAR</p>
                    <h1 class="text-3xl font-semibold">Inventaris Barang</h1>
                    <p class="mt-2 text-slate-600">Modul inventaris siap dipakai untuk memantau aset sekolah.</p>
                </div>
                <a href="/" class="inline-flex items-center rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm">Kembali ke Landing Page</a>
            </div>
            @livewire('inventory-manager')
        </div>
    </div>
</body>
</html>
