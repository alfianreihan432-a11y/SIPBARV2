@extends('layouts.admin')

@section('title', 'Pengembalian')
@section('page-heading', 'Pengembalian')

@section('content')
    <div class="panel">
        <div class="panel-title">Halaman Pengembalian</div>
        <p class="panel-text">Di sini akan ditampilkan proses pengembalian barang. Halaman ini sudah aktif dan dapat diakses.</p>
        <a href="{{ route('dashboard') }}" class="action-link">Kembali ke Dashboard</a>
    </div>
@endsection
