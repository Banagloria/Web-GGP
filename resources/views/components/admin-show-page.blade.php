@props([
    'backHref',
    'backLabel',
    'icon' => 'fa-solid fa-eye',
    'title',
    'wide' => false,
    'editUrl' => null,
    'editLabel' => 'Edit',
    'deleteUrl' => null,
    'deleteTitle' => 'Hapus data?',
    'deleteMessage' => 'Tindakan ini tidak dapat dibatalkan.',
    'backButtonLabel' => 'Kembali',
])

@php
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

    <dl class="public-card-hover divide-y divide-white/5 rounded-2xl border border-white/10 bg-church-card/80 p-4 sm:p-6">
        {{ $slot }}
    </dl>

    @unless (isset($actions))
        <div class="admin-page-actions">
            @include('admin.partials.btn', [
                'href' => $backHref,
                'variant' => 'secondary',
                'icon' => 'fa-solid fa-xmark',
                'label' => $backButtonLabel,
                'extraClass' => 'shrink-0 whitespace-nowrap',
            ])
            @if ($editUrl)
                @include('admin.partials.btn', [
                    'href' => $editUrl,
                    'variant' => 'primary',
                    'icon' => 'fa-solid fa-pen',
                    'label' => $editLabel,
                    'extraClass' => 'shrink-0 whitespace-nowrap',
                ])
            @endif
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

    @if (isset($after))
        {{ $after }}
    @endif
</div>
