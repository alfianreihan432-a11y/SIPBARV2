@extends('layouts.admin')

@section('title', 'Kategori')
@section('page-heading', 'Kategori')

@section('content')
    <div class="panel">
        <div class="panel-title">Manajemen Kategori</div>
        <p class="panel-text">Tambahkan, edit, atau hapus kategori inventaris. Kategori yang dibuat akan muncul di form tambah barang.</p>
    </div>

    <div class="mt-6">
        @livewire('category-manager')
    </div>
@endsection
