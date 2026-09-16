@extends('layouts.superadmin')

@section('title', 'Statistik – SIPBAR Superadmin')
@section('page-heading', 'Statistik (Read Only)')

@section('content')
<div style="display:flex;flex-direction:column;gap:22px">

    {{-- ═══ HERO HEADER ═══ --}}
    <div style="background:var(--bg-card);border:1px solid var(--border-alt);border-radius:18px;padding:24px 28px;display:flex;align-items:center;gap:18px;box-shadow:var(--card-shadow);flex-wrap:wrap">
        <div style="width:52px;height:52px;background:var(--blue-dark);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#ffffff !important" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
        </div>
        <div>
            <div style="font-size:11px;font-weight:700;color:var(--blue);letter-spacing:.1em;text-transform:uppercase;margin-bottom:4px">Statistik Sistem</div>
            <div style="font-size:20px;font-weight:800;color:var(--text-primary);margin-bottom:4px">Statistik Penggunaan (Read Only)</div>
            <div style="font-size:13px;color:var(--text-muted)">Mode baca saja. Superadmin dapat melihat statistik penggunaan barang dan peminjaman sistem.</div>
        </div>
    </div>

    {{-- ═══ CONTENT PANEL ═══ --}}
    <div style="background:var(--bg-card);border:1px solid var(--border-alt);border-radius:18px;padding:24px 28px;box-shadow:var(--card-shadow)">
        <div style="font-size:20px;font-weight:800;color:var(--text-primary);margin-bottom:8px">Halaman Statistik</div>
        <div style="font-size:14px;color:var(--text-muted);line-height:1.6;margin-bottom:16px">Di sini akan ditampilkan statistik penggunaan barang dan peminjaman sistem secara keseluruhan. Superadmin dapat memantau tren penggunaan inventaris di seluruh sekolah.</div>
        <a href="{{ route('superadmin.dashboard') }}" style="display:inline-flex;align-items:center;gap:6px;padding:10px 18px;background:var(--blue-dark);border:none;border-radius:10px;font-size:12px;font-weight:700;color:#ffffff !important;text-decoration:none;transition:opacity .15s">
            Kembali ke Dashboard
        </a>
    </div>

</div>
@endsection