@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">QR Code Scanner</h1>
    <livewire:admin-qr-scanner />
</div>
@endsection
