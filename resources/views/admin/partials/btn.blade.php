@php
    $variant = $variant ?? 'primary';
    $href = $href ?? null;
    $type = $type ?? 'button';
    $size = $size ?? null;
    $icon = $icon ?? null;
    $label = $label ?? '';
    $form = $form ?? null;
    $extraClass = $extraClass ?? '';

    $variants = [
        'primary' => 'admin-btn--primary',
        'secondary' => 'admin-btn--secondary',
        'danger' => 'admin-btn--danger',
        'danger-solid' => 'admin-btn--danger-solid',
        'neutral' => 'admin-btn--neutral',
    ];
    $btnClass = trim(
        'admin-btn ' . ($variants[$variant] ?? $variants['primary'])
        . ($size === 'sm' ? ' admin-btn--sm' : '')
        . ' ' . $extraClass
    );
@endphp

@if ($href)
    <a href="{{ $href }}" class="{{ $btnClass }}">
        @if ($icon)
            <i class="{{ $icon }}" aria-hidden="true"></i>
        @endif
        {{ $label }}
    </a>
@else
    <button
        type="{{ $type }}"
        class="{{ $btnClass }}"
        @if ($form) form="{{ $form }}" @endif
    >
        @if ($icon)
            <i class="{{ $icon }}" aria-hidden="true"></i>
        @endif
        {{ $label }}
    </button>
@endif
