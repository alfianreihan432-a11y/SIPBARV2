@extends('layouts.guru')

@section('title', 'Katalog Barang')
@section('page-heading', 'Katalog Barang')

@section('content')
<style>
    .section-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }
    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin: 0;
    }

    /* Pagination Styles */
    .pagination-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 16px;
        padding: 10px 14px;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
    }
    .pagination-info {
        font-size: 13px;
        color: var(--muted);
        white-space: nowrap;
        flex-shrink: 0;
    }
    .pagination-controls {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
    }
    .pagination-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: 1px solid var(--border);
        background: var(--card);
        color: var(--text);
        cursor: pointer;
        transition: background .15s;
        text-decoration: none;
    }
    .pagination-btn:hover {
        background: var(--bg3);
    }
    .pagination-btn:focus-visible {
        outline: 2px solid var(--accent);
        outline-offset: 2px;
    }
    html.dark .pagination-btn:focus-visible {
        outline: 2px solid #10b981;
        outline-offset: 2px;
    }
    .pagination-btn:disabled,
    .pagination-btn.disabled {
        opacity: 0.4;
        cursor: not-allowed;
        pointer-events: none;
    }
    .pagination-indicator {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 58px;
        height: 36px;
        padding: 0 12px;
        border-radius: 8px;
        border: 1px solid var(--border);
        background: var(--bg3);
        font-size: 12px;
        font-weight: 700;
        color: var(--text);
        white-space: nowrap;
    }

    /* Bottom Pagination */
    .pagination-bottom {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        margin-top: 24px;
        padding: 12px;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
    }
    .pagination-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 12px;
        border-radius: 8px;
        border: 1px solid var(--border);
        background: var(--card);
        color: var(--text);
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all .15s;
    }
    .pagination-link:hover {
        background: var(--bg3);
        border-color: var(--accent);
    }
    .pagination-link.active {
        background: var(--accent);
        color: #ffffff;
        border-color: var(--accent);
    }
    html.dark .pagination-link.active {
        background: #10b981;
        border-color: #10b981;
    }
    .pagination-link:disabled,
    .pagination-link.disabled {
        opacity: 0.4;
        cursor: not-allowed;
        pointer-events: none;
    }

    /* Filter & Search styling */
    .filter-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 20px;
    }
    .filter-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--muted);
        letter-spacing: .06em;
        text-transform: uppercase;
        margin-bottom: 6px;
        display: block;
    }
    .search-input-wrap {
        position: relative;
    }
    .search-input-wrap svg {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        color: var(--subtle);
        pointer-events: none;
    }
    .catalog-search-input {
        width: 100%;
        padding: 10px 14px 10px 38px;
        background: var(--input-bg);
        border: 1.5px solid var(--border);
        border-radius: 10px;
        font-size: 13.5px;
        color: var(--text);
        outline: none;
        transition: border-color .2s, box-shadow .2s;
        font-family: inherit;
    }
    .catalog-search-input:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
    }
    .catalog-select {
        width: 100%;
        padding: 10px 14px;
        background: var(--input-bg);
        border: 1.5px solid var(--border);
        border-radius: 10px;
        font-size: 13.5px;
        color: var(--text);
        outline: none;
        cursor: pointer;
        font-family: inherit;
        transition: border-color .2s, box-shadow .2s;
    }
    .catalog-select:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
    }
    .btn-reset-filter {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        background: var(--bg3);
        border: 1px solid var(--border);
        color: var(--muted);
        text-decoration: none;
        transition: all .15s;
        white-space: nowrap;
    }
    .btn-reset-filter:hover {
        background: var(--border);
        color: var(--text);
    }
    
    .items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }
    
    .item-card {
        background: var(--bg3);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.2s ease;
    }
    .item-card:hover {
        border-color: var(--accent);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .item-image {
        width: 100%;
        height: 180px;
        background: var(--bg2);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }
    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .item-image-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--muted);
        font-size: 13px;
    }
    
    .item-content {
        padding: 16px;
    }
    .item-name {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 8px;
        line-height: 1.3;
    }
    .item-meta {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 12px;
    }
    .meta-row {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
    }
    .meta-label {
        color: var(--muted);
    }
    .meta-value {
        color: var(--text);
        font-weight: 600;
    }
    
    /* ── Dynamic Status Badges ── */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.03em;
    }
    .status-badge-tersedia {
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    .status-badge-menunggu {
        background: rgba(245, 158, 11, 0.15);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }
    .status-badge-dipinjam {
        background: rgba(234, 88, 12, 0.15);
        color: #ea580c;
        border: 1px solid rgba(234, 88, 12, 0.3);
    }

    /* ── Borrow Button Variants ── */
    /* Primary (tersedia) — emerald, tema guru */
    .borrow-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 20px;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s, opacity 0.2s;
        text-decoration: none;
        width: 100%;
        margin-top: 12px;
        color: #ffffff;
    }
    .borrow-btn-primary {
        background: var(--accent); /* emerald – tema guru */
    }
    .borrow-btn-primary:hover {
        background: #059669;
    }
    /* Gray – menunggu persetujuan */
    .borrow-btn-gray {
        background: var(--bg3);
        border: 1px solid var(--border);
        color: var(--muted);
        cursor: not-allowed;
        opacity: 0.75;
    }
    /* Orange – dipinjam / stok habis */
    .borrow-btn-orange {
        background: rgba(234, 88, 12, 0.18);
        color: #ea580c;
        border: 1px solid rgba(234, 88, 12, 0.3);
        cursor: not-allowed;
        opacity: 0.85;
    }

    .empty-state {
        text-align: center;
        padding: 48px 20px;
        color: var(--muted);
    }
    .empty-state svg {
        width: 48px;
        height: 48px;
        margin-bottom: 12px;
        opacity: 0.5;
    }
