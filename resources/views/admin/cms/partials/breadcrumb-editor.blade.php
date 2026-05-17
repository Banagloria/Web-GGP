@php
    /** @var string $pageKey */
    /** @var array<string, mixed> $data */
    $homeIconKey = $homeIconKey ?? 'breadcrumb_home';
    $sepIconKey = $sepIconKey ?? 'breadcrumb_sep';
    $currentIconKey = $currentIconKey ?? 'breadcrumb_current';
@endphp

<div class="rounded-lg border border-white/10 bg-church-surface/30 p-4 sm:p-5">
    <h2 class="mb-4 text-sm font-semibold text-church-fg">Breadcrumb</h2>
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-[minmax(0,1fr)_auto_minmax(0,1fr)] lg:items-end lg:gap-4">
        <div class="min-w-0 grid grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(9rem,11rem)] sm:items-end">
            <div class="min-w-0">
                <x-admin-field-label class="text-sm text-slate-300">Teks — Beranda</x-admin-field-label>
                <input name="breadcrumb_home" value="{{ old('breadcrumb_home', $data['breadcrumb_home'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
            </div>
            <div class="min-w-0">
                @include('admin.cms.partials.page-icon-field', ['pageKey' => $pageKey, 'iconKey' => $homeIconKey, 'data' => $data, 'label' => 'Ikon'])
            </div>
        </div>

        <div class="flex min-w-0 items-center gap-3 lg:shrink-0 lg:flex-col lg:justify-end lg:gap-0 lg:px-1 lg:pb-1">
            <div class="h-px flex-1 bg-white/10 lg:hidden" aria-hidden="true"></div>
            <div class="w-full min-w-0 max-w-xs shrink-0 sm:max-w-[11rem] lg:max-w-[9.5rem]">
                @include('admin.cms.partials.page-icon-field', ['pageKey' => $pageKey, 'iconKey' => $sepIconKey, 'data' => $data, 'label' => 'Pemisah'])
            </div>
            <div class="h-px flex-1 bg-white/10 lg:hidden" aria-hidden="true"></div>
        </div>

        <div class="min-w-0 grid grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(9rem,11rem)] sm:items-end">
            <div class="min-w-0">
                <x-admin-field-label class="text-sm text-slate-300">Teks — halaman ini</x-admin-field-label>
                <input name="breadcrumb_current" value="{{ old('breadcrumb_current', $data['breadcrumb_current'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
            </div>
            <div class="min-w-0">
                @include('admin.cms.partials.page-icon-field', ['pageKey' => $pageKey, 'iconKey' => $currentIconKey, 'data' => $data, 'label' => 'Ikon'])
            </div>
        </div>
    </div>
</div>
