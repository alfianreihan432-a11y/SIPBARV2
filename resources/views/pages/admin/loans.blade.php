@extends('layouts.admin')
@section('title', 'Peminjaman')
@section('page-heading', 'Peminjaman')

@section('content')
<div style="display:flex;flex-direction:column;gap:20px">

    {{-- Hero header --}}
    <div style="background:var(--bg-card);border:1px solid var(--border-alt);border-radius:18px;padding:24px 28px;display:flex;align-items:center;gap:18px;box-shadow:var(--card-shadow)">
        <div style="width:52px;height:52px;background:var(--blue-dark);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
        </div>
        <div>
            <div style="font-size:11px;font-weight:700;color:var(--blue);letter-spacing:.1em;text-transform:uppercase;margin-bottom:4px">Manajemen Peminjaman</div>
            <div style="font-size:20px;font-weight:800;color:var(--text-primary);margin-bottom:4px">Daftar Peminjaman Barang</div>
            <div style="font-size:13px;color:var(--text-muted)">Pantau dan kelola semua transaksi peminjaman. Siswa mengajukan lewat dashboard mereka, admin menyetujui di sini.</div>
        </div>
        <div style="margin-left:auto;display:flex;gap:10px;flex-wrap:wrap;flex-shrink:0">
            <div style="background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.2);border-radius:12px;padding:12px 16px;text-align:center">
                <div style="font-size:18px;font-weight:800;color:#10b981">{{ \App\Models\BorrowingRequest::where('status','pending')->count() }}</div>
                <div style="font-size:11px;color:var(--text-muted);margin-top:2px">Menunggu</div>
            </div>
            <div style="background:rgba(59,130,246,.1);border:1px solid rgba(59,130,246,.2);border-radius:12px;padding:12px 16px;text-align:center">
                <div style="font-size:18px;font-weight:800;color:var(--blue)">{{ \App\Models\BorrowingRequest::whereIn('status',['approved','borrowed'])->count() }}</div>
                <div style="font-size:11px;color:var(--text-muted);margin-top:2px">Aktif</div>
            </div>
            <div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);border-radius:12px;padding:12px 16px;text-align:center">
                <div style="font-size:18px;font-weight:800;color:#f87171">{{ \App\Models\BorrowingRequest::where('status','overdue')->count() }}</div>
                <div style="font-size:11px;color:var(--text-muted);margin-top:2px">Terlambat</div>
            </div>
        </div>
    </div>

    {{-- Livewire component --}}
    @livewire('loan-manager')

</div>
@endsection
