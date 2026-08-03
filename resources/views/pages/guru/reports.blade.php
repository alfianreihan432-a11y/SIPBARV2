@extends('layouts.guru')

@section('title', 'Laporan')
@section('page-heading', 'Laporan')

@section('content')
    <div class="panel">
        <div class="panel-title">Laporan</div>
        <p class="panel-text">Halaman laporan guru. Semua tautan sidebar yang diinginkan sudah aktif.</p>
        <a href="{{ route('teacher.dashboard') }}" class="action-link">Kembali ke Dashboard</a>
    </div>
@endsection
