@extends('layouts.superadmin')

@section('title', 'Peminjaman – SIPBAR Superadmin')
@section('page-heading', 'Peminjaman (Read Only)')

@section('content')
@php
    $pendingSiswa = \App\Models\BorrowingRequest::where('status','pending')->where(function($q) {
        $q->where('tipe_peminjam','siswa')->orWhereNull('tipe_peminjam');
    })->count();
    $pendingGuru  = \App\Models\BorrowingRequest::where('status','pending')->where('tipe_peminjam','guru')->count();
    $pendingTotal = $pendingSiswa + $pendingGuru;

    $activeSiswa  = \App\Models\BorrowingRequest::whereIn('status',['approved','borrowed'])->where(function($q) {
        $q->where('tipe_peminjam','siswa')->orWhereNull('tipe_peminjam');
    })->count();
    $activeGuru   = \App\Models\BorrowingRequest::whereIn('status',['approved','borrowed'])->where('tipe_peminjam','guru')->count();
    $activeTotal  = $activeSiswa + $activeGuru;

    $overdueSiswa = \App\Models\BorrowingRequest::where('status','overdue')->where(function($q) {
        $q->where('tipe_peminjam','siswa')->orWhereNull('tipe_peminjam');
    })->count();
    $overdueGuru  = \App\Models\BorrowingRequest::where('status','overdue')->where('tipe_peminjam','guru')->count();
    $overdueTotal = $overdueSiswa + $overdueGuru;
@endphp
<div style="display:flex;flex-direction:column;gap:20px">

    {{-- Hero header --}}
    <div style="background:var(--bg-card);border:1px solid var(--border-alt);border-radius:18px;padding:24px 28px;display:flex;align-items:center;gap:18px;box-shadow:var(--card-shadow);flex-wrap:wrap">
        <div style="width:52px;height:52px;background:var(--blue-dark);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
        </div>
        <div>
            <div style="font-size:11px;font-weight:700;color:var(--blue);letter-spacing:.1em;text-transform:uppercase;margin-bottom:4px">Manajemen Peminjaman</div>
            <div style="font-size:20px;font-weight:800;color:var(--text-primary);margin-bottom:4px">Daftar Peminjaman Barang (Read Only)</div>
            <div style="font-size:13px;color:var(--text-muted)">Mode baca saja. Superadmin tidak dapat melakukan approve/reject peminjaman.</div>
        </div>
        <div style="margin-left:auto;display:flex;gap:10px;flex-wrap:wrap;flex-shrink:0">
            <div style="background:rgba(251,191,36,.08);border:1px solid rgba(251,191,36,.2);border-radius:12px;padding:10px 16px;text-align:center;min-width:105px">
                <div style="font-size:18px;font-weight:800;color:var(--color-warning)">{{ $pendingTotal }}</div>
                <div style="font-size:11px;font-weight:700;color:var(--text-primary);margin-top:2px">Menunggu</div>
                <div style="font-size:10px;color:var(--text-muted);margin-top:2px">{{ $pendingSiswa }} Siswa · {{ $pendingGuru }} Guru</div>
            </div>
            <div style="background:rgba(96,165,250,.08);border:1px solid rgba(96,165,250,.2);border-radius:12px;padding:10px 16px;text-align:center;min-width:105px">
                <div style="font-size:18px;font-weight:800;color:var(--color-info)">{{ $activeTotal }}</div>
                <div style="font-size:11px;font-weight:700;color:var(--text-primary);margin-top:2px">Aktif</div>
                <div style="font-size:10px;color:var(--text-muted);margin-top:2px">{{ $activeSiswa }} Siswa · {{ $activeGuru }} Guru</div>
            </div>
            <div style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);border-radius:12px;padding:10px 16px;text-align:center;min-width:105px">
                <div style="font-size:18px;font-weight:800;color:var(--color-danger)">{{ $overdueTotal }}</div>
                <div style="font-size:11px;font-weight:700;color:var(--text-primary);margin-top:2px">Terlambat</div>
                <div style="font-size:10px;color:var(--text-muted);margin-top:2px">{{ $overdueSiswa }} Siswa · {{ $overdueGuru }} Guru</div>
            </div>
        </div>
    </div>

    {{-- Livewire component with readonly mode --}}
    @livewire('loan-manager', ['readonly' => true])

</div>
@endsection