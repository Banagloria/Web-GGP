@props([
    'label',
    'icon' => null,
    'valueClass' => 'text-sm text-church-fg',
])

@php
    $iconClass = \App\Support\AdminFormLabelIcon::for($label, $icon);
@endphp

<div {{ $attributes->merge(['class' => 'grid gap-1 py-3 sm:grid-cols-[minmax(9rem,12rem)_minmax(0,1fr)] sm:items-start sm:gap-4']) }}>
    <dt class="min-w-0">
        <span class="flex items-center gap-2 text-xs font-medium uppercase tracking-wide text-slate-400">
            <i class="{{ $iconClass }} shrink-0 text-church-gold/80" aria-hidden="true"></i>
            {{ $label }}
        </span>
    </dt>
    <dd class="{{ $valueClass }} min-w-0 break-words font-medium">
        {{ $slot }}
    </dd>
</div>
