@extends('layouts.siswa')

@section('title', 'Pengumuman')
@section('page-heading', 'Pengumuman')

@section('content')
<style>
    .ann-header {
        background: var(--card); border: 1px solid var(--border2);
        border-radius: 14px; padding: 24px; margin-bottom: 20px;
    }
    .ann-header-content { display: flex; justify-content: space-between; align-items: center; }
    .ann-title { font-size: 20px; font-weight: 700; color: var(--text); margin-bottom: 4px; }
    .ann-sub { font-size: 13px; color: var(--muted); }
    .ann-icon {
        width: 40px; height: 40px; background: var(--bg3);
        border-radius: 10px; display: flex; align-items: center; justify-content: center;
    }

    .ann-panel {
        background: var(--card); border: 1px solid var(--border2);
        border-radius: 14px; overflow: hidden; margin-bottom: 20px;
    }
    .ann-panel-header {
        padding: 20px; border-bottom: 1px solid var(--border2);
    }
    .ann-panel-header-content { display: flex; align-items: center; gap: 12px; }
    .ann-panel-title { font-size: 16px; font-weight: 700; color: var(--text); }
    .ann-panel-sub { font-size: 13px; color: var(--muted); margin-top: 2px; }

    .ann-panel-body { padding: 20px; }
    .ann-item {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px; background: var(--bg3); border: 1px solid var(--border2);
        border-radius: 10px; margin-bottom: 12px;
    }
    .ann-item:last-child { margin-bottom: 0; }
    .ann-item-left { display: flex; align-items: center; gap: 12px; }
    .ann-item-icon {
        width: 36px; height: 36px; background: var(--bg2);
        border-radius: 8px; display: flex; align-items: center; justify-content: center;
    }
    .ann-item-name { font-size: 14px; font-weight: 700; color: var(--text); }
    .ann-item-meta { font-size: 13px; color: var(--muted); margin-top: 4px; }

    .ann-badge {
        padding: 4px 12px; border-radius: 6px; font-size: 11px; font-weight: 600;
    }
    .ann-badge-danger { background: #dc2626; color: #fff; }
    .ann-badge-warning { background: var(--bg2); color: var(--text); border: 1px solid var(--border2); }
    .ann-badge-primary { background: var(--primary); color: #fff; }

    .ann-card {
        padding: 16px; background: var(--bg3); border: 1px solid var(--border2);
        border-radius: 10px; margin-bottom: 12px;
    }
    .ann-card:last-child { margin-bottom: 0; }
    .ann-card-title { font-size: 14px; font-weight: 700; color: var(--text); margin-bottom: 8px; }
    .ann-card-text { font-size: 13px; color: var(--muted); line-height: 1.5; }
    .ann-card-date { font-size: 12px; color: var(--subtle); margin-top: 8px; }

    .ann-rejection {
        background: var(--card); padding: 12px; border-radius: 8px;
        border: 1px solid var(--border2); margin-top: 8px;
    }
    .ann-rejection-text { font-size: 13px; color: var(--text); }

    .ann-btn {
        padding: 8px 16px; background: var(--primary); color: #fff;
        border: none; border-radius: 8px; font-size: 13px; font-weight: 600;
        cursor: pointer; text-decoration: none; display: inline-block;
    }
    .ann-btn:hover { background: var(--primary-dark); }

    .ann-empty {
        padding: 48px 20px; text-align: center;
    }
    .ann-empty-icon { width: 80px; height: 80px; margin: 0 auto 16px; color: var(--subtle); }
    .ann-empty-title { font-size: 18px; font-weight: 600; color: var(--text); margin-bottom: 8px; }
    .ann-empty-sub { font-size: 14px; color: var(--muted); margin-bottom: 16px; }
</style>

<div>
    {{-- Page Header --}}
    <div class="ann-header">
        <div class="ann-header-content">
            <div>
                <div class="ann-title">Pengumuman & Notifikasi</div>
                <div class="ann-sub">Informasi penting terkait peminjaman Anda</div>
            </div>
            <div class="ann-icon">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:var(--muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </div>
        </div>
    </div>

    @php
        $overdueBorrowings = \App\Models\BorrowingRequest::with('item')
            ->where('user_id', auth()->id())
            ->where('status', 'borrowed')
            ->whereDate('return_date', '<', now())
            ->get();

        $dueSoonBorrowings = \App\Models\BorrowingRequest::with('item')
            ->where('user_id', auth()->id())
            ->where('status', 'borrowed')
            ->whereDate('return_date', '>=', now())
            ->whereDate('return_date', '<=', now()->addDays(2))
            ->get();

        $recentApprovals = \App\Models\BorrowingRequest::with('item')
            ->where('user_id', auth()->id())
            ->where('status', 'approved')
            ->latest('approved_at')
            ->take(3)
            ->get();

        $recentRejections = \App\Models\BorrowingRequest::with('item')
            ->where('user_id', auth()->id())
            ->where('status', 'rejected')
            ->latest('updated_at')
            ->take(3)
            ->get();
    @endphp

    {{-- Critical: Overdue Items --}}
    @if($overdueBorrowings->isNotEmpty())
    <div class="ann-panel" style="border-color:rgba(239,68,68,.3)">
        <div class="ann-panel-header" style="border-color:rgba(239,68,68,.3)">
            <div class="ann-panel-header-content">
                <div style="width:36px;height:36px;background:#dc2626;border-radius:8px;display:flex;align-items:center;justify-content:center">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <div class="ann-panel-title">Peminjaman Terlambat</div>
                    <div class="ann-panel-sub">Segera kembalikan barang berikut untuk menghindari sanksi</div>
                </div>
            </div>
        </div>
        <div class="ann-panel-body">
            @foreach($overdueBorrowings as $overdue)
            @php
                $daysOverdue = now()->diffInDays(\Carbon\Carbon::parse($overdue->return_date));
            @endphp
            <div class="ann-item">
                <div class="ann-item-left">
                    <div class="ann-item-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:var(--muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div>
                        <div class="ann-item-name">{{ $overdue->item?->name ?? 'Barang tidak tersedia' }}</div>
                        <div class="ann-item-meta">
                            Seharusnya dikembalikan: {{ \Carbon\Carbon::parse($overdue->return_date)->format('d M Y') }}
                        </div>
                    </div>
                </div>
                <span class="ann-badge ann-badge-danger">Terlambat {{ $daysOverdue }} hari</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Warning: Due Soon --}}
    @if($dueSoonBorrowings->isNotEmpty())
    <div class="ann-panel">
        <div class="ann-panel-header">
            <div class="ann-panel-header-content">
                <div class="ann-item-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:var(--muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <div class="ann-panel-title">Pengingat Pengembalian</div>
                    <div class="ann-panel-sub">Barang berikut harus segera dikembalikan</div>
                </div>
            </div>
        </div>
        <div class="ann-panel-body">
            @foreach($dueSoonBorrowings as $dueSoon)
            @php
                $daysLeft = \Carbon\Carbon::parse($dueSoon->return_date)->diffInDays(now());
            @endphp
            <div class="ann-item">
                <div class="ann-item-left">
                    <div class="ann-item-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:var(--muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div>
                        <div class="ann-item-name">{{ $dueSoon->item?->name ?? 'Barang tidak tersedia' }}</div>
                        <div class="ann-item-meta">
                            Harus dikembalikan: {{ \Carbon\Carbon::parse($dueSoon->return_date)->format('d M Y') }}
                        </div>
                    </div>
                </div>
                <span class="ann-badge ann-badge-warning">{{ $daysLeft }} hari lagi</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Recent Approvals --}}
    @if($recentApprovals->isNotEmpty())
    <div class="ann-panel">
        <div class="ann-panel-header">
            <div class="ann-panel-title">Peminjaman Disetujui</div>
            <div class="ann-panel-sub">Barang siap diambil dari ruang inventaris</div>
        </div>
        <div class="ann-panel-body">
            @foreach($recentApprovals as $approval)
            <div class="ann-item">
                <div class="ann-item-left">
                    <div>
                        <div class="ann-item-name">{{ $approval->item?->name ?? 'Barang tidak tersedia' }}</div>
                        <div class="ann-item-meta">
                            Disetujui {{ $approval->approved_at ? $approval->approved_at->diffForHumans() : 'baru saja' }}
                        </div>
                    </div>
                </div>
                <a href="{{ route('student.loans') }}" class="ann-btn">Lihat QR Code</a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Recent Rejections --}}
    @if($recentRejections->isNotEmpty())
    <div class="ann-panel">
        <div class="ann-panel-header">
            <div class="ann-panel-title">Peminjaman Ditolak</div>
            <div class="ann-panel-sub">Informasi pengajuan yang tidak disetujui</div>
        </div>
        <div class="ann-panel-body">
            @foreach($recentRejections as $rejection)
            <div class="ann-card">
                <div class="ann-card-title">{{ $rejection->item?->name ?? 'Barang tidak tersedia' }}</div>
                <div class="ann-card-meta" style="font-size:13px;color:var(--muted);margin-bottom:8px">
                    Ditolak {{ $rejection->updated_at->diffForHumans() }}
                </div>
                @if($rejection->rejection_reason)
                <div class="ann-rejection">
                    <div class="ann-rejection-text"><strong>Alasan:</strong> {{ $rejection->rejection_reason }}</div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- System Announcements --}}
    <div class="ann-panel">
        <div class="ann-panel-header">
            <div class="ann-panel-title">Pengumuman Sistem</div>
            <div class="ann-panel-sub">Informasi penting tentang sistem SIPBAR</div>
        </div>
        <div class="ann-panel-body">
            <div class="ann-card">
                <div class="ann-card-title">Cara Menggunakan QR Code</div>
                <div class="ann-card-text">
                    Setelah peminjaman disetujui, QR Code akan muncul di halaman "Peminjaman". Tunjukkan QR Code kepada admin untuk mengambil dan mengembalikan barang.
                </div>
                <div class="ann-card-date">Dipublikasikan: Hari ini</div>
            </div>

            <div class="ann-card">
                <div class="ann-card-title">Kebijakan Peminjaman</div>
                <div class="ann-card-text">
                    Pastikan mengembalikan barang tepat waktu. Keterlambatan akan mempengaruhi riwayat peminjaman Anda dan dapat berakibat pada sanksi.
                </div>
                <div class="ann-card-date">Dipublikasikan: 2 hari lalu</div>
            </div>

            <div class="ann-card">
                <div class="ann-card-title">Perawatan Barang</div>
                <div class="ann-card-text">
                    Jaga kondisi barang yang dipinjam. Laporkan segera jika terjadi kerusakan. Pengembalian dalam kondisi baik adalah tanggung jawab peminjam.
                </div>
                <div class="ann-card-date">Dipublikasikan: 1 minggu lalu</div>
            </div>
        </div>
    </div>

    {{-- Empty State --}}
    @if($overdueBorrowings->isEmpty() && $dueSoonBorrowings->isEmpty() && $recentApprovals->isEmpty() && $recentRejections->isEmpty())
    <div class="ann-empty">
        <svg xmlns="http://www.w3.org/2000/svg" class="ann-empty-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="ann-empty-title">Semua Lancar!</div>
        <div class="ann-empty-sub">Tidak ada pengumuman penting saat ini. Peminjaman Anda dalam kondisi baik.</div>
        <a href="{{ route('student.catalog') }}" class="ann-btn">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;margin-right:8px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Lihat Katalog Barang
        </a>
    </div>
    @endif
</div>
@endsection
