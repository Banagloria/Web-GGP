@props([
    'for' => null,
    'icon' => null,
    'text' => null,
    'as' => 'label',
])

@php
    $content = $text ?? trim($slot);
    $iconClass = \App\Support\AdminFormLabelIcon::for($content, $icon);
    $defaultClass = match ($as) {
        'legend' => 'flex items-center gap-2 font-semibold text-church-fg',
        'span' => 'flex items-center gap-2 text-sm font-medium text-slate-300',
        default => 'mb-1.5 flex items-center gap-2 text-sm font-medium text-slate-300',
    };
@endphp

@if ($as === 'legend')
    <legend {{ $attributes->class($defaultClass) }}>
        <i class="{{ $iconClass }} shrink-0 text-church-gold/80" aria-hidden="true"></i>
        <span>{{ $content }}</span>
    </legend>
@elseif ($as === 'span')
    <span {{ $attributes->class($defaultClass) }}>
        <i class="{{ $iconClass }} shrink-0 text-church-gold/80" aria-hidden="true"></i>
        <span>{{ $content }}</span>
    </span>
@else
    <label @if ($for) for="{{ $for }}" @endif class="block">
        <span {{ $attributes->class($defaultClass) }}>
            <i class="{{ $iconClass }} shrink-0 text-church-gold/80" aria-hidden="true"></i>
            <span>{{ $content }}</span>
        </span>
    </label>
@endif
