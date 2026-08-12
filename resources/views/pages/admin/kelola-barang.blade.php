@extends('layouts.admin')
@section('title', 'Kelola Barang')
@section('page-heading', 'Kelola Barang')

@section('content')
<div style="display:flex;flex-direction:column;gap:20px">
    {{-- Hero header --}}
    <div style="background:var(--bg-card);border:1px solid var(--border-alt);border-radius:18px;padding:24px 28px;display:flex;align-items:center;gap:18px;box-shadow:var(--card-shadow)">
        <div style="width:52px;height:52px;background:var(--blue-dark);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <div style="font-size:11px;font-weight:700;color:var(--blue);letter-spacing:.1em;text-transform:uppercase;margin-bottom:4px">Manajemen Inventaris</div>
            <div style="font-size:20px;font-weight:800;color:var(--text-primary);margin-bottom:4px">Kelola Barang</div>
            <div style="font-size:13px;color:var(--text-muted)">Manajemen lengkap data barang, kategori, lokasi, dan pengaturan inventaris lainnya.</div>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px">
        @foreach([
            ['Total Barang', \App\Models\Item::count(), 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', '#2563eb'],
            ['Kategori', \App\Models\Category::count(), 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z', '#9333ea'],
            ['Lokasi', \App\Models\Location::count(), 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z', '#0891b2'],
            ['Supplier', \App\Models\Supplier::count(), 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', '#10b981'],
        ] as $stat)
        <div style="background:var(--bg-card);border:1px solid var(--border-alt);border-radius:14px;padding:18px 20px;display:flex;align-items:center;gap:14px;transition:all .2s;box-shadow:var(--card-shadow)">
            <div style="width:44px;height:44px;background:{{ $stat[3] }}1a;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:{{ $stat[3] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat[2] }}"/>
                </svg>
            </div>
            <div>
                <div style="font-size:22px;font-weight:800;color:var(--text-primary);line-height:1">{{ $stat[1] }}</div>
                <div style="font-size:12px;color:var(--text-muted);margin-top:3px">{{ $stat[0] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Management Sections --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px">
        @foreach([
            ['Daftar Barang', 'Lihat dan kelola semua barang inventaris', 'inventory.index', 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', '#2563eb'],
            ['Kategori Barang', 'Atur kategori dan klasifikasi barang', 'categories.index', 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z', '#9333ea'],
            ['Lokasi Penyimpanan', 'Kelola lokasi dan ruang penyimpanan', '#', 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z', '#0891b2'],
            ['Data Supplier', 'Manage informasi pemasok barang', '#', 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', '#10b981'],
        ] as $section)
        <a href="{{ is_string($section[2]) && str_starts_with($section[2], '#') ? $section[2] : route($section[2]) }}" style="background:var(--bg-card);border:1px solid var(--border-alt);border-radius:16px;padding:24px;text-decoration:none;display:block;transition:all .2s;cursor:pointer;box-shadow:var(--card-shadow)" onmouseover="this.style.borderColor='{{ $section[4] }}';this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='var(--border-alt)';this.style.transform='translateY(0)'">
            <div style="width:48px;height:48px;background:{{ $section[4] }}1a;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:16px">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:{{ $section[4] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $section[3] }}"/>
                </svg>
            </div>
            <div style="font-size:16px;font-weight:700;color:var(--text-primary);margin-bottom:6px">{{ $section[0] }}</div>
            <div style="font-size:13px;color:var(--text-muted);line-height:1.6">{{ $section[1] }}</div>
            <div style="display:flex;align-items:center;gap:6px;margin-top:14px;color:{{ $section[4] }};font-size:13px;font-weight:600">
                <span>Buka</span>
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>
        @endforeach
    </div>

    {{-- Info Panel --}}
    <div style="background:var(--bg-card);border:1px solid var(--border-alt);border-radius:16px;padding:24px;box-shadow:var(--card-shadow)">
        <div style="display:flex;align-items:flex-start;gap:16px">
            <div style="width:40px;height:40px;background:rgba(59,130,246,.15);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:var(--blue)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div style="flex:1">
                <div style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:6px">Pusat Manajemen Inventaris</div>
                <div style="font-size:13px;color:var(--text-muted);line-height:1.7">
                    Halaman ini adalah pusat kontrol untuk mengelola semua aspek inventaris sekolah. Dari sini Anda dapat mengakses manajemen barang, kategori, lokasi penyimpanan, dan data supplier. Gunakan menu di atas untuk navigasi cepat ke berbagai fitur.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
