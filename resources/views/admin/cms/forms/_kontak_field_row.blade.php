@php
    $f = $f ?? [];
    $i = $i ?? 0;
    $fieldTypes = ['text' => 'Teks', 'email' => 'Email', 'number' => 'Angka', 'textarea' => 'Textarea'];
    $fieldWidths = ['setengah' => 'Setengah', 'panjang' => 'Panjang'];
    $defaultWidth = ($f['type'] ?? 'text') === 'textarea' ? 'panjang' : 'setengah';
@endphp
<div class="cms-kontak-field-row space-y-4 rounded-lg border border-white/10 bg-church-surface/40 p-5 sm:p-6">
    <div class="flex justify-end">
        <button type="button" data-cms-kontak-remove class="text-xs text-red-400 hover:underline">Hapus field</button>
    </div>
    <div class="grid gap-4 sm:grid-cols-3">
        <div><x-admin-field-label class="text-xs text-slate-400">Nama atribut field</x-admin-field-label><input name="form_fields[{{ $i }}][name]" value="{{ old('form_fields.'.$i.'.name', $f['name'] ?? '') }}" required pattern="[a-zA-Z0-9_]+" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface font-mono text-sm text-church-fg"></div>
        <div>
            <x-admin-field-label class="text-xs text-slate-400">Tipe</x-admin-field-label>
            <select name="form_fields[{{ $i }}][type]" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg">
                @foreach ($fieldTypes as $value => $label)
                    <option value="{{ $value }}" @selected(old('form_fields.'.$i.'.type', $f['type'] ?? 'text') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <x-admin-field-label class="text-xs text-slate-400">Lebar kolom</x-admin-field-label>
            <select name="form_fields[{{ $i }}][width]" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg">
                @foreach ($fieldWidths as $value => $label)
                    <option value="{{ $value }}" @selected(old('form_fields.'.$i.'.width', $f['width'] ?? $defaultWidth) === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div><x-admin-field-label class="text-xs text-slate-400">Label</x-admin-field-label><input name="form_fields[{{ $i }}][label]" value="{{ old('form_fields.'.$i.'.label', $f['label'] ?? '') }}" required class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg"></div>
    <div><x-admin-field-label class="text-xs text-slate-400">Placeholder</x-admin-field-label><input name="form_fields[{{ $i }}][placeholder]" value="{{ old('form_fields.'.$i.'.placeholder', $f['placeholder'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg"></div>
    <div class="flex items-center gap-2">
        <input type="hidden" name="form_fields[{{ $i }}][required]" value="0">
        <input type="checkbox" name="form_fields[{{ $i }}][required]" value="1" class="size-4 rounded border-white/20 bg-church-surface" @checked(old('form_fields.'.$i.'.required', ! empty($f['required'])))>
        <span class="text-sm text-slate-300">Wajib diisi</span>
    </div>
</div>
