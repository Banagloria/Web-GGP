@props(['message' => null])

@php
    $text = $message ?? session('status');
@endphp

@if ($text || ! $slot->isEmpty())
    <div
        data-flash-success
        {{ $attributes->class(['alert-status-gold mb-6 px-4 py-3 text-sm sm:mb-8 sm:px-5 sm:py-4']) }}
        role="status"
        aria-live="polite"
    >
        @if ($slot->isEmpty())
            {{ $text }}
        @else
            {{ $slot }}
        @endif
    </div>
@endif
