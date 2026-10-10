@props([
    'logo' => null,
    'brandName' => 'SIPBAR',
    'brandSubtitle' => 'SMKN 1 BANGSRI',
])

@php
    $noiseFilterId = 'sipbar-footer-noise-'.\Illuminate\Support\Str::uuid();
    $logo = $logo ?: (\App\Models\SiteSetting::get('site_logo_landing') ?: \App\Models\SiteSetting::get('site_logo', '/logossmkn1.png'));
    $about = \App\Models\SiteSetting::get('footer_description', 'Sistem peminjaman barang berbasis web yang lebih efektif, efisien, dan transparan untuk sekolah.');
    $copyright = \App\Models\SiteSetting::get('footer_copyright', '© '.date('Y').' SIPBAR – Sistem Informasi Pengelolaan Barang. All rights reserved.');
    $copyright = preg_replace('/\b(?:19|20)\d{2}\b/', date('Y'), $copyright);
    $logoUrl = preg_match('/^https?:\/\//i', $logo) ? $logo : asset($logo);
@endphp

<footer id="kontak" class="sipbar-footer" aria-label="Informasi SIPBAR">
    <div class="sipbar-footer__container">
        <div class="sipbar-footer__layout">
        <section data-footer-reveal class="sipbar-footer__card sipbar-footer__brand-card relative overflow-hidden rounded-2xl bg-[#1d4ed8] text-white">
            <svg class="sipbar-footer__noise pointer-events-none absolute inset-0 mix-blend-multiply opacity-30" aria-hidden="true" focusable="false">
                <filter id="{{ $noiseFilterId }}" x="0" y="0" width="100%" height="100%">
                    <feTurbulence type="fractalNoise" baseFrequency="0.65" numOctaves="4" stitchTiles="stitch" />
                    <feColorMatrix type="saturate" values="0" />
                    <feComponentTransfer>
                        <feFuncA type="table" tableValues="0 0.2" />
                    </feComponentTransfer>
                </filter>
                <rect width="100%" height="100%" filter="url(#{{ $noiseFilterId }})" />
            </svg>

            <a href="{{ route('home') }}" class="sipbar-footer__brand-link relative z-10 inline-flex max-w-full items-center rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-blue-700">
                <img src="{{ $logoUrl }}" alt="Logo {{ $brandSubtitle ?: $brandName }}" class="h-12 w-12 shrink-0 rounded-full bg-white object-contain p-1">
                <span class="min-w-0">
                    <span class="block truncate text-lg font-extrabold">{{ $brandName }}</span>
                    @if($brandSubtitle)
                        <span class="block text-xs font-semibold uppercase text-white/80">{{ $brandSubtitle }}</span>
                    @endif
                </span>
            </a>

            <div class="sipbar-footer__brand-copy relative z-10 flex flex-col justify-end">
                @if($about)
                    <p class="sipbar-footer__about max-w-sm">{{ $about }}</p>
                @endif
                <p class="sipbar-footer__copyright">{{ $copyright }}</p>
            </div>
        </section>

        <section data-footer-reveal class="sipbar-footer__card sipbar-footer__links-card rounded-2xl border border-neutral-300 bg-white text-neutral-950 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
            <div class="sipbar-footer__links-grid">
                <div class="sipbar-footer__column">
                    <h2 class="sipbar-footer__heading">Navigasi</h2>
                    <ul class="sipbar-footer__list text-neutral-600 dark:text-neutral-300">
                        <li><a class="sipbar-footer-link" href="{{ route('home') }}#beranda">Beranda</a></li>
                        <li><a class="sipbar-footer-link" href="{{ route('home') }}#fitur">Fitur</a></li>
                        <li><a class="sipbar-footer-link" href="{{ route('home') }}#tentang">Tentang</a></li>
                        <li><a class="sipbar-footer-link" href="{{ route('faq') }}">FAQ</a></li>
                    </ul>
                </div>

                <div class="sipbar-footer__column">
                    <h2 class="sipbar-footer__heading">Peminjaman</h2>
                    <ul class="sipbar-footer__list text-neutral-600 dark:text-neutral-300">
                        @auth
                            @if(auth()->user()->hasRole('siswa'))
                                <li><a class="sipbar-footer-link" href="{{ route('student.catalog') }}">Katalog barang</a></li>
                                <li><a class="sipbar-footer-link" href="{{ route('student.history') }}">Riwayat</a></li>
                                <li><a class="sipbar-footer-link" href="{{ route('student.returns.index') }}">Pengembalian</a></li>
                            @elseif(auth()->user()->hasRole('guru'))
                                <li><a class="sipbar-footer-link" href="{{ route('teacher.barang') }}">Katalog barang</a></li>
                                <li><a class="sipbar-footer-link" href="{{ route('teacher.pengembalian-guru.history') }}">Riwayat</a></li>
                                <li><a class="sipbar-footer-link" href="{{ route('teacher.pengembalian-guru') }}">Pengembalian</a></li>
                            @elseif(auth()->user()->hasRole('kepala_jurusan'))
                                <li><a class="sipbar-footer-link" href="{{ route('kajur.history') }}">Riwayat</a></li>
                                <li><a class="sipbar-footer-link" href="{{ route('kajur.pending-returns') }}">Pengembalian</a></li>
                            @elseif(auth()->user()->hasAnyRole(['admin', 'superadmin']))
                                <li><a class="sipbar-footer-link" href="{{ route('inventory.index') }}">Inventaris</a></li>
                                <li><a class="sipbar-footer-link" href="{{ route('returns.index') }}">Pengembalian</a></li>
                            @else
                                <li><a class="sipbar-footer-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                            @endif
                        @else
                            <li><a class="sipbar-footer-link" href="{{ route('login') }}">Masuk untuk meminjam</a></li>
                        @endauth
                    </ul>
                </div>

                <div class="sipbar-footer__column">
                    <h2 class="sipbar-footer__heading">Akun</h2>
                    <ul class="sipbar-footer__list text-neutral-600 dark:text-neutral-300">
                        @guest
                            <li><a class="sipbar-footer-link" href="{{ route('login') }}">Masuk</a></li>
                            <li><a class="sipbar-footer-link" href="{{ route('register') }}">Daftar</a></li>
                        @else
                            <li><a class="sipbar-footer-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                        @endguest
                    </ul>
                </div>

                <div class="sipbar-footer__column">
                    <h2 class="sipbar-footer__heading">Bantuan</h2>
                    <ul class="sipbar-footer__list text-neutral-600 dark:text-neutral-300">
                        <li><a class="sipbar-footer-link" href="{{ route('faq') }}">Pusat Bantuan</a></li>
                        <li><a class="sipbar-footer-link" href="{{ route('home') }}#bantuan">Bantuan Singkat</a></li>
                    </ul>
                </div>
            </div>
        </section>
        </div>
    </div>
