@extends('layouts.admin')
@section('title', 'Inventaris Barang')
@section('page-heading', 'Barang')

@section('content')
<div style="display:flex;flex-direction:column;gap:20px">
    {{-- Hero header --}}
    <div style="background:linear-gradient(135deg,rgba(15,23,42,.95),rgba(29,78,216,.12));border:1px solid rgba(59,130,246,.18);border-radius:18px;padding:24px 28px;display:flex;align-items:center;gap:18px">
        <div style="width:52px;height:52px;background:#1d4ed8;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 6px 16px rgba(29,78,216,.4)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        <div>
            <div style="font-size:11px;font-weight:700;color:#93c5fd;letter-spacing:.1em;text-transform:uppercase;margin-bottom:4px">Inventaris Barang</div>
            <div style="font-size:20px;font-weight:800;color:#f1f5f9;margin-bottom:4px">Kelola Barang</div>
            <div style="font-size:13px;color:#64748b">Manajemen dan pemantauan aset serta inventaris sekolah secara terpadu.</div>
        </div>
    </div>

    @livewire('inventory-manager')
</div>
@endsection
