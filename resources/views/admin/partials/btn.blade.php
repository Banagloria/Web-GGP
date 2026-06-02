@php
    $variant = $variant ?? 'primary';
    $href = $href ?? null;
    // $formAction — jangan pakai $action: variabel itu bocor dari x-admin-*-page (URL form utama).
    $formAction = $formAction ?? null;
    $method = $method ?? null;
    $type = $type ?? 'button';
    $size = $size ?? null;
    $icon = $icon ?? null;
    $label = $label ?? '';
    $form = $form ?? null;
    $target = $target ?? null;
    $rel = $rel ?? null;
    $extraClass = $extraClass ?? '';
    $confirmSubmit = $confirmSubmit ?? false;
    $confirmVariant = $confirmVariant ?? 'delete';
    $confirmTitle = $confirmTitle ?? 'Konfirmasi';
    $confirmMessage = $confirmMessage ?? 'Lanjutkan?';
    $confirmLabel = $confirmLabel ?? 'Lanjutkan';
    $confirmWhatsapp = $confirmWhatsapp ?? false;
    $confirmPhone = $confirmPhone ?? '';
    $confirmWaDefault = $confirmWaDefault ?? '';
    if ($confirmWhatsapp && $confirmPhone !== '') {
        $confirmPhone = \App\Services\RegistrationSubmissionService::displayPhone(
            \App\Services\RegistrationSubmissionService::normalizePhoneText((string) $confirmPhone)
        );
    }

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
        . ' shrink-0 ' . $extraClass
    );
@endphp

@if ($formAction)
    <form method="post" action="{{ $formAction }}" class="inline shrink-0">
        @csrf
        @if (strtoupper((string) $method) === 'DELETE')
            @method('DELETE')
        @endif
        <button
            type="submit"
            class="{{ $btnClass }}"
            @if ($confirmSubmit)
                data-admin-confirm-submit
                data-confirm-variant="{{ $confirmVariant }}"
                data-confirm-title="{{ $confirmTitle }}"
                data-confirm-message="{{ $confirmMessage }}"
                data-confirm-label="{{ $confirmLabel }}"
                @if ($confirmWhatsapp) data-confirm-whatsapp="1" @endif
                @if ($confirmPhone !== '') data-confirm-phone="{{ $confirmPhone }}" @endif
                @if ($confirmWaDefault !== '') data-confirm-wa-default="{{ $confirmWaDefault }}" @endif
            @endif
        >
            @if ($icon)
                <i class="{{ $icon }}" aria-hidden="true"></i>
            @endif
            {{ $label }}
        </button>
    </form>
@elseif ($href)
    <a
        href="{{ $href }}"
        class="{{ $btnClass }}"
        @if ($target) target="{{ $target }}" @endif
        @if ($rel) rel="{{ $rel }}" @endif
    >
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
        @if ($confirmSubmit)
            data-admin-confirm-submit
            data-confirm-variant="{{ $confirmVariant }}"
            data-confirm-title="{{ $confirmTitle }}"
            data-confirm-message="{{ $confirmMessage }}"
            data-confirm-label="{{ $confirmLabel }}"
            @if ($confirmWhatsapp) data-confirm-whatsapp="1" @endif
            @if ($confirmPhone !== '') data-confirm-phone="{{ $confirmPhone }}" @endif
            @if ($confirmWaDefault !== '') data-confirm-wa-default="{{ $confirmWaDefault }}" @endif
        @endif
    >
        @if ($icon)
            <i class="{{ $icon }}" aria-hidden="true"></i>
        @endif
        {{ $label }}
    </button>
@endif
