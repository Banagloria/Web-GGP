@extends('layouts.public')

@section('title', 'Pendaftaran')

@section('content')
    @php($pk = 'pendaftaran')
    <div class="reg-registration-page mx-auto min-w-0 max-w-6xl px-4 py-6 pb-[max(1.5rem,env(safe-area-inset-bottom))] sm:px-6 sm:py-10 lg:py-14">
        @if (session('status'))
            <x-flash-success class="flex min-w-0 items-start gap-3">
                @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'index_success', 'extraClasses' => 'mt-0.5 shrink-0 text-church-gold'])
                <span class="min-w-0 flex-1 break-words">{{ session('status') }}</span>
            </x-flash-success>
        @endif
        <header class="mb-6 sm:mb-10">
            <nav class="mb-4 flex flex-wrap items-center gap-x-2 gap-y-1 text-sm text-slate-400" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="inline-flex max-w-full items-center gap-1.5 font-medium text-church-gold underline-offset-4 hover:underline">
                    @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'index_breadcrumb_home', 'extraClasses' => 'text-xs opacity-90'])
                    {{ $cms['breadcrumb_home'] ?? 'Beranda' }}
                </a>
                @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'index_breadcrumb_sep', 'extraClasses' => 'text-[0.65rem] text-slate-600'])
                <span class="inline-flex max-w-full items-center gap-1.5 text-slate-200">
                    @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'index_breadcrumb_current', 'extraClasses' => 'text-xs text-church-gold/80'])
                    {{ $cms['breadcrumb_current'] ?? 'Pendaftaran' }}
                </span>
            </nav>
            <h1 class="flex flex-wrap items-start gap-3 font-serif text-2xl font-bold tracking-tight text-church-fg sm:items-center sm:text-3xl lg:text-4xl">
                <span class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-church-gold/15 text-church-gold ring-1 ring-church-gold/25 sm:size-12">
                    @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'index_h1', 'extraClasses' => 'text-lg sm:text-xl'])
                </span>
                <span class="min-w-0 flex-1 break-words">{{ $cms['h1'] ?? 'Pendaftaran' }}</span>
            </h1>
            @php($daftarIntro = trim((string) ($cms['intro'] ?? '')))
            @if ($daftarIntro !== '')
                <p class="mt-2 flex w-full max-w-2xl items-start gap-2 text-sm leading-relaxed text-slate-400 sm:text-base">
                    @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'index_intro', 'extraClasses' => 'mt-0.5 shrink-0 text-church-gold/55'])
                    <span class="min-w-0 flex-1 break-words">{{ $daftarIntro }}</span>
                </p>
            @endif
        </header>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-5 xl:grid-cols-3 xl:gap-6">
            @foreach ($cms['cards'] ?? [] as $card)
                <a
                    href="{{ \App\Support\PublicCmsUrl::fromPathOrUrl($card['url'] ?? '#') }}"
                    class="public-card-link group relative flex min-w-0 flex-col overflow-hidden rounded-2xl border border-white/10 bg-church-card ring-1 ring-church-gold/10"
                >
                    <span class="relative z-[1] flex min-w-0 flex-1 flex-col">
                        <div class="h-1 bg-gradient-to-r from-church-gold via-church-gold-soft to-church-gold" aria-hidden="true"></div>
                        <span class="flex min-w-0 flex-1 flex-row items-start gap-4 p-5 sm:flex-col sm:gap-0 sm:p-6 lg:p-7">
                            <span class="mb-0 flex size-11 shrink-0 items-center justify-center rounded-xl bg-church-gold/15 text-lg text-church-gold ring-1 ring-church-gold/25 sm:mb-4 sm:size-12 sm:text-xl">
                                <i class="{{ \App\Support\CmsIcon::linkedCardIconClasses($card['icon'] ?? '', (string) ($card['url'] ?? '')) }}" aria-hidden="true"></i>
                            </span>
                            <span class="flex min-w-0 flex-1 flex-col">
                                <h2 class="break-words font-serif text-lg font-semibold leading-snug text-church-fg sm:text-xl">{{ $card['title'] ?? '' }}</h2>
                                <p class="mt-1.5 flex-1 break-words text-sm leading-relaxed text-slate-400 sm:mt-2">{{ $card['description'] ?? '' }}</p>
                                <span class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-church-gold sm:mt-5">
                                    {{ $card['cta_label'] ?? 'Isi formulir' }}
                                    <i class="{{ \App\Support\CmsIcon::toFontAwesome($card['arrow_icon'] ?? '', 'fa-solid fa-arrow-right') }} shrink-0 transition group-hover:translate-x-0.5" aria-hidden="true"></i>
                                </span>
                            </span>
                        </span>
                    </span>
                </a>
            @endforeach
        </div>
    </div>
@endsection
