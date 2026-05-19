@props(['message' => null, 'variant' => null])

@php
    $text = $message ?? session('status');
    $variant = $variant ?? session('status_variant', 'success');
    $isError = $variant === 'error';
@endphp

@if ($text || ! $slot->isEmpty())
    <div
        data-flash-success
        {{ $attributes->class([
            $isError ? 'alert-status-red' : 'alert-status-gold',
            'mb-6 px-4 py-3 text-sm sm:mb-8 sm:px-5 sm:py-4',
        ]) }}
        role="{{ $isError ? 'alert' : 'status' }}"
        aria-live="polite"
    >
        @if ($slot->isEmpty())
            {{ $text }}
        @else
            {{ $slot }}
        @endif
    </div>
@endif
