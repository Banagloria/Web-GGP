@extends('layouts.public')

@section('title', $announcement->title)

@push('meta')
    @php
        $desc = \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($announcement->body))), 160, '…');
        $url = url()->current();
    @endphp
    <meta name="description" content="{{ e($desc) }}">
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ e($announcement->title) }}">
    <meta property="og:description" content="{{ e($desc) }}">
    <meta property="og:url" content="{{ e($url) }}">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ e($announcement->title) }}">
    <meta name="twitter:description" content="{{ e($desc) }}">
@endpush

@section('content')
    @include('partials.cms-page-content-styles')

    @php
        $pk = 'informasi_kegiatan';
        $publishedLabel = $announcement->published_at?->timezone(config('app.timezone'))->format('d M Y');
        $listLabel = $cms['breadcrumb_current'] ?? 'Informasi kegiatan';
    @endphp

    <div class="mx-auto max-w-6xl min-w-0 px-4 py-8 pb-[max(2rem,env(safe-area-inset-bottom))] sm:px-6 sm:py-12 lg:py-16">
        <header class="mb-8 sm:mb-10">
            <nav class="mb-4 flex flex-wrap items-center gap-2 text-sm text-slate-400" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="inline-flex max-w-full items-center gap-1.5 font-medium text-church-gold underline-offset-4 transition hover:text-church-gold-soft hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-church-gold/60">
                    @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'index_breadcrumb_home', 'extraClasses' => 'text-xs opacity-90'])
                    {{ $cms['breadcrumb_home'] ?? 'Beranda' }}
                </a>
                @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'index_breadcrumb_sep', 'extraClasses' => 'text-[0.65rem] text-slate-600'])
                <a href="{{ route('informasi-kegiatan') }}" class="inline-flex max-w-full items-center gap-1.5 font-medium text-church-gold underline-offset-4 transition hover:text-church-gold-soft hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-church-gold/60">
                    @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'index_breadcrumb_current', 'extraClasses' => 'text-xs text-church-gold/80'])
                    <span class="min-w-0 break-words">{{ $listLabel }}</span>
                </a>
                @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'index_breadcrumb_sep', 'extraClasses' => 'text-[0.65rem] text-slate-600'])
                <span class="inline-flex min-w-0 max-w-full items-center gap-1.5 font-medium text-slate-200" aria-current="page">
                    @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'show_h1', 'extraClasses' => 'text-xs text-church-gold/70'])
                    <span class="min-w-0 break-words">{{ $announcement->title }}</span>
                </span>
            </nav>
            <h1 class="flex items-center gap-3 font-serif text-3xl font-bold tracking-tight text-church-fg sm:text-4xl">
                <span class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-church-gold/15 text-church-gold ring-1 ring-church-gold/25">
                    @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'show_page_h1', 'extraClasses' => 'text-xl'])
                </span>
                {{ $cms['show_page_h1'] ?? 'Detail kegiatan' }}
            </h1>
        </header>

        <article class="public-card-hover relative overflow-hidden rounded-2xl border border-white/10 bg-church-card ring-1 ring-church-gold/10">

            <div class="relative z-[1]">
            <div class="h-1 bg-gradient-to-r from-church-gold via-church-gold-soft to-church-gold" aria-hidden="true"></div>

            <header class="relative border-b border-white/10 bg-gradient-to-br from-church-navy/50 via-church-card to-church-card px-4 py-6 sm:px-10 sm:py-8">

                <div class="relative flex flex-wrap items-center gap-3">
                    @if ($publishedLabel)
                        <time
                            datetime="{{ $announcement->published_at?->timezone(config('app.timezone'))->toDateString() }}"
                            class="inline-flex items-center gap-1.5 rounded-full border border-church-gold/25 bg-church-gold/10 px-3 py-1 text-[0.6875rem] font-semibold uppercase tracking-[0.14em] text-church-gold sm:text-xs"
                        >
                            @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'show_date', 'extraClasses' => 'text-[0.7rem] opacity-90'])
                            {{ $publishedLabel }}
                        </time>
                    @endif
                    <span class="hidden h-4 w-px bg-white/15 sm:block" aria-hidden="true"></span>
                    <span class="hidden items-center gap-1.5 text-xs font-medium uppercase tracking-wider text-slate-500 sm:inline-flex">
                        @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'show_h1', 'extraClasses' => 'text-church-gold/50'])
                        Pengumuman
                    </span>
                </div>

                <h2 class="relative mt-4 font-serif text-2xl font-bold leading-snug tracking-tight text-church-fg sm:mt-5 sm:text-3xl lg:mt-5 lg:text-[2rem] lg:leading-tight xl:text-[2.125rem]">
                    {{ $announcement->title }}
                </h2>
            </header>

            <div class="church-cms-body max-w-none break-words overflow-x-auto px-4 py-6 sm:px-10 sm:py-10 [&_img]:h-auto [&_img]:max-w-full [&_pre]:max-w-full [&_pre]:overflow-x-auto [&_table]:block [&_table]:max-w-full [&_table]:overflow-x-auto">
                {!! $announcement->body !!}
            </div>
            </div>
        </article>

        <footer class="mt-8 flex flex-col gap-4 border-t border-white/10 pt-8 sm:mt-10 sm:flex-row sm:items-center sm:justify-between lg:mt-12 lg:gap-6 lg:pt-10">
            <p class="text-sm text-slate-500 lg:shrink-0">
                @if ($publishedLabel)
                    Dipublikasikan {{ $publishedLabel }}
                @endif
            </p>
            <a
                href="{{ route('informasi-kegiatan') }}"
                class="public-btn-hover inline-flex min-h-[2.75rem] w-full shrink-0 items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-church-gold to-church-gold-soft px-5 py-3 text-sm font-semibold text-church-navy focus-visible:ring-offset-2 focus-visible:ring-offset-church-bg sm:w-auto sm:min-w-[12rem] lg:min-w-[13rem]"
            >
                @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'show_back', 'extraClasses' => ''])
                Semua {{ strtolower($listLabel) }}
            </a>
        </footer>
    </div>
@endsection
