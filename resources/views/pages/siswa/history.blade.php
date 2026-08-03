@extends('layouts.siswa')

@section('title', 'Riwayat')
@section('page-heading', 'Riwayat')

@section('content')
    <div class="panel">
        <div class="panel-title">Riwayat</div>
        <p class="panel-text">Halaman riwayat peminjaman siswa. Tautan riwayat pada sidebar bisa digunakan.</p>
        <a href="{{ route('student.dashboard') }}" class="action-link">Kembali ke Dashboard</a>
    </div>
@endsection
