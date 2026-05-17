@extends('layouts.public')

@section('title', $page->title)

@section('content')
    @include('partials.cms-page-content-styles')

    @php($pageKey = $page->slug)

    <div class="mx-auto max-w-6xl min-w-0 px-4 py-8 sm:px-6 sm:py-12 lg:py-16">
        <nav class="mb-6 flex flex-wrap items-center gap-2 text-sm text-slate-400" aria-label="Breadcrumb">
 <a href="{{ route('home') }}" class="public-btn-hover inline-flex items-center gap-1.5 rounded-md font-medium text-church-gold underline-offset-4 transition hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-church-gold/60">
                @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pageKey, 'iconKey' => 'breadcrumb_home', 'extraClasses' => 'text-xs opacity-90'])
                {{ $cms['breadcrumb_home'] ?? 'Beranda' }}
            </a>
            @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pageKey, 'iconKey' => 'breadcrumb_sep', 'extraClasses' => 'text-[0.65rem] text-slate-600'])
            <span class="inline-flex items-center gap-1.5 font-medium text-slate-200">
                @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pageKey, 'iconKey' => 'breadcrumb_current', 'extraClasses' => 'text-xs text-church-gold/80'])
                {{ $cms['breadcrumb_current'] ?? $page->title }}
            </span>
        </nav>
        <h1 class="mb-8 flex items-center gap-3 font-serif text-3xl font-bold tracking-tight text-church-fg sm:text-4xl">
            <span class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-church-gold/15 text-church-gold ring-1 ring-church-gold/25">
                @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pageKey, 'iconKey' => 'h1', 'extraClasses' => 'text-xl'])
            </span>
            {{ $page->title }}
        </h1>
        <div class="public-card-hover relative overflow-hidden rounded-2xl border border-white/10 bg-church-card ring-1 ring-church-gold/10">
            <div class="relative z-[1]">
                <div class="h-1 bg-gradient-to-r from-church-gold via-church-gold-soft to-church-gold" aria-hidden="true"></div>
                <article @class([
                    'church-cms-body max-w-none break-words overflow-x-auto px-4 py-6 sm:px-10 sm:py-10 [&_img]:h-auto [&_img]:max-w-full [&_pre]:max-w-full [&_pre]:overflow-x-auto [&_table]:block [&_table]:max-w-full [&_table]:overflow-x-auto',
                    'church-cms-body--para-divider' => in_array($pageKey, ['profil', 'struktur'], true),
                ])>
                    {!! $page->body !!}
                </article>
            </div>
        </div>
    </div>
@endsection