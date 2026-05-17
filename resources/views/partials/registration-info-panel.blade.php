@props([
    'steps' => [],
    'tips' => [],
    'title' => 'Alur pendaftaran',
    'subtitle' => 'Ikuti langkah berikut',
    'panelIcon' => 'fa-solid fa-route',
    'tipsHeading' => 'Tips',
    'tipsHeadingIcon' => 'fa-solid fa-lightbulb',
])

<aside {{ $attributes->merge(['class' => 'reg-registration-panel-info']) }}>
    <div class="reg-registration-panel-info-sticky">
        <div class="reg-info-card relative overflow-hidden rounded-2xl border border-white/10 bg-gradient-to-br from-church-card via-church-navy/50 to-church-surface ring-1 ring-church-gold/20">
            <div class="h-1.5 bg-gradient-to-r from-church-gold via-church-gold-soft to-church-gold" aria-hidden="true"></div>
            <div class="relative p-5 sm:p-6">
                <div class="mb-5 flex items-center gap-3">
                    <span class="flex size-10 items-center justify-center rounded-xl bg-church-gold/15 text-church-gold ring-1 ring-church-gold/30">
                        <i class="{{ $panelIcon }} text-sm" aria-hidden="true"></i>
                    </span>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-church-gold/90">{{ $title }}</p>
                        @if ($subtitle !== '')
                            <p class="mt-0.5 text-xs text-slate-500">{{ $subtitle }}</p>
                        @endif
                    </div>
                </div>

                <ol class="reg-info-steps relative space-y-0">
                    @foreach ($steps as $index => $step)
                        <li class="reg-info-step relative flex gap-3 pb-5 last:pb-0">
                            @if (! $loop->last)
                                <span class="reg-info-step-line absolute left-[0.85rem] top-8 bottom-0 w-px bg-gradient-to-b from-church-gold/40 to-white/10" aria-hidden="true"></span>
                            @endif
                            <span @class([
                                'relative z-[1] flex size-7 shrink-0 items-center justify-center rounded-full text-xs font-bold ring-2',
                                'bg-church-gold text-church-navy ring-church-gold/50 shadow-md shadow-church-gold/20' => $index === 0,
                                'bg-church-surface text-slate-400 ring-white/15' => $index !== 0,
                            ])>{{ $index + 1 }}</span>
                            <span @class([
                                'min-w-0 flex-1 pt-0.5 text-sm leading-snug',
                                'font-medium text-slate-200' => $index === 0,
                                'text-slate-400' => $index !== 0,
                            ])>{{ $step }}</span>
                        </li>
                    @endforeach
                </ol>

                @if (count($tips) > 0)
                    <div class="reg-info-tips mt-6 rounded-xl border border-white/10 bg-black/20 p-4 ring-1 ring-white/5">
                        <p class="mb-3 flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-church-gold/80">
                            <i class="{{ $tipsHeadingIcon }} text-[0.7rem]" aria-hidden="true"></i>
                            {{ $tipsHeading }}
                        </p>
                        <ul class="space-y-3">
                            @foreach ($tips as $tip)
                                <li class="flex items-start gap-2.5 text-xs leading-relaxed text-slate-400">
                                    <span class="mt-0.5 flex size-6 shrink-0 items-center justify-center rounded-md bg-church-gold/10 text-church-gold/80">
                                        <i class="{{ $tip['icon'] ?? 'fa-solid fa-circle-info' }} text-[0.65rem]" aria-hidden="true"></i>
                                    </span>
                                    <span>{{ $tip['text'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</aside>
