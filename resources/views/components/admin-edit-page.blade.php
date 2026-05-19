@props([
    'backHref',
    'backLabel',
    'icon' => 'fa-solid fa-pen-to-square',
    'title',
    'action',
    'method' => 'put',
    'formId' => null,
    'enctype' => null,
    'cancelHref' => null,
    'cancelLabel' => 'Batal',
    'submitLabel' => 'Simpan',
    'deleteUrl' => null,
    'deleteTitle' => 'Hapus data?',
    'deleteMessage' => 'Tindakan ini tidak dapat dibatalkan.',
    'wide' => false,
    'cardWrapped' => true,
])

@php
    $cancelHref = $cancelHref ?? $backHref;
    $formId = $formId ?? 'admin-edit-form';
    $maxWidth = $wide ? 'max-w-full' : 'max-w-3xl sm:max-w-4xl';
@endphp

<div class="mx-auto w-full max-w-full {{ $maxWidth }} min-w-0 px-0">
    <nav class="mb-5">
        <a
            href="{{ $backHref }}"
            class="inline-flex items-center gap-2 text-sm font-medium text-church-gold transition"
        >
            <i class="fa-solid fa-arrow-left text-xs" aria-hidden="true"></i>
            {{ $backLabel }}
        </a>
    </nav>

    <header class="mb-5 flex items-start gap-3 sm:mb-6 sm:gap-4">
        <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-church-gold/15 text-base text-church-gold ring-1 ring-church-gold/30 sm:size-12 sm:rounded-2xl sm:text-lg">
            <i class="{{ $icon }}" aria-hidden="true"></i>
        </span>
        <div class="min-w-0 flex-1">
            <h1 class="break-words font-serif text-lg font-bold leading-snug text-church-fg sm:text-2xl">{{ $title }}</h1>
            @if (isset($subtitle))
                <p class="mt-1 text-sm text-slate-400">{{ $subtitle }}</p>
            @endif
        </div>
    </header>

    @if (isset($before))
        {{ $before }}
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300" role="alert">
            <p class="font-medium text-red-200">Perubahan belum tersimpan. Periksa isian berikut:</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        id="{{ $formId }}"
        method="post"
        action="{{ $action }}"
        data-admin-main-form
        @if ($enctype) enctype="{{ $enctype }}" @endif
        {{ $attributes->merge(['class' => 'space-y-5']) }}
    >
        @csrf
        @method($method)

        @if ($cardWrapped)
            <div class="public-card-hover space-y-4 rounded-2xl border border-white/10 bg-church-card/80 p-3 sm:p-6">
                {{ $slot }}
            </div>
        @else
            {{ $slot }}
        @endif

        @unless (isset($actions))
            <div class="admin-page-actions border-t border-white/10 pt-4 sm:border-0 sm:pt-0">
                @include('admin.partials.btn', [
                    'href' => $cancelHref,
                    'variant' => 'secondary',
                    'icon' => 'fa-solid fa-xmark',
                    'label' => $cancelLabel,
                    'extraClass' => 'shrink-0 whitespace-nowrap',
                ])
                @include('admin.partials.btn', [
                    'type' => 'submit',
                    'variant' => 'primary',
                    'icon' => 'fa-solid fa-check',
                    'label' => $submitLabel,
                    'extraClass' => 'shrink-0 whitespace-nowrap',
                ])
                @if ($deleteUrl)
                    @include('admin.partials.btn', [
                        'formAction' => $deleteUrl,
                        'method' => 'DELETE',
                        'variant' => 'danger-solid',
                        'icon' => 'fa-solid fa-trash',
                        'label' => 'Hapus',
                        'extraClass' => 'shrink-0 whitespace-nowrap',
                        'confirmSubmit' => true,
                        'confirmVariant' => 'delete',
                        'confirmTitle' => $deleteTitle,
                        'confirmMessage' => $deleteMessage,
                        'confirmLabel' => 'Ya, hapus',
                    ])
                @endif
            </div>
        @else
            {{ $actions }}
        @endunless
    </form>

    @if (isset($after))
        {{ $after }}
    @endif
</div>
