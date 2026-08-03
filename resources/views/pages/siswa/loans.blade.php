@extends('layouts.siswa')

@section('title', 'Peminjaman')
@section('page-heading', 'Peminjaman')

@section('content')
    <div class="panel">
        <div class="panel-title">Peminjaman</div>
        <p class="panel-text">Halaman peminjaman siswa. Semua tautan sidebar siswa sekarang berfungsi.</p>
        <a href="{{ route('student.dashboard') }}" class="action-link">Kembali ke Dashboard</a>
    </div>
@endsection
