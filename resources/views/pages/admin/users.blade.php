@extends('layouts.admin')
@section('title', 'Kelola Pengguna')
@section('page-heading', 'Pengguna')

@section('content')
<div style="display:flex;flex-direction:column;gap:22px">

    {{-- ═══ HERO HEADER ═══ --}}
    <div style="background:linear-gradient(135deg,rgba(15,23,42,.95) 0%,rgba(29,78,216,.15) 100%);border:1px solid rgba(59,130,246,.2);border-radius:20px;padding:28px 32px;display:flex;align-items:center;justify-content:space-between;gap:24px;flex-wrap:wrap">
        <div style="display:flex;align-items:center;gap:18px">
            <div style="width:56px;height:56px;background:linear-gradient(135deg,#1d4ed8,#06b6d4);border-radius:16px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 8px 20px rgba(29,78,216,.4)">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:26px;height:26px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <div style="font-size:11px;font-weight:700;color:#93c5fd;letter-spacing:.1em;text-transform:uppercase;margin-bottom:4px">Manajemen Pengguna</div>
                <div style="font-size:22px;font-weight:800;color:#f1f5f9;margin-bottom:6px">Kelola Akun Guru & Siswa</div>
                <div style="font-size:13px;color:#94a3b8;line-height:1.6;max-width:480px">Tambahkan, edit, atau hapus akun guru dan siswa. Hanya akun terdaftar yang bisa mengakses dashboard dan peminjaman.</div>
            </div>
        </div>
        <div style="display:flex;flex-direction:column;gap:10px;flex-shrink:0">
            <div style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:14px 18px;min-width:180px">
                <div style="font-size:10px;font-weight:700;color:#64748b;letter-spacing:.08em;text-transform:uppercase;margin-bottom:8px">Panduan Cepat</div>
                <div style="display:flex;flex-direction:column;gap:7px">
                    <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:#94a3b8">
                        <div style="width:20px;height:20px;background:rgba(29,78,216,.25);border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:10px;font-weight:800;color:#60a5fa">1</div>
                        Pilih peran terlebih dahulu
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:#94a3b8">
                        <div style="width:20px;height:20px;background:rgba(29,78,216,.25);border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:10px;font-weight:800;color:#60a5fa">2</div>
                        Isi data umum & detail peran
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:#94a3b8">
                        <div style="width:20px;height:20px;background:rgba(29,78,216,.25);border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:10px;font-weight:800;color:#60a5fa">3</div>
                        Klik Simpan Pengguna
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ ROLE INFO CARDS ═══ --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px">
        @foreach([
            ['👤','Admin','Kelola seluruh sistem inventaris','#ef4444','rgba(239,68,68,.12)','rgba(239,68,68,.2)'],
            ['👨‍🏫','Guru','Setujui peminjaman & monitor siswa','#a78bfa','rgba(167,139,250,.12)','rgba(167,139,250,.2)'],
            ['🎓','Siswa','Ajukan peminjaman barang inventaris','#3b82f6','rgba(59,130,246,.12)','rgba(59,130,246,.2)'],
        ] as $r)
        <div style="background:{{ $r[4] }};border:1px solid {{ $r[5] }};border-radius:14px;padding:16px 18px;display:flex;align-items:center;gap:12px">
            <div style="font-size:26px;line-height:1">{{ $r[0] }}</div>
            <div>
                <div style="font-size:14px;font-weight:700;color:#f1f5f9">{{ $r[1] }}</div>
                <div style="font-size:12px;color:#94a3b8;margin-top:2px">{{ $r[2] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ═══ LIVEWIRE COMPONENT ═══ --}}
    @livewire('user-manager')

</div>
@endsection
