@php
    $field = $field ?? [];
    $fname = $field['name'] ?? '';
    $ftype = $field['type'] ?? 'text';
    $flabel = $field['label'] ?? $fname;
    $fplaceholder = $field['placeholder'] ?? '';
    $freq = ! empty($field['required']);
    $fid = 'cms-'.$fname;
    $isTextarea = $ftype === 'textarea';
    $width = $field['width'] ?? ($isTextarea ? 'panjang' : 'setengah');
    $isFullWidth = $width === 'panjang';
    $fieldIcon = match (true) {
        $ftype === 'email' => 'fa-solid fa-envelope',
        $ftype === 'number' => 'fa-solid fa-hashtag',
        $ftype === 'textarea' => 'fa-solid fa-comment-dots',
        in_array($fname, ['name', 'nama'], true) || str_contains($fname, 'nama') => 'fa-solid fa-user',
        in_array($fname, ['phone', 'telepon', 'wa', 'whatsapp'], true) || str_contains($fname, 'phone') || str_contains($fname, 'tel') => 'fa-solid fa-phone',
        $fname === 'subject' || str_contains($fname, 'subjek') => 'fa-solid fa-tag',
        default => 'fa-solid fa-pen-to-square',
    };
    $inputType = match ($ftype) {
        'email' => 'email',
        'number' => 'number',
        default => 'text',
    };
@endphp

<div @class([
    'min-w-0',
    $isFullWidth ? 'sm:col-span-2' : '',
])>
    <label for="{{ $fid }}" class="reg-field-label">
        <i class="{{ $fieldIcon }} text-xs text-church-gold/75" aria-hidden="true"></i>
        <span>{{ $flabel }}</span>
        @if ($freq)
            <span class="text-church-gold/90" aria-hidden="true">*</span>
        @endif
    </label>
    @if ($isTextarea)
        <textarea
            id="{{ $fid }}"
            name="{{ $fname }}"
            rows="{{ (int) ($field['rows'] ?? 5) }}"
            @if ($freq) required @endif
            placeholder="{{ $fplaceholder }}"
            class="input-public-dark min-h-[5.5rem] resize-y"
        >{{ old($fname) }}</textarea>
    @else
        <input
            id="{{ $fid }}"
            type="{{ $inputType }}"
            name="{{ $fname }}"
            value="{{ old($fname) }}"
            @if ($freq) required @endif
            placeholder="{{ $fplaceholder }}"
            class="input-public-dark"
            @if ($ftype === 'email') autocomplete="email" @endif
            @if ($ftype === 'number') inputmode="numeric" @endif
            @if (in_array($fname, ['name', 'nama'], true)) autocomplete="name" @endif
            @if (str_contains($fname, 'phone') || str_contains($fname, 'tel')) autocomplete="tel" @endif
        >
    @endif
    @error($fname)
        <p class="mt-1.5 flex items-start gap-1.5 text-sm text-red-400">
            <i class="fa-solid fa-circle-exclamation mt-0.5 shrink-0 text-xs" aria-hidden="true"></i>
            <span>{{ $message }}</span>
        </p>
    @enderror
</div>
