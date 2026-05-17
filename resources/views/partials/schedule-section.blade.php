@php
    use App\Services\WorshipSchedulePartitionService;
    $pk = 'jadwal';
    $sectionKey = $sectionKey ?? 'schedule';
    $headers = $headers ?? [];
    $colCount = count($headers);
    $showActions = $showActions ?? false;
    $emptyMessage = $emptyMessage ?? ($cms['empty_message'] ?? 'Belum ada jadwal.');
    $perPage = WorshipSchedulePartitionService::ROWS_PER_PAGE;
    $totalRows = $rows->count();
    $totalPages = max(1, (int) ceil($totalRows / $perPage));
    $lastHeaderIndex = $colCount > 0 ? $colCount - 1 : -1;
    $hasActionColumn = $showActions
        && $lastHeaderIndex >= 0
        && WorshipSchedulePartitionService::isActionColumn((string) ($headers[$lastHeaderIndex] ?? ''));
    $tableKind = str_contains($sectionKey, 'completed') ? 'completed' : 'upcoming';
@endphp

<section class="mb-10 last:mb-0" aria-labelledby="{{ $sectionKey }}-heading">
    <h2 id="{{ $sectionKey }}-heading" class="mb-4 flex items-center gap-2 font-serif text-xl font-semibold text-church-fg sm:text-2xl">
        @include('partials.cms-page-icon', [
            'cms' => $cms,
            'pageKey' => $pk,
            'iconKey' => str_contains($sectionKey, 'completed') ? 'section_completed' : 'section_upcoming',
            'extraClasses' => 'text-church-gold',
        ])
        {{ $sectionTitle }}
    </h2>

    <div class="space-y-4 md:hidden" data-schedule-list="{{ $sectionKey }}" data-schedule-mobile>
        @forelse ($rows as $row)
            @php $pageNum = (int) floor($loop->index / $perPage) + 1; @endphp
            <article
                class="public-card-hover schedule-row relative overflow-hidden rounded-2xl border border-white/10 bg-church-card ring-1 ring-church-gold/10 {{ $pageNum > 1 ? 'hidden' : '' }}"
                data-schedule-row
                data-schedule-page="{{ $pageNum }}"
            >

                <div class="relative z-[1] flex flex-col">
                    <div class="h-1 bg-gradient-to-r from-church-gold via-church-gold-soft to-church-gold" aria-hidden="true"></div>
                    <div class="p-4 sm:p-5">
                        <div class="flex items-start gap-3 border-b border-white/10 pb-3">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-church-gold/15 text-church-gold ring-1 ring-church-gold/25">
                                @include('partials.schedule-column-icon', [
                                    'cms' => $cms,
                                    'colIndex' => 0,
                                    'tableKind' => $tableKind,
                                    'sectionKey' => $sectionKey,
                                    'extraClasses' => 'text-sm',
                                ])
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $headers[0] ?? 'Waktu' }}</p>
                                <time class="mt-1 block text-sm font-semibold leading-snug text-church-gold">
                                    {{ WorshipSchedulePartitionService::timeDisplay($row) }}
                                </time>
                                <p class="mt-0.5 text-xs text-slate-400">{{ WorshipSchedulePartitionService::relativeTimeLabel($row) }}</p>
                            </div>
                        </div>

                        @foreach ($headers as $colIndex => $header)
                            @if ($colIndex === 0 || ($hasActionColumn && $colIndex === $lastHeaderIndex))
                                @continue
                            @endif
                            @php
                                $cellValue = trim(WorshipSchedulePartitionService::middleCellValue($row, $colIndex - 1));
                            @endphp
                            @if ($cellValue === '')
                                @continue
                            @endif
                            <p class="mt-3 flex items-start gap-3 text-sm text-slate-300">
                                <span class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-lg bg-church-surface/80 text-church-gold/90 ring-1 ring-white/10">
                                    @include('partials.schedule-column-icon', [
                                        'cms' => $cms,
                                        'colIndex' => $colIndex,
                                        'tableKind' => $tableKind,
                                        'sectionKey' => $sectionKey,
                                        'extraClasses' => 'text-xs',
                                    ])
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="block text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $header }}</span>
                                    <span class="mt-0.5 block break-words text-church-fg">{{ $cellValue }}</span>
                                </span>
                            </p>
                        @endforeach
                    </div>
                </div>
                @if ($hasActionColumn)
                    <div class="relative z-[1] mx-4 mb-4 flex items-center gap-3 border-t border-white/10 pt-3 sm:mx-5">
                        <a
                            href="{{ route('dashboard.jadwal-ibadah.edit', $row) }}"
                            class="admin-btn-icon admin-btn-icon--edit size-9"
                            title="Edit"
                            aria-label="Edit jadwal"
                        >
                            <i class="fa-solid fa-pen text-sm" aria-hidden="true"></i>
                        </a>
                        <form method="post" action="{{ route('dashboard.jadwal-ibadah.destroy', $row) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                data-admin-confirm-submit
                                data-confirm-title="Hapus jadwal?"
                                data-confirm-message="Jadwal ini akan dihapus permanen."
                                data-confirm-label="Ya, hapus"
                                class="admin-btn-icon admin-btn-icon--delete size-9"
                                title="Hapus"
                                aria-label="Hapus jadwal"
                            >
                                <i class="fa-solid fa-trash text-sm" aria-hidden="true"></i>
                            </button>
                        </form>
                        @unless ($row->is_active)
                            <span class="rounded bg-slate-700 px-2 py-0.5 text-xs text-slate-300">Nonaktif</span>
                        @endunless
                    </div>
                @endif
            </article>
        @empty
            <div class="relative overflow-hidden rounded-2xl border border-dashed border-white/20 bg-church-card/80 ring-1 ring-church-gold/10">
                <p class="relative z-[1] flex flex-col items-center gap-3 px-4 py-10 text-center text-slate-400">
                    @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'empty_mobile', 'extraClasses' => 'text-3xl text-church-gold/40'])
                    {{ $emptyMessage }}
                </p>
            </div>
        @endforelse
    </div>

    <div class="public-card-hover hidden overflow-hidden rounded-2xl border border-white/10 bg-church-card ring-1 ring-church-gold/10 md:block" data-schedule-table="{{ $sectionKey }}" data-schedule-desktop>
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-gradient-to-r from-church-navy via-church-gold/15 to-church-navy-mid text-white">
                    <tr>
                        @foreach ($headers as $colIndex => $header)
                            <th class="px-5 py-4 font-semibold tracking-wide {{ $hasActionColumn && $colIndex === $lastHeaderIndex ? 'w-28' : '' }}">
                                <span class="inline-flex items-center gap-2">
                                    @include('partials.schedule-column-icon', [
                                        'cms' => $cms,
                                        'colIndex' => $colIndex,
                                        'tableKind' => $tableKind,
                                        'sectionKey' => $sectionKey,
                                        'extraClasses' => 'opacity-90',
                                    ])
                                    {{ $header }}
                                </span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse ($rows as $row)
                        @php $pageNum = (int) floor($loop->index / $perPage) + 1; @endphp
                        <tr
                            class="schedule-row transition hover:bg-white/5 {{ $loop->even ? 'bg-church-surface/40' : 'bg-church-card' }} {{ $pageNum > 1 ? 'hidden' : '' }}"
                            data-schedule-row
                            data-schedule-page="{{ $pageNum }}"
                        >
                            @foreach ($headers as $colIndex => $header)
                                <td class="px-5 py-3.5 {{ $colIndex === 0 ? 'whitespace-nowrap font-semibold text-church-gold' : 'text-slate-200' }}">
                                    @if ($colIndex === 0)
                                        <span class="inline-flex flex-col gap-0.5">
                                            <span class="inline-flex items-center gap-2">
                                                @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'row_time', 'extraClasses' => 'text-church-gold/70'])
                                                {{ WorshipSchedulePartitionService::timeDisplay($row) }}
                                            </span>
                                            <span class="text-xs font-normal text-slate-400">{{ WorshipSchedulePartitionService::relativeTimeLabel($row) }}</span>
                                        </span>
                                    @elseif ($hasActionColumn && $colIndex === $lastHeaderIndex)
                                        <div class="flex items-center gap-2">
                                            <a
                                                href="{{ route('dashboard.jadwal-ibadah.edit', $row) }}"
                                                class="admin-btn-icon admin-btn-icon--edit"
                                                title="Edit"
                                                aria-label="Edit jadwal"
                                            >
                                                <i class="fa-solid fa-pen text-sm" aria-hidden="true"></i>
                                            </a>
                                            <form method="post" action="{{ route('dashboard.jadwal-ibadah.destroy', $row) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    data-admin-confirm-submit
                                                    data-confirm-title="Hapus jadwal?"
                                                    data-confirm-message="Jadwal ini akan dihapus permanen."
                                                    data-confirm-label="Ya, hapus"
                                                    class="admin-btn-icon admin-btn-icon--delete"
                                                    title="Hapus"
                                                    aria-label="Hapus jadwal"
                                                >
                                                    <i class="fa-solid fa-trash text-sm" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                            @unless ($row->is_active)
                                                <span class="text-xs text-slate-500">Nonaktif</span>
                                            @endunless
                                        </div>
                                    @else
                                        {{ WorshipSchedulePartitionService::middleCellValue($row, $colIndex - 1) }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ max($colCount, 1) }}" class="px-5 py-12">
                                <span class="flex flex-col items-center gap-3 text-slate-400">
                                    @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pk, 'iconKey' => 'empty_desktop', 'extraClasses' => 'text-3xl text-church-gold/35'])
                                    {{ $emptyMessage }}
                                </span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($totalPages > 1)
        <nav
            class="mt-4 flex flex-wrap items-center justify-center gap-1.5"
            aria-label="Halaman {{ $sectionTitle }}"
            data-schedule-pagination="{{ $sectionKey }}"
        >
            @for ($p = 1; $p <= $totalPages; $p++)
                <button
                    type="button"
                    data-schedule-page-btn="{{ $p }}"
                    aria-label="Halaman {{ $p }}"
                    @if ($p === 1) aria-current="page" @endif
                    class="public-btn-hover schedule-page-btn inline-flex min-w-[2.25rem] items-center justify-center rounded-lg border px-3 py-2 text-sm font-semibold {{ $p === 1 ? 'border-church-gold/50 bg-church-gold/20 text-church-gold' : 'border-white/15 bg-church-surface/50 text-slate-300' }}"
                >
                    {{ $p }}
                </button>
            @endfor
        </nav>
    @endif
