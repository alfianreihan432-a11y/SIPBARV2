@extends('layouts.siswa')

@section('title', 'Katalog Barang')
@section('page-heading', 'Katalog Barang')

@section('content')
    <div class="panel">
        <div class="panel-title">Katalog Barang</div>
        <p class="panel-text">Halaman katalog barang siswa. Tautan sidebar sekarang sudah aktif dan mengarah ke halaman ini.</p>
        <a href="{{ route('student.dashboard') }}" class="action-link">Kembali ke Dashboard</a>
    </div>
@endsection
