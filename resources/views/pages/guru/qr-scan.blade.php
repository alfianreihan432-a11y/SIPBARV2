@extends('layouts.guru')

@section('title', 'Scanner QR Code')
@section('page-heading', 'Scanner QR Code')

@section('content')
<style>
    .page-header {
        background: var(--card);
        border: 1px solid var(--border2);
        border-radius: 14px;
        padding: 24px;
        margin-bottom: 20px;
    }
    .page-header-content {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    @media (min-width: 768px) {
        .page-header-content {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }
    .page-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 4px;
    }
    .page-subtitle {
        font-size: 14px;
        color: var(--muted);
    }
    
    .scanner-container {
        background: var(--card);
        border: 1px solid var(--border2);
        border-radius: 14px;
        padding: 24px;
    }
</style>

<div>
    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-header-content">
            <div>
                <h1 class="page-title">Scanner QR Code</h1>
                <p class="page-subtitle">Scan QR code untuk memverifikasi pengembalian barang</p>
            </div>
        </div>
    </div>

    {{-- Scanner Component --}}
    <div class="scanner-container">
        <livewire:q-r-scanner />
    </div>
</div>
@endsection
