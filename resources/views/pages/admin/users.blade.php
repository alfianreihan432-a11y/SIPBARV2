@extends('layouts.admin')

@section('title', 'Pengguna')
@section('page-heading', 'Pengguna')

@section('content')
    <div class="panel">
        <div class="panel-title">Manajemen Pengguna</div>
        <p class="panel-text">Tambahkan siswa atau guru baru langsung ke database. Hanya akun siswa dan guru terdaftar yang dapat mengakses halaman peminjaman dan dashboard masing-masing.</p>
    </div>

    <div class="mt-6">
        @livewire('user-manager')
    </div>
@endsection
