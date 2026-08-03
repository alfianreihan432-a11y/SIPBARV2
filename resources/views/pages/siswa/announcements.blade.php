@extends('layouts.siswa')

@section('title', 'Pengumuman')
@section('page-heading', 'Pengumuman')

@section('content')
    <div class="panel">
        <div class="panel-title">Pengumuman</div>
        <p class="panel-text">Halaman pengumuman siswa. Tautan pengumuman di sidebar sudah terhubung.</p>
        <a href="{{ route('student.dashboard') }}" class="action-link">Kembali ke Dashboard</a>
    </div>
@endsection
