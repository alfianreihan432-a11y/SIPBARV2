@extends('layouts.admin')

@section('title', 'Peminjaman')
@section('page-heading', 'Peminjaman')

@section('content')
    <div class="panel">
        <div class="panel-title">Halaman Peminjaman</div>
        <p class="panel-text">Hanya siswa yang terdaftar di database dapat meminjam barang melalui panel admin.</p>
    </div>

    <div class="mt-6">
        @livewire('loan-manager')
    </div>
@endsection
