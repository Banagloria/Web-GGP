@extends('layouts.public')

@section('title', 'Beranda')

@section('content')
    @include('partials.cms-page-content-styles')

    @php
        $hero = ! empty($cms['hero_image_url']) ? $cms['hero_image_url'] : 'https://images.unsplash.com/photo-1507692049790-de58290a4334?auto=format&fit=crop&w=1600&q=80';
    @endphp

    <div class="home-beranda-stack">
    {{-- Hero --}}
    <section class="home-hero-section relative isolate mb-16 flex min-h-[460px] items-end overflow-hidden sm:mb-20 sm:min-h-[560px] sm:items-center lg:mb-28">
        <div class="absolute inset-0 scale-105 bg-cover bg-center motion-reduce:scale-100" style="background-image: url('{{ e($hero) }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-church-navy/90 via-black/70 to-black/50"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_60%_at_70%_20%,rgba(232,197,71,0.14),transparent_55%)]"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-transparent to-black/20"></div>

        <div class="relative z-10 mx-auto w-full min-w-0 max-w-6xl px-4 pb-14 pt-20 sm:px-6 sm:py-24">
            <div class="max-w-3xl border-l-4 border-church-gold pl-5 sm:pl-7">
                <p class="mb-1 break-words font-script text-3xl text-white/95 drop-shadow sm:text-4xl md:text-5xl">{{ $cms['hero_script_top'] ?? '' }}</p>
                <h1 class="break-words font-serif text-4xl font-bold leading-[1.1] tracking-tight text-church-gold drop-shadow-lg sm:text-5xl md:text-6xl lg:text-7xl">
                    {{ $cms['hero_title_gold'] ?? '' }}
                </h1>
                <h2 class="mt-2 break-words font-serif text-3xl font-bold leading-tight tracking-tight text-white drop-shadow-md sm:text-4xl md:text-5xl lg:text-6xl">
                    {{ $cms['hero_title_white'] ?? '' }}
                </h2>
                <div class="my-6 flex items-center gap-3">
                    <span class="h-px flex-1 max-w-[4.5rem] rounded-full bg-gradient-to-r from-church-gold to-transparent"></span>
                    @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => 'beranda', 'iconKey' => 'hero_ornament', 'extraClasses' => 'text-lg text-church-gold/90'])
                    <span class="h-px flex-1 max-w-[4.5rem] rounded-full bg-gradient-to-l from-church-gold to-transparent"></span>
                </div>
                <p class="font-script text-2xl text-white/95 drop-shadow sm:text-3xl md:text-4xl">{{ $cms['hero_script_bottom'] ?? '' }}</p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                    @foreach ($cms['hero_buttons'] ?? [] as $btn)
                        @php
                            $href = \App\Support\PublicCmsUrl::fromPathOrUrl($btn['url'] ?? '#');
                            $style = $btn['style'] ?? 'primary';
                            $heroIconClasses = \App\Support\CmsIcon::toFontAwesome(
                                $btn['icon'] ?? '',
                                \App\Support\CmsIcon::heroButtonIconDefault($style)
                            );
                            $heroBtnIconExtra = $style === 'link' ? 'text-church-gold/90' : '';
                        @endphp
                        @if ($style === 'primary')
                            <a href="{{ $href }}" class="public-btn-hover inline-flex items-center justify-center gap-2 rounded-xl bg-church-gold px-5 py-3 text-sm font-semibold text-church-navy focus-visible:ring-offset-2 focus-visible:ring-offset-church-navy"><i class="{{ $heroIconClasses }} {{ $heroBtnIconExtra }}" aria-hidden="true"></i>{{ $btn['label'] ?? '' }}</a>
                        @elseif ($style === 'secondary')
                            <a href="{{ $href }}" class="public-btn-hover inline-flex items-center justify-center gap-2 rounded-xl border border-white/35 bg-white/10 px-5 py-3 text-sm font-semibold text-white backdrop-blur-sm focus-visible:ring-offset-2 focus-visible:ring-offset-transparent"><i class="{{ $heroIconClasses }} {{ $heroBtnIconExtra }}" aria-hidden="true"></i>{{ $btn['label'] ?? '' }}</a>
                        @else
                            <a href="{{ $href }}" class="inline-flex items-center justify-center gap-2 text-sm font-medium text-white/90 underline decoration-church-gold/80 underline-offset-4 transition hover:text-white sm:ml-1"><i class="{{ $heroIconClasses }} {{ $heroBtnIconExtra }}" aria-hidden="true"></i>{{ $btn['label'] ?? '' }}</a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Visi + ajakan --}}
    <section class="relative bg-gradient-to-b from-church-bg via-church-surface to-church-bg px-4 pb-16 sm:px-6 sm:pb-20">
        <div class="mx-auto max-w-6xl min-w-0">
            <div class="public-card-hover relative overflow-hidden rounded-2xl border border-white/10 bg-church-card ring-1 ring-church-gold/10 sm:rounded-3xl">
                <div class="pointer-events-none absolute -right-16 -top-16 size-48 rounded-full bg-church-gold/10 blur-3xl sm:size-64" aria-hidden="true"></div>
                <div class="pointer-events-none absolute -bottom-20 -left-10 size-56 rounded-full bg-church-forest/20 blur-3xl" aria-hidden="true"></div>
                <div class="relative grid gap-0 lg:grid-cols-[minmax(0,1fr)_280px]">
                    <div>
                        <div class="border-b border-white/10 bg-gradient-to-r from-church-navy via-church-forest/30 to-church-navy-mid px-5 py-4 shadow-inner sm:px-8 sm:py-5">
                            <h2 class="flex items-center gap-3 font-serif text-lg font-semibold tracking-wide text-white sm:text-xl">
                                <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-church-gold/20 text-base text-church-gold shadow-inner ring-1 ring-church-gold/35">
                                    @include('partials.cms-icon', ['value' => $cms['vision_icon'] ?? '', 'defaultFa' => 'fa-solid fa-cross'])
                                </span>
                                <span>{{ $cms['vision_title'] ?? '' }}</span>
                            </h2>
                        </div>
                        <div class="church-cms-body px-5 py-8 text-base leading-relaxed text-slate-300 sm:px-8 sm:py-10 sm:text-lg">
                            {!! $cms['vision_body'] ?? '' !!}
                        </div>
                    </div>
                    <aside class="flex flex-col justify-center gap-4 border-t border-white/10 bg-gradient-to-b from-church-surface/80 to-church-bg/90 p-6 sm:p-8 lg:border-l lg:border-t-0">
                        <p class="flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-church-gold/80">
                            @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => 'beranda', 'iconKey' => 'sidebar_section', 'extraClasses' => 'text-sm'])
                            {{ $cms['sidebar_section_title'] ?? 'Jelajahi' }}
                        </p>
                        @foreach ($cms['sidebar_cards'] ?? [] as $card)
                            <a href="{{ \App\Support\PublicCmsUrl::fromPathOrUrl($card['url'] ?? '#') }}" class="public-card-link group flex items-center gap-3 rounded-xl border border-white/10 bg-church-card-raised p-4">
                                <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-church-navy/60 text-lg text-church-gold ring-1 ring-church-gold/25">
                                    <i class="{{ \App\Support\CmsIcon::linkedCardIconClasses($card['icon'] ?? '', (string) ($card['url'] ?? '')) }}" aria-hidden="true"></i>
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="block text-sm font-semibold text-church-fg">{{ $card['title'] ?? '' }}</span>
                                    <span class="block text-xs text-slate-400">{{ $card['subtitle'] ?? '' }}</span>
                                </span>
                                @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => 'beranda', 'iconKey' => 'sidebar_card_arrow', 'extraClasses' => 'text-slate-500 transition group-hover:translate-x-0.5 group-hover:text-church-gold'])
                            </a>
                        @endforeach
                    </aside>
                </div>
            </div>
        </div>
    </section>
    </div>
@endsection
