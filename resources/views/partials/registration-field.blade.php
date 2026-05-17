@props([
    'name',
    'label',
    'icon' => 'fa-solid fa-pen',
    'type' => 'text',
    'required' => false,
    'value' => null,
    'rows' => 3,
    'placeholder' => '',
    'selectOptions' => null,
])

@php
    $fieldId = 'reg-field-'.$name;
    $resolvedValue = $value ?? old($name);
    $isTextarea = $type === 'textarea';
    $isSelect = $type === 'select';
    $isFile = $type === 'file';
    $isDate = $type === 'date';
    $isNumber = $type === 'number';
    $inputType = $isNumber ? 'text' : $type;
    $inputClass = 'input-public-dark'
        .($isDate ? ' input-public-dark--date' : '')
        .($isNumber ? ' input-public-dark--numeric' : '');
@endphp

<div {{ $attributes->merge(['class' => 'min-w-0']) }}>
    <label for="{{ $fieldId }}" class="reg-field-label">
        <i class="{{ $icon }} text-xs text-church-gold/75" aria-hidden="true"></i>
        <span>{{ $label }}</span>
        @if ($required)
            <span class="text-church-gold/90" aria-hidden="true">*</span>
        @endif
    </label>
    <div class="relative">
        @if ($isTextarea)
            <textarea
                id="{{ $fieldId }}"
                name="{{ $name }}"
                rows="{{ (int) $rows }}"
                @if ($required) required @endif
                placeholder="{{ $placeholder }}"
                class="{{ $inputClass }} min-h-[5.5rem] resize-y"
            >{{ $resolvedValue }}</textarea>
        @elseif ($isSelect)
            <select
                id="{{ $fieldId }}"
                name="{{ $name }}"
                @if ($required) required @endif
                class="{{ $inputClass }}"
            >
                @foreach ($selectOptions ?? [] as $optValue => $optLabel)
                    <option value="{{ $optValue }}" @selected((string) $resolvedValue === (string) $optValue)>{{ $optLabel }}</option>
                @endforeach
            </select>
        @elseif ($isFile)
            <input
                id="{{ $fieldId }}"
                type="file"
                name="{{ $name }}"
                @if ($required) required @endif
                class="{{ $inputClass }}"
                accept=".pdf,.jpg,.jpeg,.png,.webp,.doc,.docx"
            >
        @else
            <input
                id="{{ $fieldId }}"
                type="{{ $inputType }}"
                name="{{ $name }}"
                value="{{ $resolvedValue }}"
                @if ($required) required @endif
                @if ($isNumber) inputmode="numeric" autocomplete="off" @endif
                placeholder="{{ $placeholder }}"
                class="{{ $inputClass }}"
            >
        @endif
    </div>
    @error($name)
        <p class="mt-1.5 flex items-start gap-1.5 text-sm text-red-400">
            <i class="fa-solid fa-circle-exclamation mt-0.5 shrink-0 text-xs" aria-hidden="true"></i>
            <span>{{ $message }}</span>
        </p>
    @enderror
</div>
