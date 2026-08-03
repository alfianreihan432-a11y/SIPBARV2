@extends('layouts.admin')

@section('title', 'Statistik')
@section('page-heading', 'Statistik')

@section('content')
    <div class="panel">
        <div class="panel-title">Halaman Statistik</div>
        <p class="panel-text">Di sini akan ditampilkan statistik penggunaan barang dan peminjaman. Halaman ini sudah aktif dan dapat diakses.</p>
        <a href="{{ route('dashboard') }}" class="action-link">Kembali ke Dashboard</a>
    </div>
@endsection
