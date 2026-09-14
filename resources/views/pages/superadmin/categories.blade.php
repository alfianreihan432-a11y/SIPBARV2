@extends('layouts.superadmin')

@section('title', 'Kategori – SIPBAR Superadmin')
@section('page-heading', 'Kategori (Read Only)')

@section('content')
<div style="display:flex;flex-direction:column;gap:20px">
    {{-- Hero header with readonly notice --}}
    <div style="background:var(--bg-card);border:1px solid var(--border-alt);border-radius:18px;padding:24px 28px;display:flex;align-items:center;gap:18px;box-shadow:var(--card-shadow);flex-wrap:wrap">
        <div style="width:52px;height:52px;background:var(--blue-dark);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
        </div>
        <div>
            <div style="font-size:11px;font-weight:700;color:var(--blue);letter-spacing:.1em;text-transform:uppercase;margin-bottom:4px">Manajemen Kategori</div>
            <div style="font-size:20px;font-weight:800;color:var(--text-primary);margin-bottom:4px">Kategori Barang (Read Only)</div>
            <div style="font-size:13px;color:var(--text-muted)">Mode baca saja. Superadmin tidak dapat menambah, mengedit, atau menghapus kategori.</div>
        </div>
    </div>

    {{-- Livewire component with readonly mode --}}
    @livewire('category-manager', ['readonly' => true])
</div>
@endsection