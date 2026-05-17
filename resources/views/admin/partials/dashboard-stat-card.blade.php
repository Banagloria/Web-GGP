<a
    href="{{ $stat['href'] }}"
    class="public-card-link group flex h-full min-h-0 min-w-0 flex-col rounded-2xl border border-white/10 bg-church-card touch-manipulation"
    aria-label="{{ $stat['label'] }}: {{ $stat['value'] }}"
>
    <span class="flex min-w-0 flex-1 flex-col p-3.5 sm:p-5">
        <span class="flex items-start justify-between gap-2.5 sm:gap-3">
            <span
                class="flex size-9 shrink-0 items-center justify-center rounded-lg ring-1 sm:size-11 sm:rounded-xl {{ $stat['iconWrapClass'] }}"
                aria-hidden="true"
            >
                @include('partials.dashboard-nav-icon', ['which' => $stat['icon'], 'class' => $stat['iconClass']])
            </span>
            <span class="shrink-0 text-xl font-bold tabular-nums leading-none {{ $stat['valueClass'] }} sm:text-2xl md:text-3xl">
                {{ $stat['value'] }}
            </span>
        </span>

        <span class="mt-3 flex min-w-0 flex-1 flex-col gap-0.5 sm:mt-5">
            <span class="text-pretty text-[0.8125rem] font-semibold leading-snug text-slate-100 sm:text-sm md:text-[0.9375rem]">
                {{ $stat['label'] }}
            </span>
            <span class="text-xs leading-relaxed text-slate-500">{{ $stat['hint'] }}</span>
        </span>

        <span class="mt-3 inline-flex items-center gap-1 text-xs font-medium text-church-gold/70 sm:mt-4">
            Lihat detail
            <svg class="size-3.5 transition group-hover:translate-x-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
            </svg>
        </span>
    </span>
</a>
