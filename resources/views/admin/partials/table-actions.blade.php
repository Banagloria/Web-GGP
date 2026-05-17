@php
    $showUrl = $showUrl ?? null;
    $showLabel = $showLabel ?? 'Detail';
    $editUrl = $editUrl ?? null;
    $editLabel = $editLabel ?? 'Edit';
    $deleteUrl = $deleteUrl ?? null;
    $deleteTitle = $deleteTitle ?? 'Hapus data?';
    $deleteMessage = $deleteMessage ?? 'Tindakan ini tidak dapat dibatalkan.';
    $deleteLabel = $deleteLabel ?? 'Ya, hapus';
    $extraClass = $extraClass ?? '';
    $wrapClass = trim('admin-table-actions ' . $extraClass);
@endphp

<div class="{{ $wrapClass }}">
    @if ($showUrl)
        <a
            href="{{ $showUrl }}"
            class="admin-btn-icon admin-btn-icon--view"
            title="{{ $showLabel }}"
            aria-label="{{ $showLabel }}"
        >
            <i class="fa-solid fa-eye" aria-hidden="true"></i>
        </a>
    @endif
    @if ($editUrl)
        <a
            href="{{ $editUrl }}"
            class="admin-btn-icon admin-btn-icon--edit"
            title="{{ $editLabel }}"
            aria-label="{{ $editLabel }}"
        >
            <i class="fa-solid fa-pen" aria-hidden="true"></i>
        </a>
    @endif
    @if ($deleteUrl)
        <form method="post" action="{{ $deleteUrl }}" class="inline">
            @csrf
            @method('DELETE')
            <button
                type="submit"
                class="admin-btn-icon admin-btn-icon--delete"
                title="Hapus"
                aria-label="Hapus"
                data-admin-confirm-submit
                data-confirm-title="{{ $deleteTitle }}"
                data-confirm-message="{{ $deleteMessage }}"
                data-confirm-label="{{ $deleteLabel }}"
            >
                <i class="fa-solid fa-trash" aria-hidden="true"></i>
            </button>
        </form>
    @endif
</div>
