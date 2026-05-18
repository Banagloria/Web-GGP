@php
    $iconPrefix = $card['icon_prefix'];
    $rawSections = old('sections', $detail['sections'] ?? []);
    $sections = [];
    foreach ($rawSections as $si => $section) {
        if (! is_array($section)) {
            continue;
        }
        $section['fields'] = \App\Support\PendaftaranCardCms::sectionFieldsForAdmin($section);
        $sections[$si] = $section;
    }
    if ($sections === []) {
        $sections = [[
            'key' => 'bagian',
            'title' => '',
            'subtitle' => '',
            'icon' => '',
            'fields' => [
                ['name' => '', 'label' => '', 'icon' => '', 'type' => 'text', 'width' => 'full', 'required' => false, 'placeholder' => ''],
            ],
        ]];
    }
    $steps = old('info_panel.steps', $detail['info_panel']['steps'] ?? []);
    if (! is_array($steps) || $steps === []) {
        $steps = [''];
    }
    $tips = old('info_panel.tips', $detail['info_panel']['tips'] ?? []);
    if (! is_array($tips) || $tips === []) {
        $tips = [['icon' => 'fa-solid fa-circle-info', 'text' => '']];
    }
@endphp

<div class="admin-panel space-y-4 rounded-lg border border-white/10 bg-church-surface/40 p-4 sm:p-6">
    <h2 class="text-sm font-semibold text-church-fg">Judul halaman & breadcrumb</h2>
    <div class="grid min-w-0 grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(9rem,11rem)] sm:items-end">
        <div class="min-w-0">
            <x-admin-field-label class="text-sm text-slate-300">Judul halaman (H1)</x-admin-field-label>
            <input name="title" value="{{ old('title', $detail['title']) }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
        </div>
        <div class="min-w-0">
            @include('admin.cms.partials.page-icon-field', ['pageKey' => 'pendaftaran', 'iconKey' => $iconPrefix.'_h1', 'data' => $data])
        </div>
    </div>
    <div class="grid min-w-0 grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(9rem,11rem)] sm:items-start">
        <div class="min-w-0">
            <x-admin-field-label class="text-sm text-slate-300">Pengantar di bawah judul</x-admin-field-label>
            <textarea name="subtitle" rows="2" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">{{ old('subtitle', $detail['subtitle']) }}</textarea>
        </div>
        <div class="sm:pt-[1.375rem]">
            @include('admin.cms.partials.page-icon-field', ['pageKey' => 'pendaftaran', 'iconKey' => $iconPrefix.'_intro', 'data' => $data, 'label' => 'Ikon pengantar'])
        </div>
    </div>
    <div class="grid min-w-0 grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(9rem,11rem)] sm:items-end">
        <div class="min-w-0">
            <x-admin-field-label class="text-sm text-slate-300">Label breadcrumb akhir</x-admin-field-label>
            <input name="leaf_label" value="{{ old('leaf_label', $detail['leaf_label']) }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
        </div>
        <div class="min-w-0">
            @include('admin.cms.partials.page-icon-field', ['pageKey' => 'pendaftaran', 'iconKey' => $iconPrefix.'_leaf', 'data' => $data])
        </div>
    </div>
    <div class="grid gap-4 sm:grid-cols-3 sm:items-end">
        @include('admin.cms.partials.page-icon-field', ['pageKey' => 'pendaftaran', 'iconKey' => 'form_breadcrumb_home', 'data' => $data, 'label' => 'Ikon beranda'])
        @include('admin.cms.partials.page-icon-field', ['pageKey' => 'pendaftaran', 'iconKey' => 'form_breadcrumb_sep', 'data' => $data, 'label' => 'Ikon pemisah'])
        @include('admin.cms.partials.page-icon-field', ['pageKey' => 'pendaftaran', 'iconKey' => 'form_breadcrumb_mid', 'data' => $data, 'label' => 'Ikon “Pendaftaran”'])
    </div>
</div>

