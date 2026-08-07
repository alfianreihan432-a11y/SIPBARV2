<div>
    {{-- Greeting --}}
    <div class="greeting-row">
        <div>
            <div class="greeting-title">
                @php
                    $hour = now()->hour;
                    $greet = $hour < 12 ? 'Selamat Pagi' : ($hour < 17 ? 'Selamat Siang' : 'Selamat Malam');
                @endphp
                {{ $greet }}, {{ auth()->check() ? explode(' ', auth()->user()->name)[0] : 'Siswa' }} 👋
            </div>
            <div class="greeting-sub">{{ now()->translatedFormat('l, d F Y') }}</div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon-box" style="background:#1d4ed81a">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#1d4ed8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </div>
            <div>
                <div class="stat-num">{{ $totalBorrowed }}</div>
                <div class="stat-label">Sedang Dipinjam</div>
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
                <div class="stat-label">Sudah Dikembalikan</div>
                <div class="stat-change up">Real-time</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon-box" style="background:#f59e0b1a">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#f59e0b" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="stat-num">{{ $pendingRequests }}</div>
                <div class="stat-label">Menunggu Persetujuan</div>
                <div class="stat-change {{ $pendingRequests > 0 ? 'down' : 'up' }}">
                    {{ $pendingRequests > 0 ? 'Perlu Perhatian' : 'Aman' }}
                </div>
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
    <div class="panel">
        <div class="panel-header">
            <div>
                <div class="panel-title">Peminjaman Saya</div>
                <div style="font-size:11px;color:#475569;margin-top:2px">Riwayat peminjaman terbaru</div>
            </div>
            <button class="panel-more">··· </button>
        </div>

        @if($myRequests->count() > 0)
            @foreach($myRequests as $request)
            <div class="txn-item">
                <div class="txn-top">
                    <div>
                        <div class="txn-name">{{ $request->item->name }}</div>
                        <div class="txn-type">Peminjaman #{{ $request->id }}</div>
                    </div>
                    <span class="badge 
                        @if($request->status === 'pending') badge-maintenance
                        @elseif($request->status === 'approved' || $request->status === 'qr_ready') badge-available
                        @elseif($request->status === 'rejected') badge-borrowed
                        @elseif($request->status === 'borrowed') badge-borrowed
                        @else badge-available @endif">
                        {{ $request->status_label }}
                    </span>
                </div>
                <div class="txn-meta">
                    <div style="display:flex;align-items:center;gap:5px;color:#475569">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $request->created_at->diffForHumans() }}
                    </div>
                    <div style="font-size:11px;color:#64748b">
                        Jumlah: {{ $request->quantity }} | {{ $request->borrow_date->format('d M Y') }} - {{ $request->return_date->format('d M Y') }}
                    </div>
                </div>
                @if($request->status === 'qr_ready' && $request->qrCode)
                    <div style="margin-top:8px">
                        <button wire:click="showQRCode({{ $request->id }})" 
                                class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition">
                            Tampilkan QR Code
                        </button>
                    </div>
                @endif
            </div>
            @endforeach
        @else
            <div style="text-align:center;padding:40px;color:#64748b">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:48px;height:48px;margin-bottom:12px;color:#94a3b8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <div style="font-size:14px;font-weight:600">Belum ada peminjaman</div>
                <div style="font-size:12px;margin-top:4px">Mulai pinjam barang sekarang</div>
            </div>
        @endif
    </div>

    {{-- QR Code Modal --}}
    @if($showQRModal && $selectedQR)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">QR Code Peminjaman</h3>
                    <button wire:click="closeQRModal" class="text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="text-center">
                    @if($selectedQR->image_path)
                        <img src="{{ asset('storage/' . $selectedQR->image_path) }}" alt="QR Code" class="mx-auto mb-4">
                    @else
                        <div class="bg-gray-200 rounded-lg p-8 mb-4">
                            <p class="text-gray-500">QR Code Image</p>
                        </div>
                    @endif
                    <p class="text-sm text-gray-600 mb-2">Kode: {{ $selectedQR->code }}</p>
                    <p class="text-xs text-gray-500">Berlaku sampai: {{ $selectedQR->expires_at ? $selectedQR->expires_at->format('d M Y H:i') : 'Tidak terbatas' }}</p>
                    <p class="text-xs text-gray-500 mt-2">Tunjukkan QR Code ini kepada admin saat mengambil barang.</p>
                </div>
            </div>
        </div>
    @endif

    @session('success')
        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
            {{ session('success') }}
        </div>
    @endsession

    @session('error')
        <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700">
            {{ session('error') }}
        </div>
    @endsession
</div>
