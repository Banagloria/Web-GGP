<div class="cms-pendaftaran-section-row admin-panel space-y-4 rounded-lg border border-church-gold/20 bg-church-surface/40 p-4 sm:p-6" data-pendaftaran-section-row>
    <div class="flex flex-wrap items-center justify-between gap-2">
        <h2 class="text-sm font-semibold text-church-gold">Bagian: <span data-pendaftaran-section-title-preview>{{ old('sections.'.$si.'.title', $section['title'] ?? '') }}</span></h2>
        <button type="button" data-pendaftaran-section-remove class="text-xs text-red-400 hover:underline">Hapus bagian</button>
    </div>
    <input type="hidden" name="sections[{{ $si }}][key]" value="{{ old('sections.'.$si.'.key', $section['key'] ?? '') }}">
    <div class="grid gap-3 sm:grid-cols-2 sm:items-end">
        <div>
            <x-admin-field-label class="text-xs text-slate-400">Judul bagian</x-admin-field-label>
            <input name="sections[{{ $si }}][title]" value="{{ old('sections.'.$si.'.title', $section['title'] ?? '') }}" data-pendaftaran-section-title class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg">
        </div>
        @include('admin.partials.fa-icon-input', [
            'name' => 'sections['.$si.'][icon]',
            'value' => old('sections.'.$si.'.icon', $section['icon'] ?? ''),
            'label' => 'Ikon bagian',
            'variant' => 'adjacent',
            'previewDefault' => 'fa-solid fa-circle',
        ])
    </div>
    <div>
        <x-admin-field-label class="text-xs text-slate-400">Keterangan bagian</x-admin-field-label>
        <input name="sections[{{ $si }}][subtitle]" value="{{ old('sections.'.$si.'.subtitle', $section['subtitle'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg">
    </div>

    <div class="space-y-3 rounded-md border border-white/10 bg-black/10 p-3 sm:p-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Input formulir</p>
            <button
                type="button"
                data-pendaftaran-field-add
                data-section-index="{{ $si }}"
                class="rounded-md border border-church-gold/40 bg-church-gold/10 px-3 py-1.5 text-xs font-semibold text-church-gold"
            >
                Tambah input
            </button>
        </div>
        <div class="space-y-3" data-pendaftaran-fields-wrap data-section-index="{{ $si }}">
            @foreach ($section['fields'] ?? [] as $fi => $field)
                @include('admin.cms.partials.pendaftaran-card-field-row', [
                    'si' => $si,
                    'fi' => $fi,
                    'field' => $field,
                ])
            @endforeach
        </div>
    </div>
</div>
