@php
    $iconClass = $iconClass ?? 'text-church-gold/80';
    $labelClass = $labelClass ?? 'mb-1.5 flex items-center gap-2 text-xs font-medium uppercase tracking-wide text-slate-400';
    $resolvedIcon = \App\Support\AdminFormLabelIcon::for($text, $icon ?? null);
@endphp

<span class="{{ $labelClass }}">
    <i class="{{ $resolvedIcon }} {{ $iconClass }}" aria-hidden="true"></i>
    {{ $text }}
</span>
