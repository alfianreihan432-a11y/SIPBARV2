@extends('layouts.admin')
@section('title', 'Inventaris Barang')
@section('page-heading', 'Barang')

@section('content')
<div style="display:flex;flex-direction:column;gap:20px">
    {{-- Hero header --}}
    <div style="background:var(--bg-card);border:1px solid var(--border-alt);border-radius:18px;padding:24px 28px;display:flex;align-items:center;gap:18px;box-shadow:var(--card-shadow)">
        <div style="width:52px;height:52px;background:var(--blue-dark);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        <div>
            <div style="font-size:11px;font-weight:700;color:var(--blue);letter-spacing:.1em;text-transform:uppercase;margin-bottom:4px">Inventaris Barang</div>
            <div style="font-size:20px;font-weight:800;color:var(--text-primary);margin-bottom:4px">Kelola Barang</div>
            <div style="font-size:13px;color:var(--text-muted)">Manajemen dan pemantauan aset serta inventaris sekolah secara terpadu.</div>
        </div>
    </div>

    @livewire('inventory-manager')
</div>
@endsection
