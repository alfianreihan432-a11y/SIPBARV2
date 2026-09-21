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
    
    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
    .status-tersedia {
        background: rgba(16, 185, 129, 0.12);
        color: #10b981;
    }
    
    .stock-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        background: var(--accent);
        color: #ffffff;
    }

    .borrow-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 20px;
        background: var(--accent);
        color: #ffffff;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        text-decoration: none;
        width: 100%;
        margin-top: 12px;
    }
    .borrow-btn:hover {
        background: #059669;
    }
    .borrow-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background: var(--muted);
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
                <select name="categoryFilter" id="catalogCategorySelect" class="catalog-select" onchange="this.form.submit()">
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
        <span id="itemsCountDisplay" style="font-size: 13px; color: var(--muted); font-weight: 600;">
            {{ $items->count() }} barang tersedia
        </span>
    </div>
    
    @if($items->count() > 0)
        <div class="items-grid" id="itemsGrid">
            @foreach($items as $item)
                <div class="item-card"
                     data-name="{{ strtolower($item->name) }}"
                     data-code="{{ strtolower($item->code ?? '') }}"
                     data-desc="{{ strtolower($item->description ?? '') }}"
                     data-category="{{ $item->category_id }}">
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
                                <span class="meta-value">{{ $item->location->name ?? '-' }}</span>
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
                        
                        <div style="display: flex; gap: 8px; align-items: center; margin-top: 12px;">
                            <span class="status-badge status-tersedia">Tersedia</span>
                            <span class="stock-badge">{{ $item->stock }} Unit</span>
                        </div>

                        @if($item->stock > 0)
                            <form method="POST" action="{{ route('teacher.peminjaman-guru.cart.add') }}">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="borrow-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Pinjam Barang
                                </button>
                            </form>
                        @else
                            <button disabled class="borrow-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                </svg>
                                Stok Habis
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Client-side empty state container --}}
        <div id="clientEmptyState" class="empty-state" style="display:none">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <div style="font-size:16px;font-weight:700;color:var(--text);margin-bottom:6px">Barang tidak ditemukan</div>
            <p style="font-size:13px;color:var(--muted);margin-bottom:14px">Coba ubah kata kunci atau pilih kategori lain</p>
            <a href="{{ route('teacher.barang') }}" class="btn-reset-filter" style="display:inline-flex">Reset Pencarian</a>
        </div>
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
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('catalogSearchInput');
    const categorySelect = document.getElementById('catalogCategorySelect');
    const cards = document.querySelectorAll('.item-card');
    const emptyState = document.getElementById('clientEmptyState');
    const countDisplay = document.getElementById('itemsCountDisplay');

    if (!searchInput) return;

    let debounceTimer;
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const query = searchInput.value.toLowerCase().trim();
            const selectedCat = categorySelect ? categorySelect.value : '';
            let visibleCount = 0;

            cards.forEach(card => {
                const name = card.getAttribute('data-name') || '';
                const code = card.getAttribute('data-code') || '';
                const desc = card.getAttribute('data-desc') || '';
                const catId = card.getAttribute('data-category') || '';

                const matchesQuery = !query || name.includes(query) || code.includes(query) || desc.includes(query);
                const matchesCat = !selectedCat || catId === selectedCat;

                if (matchesQuery && matchesCat) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (countDisplay) {
                countDisplay.textContent = visibleCount + ' barang tersedia';
            }

            if (emptyState) {
                emptyState.style.display = (visibleCount === 0 && cards.length > 0) ? 'block' : 'none';
            }
        }, 120);
    });
});
</script>
@endsection