<div>
    {{-- Greeting --}}
    <div class="greeting-row">
        <div>
            <div class="greeting-title">
                @php
                    $hour = now()->hour;
                    $greet = $hour < 12 ? 'Selamat Pagi' : ($hour < 17 ? 'Selamat Siang' : 'Selamat Malam');
                @endphp
                {{ $greet }}, {{ auth()->check() ? explode(' ', auth()->user()->name)[0] : 'Guru' }} 👋
            </div>
            <div class="greeting-sub">{{ now()->translatedFormat('l, d F Y') }}</div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon-box" style="background:#0f766e1a">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#0f766e" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div>
                <div class="stat-num">{{ $departmentItems }}</div>
                <div class="stat-label">Total Barang</div>
                <div class="stat-change up">Real-time</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon-box" style="background:#0891b21a">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#0891b2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </div>
            <div>
                <div class="stat-num">{{ $totalBorrowed }}</div>
                <div class="stat-label">Peminjaman Saya</div>
                <div class="stat-change up">Real-time</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon-box" style="background:#0596691a">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#059669" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="stat-num">{{ $totalReturned }}</div>
                <div class="stat-label">Dikembalikan</div>
                <div class="stat-change up">Real-time</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon-box" style="background:#7c3aed1a">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#7c3aed" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div>
                <div class="stat-num">{{ $availableItems }}</div>
                <div class="stat-label">Barang Tersedia</div>
                <div class="stat-change up">Real-time</div>
            </div>
        </div>
    </div>

    {{-- Peminjaman Saya --}}
    <div class="panel" style="margin-bottom:16px">
        <div class="panel-header">
            <div>
                <div class="panel-title">Peminjaman Saya</div>
                <div style="font-size:11px;color:#475569;margin-top:2px">Riwayat peminjaman terbaru</div>
            </div>
            <button class="panel-more">··· </button>
        </div>

        @if($myBorrowings->count() > 0)
            @foreach($myBorrowings as $borrowing)
            <div class="txn-item">
                <div class="txn-top">
                    <div>
                        <div class="txn-name">{{ $borrowing->details->first()?->item->name ?? 'Unknown Item' }}</div>
                        <div class="txn-type">Peminjaman #{{ $borrowing->number ?? $borrowing->id }}</div>
                    </div>
                    <span class="badge {{ $borrowing->status === 'borrowed' ? 'badge-borrowed' : ($borrowing->status === 'returned' ? 'badge-available' : 'badge-maintenance') }}">
                        {{ ucfirst($borrowing->status) }}
                    </span>
                </div>
                <div class="txn-meta">
                    <div style="display:flex;align-items:center;gap:5px;color:#475569">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $borrowing->borrowed_at?->diffForHumans() ?? 'Just now' }}
                    </div>
                    <div style="font-size:11px;color:#64748b">
                        @if($borrowing->return_date)
                            Deadline: {{ $borrowing->return_date->format('d M Y') }}
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div style="text-align:center;padding:40px;color:#64748b">
                <div style="font-size:14px;font-weight:600">Belum ada peminjaman</div>
                <div style="font-size:12px;margin-top:4px">Mulai pinjam barang sekarang</div>
            </div>
        @endif
    </div>

    {{-- Peminjaman Sekolah --}}
    <div class="panel">
        <div class="panel-header">
            <div>
                <div class="panel-title">Peminjaman Sekolah</div>
                <div style="font-size:11px;color:#475569;margin-top:2px">Aktivitas peminjaman terbaru</div>
            </div>
            <button class="panel-more">··· </button>
        </div>

        @if($recentDepartmentBorrowings->count() > 0)
            @foreach($recentDepartmentBorrowings as $borrowing)
            <div class="txn-item">
                <div class="txn-top">
                    <div>
                        <div class="txn-name">{{ $borrowing->details->first()?->item->name ?? 'Unknown Item' }}</div>
                        <div class="txn-type">Oleh {{ $borrowing->user->name ?? 'Unknown User' }}</div>
                    </div>
                    <span class="badge {{ $borrowing->status === 'borrowed' ? 'badge-borrowed' : ($borrowing->status === 'returned' ? 'badge-available' : 'badge-maintenance') }}">
                        {{ ucfirst($borrowing->status) }}
                    </span>
                </div>
                <div class="txn-meta">
                    <div style="display:flex;align-items:center;gap:5px;color:#475569">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $borrowing->borrowed_at?->diffForHumans() ?? 'Just now' }}
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div style="text-align:center;padding:40px;color:#64748b">
                <div style="font-size:14px;font-weight:600">Belum ada aktivitas</div>
                <div style="font-size:12px;margin-top:4px">Menunggu peminjaman baru</div>
            </div>
        @endif
    </div>
</div>
