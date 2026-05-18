@php
    $user = $user ?? auth()->user();
    $sizeClass = $sizeClass ?? 'size-11';
    $roundedClass = $roundedClass ?? 'rounded-lg';
    $photoSrc = $user?->profilePhotoSrc();
@endphp
<div
    class="{{ $sizeClass }} {{ $roundedClass }} relative shrink-0 overflow-hidden border border-church-gold/25 bg-church-gold/10 ring-1 ring-church-gold/25"
    aria-hidden="true"
>
    @if ($photoSrc)
        <img
            src="{{ $photoSrc }}"
            alt=""
            class="absolute inset-0 size-full object-cover"
            loading="lazy"
            decoding="async"
        >
    @else
        <span class="flex size-full items-center justify-center text-church-gold/75">
            <i class="fa-solid fa-user text-sm sm:text-base" aria-hidden="true"></i>
        </span>
    @endif
</div>
