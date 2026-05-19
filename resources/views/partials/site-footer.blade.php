@php
    $footerExtraClass = trim((string) ($footerExtraClass ?? ''));
    $footerHideBranding = (bool) ($footerHideBranding ?? false);
@endphp
<footer class="relative mt-auto overflow-hidden bg-gradient-to-b from-church-navy via-[#0f1f38] to-[#070f1a] text-white{{ $footerExtraClass !== '' ? ' '.$footerExtraClass : '' }}">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_80%_50%_at_50%_0%,rgba(232,185,35,0.08),transparent_55%)]" aria-hidden="true"></div>
    <div class="relative mx-auto max-w-6xl px-4 pb-10 pt-8 sm:px-6 sm:pb-12 sm:pt-10">
        @unless ($footerHideBranding)
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
        @endunless

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