<div class="admin-panel space-y-4 rounded-lg border border-white/10 bg-church-surface/40 p-4 sm:p-6">
    <h2 class="text-sm font-semibold text-church-fg">Header formulir</h2>
    <div class="grid gap-3 sm:grid-cols-2 sm:items-end">
        <div>
            <x-admin-field-label class="text-xs text-slate-400">Judul formulir</x-admin-field-label>
            <input name="form_header[title]" value="{{ old('form_header.title', $detail['form_header']['title'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg">
        </div>
        @include('admin.partials.fa-icon-input', [
            'name' => 'form_header[icon]',
            'value' => old('form_header.icon', $detail['form_header']['icon'] ?? ''),
            'label' => 'Ikon header formulir',
            'variant' => 'adjacent',
            'previewDefault' => 'fa-solid fa-pen-to-square',
        ])
    </div>
    <div>
        <x-admin-field-label class="text-xs text-slate-400">Subjudul header (opsional)</x-admin-field-label>
        <input name="form_header[subtitle]" value="{{ old('form_header.subtitle', $detail['form_header']['subtitle'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg">
    </div>
</div>

<div class="space-y-4">
    <div class="flex flex-wrap items-center justify-between gap-2">
        <h2 class="text-sm font-semibold text-church-fg">Kotak bagian formulir</h2>
        <button
            type="button"
            id="cms-pendaftaran-section-add"
            class="rounded-md border border-church-gold/40 bg-church-gold/10 px-3 py-1.5 text-xs font-semibold text-church-gold"
        >
            Tambah bagian
        </button>
    </div>
    <div id="cms-pendaftaran-sections-wrap" class="space-y-4">
        @foreach ($sections as $si => $section)
            @include('admin.cms.partials.pendaftaran-card-section-row', [
                'si' => $si,
                'section' => $section,
            ])
        @endforeach
    </div>
</div>

<template id="cms-pendaftaran-section-template">
    @include('admin.cms.partials.pendaftaran-card-section-row', [
        'si' => 0,
        'section' => [
            'key' => '',
            'title' => '',
            'subtitle' => '',
            'icon' => '',
            'fields' => [
                ['name' => '', 'label' => '', 'icon' => '', 'type' => 'text', 'width' => 'full', 'required' => false, 'placeholder' => ''],
            ],
        ],
    ])
</template>

<div class="admin-panel space-y-4 rounded-lg border border-white/10 bg-church-surface/40 p-4 sm:p-6">
    <h2 class="text-sm font-semibold text-church-fg">Persetujuan & tombol kirim</h2>
    <div>
        <x-admin-field-label class="text-xs text-slate-400">Teks persetujuan (checkbox)</x-admin-field-label>
        <textarea name="consent[text]" rows="2" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg">{{ old('consent.text', $detail['consent']['text'] ?? '') }}</textarea>
    </div>
    <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(9rem,11rem)] sm:items-end">
        <div>
            <x-admin-field-label class="text-xs text-slate-400">Label tombol kirim</x-admin-field-label>
            <input name="consent[submit_label]" value="{{ old('consent.submit_label', $detail['consent']['submit_label'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg">
        </div>
        @include('admin.cms.partials.page-icon-field', ['pageKey' => 'pendaftaran', 'iconKey' => $iconPrefix.'_submit', 'data' => $data, 'label' => 'Ikon tombol kirim'])
    </div>
</div>

