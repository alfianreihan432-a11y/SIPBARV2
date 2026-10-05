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
            <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:#ffffff !important" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
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

    @php
        $nowJakarta = now()->timezone('Asia/Jakarta');
        $unassignedOverdueList = \App\Models\BorrowingRequest::with(['user', 'itemWithTrashed', 'items.itemWithTrashed'])
            ->where(function ($q) {
                $q->whereNull('approved_by_kajur_id')
                  ->where(function ($uq) {
                      $uq->whereDoesntHave('user')
                         ->orWhereHas('user', function ($sub) {
                             $sub->whereNull('jurusan_id');
                         });
                  });
            })
            ->where(function ($q) use ($nowJakarta) {
                $q->where('status', \App\Models\BorrowingRequest::STATUS_OVERDUE)
                    ->orWhere(function ($sub) use ($nowJakarta) {
                        $sub->where('status', \App\Models\BorrowingRequest::STATUS_BORROWED)
                            ->where(function ($dateSub) use ($nowJakarta) {
                                $dateSub->whereDate('return_date', '<', $nowJakarta->toDateString())
                                    ->orWhere(function ($timeSub) use ($nowJakarta) {
                                        $timeSub->whereDate('return_date', '=', $nowJakarta->toDateString())
                                            ->whereNotNull('return_time')
                                            ->whereTime('return_time', '<', $nowJakarta->toTimeString());
                                    });
                            });
                    });
            })
            ->get();
    @endphp

    @if($unassignedOverdueList->isNotEmpty())
    <div style="background: rgba(239,68,68,0.06); border: 1.5px dashed rgba(239,68,68,0.4); border-radius: 16px; padding: 18px 22px;">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px">
            <div style="width:32px;height:32px;border-radius:8px;background:rgba(239,68,68,0.15);color:#dc2626;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <div style="font-size:14px;font-weight:800;color:var(--text-primary)">
                    Peminjaman Terlambat Tanpa Jurusan & Kajur ({{ $unassignedOverdueList->count() }})
                </div>
                <div style="font-size:12px;color:var(--text-muted)">
                    Peminjaman berikut tidak terhubung ke jurusan atau Kepala Jurusan manapun. Perlu ditindaklanjuti secara manual oleh Admin/TU.
                </div>
            </div>
        </div>
        <div style="overflow-x:auto">
            <table style="width:100%;border-collapse:collapse;font-size:12.5px">
                <thead>
                    <tr style="border-bottom:1px solid rgba(239,68,68,0.2);text-align:left;color:var(--text-muted);font-size:11px;text-transform:uppercase">
                        <th style="padding:8px">No</th>
                        <th style="padding:8px">Peminjam</th>
                        <th style="padding:8px">Barang</th>
                        <th style="padding:8px">Batas Kembali</th>
                        <th style="padding:8px">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($unassignedOverdueList as $idx => $uo)
                    <tr style="border-bottom:1px solid rgba(0,0,0,0.05)">
                        <td style="padding:8px">#BR-{{ str_pad($uo->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td style="padding:8px;font-weight:700;color:var(--text-primary)">
                            {{ $uo->user?->name ?? 'User #' . $uo->user_id }}
                            <span style="font-size:11px;color:var(--text-muted);font-weight:normal">({{ ucfirst($uo->tipe_peminjam ?? 'siswa') }})</span>
                        </td>
                        <td style="padding:8px;color:var(--text-primary)">{{ $uo->item_display_name }} ({{ $uo->totalQuantity() }} unit)</td>
                        <td style="padding:8px;color:#dc2626;font-weight:700">
                            {{ $uo->return_date ? $uo->return_date->format('d/m/Y') : '-' }}
                            @if($uo->return_time) · {{ $uo->return_time }}@endif
                        </td>
                        <td style="padding:8px">
                            <span style="display:inline-block;padding:3px 8px;border-radius:6px;font-size:11px;font-weight:700;background:rgba(239,68,68,0.15);color:#dc2626">Terlambat</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Livewire component with readonly mode --}}
    @livewire('loan-manager', ['readonly' => true])

</div>
@endsection