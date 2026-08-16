@extends('layouts.guru')

@section('title', 'Scanner QR Code')
@section('page-heading', 'Scanner QR Code')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Scanner QR Code</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Scan QR code untuk memverifikasi pengembalian barang</p>
            </div>
        </div>
    </div>

    {{-- Scanner Component --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <livewire:q-r-scanner />
    </div>
</div>
@endsection
