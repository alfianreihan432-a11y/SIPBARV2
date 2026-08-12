<div>
<style>
.lm-card{background:var(--bg-card);border:1px solid var(--border-alt);border-radius:18px;overflow:hidden;box-shadow:var(--card-shadow)}
.lm-header{display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1px solid var(--border-subtle);flex-wrap:wrap;gap:10px}
.lm-title{font-size:16px;font-weight:800;color:var(--text-primary)}
.lm-count{font-size:11px;background:rgba(29,78,216,.14);color:var(--blue);border:1px solid rgba(29,78,216,.2);padding:3px 10px;border-radius:999px;font-weight:700}
.lm-search{display:flex;align-items:center;gap:8px;background:var(--input-bg);border:1.5px solid var(--input-border);border-radius:10px;padding:7px 12px}
.lm-search input{background:none;border:none;outline:none;font-size:13px;color:var(--text-primary);width:200px}
.lm-search input::placeholder{color:var(--text-subtle)}
.lm-filter-row{display:flex;align-items:center;gap:8px;padding:12px 22px;border-bottom:1px solid var(--border-subtle);flex-wrap:wrap}
.lm-filter-btn{display:inline-flex;align-items:center;gap:5px;padding:5px 14px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;border:1px solid var(--border-subtle);background:var(--bg-card-subtle);color:var(--text-muted);transition:all .15s}
.lm-filter-btn:hover{background:var(--bg-hover);color:var(--text-primary)}
.lm-filter-btn.act{background:var(--blue-dark);color:#fff;border-color:var(--blue-dark)}
table.lmt{width:100%;border-collapse:collapse}
table.lmt thead th{padding:11px 18px;font-size:10px;font-weight:700;color:var(--text-subtle);letter-spacing:.08em;text-transform:uppercase;background:var(--table-head-bg);text-align:left;border-bottom:1px solid var(--border-subtle)}
table.lmt tbody tr{border-bottom:1px solid var(--border-subtle);transition:background .15s}
table.lmt tbody tr:last-child{border-bottom:none}
table.lmt tbody tr:hover{background:var(--table-hover)}
table.lmt tbody td{padding:13px 18px;font-size:13px;color:var(--text-secondary);vertical-align:middle}
.lmt-num{font-size:12px;font-weight:700;color:var(--text-primary);font-family:monospace}
.lmt-student{display:flex;align-items:center;gap:9px}
.lmt-avatar{width:32px;height:32px;border-radius:50%;background:var(--blue-dark);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;flex-shrink:0}
.lmt-name{font-size:13px;font-weight:600;color:var(--text-primary)}
.lmt-nis{font-size:11px;color:var(--text-muted);font-family:monospace}
.lmt-item{font-size:13px;color:var(--text-secondary);line-height:1.5}
.lmt-qty{display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;background:rgba(59,130,246,.12);color:var(--blue);font-weight:700;font-size:13px;border-radius:7px}
.lmt-date{font-size:12px;color:var(--text-muted)}
.lmt-due{font-size:12px}
.lmt-due.ok{color:#10b981}
.lmt-due.warn{color:#f59e0b}
.lmt-due.over{color:#f87171;font-weight:700}
.lm-badge{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:7px;font-size:11px;font-weight:700}
.badge-pending{background:rgba(245,158,11,.12);color:#f59e0b;border:1px solid rgba(245,158,11,.2)}
.badge-approved{background:rgba(59,130,246,.12);color:var(--blue);border:1px solid rgba(59,130,246,.2)}
.badge-borrowed{background:rgba(234,179,8,.12);color:#eab308;border:1px solid rgba(234,179,8,.2)}
.badge-returned{background:rgba(16,185,129,.12);color:#10b981;border:1px solid rgba(16,185,129,.2)}
.badge-rejected{background:rgba(239,68,68,.12);color:#f87171;border:1px solid rgba(239,68,68,.2)}
.badge-overdue{background:rgba(239,68,68,.15);color:#f87171;border:1px solid rgba(239,68,68,.25)}
.lm-act-btn{display:inline-flex;align-items:center;gap:5px;padding:5px 11px;border-radius:7px;font-size:11px;font-weight:700;cursor:pointer;border:none;transition:all .15s}
.btn-approve{background:rgba(16,185,129,.12);color:#10b981;border:1px solid rgba(16,185,129,.2)}
.btn-approve:hover{background:#10b981;color:#fff}
.btn-return{background:rgba(59,130,246,.12);color:var(--blue);border:1px solid rgba(59,130,246,.2)}
.btn-return:hover{background:var(--blue);color:#fff}
.lm-empty{padding:56px;text-align:center;color:var(--text-muted)}
.lm-alert{display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:11px;background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);color:#10b981;font-size:13px;font-weight:600;margin-bottom:16px;animation:lmIn .3s ease}
@keyframes lmIn{from{opacity:0;transform:translateY(-5px)}to{opacity:1;transform:translateY(0)}}
@media(max-width:800px){.lm-filter-row{gap:6px}}
</style>

@if(session()->has('message'))
<div class="lm-alert">
    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('message') }}
</div>
@endif

<div class="lm-card">
    {{-- Header --}}
    <div class="lm-header">
        <div style="display:flex;align-items:center;gap:12px">
            <div style="display:flex;align-items:center;gap:10px">
                <div style="width:38px;height:38px;background:linear-gradient(135deg,#1d4ed8,#06b6d4);border-radius:11px;display:flex;align-items:center;justify-content:center">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </div>
                <div>
                    <div class="lm-title">Daftar Peminjaman</div>
                    <div style="font-size:12px;color:#475569;margin-top:1px">Semua transaksi peminjaman barang inventaris</div>
                </div>
            </div>
            <span class="lm-count">{{ $borrowings->count() }} transaksi</span>
        </div>
        <div class="lm-search">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;color:#334155;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" placeholder="Cari no. pinjaman / siswa..." oninput="lmFilter(this.value)" id="lmSearch">
        </div>
    </div>

    {{-- Filter tabs --}}
    <div class="lm-filter-row">
        <span style="font-size:11px;color:#334155;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin-right:4px">Filter:</span>
        @php $statuses = ['semua'=>'Semua','pending'=>'Menunggu','approved'=>'Disetujui','borrowed'=>'Dipinjam','returned'=>'Dikembalikan','overdue'=>'Terlambat']; @endphp
        @foreach($statuses as $val => $lbl)
        <button class="lm-filter-btn {{ ($filterStatus??'semua')===$val?'act':'' }}"
            wire:click="$set('filterStatus','{{ $val }}')">
            {{ $lbl }}
            @if($val !== 'semua')
            <span style="font-size:10px;opacity:.7">({{ $borrowings->where('status',$val)->count() }})</span>
            @endif
        </button>
        @endforeach
    </div>

    {{-- Table --}}
    @if($borrowings->isEmpty())
    <div class="lm-empty">
        <div style="font-size:40px;margin-bottom:12px">📋</div>
        <div style="font-size:14px;color:#334155">Belum ada transaksi peminjaman.</div>
        <div style="font-size:12px;color:#1e293b;margin-top:6px">Siswa dapat mengajukan peminjaman melalui dashboard mereka.</div>
    </div>
    @else
    <div style="overflow-x:auto">
        <table class="lmt" id="lmTable">
            <thead>
                <tr>
                    <th>No. Peminjaman</th>
                    <th>Siswa</th>
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
                    'pending'  => 'badge-pending',
                    'approved' => 'badge-approved',
                    'borrowed' => 'badge-borrowed',
                    'returned' => 'badge-returned',
                    'rejected' => 'badge-rejected',
                    'overdue'  => 'badge-overdue',
                ];
                $labelMap = [
                    'pending'  => 'Menunggu',
                    'approved' => 'Disetujui',
                    'borrowed' => 'Dipinjam',
                    'returned' => 'Dikembalikan',
                    'rejected' => 'Ditolak',
                    'overdue'  => 'Terlambat',
                ];
                @endphp
                @foreach($borrowings as $b)
                @php
                    $dueDate   = $b->due_at ? \Carbon\Carbon::parse($b->due_at) : null;
                    $dueClass  = 'ok';
                    $dueText   = $dueDate ? $dueDate->format('d M Y') : '—';
                    if ($dueDate) {
                        if ($dueDate->isPast() && $b->status !== 'returned') $dueClass = 'over';
                        elseif ($dueDate->diffInDays(now()) <= 2 && $b->status !== 'returned') $dueClass = 'warn';
                    }
                @endphp
                <tr wire:key="b-{{ $b->id }}">
                    <td><span class="lmt-num">{{ $b->number }}</span></td>
                    <td>
                        <div class="lmt-student">
                            <div class="lmt-avatar">{{ strtoupper(substr($b->user?->name??'?',0,2)) }}</div>
                            <div>
                                <div class="lmt-name">{{ $b->user?->name ?? '—' }}</div>
                                <div class="lmt-nis">NIS: {{ $b->user?->nis ?? '—' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @foreach($b->details as $d)
                        <div class="lmt-item">{{ $d->item?->name ?? '—' }}</div>
                        @endforeach
                    </td>
                    <td>
                        @foreach($b->details as $d)
                        <span class="lmt-qty">{{ $d->quantity }}</span>
                        @endforeach
                    </td>
                    <td>
                        <div class="lmt-date">{{ $b->borrowed_at ? \Carbon\Carbon::parse($b->borrowed_at)->format('d M Y') : '—' }}</div>
                    </td>
                    <td><div class="lmt-due {{ $dueClass }}">{{ $dueText }}</div></td>
                    <td>
                        <span class="lm-badge {{ $badgeMap[$b->status] ?? 'badge-pending' }}">
                            <span style="width:5px;height:5px;border-radius:50%;background:currentColor"></span>
                            {{ $labelMap[$b->status] ?? ucfirst($b->status) }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            @if($b->status === 'pending')
                            <button wire:click="approve({{ $b->id }})" class="lm-act-btn btn-approve">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Setujui
                            </button>
                            @endif
                            @if(in_array($b->status, ['approved','borrowed']))
                            <button wire:click="markReturned({{ $b->id }})" class="lm-act-btn btn-return">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                Kembalikan
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

<script>
function lmFilter(q) {
    document.querySelectorAll('#lmTable tbody tr').forEach(function(r){
        r.style.display = r.textContent.toLowerCase().includes(q.toLowerCase()) ? '' : 'none';
    });
}
</script>
</div>
