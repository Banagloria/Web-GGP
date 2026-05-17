<!DOCTYPE html>
<html lang="id" class="min-w-0 scroll-smooth bg-church-void">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Gereja Gerakan Pantekosta Syalom Timika')</title>
    @php
        $__favRaw = isset($siteLogoUrl) ? trim((string) $siteLogoUrl) : '';
        $__favSrc = $__favRaw !== '' ? \App\Support\PublicCmsUrl::imagePreviewSrc($__favRaw) : null;
        $__favBust = $__favRaw !== '' ? '?v='.substr(md5($__favRaw), 0, 12) : '';
    @endphp
    @if ($__favSrc)
        <link rel="icon" href="{{ $__favSrc }}{{ $__favBust }}" sizes="any">
        <link rel="apple-touch-icon" href="{{ $__favSrc }}{{ $__favBust }}">
        <meta property="og:image" content="{{ $__favSrc }}">
    @else
        <link rel="icon" href="data:," sizes="any">
    @endif
    @stack('meta')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    @include('partials.app-styles')
    @include('partials.font-awesome')
</head>
<body class="flex min-h-screen min-w-0 flex-col overflow-x-hidden overflow-y-auto bg-gradient-to-b from-church-void via-church-bg to-church-surface font-sans text-slate-200 antialiased selection:bg-church-gold/30 selection:text-church-navy">
    @php
        $cmsBeranda = $cmsBeranda ?? [];
    @endphp
 <a href="#konten-utama" class="public-btn-hover sr-only inline-flex items-center gap-2 focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[100] focus:rounded-lg focus:bg-church-gold focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-church-navy focus:focus:outline-none focus:ring-2 focus:ring-white/80">
        @include('partials.cms-page-icon', ['cms' => $cmsBeranda, 'pageKey' => 'beranda', 'iconKey' => 'layout_skip_link', 'extraClasses' => 'text-xs'])
        Langsung ke isi halaman
    </a>

    @php
        $navItems = $cmsPublicNav ?? [];
    @endphp

    <header class="sticky top-0 z-40 isolate overflow-visible shadow-xl shadow-black/40 ring-1 ring-white/5">
        <div class="relative overflow-visible border-t-[3px] border-church-gold text-white">
            {{-- Latar solid agar konten di bawah tidak tembus saat scroll --}}
            <div class="pointer-events-none absolute inset-0 z-0 bg-gradient-to-r from-church-navy via-church-navy to-church-navy-mid" aria-hidden="true"></div>
            <div class="pointer-events-none absolute inset-0 z-0 bg-[radial-gradient(ellipse_100%_80%_at_50%_-20%,rgba(232,185,35,0.1),transparent_50%)]" aria-hidden="true"></div>
            {{-- Satu baris mobile: logo + judul + kotak nav sejajar; panel absolute menempel ke lebar baris (bukan <details> sempit) --}}
            <div class="relative z-10 mx-auto flex w-full max-w-6xl flex-nowrap items-center gap-3 px-4 py-3.5 sm:gap-4 sm:py-5 md:gap-5">
                <div class="flex min-w-0 flex-1 items-center gap-3 sm:gap-5">
                    <a href="{{ route('home') }}" class="shrink-0 rounded-2xl outline-none transition hover:ring-2 hover:ring-church-gold/50 focus-visible:ring-2 focus-visible:ring-church-gold focus-visible:ring-offset-2 focus-visible:ring-offset-church-navy" aria-label="Beranda">
                        <div class="flex size-14 items-center justify-center rounded-2xl border border-white/25 bg-church-surface shadow-lg shadow-black/20 ring-1 ring-church-gold/35 sm:size-16">
                            @include('partials.site-logo-or-mark')
                        </div>
                    </a>
                    <div class="min-w-0 flex-1 self-center">
                        <p class="break-words text-[0.65rem] font-semibold uppercase leading-snug tracking-[0.12em] text-church-gold/90 sm:text-xs sm:tracking-[0.18em]">{{ $churchNameLine1 }}</p>
                        <p class="mt-0.5 break-words font-script text-base leading-snug text-white sm:text-xl md:text-2xl">{{ $churchNameLine2 }}</p>
                        <p class="mt-1 hidden items-center gap-2 text-xs text-white/60 sm:flex">
                            @include('partials.cms-page-icon', ['cms' => $cmsBeranda, 'pageKey' => 'beranda', 'iconKey' => 'layout_header_tagline', 'extraClasses' => 'shrink-0 text-church-gold/75'])
                            <span>{{ $cmsBeranda['header_tagline'] ?? 'Situs resmi jemaat — informasi ibadah & pelayanan' }}</span>
                        </p>
                    </div>
                </div>

                <div class="z-[60] shrink-0 self-center md:hidden">
                    <details data-nav-disclosure class="group">
                    <summary class="public-btn-hover flex cursor-pointer list-none items-center gap-1.5 rounded-xl border border-white/35 bg-church-surface px-2.5 py-2 text-white outline-none focus-visible:ring-offset-2 focus-visible:ring-offset-church-navy [&::-webkit-details-marker]:hidden">
                        <span class="sr-only">Buka atau tutup menu navigasi</span>
                        <svg class="size-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg class="size-4 shrink-0 opacity-80 transition group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </summary>
                    {{-- fixed + inset aman viewport: hindari terpotong oleh overflow-x-hidden body / lebar induk absolute --}}
                    <div class="fixed left-[max(0.75rem,env(safe-area-inset-left,0px))] right-[max(0.75rem,env(safe-area-inset-right,0px))] top-[calc(5.25rem+env(safe-area-inset-top,0px))] z-[70] max-h-[min(72dvh,calc(100dvh-6.5rem-env(safe-area-inset-bottom,0px)))] overflow-x-hidden overflow-y-auto overscroll-contain break-words rounded-2xl border border-white/10 bg-church-card py-1 shadow-2xl shadow-black/50 ring-1 ring-church-gold/15 sm:left-4 sm:right-4 sm:top-[calc(5.75rem+env(safe-area-inset-top,0px))]">
                        <nav aria-label="Menu utama (mobile)">
                            <ul class="divide-y divide-white/10">
                                @foreach ($navItems as $item)
                                    @php
                                        $__navChildActive = filled($item['children'] ?? null) && collect($item['children'])->contains(function ($c) {
                                            return filled($c['active'] ?? null);
                                        });
                                        $__navLinkActive = $item['active'] || $__navChildActive;
                                    @endphp
                                    <li>
                                        @if (filled($item['outline'] ?? null))
                                            <a
                                                href="{{ $item['url'] }}"
                                                class="public-btn-hover mx-3 my-2 flex min-h-[2.5rem] items-center justify-center rounded-lg border border-white/25 bg-church-surface px-3 py-2 text-center text-xs font-semibold text-white focus-visible:ring-offset-2 focus-visible:ring-offset-church-card sm:text-sm {{ $item['active'] ? 'ring-1 ring-church-gold/50' : '' }}"
                                            >
                                                <span class="inline-flex items-center gap-2">
                                                    <i class="{{ $item['icon'] ?? 'fa-solid fa-link' }} shrink-0 text-sm opacity-90" aria-hidden="true"></i>
                                                    {{ $item['label'] }}
                                                </span>
                                            </a>
                                        @else
                                            <a
                                                href="{{ $item['url'] }}"
                                                class="flex min-h-[2.5rem] items-start justify-between gap-2 px-3 py-2 text-[0.8125rem] font-medium leading-snug transition focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-church-gold/40 sm:min-h-0 sm:px-4 sm:text-[0.9375rem] {{ $__navLinkActive ? 'border-l-4 border-l-church-gold bg-church-surface text-church-gold' : 'border-l-4 border-l-transparent text-slate-200 active:bg-church-surface' }}"
                                            >
                                                <span class="flex min-w-0 flex-1 items-start gap-2 pr-1">
                                                    <i class="{{ $item['icon'] ?? 'fa-solid fa-link' }} mt-0.5 shrink-0 text-sm opacity-80" aria-hidden="true"></i>
                                                    <span class="min-w-0 flex-1 break-words">{{ $item['label'] }}</span>
                                                </span>
                                                @if ($__navLinkActive)
                                                    <span class="shrink-0 rounded-full bg-church-gold/25 px-2 py-0.5 text-[0.65rem] font-bold uppercase tracking-wide text-church-navy">Aktif</span>
                                                @else
                                                    @include('partials.cms-page-icon', ['cms' => $cmsBeranda, 'pageKey' => 'beranda', 'iconKey' => 'nav_mobile_chevron', 'extraClasses' => 'shrink-0 text-xs text-slate-500'])
                                                @endif
                                            </a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </nav>
                    </div>
                </details>
                </div>
            </div>
        </div>

        {{-- Desktop: bar nav — latar solid --}}
        <div class="relative hidden border-b border-white/10 bg-church-nav-bg md:block">
            <div class="pointer-events-none absolute inset-0 z-0 bg-church-nav-bg" aria-hidden="true"></div>
            <nav class="relative z-10 mx-auto max-w-6xl px-4 py-3 lg:px-6" aria-label="Menu utama">
                <ul class="flex flex-wrap items-center justify-center gap-1.5 lg:gap-2">
                    @foreach ($navItems as $item)
                        <li>
                            <a
                                href="{{ $item['url'] }}"
                                class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium tracking-tight transition duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-church-gold/60 focus-visible:ring-offset-2 focus-visible:ring-offset-church-nav-bg lg:px-5 lg:text-[0.9375rem] {{ $item['active'] ? 'bg-gradient-to-b from-church-gold to-church-gold-soft text-church-navy shadow-md ring-1 ring-church-gold/40' : 'text-slate-300 hover:bg-church-surface hover:text-church-gold hover:shadow-md hover:ring-1 hover:ring-church-gold/25' }}"
                            >
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </header>

    <main id="konten-utama" class="church-content-animate w-full min-w-0 max-w-full flex-1 scroll-mt-[calc(var(--church-site-header-sticky-top)+0.5rem)]" tabindex="-1">
        @yield('content')
    </main>

    <footer class="relative mt-auto overflow-hidden bg-gradient-to-b from-church-navy via-[#0f1f38] to-[#070f1a] text-white">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_80%_50%_at_50%_0%,rgba(232,185,35,0.08),transparent_55%)]" aria-hidden="true"></div>
        <div class="relative mx-auto max-w-6xl px-4 pb-10 pt-8 sm:px-6 sm:pb-12 sm:pt-10">
            <div class="mb-10 flex flex-col gap-4 border-b border-white/10 pb-10 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-church-gold/90">{{ $churchNameLine1 }}</p>
                    <p class="mt-1 font-serif text-2xl font-semibold text-white sm:text-3xl">{{ $churchNameLine2 }}</p>
                </div>
                <div class="flex flex-wrap gap-2 text-sm">
                    @foreach ($cmsBeranda['footer_quick_links'] ?? [] as $fl)
                        @php
                            $flRoute = $fl['route'] ?? '';
                            $flIcon = trim((string) ($fl['icon'] ?? ''));
                            $flIconClass = $flIcon !== ''
                                ? \App\Support\CmsIcon::displayClasses($flIcon, \App\Support\PublicNavIcon::forRouteRaw($flRoute))
                                : \App\Support\PublicNavIcon::forRouteRaw($flRoute);
                        @endphp
 <a href="{{ \App\Support\PublicCmsUrl::fromPathOrUrl($flRoute) }}" class="public-btn-hover inline-flex items-center gap-2 rounded-lg border border-white/15 bg-white/5 px-3 py-1.5 text-white/90 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-church-gold">
                            <i class="{{ $flIconClass }} text-xs text-church-gold/90" aria-hidden="true"></i>
                            {{ $fl['label'] ?? '' }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="grid gap-10 sm:grid-cols-3">
                <div>
                    <h3 class="mb-4 flex items-center gap-2 font-serif text-lg font-semibold tracking-wide text-church-gold">
                        @include('partials.cms-page-icon', ['cms' => $cmsBeranda, 'pageKey' => 'beranda', 'iconKey' => 'footer_contact_heading', 'extraClasses' => 'text-base opacity-95'])
                        {{ $cmsBeranda['footer_headings']['contact'] ?? 'Kontak' }}
                    </h3>
                    <p class="flex items-start gap-2 text-sm text-white/90">
                        @include('partials.cms-page-icon', ['cms' => $cmsBeranda, 'pageKey' => 'beranda', 'iconKey' => 'footer_phone_row', 'extraClasses' => 'mt-0.5 shrink-0 text-church-gold/70'])
                        <a href="tel:{{ preg_replace('/[^\d+]/', '', $churchPhone) }}" class="rounded underline-offset-2 transition hover:text-white hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-church-gold">{{ $churchPhone }}</a>
                    </p>
                    <p class="mt-3 flex items-start gap-2 text-sm text-white/90">
                        @include('partials.cms-page-icon', ['cms' => $cmsBeranda, 'pageKey' => 'beranda', 'iconKey' => 'footer_email_row', 'extraClasses' => 'mt-0.5 shrink-0 text-church-gold/70'])
                        <a href="mailto:{{ $churchEmail }}" class="break-all rounded underline-offset-2 transition hover:text-white hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-church-gold">{{ $churchEmail }}</a>
                    </p>
                </div>
                <div>
                    <h3 class="mb-4 flex items-center gap-2 font-serif text-lg font-semibold tracking-wide text-church-gold">
                        @include('partials.cms-page-icon', ['cms' => $cmsBeranda, 'pageKey' => 'beranda', 'iconKey' => 'footer_address_heading', 'extraClasses' => 'text-base opacity-95'])
                        {{ $cmsBeranda['footer_headings']['address'] ?? 'Alamat' }}
                    </h3>
                    <p class="flex items-start gap-2 text-sm leading-relaxed text-white/90">
                        @include('partials.cms-page-icon', ['cms' => $cmsBeranda, 'pageKey' => 'beranda', 'iconKey' => 'footer_map_pin_row', 'extraClasses' => 'mt-0.5 shrink-0 text-church-gold/70'])
                        <span>{{ $churchAddress }}</span>
                    </p>
                </div>
                <div>
                    <h3 class="mb-4 flex items-center gap-2 font-serif text-lg font-semibold tracking-wide text-church-gold">
                        @include('partials.cms-page-icon', ['cms' => $cmsBeranda, 'pageKey' => 'beranda', 'iconKey' => 'footer_social_heading', 'extraClasses' => 'text-base opacity-95'])
                        {{ $cmsBeranda['footer_headings']['social'] ?? 'Media sosial' }}
                    </h3>
                    <div class="flex flex-wrap gap-3">
                        @foreach ($cmsBeranda['footer_social_links'] ?? [] as $social)
                            @php
                                $socialUrl = trim((string) ($social['url'] ?? ''));
                            @endphp
                            @if ($socialUrl !== '' && $socialUrl !== '#')
                                <a
                                    href="{{ $socialUrl }}"
                                    class="flex size-11 items-center justify-center rounded-full bg-church-gold/15 text-lg text-church-gold shadow-lg shadow-church-gold/10 ring-1 ring-church-gold/30 transition hover:scale-110 hover:bg-church-gold/25 hover:text-church-gold-soft hover:ring-2 hover:ring-church-gold/50 focus:outline-none focus-visible:ring-2 focus-visible:ring-church-gold focus-visible:ring-offset-2 focus-visible:ring-offset-church-navy"
                                    aria-label="{{ $social['label'] ?? 'Media sosial' }}"
                                    @if (! str_starts_with($socialUrl, '/')) target="_blank" rel="noopener noreferrer" @endif
                                >
                                    <i class="{{ \App\Support\CmsIcon::displayClasses($social['icon'] ?? '', 'fa-brands fa-link') }}" aria-hidden="true"></i>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        @php
            $footerCopyrightRaw = (string) ($cmsBeranda['footer_copyright_text'] ?? '© {year} ' . ($churchNameLine2 ?? 'Syalom Timika'));
            $footerCopyrightText = str_replace('{year}', (string) date('Y'), $footerCopyrightRaw);
        @endphp
        <div class="relative border-t border-white/10 bg-black/20 py-4 text-center text-xs text-white/50">
            {{ $footerCopyrightText }}
        </div>
    </footer>
    @include('partials.nav-disclosure-script')
    @stack('overlays')
    @stack('scripts')
    @include('partials.flash-success-script')
</body>
</html>
