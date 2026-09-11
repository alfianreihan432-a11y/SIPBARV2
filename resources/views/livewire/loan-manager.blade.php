<div>
<style>
.lm-card{background:var(--bg-card);border:1px solid var(--border-alt);border-radius:18px;overflow:hidden;box-shadow:var(--card-shadow)}
.lm-header{display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1px solid var(--border-subtle);flex-wrap:wrap;gap:12px}
.lm-title{font-size:16px;font-weight:800;color:var(--text-primary)}
.lm-count{font-size:11px;background:rgba(29,78,216,.14);color:var(--blue);border:1px solid rgba(29,78,216,.2);padding:3px 10px;border-radius:999px;font-weight:700}
.lm-search{display:flex;align-items:center;gap:8px;background:var(--input-bg);border:1.5px solid var(--input-border);border-radius:10px;padding:7px 12px}
.lm-search input{background:none;border:none;outline:none;font-size:13px;color:var(--text-primary);width:220px}
.lm-search input::placeholder{color:var(--text-subtle)}

/* Filter Rows */
.lm-filters-wrapper{display:flex;flex-direction:column;gap:10px;padding:14px 22px;border-bottom:1px solid var(--border-subtle);background:var(--bg-card-subtle)}
.lm-filter-group{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.lm-filter-label{font-size:11px;color:var(--text-subtle);font-weight:700;text-transform:uppercase;letter-spacing:.06em;min-width:55px}
.lm-filter-btn{display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;border:1px solid var(--border-subtle);background:var(--bg-card);color:var(--text-muted);transition:all .15s}
.lm-filter-btn:hover{background:var(--bg-hover);color:var(--text-primary)}
.lm-filter-btn.act{background:var(--blue-dark);color:#fff;border-color:var(--blue-dark);box-shadow:0 2px 6px rgba(29,78,216,.3)}
.lm-filter-btn.act-guru{background:#059669;color:#fff;border-color:#059669;box-shadow:0 2px 6px rgba(5,150,105,.3)}
.lm-filter-btn.act-siswa{background:#2563eb;color:#fff;border-color:#2563eb;box-shadow:0 2px 6px rgba(37,99,235,.3)}

/* Badges */
.lm-badge{display:inline-flex;align-items:center;gap:5px;padding:4px 9px;border-radius:7px;font-size:11px;font-weight:700;white-space:nowrap}
.badge-pending{background:rgba(245,158,11,.12);color:#f59e0b;border:1px solid rgba(245,158,11,.2)}
.badge-approved{background:rgba(59,130,246,.12);color:var(--blue);border:1px solid rgba(59,130,246,.2)}
.badge-borrowed{background:rgba(234,179,8,.12);color:#eab308;border:1px solid rgba(234,179,8,.2)}
.badge-returned{background:rgba(16,185,129,.12);color:#10b981;border:1px solid rgba(16,185,129,.2)}
.badge-rejected{background:rgba(239,68,68,.12);color:#f87171;border:1px solid rgba(239,68,68,.2)}
.badge-overdue{background:rgba(239,68,68,.15);color:#f87171;border:1px solid rgba(239,68,68,.25)}

/* Type Badges */
.badge-type-siswa{background:rgba(59,130,246,.12);color:#3b82f6;border:1px solid rgba(59,130,246,.25);font-size:11px;font-weight:700;padding:3px 8px;border-radius:6px;display:inline-flex;align-items:center;gap:4px}
.badge-type-guru{background:rgba(16,185,129,.12);color:#10b981;border:1px solid rgba(16,185,129,.25);font-size:11px;font-weight:700;padding:3px 8px;border-radius:6px;display:inline-flex;align-items:center;gap:4px}

/* Table */
table.lmt{width:100%;border-collapse:collapse}
table.lmt thead th{padding:12px 16px;font-size:10px;font-weight:700;color:var(--text-subtle);letter-spacing:.08em;text-transform:uppercase;background:var(--table-head-bg);text-align:left;border-bottom:1px solid var(--border-subtle);white-space:nowrap}
table.lmt tbody tr{border-bottom:1px solid var(--border-subtle);transition:background .15s}
table.lmt tbody tr:last-child{border-bottom:none}
table.lmt tbody tr:hover{background:var(--table-hover)}
table.lmt tbody td{padding:12px 16px;font-size:13px;color:var(--text-secondary);vertical-align:middle}

.lmt-num{font-size:12px;font-weight:700;color:var(--text-primary);font-family:monospace}
.lmt-student{display:flex;align-items:center;gap:9px}
.lmt-avatar{width:32px;height:32px;border-radius:50%;background:var(--blue-dark);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;flex-shrink:0}
.lmt-avatar.guru-avatar{background:linear-gradient(135deg,#059669,#10b981)}
.lmt-name{font-size:13px;font-weight:600;color:var(--text-primary)}
.lmt-sub{font-size:11px;color:var(--text-muted)}
.lmt-item{font-size:13px;color:var(--text-secondary);line-height:1.4}
.lmt-qty{display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;background:rgba(59,130,246,.12);color:var(--blue);font-weight:700;font-size:12px;border-radius:6px}
.lmt-date{font-size:12px;color:var(--text-muted)}
.lmt-due{font-size:12px}
.lmt-due.ok{color:#10b981}
.lmt-due.warn{color:#f59e0b}
.lmt-due.over{color:#f87171;font-weight:700}

/* Action Buttons */
.lm-act-btn{display:inline-flex;align-items:center;gap:4px;padding:5px 9px;border-radius:7px;font-size:11px;font-weight:700;cursor:pointer;border:none;transition:all .15s;white-space:nowrap}
.btn-approve{background:rgba(16,185,129,.12);color:#10b981;border:1px solid rgba(16,185,129,.2)}
.btn-approve:hover{background:#10b981;color:#fff}
.btn-borrowed{background:rgba(234,179,8,.12);color:#eab308;border:1px solid rgba(234,179,8,.2)}
.btn-borrowed:hover{background:#eab308;color:#fff}
.btn-return{background:rgba(59,130,246,.12);color:var(--blue);border:1px solid rgba(59,130,246,.2)}
.btn-return:hover{background:var(--blue);color:#fff}
.btn-reject{background:rgba(239,68,68,.08);color:#f87171;border:1px solid rgba(239,68,68,.2)}
.btn-reject:hover{background:#f87171;color:#fff}
.btn-detail{background:rgba(148,163,184,.12);color:var(--text-secondary);border:1px solid var(--border-subtle)}
.btn-detail:hover{background:var(--bg-hover);color:var(--text-primary)}

/* Kajur Notice Badge */
.lm-kajur-notice{display:inline-flex;align-items:center;gap:4px;padding:4px 8px;border-radius:6px;font-size:11px;font-weight:600;background:rgba(245,158,11,.1);color:#d97706;border:1px dashed rgba(245,158,11,.3);cursor:default}

.lm-empty{padding:56px;text-align:center;color:var(--text-muted)}
.lm-alert-success{display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:11px;background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);color:#10b981;font-size:13px;font-weight:600;margin-bottom:16px;animation:lmIn .3s ease}
.lm-alert-danger{display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:11px;background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);color:#ef4444;font-size:13px;font-weight:600;margin-bottom:16px;animation:lmIn .3s ease}
@keyframes lmIn{from{opacity:0;transform:translateY(-5px)}to{opacity:1;transform:translateY(0)}}

/* Modal */
.lm-modal-backdrop{position:fixed;inset:0;background:rgba(0,0,0,.65);backdrop-filter:blur(4px);z-index:9999;display:flex;align-items:center;justify-content:center;padding:16px;animation:lmFadeIn .2s ease}
.lm-modal-content{background:var(--bg-card);border:1px solid var(--border-alt);border-radius:18px;max-width:560px;width:100%;max-height:90vh;overflow-y:auto;box-shadow:0 20px 40px rgba(0,0,0,.4);animation:lmScaleIn .2s ease}
.lm-modal-header{display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1px solid var(--border-subtle)}
.lm-modal-body{padding:22px;display:flex;flex-direction:column;gap:16px}
.lm-modal-row{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;font-size:13px;padding-bottom:10px;border-bottom:1px solid var(--border-subtle)}
.lm-modal-row:last-child{border-bottom:none;padding-bottom:0}
.lm-modal-lbl{color:var(--text-muted);font-weight:600;flex-shrink:0;width:140px}
.lm-modal-val{color:var(--text-primary);font-weight:600;text-align:right}
@keyframes lmFadeIn{from{opacity:0}to{opacity:1}}
@keyframes lmScaleIn{from{opacity:0;transform:scale(0.96)}to{opacity:1;transform:scale(1)}}
</style>

@if(session()->has('message'))
<div class="lm-alert-success">
    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('message') }}
</div>
@endif

@if(session()->has('error'))
<div class="lm-alert-danger">
    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    {{ session('error') }}
</div>
@endif

<div class="lm-card">
    {{-- Header --}}
    <div class="lm-header">
        <div style="display:flex;align-items:center;gap:12px">
            <div style="width:38px;height:38px;background:linear-gradient(135deg,#1d4ed8,#06b6d4);border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            </div>
            <div>
                <div class="lm-title">Daftar Peminjaman</div>
                <div style="font-size:12px;color:var(--text-muted);margin-top:1px">Semua transaksi peminjaman barang inventaris</div>
            </div>
            <span class="lm-count">{{ $borrowings->count() }} transaksi</span>
        </div>
        <div class="lm-search">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;color:var(--text-subtle);flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" placeholder="Cari no. pinjaman / peminjam / barang..." oninput="lmFilter(this.value)" id="lmSearch">
        </div>
    </div>

    {{-- Filter Bars --}}
    <div class="lm-filters-wrapper">
        {{-- Filter Tipe Peminjam --}}
        <div class="lm-filter-group">
            <span class="lm-filter-label">Tipe:</span>
            <button class="lm-filter-btn {{ $filterType === 'semua' ? 'act' : '' }}"
                wire:click="$set('filterType', 'semua')">
                Semua Tipe
                <span style="font-size:10px;opacity:.8">({{ $typeCounts['semua'] ?? 0 }})</span>
            </button>
            <button class="lm-filter-btn {{ $filterType === 'siswa' ? 'act-siswa' : '' }}"
                wire:click="$set('filterType', 'siswa')">
                <span style="width:6px;height:6px;border-radius:50%;background:#3b82f6"></span>
                Siswa
                <span style="font-size:10px;opacity:.8">({{ $typeCounts['siswa'] ?? 0 }})</span>
            </button>
            <button class="lm-filter-btn {{ $filterType === 'guru' ? 'act-guru' : '' }}"
                wire:click="$set('filterType', 'guru')">
                <span style="width:6px;height:6px;border-radius:50%;background:#10b981"></span>
                Guru
                <span style="font-size:10px;opacity:.8">({{ $typeCounts['guru'] ?? 0 }})</span>
            </button>
        </div>

        {{-- Filter Status Peminjaman --}}
        <div class="lm-filter-group">
            <span class="lm-filter-label">Status:</span>
            @php 
                $statuses = [
                    'semua'     => 'Semua',
                    'pending'   => 'Menunggu',
                    'approved'  => 'Disetujui',
                    'borrowed'  => 'Dipinjam',
                    'returned'  => 'Dikembalikan',
                    'overdue'   => 'Terlambat'
                ]; 
            @endphp
            @foreach($statuses as $val => $lbl)
            <button class="lm-filter-btn {{ $filterStatus === $val ? 'act' : '' }}"
                wire:click="$set('filterStatus', '{{ $val }}')">
                {{ $lbl }}
                <span style="font-size:10px;opacity:.75">({{ $statusCounts[$val] ?? 0 }})</span>
            </button>
            @endforeach
        </div>
    </div>

    {{-- Table --}}
    @if($borrowings->isEmpty())
    <div class="lm-empty">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:40px;height:40px;margin:0 auto 12px;color:var(--text-muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
        </svg>
        <div style="font-size:14px;color:var(--text-primary);font-weight:600">Tidak ada data peminjaman yang sesuai filter.</div>
        <div style="font-size:12px;color:var(--text-muted);margin-top:4px">Coba ubah opsi filter tipe atau status di atas.</div>
    </div>
    @else
    <div style="overflow-x:auto">
        <table class="lmt" id="lmTable">
            <thead>
                <tr>
                    <th>No. Peminjaman</th>
                    <th>Tipe</th>
                    <th>Peminjam</th>
                    <th>Barang</th>
                    <th>Jml</th>
                    <th>Tgl. Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                $badgeMap = [
                    'pending'   => 'badge-pending',
                    'approved'  => 'badge-approved',
                    'borrowed'  => 'badge-borrowed',
                    'returned'  => 'badge-returned',
                    'rejected'  => 'badge-rejected',
                    'overdue'   => 'badge-overdue',
                ];
                $labelMap = [
                    'pending'   => 'Menunggu',
                    'approved'  => 'Disetujui',
                    'borrowed'  => 'Dipinjam',
                    'returned'  => 'Dikembalikan',
                    'rejected'  => 'Ditolak',
                    'overdue'   => 'Terlambat',
                ];
                @endphp
                @foreach($borrowings as $b)
                @php
                    $isGuru = ($b->tipe_peminjam === 'guru');
                    $nomorPinjam = 'BR-' . str_pad($b->id, 4, '0', STR_PAD_LEFT);
                    $dueDate  = $b->return_date ? \Carbon\Carbon::parse($b->return_date) : null;
                    $dueClass = 'ok';
                    $dueText  = $dueDate ? $dueDate->format('d M Y') : '—';
                    if ($b->return_time) {
                        $dueText .= ' · ' . substr($b->return_time, 0, 5);
                    }
                    if ($dueDate) {
                        if ($dueDate->isPast() && !in_array($b->status, ['returned', 'rejected', 'cancelled'])) {
                            $dueClass = 'over';
                        } elseif ($dueDate->diffInDays(now()) <= 2 && !in_array($b->status, ['returned', 'rejected', 'cancelled'])) {
                            $dueClass = 'warn';
                        }
                    }
                    $borrowedAt = $b->borrowed_at ?? $b->borrow_date;
                @endphp
                <tr wire:key="b-{{ $b->id }}">
                    <td><span class="lmt-num">{{ $nomorPinjam }}</span></td>
                    
                    {{-- 1. Badge Tipe (Siswa / Guru) --}}
                    <td>
                        @if($isGuru)
                        <span class="badge-type-guru">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Guru
                        </span>
                        @else
                        <span class="badge-type-siswa">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                            Siswa
                        </span>
                        @endif
                    </td>

                    {{-- 2. Peminjam (Nama + Identitas) --}}
                    <td>
                        <div class="lmt-student">
                            <div class="lmt-avatar {{ $isGuru ? 'guru-avatar' : '' }}">
                                {{ strtoupper(substr($b->user?->name ?? '?', 0, 2)) }}
                            </div>
                            <div>
                                <div class="lmt-name">{{ $b->user?->name ?? '—' }}</div>
                                @if($isGuru)
                                <div class="lmt-sub">
                                    {{ $b->user?->nip ? 'NIP: '.$b->user->nip : 'Tenaga Pengajar' }}
                                </div>
                                @else
                                <div class="lmt-sub">
                                    NIS: {{ $b->user?->nis ?? '—' }}
                                    @if($b->user?->classroom?->name || $b->user?->kelas)
                                    · {{ $b->user?->classroom?->name ?? $b->user?->kelas }}
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- 3. Barang --}}
                    <td>
                        <div class="lmt-item">{{ $b->itemWithTrashed?->name ?? $b->item?->name ?? '—' }}</div>
                        @if($b->purpose)
                        <div style="font-size:11px;color:var(--text-subtle);margin-top:2px">{{ Str::limit($b->purpose, 35) }}</div>
                        @endif
                    </td>

                    {{-- 4. Kuantitas --}}
                    <td>
                        <span class="lmt-qty">{{ $b->quantity ?? 1 }}</span>
                    </td>

                    {{-- 5. Tanggal Pinjam --}}
                    <td>
                        <div class="lmt-date">{{ $borrowedAt ? \Carbon\Carbon::parse($borrowedAt)->format('d M Y') : '—' }}</div>
                    </td>

                    {{-- 6. Jatuh Tempo --}}
                    <td><div class="lmt-due {{ $dueClass }}">{{ $dueText }}</div></td>

                    {{-- 7. Status --}}
                    <td>
                        <span class="lm-badge {{ $badgeMap[$b->status] ?? 'badge-pending' }}">
                            <span style="width:5px;height:5px;border-radius:50%;background:currentColor"></span>
                            {{ $labelMap[$b->status] ?? ucfirst($b->status) }}
                        </span>
                    </td>

                    {{-- 8. Aksi --}}
                    <td>
                        <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap">
                            @if($isGuru)
                                {{-- Baris GURU: Approval khusus Kepala Jurusan, Admin HANYA read-only / Lihat Detail --}}
                                @if($b->status === 'pending')
                                <span class="lm-kajur-notice" title="Persetujuan dilakukan oleh Kepala Jurusan terkait">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Approval Kajur
                                </span>
                                @elseif($b->status === 'approved')
                                <span class="lm-kajur-notice" title="Disetujui oleh Kepala Jurusan">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Disetujui Kajur
                                </span>
                                @endif
                                <button wire:click="openDetail({{ $b->id }})" class="lm-act-btn btn-detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Lihat Detail
                                </button>
                            @else
                                {{-- Baris SISWA: Admin mengelola approval & status seperti biasa --}}
                                @if($b->status === 'pending')
                                <button wire:click="approve({{ $b->id }})" class="lm-act-btn btn-approve" title="Setujui Peminjaman Siswa">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Setujui
                                </button>
                                <button wire:click="reject({{ $b->id }})" class="lm-act-btn btn-reject" title="Tolak Peminjaman Siswa">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Tolak
                                </button>
                                @endif

                                @if($b->status === 'approved')
                                <button wire:click="markBorrowed({{ $b->id }})" class="lm-act-btn btn-borrowed" title="Tandai Barang Telah Diambil">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4"/></svg>
                                    Dipinjam
                                </button>
                                @endif

                                @if(in_array($b->status, ['borrowed', 'overdue']))
                                <button wire:click="markReturned({{ $b->id }})" class="lm-act-btn btn-return" title="Tandai Barang Telah Dikembalikan">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                    Kembalikan
                                </button>
                                @endif

                                <button wire:click="openDetail({{ $b->id }})" class="lm-act-btn btn-detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Detail
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

{{-- Modal Detail Peminjaman --}}
@if($showModal && $selectedBorrowing)
@php
    $modalIsGuru = ($selectedBorrowing->tipe_peminjam === 'guru');
    $modalNo = 'BR-' . str_pad($selectedBorrowing->id, 4, '0', STR_PAD_LEFT);
@endphp
<div class="lm-modal-backdrop" wire:click.self="closeModal">
    <div class="lm-modal-content">
        <div class="lm-modal-header">
            <div style="display:flex;align-items:center;gap:10px">
                <div style="font-size:16px;font-weight:800;color:var(--text-primary)">
                    Detail Peminjaman <span style="color:var(--blue);font-family:monospace">#{{ $modalNo }}</span>
                </div>
                @if($modalIsGuru)
                    <span class="badge-type-guru">Guru</span>
                @else
                    <span class="badge-type-siswa">Siswa</span>
                @endif
            </div>
            <button wire:click="closeModal" style="background:none;border:none;color:var(--text-muted);cursor:pointer;padding:4px;border-radius:6px">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="lm-modal-body">
            <div class="lm-modal-row">
                <div class="lm-modal-lbl">Status Peminjaman</div>
                <div class="lm-modal-val">
                    <span class="lm-badge {{ $badgeMap[$selectedBorrowing->status] ?? 'badge-pending' }}">
                        <span style="width:5px;height:5px;border-radius:50%;background:currentColor"></span>
                        {{ $labelMap[$selectedBorrowing->status] ?? ucfirst($selectedBorrowing->status) }}
                    </span>
                </div>
            </div>

            <div class="lm-modal-row">
                <div class="lm-modal-lbl">Nama Peminjam</div>
                <div class="lm-modal-val">
                    <div>{{ $selectedBorrowing->user?->name ?? '—' }}</div>
                    <div style="font-size:11px;color:var(--text-muted);font-weight:400">
                        {{ $modalIsGuru ? ($selectedBorrowing->user?->nip ? 'NIP: '.$selectedBorrowing->user->nip : 'Guru Pengajar') : ('NIS: '.($selectedBorrowing->user?->nis ?? '—').' · Kelas: '.($selectedBorrowing->user?->classroom?->name ?? $selectedBorrowing->user?->kelas ?? '—')) }}
                    </div>
                </div>
            </div>

            <div class="lm-modal-row">
                <div class="lm-modal-lbl">Barang Dipinjam</div>
                <div class="lm-modal-val">
                    <div>{{ $selectedBorrowing->itemWithTrashed?->name ?? $selectedBorrowing->item?->name ?? '—' }}</div>
                    <div style="font-size:11px;color:var(--text-muted);font-weight:400">
                        Jumlah: {{ $selectedBorrowing->quantity ?? 1 }} unit
                        @if($selectedBorrowing->itemWithTrashed?->category)
                        · Kategori: {{ $selectedBorrowing->itemWithTrashed->category->name }}
                        @endif
                    </div>
                </div>
            </div>

            <div class="lm-modal-row">
                <div class="lm-modal-lbl">Jadwal Pinjam</div>
                <div class="lm-modal-val">
                    <div>{{ $selectedBorrowing->borrow_date ? \Carbon\Carbon::parse($selectedBorrowing->borrow_date)->format('d M Y') : '—' }} &rarr; {{ $selectedBorrowing->return_date ? \Carbon\Carbon::parse($selectedBorrowing->return_date)->format('d M Y') : '—' }}</div>
                    @if($selectedBorrowing->return_time)
                    <div style="font-size:11px;color:var(--text-muted);font-weight:400">Jam Pengembalian: {{ substr($selectedBorrowing->return_time, 0, 5) }} WIB</div>
                    @endif
                </div>
            </div>

            <div class="lm-modal-row">
                <div class="lm-modal-lbl">Keperluan</div>
                <div class="lm-modal-val" style="font-weight:400;max-width:320px;text-align:right">
                    {{ $selectedBorrowing->purpose ?? '—' }}
                </div>
            </div>

            @if($selectedBorrowing->notes)
            <div class="lm-modal-row">
                <div class="lm-modal-lbl">Catatan Tambahan</div>
                <div class="lm-modal-val" style="font-weight:400;max-width:320px;text-align:right">
                    {{ $selectedBorrowing->notes }}
                </div>
            </div>
            @endif

            @if($modalIsGuru)
            <div class="lm-modal-row">
                <div class="lm-modal-lbl">Kepala Jurusan Tujuan</div>
                <div class="lm-modal-val">
                    {{ $selectedBorrowing->approvedByKajur?->name ?? 'Kepala Jurusan' }}
                </div>
            </div>
            @else
            @if($selectedBorrowing->teacher)
            <div class="lm-modal-row">
                <div class="lm-modal-lbl">Guru Pembimbing</div>
                <div class="lm-modal-val">
                    {{ $selectedBorrowing->teacher->name }}
                </div>
            </div>
            @endif
            @endif

            @if($selectedBorrowing->approved_at)
            <div class="lm-modal-row">
                <div class="lm-modal-lbl">Waktu Persetujuan</div>
                <div class="lm-modal-val" style="font-weight:400">
                    {{ \Carbon\Carbon::parse($selectedBorrowing->approved_at)->format('d M Y, H:i') }} WIB
                </div>
            </div>
            @endif

            @if($selectedBorrowing->qr_token)
            <div class="lm-modal-row">
                <div class="lm-modal-lbl">QR Token</div>
                <div class="lm-modal-val" style="font-family:monospace;font-size:12px;color:var(--blue)">
                    {{ $selectedBorrowing->qr_token }}
                </div>
            </div>
            @endif
        </div>
        <div style="padding:16px 22px;border-top:1px solid var(--border-subtle);display:flex;justify-content:flex-end">
            <button wire:click="closeModal" class="lm-filter-btn" style="background:var(--bg-hover);color:var(--text-primary)">
                Tutup
            </button>
        </div>
    </div>
</div>
@endif

<script>
function lmFilter(q) {
    document.querySelectorAll('#lmTable tbody tr').forEach(function(r){
        r.style.display = r.textContent.toLowerCase().includes(q.toLowerCase()) ? '' : 'none';
    });
}
</script>
</div>
