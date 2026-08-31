<div>
    {{-- ══ Greeting Row ══ --}}
    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px;gap:16px;flex-wrap:wrap">
        <div>
            @php
                $hour = now()->hour;
                $greet = $hour < 12 ? 'Selamat Pagi' : ($hour < 17 ? 'Selamat Siang' : 'Selamat Malam');
            @endphp
            <div style="font-family:var(--font-head);font-size:24px;font-weight:800;color:var(--text);letter-spacing:-.02em;line-height:1.2">
                {{ $greet }}, {{ auth()->check() ? explode(' ', auth()->user()->name)[0] : 'Siswa' }} 👋
            </div>
            <div style="font-size:13px;color:var(--muted);margin-top:5px">
                {{ now()->translatedFormat('l, d F Y') }}
            </div>
        </div>
        <a href="{{ route('student.catalog') }}" class="s-btn s-btn--primary">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Pinjam Barang
        </a>
    </div>

    {{-- ══ Stat Cards ══ --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px" class="db-stat-grid">
        {{-- Sedang Dipinjam --}}
        <div class="s-stat">
            <div class="s-stat-icon" style="background:rgba(8,145,178,.12)">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#0891b2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            </div>
            <div class="s-stat-body">
                <div class="s-stat-num">{{ $totalBorrowed }}</div>
                <div class="s-stat-label">Sedang Dipinjam</div>
                @if($totalBorrowed > 0)
                <span class="s-stat-pill" style="background:var(--s-borrowed-bg);color:var(--s-borrowed)">Aktif</span>
                @endif
            </div>
        </div>
        {{-- Menunggu --}}
        <div class="s-stat">
            <div class="s-stat-icon" style="background:rgba(217,119,6,.1)">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#d97706" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="s-stat-body">
                <div class="s-stat-num">{{ $pendingRequests }}</div>
                <div class="s-stat-label">Menunggu Persetujuan</div>
                @if($pendingRequests > 0)
                <span class="s-stat-pill" style="background:var(--s-pending-bg);color:var(--s-pending)">Proses</span>
                @endif
            </div>
        </div>
        {{-- Dikembalikan --}}
        <div class="s-stat">
            <div class="s-stat-icon" style="background:rgba(5,150,105,.1)">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#059669" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="s-stat-body">
                <div class="s-stat-num">{{ $totalReturned }}</div>
                <div class="s-stat-label">Sudah Dikembalikan</div>
            </div>
        </div>
        {{-- Barang Tersedia --}}
        <div class="s-stat">
            <div class="s-stat-icon" style="background:rgba(37,99,235,.1)">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:var(--primary)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div class="s-stat-body">
                <div class="s-stat-num">{{ $availableItems }}</div>
                <div class="s-stat-label">Barang Tersedia</div>
            </div>
        </div>
    </div>
    <style>
        @media(max-width:1024px){.db-stat-grid{grid-template-columns:repeat(2,1fr)!important}}
        @media(max-width:640px){.db-stat-grid{grid-template-columns:1fr!important}}
    </style>

    {{-- ══ Peminjaman Saya ══ --}}
    <div class="s-card">
        <div class="s-card-header">
            <div>
                <div class="s-card-title">Peminjaman Saya</div>
                <div class="s-card-sub">Riwayat peminjaman terbaru</div>
            </div>
            <a href="{{ route('student.loans') }}" class="s-card-action">Lihat semua →</a>
        </div>

        @if($myRequests->count() > 0)
            @foreach($myRequests as $request)
            @php
                $statusMap = [
                    'pending'   => ['label'=>'Menunggu',    'cls'=>'s-badge--pending',  'row'=>'s-loan-row--pending',  'dot'=>'var(--s-pending)'],
                    'cancelled' => ['label'=>'Dibatalkan',  'cls'=>'s-badge--returned', 'row'=>'s-loan-row--returned', 'dot'=>'var(--s-returned)'],
                    'approved'  => ['label'=>'Disetujui',   'cls'=>'s-badge--approved', 'row'=>'s-loan-row--approved', 'dot'=>'var(--s-approved)'],
                    'qr_ready'  => ['label'=>'Siap Ambil',  'cls'=>'s-badge--approved', 'row'=>'s-loan-row--approved', 'dot'=>'var(--s-approved)'],
                    'borrowed'  => ['label'=>'Dipinjam',    'cls'=>'s-badge--borrowed', 'row'=>'s-loan-row--borrowed', 'dot'=>'var(--s-borrowed)'],
                    'returned'  => ['label'=>'Dikembalikan','cls'=>'s-badge--returned', 'row'=>'s-loan-row--returned', 'dot'=>'var(--s-returned)'],
                    'rejected'  => ['label'=>'Ditolak',     'cls'=>'s-badge--rejected', 'row'=>'s-loan-row--rejected', 'dot'=>'var(--s-rejected)'],
                ];
                $st = $statusMap[$request->status] ?? $statusMap['pending'];
            @endphp
            <div class="s-loan-row {{ $st['row'] }}">
                <div class="s-loan-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:var(--muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div class="s-loan-content">
                    <div class="s-loan-name">{{ $request->item?->name ?? 'Barang tidak tersedia' }}</div>
                    <div class="s-loan-code">Peminjaman #{{ $request->id }} · Qty: {{ $request->quantity }}</div>
                    <div class="s-loan-meta">
                        <div class="s-loan-meta-item">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $request->borrow_date->format('d M Y') }} – {{ $request->return_date->format('d M Y') }}
                        </div>
                    </div>
                    @if($request->status === 'pending')
                        @php
                            $shareLink = $request->teacher && $request->teacher->phone
                                ? app(\App\Services\WhatsAppNotificationService::class)->getDirectWaLink($request)
                                : null;
                        @endphp
                        <div style="margin-top:10px;display:flex;gap:8px;flex-wrap:wrap">
                            <a href="{{ route('student.loans.edit', $request->id) }}" class="s-btn s-btn--sm s-btn--ghost">
                                Edit
                            </a>
                            @if($shareLink)
                                <a href="{{ $shareLink }}" target="_blank" rel="noopener" class="s-btn s-btn--sm s-btn--primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l.7-3.305A7.93 7.93 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    Kirim ke WA Guru
                                </a>
                                <button type="button" onclick="navigator.clipboard.writeText('{{ $shareLink }}'); this.textContent='Tersalin';" class="s-btn s-btn--sm s-btn--ghost">
                                    Salin Link
                                </button>
                            @endif
                            <form method="POST" action="{{ route('student.loans.cancel', $request->id) }}" onsubmit="return confirm('Yakin ingin membatalkan peminjaman ini?')" style="display:inline;">
                                @csrf
                                <button type="submit" class="s-btn s-btn--sm s-btn--danger">
                                    Batalkan
                                </button>
                            </form>
                        </div>
                    @elseif($request->status === 'qr_ready' && $request->qrCode)
                    <div style="margin-top:10px">
                        <button wire:click="showQRCode({{ $request->id }})" class="s-btn s-btn--sm s-btn--ghost">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            Tampilkan QR Code
                        </button>
                    </div>
                    @endif
                </div>
                <div class="s-loan-right">
                    <span class="s-badge {{ $st['cls'] }}">
                        <span class="s-badge-dot" style="background:{{ $st['dot'] }}"></span>
                        {{ $st['label'] }}
                    </span>
                    <span class="s-loan-time">{{ $request->created_at->diffForHumans() }}</span>
                </div>
            </div>
            @endforeach
        @else
            <div class="s-empty">
                <div class="s-empty-icon-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:32px;height:32px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div class="s-empty-title">Belum ada peminjaman</div>
                <div class="s-empty-sub">Kunjungi katalog untuk menemukan barang yang ingin kamu pinjam</div>
                <a href="{{ route('student.catalog') }}" class="s-btn s-btn--primary">Lihat Katalog Barang</a>
            </div>
        @endif
    </div>

    {{-- ══ QR Code Modal ══ --}}
    @if($showQRModal && $selectedQR)
    <div style="position:fixed;inset:0;background:rgba(0,0,0,.6);display:flex;align-items:center;justify-content:center;z-index:50;padding:16px;backdrop-filter:blur(4px)" wire:click="closeQRModal">
        <div style="background:var(--card);border-radius:20px;padding:28px;max-width:380px;width:100%;box-shadow:0 24px 48px rgba(0,0,0,.3)" wire:click.stop>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
                <div>
                    <div style="font-family:var(--font-head);font-size:16px;font-weight:800;color:var(--text)">QR Code Peminjaman</div>
                    <div style="font-size:12px;color:var(--muted);margin-top:2px">Tunjukkan kepada admin saat ambil barang</div>
                </div>
                <button wire:click="closeQRModal" style="width:32px;height:32px;border-radius:8px;background:var(--bg3);border:1px solid var(--border2);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--muted)">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div style="text-align:center">
                @if($selectedQR->image_path)
                    <img src="{{ asset('storage/' . $selectedQR->image_path) }}" alt="QR Code" style="border-radius:14px;max-width:100%;margin-bottom:16px;border:3px solid var(--border2)">
                @else
                    <div style="background:var(--bg3);border-radius:14px;padding:48px;margin-bottom:16px;border:1px solid var(--border2)">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:48px;height:48px;color:var(--subtle);margin:0 auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    </div>
                @endif
                <div style="font-family:monospace;font-size:15px;font-weight:800;color:var(--text);letter-spacing:.05em;margin-bottom:8px">{{ $selectedQR->code }}</div>
                <div style="font-size:12px;color:var(--muted);margin-bottom:14px">Berlaku sampai: {{ $selectedQR->expires_at ? $selectedQR->expires_at->format('d M Y H:i') : 'Tidak terbatas' }}</div>
                <div style="font-size:12px;color:var(--muted);padding:12px 16px;background:var(--primary-light);border:1px solid var(--primary-muted);border-radius:10px;text-align:left">
                    Tunjukkan QR Code ini kepada admin atau guru pembimbing saat mengambil barang dari gudang.
                </div>
            </div>
        </div>
    </div>
    @endif

    @session('success')
    <div style="margin-top:16px;padding:14px 18px;background:var(--s-returned-bg);border:1px solid var(--s-returned-bdr);color:var(--s-returned);border-radius:12px;font-size:13px;font-weight:600">
        ✓ {{ session('success') }}
    </div>
    @endsession
    @session('error')
    <div style="margin-top:16px;padding:14px 18px;background:var(--s-rejected-bg);border:1px solid var(--s-rejected-bdr);color:var(--s-rejected);border-radius:12px;font-size:13px;font-weight:600">
        ✕ {{ session('error') }}
    </div>
    @endsession
</div>
