@extends('layouts.admin')

@section('title', 'Laporan')
@section('page-heading', 'Laporan')

@section('content')
    <div class="panel">
        <div class="panel-title">Halaman Laporan</div>
        <p class="panel-text">Di sini akan ditampilkan laporan inventaris dan peminjaman. Halaman ini sudah aktif dan dapat diakses.</p>
        <a href="{{ route('dashboard') }}" class="action-link">Kembali ke Dashboard</a>
    </div>
@endsection
