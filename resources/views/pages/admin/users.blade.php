@extends('layouts.admin')

@section('title', 'Pengguna')
@section('page-heading', 'Pengguna')

@section('content')
    <div class="panel">
        <div class="panel-title">Halaman Pengguna</div>
        <p class="panel-text">Di sini akan ditampilkan daftar pengguna dan manajemen hak akses. Halaman ini sudah aktif dan dapat diakses.</p>
        <a href="{{ route('dashboard') }}" class="action-link">Kembali ke Dashboard</a>
    </div>
@endsection
