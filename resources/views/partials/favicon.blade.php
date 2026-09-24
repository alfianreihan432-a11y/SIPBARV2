{{-- SIPBAR Favicon Meta Tags (Multi-size, Circular) --}}
{{-- Favicon di-generate otomatis berbentuk lingkaran saat upload lewat Kelola Landing Page. --}}
{{-- Cache busting menggunakan ?v={timestamp} agar favicon baru langsung tampil di browser. --}}
@php
    $customFavicon    = \App\Models\SiteSetting::get('site_favicon');       // 180px circular PNG
    $favicon48        = \App\Models\SiteSetting::get('site_favicon_48');    // 48px
    $favicon32        = \App\Models\SiteSetting::get('site_favicon_32');    // 32px
    $favicon16        = \App\Models\SiteSetting::get('site_favicon_16');    // 16px
    $faviconVersion   = \App\Models\SiteSetting::get('site_favicon_version') ?? '1';

    // Bangun URL dengan query string versioning untuk cache busting
    $fav180Url = $customFavicon ? asset($customFavicon) . '?v=' . $faviconVersion : asset('apple-touch-icon.png');
    $fav48Url  = $favicon48    ? asset($favicon48)      . '?v=' . $faviconVersion : asset('favicon-48x48.png');
    $fav32Url  = $favicon32    ? asset($favicon32)      . '?v=' . $faviconVersion : asset('favicon-32x32.png');
    $fav16Url  = $favicon16    ? asset($favicon16)      . '?v=' . $faviconVersion : asset('favicon-16x16.png');
@endphp
<link rel="icon" type="image/png" sizes="180x180" href="{{ $fav180Url }}">
<link rel="shortcut icon" href="{{ $fav180Url }}">
<link rel="icon" type="image/png" sizes="48x48" href="{{ $fav48Url }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ $fav32Url }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ $fav16Url }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ $fav180Url }}">
<link rel="manifest" href="{{ asset('site.webmanifest') }}">
