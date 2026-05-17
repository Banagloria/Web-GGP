@extends('layouts.public')

@section('title', 'Informasi Kegiatan')

@section('content')
    @php($pk = 'informasi_kegiatan')
    <div class="mx-auto max-w-6xl min-w-0 px-4 py-8 sm:px-6 sm:py-12 lg:py-14">
        <header class="mb-8 sm:mb-10">
            <nav class="mb-4 flex flex-wrap items-center gap-2 text-sm text-slate-400" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 font-medium text-church-gold underline-offset-4 hover:underline">
                    @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'index_breadcrumb_home', 'extraClasses' => 'text-xs opacity-90'])
                    {{ $cms['breadcrumb_home'] ?? 'Beranda' }}
                </a>
                @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'index_breadcrumb_sep', 'extraClasses' => 'text-[0.65rem] text-slate-600'])
                <span class="inline-flex items-center gap-1.5 text-slate-200">
                    @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'index_breadcrumb_current', 'extraClasses' => 'text-xs text-church-gold/80'])
                    {{ $cms['breadcrumb_current'] ?? 'Informasi kegiatan' }}
                </span>
            </nav>
            <h1 class="flex items-center gap-3 font-serif text-3xl font-bold tracking-tight text-church-fg sm:text-4xl">
                <span class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-church-gold/15 text-church-gold ring-1 ring-church-gold/25">
                    @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'index_h1', 'extraClasses' => 'text-xl'])
                </span>
                {{ $cms['h1'] ?? 'Informasi kegiatan' }}
            </h1>
        </header>

        <div class="grid gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-2 lg:gap-6">
            @forelse ($items as $item)
                <a
                    href="{{ route('informasi-kegiatan.show', $item->slug) }}"
                    class="public-card-link group relative flex min-w-0 flex-col overflow-hidden rounded-2xl border border-white/10 bg-church-card ring-1 ring-church-gold/10"
                >
                    <span class="relative z-[1] flex min-w-0 flex-1 flex-col">
                        <div class="h-1 bg-gradient-to-r from-church-gold via-church-gold-soft to-church-gold" aria-hidden="true"></div>
                        <span class="flex min-w-0 flex-1 flex-col p-5 sm:p-6">
                            <time class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-church-gold/90">
                                @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'index_card_date', 'extraClasses' => 'opacity-90'])
                                {{ $item->published_at?->timezone(config('app.timezone'))->format('d M Y') }}
                            </time>
                            <h2 class="mt-2 font-serif text-lg font-semibold leading-snug text-church-fg transition group-hover:text-church-gold sm:text-xl">{{ $item->title }}</h2>
                            <p class="mt-3 line-clamp-3 flex-1 text-sm leading-relaxed text-slate-400">{{ strip_tags($item->body) }}</p>
                            <span class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-church-gold">
                                {{ $cms['read_more_label'] ?? 'Baca selengkapnya' }}
                                @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'index_card_arrow', 'extraClasses' => 'transition group-hover:translate-x-0.5'])
                            </span>
                        </span>
                    </span>
                </a>
            @empty
                <div class="flex flex-col items-center gap-3 rounded-2xl border border-dashed border-white/20 bg-church-surface/80 px-4 py-12 text-center text-slate-400 sm:col-span-2">
                    @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'index_empty', 'extraClasses' => 'text-3xl text-church-gold/35'])
                    {{ $cms['empty_message'] ?? 'Belum ada pengumuman.' }}
                </div>
            @endforelse
        </div>

        @if ($items->hasPages())
            <div class="mt-8 overflow-x-auto pb-2 sm:mt-10">
                <div class="min-w-0 [&_a]:text-church-gold [&_span]:text-slate-400">{{ $items->links() }}</div>
            </div>
        @endif
    </div>
@endsection
