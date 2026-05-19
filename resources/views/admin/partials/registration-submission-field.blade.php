@php
    $name = $field['name'] ?? '';
    $label = $field['label'] ?? $name;
    $type = $field['type'] ?? 'text';
    $required = ! empty($field['required']);
    $placeholder = $field['placeholder'] ?? '';
    $rows = (int) ($field['rows'] ?? 3);
    $value = $value ?? old($name);
    $inputClass = 'mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg shadow-sm';
    $selectOptions = [];
    foreach ($field['select_options'] ?? [] as $opt) {
        if (! is_array($opt)) {
            continue;
        }
        $selectOptions[(string) ($opt['value'] ?? '')] = (string) ($opt['label'] ?? '');
    }
@endphp

<div>
    <x-admin-field-label>{{ $label }}@if ($required)<span class="text-church-gold"> *</span>@endif</x-admin-field-label>

    @if ($type === 'textarea')
        <textarea
            name="{{ $name }}"
            rows="{{ $rows }}"
            @if ($required) required @endif
            placeholder="{{ $placeholder }}"
            class="{{ $inputClass }} min-h-[5.5rem] resize-y"
        >{{ $value }}</textarea>
    @elseif ($type === 'select')
        <select name="{{ $name }}" @if ($required) required @endif class="{{ $inputClass }}">
            @foreach ($selectOptions as $optValue => $optLabel)
                <option value="{{ $optValue }}" @selected((string) $value === (string) $optValue)>{{ $optLabel }}</option>
            @endforeach
        </select>
    @elseif ($type === 'file')
        @if (! empty($currentFileUrl))
            <p class="mt-1 text-sm text-slate-400">
                Berkas saat ini:
                <a href="{{ $currentFileUrl }}" target="_blank" rel="noopener noreferrer" class="text-church-gold hover:underline">Unduh</a>
            </p>
        @endif
        <input
            type="file"
            name="{{ $name }}"
            class="{{ $inputClass }}"
            accept=".pdf,.jpg,.jpeg,.png,.webp,.doc,.docx"
        >
        <p class="mt-1 text-xs text-slate-500">Kosongkan jika tidak ingin mengganti berkas.</p>
    @else
        @php
            $inputType = match ($type) {
                'email' => 'email',
                'tel' => 'tel',
                'date' => 'date',
                'number' => 'number',
                default => 'text',
            };
        @endphp
        <input
            type="{{ $inputType }}"
            name="{{ $name }}"
            value="{{ $value }}"
            @if ($required) required @endif
            placeholder="{{ $placeholder }}"
            class="{{ $inputClass }}"
        >
    @endif

    @error($name)
        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
    @enderror
</div>
