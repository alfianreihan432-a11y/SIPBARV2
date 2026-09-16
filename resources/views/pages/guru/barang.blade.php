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
        padding: 40px 20px;
        color: var(--muted);
    }
    .empty-state svg {
        width: 48px;
        height: 48px;
        margin-bottom: 12px;
        opacity: 0.5;
    }
</style>

<div class="section-card">
    <div class="section-header">
        <h2 class="section-title">Katalog Barang Tersedia</h2>
        <span style="font-size: 13px; color: var(--muted);">
            {{ $items->count() }} barang tersedia
        </span>
    </div>
    
    @if($items->count() > 0)
        <div class="items-grid">
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
    @else
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p>Tidak ada barang yang tersedia saat ini</p>
        </div>
    @endif
</div>
@endsection