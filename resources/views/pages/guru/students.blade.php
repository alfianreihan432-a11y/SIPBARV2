@extends('layouts.guru')

@section('title', 'Siswa Bimbingan')
@section('page-heading', 'Siswa Bimbingan')

@section('content')
    <div class="panel">
        <div class="panel-title">Siswa Bimbingan</div>
        <p class="panel-text">Halaman daftar siswa bimbingan guru. Tautan sidebar guru sudah aktif.</p>
        <a href="{{ route('teacher.dashboard') }}" class="action-link">Kembali ke Dashboard</a>
    </div>
@endsection
