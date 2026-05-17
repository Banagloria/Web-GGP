@props([
    'cms' => [],
    'pageKey' => 'pendaftaran',
    'title',
    'h1IconKey',
    'leafIconKey',
    'leafLabel',
    'subtitle' => null,
])

<header class="reg-page-header relative mb-8 sm:mb-10">

    <nav class="reg-page-breadcrumb relative z-[1] mb-5 flex flex-wrap items-center gap-x-2 gap-y-1 text-sm" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="inline-flex max-w-full items-center gap-1.5 font-medium text-church-gold underline-offset-4 transition hover:text-church-gold-soft hover:underline">
            @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pageKey, 'iconKey' => 'form_breadcrumb_home', 'extraClasses' => 'text-xs opacity-90'])
            {{ $cms['breadcrumb_home'] ?? 'Beranda' }}
        </a>
        @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pageKey, 'iconKey' => 'form_breadcrumb_sep', 'extraClasses' => 'text-[0.65rem] text-slate-600'])
        <a href="{{ route('pendaftaran.index') }}" class="inline-flex max-w-full items-center gap-1.5 font-medium text-church-gold underline-offset-4 transition hover:text-church-gold-soft hover:underline">
            @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pageKey, 'iconKey' => 'form_breadcrumb_mid', 'extraClasses' => 'text-xs opacity-90'])
            {{ $cms['breadcrumb_current'] ?? 'Pendaftaran' }}
        </a>
        @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pageKey, 'iconKey' => 'form_breadcrumb_sep', 'extraClasses' => 'text-[0.65rem] text-slate-600'])
        <span class="inline-flex max-w-full items-center gap-1.5 rounded-full bg-white/5 px-2.5 py-0.5 text-slate-200 ring-1 ring-white/10">
            @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pageKey, 'iconKey' => $leafIconKey, 'extraClasses' => 'text-xs text-church-gold/80'])
            {{ $leafLabel }}
        </span>
    </nav>

    <div class="reg-page-hero relative z-[1] overflow-hidden rounded-2xl border border-white/10 bg-gradient-to-br from-church-card/90 via-church-navy/40 to-church-surface/80 p-5 ring-1 ring-church-gold/15 backdrop-blur-sm sm:p-7">
        <div class="relative flex flex-wrap items-start gap-4 sm:items-center">
            <span class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-church-gold/25 to-church-gold/5 text-church-gold shadow-inner ring-1 ring-church-gold/30 sm:size-14">
                @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pageKey, 'iconKey' => $h1IconKey, 'extraClasses' => 'text-xl sm:text-2xl'])
            </span>
            <div class="min-w-0 flex-1">
                <h1 class="font-serif text-2xl font-bold tracking-tight text-church-fg sm:text-3xl lg:text-[2rem]">{{ $title }}</h1>
                @if ($subtitle)
                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-400 sm:text-base">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
    </div>
</header>
