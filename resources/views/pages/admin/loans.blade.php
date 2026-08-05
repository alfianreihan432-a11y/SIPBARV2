@extends('layouts.admin')
@section('title', 'Peminjaman')
@section('page-heading', 'Peminjaman')

@section('content')
<div style="display:flex;flex-direction:column;gap:20px">

    {{-- Hero header --}}
    <div style="background:linear-gradient(135deg,rgba(15,23,42,.95),rgba(29,78,216,.12));border:1px solid rgba(59,130,246,.18);border-radius:18px;padding:24px 28px;display:flex;align-items:center;gap:18px">
        <div style="width:52px;height:52px;background:linear-gradient(135deg,#1d4ed8,#06b6d4);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 6px 16px rgba(29,78,216,.4)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
        </div>
        <div>
            <div style="font-size:11px;font-weight:700;color:#93c5fd;letter-spacing:.1em;text-transform:uppercase;margin-bottom:4px">Manajemen Peminjaman</div>
            <div style="font-size:20px;font-weight:800;color:#f1f5f9;margin-bottom:4px">Daftar Peminjaman Barang</div>
            <div style="font-size:13px;color:#64748b">Pantau dan kelola semua transaksi peminjaman. Siswa mengajukan lewat dashboard mereka, admin menyetujui di sini.</div>
        </div>
        <div style="margin-left:auto;display:flex;gap:10px;flex-wrap:wrap;flex-shrink:0">
            <div style="background:rgba(52,211,153,.08);border:1px solid rgba(52,211,153,.18);border-radius:12px;padding:12px 16px;text-align:center">
                <div style="font-size:18px;font-weight:800;color:#34d399">{{ \App\Models\Borrowing::where('status','pending')->count() }}</div>
                <div style="font-size:11px;color:#64748b;margin-top:2px">Menunggu</div>
            </div>
            <div style="background:rgba(59,130,246,.08);border:1px solid rgba(59,130,246,.18);border-radius:12px;padding:12px 16px;text-align:center">
                <div style="font-size:18px;font-weight:800;color:#60a5fa">{{ \App\Models\Borrowing::whereIn('status',['approved','borrowed'])->count() }}</div>
                <div style="font-size:11px;color:#64748b;margin-top:2px">Aktif</div>
            </div>
            <div style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.18);border-radius:12px;padding:12px 16px;text-align:center">
                <div style="font-size:18px;font-weight:800;color:#f87171">{{ \App\Models\Borrowing::where('status','overdue')->count() }}</div>
                <div style="font-size:11px;color:#64748b;margin-top:2px">Terlambat</div>
            </div>
        </div>
    </div>

    {{-- Livewire component --}}
    @livewire('loan-manager')

</div>
@endsection
