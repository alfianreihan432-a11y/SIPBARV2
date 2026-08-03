@extends('layouts.guru')

@section('title', 'Peminjaman Aktif')
@section('page-heading', 'Peminjaman Aktif')

@section('content')
    <div class="panel">
        <div class="panel-title">Peminjaman Aktif</div>
        <p class="panel-text">Halaman peminjaman aktif guru. Tautan sidebar guru sekarang berfungsi.</p>
        <a href="{{ route('teacher.dashboard') }}" class="action-link">Kembali ke Dashboard</a>
    </div>
@endsection
