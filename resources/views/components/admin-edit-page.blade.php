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

    <form
        id="{{ $formId }}"
        method="post"
        action="{{ $action }}"
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
            <div class="mt-5 flex w-full min-w-0 flex-col-reverse gap-2 border-t border-white/10 pt-4 sm:flex-row sm:flex-wrap sm:items-center sm:justify-end sm:border-0 sm:pt-0">
            @include('admin.partials.btn', [
                'href' => $cancelHref,
                'variant' => 'secondary',
                'icon' => 'fa-solid fa-xmark',
                'label' => $cancelLabel,
                'extraClass' => 'w-full justify-center sm:w-auto',
            ])
            @if ($deleteUrl)
                @include('admin.partials.table-actions', [
                    'deleteUrl' => $deleteUrl,
                    'deleteTitle' => $deleteTitle,
                    'deleteMessage' => $deleteMessage,
                    'extraClass' => 'w-full justify-center sm:w-auto',
                ])
            @endif
            @include('admin.partials.btn', [
                'type' => 'submit',
                'variant' => 'primary',
                'icon' => 'fa-solid fa-check',
                'label' => $submitLabel,
                'extraClass' => 'w-full justify-center sm:w-auto',
            ])
            </div>
        @else
            {{ $actions }}
        @endunless
    </form>

    @if (isset($after))
        {{ $after }}
    @endif
</div>
