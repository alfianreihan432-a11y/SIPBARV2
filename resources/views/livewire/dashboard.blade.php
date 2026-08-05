<div>
    {{-- Greeting + Gauge --}}
    <div class="greeting-row">
        <div>
            <div class="greeting-title">
                @php
                    $hour = now()->hour;
                    $greet = $hour < 12 ? 'Selamat Pagi' : ($hour < 17 ? 'Selamat Siang' : 'Selamat Malam');
                @endphp
                {{ $greet }}, {{ auth()->check() ? explode(' ', auth()->user()->name)[0] : 'Admin' }} 👋
            </div>
            <div class="greeting-sub">{{ now()->translatedFormat('l, d F Y') }}</div>
        </div>
        <div class="gauge-card">
            <div class="gauge-title">Kondisi Barang Baik</div>
            <svg class="gauge-svg" viewBox="0 0 120 70">
                <defs>
                    <linearGradient id="gaugeGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#1d4ed8"/>
                        <stop offset="100%" stop-color="#06b6d4"/>
                    </linearGradient>
                </defs>
                <path d="M10,65 A50,50 0 0,1 110,65" fill="none" stroke="#1e293b" stroke-width="10" stroke-linecap="round"/>
                <path d="M10,65 A50,50 0 0,1 110,65" fill="none" stroke="url(#gaugeGrad)" stroke-width="10" stroke-linecap="round" stroke-dasharray="157" stroke-dashoffset="{{ 157 - (157 * $itemConditionPercentage / 100) }}"/>
                <text x="60" y="58" text-anchor="middle" font-size="16" font-weight="800" fill="#f1f5f9">{{ $itemConditionPercentage }}%</text>
                <text x="12" y="70" font-size="7" fill="#475569">0</text>
                <text x="104" y="70" font-size="7" fill="#475569">100</text>
            </svg>
            <div class="gauge-label">Kondisi Barang Baik</div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon-box" style="background:#1d4ed81a">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#1d4ed8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div>
                <div class="stat-num">{{ $totalItems }}</div>
                <div class="stat-label">Total Barang</div>
                <div class="stat-change up">Total keseluruhan</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon-box" style="background:#0891b21a">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#0891b2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </div>
            <div>
                <div class="stat-num">{{ $borrowedItems }}</div>
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
                <div class="stat-num">{{ $availableItems }}</div>
                <div class="stat-label">Barang Tersedia</div>
                <div class="stat-change up">Real-time</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon-box" style="background:#7c3aed1a">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#7c3aed" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <div>
                <div class="stat-num">{{ $totalCategories }}</div>
                <div class="stat-label">Kategori</div>
                <div class="stat-change" style="color:#475569">Total keseluruhan</div>
            </div>
        </div>
    </div>

    {{-- Bottom Grid: Transaksi --}}
    <div class="bottom-grid">
        {{-- Transaksi Terbaru --}}
        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Transaksi Terbaru</div>
                    <div style="font-size:11px;color:#475569;margin-top:2px">Peminjaman terbaru</div>
                </div>
                <button class="panel-more">··· </button>
            </div>

            @foreach($recentBorrowings as $borrowing)
            <div class="txn-item">
                <div class="txn-top">
                    <div>
                        <div class="txn-name">{{ $borrowing->details->first()?->item->name ?? 'Unknown Item' }}</div>
                        <div class="txn-type">Peminjaman #{{ $borrowing->number ?? $borrowing->id }}</div>
                    </div>
                    <div class="txn-link-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </div>
                </div>
                <div class="txn-meta">
                    <div style="display:flex;align-items:center;gap:5px;color:#475569">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $borrowing->borrowed_at?->diffForHumans() ?? 'Just now' }}
                    </div>
                    <div class="txn-user">
                        <div class="txn-avatar">{{ strtoupper(substr($borrowing->user->name ?? 'U', 0, 1)) }}</div>
                        {{ $borrowing->user->name ?? 'Unknown User' }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Chart Panel --}}
        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Statistik Barang</div>
                    <div style="font-size:11px;color:#475569;margin-top:2px">Overview inventaris</div>
                </div>
                <button class="panel-more">··· </button>
            </div>
            <div class="chart-big-num">{{ $totalItems }}</div>
            <div class="chart-big-label">Total Barang</div>
            <svg class="chart-svg" viewBox="0 0 300 110">
                <defs>
                    <linearGradient id="chartGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#1d4ed8" stop-opacity="0.3"/>
                        <stop offset="100%" stop-color="#1d4ed8" stop-opacity="0"/>
                    </linearGradient>
                </defs>
                <path d="M0,110 L0,60 Q30,50 60,55 T120,45 T180,50 T240,40 T300,45 L300,110 Z" fill="url(#chartGrad)"/>
                <path d="M0,60 Q30,50 60,55 T120,45 T180,50 T240,40 T300,45" fill="none" stroke="#1d4ed8" stroke-width="2"/>
            </svg>
        </div>

        {{-- Status Bars --}}
        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Status Barang</div>
                    <div style="font-size:11px;color:#475569;margin-top:2px">Distribusi status</div>
                </div>
                <button class="panel-more">··· </button>
            </div>
            <div class="status-grid">
                <div class="status-bar-item">
                    <div class="bar-wrap">
                        <div class="bar" style="height:{{ $totalItems > 0 ? ($availableItems / $totalItems * 70) : 0 }}px;background:#059669"></div>
                    </div>
                    <div class="bar-val">{{ $availableItems }}</div>
                    <div class="bar-label">Tersedia</div>
                </div>
                <div class="status-bar-item">
                    <div class="bar-wrap">
                        <div class="bar" style="height:{{ $totalItems > 0 ? ($borrowedItems / $totalItems * 70) : 0 }}px;background:#f87171"></div>
                    </div>
                    <div class="bar-val">{{ $borrowedItems }}</div>
                    <div class="bar-label">Dipinjam</div>
                </div>
                <div class="status-bar-item">
                    <div class="bar-wrap">
                        <div class="bar" style="height:{{ $totalItems > 0 ? (($totalItems - $availableItems - $borrowedItems) / $totalItems * 70) : 0 }}px;background:#fbbf24"></div>
                    </div>
                    <div class="bar-val">{{ $totalItems - $availableItems - $borrowedItems }}</div>
                    <div class="bar-label">Lainnya</div>
                </div>
            </div>
        </div>
    </div>
</div>
