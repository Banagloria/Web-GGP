@extends('layouts.public')

@section('title', 'Galeri')

@section('content')
    @php
        $pk = 'galeri';
    @endphp
    <div class="bg-church-bg/50">
        <div class="mx-auto max-w-6xl min-w-0 px-4 py-8 sm:px-6 sm:py-10 lg:py-12">
            <header class="mb-6 sm:mb-8">
                <nav class="mb-4 flex flex-wrap items-center gap-2 text-sm text-slate-400" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 font-medium text-church-gold underline-offset-4 hover:underline">
                        @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'breadcrumb_home', 'extraClasses' => 'text-xs opacity-90'])
                        {{ $cms['breadcrumb_home'] ?? 'Beranda' }}
                    </a>
                    @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'breadcrumb_sep', 'extraClasses' => 'text-[0.65rem] text-slate-600'])
                    <span class="inline-flex items-center gap-1.5 text-slate-200">
                        @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'breadcrumb_current', 'extraClasses' => 'text-xs text-church-gold/80'])
                        {{ $cms['breadcrumb_current'] ?? 'Galeri' }}
                    </span>
                </nav>
                <h1 class="flex items-center gap-3 font-sans text-2xl font-bold tracking-tight text-church-fg sm:text-3xl">
                    <span class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-church-gold/15 text-church-gold ring-1 ring-church-gold/25">
                        @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'h1', 'extraClasses' => 'text-lg sm:text-xl'])
                    </span>
                    {{ $cms['h1'] ?? 'Galeri foto' }}
                </h1>
                @php
                    $galleryIntro = trim((string) ($cms['intro'] ?? ''));
                @endphp
                @if ($galleryIntro !== '')
                    <p class="mt-2 inline-flex max-w-2xl items-start gap-2 text-sm text-slate-400 sm:text-base">
                        @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'intro', 'extraClasses' => 'mt-0.5 shrink-0 text-church-gold/55'])
                        <span>{{ $galleryIntro }}</span>
                    </p>
                @endif
            </header>

            @if ($photos->count() > 0)
                <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 sm:gap-3 md:grid-cols-4 lg:grid-cols-5 lg:gap-2" id="gallery-thumbs">
                    @foreach ($photos as $idx => $photo)
                        @php
                            $globalIdx = ($photos->currentPage() - 1) * $photos->perPage() + $idx;
                            $isHeroThumb = $idx === 0 && $photos->onFirstPage();
                        @endphp
                        <button
                            type="button"
                            class="public-card-hover group relative block aspect-square w-full cursor-zoom-in overflow-hidden rounded-sm border border-white/10 bg-church-surface p-0 text-left outline-none focus-visible:ring-2 focus-visible:ring-church-gold/50 focus-visible:ring-offset-2 focus-visible:ring-offset-church-bg sm:rounded-md {{ $isHeroThumb ? 'col-span-2 aspect-[2/1] sm:col-span-2 sm:aspect-[5/3] lg:col-span-2 lg:row-span-2 lg:aspect-auto lg:min-h-[14rem]' : '' }}"
                            data-gallery-lightbox-open="{{ $globalIdx }}"
                            aria-label="Buka foto: {{ $photo['alt'] }}"
                        >
                            <img
                                src="{{ $photo['src'] }}"
                                alt=""
                                class="pointer-events-none h-full w-full object-cover transition duration-300 ease-out group-hover:scale-[1.03]"
                                loading="lazy"
                                decoding="async"
                                sizes="(max-width: 640px) 50vw, (max-width: 1024px) 33vw, 20vw"
                            >
                        </button>
                    @endforeach
                </div>

                @if ($photos->hasPages())
                    <nav class="mt-8 overflow-x-auto pb-2 sm:mt-10" aria-label="Halaman galeri">
                        <div class="min-w-0 [&_a]:text-church-gold [&_span]:text-slate-400">{{ $photos->links() }}</div>
                    </nav>
                @endif
            @else
                <div class="rounded-2xl border border-dashed border-white/20 bg-church-card/80 px-4 py-14 text-center text-slate-400">
                    <p class="flex flex-col items-center gap-3">
                        @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'empty_message', 'extraClasses' => 'text-4xl text-church-gold/35'])
                        {{ $cms['empty_message'] ?? 'Belum ada foto di galeri.' }}
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection

@if ($allPhotos->isNotEmpty())
    @include('partials.gallery-lightbox', [
        'photos' => $allPhotos,
        'labels' => [
            'title' => $cms['lightbox_title'] ?? 'Galeri foto — tampilan besar',
            'close' => $cms['lightbox_close_label'] ?? 'Tutup',
            'prev' => $cms['lightbox_prev_label'] ?? 'Foto sebelumnya',
            'next' => $cms['lightbox_next_label'] ?? 'Foto berikutnya',
        ],
    ])
@endif
