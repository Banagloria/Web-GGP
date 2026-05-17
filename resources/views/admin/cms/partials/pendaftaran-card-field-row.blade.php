@php
    $fieldType = old('sections.'.$si.'.fields.'.$fi.'.type', $field['type'] ?? 'text');
    $fieldWidth = old('sections.'.$si.'.fields.'.$fi.'.width', $field['width'] ?? 'full');
    $fieldRequired = old('sections.'.$si.'.fields.'.$fi.'.required', ! empty($field['required']) ? '1' : '0');
    $selectOptions = old('sections.'.$si.'.fields.'.$fi.'.select_options', $field['select_options'] ?? []);
    if (! is_array($selectOptions)) {
        $selectOptions = [];
    }
@endphp

<div class="cms-pendaftaran-field-row rounded-md border border-white/10 bg-church-surface/50 p-3 space-y-3" data-pendaftaran-field-row>
    <div class="flex flex-wrap items-center justify-between gap-2">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Input</p>
        <button type="button" data-pendaftaran-field-remove class="text-xs text-red-400 hover:underline">Hapus</button>
    </div>

    <div class="grid gap-2 sm:grid-cols-2">
        <div>
            <x-admin-field-label class="text-xs text-slate-400">Nama field (sistem)</x-admin-field-label>
            <input name="sections[{{ $si }}][fields][{{ $fi }}][name]" value="{{ old('sections.'.$si.'.fields.'.$fi.'.name', $field['name'] ?? '') }}" pattern="[a-zA-Z0-9_]+" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface font-mono text-sm text-church-fg" placeholder="contoh: full_name">
        </div>
        <div>
            <x-admin-field-label class="text-xs text-slate-400">Label</x-admin-field-label>
            <input name="sections[{{ $si }}][fields][{{ $fi }}][label]" value="{{ old('sections.'.$si.'.fields.'.$fi.'.label', $field['label'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg">
        </div>
    </div>

    <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-4">
        <div>
            <x-admin-field-label class="text-xs text-slate-400">Tipe</x-admin-field-label>
            <select name="sections[{{ $si }}][fields][{{ $fi }}][type]" data-pendaftaran-field-type class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg">
                @foreach (['text' => 'Teks', 'email' => 'Email', 'tel' => 'Telepon', 'date' => 'Tanggal', 'number' => 'Angka', 'textarea' => 'Area teks', 'select' => 'Pilihan', 'file' => 'Berkas'] as $val => $lbl)
                    <option value="{{ $val }}" @selected($fieldType === $val)>{{ $lbl }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <x-admin-field-label class="text-xs text-slate-400">Lebar</x-admin-field-label>
            <select name="sections[{{ $si }}][fields][{{ $fi }}][width]" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg">
                <option value="full" @selected($fieldWidth === 'full')>Penuh</option>
                <option value="half" @selected($fieldWidth === 'half')>Setengah</option>
            </select>
        </div>
        <div>
            <x-admin-field-label class="text-xs text-slate-400">Placeholder</x-admin-field-label>
            <input name="sections[{{ $si }}][fields][{{ $fi }}][placeholder]" value="{{ old('sections.'.$si.'.fields.'.$fi.'.placeholder', $field['placeholder'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg">
        </div>
        <label class="flex items-end gap-2 pb-2 text-sm text-slate-300">
            <input type="hidden" name="sections[{{ $si }}][fields][{{ $fi }}][required]" value="0">
            <input type="checkbox" name="sections[{{ $si }}][fields][{{ $fi }}][required]" value="1" @checked($fieldRequired === '1' || $fieldRequired === 1 || $fieldRequired === true) class="rounded border-white/20">
            <i class="fa-solid fa-asterisk shrink-0 text-church-gold/80" aria-hidden="true"></i>
            <span>Wajib</span>
        </label>
    </div>

    @include('admin.partials.fa-icon-input', [
        'name' => 'sections['.$si.'][fields]['.$fi.'][icon]',
        'value' => old('sections.'.$si.'.fields.'.$fi.'.icon', $field['icon'] ?? ''),
        'label' => 'Ikon label',
        'variant' => 'adjacent',
        'previewDefault' => 'fa-solid fa-pen',
    ])

    <div data-pendaftaran-field-textarea @class(['hidden' => $fieldType !== 'textarea'])>
        <x-admin-field-label class="text-xs text-slate-400">Baris textarea</x-admin-field-label>
        <input type="number" name="sections[{{ $si }}][fields][{{ $fi }}][rows]" value="{{ old('sections.'.$si.'.fields.'.$fi.'.rows', $field['rows'] ?? 3) }}" min="2" max="20" class="mt-1 max-w-[8rem] rounded-md border border-white/15 bg-church-surface text-sm text-church-fg">
    </div>

    <div data-pendaftaran-field-select @class(['hidden' => $fieldType !== 'select', 'space-y-2' => true])>
        <p class="text-xs text-slate-400">Opsi pilihan</p>
        <div class="space-y-2" data-pendaftaran-select-options>
            @forelse ($selectOptions as $oi => $opt)
                <div class="grid grid-cols-2 gap-2" data-pendaftaran-select-option>
                    <input name="sections[{{ $si }}][fields][{{ $fi }}][select_options][{{ $oi }}][value]" value="{{ old('sections.'.$si.'.fields.'.$fi.'.select_options.'.$oi.'.value', $opt['value'] ?? '') }}" placeholder="Nilai" class="rounded-md border border-white/15 bg-church-surface text-sm text-church-fg">
                    <div class="flex gap-2">
                        <input name="sections[{{ $si }}][fields][{{ $fi }}][select_options][{{ $oi }}][label]" value="{{ old('sections.'.$si.'.fields.'.$fi.'.select_options.'.$oi.'.label', $opt['label'] ?? '') }}" placeholder="Label" class="min-w-0 flex-1 rounded-md border border-white/15 bg-church-surface text-sm text-church-fg">
                        <button type="button" data-pendaftaran-select-option-remove class="shrink-0 text-xs text-red-400 hover:underline">×</button>
                    </div>
                </div>
            @empty
                <div class="grid grid-cols-2 gap-2" data-pendaftaran-select-option>
                    <input name="sections[{{ $si }}][fields][{{ $fi }}][select_options][0][value]" value="" placeholder="Nilai" class="rounded-md border border-white/15 bg-church-surface text-sm text-church-fg">
                    <div class="flex gap-2">
                        <input name="sections[{{ $si }}][fields][{{ $fi }}][select_options][0][label]" value="" placeholder="Label" class="min-w-0 flex-1 rounded-md border border-white/15 bg-church-surface text-sm text-church-fg">
                        <button type="button" data-pendaftaran-select-option-remove class="shrink-0 text-xs text-red-400 hover:underline">×</button>
                    </div>
                </div>
            @endforelse
        </div>
        <button type="button" data-pendaftaran-select-option-add class="text-xs text-church-gold hover:underline">+ Tambah opsi</button>
    </div>
</div>
