@extends('layouts.public')

@section('title', 'Jadwal Ibadah')

@section('content')
    @php $pk = 'jadwal'; @endphp
    <div class="mx-auto max-w-6xl min-w-0 px-4 py-8 sm:px-6 sm:py-12 lg:py-14">
        <header class="mb-8 sm:mb-10">
            <nav class="mb-4 flex flex-wrap items-center gap-2 text-sm text-slate-400" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 font-medium text-church-gold underline-offset-4 hover:underline">
                    @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'breadcrumb_home', 'extraClasses' => 'text-xs opacity-90'])
                    {{ $cms['breadcrumb_home'] ?? 'Beranda' }}
                </a>
                @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'breadcrumb_sep', 'extraClasses' => 'text-[0.65rem] text-slate-600'])
                <span class="inline-flex items-center gap-1.5 text-slate-200">
                    @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'breadcrumb_current', 'extraClasses' => 'text-xs text-church-gold/80'])
                    {{ $cms['breadcrumb_current'] ?? 'Jadwal' }}
                </span>
            </nav>
            <h1 class="flex items-center gap-3 font-serif text-3xl font-bold tracking-tight text-church-fg sm:text-4xl">
                <span class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-church-gold/15 text-church-gold ring-1 ring-church-gold/25">
                    @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'h1', 'extraClasses' => 'text-xl'])
                </span>
                {{ $cms['h1'] ?? 'Jadwal ibadah' }}
            </h1>
        </header>

        @include('partials.schedule-section', [
            'sectionKey' => 'jadwal-upcoming',
            'sectionTitle' => $cms['section_upcoming_title'] ?? 'Jadwal mendatang',
            'headers' => \App\Services\WorshipSchedulePartitionService::publicHeaders(
                \App\Services\WorshipSchedulePartitionService::headersUpcoming($cms)
            ),
            'rows' => $upcoming,
            'cms' => $cms,
            'showActions' => false,
        ])

        @include('partials.schedule-section', [
            'sectionKey' => 'jadwal-completed',
            'sectionTitle' => $cms['section_completed_title'] ?? 'Jadwal selesai',
            'headers' => \App\Services\WorshipSchedulePartitionService::publicHeaders(
                \App\Services\WorshipSchedulePartitionService::headersCompleted($cms)
            ),
            'rows' => $completed,
            'cms' => $cms,
            'showActions' => false,
        ])
    </div>
@endsection
