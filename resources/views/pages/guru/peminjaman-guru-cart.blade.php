@extends('layouts.guru')

@section('title', 'Keranjang Peminjaman Guru – SIPBAR')

@section('content')
<style>
    /* ═══════════════════════════════════════════════
       KERANJANG PEMINJAMAN GURU — GREEN/TEAL THEME
       Harmonized with SIPBAR Guru Layout & Tokens
    ═══════════════════════════════════════════════ */
    .cart-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 24px 20px 48px;
    }

    /* Page Header */
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .page-header-left {
        flex: 1;
        min-width: 260px;
    }
    .page-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 22px;
        font-weight: 800;
        color: var(--text, #0f172a);
        letter-spacing: -.02em;
    }
    .page-title-count {
        font-size: 12px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 999px;
        background: var(--accent-light, #ecfdf5);
        color: var(--accent-text, #047857);
        border: 1px solid rgba(16,185,129,.25);
    }
    .page-subtitle {
        font-size: 13.5px;
        color: var(--muted, #475569);
        margin-top: 4px;
    }

    /* 2-Column Responsive Layout */
    .cart-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.8fr) minmax(320px, 1fr);
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
        border: 1px solid var(--border, #e2e8f0);
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
        border-bottom: 1px solid var(--border, #e2e8f0);
    }

    .cart-card-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text, #0f172a);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .cart-card-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: var(--accent-light, #ecfdf5);
        color: var(--accent-text, #047857);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Item Card inside Cart */
    .cart-item-row {
        background: var(--bg3, #f8fafc);
        border: 1px solid var(--border2, #e2e8f0);
        border-radius: 12px;
        padding: 16px 18px;
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
        border-color: var(--accent, #10b981);
        box-shadow: 0 2px 8px rgba(16,185,129,.08);
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
            padding-top: 10px;
            border-top: 1px solid var(--border, #e2e8f0);
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
        background: var(--accent-light, #ecfdf5);
        color: var(--accent-text, #047857);
        border: 1px solid rgba(16,185,129,.25);
        margin-bottom: 5px;
    }

    .cart-item-name {
        font-size: 15px;
        font-weight: 700;
        color: var(--text, #0f172a);
        line-height: 1.35;
        word-break: break-word;
    }

    .cart-item-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 6px;
        font-size: 12px;
        color: var(--muted, #475569);
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
        color: var(--text, #0f172a);
        margin-bottom: 6px;
    }

    .cart-label svg {
        color: var(--accent-text, #047857);
        flex-shrink: 0;
    }

    .cart-input {
        width: 100%;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid var(--border, #e2e8f0);
        background: var(--bg, #f8fafc);
        color: var(--text, #0f172a);
        font-size: 13.5px;
        font-family: inherit;
        box-sizing: border-box;
        transition: border-color .15s, box-shadow .15s;
    }

    .cart-input:focus {
        outline: none;
        border-color: var(--accent, #10b981);
        box-shadow: 0 0 0 3px rgba(16,185,129,.18);
        background: var(--card, #ffffff);
    }

    .cart-input::placeholder {
        color: var(--subtle, #64748b);
    }

    /* Buttons */
    .guru-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-family: inherit;
        font-size: 13.5px;
        font-weight: 600;
        padding: 9px 18px;
        border-radius: 10px;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all .15s ease;
        text-decoration: none;
        box-sizing: border-box;
    }
    .guru-btn--primary {
        background: #059669;
        color: #ffffff;
        border-color: #059669;
    }
    .guru-btn--primary:hover {
        background: #047857;
        border-color: #047857;
        color: #ffffff;
    }
    .guru-btn--primary:disabled {
        opacity: .5;
        cursor: not-allowed;
    }
    .guru-btn--secondary {
        background: var(--bg3, #f1f5f9);
        color: var(--text2, #1e293b);
        border-color: var(--border, #e2e8f0);
    }
    .guru-btn--secondary:hover {
        background: var(--border, #e2e8f0);
        color: var(--text, #0f172a);
    }
    .guru-btn--sm {
        padding: 6px 12px;
        font-size: 12.5px;
        border-radius: 8px;
    }
    .guru-btn--danger-sm {
        font-size: 12px;
        padding: 6px 12px;
        border-radius: 8px;
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
        cursor: pointer;
    }
    .guru-btn--danger-sm:hover {
        background: #fee2e2;
        color: #b91c1c;
    }
    .guru-btn--edit-sm {
        font-size: 12px;
        padding: 6px 12px;
        border-radius: 8px;
        background: var(--card, #ffffff);
        color: var(--text2, #1e293b);
        border: 1px solid var(--border, #e2e8f0);
        cursor: pointer;
    }
    .guru-btn--edit-sm:hover {
        background: var(--accent-light, #ecfdf5);
        color: var(--accent-text, #047857);
        border-color: var(--accent, #10b981);
    }

    /* Summary Side Card */
    .cart-summary-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 13px;
        color: var(--muted, #475569);
    }

    .cart-summary-val {
        font-weight: 700;
        color: var(--text, #0f172a);
    }

    .cart-summary-divider {
        height: 1px;
        background: var(--border, #e2e8f0);
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
        border: 1px solid var(--border, #e2e8f0);
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

    /* Ensure SVGs render cleanly */
    svg {
        max-width: 100%;
        box-sizing: content-box;
    }
    .svg-icon-14 { width: 14px !important; height: 14px !important; min-width: 14px !important; }
    .svg-icon-16 { width: 16px !important; height: 16px !important; min-width: 16px !important; }
    .svg-icon-18 { width: 18px !important; height: 18px !important; min-width: 18px !important; }

    /* Dark mode picker support */
    input[type="date"], input[type="time"] { color-scheme: light; }
    html.dark input[type="date"], html.dark input[type="time"] { color-scheme: dark; }
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
@endphp

<div class="cart-container">
    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-header-left">
            <div class="page-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" style="width:22px;height:22px;color:var(--accent-text, #047857);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span>Keranjang Peminjaman Guru</span>
                @if($totalItems > 0)
                    <span class="page-title-count">{{ $totalItems }} jenis barang</span>
                @endif
            </div>
            <div class="page-subtitle">Review daftar barang dan lengkapi permohonan peminjaman inventaris sebelum diajukan.</div>
        </div>
        <a href="{{ route('teacher.barang') }}" class="guru-btn guru-btn--primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="svg-icon-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Barang</span>
        </a>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div style="background: var(--accent-light, #ecfdf5); border: 1px solid rgba(16,185,129,.3); color: var(--accent-text, #047857); border-radius: 12px; padding: 12px 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 500;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="svg-icon-18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div style="flex: 1;">{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; border-radius: 12px; padding: 12px 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 500;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="svg-icon-18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div style="flex: 1;">{{ session('error') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; border-radius: 12px; padding: 14px 18px; margin-bottom: 20px; font-size: 13px;">
            <div style="font-weight: 700; margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="svg-icon-16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>Mohon perbaiki kesalahan berikut:</span>
            </div>
            <ul style="margin: 0; padding-left: 22px; list-style-type: disc;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Main 2-Column Grid --}}
    <div class="cart-grid">
        {{-- Left Column: Cards --}}
        <div class="cart-main-col">
            {{-- CARD 1: Barang di Keranjang --}}
            <div class="cart-card">
                <div class="cart-card-header">
                    <div class="cart-card-title">
                        <div class="cart-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="svg-icon-18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <span>Barang di Keranjang</span>
                    </div>
                    @if(!empty($cartItems))
                        <span style="font-size: 12.5px; color: var(--muted, #475569); font-weight: 500;">
                            {{ $totalItems }} jenis · {{ $totalUnits }} total unit
                        </span>
                    @endif
                </div>

                @if(empty($cartItems))
                    <div style="border: 2px dashed var(--border, #e2e8f0); border-radius: 14px; padding: 48px 24px; text-align: center;">
                        <div style="width: 52px; height: 52px; border-radius: 50%; background: var(--bg3, #f1f5f9); display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; color: var(--subtle, #64748b);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="svg-icon-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div style="font-size: 15px; font-weight: 700; color: var(--text, #0f172a); margin-bottom: 4px;">Keranjang Masih Kosong</div>
                        <p style="font-size: 13px; color: var(--muted, #475569); max-width: 360px; margin: 0 auto 18px;">
                            Anda belum menambahkan barang apapun ke keranjang peminjaman. Jelajahi katalog barang untuk mulai meminjam.
                        </p>
                        <a href="{{ route('teacher.barang') }}" class="guru-btn guru-btn--primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="svg-icon-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Pilih Barang dari Katalog</span>
                        </a>
                    </div>
                @else
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        @foreach($cartItems as $entry)
                            @php
                                $item = $entry['item'];
                                $rawName = $item->name ?? 'Barang';
                                $categoryBadge = $item->category?->name ?? '';
                                $cleanName = $rawName;

                                // Sanitize duplicate/concatenated name strings (e.g., "ALAT RUMAH TANGGA.ALAT...MEJA KURSI - [ ]")
                                if (strpos($rawName, '.') !== false) {
                                    $parts = explode('.', $rawName);
                                    if (count($parts) >= 2) {
                                        if (empty($categoryBadge)) {
                                            $categoryBadge = trim($parts[0]);
                                        }
                                        $cleanName = trim($parts[count($parts) - 1]);
                                        $cleanName = preg_replace('/\s*-\s*\[.*?\]\s*$/', '', $cleanName);
                                        $cleanName = trim($cleanName);
                                    }
                                }

                                $displayTitle = \Illuminate\Support\Str::title(mb_strtolower($cleanName));
                                if ($categoryBadge) {
                                    $categoryBadge = \Illuminate\Support\Str::title(mb_strtolower($categoryBadge));
                                }
                                $itemCode = $item->kode_barang ?? $item->code ?? '-';
                                $availableStock = $item->stock ?? 0;
                            @endphp

                            <div class="cart-item-row">
                                <div class="cart-item-info">
                                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                        @if($categoryBadge)
                                            <span class="cart-item-badge">{{ $categoryBadge }}</span>
                                        @endif
                                        @if($itemCode && $itemCode !== '-')
                                            <span style="font-size: 11px; font-family: ui-monospace, monospace; color: var(--subtle, #64748b);">
                                                Kode: {{ $itemCode }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="cart-item-name">{{ $displayTitle }}</div>
                                    <div class="cart-item-meta">
                                        <div class="cart-item-meta-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" class="svg-icon-14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                            <span>Jumlah diajukan: <strong style="color: var(--text, #0f172a);">{{ $entry['quantity'] }} unit</strong></span>
                                        </div>
                                        <div class="cart-item-meta-item" style="color: var(--subtle, #64748b);">
                                            <span>·</span>
                                            <span>Sisa stok tersedia: {{ $availableStock }} unit</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="cart-item-actions">
                                    {{-- Edit Button --}}
                                    <button type="button" 
                                            onclick="openEditModal('{{ $item->id }}', '{{ addslashes($displayTitle) }}', {{ $entry['quantity'] }}, {{ $availableStock }})" 
                                            class="guru-btn--edit-sm"
                                            title="Ubah kuantitas barang">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" class="svg-icon-14" style="vertical-align: middle; margin-right: 3px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        Edit
                                    </button>

                                    {{-- Delete Button (Popup Modal) --}}
                                    <button type="button" 
                                            onclick="openDeleteModal('{{ $item->id }}', '{{ addslashes($displayTitle) }}', {{ $entry['quantity'] }})" 
                                            class="guru-btn--danger-sm" 
                                            title="Hapus dari keranjang">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" class="svg-icon-14" style="vertical-align: middle; margin-right: 3px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- CARD 2: Form Pengajuan Peminjaman --}}
            @if(!empty($cartItems))
                <div class="cart-card">
                    <div class="cart-card-header">
                        <div class="cart-card-title">
                            <div class="cart-card-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="svg-icon-18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <span>Rincian Permohonan Peminjaman</span>
                        </div>
                    </div>

                    <form id="teacher-borrow-form" method="POST" action="{{ route('teacher.peminjaman-guru.cart.submit') }}">
                        @csrf

                        <div class="cart-form-grid">
                            {{-- Kepala Jurusan --}}
                            <div class="cart-field-group" style="grid-column: 1 / -1;">
                                <label for="kepala_jurusan_id" class="cart-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" class="svg-icon-14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>Kepala Jurusan yang Dituju</span>
                                    <span style="color:#dc2626;font-weight:700;">*</span>
                                </label>
                                <select id="kepala_jurusan_id" name="kepala_jurusan_id" required class="cart-input">
                                    <option value="">-- Pilih Kepala Jurusan Penyetuju --</option>
                                    @foreach($kepalaJurusans as $kajur)
                                        <option value="{{ $kajur->id }}" {{ old('kepala_jurusan_id') == $kajur->id ? 'selected' : '' }}>
                                            {{ $kajur->name }} {{ $kajur->jurusan?->nama ? '(' . $kajur->jurusan->nama . ')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tanggal Pinjam --}}
                            <div class="cart-field-group">
                                <label for="borrow_date" class="cart-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" class="svg-icon-14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>Tanggal Pinjam</span>
                                    <span style="color:#dc2626;font-weight:700;">*</span>
                                </label>
                                <input type="date" 
                                       id="borrow_date" 
                                       name="borrow_date" 
                                       value="{{ old('borrow_date', $defaultBorrowDate) }}" 
                                       min="{{ now()->toDateString() }}" 
                                       required 
                                       class="cart-input">
                            </div>

                            {{-- Tanggal Kembali --}}
                            <div class="cart-field-group">
                                <label for="return_date" class="cart-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" class="svg-icon-14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>Tanggal Kembali</span>
                                    <span style="color:#dc2626;font-weight:700;">*</span>
                                </label>
                                <input type="date" 
                                       id="return_date" 
                                       name="return_date" 
                                       value="{{ old('return_date', $defaultReturnDate) }}" 
                                       min="{{ old('borrow_date', $defaultBorrowDate) }}" 
                                       required 
                                       class="cart-input">
                            </div>

                            {{-- Jam Pengembalian --}}
                            <div class="cart-field-group" style="grid-column: 1 / -1;">
                                <label for="return_time" class="cart-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" class="svg-icon-14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Batas Jam Pengembalian (WIB)</span>
                                    <span style="color:#dc2626;font-weight:700;">*</span>
                                </label>
                                <input type="time" 
                                       id="return_time" 
                                       name="return_time" 
                                       value="{{ old('return_time', $defaultReturnTime) }}" 
                                       required 
                                       class="cart-input">
                            </div>

                            {{-- Keperluan / Tujuan Peminjaman --}}
                            <div class="cart-field-group" style="grid-column: 1 / -1;">
                                <label for="purpose" class="cart-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" class="svg-icon-14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Tujuan / Keperluan Peminjaman</span>
                                    <span style="color:#dc2626;font-weight:700;">*</span>
                                </label>
                                <textarea id="purpose" 
                                          name="purpose" 
                                          rows="3" 
                                          required 
                                          class="cart-input" 
                                          placeholder="Contoh: Praktikum siswa kelas XI RPL untuk modul perakitan jaringan...">{{ old('purpose') }}</textarea>
                            </div>

                            {{-- Catatan Tambahan --}}
                            <div class="cart-field-group" style="grid-column: 1 / -1;">
                                <label for="notes" class="cart-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" class="svg-icon-14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                    </svg>
                                    <span>Catatan Tambahan</span>
                                    <span style="color:var(--subtle, #64748b);font-weight:400;font-size:11px;">(Opsional)</span>
                                </label>
                                <textarea id="notes" 
                                          name="notes" 
                                          rows="2" 
                                          class="cart-input" 
                                          placeholder="Tuliskan catatan khusus atau lokasi penempatan sementara jika diperlukan...">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>

        {{-- Right Column: Sticky Summary Card --}}
        <div class="cart-side-col">
            <div class="cart-card">
                <div class="cart-card-header">
                    <div class="cart-card-title">
                        <div class="cart-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="svg-icon-18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <span>Ringkasan Pengajuan</span>
                    </div>
                </div>

                <div class="cart-summary-row">
                    <span>Total Jenis Barang</span>
                    <span class="cart-summary-val">{{ $totalItems }} jenis</span>
                </div>
                <div class="cart-summary-row">
                    <span>Total Unit Barang</span>
                    <span class="cart-summary-val" style="color: var(--accent-text, #047857); font-size: 15px;">{{ $totalUnits }} unit</span>
                </div>

                <div class="cart-summary-divider"></div>

                <div class="cart-summary-row">
                    <span>Tanggal Pinjam</span>
                    <span id="summary_borrow_date" class="cart-summary-val">-</span>
                </div>
                <div class="cart-summary-row">
                    <span>Tanggal Kembali</span>
                    <span id="summary_return_date" class="cart-summary-val">-</span>
                </div>
                <div class="cart-summary-row">
                    <span>Batas Jam Kembali</span>
                    <span id="summary_return_time" class="cart-summary-val">-</span>
                </div>

                <div class="cart-summary-divider"></div>

                {{-- Action Buttons --}}
                <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 14px;">
                    <button type="submit" 
                            form="teacher-borrow-form" 
                            class="guru-btn guru-btn--primary" 
                            style="width: 100%; padding: 13px 18px; font-size: 14px;"
                            {{ empty($cartItems) ? 'disabled' : '' }}>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="svg-icon-18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Ajukan Permohonan</span>
                    </button>

                    <a href="{{ route('teacher.peminjaman-guru') }}" class="guru-btn guru-btn--secondary" style="width: 100%; text-align: center;">
                        <span>Kembali ke Riwayat</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT KUANTITAS BARANG (GURU) --}}
<div id="editCartModal" class="cart-modal-backdrop">
    <div class="cart-modal-box">
        <div class="cart-card-header" style="margin: 0; padding: 18px 20px; background: var(--card, #ffffff);">
            <div class="cart-card-title" style="font-size: 15px;">
                <div class="cart-card-icon" style="width: 32px; height: 32px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" class="svg-icon-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </div>
                <span>Ubah Kuantitas Barang</span>
            </div>
            <button type="button" onclick="closeEditModal()" style="background:none;border:none;color:var(--muted, #475569);cursor:pointer;font-size:22px;line-height:1;padding:4px;" title="Tutup">&times;</button>
        </div>

        <form id="editCartForm" method="POST" action="" style="padding: 20px;">
            @csrf
            <div style="margin-bottom: 16px; padding: 12px 14px; border-radius: 10px; background: var(--bg3, #f1f5f9); border: 1px solid var(--border, #e2e8f0);">
                <div id="editItemName" style="font-weight: 700; font-size: 14px; color: var(--text, #0f172a);"></div>
                <div id="editItemStock" style="font-size: 12px; color: var(--muted, #475569); margin-top: 4px;"></div>
            </div>

            <div class="cart-field-group">
                <label for="editQuantity" class="cart-label">
                    <span>Jumlah Unit</span>
                    <span style="color:#dc2626;font-weight:700;">*</span>
                </label>
                <input type="number" 
                       id="editQuantity" 
                       name="quantity" 
                       min="1" 
                       required 
                       class="cart-input" 
                       style="font-size: 15px; font-weight: 700;">
                <span id="editStockWarning" style="color: #dc2626; font-size: 12px; margin-top: 5px; display: none;"></span>
            </div>

            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; margin-top: 20px; padding-top: 14px; border-top: 1px solid var(--border, #e2e8f0);">
                <button type="button" onclick="closeEditModal()" class="guru-btn guru-btn--secondary guru-btn--sm">Batal</button>
                <button type="submit" id="btnSaveEdit" class="guru-btn guru-btn--primary guru-btn--sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- POPUP / MODAL KONFIRMASI HAPUS ITEM (GURU) --}}
<div id="deleteCartModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: var(--card, #ffffff); border: 1px solid var(--border2, #e2e8f0); border-radius: 18px; max-width: 420px; width: 100%; padding: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.25); text-align: center; animation: modalPop .2s cubic-bezier(.34,1.56,.64,1);">
        <div style="width: 52px; height: 52px; border-radius: 50%; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.25); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; color: #ef4444;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </div>
        <h3 style="font-size: 17px; font-weight: 800; color: var(--text, #0f172a); margin-bottom: 8px;">Hapus Barang dari Keranjang?</h3>
        <p style="font-size: 13px; color: var(--muted, #475569); line-height: 1.5; margin-bottom: 22px;">
            Apakah Anda yakin ingin menghapus <strong id="deleteItemName" style="color: var(--text, #0f172a);"></strong> (<span id="deleteItemQty"></span> unit) dari daftar peminjaman guru?
        </p>
        <form id="deleteCartForm" method="POST" action="" style="margin: 0; display: flex; gap: 10px; justify-content: center;">
            @csrf
            <button type="button" onclick="closeDeleteModal()" class="guru-btn guru-btn--secondary guru-btn--sm" style="min-width: 100px; justify-content: center;">Batal</button>
            <button type="submit" class="guru-btn guru-btn--danger guru-btn--sm" style="min-width: 110px; justify-content: center; background: #dc2626; color: #fff;">Ya, Hapus</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Date & Time interactive sync with summary card
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
});

// 2. Modal Edit Item Barang Logic
let currentMaxStock = 1;

function openEditModal(itemId, itemName, currentQty, maxStock) {
    const modal = document.getElementById('editCartModal');
    const form = document.getElementById('editCartForm');
    const nameEl = document.getElementById('editItemName');
    const stockEl = document.getElementById('editItemStock');
    const qtyInput = document.getElementById('editQuantity');
    const warnEl = document.getElementById('editStockWarning');

    currentMaxStock = maxStock || 999;
    form.action = "{{ url('guru/peminjaman-guru/keranjang/update') }}/" + itemId;
    nameEl.textContent = itemName;
    stockEl.textContent = 'Stok saat ini tersedia: ' + currentMaxStock + ' unit';
    qtyInput.value = currentQty;
    qtyInput.max = currentMaxStock;
    qtyInput.min = 1;
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

// 3. FITUR 2: Modal Konfirmasi Hapus Item Cart Logic (Guru)
function openDeleteModal(itemId, itemName, itemQty) {
    const modal = document.getElementById('deleteCartModal');
    const form = document.getElementById('deleteCartForm');
    const nameEl = document.getElementById('deleteItemName');
    const qtyEl = document.getElementById('deleteItemQty');

    if (!modal || !form) return;
    form.action = "{{ url('guru/peminjaman-guru/keranjang/hapus') }}/" + itemId;
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
