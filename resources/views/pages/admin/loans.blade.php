@extends('layouts.admin')

@section('title', 'Peminjaman')
@section('page-heading', 'Peminjaman')

@section('content')
    <div class="panel">
        <div class="panel-title">Halaman Peminjaman</div>
        <p class="panel-text">Di sini akan ditampilkan daftar peminjaman barang. Halaman ini sudah aktif dan dapat diakses.</p>
        <a href="{{ route('dashboard') }}" class="action-link">Kembali ke Dashboard</a>
    </div>
@endsection