<div class="admin-panel space-y-4 rounded-lg border border-white/10 bg-church-surface/40 p-4 sm:p-6">
    <h2 class="text-sm font-semibold text-church-fg">Panel informasi (kanan)</h2>
    <div class="grid gap-3 sm:grid-cols-2 sm:items-end">
        <div>
            <x-admin-field-label class="text-xs text-slate-400">Judul panel</x-admin-field-label>
            <input name="info_panel[title]" value="{{ old('info_panel.title', $detail['info_panel']['title'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg">
        </div>
        @include('admin.partials.fa-icon-input', [
            'name' => 'info_panel[icon]',
            'value' => old('info_panel.icon', $detail['info_panel']['icon'] ?? ''),
            'label' => 'Ikon judul panel',
            'variant' => 'adjacent',
            'previewDefault' => 'fa-solid fa-route',
        ])
    </div>
    <div>
        <x-admin-field-label class="text-xs text-slate-400">Subjudul panel</x-admin-field-label>
        <input name="info_panel[subtitle]" value="{{ old('info_panel.subtitle', $detail['info_panel']['subtitle'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg">
    </div>
    <div class="grid gap-3 sm:grid-cols-2 sm:items-end">
        <div>
            <x-admin-field-label class="text-xs text-slate-400">Judul blok tips</x-admin-field-label>
            <input name="info_panel[tips_heading]" value="{{ old('info_panel.tips_heading', $detail['info_panel']['tips_heading'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg">
        </div>
        @include('admin.partials.fa-icon-input', [
            'name' => 'info_panel[tips_heading_icon]',
            'value' => old('info_panel.tips_heading_icon', $detail['info_panel']['tips_heading_icon'] ?? ''),
            'label' => 'Ikon judul tips',
            'variant' => 'adjacent',
            'previewDefault' => 'fa-solid fa-lightbulb',
        ])
    </div>
    <div class="space-y-2">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <p class="text-xs font-medium text-slate-400">Alur pendaftaran</p>
            <button type="button" id="cms-pendaftaran-step-add" class="rounded-md border border-church-gold/40 bg-church-gold/10 px-3 py-1.5 text-xs font-semibold text-church-gold">
                Tambah langkah
            </button>
        </div>
        <div id="cms-pendaftaran-steps-wrap" class="space-y-2">
            @foreach ($steps as $sti => $step)
                <div class="cms-pendaftaran-step-row flex gap-2" data-pendaftaran-step-row>
                    <input name="info_panel[steps][{{ $sti }}]" value="{{ old('info_panel.steps.'.$sti, is_string($step) ? $step : '') }}" placeholder="Langkah {{ $sti + 1 }}" class="min-w-0 flex-1 rounded-md border border-white/15 bg-church-surface text-sm text-church-fg">
                    <button type="button" data-pendaftaran-step-remove class="shrink-0 px-2 text-xs text-red-400 hover:underline">Hapus</button>
                </div>
            @endforeach
        </div>
    </div>
    <div class="space-y-3">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <p class="text-xs font-medium text-slate-400">Tips</p>
            <button type="button" id="cms-pendaftaran-tip-add" class="rounded-md border border-church-gold/40 bg-church-gold/10 px-3 py-1.5 text-xs font-semibold text-church-gold">
                Tambah tips
            </button>
        </div>
        <div id="cms-pendaftaran-tips-wrap" class="space-y-3">
            @foreach ($tips as $ti => $tip)
                <div class="cms-pendaftaran-tip-row grid gap-2 rounded-md border border-white/10 p-3 sm:grid-cols-[minmax(9rem,11rem)_minmax(0,1fr)_auto]" data-pendaftaran-tip-row>
                    @include('admin.partials.fa-icon-input', [
                        'name' => 'info_panel[tips]['.$ti.'][icon]',
                        'value' => old('info_panel.tips.'.$ti.'.icon', $tip['icon'] ?? ''),
                        'label' => 'Ikon',
                        'variant' => 'adjacent',
                        'previewDefault' => 'fa-solid fa-circle-info',
                    ])
                    <div>
                        <x-admin-field-label class="text-xs text-slate-400">Teks tips</x-admin-field-label>
                        <textarea name="info_panel[tips][{{ $ti }}][text]" rows="2" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg">{{ old('info_panel.tips.'.$ti.'.text', $tip['text'] ?? '') }}</textarea>
                    </div>
                    <button type="button" data-pendaftaran-tip-remove class="self-start px-2 pt-6 text-xs text-red-400 hover:underline sm:pt-8">Hapus</button>
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
    @include('admin.cms.partials.pendaftaran-card-detail-scripts')
@endpush
