@extends('layouts.admin')
@section('title', 'Kelola Pengguna')
@section('page-heading', 'Pengguna')

@section('content')
<div style="display:flex;flex-direction:column;gap:22px">

    {{-- ═══ HERO HEADER ═══ --}}
    <div style="background:var(--bg-card);border:1px solid var(--border-alt);border-radius:18px;padding:24px 28px;display:flex;align-items:center;justify-content:space-between;gap:24px;flex-wrap:wrap;box-shadow:var(--card-shadow)">
        <div style="display:flex;align-items:center;gap:18px">
            <div style="width:52px;height:52px;background:var(--blue-dark);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:26px;height:26px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <div style="font-size:11px;font-weight:700;color:var(--blue);letter-spacing:.1em;text-transform:uppercase;margin-bottom:4px">Manajemen Pengguna</div>
                <div style="font-size:20px;font-weight:800;color:var(--text-primary);margin-bottom:4px">Kelola Akun Guru & Siswa</div>
                <div style="font-size:13px;color:var(--text-muted);line-height:1.6;max-width:480px">Tambahkan, edit, atau hapus akun guru dan siswa. Hanya akun terdaftar yang bisa mengakses dashboard dan peminjaman.</div>
            </div>
        </div>
        <div style="display:flex;flex-direction:column;gap:10px;flex-shrink:0">
            <div style="background:var(--bg-card-subtle);border:1px solid var(--border-subtle);border-radius:12px;padding:12px 16px;min-width:180px">
                <div style="font-size:10px;font-weight:700;color:var(--text-subtle);letter-spacing:.08em;text-transform:uppercase;margin-bottom:8px">Panduan Cepat</div>
                <div style="display:flex;flex-direction:column;gap:7px">
                    <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text-muted)">
                        <div style="width:20px;height:20px;background:rgba(29,78,216,.2);border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:10px;font-weight:800;color:var(--blue)">1</div>
                        Pilih peran terlebih dahulu
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text-muted)">
                        <div style="width:20px;height:20px;background:rgba(29,78,216,.2);border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:10px;font-weight:800;color:var(--blue)">2</div>
                        Isi data umum & detail peran
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text-muted)">
                        <div style="width:20px;height:20px;background:rgba(29,78,216,.2);border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:10px;font-weight:800;color:var(--blue)">3</div>
                        Klik Simpan Pengguna
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ ROLE INFO CARDS ═══ --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px">
        @foreach([
            ['<svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#ef4444" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>','Admin','Kelola seluruh sistem inventaris','#ef4444','rgba(239,68,68,.1)'],
            ['<svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#10b981" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>','Guru','Setujui peminjaman & monitor siswa','#10b981','rgba(16,185,129,.1)'],
            ['<svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#2563eb" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>','Siswa','Ajukan peminjaman barang inventaris','#2563eb','rgba(37,99,235,.1)'],
        ] as $r)
        <div style="background:var(--bg-card);border:1px solid var(--border-alt);border-radius:14px;padding:16px 18px;display:flex;align-items:center;gap:12px;box-shadow:var(--card-shadow)">
            <div style="display:flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:10px;background:{{ $r[4] }}">{!! $r[0] !!}</div>
            <div>
                <div style="font-size:14px;font-weight:700;color:var(--text-primary)">{{ $r[1] }}</div>
                <div style="font-size:12px;color:var(--text-muted);margin-top:2px">{{ $r[2] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ═══ LIVEWIRE COMPONENT ═══ --}}
    @livewire('user-manager')

</div>
@endsection
