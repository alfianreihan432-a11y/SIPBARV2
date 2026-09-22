{{-- SIPBAR Favicon Meta Tags (Multi-size) --}}
{{-- v5: Transparent background, logo S+kubus biru gradasi --}}
{{-- Use custom favicon from settings if available, otherwise fallback to default --}}
@php
    $customFavicon = \App\Models\SiteSetting::get('site_favicon');
    $faviconUrl = $customFavicon ? asset($customFavicon) : asset('favicon.ico');
@endphp
<link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
<link rel="shortcut icon" href="{{ $faviconUrl }}">
<link rel="icon" type="image/png" sizes="48x48" href="{{ $customFavicon ? $faviconUrl : asset('favicon-48x48.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ $customFavicon ? $faviconUrl : asset('favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ $customFavicon ? $faviconUrl : asset('favicon-16x16.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ $customFavicon ? $faviconUrl : asset('apple-touch-icon.png') }}">
<link rel="manifest" href="{{ asset('site.webmanifest') }}">
