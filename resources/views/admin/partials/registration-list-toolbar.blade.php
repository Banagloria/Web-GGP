@php
    $exportExcelUrl = $exportExcelUrl ?? ($exportUrl ?? '#');
    $exportPdfUrl = $exportPdfUrl ?? '#';
    $defaultStatus = $defaultStatus ?? 'submitted';
    $activeSearch = trim((string) request('q', ''));
    $resetParams = collect(request()->query())->except(['q', 'page'])->filter(
        static fn ($value) => $value !== null && $value !== '',
    )->all();
    $resetUrl = request()->url().(count($resetParams) ? '?'.http_build_query($resetParams) : '');
@endphp

<div class="admin-list-toolbar public-card-hover mb-4 rounded-xl border border-white/10 bg-church-card/80 p-4 sm:p-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex min-w-0 flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-center">
            @include('admin.partials.btn', [
                'href' => $exportExcelUrl,
                'variant' => 'secondary',
                'icon' => 'fa-solid fa-file-excel',
                'label' => 'Ekspor Excel',
                'size' => 'sm',
                'extraClass' => 'w-full sm:w-auto',
            ])
            @include('admin.partials.btn', [
                'href' => $exportPdfUrl,
                'variant' => 'secondary',
                'icon' => 'fa-solid fa-file-pdf',
                'label' => 'Ekspor PDF',
                'size' => 'sm',
                'extraClass' => 'w-full sm:w-auto',
            ])
        </div>

        <form method="get" class="admin-list-toolbar__search flex min-w-0 w-full flex-col gap-2 sm:max-w-md sm:flex-row sm:items-center">
            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
            <input type="hidden" name="status" value="{{ request('status', $defaultStatus) }}">
            <div class="relative min-w-0 flex-1">
                <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs text-church-gold/70" aria-hidden="true"></i>
                <input
                    type="search"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari..."
                    class="admin-list-toolbar__input w-full !pl-9"
                >
            </div>
            <button
                type="submit"
                class="admin-btn-icon admin-btn-icon--search shrink-0"
                title="Cari"
                aria-label="Cari"
            >
                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
            </button>
            @if ($activeSearch !== '')
                <a
                    href="{{ $resetUrl }}"
                    class="admin-btn-icon admin-btn-icon--reset shrink-0"
                    title="Tampilkan semua"
                    aria-label="Tampilkan semua"
                >
                    <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                </a>
            @endif
        </form>
    </div>
</div>
