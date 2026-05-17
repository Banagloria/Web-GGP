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

<div class="mx-auto w-full {{ $maxWidth }} min-w-0 px-0">
    <nav class="mb-5">
        <a
            href="{{ $backHref }}"
            class="inline-flex items-center gap-2 text-sm font-medium text-church-gold transition"
        >
            <i class="fa-solid fa-arrow-left text-xs" aria-hidden="true"></i>
            {{ $backLabel }}
        </a>
    </nav>

    <header class="mb-6 flex items-start gap-4">
        <span class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-church-gold/15 text-lg text-church-gold ring-1 ring-church-gold/30">
            <i class="{{ $icon }}" aria-hidden="true"></i>
        </span>
        <div class="min-w-0 flex-1">
            <h1 class="font-serif text-xl font-bold text-church-fg sm:text-2xl">{{ $title }}</h1>
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
            <div class="public-card-hover space-y-4 rounded-2xl border border-white/10 bg-church-card/80 p-4 sm:p-6">
                {{ $slot }}
            </div>
        @else
            {{ $slot }}
        @endif
    </form>

    @unless (isset($actions))
        <div class="mt-5 flex flex-nowrap items-center justify-end gap-2 overflow-x-auto pb-1">
            @include('admin.partials.btn', [
                'href' => $cancelHref,
                'variant' => 'secondary',
                'icon' => 'fa-solid fa-xmark',
                'label' => $cancelLabel,
                'extraClass' => 'shrink-0',
            ])
            @if ($deleteUrl)
                @include('admin.partials.table-actions', [
                    'deleteUrl' => $deleteUrl,
                    'deleteTitle' => $deleteTitle,
                    'deleteMessage' => $deleteMessage,
                    'extraClass' => 'shrink-0',
                ])
            @endif
            @include('admin.partials.btn', [
                'type' => 'submit',
                'variant' => 'primary',
                'icon' => 'fa-solid fa-check',
                'label' => $submitLabel,
                'extraClass' => 'shrink-0',
                'form' => $formId,
            ])
        </div>
    @else
        {{ $actions }}
    @endunless

    @if (isset($after))
        {{ $after }}
    @endif
</div>
