<!DOCTYPE html>
<html lang="id" class="h-full min-w-0 scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('title', 'Admin') — {{ $churchNameLine2 }}</title>
    @php
        $__favRaw = isset($siteLogoUrl) ? trim((string) $siteLogoUrl) : '';
        $__favSrc = $__favRaw !== '' ? \App\Support\PublicCmsUrl::imagePreviewSrc($__favRaw) : null;
        $__favBust = $__favRaw !== '' ? '?v='.substr(md5($__favRaw), 0, 12) : '';
    @endphp
    @if ($__favSrc)
        <link rel="icon" href="{{ $__favSrc }}{{ $__favBust }}" sizes="any">
        <link rel="apple-touch-icon" href="{{ $__favSrc }}{{ $__favBust }}">
    @else
        <link rel="icon" href="data:," sizes="any">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    @include('partials.app-styles')
    @include('partials.font-awesome')
    @stack('head')
    {{-- Gaya kritis: isi viewport + baris grid meski public/css belum di-build ulang (min-h-dvh / grid arbitrary hilang) --}}
    <style>
        html {
            height: 100%;
        }
        @media (max-width: 767.98px) {
            html:has(body.admin-layout-root) {
                height: auto;
                min-height: 100%;
                min-height: 100dvh;
                width: 100%;
                max-width: 100%;
                overflow-x: clip;
            }
            body.admin-layout-root {
                width: 100%;
                max-width: 100%;
                overflow-x: clip;
            }
            body.admin-layout-root > .admin-layout-shell,
            body.admin-layout-root .admin-layout-main,
            body.admin-layout-root main.admin-main,
            body.admin-layout-root .admin-main-body {
                width: 100%;
                max-width: 100%;
                min-width: 0;
            }
        }
        body.admin-layout-root {
            min-height: 100vh;
            min-height: 100dvh;
            display: grid;
            grid-template-rows: auto auto;
            overflow-x: clip;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }
        @media (min-width: 768px) {
            html:has(body.admin-layout-root) {
                height: 100%;
                overflow: hidden;
            }

            body.admin-layout-root {
                height: 100dvh;
                max-height: 100dvh;
                grid-template-rows: minmax(0, 1fr);
                overflow: hidden;
            }

            body.admin-layout-root > .admin-layout-shell {
                height: 100%;
                max-height: 100dvh;
                overflow: hidden;
            }

            body.admin-layout-root .admin-layout-main {
                min-height: 0;
                overflow-x: clip;
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
                overscroll-behavior: contain;
            }

            body.admin-layout-root > .admin-layout-shell > .admin-sidebar {
                position: sticky;
                top: 0;
                align-self: flex-start;
                height: 100dvh;
                max-height: 100dvh;
            }
        }

        body.admin-layout-root > .admin-layout-shell {
            min-height: 0;
        }

        /* Input gelap konsisten — hindari latar putih/biru autofill browser */
        body.admin-layout-root {
            color-scheme: dark;
        }

        body.admin-layout-root input:not([type='checkbox']):not([type='radio']):not([type='file']):not([type='hidden']):not([type='submit']):not([type='button']),
        body.admin-layout-root select,
        body.admin-layout-root textarea {
            background-color: #0a1524 !important;
            color: #e2e8f0 !important;
            caret-color: #e2e8f0;
        }

        body.admin-layout-root input:not([type='checkbox']):not([type='radio']):not([type='file']):not([type='hidden']):not([type='submit']):not([type='button']):-webkit-autofill,
        body.admin-layout-root textarea:-webkit-autofill,
        body.admin-layout-root select:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000px #0a1524 inset !important;
            box-shadow: 0 0 0 1000px #0a1524 inset !important;
            -webkit-text-fill-color: #e2e8f0 !important;
        }
    </style>
    @yield('extra-styles')
