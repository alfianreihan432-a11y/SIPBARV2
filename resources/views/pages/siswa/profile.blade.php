@extends('layouts.siswa')

@section('title', 'Profil Saya – SIPBAR')

@section('content')
@php
    $user = auth()->user();
    $totalLoans    = \App\Models\BorrowingRequest::where('user_id', auth()->id())->count();
    $activeLoans   = \App\Models\BorrowingRequest::where('user_id', auth()->id())->whereIn('status', ['borrowed', 'approved'])->count();
    $returnedLoans = \App\Models\BorrowingRequest::where('user_id', auth()->id())->where('status', 'returned')->count();
@endphp

{{-- Page Header --}}
<div class="page-header">
    <div class="page-header-left">
        <div class="page-title">Profil Saya</div>
        <div class="page-subtitle">Informasi akun dan ringkasan aktivitas peminjaman kamu</div>
    </div>
</div>

{{-- Profile Card Banner --}}
<div class="s-card" style="margin-bottom:20px;padding:24px 28px">
    <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap">
        <div style="width:72px;height:72px;border-radius:50%;background:var(--primary-dark);border:3px solid var(--border2);display:flex;align-items:center;justify-content:center;font-family:var(--font-head);font-size:24px;font-weight:800;color:#fff;flex-shrink:0;box-shadow:0 4px 12px rgba(37,99,235,.2)">
            {{ $user ? strtoupper(substr($user->name, 0, 2)) : 'SI' }}
        </div>
        <div style="flex:1;min-width:200px">
            <div style="font-family:var(--font-head);font-size:20px;font-weight:800;color:var(--text);line-height:1.2">
                {{ $user ? $user->name : 'Siswa' }}
            </div>
            <div style="font-size:13px;color:var(--muted);margin-top:4px">
                {{ $user ? $user->email : '-' }}
            </div>
            <div style="display:flex;align-items:center;gap:8px;margin-top:10px;flex-wrap:wrap">
                <span class="s-badge s-badge--approved">
                    <span class="s-badge-dot" style="background:var(--s-approved)"></span>
                    Peminjam Barang
                </span>
                <span style="font-size:11.5px;color:var(--subtle);background:var(--bg3);border:1px solid var(--border2);padding:3px 10px;border-radius:6px">
                    NIS/Role: Siswa
                </span>
            </div>
        </div>
    </div>
</div>

{{-- Statistics Row --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:20px" class="profile-stat-grid">
    <div class="s-stat">
        <div class="s-stat-icon" style="background:rgba(37,99,235,.1)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:var(--primary)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <div class="s-stat-body">
            <div class="s-stat-num">{{ $totalLoans }}</div>
            <div class="s-stat-label">Total Pengajuan</div>
        </div>
    </div>
    <div class="s-stat">
        <div class="s-stat-icon" style="background:rgba(8,145,178,.1)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#0891b2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
        </div>
        <div class="s-stat-body">
            <div class="s-stat-num">{{ $activeLoans }}</div>
            <div class="s-stat-label">Sedang Dipinjam</div>
        </div>
    </div>
    <div class="s-stat">
        <div class="s-stat-icon" style="background:rgba(5,150,105,.1)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#059669" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="s-stat-body">
            <div class="s-stat-num">{{ $returnedLoans }}</div>
            <div class="s-stat-label">Selesai Dikembalikan</div>
        </div>
    </div>
</div>
<style>
    @media(max-width:768px){.profile-stat-grid{grid-template-columns:1fr!important}}
</style>

{{-- Info Grid --}}
<div class="s-card" style="margin-bottom:20px">
    <div class="s-card-header">
        <div>
            <div class="s-card-title">Informasi Akun</div>
            <div class="s-card-sub">Data identitas yang terdaftar pada sistem SIPBAR</div>
        </div>
    </div>
    <div class="s-info-grid">
        <div class="s-info-item">
            <div class="s-info-label">Nama Lengkap</div>
            <div class="s-info-value">{{ $user ? $user->name : '-' }}</div>
        </div>
        <div class="s-info-item">
            <div class="s-info-label">Alamat Email</div>
            <div class="s-info-value">{{ $user ? $user->email : '-' }}</div>
        </div>
        <div class="s-info-item">
            <div class="s-info-label">Hak Akses</div>
            <div class="s-info-value">Siswa / Peminjam Inventaris</div>
        </div>
        <div class="s-info-item">
            <div class="s-info-label">Terdaftar Sejak</div>
            <div class="s-info-value">
                {{ $user && $user->created_at ? \Carbon\Carbon::parse($user->created_at)->translatedFormat('d F Y') : '-' }}
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="s-card">
    <div class="s-card-header">
        <div>
            <div class="s-card-title">Aksi & Navigasi Cepat</div>
            <div class="s-card-sub">Pintas menuju fitur inventaris yang sering digunakan</div>
        </div>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap">
        <a href="{{ route('student.catalog') }}" class="s-btn s-btn--primary">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            Katalog Barang
        </a>
        <a href="{{ route('student.loans') }}" class="s-btn s-btn--secondary">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            Peminjaman Saya
        </a>
        <a href="{{ route('student.history') }}" class="s-btn s-btn--secondary">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Riwayat Transaksi
        </a>
        <a href="{{ route('student.announcements') }}" class="s-btn s-btn--secondary">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            Pengumuman
        </a>
    </div>
</div>
@endsection
