@extends('layouts.guru')

@section('title', 'Pengembalian')
@section('page-heading', 'Pengembalian')

@section('content')
    <div class="panel">
        <div class="panel-title">Pengembalian</div>
        <p class="panel-text">Halaman pengembalian guru. Tautan sidebar guru sudah terhubung.</p>
        <a href="{{ route('teacher.dashboard') }}" class="action-link">Kembali ke Dashboard</a>
    </div>
@endsection
