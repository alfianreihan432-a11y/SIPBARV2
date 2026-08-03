@extends('layouts.admin')

@section('title', 'Kategori')
@section('page-heading', 'Kategori')

@section('content')
    <div class="panel">
        <div class="panel-title">Halaman Kategori</div>
        <p class="panel-text">Di sini akan ditampilkan daftar kategori inventaris. Untuk sementara ini adalah halaman placeholder yang sudah aktif.</p>
        <a href="{{ route('dashboard') }}" class="action-link">Kembali ke Dashboard</a>
    </div>
@endsection
