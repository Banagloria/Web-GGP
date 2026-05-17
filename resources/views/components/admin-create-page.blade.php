@props([
    'backHref',
    'backLabel',
    'icon',
    'title',
    'action',
    'method' => 'post',
    'formId' => null,
    'enctype' => null,
    'cancelHref' => null,
    'cancelLabel' => 'Batal',
    'submitLabel' => 'Simpan',
])

@php
    $cancelHref = $cancelHref ?? $backHref;
    $httpMethod = strtoupper($method);
    $needsMethodOverride = ! in_array($httpMethod, ['GET', 'POST'], true);
@endphp

<div class="mx-auto w-full max-w-3xl min-w-0 px-0 sm:max-w-4xl">
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
        <h1 class="font-serif text-xl font-bold text-church-fg sm:text-2xl">{{ $title }}</h1>
    </header>

    <form
        @if ($formId) id="{{ $formId }}" @endif
        method="{{ $needsMethodOverride ? 'post' : strtolower($httpMethod) }}"
        action="{{ $action }}"
        @if ($enctype) enctype="{{ $enctype }}" @endif
        {{ $attributes->merge(['class' => 'space-y-5']) }}
    >
        @csrf
        @if ($needsMethodOverride)
            @method($method)
        @endif

        <div class="public-card-hover space-y-4 rounded-2xl border border-white/10 bg-church-card/80 p-4 sm:p-6">
            {{ $slot }}
        </div>

        @unless (isset($footer))
            <div class="flex flex-nowrap items-center justify-end gap-2 overflow-x-auto pb-1">
                @include('admin.partials.btn', [
                    'href' => $cancelHref,
                    'variant' => 'secondary',
                    'icon' => 'fa-solid fa-xmark',
                    'label' => $cancelLabel,
                    'extraClass' => 'shrink-0',
                ])
                @include('admin.partials.btn', [
                    'type' => 'submit',
                    'variant' => 'primary',
                    'icon' => 'fa-solid fa-check',
                    'label' => $submitLabel,
                    'extraClass' => 'shrink-0',
                ])
            </div>
        @else
            {{ $footer }}
        @endunless
    </form>
</div>
