@php
    $showUrl = $showUrl ?? null;
    $editUrl = $editUrl ?? null;
    $deleteUrl = $deleteUrl ?? null;
    $acceptUrl = $acceptUrl ?? null;
    $rejectUrl = $rejectUrl ?? null;
    $canReview = $canReview ?? false;
    $deleteMessage = $deleteMessage ?? 'Data pendaftaran ini akan dihapus permanen.';
    $confirmPhone = $confirmPhone ?? '';

    $actionCount = (int) ($canReview && $acceptUrl)
        + (int) ($canReview && $rejectUrl)
        + (int) (bool) $editUrl
        + (int) (bool) $showUrl
        + (int) (bool) $deleteUrl;
    $layoutClass = match (true) {
        $actionCount === 4 => 'admin-table-actions--n4',
        $actionCount >= 5 => 'admin-table-actions--n5',
        default => 'admin-table-actions--n3',
    };
@endphp

<div class="admin-table-actions {{ $layoutClass }}">
    @if ($canReview && $acceptUrl)
        @include('admin.partials.table-action-icon', [
            'formAction' => $acceptUrl,
            'icon' => 'fa-solid fa-check',
            'label' => 'Terima',
            'confirmSubmit' => true,
            'confirmVariant' => 'accept',
            'confirmTitle' => 'Terima pendaftaran?',
            'confirmMessage' => 'Data akan dipindahkan ke halaman Data.',
            'confirmLabel' => 'Ya, terima',
            'confirmWhatsapp' => true,
            'confirmPhone' => $confirmPhone,
            'confirmWaDefault' => 'Selamat, pendaftaran Anda telah kami terima. Terima kasih.',
        ])
    @endif
    @if ($canReview && $rejectUrl)
        @include('admin.partials.table-action-icon', [
            'formAction' => $rejectUrl,
            'icon' => 'fa-solid fa-xmark',
            'label' => 'Tolak',
            'confirmSubmit' => true,
            'confirmVariant' => 'reject',
            'confirmTitle' => 'Tolak pendaftaran?',
            'confirmMessage' => 'Data akan dihapus permanen.',
            'confirmLabel' => 'Ya, tolak',
            'confirmWhatsapp' => true,
            'confirmPhone' => $confirmPhone,
            'confirmWaDefault' => 'Mohon maaf, pendaftaran Anda belum dapat kami terima saat ini.',
        ])
    @endif
    @if ($editUrl)
        @include('admin.partials.table-action-icon', [
            'href' => $editUrl,
            'icon' => 'fa-solid fa-pen',
            'label' => 'Edit',
        ])
    @endif
    @if ($showUrl)
        @include('admin.partials.table-action-icon', [
            'href' => $showUrl,
            'icon' => 'fa-solid fa-eye',
            'label' => 'Detail',
        ])
    @endif
    @if ($deleteUrl)
        @include('admin.partials.table-action-icon', [
            'formAction' => $deleteUrl,
            'method' => 'DELETE',
            'icon' => 'fa-solid fa-trash',
            'label' => 'Hapus',
            'variant' => 'delete',
            'confirmSubmit' => true,
            'confirmVariant' => 'delete',
            'confirmTitle' => 'Hapus data?',
            'confirmMessage' => $deleteMessage,
            'confirmLabel' => 'Ya, hapus',
        ])
    @endif
</div>
