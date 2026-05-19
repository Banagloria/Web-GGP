@php
    $showUrl = $showUrl ?? null;
    $showLabel = $showLabel ?? 'Detail';
    $editUrl = $editUrl ?? null;
    $editLabel = $editLabel ?? 'Edit';
    $deleteUrl = $deleteUrl ?? null;
    $confirmDelete = $confirmDelete ?? true;
    $deleteTitle = $deleteTitle ?? 'Hapus data?';
    $deleteMessage = $deleteMessage ?? 'Tindakan ini tidak dapat dibatalkan.';
    $deleteLabel = $deleteLabel ?? 'Ya, hapus';
    $extraClass = $extraClass ?? '';

    $actionCount = (int) (bool) $showUrl + (int) (bool) $editUrl + (int) (bool) $deleteUrl;
    $layoutClass = match (true) {
        $actionCount === 4 => 'admin-table-actions--n4',
        $actionCount >= 5 => 'admin-table-actions--n5',
        default => 'admin-table-actions--n3',
    };
    $wrapClass = trim('admin-table-actions '.$layoutClass.' '.$extraClass);
@endphp

<div class="{{ $wrapClass }}">
    @if ($showUrl)
        @include('admin.partials.table-action-icon', [
            'href' => $showUrl,
            'icon' => 'fa-solid fa-eye',
            'label' => $showLabel,
        ])
    @endif
    @if ($editUrl)
        @include('admin.partials.table-action-icon', [
            'href' => $editUrl,
            'icon' => 'fa-solid fa-pen',
            'label' => $editLabel,
        ])
    @endif
    @if ($deleteUrl)
        @include('admin.partials.table-action-icon', [
            'formAction' => $deleteUrl,
            'method' => 'DELETE',
            'icon' => 'fa-solid fa-trash',
            'label' => 'Hapus',
            'variant' => 'delete',
            'confirmSubmit' => $confirmDelete,
            'confirmVariant' => 'delete',
            'confirmTitle' => $deleteTitle,
            'confirmMessage' => $deleteMessage,
            'confirmLabel' => $deleteLabel,
        ])
    @endif
</div>
