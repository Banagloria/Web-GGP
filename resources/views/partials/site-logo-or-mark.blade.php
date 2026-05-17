@php
    $logoRaw = trim((string) ($siteLogoUrl ?? ''));
    $logoSrc = $logoRaw !== '' ? \App\Support\PublicCmsUrl::imagePreviewSrc($logoRaw) : null;
    $imgClass = $imgClass ?? 'max-h-9 max-w-[2.25rem] object-contain sm:max-h-10 sm:max-w-[2.5rem]';
@endphp
@if ($logoSrc)
    <img src="{{ $logoSrc }}" alt="" class="{{ $imgClass }} drop-shadow-md" width="40" height="40" loading="lazy" decoding="async" />
@else
    <span class="drop-shadow-md [&_svg]:h-9 [&_svg]:w-9 sm:[&_svg]:h-10 sm:[&_svg]:w-10">
        @include('partials.logo-mark')
    </span>
@endif
