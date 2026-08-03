@extends('layouts.guru')

@section('title', 'Permohonan')
@section('page-heading', 'Permohonan')

@section('content')
    <div class="panel">
        <div class="panel-title">Permohonan</div>
        <p class="panel-text">Halaman permohonan guru. Semua tautan sidebar guru sekarang bisa diklik.</p>
        <a href="{{ route('teacher.dashboard') }}" class="action-link">Kembali ke Dashboard</a>
    </div>
@endsection
