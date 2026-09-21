{{-- SIPBAR Favicon Meta Tags (Dynamic + Multi-size Fallback) --}}
{{-- v3: Regenerated with letterbox (proportional fit) from 375x666 source --}}
@php
    $favIconDynamic = $siteLogoLanding ?? (\App\Models\SiteSetting::get('site_logo_landing') ?: \App\Models\SiteSetting::get('site_logo', asset('favicon.ico')));
@endphp
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=3">
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=3">
<link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon-48x48.png') }}?v=3">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}?v=3">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}?v=3">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}?v=3">
<link rel="manifest" href="{{ asset('site.webmanifest') }}?v=3">
