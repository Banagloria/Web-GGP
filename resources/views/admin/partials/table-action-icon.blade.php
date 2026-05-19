@php
    $href = $href ?? null;
    $formAction = $formAction ?? null;
    $method = $method ?? null;
    $icon = $icon ?? 'fa-solid fa-circle';
    $label = $label ?? '';
    $variant = $variant ?? 'gold';
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
    $iconClass = $variant === 'delete'
        ? 'admin-btn-icon admin-btn-icon--delete'
        : 'admin-btn-icon admin-btn-icon--gold';
@endphp

@if ($formAction)
    <form method="post" action="{{ $formAction }}" class="contents">
        @csrf
        @if (strtoupper((string) $method) === 'DELETE')
            @method('DELETE')
        @endif
        <button
            type="submit"
            class="{{ $iconClass }}"
            title="{{ $label }}"
            aria-label="{{ $label }}"
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
            <i class="{{ $icon }}" aria-hidden="true"></i>
        </button>
    </form>
@elseif ($href)
    <a
        href="{{ $href }}"
        class="{{ $iconClass }}"
        title="{{ $label }}"
        aria-label="{{ $label }}"
    >
        <i class="{{ $icon }}" aria-hidden="true"></i>
    </a>
@endif