</section>

@once
    @push('scripts')
        <script>
            (function () {
                var activeClass = ['border-church-gold/50', 'bg-church-gold/20', 'text-church-gold'];
                var inactiveClass = ['border-white/15', 'bg-church-surface/50', 'text-slate-300'];

                function setBtnActive(btn, active) {
                    activeClass.forEach(function (c) { btn.classList.toggle(c, active); });
                    inactiveClass.forEach(function (c) { btn.classList.toggle(c, !active); });
                    if (active) {
                        btn.setAttribute('aria-current', 'page');
                    } else {
                        btn.removeAttribute('aria-current');
                    }
                }

                document.querySelectorAll('[data-schedule-pagination]').forEach(function (nav) {
                    var sectionKey = nav.getAttribute('data-schedule-pagination');

                    function rowsForSection() {
                        return document.querySelectorAll(
                            '[data-schedule-list="' + sectionKey + '"] [data-schedule-row], ' +
                            '[data-schedule-table="' + sectionKey + '"] [data-schedule-row]'
                        );
                    }

                    function goToPage(page) {
                        nav.querySelectorAll('[data-schedule-page-btn]').forEach(function (btn) {
                            var p = parseInt(btn.getAttribute('data-schedule-page-btn'), 10);
                            setBtnActive(btn, p === page);
                        });
                        rowsForSection().forEach(function (row) {
                            var rowPage = parseInt(row.getAttribute('data-schedule-page'), 10);
                            row.classList.toggle('hidden', rowPage !== page);
                        });
                    }

                    nav.querySelectorAll('[data-schedule-page-btn]').forEach(function (btn) {
                        btn.addEventListener('click', function () {
                            goToPage(parseInt(btn.getAttribute('data-schedule-page-btn'), 10));
                        });
                    });
                });
            })();
        </script>
    @endpush
@endonce