</footer>

<style>
    .sipbar-footer {
        padding: 48px 0;
    }

    .sipbar-footer .sipbar-footer__container {
        width: 100%;
        max-width: 72rem;
        margin: 0 auto;
        padding: 0 16px;
        box-sizing: border-box;
    }

    .sipbar-footer .sipbar-footer__layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        align-items: stretch;
        gap: 24px;
    }

    .sipbar-footer .sipbar-footer__card {
        min-width: 0;
        box-sizing: border-box;
        padding: 24px;
        border-radius: 16px;
    }

    .sipbar-footer .sipbar-footer__brand-card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 40px;
        background-color: #1d4ed8;
    }

    .sipbar-footer .sipbar-footer__noise {
        width: 100%;
        height: 100%;
    }

    .sipbar-footer .sipbar-footer__brand-link {
        gap: 12px;
        text-decoration: none;
    }

    .sipbar-footer .sipbar-footer__brand-link img {
        display: block;
        width: 48px;
        height: 48px;
        padding: 4px;
        box-sizing: border-box;
    }

    .sipbar-footer .sipbar-footer__brand-copy {
        gap: 24px;
    }

    .sipbar-footer .sipbar-footer__about {
        margin: 0;
        color: rgba(255, 255, 255, 0.9);
        font-size: 14px;
        font-weight: 400;
        line-height: 1.625;
    }

    .sipbar-footer .sipbar-footer__copyright {
        margin: 0;
        color: rgba(255, 255, 255, 0.74);
        font-size: 12px;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .sipbar-footer .sipbar-footer__links-card {
        display: flex;
        flex-direction: column;
        background-color: #ffffff;
        color: #171717;
    }

    .dark .sipbar-footer .sipbar-footer__links-card {
        border-color: #404040;
        background-color: #171717;
        color: #f5f5f5;
    }

    .sipbar-footer .sipbar-footer__links-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 32px;
    }

    .sipbar-footer .sipbar-footer__heading {
        margin: 0 0 16px;
        color: inherit;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.4;
    }

    .sipbar-footer .sipbar-footer__list {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin: 0;
        padding: 0;
        list-style: none;
        font-size: 14px;
        font-weight: 500;
        line-height: 1.5;
    }

    .sipbar-footer .sipbar-footer__list li {
        margin: 0;
        padding: 0;
    }

    .sipbar-footer-link {
        display: inline-flex;
        max-width: 100%;
        overflow-wrap: anywhere;
        border-radius: 3px;
        color: #525252;
        transition: color 180ms ease;
    }

    .dark .sipbar-footer .sipbar-footer-link {
        color: #d4d4d4;
    }

    .sipbar-footer-link:hover {
        color: #111827;
    }

    .dark .sipbar-footer-link:hover {
        color: #ffffff;
    }

    .sipbar-footer-link:focus-visible {
        outline: 2px solid currentColor;
        outline-offset: 3px;
    }

    @media (min-width: 640px) {
        .sipbar-footer .sipbar-footer__container {
            padding-right: 24px;
            padding-left: 24px;
        }
    }

    @media (min-width: 768px) {
        .sipbar-footer .sipbar-footer__links-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }

    @media (min-width: 1024px) {
        .sipbar-footer .sipbar-footer__layout {
            grid-template-columns: minmax(0, 1fr) minmax(0, 2fr);
        }
    }

    @media (prefers-reduced-motion: no-preference) {
        [data-footer-reveal].sipbar-footer-reveal {
            opacity: 0;
            transform: translateY(14px);
            transition: opacity 500ms ease, transform 500ms ease;
        }

        [data-footer-reveal].sipbar-footer-reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (prefers-reduced-motion: reduce) {
        [data-footer-reveal] {
            opacity: 1;
            transform: none;
            transition: none;
        }
    }
</style>

<script>
    (() => {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        const cards = document.querySelectorAll('[data-footer-reveal]');
        if (!('IntersectionObserver' in window)) {
            cards.forEach((card) => card.classList.add('is-visible'));
            return;
        }

        const observer = new IntersectionObserver((entries, currentObserver) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-visible');
                currentObserver.unobserve(entry.target);
            });
        }, { threshold: 0.12 });

        cards.forEach((card) => {
            card.classList.add('sipbar-footer-reveal');
            observer.observe(card);
        });
    })();
</script>