</head>
<body class="site-interactive-root admin-layout-root grid min-h-dvh grid-rows-[auto_auto] overflow-x-clip overflow-y-auto bg-gradient-to-br from-church-void via-church-bg to-church-surface font-sans text-slate-200 antialiased md:h-dvh md:max-h-dvh md:grid-rows-[minmax(0,1fr)] md:overflow-hidden">
    {{-- Header hanya mobile: desktop memakai sidebar saja --}}
    <header class="sticky top-0 z-40 isolate overflow-visible shadow-xl shadow-black/40 ring-1 ring-white/5 md:hidden">
        <div class="relative overflow-visible border-t-[3px] border-church-gold text-white">
            <div class="pointer-events-none absolute inset-0 z-0 bg-gradient-to-r from-church-navy/88 via-church-navy/80 to-church-navy-mid/88" aria-hidden="true"></div>
            <div class="pointer-events-none absolute inset-0 z-0 bg-[radial-gradient(ellipse_100%_80%_at_50%_-20%,rgba(232,185,35,0.1),transparent_50%)]" aria-hidden="true"></div>

            {{-- Satu baris mobile: logo + judul + kotak nav sejajar; panel absolute ke lebar baris header --}}
            <div class="relative z-10 flex w-full min-w-0 max-w-full flex-nowrap items-center gap-2 px-3 py-3 max-[380px]:gap-1.5 sm:gap-4 sm:px-5 sm:py-4 md:mx-auto md:max-w-6xl md:gap-5">
                <div class="flex min-h-0 min-w-0 flex-1 items-center gap-2 sm:gap-5">
                    <a href="{{ route('dashboard.index') }}" class="shrink-0 rounded-xl outline-none transition focus-visible:ring-2 focus-visible:ring-church-gold focus-visible:ring-offset-2 focus-visible:ring-offset-church-navy sm:rounded-2xl" aria-label="Dashboard">
                        <div class="flex size-11 items-center justify-center rounded-xl border border-white/25 bg-gradient-to-br from-white/15 to-white/5 shadow-lg shadow-black/20 ring-1 ring-church-gold/35 backdrop-blur-sm sm:size-14 sm:rounded-2xl md:size-16">
                            @include('partials.site-logo-or-mark')
                        </div>
                    </a>
                    <div class="min-w-0 flex-1 self-center">
                        <p class="break-words text-[0.65rem] font-semibold uppercase leading-snug tracking-[0.12em] text-church-gold/90 sm:text-xs sm:tracking-[0.18em]">{{ $churchNameLine1 }}</p>
                        <p class="mt-0.5 break-words font-script text-sm leading-snug text-white max-[380px]:text-[0.9rem] sm:text-xl md:text-2xl">{{ $churchNameLine2 }}</p>
                        <p class="mt-1 hidden text-xs text-white/60 sm:block">Panel pengurus — kelola konten &amp; pendaftaran</p>
                    </div>
                </div>

                <div class="z-[60] shrink-0 self-center md:hidden">
                    <details data-nav-disclosure class="group">
                    <summary class="public-btn-hover flex cursor-pointer list-none items-center gap-1 rounded-lg border border-white/35 bg-white/10 px-2 py-1.5 text-white outline-none backdrop-blur-sm focus-visible:ring-offset-2 focus-visible:ring-offset-church-navy max-[380px]:px-1.5 sm:gap-1.5 sm:rounded-xl sm:px-2.5 sm:py-2 [&::-webkit-details-marker]:hidden">
                        <span class="sr-only">Buka atau tutup menu admin</span>
                        <svg class="size-5 shrink-0 sm:size-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg class="size-4 shrink-0 opacity-80 transition group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </summary>
                    <div class="public-card-hover fixed left-[max(0.75rem,env(safe-area-inset-left,0px))] right-[max(0.75rem,env(safe-area-inset-right,0px))] top-[calc(4.75rem+env(safe-area-inset-top,0px))] z-[70] max-h-[min(72dvh,calc(100dvh-5.75rem-env(safe-area-inset-bottom,0px)))] overflow-x-hidden overflow-y-auto overscroll-contain break-words rounded-2xl border border-white/10 bg-church-card py-1 text-slate-100 ring-1 ring-church-gold/15 max-[380px]:top-[calc(4.5rem+env(safe-area-inset-top,0px))] sm:left-4 sm:right-4 sm:top-[calc(5.75rem+env(safe-area-inset-top,0px))] sm:max-h-[min(72dvh,calc(100dvh-6.5rem-env(safe-area-inset-bottom,0px)))]">
                        <div class="flex items-center gap-3 border-b border-white/10 px-3 py-3">
                            <div class="flex size-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-church-gold/90 to-amber-500 text-sm font-bold text-church-navy shadow-md">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                            <div class="min-w-0">
                                <p class="break-words text-sm font-semibold leading-snug text-white">{{ auth()->user()->name }}</p>
                                <p class="mt-0.5 break-all text-xs leading-snug text-white/65">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                        @include('partials.admin-nav-menu', ['compact' => true])
                        <div class="border-t border-white/10 p-2">
                            <form method="post" action="{{ route('logout') }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="public-btn-hover flex w-full items-center justify-center rounded-lg border border-white/25 bg-white/10 px-3 py-2.5 text-sm font-semibold text-white backdrop-blur-sm focus-visible:ring-offset-2 focus-visible:ring-offset-church-card"
                                >
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </details>
                </div>
            </div>
        </div>
    </header>

    <div class="admin-layout-shell flex min-h-0 w-full max-w-full min-w-0 items-stretch max-md:h-auto md:h-full md:max-h-dvh md:items-stretch md:overflow-hidden">
        <aside class="admin-sidebar hidden w-64 shrink-0 flex-col overflow-hidden text-white shadow-xl md:sticky md:top-0 md:z-30 md:flex md:h-dvh md:max-h-dvh md:min-h-0 md:self-start">
            <div class="admin-sidebar__inner relative z-[1] flex min-h-0 flex-1 flex-col">
            <div class="admin-sidebar__profile flex shrink-0 items-center gap-3 border-b border-white/10 p-4">
                <div class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-church-gold/15 text-sm font-bold text-church-gold ring-1 ring-church-gold/25">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="min-w-0">
                    <p class="break-words text-sm font-semibold leading-snug text-church-fg">{{ auth()->user()->name }}</p>
                    <p class="mt-0.5 break-all text-xs leading-snug text-slate-400">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <div class="admin-sidebar__nav min-h-0 flex-1 overflow-x-hidden overflow-y-auto overscroll-contain">
                @include('partials.admin-nav-menu', ['compact' => false])
            </div>
            <div class="admin-sidebar__footer shrink-0 space-y-2 border-t border-white/10 p-3">
                <a
                    href="{{ route('home') }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="admin-sidebar__footer-btn public-btn-hover flex w-full items-center justify-center rounded-xl border border-white/15 bg-church-surface/50 px-3 py-2 text-sm font-medium text-church-gold focus-visible:ring-offset-2 focus-visible:ring-offset-church-card"
                >
                    Lihat situs publik
                </a>
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="admin-sidebar__footer-btn public-btn-hover flex w-full items-center justify-center rounded-xl border border-white/15 bg-church-surface/80 px-3 py-2.5 text-sm font-semibold text-slate-200 focus-visible:ring-offset-2 focus-visible:ring-offset-church-card"
                    >
                        Keluar
                    </button>
                </form>
            </div>
            </div>
        </aside>

        <div class="admin-layout-main flex min-h-0 min-w-0 w-full max-w-full flex-1 basis-0 flex-col bg-gradient-to-br from-church-bg/90 via-church-surface/95 to-church-bg/90 max-md:h-auto md:min-h-0 md:overflow-x-clip md:overflow-y-auto md:overscroll-contain">
            @include('admin.partials.main-body')

            <footer class="admin-layout-footer mt-auto w-full min-w-0 shrink-0 border-t border-white/10 bg-gradient-to-r from-church-navy via-church-gold/10 to-church-navy-mid pb-[env(safe-area-inset-bottom,0px)] text-white shadow-inner max-md:shrink-0">
                <div class="mx-auto grid w-full max-w-full min-w-0 gap-6 px-4 py-8 text-sm sm:gap-8 sm:px-6 sm:py-10 md:grid-cols-3 md:gap-10 md:px-10 md:py-12 lg:px-14 lg:py-14">
                    <div>
                        <h3 class="mb-3 border-b border-white/25 pb-2 font-serif font-semibold text-church-gold">Kontak Gereja</h3>
                        <p class="text-white/90">&#9742; {{ $churchPhone }}</p>
                        <p class="mt-2 text-white/90">&#9993; {{ $churchEmail }}</p>
                    </div>
                    <div>
                        <h3 class="mb-3 border-b border-white/25 pb-2 font-serif font-semibold text-church-gold">Alamat Gereja</h3>
                        <p class="text-white/90">&#128205; {{ $churchAddress }}</p>
                    </div>
                    <div>
                        <h3 class="mb-3 border-b border-white/25 pb-2 font-serif font-semibold text-church-gold">Ikuti Kami</h3>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ $socialFacebook }}" class="flex size-9 items-center justify-center rounded-full bg-blue-700 text-xs transition   " aria-label="Facebook">f</a>
                            <a href="{{ $socialTwitter }}" class="flex size-9 items-center justify-center rounded-full bg-sky-500 text-xs transition   " aria-label="X">x</a>
                            <a href="{{ $socialInstagram }}" class="flex size-9 items-center justify-center rounded-full bg-gradient-to-br from-pink-500 to-amber-400 text-xs transition   " aria-label="Instagram">in</a>
                            <a href="{{ $socialYoutube }}" class="flex size-9 items-center justify-center rounded-full bg-red-600 text-xs transition   " aria-label="YouTube">▶</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    @include('partials.nav-disclosure-script')
    @include('admin.partials.confirm-dialog')
    @stack('scripts')
    @include('partials.flash-success-script')
</body>
</html>
