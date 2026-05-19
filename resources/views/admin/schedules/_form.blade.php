@php
    use App\Services\WorshipSchedulePartitionService;
    $timeVal = $item ? \Illuminate\Support\Str::of((string) $item->starts_at)->substr(0, 5) : old('starts_at', '09:00');
    $endTimeVal = $item
        ? \Illuminate\Support\Str::of((string) ($item->ends_at ?? $item->starts_at))->substr(0, 5)
        : old('ends_at', '11:00');
    $dateVal = $item && $item->schedule_date
        ? \Illuminate\Support\Carbon::parse($item->schedule_date)->format('Y-m-d')
        : old('schedule_date', now()->format('Y-m-d'));
    $columnValues = $item
        ? WorshipSchedulePartitionService::columnValuesFromRow($item)
        : (array) old('column_values', []);
    $middleLabels = $middleLabels ?? WorshipSchedulePartitionService::middleLabelsFromCms($cms);
    $inputClass = 'w-full min-w-0 rounded-xl border border-white/10 bg-church-surface/80 px-3.5 py-2.5 text-sm text-church-fg shadow-inner shadow-black/10 transition focus:border-church-gold/50 focus:outline-none focus:ring-2 focus:ring-church-gold/20';
@endphp

<div class="space-y-5 sm:space-y-6">
    <section class="public-card-hover rounded-2xl border border-white/10 bg-gradient-to-br from-church-surface/60 to-church-card/40 p-4 sm:p-5">
        <header class="mb-4 flex items-center gap-3">
            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-church-gold/15 text-church-gold ring-1 ring-church-gold/25">
                <i class="fa-regular fa-clock" aria-hidden="true"></i>
            </span>
            
            <h2 class="text-sm font-semibold text-church-fg sm:text-base">Waktu jadwal</h2>
        </header>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <label class="block">
                @include('admin.partials.form-label', ['text' => 'Tanggal'])
                <input type="date" name="schedule_date" value="{{ $dateVal }}" class="{{ $inputClass }}">
                @error('schedule_date')<p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>@enderror
            </label>
            <label class="block">
                @include('admin.partials.form-label', ['text' => 'Jam mulai'])
                <input type="time" name="starts_at" value="{{ old('starts_at', $timeVal) }}" class="{{ $inputClass }}">
                @error('starts_at')<p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>@enderror
            </label>
            <label class="block sm:col-span-2 lg:col-span-1">
                @include('admin.partials.form-label', ['text' => 'Jam selesai'])
                <input type="time" name="ends_at" value="{{ old('ends_at', $endTimeVal) }}" class="{{ $inputClass }}">
                @error('ends_at')<p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>@enderror
            </label>
        </div>
    </section>

    @if (count($middleLabels) > 0)
        <section class="public-card-hover rounded-2xl border border-white/10 bg-church-card/50 p-4 sm:p-5">
            <header class="mb-4 flex items-center gap-3">
                <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-church-navy-mid/80 text-church-gold ring-1 ring-white/10">
                    <i class="fa-solid fa-table-columns" aria-hidden="true"></i>
                </span>
                <h2 class="text-sm font-semibold text-church-fg sm:text-base">Detail jadwal</h2>
            </header>
            
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                @foreach ($middleLabels as $i => $label)
                    <label class="block">
                        @include('admin.partials.form-label', ['text' => $label])
                        <input
                            name="column_values[{{ $i }}]"
                            value="{{ old('column_values.'.$i, $columnValues[$i] ?? '') }}"
                            class="{{ $inputClass }}"
                            placeholder="{{ $label }}"
                        >
                        @error('column_values.'.$i)<p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>@enderror
                    </label>
                @endforeach
            </div>
            @error('column_values')<p class="mt-3 text-xs text-red-400">{{ $message }}</p>@enderror
        </section>
    @endif
</div>