</style>

{{-- ═══ SECTION SEARCH & FILTER ═══ --}}
<div class="filter-card">
    <form action="{{ route('teacher.barang') }}" method="GET" id="catalogFilterForm">
        <input type="hidden" name="page" value="1">
        <div style="display:flex;gap:14px;flex-wrap:wrap;align-items:flex-end">
            <div style="flex:1;min-width:240px">
                <label for="catalogSearchInput" class="filter-label">Cari Barang</label>
                <div class="search-input-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ $search ?? '' }}" id="catalogSearchInput"
                        class="catalog-search-input"
                        placeholder="Nama barang, kode, atau deskripsi..." autocomplete="off">
                </div>
            </div>
            <div style="min-width:200px">
                <label for="catalogCategorySelect" class="filter-label">Kategori</label>
                <select name="categoryFilter" id="catalogCategorySelect" class="catalog-select" onchange="resetToPage1AndSubmit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (string)($categoryFilter ?? '') === (string)$cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @if(!empty($search) || !empty($categoryFilter))
            <div>
                <a href="{{ route('teacher.barang') }}" class="btn-reset-filter">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reset Filter
                </a>
            </div>
            @endif
        </div>
    </form>
</div>

{{-- ═══ SECTION GRID KATALOG ═══ --}}
<div class="section-card">
    <div class="section-header">
        <h2 class="section-title">Katalog Barang Tersedia</h2>
    </div>

    {{-- TOP PAGINATION BAR --}}
    @if($items->total() > 0)
    <div class="pagination-bar">
        {{-- Info teks kiri --}}
        <span class="pagination-info">
            @if($items->lastPage() > 1)
                Halaman <strong style="color:var(--text)">{{ $items->currentPage() }}</strong> dari <strong style="color:var(--text)">{{ $items->lastPage() }}</strong>
                &nbsp;&middot;&nbsp; <span style="color:var(--subtle)">{{ $items->total() }} barang</span>
            @else
                Menampilkan <strong style="color:var(--text)">{{ $items->total() }}</strong> barang
            @endif
        </span>
        {{-- Tombol prev / next (tampil hanya jika ada lebih dari 1 halaman) --}}
        @if($items->lastPage() > 1)
        <div class="pagination-controls">
            @if($items->onFirstPage())
                <span aria-disabled="true" class="pagination-btn disabled">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </span>
            @else
                <a href="{{ $items->previousPageUrl() }}" class="pagination-btn" aria-label="Halaman sebelumnya">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </a>
            @endif
            <span class="pagination-indicator">{{ $items->currentPage() }} / {{ $items->lastPage() }}</span>
            @if($items->hasMorePages())
                <a href="{{ $items->nextPageUrl() }}" class="pagination-btn" aria-label="Halaman berikutnya">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <span aria-disabled="true" class="pagination-btn disabled">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif
        </div>
        @endif
    </div>
    @endif

    @if($items->count() > 0)
        <div class="items-grid" id="itemsGrid">
            @foreach($items as $item)
                <div class="item-card">
                    <div class="item-image">
                        @if($item->photo_path)
                            <img src="{{ asset('storage/' . $item->photo_path) }}" alt="{{ $item->name }}">
                        @else
                            <div class="item-image-placeholder">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:48px;height:48px;margin-bottom:8px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                <span>Tidak ada foto</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="item-content">
                        <h3 class="item-name">{{ $item->name }}</h3>
                        
                        <div class="item-meta">
                            <div class="meta-row">
                                <span class="meta-label">Kategori:</span>
                                <span class="meta-value">{{ $item->category->name ?? '-' }}</span>
                            </div>
                            <div class="meta-row">
                                <span class="meta-label">Lokasi:</span>
                                <span class="meta-value">{{ $item->location?->room ?? $item->location?->building ?? '-' }}</span>
                            </div>
                            <div class="meta-row">
                                <span class="meta-label">Kondisi:</span>
                                <span class="meta-value">{{ $item->condition ?? '-' }}</span>
                            </div>
                            <div class="meta-row">
                                <span class="meta-label">Stok:</span>
                                <span class="meta-value">{{ $item->stock }} unit</span>
                            </div>
                        </div>
                        
                        {{-- ── Dynamic Status Badge + Borrow Button (reuses getCatalogStatusInfo()) ── --}}
                        @php $statusInfo = $item->getCatalogStatusInfo(); @endphp

                        <div style="display: flex; gap: 8px; align-items: center; margin-top: 12px; flex-wrap: wrap;">
                            {{-- Badge Semantik: Tersedia / Menunggu / Dipinjam --}}
                            <span class="status-badge status-badge-{{ $statusInfo['status'] }}">
                                @if($statusInfo['status'] === 'tersedia')
                                    {{-- dot hijau --}}
                                    <span style="width:7px;height:7px;border-radius:50%;background:#10b981;display:inline-block;"></span>
                                @elseif($statusInfo['status'] === 'menunggu')
                                    {{-- ikon jam --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @else
                                    {{-- ikon orang --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                @endif
                                {{ $statusInfo['badge_label'] }}
                            </span>

                            {{-- Info stok total --}}
                            <span style="font-size:11px;color:var(--muted);font-weight:600;">
                                {{ $item->stock }} unit
                            </span>
                        </div>

                        {{-- Tombol aksi sesuai status --}}
                        @if(!$statusInfo['button_disabled'])
                            <form method="POST" action="{{ route('teacher.peminjaman-guru.cart.add') }}">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="borrow-btn borrow-btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Pinjam Barang
                                </button>
                            </form>
                        @elseif($statusInfo['status'] === 'menunggu')
                            <button disabled class="borrow-btn borrow-btn-gray">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Menunggu Persetujuan
                            </button>
                        @else
                            <button disabled class="borrow-btn borrow-btn-orange">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                {{ $statusInfo['button_label'] }}
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- BOTTOM PAGINATION --}}
        @if($items->lastPage() > 1)
        <div class="pagination-bottom">
            @if($items->onFirstPage())
                <span class="pagination-link disabled">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </span>
            @else
                <a href="{{ $items->previousPageUrl() }}" class="pagination-link" aria-label="Halaman sebelumnya">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
            @endif

            @for($i = 1; $i <= $items->lastPage(); $i++)
                @if($i == $items->currentPage())
                    <span class="pagination-link active">{{ $i }}</span>
                @elseif($i == 1 || $i == $items->lastPage() || ($i >= $items->currentPage() - 1 && $i <= $items->currentPage() + 1))
                    <a href="{{ $items->url($i) }}" class="pagination-link">{{ $i }}</a>
                @elseif($i == 2 && $items->currentPage() > 4)
                    <span class="pagination-link disabled">...</span>
                @elseif($i == $items->lastPage() - 1 && $items->currentPage() < $items->lastPage() - 3)
                    <span class="pagination-link disabled">...</span>
                @endif
            @endfor

            @if($items->hasMorePages())
                <a href="{{ $items->nextPageUrl() }}" class="pagination-link" aria-label="Halaman berikutnya">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <span class="pagination-link disabled">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif
        </div>
        @endif
    @else
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <div style="font-size:16px;font-weight:700;color:var(--text);margin-bottom:6px">Barang tidak ditemukan</div>
            <p style="font-size:13px;color:var(--muted);margin-bottom:14px">
                @if(!empty($search) || !empty($categoryFilter))
                    Tidak ada barang yang sesuai dengan kriteria pencarian Anda.
                @else
                    Tidak ada barang yang tersedia saat ini.
                @endif
            </p>
            @if(!empty($search) || !empty($categoryFilter))
                <a href="{{ route('teacher.barang') }}" class="btn-reset-filter" style="display:inline-flex">Reset Pencarian</a>
            @endif
        </div>
    @endif
</div>

<script>
let debounceTimer;
function resetToPage1AndSubmit() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        const form = document.getElementById('catalogFilterForm');
        const pageInput = form.querySelector('input[name="page"]');
        if (pageInput) {
            pageInput.value = '1';
        }
        form.submit();
    }, 400);
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('catalogSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', resetToPage1AndSubmit);
    }
});
</script>
@endsection