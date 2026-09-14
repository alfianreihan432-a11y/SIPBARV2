@extends('layouts.superadmin')

@section('title', 'Kelola Barang – SIPBAR Superadmin')
@section('page-heading', 'Kelola Barang (Read Only)')

@section('content')
<div style="display:flex;flex-direction:column;gap:20px">
    {{-- Hero header with readonly notice --}}
    <div style="background:var(--bg-card);border:1px solid var(--border-alt);border-radius:18px;padding:24px 28px;display:flex;align-items:center;gap:18px;box-shadow:var(--card-shadow);flex-wrap:wrap">
        <div style="width:52px;height:52px;background:var(--blue-dark);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <div style="font-size:11px;font-weight:700;color:var(--blue);letter-spacing:.1em;text-transform:uppercase;margin-bottom:4px">Manajemen Inventaris</div>
            <div style="font-size:20px;font-weight:800;color:var(--text-primary);margin-bottom:4px">Kelola Barang (Read Only)</div>
            <div style="font-size:13px;color:var(--text-muted)">Mode baca saja. Superadmin tidak dapat menambah, mengedit, atau menghapus barang inventaris.</div>
        </div>
    </div>

    {{-- Livewire component with readonly mode --}}
    @livewire('inventory-manager', ['readonly' => true])
</div>
@endsection