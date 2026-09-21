@extends('layouts.siswa')

@section('title', 'Keranjang Peminjaman – SIPBAR')

@section('content')
<style>
    /* ═══════════════════════════════════════════════
       KERANJANG PEMINJAMAN — BULLETPROOF CSS
       Harmonized with SIPBAR Siswa Design System v2
    ═══════════════════════════════════════════════ */
    .cart-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* 2-Column Responsive Layout */
    .cart-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.8fr) minmax(300px, 1fr);
        gap: 24px;
        align-items: start;
        width: 100%;
    }

    @media (max-width: 1024px) {
        .cart-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }

    .cart-main-col {
        display: flex;
        flex-direction: column;
        gap: 24px;
        min-width: 0;
        width: 100%;
    }

    .cart-side-col {
        position: sticky;
        top: 24px;
        display: flex;
        flex-direction: column;
        gap: 16px;
        width: 100%;
    }

    @media (max-width: 1024px) {
        .cart-side-col {
            position: static;
        }
    }

    /* Cards */
    .cart-card {
        background: var(--card, #ffffff);
        border: 1px solid var(--border2, #e4e9ef);
        border-radius: 16px;
        padding: 22px 24px;
        box-shadow: 0 1px 4px rgba(0,0,0,.04);
        width: 100%;
        box-sizing: border-box;
    }

    .cart-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 1px solid var(--border2, #e4e9ef);
    }

    .cart-card-title {
        font-family: var(--font-head, sans-serif);
        font-size: 16px;
        font-weight: 700;
        color: var(--text, #0d1829);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .cart-card-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: var(--primary-light, #eff6ff);
        color: var(--primary, #2563eb);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Item Card inside Cart */
    .cart-item-row {
        background: var(--card2, #f8fafc);
        border: 1px solid var(--border2, #e4e9ef);
        border-radius: 12px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 12px;
        transition: all .15s ease;
    }

    .cart-item-row:last-child {
        margin-bottom: 0;
    }

    .cart-item-row:hover {
        background: var(--bg3, #e8edf2);
        border-color: var(--border, #dde3ea);
    }

    .cart-item-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    @media (max-width: 640px) {
        .cart-item-row {
            flex-direction: column;
            align-items: flex-start;
        }
        .cart-item-actions {
            width: 100%;
            justify-content: flex-end;
            padding-top: 8px;
            border-top: 1px solid var(--border2, #e4e9ef);
        }
    }

    .cart-item-info {
        flex: 1;
        min-width: 0;
    }

    .cart-item-badge {
        display: inline-block;
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: .02em;
        padding: 2px 8px;
        border-radius: 6px;
        background: var(--primary-light, #eff6ff);
        color: var(--primary, #2563eb);
        border: 1px solid var(--primary-muted, rgba(59,130,246,.3));
        margin-bottom: 5px;
    }

    .cart-item-name {
        font-size: 14.5px;
        font-weight: 700;
        color: var(--text, #0d1829);
        line-height: 1.3;
        word-break: break-word;
    }

    .cart-item-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 6px;
        font-size: 12px;
        color: var(--muted, #5a6a7e);
    }

    .cart-item-meta-item {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    /* Form Fields */
    .cart-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 16px;
    }

    @media (max-width: 640px) {
        .cart-form-grid {
            grid-template-columns: 1fr;
        }
    }

    .cart-field-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 16px;
    }

    .cart-field-group:last-child {
        margin-bottom: 0;
    }

    .cart-label {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 12.5px;
        font-weight: 600;
        color: var(--text, #0d1829);
        margin-bottom: 6px;
    }

    .cart-label svg {
        color: var(--primary, #2563eb);
        flex-shrink: 0;
    }

    .cart-input {
        width: 100%;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid var(--border2, #e4e9ef);
        background: var(--input-bg, #ffffff);
        color: var(--text, #0d1829);
        font-size: 13.5px;
        font-family: var(--font-body, inherit);
        box-sizing: border-box;
        transition: border-color .15s, box-shadow .15s;
    }

    .cart-input:focus {
        outline: none;
        border-color: var(--primary, #2563eb);
        box-shadow: 0 0 0 3px var(--primary-light, rgba(59,130,246,.15));
    }

    .cart-input::placeholder {
        color: var(--subtle, #8898aa);
    }

    /* Summary Side Card */
    .cart-summary-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 13px;
        color: var(--muted, #5a6a7e);
    }

    .cart-summary-val {
        font-weight: 700;
        color: var(--text, #0d1829);
    }

    .cart-summary-divider {
        height: 1px;
        background: var(--border2, #e4e9ef);
        margin: 10px 0;
    }

    /* Modal / Dialog */
    .cart-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.55);
        backdrop-filter: blur(3px);
        z-index: 1000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }
    .cart-modal-box {
        background: var(--card, #ffffff);
        border: 1px solid var(--border2, #e4e9ef);
        border-radius: 16px;
        width: 100%;
        max-width: 440px;
        box-shadow: 0 20px 40px -8px rgba(0,0,0,.25);
        overflow: hidden;
        animation: cartModalIn .18s ease-out;
    }
    @keyframes cartModalIn {
        from { opacity: 0; transform: translateY(-8px) scale(.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* Ensure NO SVGs can render giant under any circumstances */
    svg {
        max-width: 100%;
        box-sizing: content-box;
    }
    .svg-icon-14 { width: 14px !important; height: 14px !important; min-width: 14px !important; }
    .svg-icon-16 { width: 16px !important; height: 16px !important; min-width: 16px !important; }
    .svg-icon-18 { width: 18px !important; height: 18px !important; min-width: 18px !important; }

    /* Native date/time picker invert for dark mode */
    input[type="date"], input[type="time"] { color-scheme: light; }
    html.dark input[type="date"], html.dark input[type="time"] { color-scheme: dark; }

    /* Custom Searchable Teacher Dropdown */
    .ts-wrapper { position: relative; font-family: var(--font-sans, sans-serif); }
    .ts-trigger {
        display: flex; align-items: center; justify-content: space-between;
        gap: 8px; padding: 10px 14px; background: var(--card);
        border: 1.5px solid var(--border2); border-radius: 10px;
        cursor: pointer; font-size: 13.5px; color: var(--text);
        transition: border-color .2s, box-shadow .2s; user-select: none; min-height: 42px;
    }
    .ts-trigger:focus-visible { outline: 2px solid var(--primary); outline-offset: 2px; }
    .ts-trigger.ts-open {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(59,130,246,.15);
    }
    .ts-trigger-placeholder { color: var(--muted); }
    .ts-trigger-value { display: flex; align-items: center; gap: 8px; flex: 1; overflow: hidden; }
    .ts-trigger-value .ts-avatar {
        width: 26px; height: 26px; border-radius: 50%; background: var(--primary-light);
        border: 1.5px solid var(--primary-muted); display: flex; align-items: center;
        justify-content: center; font-size: 10px; font-weight: 700; color: var(--primary); flex-shrink: 0;
    }
    .ts-trigger-value .ts-val-name { font-weight: 600; color: var(--text); font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .ts-trigger-value .ts-val-jabatan { font-size: 11px; color: var(--muted); flex-shrink: 0; }
    .ts-chevron { flex-shrink: 0; transition: transform .25s; color: var(--muted); }
    .ts-trigger.ts-open .ts-chevron { transform: rotate(180deg); }
    [x-cloak] { display: none !important; }
    .ts-dropdown {
        position: absolute; top: calc(100% + 6px); left: 0; right: 0;
        background: var(--card); border: 1.5px solid var(--border2); border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0,0,0,.15), 0 2px 8px rgba(0,0,0,.08);
        z-index: 99999; overflow: hidden;
        animation: tsDropIn .18s cubic-bezier(.22,.68,0,1.2);
    }
    @keyframes tsDropIn {
        from { opacity: 0; transform: translateY(-6px) scale(.98); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    .ts-search-box {
        padding: 10px 12px; border-bottom: 1px solid var(--border2);
        display: flex; align-items: center; gap: 8px;
    }
    .ts-search-box svg { color: var(--muted); flex-shrink: 0; }
    .ts-search-input {
        flex: 1; border: none; outline: none; background: transparent;
        font-size: 13px; color: var(--text); font-family: inherit;
    }
    .ts-search-input::placeholder { color: var(--muted); }
    .ts-options {
        max-height: 248px; overflow-y: auto; padding: 6px;
        scrollbar-width: thin; scrollbar-color: var(--border2) transparent;
    }
    .ts-options::-webkit-scrollbar { width: 5px; }
    .ts-options::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 4px; }
    .ts-option {
        display: flex; align-items: center; gap: 10px;
        padding: 9px 10px; border-radius: 8px; cursor: pointer; transition: background .15s;
    }
    .ts-option:hover, .ts-option.ts-focused { background: var(--bg3, rgba(0,0,0,.04)); }
    .ts-option.ts-selected { background: var(--primary-light); }
    .ts-option .ts-opt-avatar {
        width: 30px; height: 30px; border-radius: 50%; background: var(--primary-light);
        border: 1.5px solid var(--primary-muted); display: flex; align-items: center;
        justify-content: center; font-size: 11px; font-weight: 700; color: var(--primary); flex-shrink: 0;
    }
    .ts-option.ts-selected .ts-opt-avatar { background: var(--primary); color: #fff; border-color: var(--primary); }
    .ts-option .ts-opt-info { flex: 1; overflow: hidden; }
    .ts-option .ts-opt-name { font-weight: 600; font-size: 13px; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .ts-option .ts-opt-jabatan { font-size: 11px; color: var(--muted); margin-top: 1px; }
    .ts-option.ts-selected .ts-opt-name { color: var(--primary); }
    .ts-check { flex-shrink: 0; opacity: 0; transition: opacity .15s; color: var(--primary); }
    .ts-option.ts-selected .ts-check { opacity: 1; }
    .ts-empty { padding: 20px 12px; text-align: center; color: var(--muted); font-size: 13px; display: none; }
    .ts-empty svg { display: block; margin: 0 auto 8px; color: var(--subtle); }
    .ts-native { display: none !important; }
</style>

@php
    $totalItems = count($cartItems);
    $totalUnits = 0;
    foreach ($cartItems as $c) {
        $totalUnits += (int)($c['quantity'] ?? 1);
    }
    $defaultBorrowDate = now()->toDateString();
    $defaultReturnDate = now()->addDays(7)->toDateString();
    $defaultReturnTime = '14:00';
    $defaultWaNumber = old('whatsapp_number', auth()->user()->phone ?? '');
@endphp

<div class="cart-container">
    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-header-left">
            <div class="page-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" style="width:22px;height:22px;color:var(--primary);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span>Keranjang Peminjaman</span>
                @if($totalItems > 0)
                    <span class="page-title-count">{{ $totalItems }} barang</span>
                @endif
            </div>
            <div class="page-subtitle">Review daftar barang dan lengkapi rincian peminjaman sebelum mengajukan permohonan.</div>
        </div>
        <a href="{{ route('student.catalog') }}" class="s-btn s-btn--primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="svg-icon-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Barang</span>
        </a>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div style="background: var(--s-returned-bg, #ecfdf5); border: 1px solid var(--s-returned-bdr, #a7f3d0); color: var(--s-returned, #059669); border-radius: 12px; padding: 12px 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 500;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="svg-icon-18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div style="flex: 1;">{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div style="background: var(--s-rejected-bg, #fef2f2); border: 1px solid var(--s-rejected-bdr, #fecaca); color: var(--s-rejected, #dc2626); border-radius: 12px; padding: 12px 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 500;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="svg-icon-18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div style="flex: 1;">{{ session('error') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div style="background: var(--s-rejected-bg, #fef2f2); border: 1px solid var(--s-rejected-bdr, #fecaca); color: var(--s-rejected, #dc2626); border-radius: 12px; padding: 14px 18px; margin-bottom: 20px; font-size: 13px;">
            <div style="font-weight: 700; margin-bottom: 6px; display: flex; align-items: center; gap: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="svg-icon-18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Periksa kembali data yang dimasukkan:</span>
            </div>
            <ul style="padding-left: 26px; list-style-type: disc;">
                @foreach($errors->all() as $error)
                    <li style="margin-top: 2px;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Main 2-Column Responsive Layout --}}
    <div class="cart-grid">
        
        {{-- ========================================================================= --}}
        {{-- KOLOM KIRI (~65% di desktop): Card Barang di Keranjang + Card Formulir    --}}
        {{-- ========================================================================= --}}
        <div class="cart-main-col">
            
            {{-- CARD 1: BARANG DI KERANJANG --}}
            <div class="cart-card">
                <div class="cart-card-header">
                    <div class="cart-card-title">
                        <div class="cart-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="svg-icon-18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <span>Barang di Keranjang</span>
                    </div>
                    <span class="s-badge s-badge--approved">{{ $totalItems }} Jenis Barang</span>
                </div>

                @if(empty($cartItems))
                    <div class="s-empty">
                        <div class="s-empty-icon-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" style="width:32px;height:32px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="s-empty-title">Keranjang masih kosong</div>
                        <div class="s-empty-sub">Silakan pilih barang yang ingin Anda pinjam dari katalog inventaris terlebih dahulu.</div>
                        <a href="{{ route('student.catalog') }}" class="s-btn s-btn--primary s-btn--sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" class="svg-icon-14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span>Buka Katalog Barang</span>
                        </a>
                    </div>
                @else
                    <div>
                        @foreach($cartItems as $entry)
                            @php 
                                $item = $entry['item'];
                                $cleanName = $item->name;
                                $categoryBadge = $item->category->name ?? '';

                                if (strpos($item->name, '.') !== false) {
                                    $parts = explode('.', $item->name);
                                    if (count($parts) >= 2) {
                                        if (empty($categoryBadge)) {
                                            $categoryBadge = trim($parts[0]);
                                        }
                                        $cleanName = trim($parts[count($parts) - 1]);
                                        $cleanName = preg_replace('/\s*-\s*\[.*?\]\s*$/', '', $cleanName);
                                        $cleanName = trim($cleanName);
                                    }
                                }
                                
                                // Format nama barang jadi Title Case
                                $displayTitle = \Illuminate\Support\Str::title(mb_strtolower($cleanName));
                                if ($categoryBadge) {
                                    $categoryBadge = \Illuminate\Support\Str::title(mb_strtolower($categoryBadge));
                                }
                                $itemStock = (int) ($item->stock ?? 1);
                                $itemPurpose = $entry['purpose'] ?? '';
                            @endphp
                            <div class="cart-item-row" id="cart-row-{{ $item->id }}">
                                <div class="cart-item-info">
                                    @if($categoryBadge)
                                        <div class="cart-item-badge">{{ $categoryBadge }}</div>
                                    @endif
                                    
                                    <div class="cart-item-name" title="{{ $cleanName }}">
                                        {{ $displayTitle }}
                                    </div>

                                    <div class="cart-item-meta">
                                        <div class="cart-item-meta-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" class="svg-icon-14" style="color:var(--primary);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                            <span><strong id="qty-text-{{ $item->id }}">{{ $entry['quantity'] }}</strong> unit</span>
                                        </div>
                                        <span style="color:var(--border2);">•</span>
                                        <div class="cart-item-meta-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" class="svg-icon-14" style="color:var(--subtle);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                            </svg>
                                            <span>Kode: <span style="font-family:monospace;">{{ $item->code ?? '-' }}</span></span>
                                        </div>
                                        @if(!empty($item->location?->name))
                                            <span style="color:var(--border2);">•</span>
                                            <div class="cart-item-meta-item">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" class="svg-icon-14" style="color:var(--subtle);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                <span>{{ $item->location->name }}</span>
                                            </div>
                                        @endif
                                        @if(!empty($itemPurpose))
                                            <span style="color:var(--border2);">•</span>
                                            <div class="cart-item-meta-item" style="font-style:italic;">
                                                <span>"{{ Str::limit($itemPurpose, 35) }}"</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- FITUR 1: Tombol Edit (icon pensil) & Tombol Hapus (icon trash) --}}
                                <div class="cart-item-actions">
                                    <button type="button" 
                                            onclick="openEditModal({{ $item->id }}, '{{ addslashes($displayTitle) }}', {{ $entry['quantity'] }}, {{ $itemStock }}, '{{ addslashes($itemPurpose) }}')"
                                            class="s-btn s-btn--secondary s-btn--sm"
                                            title="Edit jumlah unit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" class="svg-icon-14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        <span>Edit</span>
                                    </button>

                                    <button type="button" 
                                            onclick="openDeleteModal({{ $item->id }}, '{{ addslashes($displayTitle) }}', {{ $entry['quantity'] }})"
                                            class="s-btn s-btn--danger s-btn--sm"
                                            title="Hapus dari keranjang">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" class="svg-icon-14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span>Hapus</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- CARD 2: RINGKASAN / FORMULIR INFORMASI PEMINJAMAN --}}
            <div class="cart-card">
                <div class="cart-card-header">
                    <div class="cart-card-title">
                        <div class="cart-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="svg-icon-18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span>Informasi Peminjaman</span>
                    </div>
                    <span style="font-size:12px;color:var(--muted);">Lengkapi rincian pengajuan</span>
                </div>

                <form id="borrow-cart-form" method="POST" action="{{ route('student.loans.cart.submit') }}">
                    @csrf

                    {{-- Row 1: Tanggal Pinjam & Tanggal Kembali (2 Kolom) --}}
                    <div class="cart-form-grid">
                        <div class="cart-field-group">
                            <label for="borrow_date" class="cart-label">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="svg-icon-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Tanggal Pinjam</span>
                                <span style="color:var(--s-rejected);font-weight:700;">*</span>
                            </label>
                            <input type="date" 
                                   id="borrow_date"
                                   name="borrow_date" 
                                   required 
                                   min="{{ now()->toDateString() }}"
                                   value="{{ old('borrow_date', $defaultBorrowDate) }}" 
                                   class="cart-input">
                        </div>

                        <div class="cart-field-group">
                            <label for="return_date" class="cart-label">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="svg-icon-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Tanggal Kembali</span>
                                <span style="color:var(--s-rejected);font-weight:700;">*</span>
                            </label>
                            <input type="date" 
                                   id="return_date"
                                   name="return_date" 
                                   required 
                                   min="{{ old('borrow_date', $defaultBorrowDate) }}"
                                   value="{{ old('return_date', $defaultReturnDate) }}" 
                                   class="cart-input">
                        </div>
                    </div>

                    {{-- Row 2: Jam Kembali & Guru Pembimbing (2 Kolom) --}}
                    <div class="cart-form-grid">
                        <div class="cart-field-group">
                            <label for="return_time" class="cart-label">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="svg-icon-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Jam Kembali</span>
                                <span style="color:var(--s-rejected);font-weight:700;">*</span>
                            </label>
                            <input type="time" 
                                   id="return_time"
                                   name="return_time" 
                                   required 
                                   value="{{ old('return_time', $defaultReturnTime) }}" 
                                   class="cart-input">
                        </div>

                        <div class="cart-field-group"
                             x-data="{
                                 open: false,
                                 search: '',
                                 selectedId: {{ (int) old('teacher_id', 0) }},
                                 teachers: {{ Js::from($teachers->map(fn($t) => ['id' => $t->id, 'name' => $t->name, 'jabatan' => $t->jabatan ?? 'Guru'])) }},
                                 get selectedTeacher() {
                                     return this.teachers.find(t => t.id == this.selectedId);
                                 },
                                 get filteredTeachers() {
                                     if (!this.search || !this.search.trim()) return this.teachers;
                                     let q = this.search.toLowerCase().trim();
                                     return this.teachers.filter(t => (t.name + ' ' + t.jabatan).toLowerCase().includes(q));
                                 },
                                 getInitials(name) {
                                     if (!name) return 'G';
                                     return name.split(' ').slice(0, 2).map(w => w[0]).join('').toUpperCase();
                                 },
                                 select(teacher) {
                                     this.selectedId = teacher.id;
                                     this.open = false;
                                     this.search = '';
                                 }
                             }">
                            <label for="teacher_id" class="cart-label">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="svg-icon-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>Guru Pembimbing</span>
                                <span style="color:var(--s-rejected);font-weight:700;">*</span>
                            </label>

                            <input type="hidden" name="teacher_id" :value="selectedId" required>

                            <div class="ts-wrapper" @click.outside="open = false" @keydown.escape.window="open = false" style="position: relative;">
                                <div @click="open = !open; if(open) { $nextTick(() => { $refs.searchInput && $refs.searchInput.focus() }); }"
                                     :class="{ 'ts-open': open }"
                                     class="ts-trigger"
                                     tabindex="0"
                                     role="combobox"
                                     aria-haspopup="listbox"
                                     :aria-expanded="open.toString()">
                                    <div class="ts-trigger-value">
                                        <template x-if="selectedTeacher">
                                            <div style="display: flex; align-items: center; gap: 8px; overflow: hidden; width: 100%;">
                                                <div class="ts-avatar" x-text="getInitials(selectedTeacher.name)"></div>
                                                <span class="ts-val-name" x-text="selectedTeacher.name"></span>
                                                <span class="ts-val-jabatan" x-text="selectedTeacher.jabatan"></span>
                                            </div>
                                        </template>
                                        <template x-if="!selectedTeacher">
                                            <span class="ts-trigger-placeholder">Cari atau pilih guru...</span>
                                        </template>
                                    </div>
                                    <svg class="ts-chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"/>
                                    </svg>
                                </div>

                                <div x-show="open"
                                     x-cloak
                                     class="ts-dropdown ts-active"
                                     role="listbox">
                                    <div class="ts-search-box">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                                        </svg>
                                        <input x-ref="searchInput"
                                               x-model="search"
                                               class="ts-search-input"
                                               type="text"
                                               placeholder="Ketik nama guru..."
                                               autocomplete="off"
                                               spellcheck="false">
                                    </div>

                                    <div class="ts-options">
                                        <template x-for="teacher in filteredTeachers" :key="teacher.id">
                                            <div @click="select(teacher)"
                                                 class="ts-option"
                                                 :class="{ 'ts-selected': selectedId == teacher.id }"
                                                 role="option">
                                                <div class="ts-opt-avatar" x-text="getInitials(teacher.name)"></div>
                                                <div class="ts-opt-info">
                                                    <div class="ts-opt-name" x-text="teacher.name"></div>
                                                    <div class="ts-opt-jabatan" x-text="teacher.jabatan"></div>
                                                </div>
                                                <svg class="ts-check" xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <polyline points="20 6 9 17 4 12"/>
                                                </svg>
                                            </div>
                                        </template>

                                        <template x-if="filteredTeachers.length === 0">
                                            <div class="ts-empty" style="display: block;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                Guru tidak ditemukan
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            @error('teacher_id')
                                <span style="color: var(--s-rejected); font-size: 12px; display: block; margin-top: 6px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- FITUR 2: INPUT NOMOR WHATSAPP SISWA --}}
                    {{-- Diletakkan setelah field Guru Pembimbing dan sebelum Tujuan Peminjaman --}}
                    <div class="cart-field-group">
                        <label for="whatsapp_number" class="cart-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="svg-icon-16" fill="currentColor" viewBox="0 0 24 24" style="color:#25D366;flex-shrink:0;">
                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.97.585 1.961.948 3.125.948h.005c3.18 0 5.767-2.586 5.768-5.766.001-3.182-2.585-5.77-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.312.045-.698.072-2.09-.474-1.782-.699-2.919-2.518-3.007-2.636-.089-.118-.722-.96-.722-1.831 0-.871.455-1.298.618-1.476.162-.178.354-.223.473-.223.118 0 .237.001.341.006.109.006.255-.041.399.305.148.356.505 1.232.549 1.321.045.089.074.193.015.312-.059.118-.089.193-.178.297-.089.104-.187.232-.267.311-.089.089-.182.186-.078.365.104.178.463.764.995 1.238.685.611 1.262.8 1.44.889.178.089.282.074.386-.045.104-.118.445-.519.564-.697.118-.178.237-.148.399-.089.163.059 1.037.489 1.215.578.178.089.296.133.34.208.045.074.045.43-.099.835z"/>
                                <path d="M12 2C6.477 2 2 6.477 2 12c0 1.891.524 3.66 1.436 5.176L2 22l4.981-1.306C8.423 21.524 10.154 22 12 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm.031 16.924h-.005c-1.164 0-2.304-.337-3.295-.973l-.236-.14-2.449.642.653-2.387-.154-.246c-.7-1.116-1.07-2.418-1.07-3.762 0-3.953 3.218-7.171 7.175-7.171 1.916.001 3.717.747 5.072 2.104 1.355 1.357 2.101 3.158 2.1 5.074-.002 3.955-3.22 7.172-7.174 7.172z"/>
                            </svg>
                            <span>Nomor WhatsApp Siswa</span>
                            <span style="color:var(--s-rejected);font-weight:700;">*</span>
                        </label>
                        <input type="tel" 
                               id="whatsapp_number"
                               name="whatsapp_number" 
                               required 
                               pattern="^(\+?62|0)[0-9]{9,13}$"
                               value="{{ $defaultWaNumber }}" 
                               placeholder="Contoh: 081234567890" 
                               class="cart-input">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:4px;">
                            <span style="font-size:11.5px;color:var(--muted);">Format angka 10-14 digit, diawali 08... atau 62...</span>
                            <span id="waValidationMsg" style="font-size:11.5px;display:none;"></span>
                        </div>
                    </div>

                    {{-- Row 4: Tujuan Peminjaman (Full Width Textarea) --}}
                    <div class="cart-field-group">
                        <label for="purpose" class="cart-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="svg-icon-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Tujuan Peminjaman</span>
                            <span style="color:var(--s-rejected);font-weight:700;">*</span>
                        </label>
                        <textarea id="purpose" 
                                  name="purpose" 
                                  rows="3" 
                                  required 
                                  class="cart-input" 
                                  style="resize:vertical;"
                                  placeholder="Contoh: Digunakan untuk praktikum mandiri dan tugas pembelajaran kelas...">{{ old('purpose') }}</textarea>
                    </div>

                    {{-- Row 5: Catatan (Full Width Textarea) --}}
                    <div class="cart-field-group">
                        <label for="notes" class="cart-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="svg-icon-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span>Catatan</span>
                            <span style="color:var(--muted);font-weight:400;font-size:11px;">(Opsional)</span>
                        </label>
                        <textarea id="notes" 
                                  name="notes" 
                                  rows="2" 
                                  class="cart-input" 
                                  style="resize:vertical;"
                                  placeholder="Tambahkan catatan atau spesifikasi khusus jika diperlukan...">{{ old('notes') }}</textarea>
                    </div>

                    {{-- Tombol Aksi Mobile (Hanya tampil di mobile < 1024px) --}}
                    <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--border2);display:none;" id="mobileActionWrap">
                        <button type="submit" 
                                class="s-btn s-btn--primary"
                                style="width:100%;justify-content:center;padding:12px;font-size:14px;font-weight:700;margin-bottom:8px;"
                                {{ empty($cartItems) ? 'disabled' : '' }}>
                            <span>Ajukan Peminjaman</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="svg-icon-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                        <a href="{{ route('student.loans') }}" 
                           class="s-btn s-btn--secondary"
                           style="width:100%;justify-content:center;padding:9px;font-size:12.5px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" class="svg-icon-14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span>Kembali ke Riwayat</span>
                        </a>
                    </div>
                </form>
            </div>

        </div>

        {{-- ========================================================================= --}}
        {{-- KOLOM KANAN (~35% di desktop, Sticky): Card Ringkasan & Tombol Submit      --}}
        {{-- ========================================================================= --}}
        <div class="cart-side-col">
            <div class="cart-card">
                {{-- Header Ringkasan --}}
                <div class="cart-card-header">
                    <div class="cart-card-title">
                        <div class="cart-card-icon" style="background:var(--s-returned-bg, #ecfdf5);color:var(--s-returned, #059669);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="svg-icon-18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <span>Ringkasan Pengajuan</span>
                    </div>
                </div>

                {{-- Detail Metrik & Jadwal --}}
                <div style="margin-bottom: 16px;">
                    <div class="cart-summary-row">
                        <span>Total Jenis Barang</span>
                        <span class="cart-summary-val">{{ $totalItems }} jenis</span>
                    </div>
                    <div class="cart-summary-row">
                        <span>Total Unit Barang</span>
                        <span class="cart-summary-val" id="summary_total_units" style="color:var(--primary);font-size:15px;">{{ $totalUnits }} unit</span>
                    </div>

                    <div class="cart-summary-divider"></div>

                    <div class="cart-summary-row" style="font-size:12px;">
                        <span style="display:flex;align-items:center;gap:6px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" class="svg-icon-14" style="color:var(--subtle);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Tgl Pinjam
                        </span>
                        <span id="summary_borrow_date" class="cart-summary-val" style="font-size:12px;">
                            {{ \Carbon\Carbon::parse($defaultBorrowDate)->translatedFormat('d M Y') }}
                        </span>
                    </div>

                    <div class="cart-summary-row" style="font-size:12px;">
                        <span style="display:flex;align-items:center;gap:6px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" class="svg-icon-14" style="color:var(--subtle);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Tgl Kembali
                        </span>
                        <span id="summary_return_date" class="cart-summary-val" style="font-size:12px;">
                            {{ \Carbon\Carbon::parse($defaultReturnDate)->translatedFormat('d M Y') }}
                        </span>
                    </div>

                    <div class="cart-summary-row" style="font-size:12px;">
                        <span style="display:flex;align-items:center;gap:6px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" class="svg-icon-14" style="color:var(--subtle);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Jam Kembali
                        </span>
                        <span id="summary_return_time" class="cart-summary-val" style="font-size:12px;">
                            {{ $defaultReturnTime }} WIB
                        </span>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div style="display:flex;flex-direction:column;gap:8px;padding-top:14px;border-top:1px solid var(--border2);">
                    <button type="submit" 
                            form="borrow-cart-form" 
                            class="s-btn s-btn--primary"
                            style="width:100%;justify-content:center;padding:12px 16px;font-size:14px;font-weight:700;"
                            {{ empty($cartItems) ? 'disabled' : '' }}>
                        <span>Ajukan Peminjaman</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="svg-icon-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>

                    <a href="{{ route('student.loans') }}" 
                       class="s-btn s-btn--secondary"
                       style="width:100%;justify-content:center;padding:9px 16px;font-size:12.5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" class="svg-icon-14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Kembali ke Riwayat</span>
                    </a>
                </div>

                {{-- Petunjuk / Catatan Sistem --}}
                <div style="margin-top:14px;padding:10px 12px;border-radius:10px;background:var(--bg3);border:1px solid var(--border2);display:flex;gap:8px;align-items:flex-start;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" class="svg-icon-16" style="color:var(--muted);margin-top:1px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p style="font-size:11px;color:var(--muted);line-height:1.5;margin:0;">
                        Setelah diajukan, permohonan peminjaman akan otomatis diteruskan ke guru pembimbing untuk diverifikasi.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- MODAL EDIT ITEM BARANG (FITUR 1) --}}
<div id="editCartModal" class="cart-modal-backdrop">
    <div class="cart-modal-box">
        <div class="cart-card-header" style="margin: 0; padding: 18px 20px; background: var(--card);">
            <div class="cart-card-title" style="font-size: 15px;">
                <div class="cart-card-icon" style="width: 30px; height: 30px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" class="svg-icon-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </div>
                <span>Edit Barang di Keranjang</span>
            </div>
            <button type="button" onclick="closeEditModal()" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:22px;line-height:1;padding:4px;" title="Tutup">&times;</button>
        </div>

        <form id="editCartForm" method="POST" action="" style="padding: 20px;">
            @csrf
            <div style="margin-bottom: 16px; padding: 12px 14px; border-radius: 10px; background: var(--card2); border: 1px solid var(--border2);">
                <div id="editItemName" style="font-weight: 700; font-size: 14px; color: var(--text);"></div>
                <div id="editItemStock" style="font-size: 11.5px; color: var(--muted); margin-top: 3px;"></div>
            </div>

            <div class="cart-field-group">
                <label for="editQuantity" class="cart-label">
                    <span>Jumlah Unit</span>
                    <span style="color:var(--s-rejected);font-weight:700;">*</span>
                </label>
                <input type="number" 
                       id="editQuantity" 
                       name="quantity" 
                       min="1" 
                       required 
                       class="cart-input" 
                       style="font-size: 15px; font-weight: 600;">
                <span id="editStockWarning" style="color: var(--s-rejected); font-size: 11.5px; margin-top: 4px; display: none;">Jumlah melebihi stok yang tersedia.</span>
            </div>

            <div class="cart-field-group">
                <label for="editPurpose" class="cart-label">
                    <span>Keterangan Khusus Barang</span>
                    <span style="color:var(--muted);font-weight:400;font-size:11px;">(Opsional)</span>
                </label>
                <input type="text" 
                       id="editPurpose" 
                       name="purpose" 
                       class="cart-input" 
                       placeholder="Contoh: untuk sesi praktikum jam ke-3...">
            </div>

            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; margin-top: 20px; padding-top: 14px; border-top: 1px solid var(--border2);">
                <button type="button" onclick="closeEditModal()" class="s-btn s-btn--secondary s-btn--sm">Batal</button>
                <button type="submit" id="btnSaveEdit" class="s-btn s-btn--primary s-btn--sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- POPUP / MODAL KONFIRMASI HAPUS ITEM (SISWA) --}}
<div id="deleteCartModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: var(--card); border: 1px solid var(--border2); border-radius: 18px; max-width: 420px; width: 100%; padding: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.25); text-align: center; animation: modalPop .2s cubic-bezier(.34,1.56,.64,1);">
        <div style="width: 52px; height: 52px; border-radius: 50%; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.25); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; color: #ef4444;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </div>
        <h3 style="font-family: var(--font-head); font-size: 17px; font-weight: 800; color: var(--text); margin-bottom: 8px;">Hapus Barang dari Keranjang?</h3>
        <p style="font-size: 13px; color: var(--muted); line-height: 1.5; margin-bottom: 22px;">
            Apakah Anda yakin ingin menghapus <strong id="deleteItemName" style="color: var(--text);"></strong> (<span id="deleteItemQty"></span> unit) dari daftar permohonan peminjaman?
        </p>
        <form id="deleteCartForm" method="POST" action="" style="margin: 0; display: flex; gap: 10px; justify-content: center;">
            @csrf
            <button type="button" onclick="closeDeleteModal()" class="s-btn s-btn--secondary s-btn--sm" style="min-width: 100px; justify-content: center;">Batal</button>
            <button type="submit" class="s-btn s-btn--danger s-btn--sm" style="min-width: 110px; justify-content: center;">Ya, Hapus</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Media query toggle for mobile buttons
    function checkMobile() {
        const wrap = document.getElementById('mobileActionWrap');
        if (wrap) {
            wrap.style.display = window.innerWidth <= 1024 ? 'block' : 'none';
        }
    }
    window.addEventListener('resize', checkMobile);
    checkMobile();

    // 2. Date & Time interactive sync with summary card
    const borrowInput = document.getElementById('borrow_date');
    const returnInput = document.getElementById('return_date');
    const timeInput = document.getElementById('return_time');

    const summaryBorrow = document.getElementById('summary_borrow_date');
    const summaryReturn = document.getElementById('summary_return_date');
    const summaryTime = document.getElementById('summary_return_time');

    function formatDate(dateStr) {
        if (!dateStr) return '-';
        const parts = dateStr.split('-');
        if (parts.length !== 3) return dateStr;
        const d = new Date(parts[0], parts[1] - 1, parts[2]);
        if (isNaN(d.getTime())) return dateStr;
        return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    }

    if (borrowInput && summaryBorrow) {
        borrowInput.addEventListener('change', function() {
            summaryBorrow.textContent = formatDate(this.value);
            if (returnInput && this.value) {
                returnInput.min = this.value;
                if (returnInput.value && returnInput.value < this.value) {
                    returnInput.value = this.value;
                    if (summaryReturn) summaryReturn.textContent = formatDate(this.value);
                }
            }
        });
        if (borrowInput.value) summaryBorrow.textContent = formatDate(borrowInput.value);
    }

    if (returnInput && summaryReturn) {
        returnInput.addEventListener('change', function() {
            summaryReturn.textContent = formatDate(this.value);
        });
        if (returnInput.value) summaryReturn.textContent = formatDate(returnInput.value);
    }

    if (timeInput && summaryTime) {
        timeInput.addEventListener('input', function() {
            summaryTime.textContent = (this.value || '14:00') + ' WIB';
        });
        if (timeInput.value) summaryTime.textContent = timeInput.value + ' WIB';
    }

    // 3. FITUR 2: Validasi Live Nomor WhatsApp Siswa
    const waInput = document.getElementById('whatsapp_number');
    const waMsg = document.getElementById('waValidationMsg');
    const waPattern = /^(\+?62|0)[0-9]{8,13}$/;

    if (waInput && waMsg) {
        function validateWa() {
            const val = waInput.value.trim();
            if (!val) {
                waMsg.style.display = 'none';
                waInput.style.borderColor = 'var(--border2)';
                return;
            }
            if (waPattern.test(val)) {
                waMsg.textContent = '✓ Format nomor valid';
                waMsg.style.color = 'var(--s-returned, #059669)';
                waMsg.style.display = 'inline';
                waInput.style.borderColor = 'var(--s-returned, #059669)';
            } else {
                waMsg.textContent = '✕ Harus 10-14 digit (08... atau 62...)';
                waMsg.style.color = 'var(--s-rejected, #dc2626)';
                waMsg.style.display = 'inline';
                waInput.style.borderColor = 'var(--s-rejected, #dc2626)';
            }
        }
        waInput.addEventListener('input', function() {
            // Sanitize: allow numbers and '+' at the start
            this.value = this.value.replace(/[^\d+]/g, '');
            validateWa();
        });
        if (waInput.value) validateWa();
    }
});

// 4. FITUR 1: Modal Edit Item Barang Logic
let currentMaxStock = 1;

function openEditModal(itemId, itemName, currentQty, maxStock, purpose) {
    const modal = document.getElementById('editCartModal');
    const form = document.getElementById('editCartForm');
    const nameEl = document.getElementById('editItemName');
    const stockEl = document.getElementById('editItemStock');
    const qtyInput = document.getElementById('editQuantity');
    const purposeInput = document.getElementById('editPurpose');
    const warnEl = document.getElementById('editStockWarning');

    currentMaxStock = maxStock || 999;
    form.action = "{{ url('siswa/peminjaman/keranjang/update') }}/" + itemId;
    nameEl.textContent = itemName;
    stockEl.textContent = 'Stok saat ini tersedia: ' + currentMaxStock + ' unit';
    qtyInput.value = currentQty;
    qtyInput.max = currentMaxStock;
    qtyInput.min = 1;
    purposeInput.value = purpose || '';
    warnEl.style.display = 'none';

    modal.style.display = 'flex';
    setTimeout(function() { qtyInput.focus(); }, 50);
}

function closeEditModal() {
    const modal = document.getElementById('editCartModal');
    if (modal) modal.style.display = 'none';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeEditModal();
});

document.getElementById('editCartModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});

// Quantity validator in edit modal
document.getElementById('editQuantity')?.addEventListener('input', function() {
    const val = parseInt(this.value) || 0;
    const warnEl = document.getElementById('editStockWarning');
    const saveBtn = document.getElementById('btnSaveEdit');
    if (val > currentMaxStock) {
        warnEl.style.display = 'block';
        warnEl.textContent = 'Jumlah melebihi stok yang tersedia (' + currentMaxStock + ' unit).';
        if (saveBtn) saveBtn.disabled = true;
    } else if (val < 1) {
        warnEl.style.display = 'block';
        warnEl.textContent = 'Jumlah minimal adalah 1 unit.';
        if (saveBtn) saveBtn.disabled = true;
    } else {
        warnEl.style.display = 'none';
        if (saveBtn) saveBtn.disabled = false;
    }
});

// 5. FITUR 2: Modal Konfirmasi Hapus Item Cart Logic
function openDeleteModal(itemId, itemName, itemQty) {
    const modal = document.getElementById('deleteCartModal');
    const form = document.getElementById('deleteCartForm');
    const nameEl = document.getElementById('deleteItemName');
    const qtyEl = document.getElementById('deleteItemQty');

    if (!modal || !form) return;
    form.action = "{{ url('siswa/peminjaman/keranjang/hapus') }}/" + itemId;
    if (nameEl) nameEl.textContent = itemName;
    if (qtyEl) qtyEl.textContent = itemQty;

    modal.style.display = 'flex';
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteCartModal');
    if (modal) modal.style.display = 'none';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditModal();
        closeDeleteModal();
    }
});

document.getElementById('deleteCartModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
</script>
@endsection
