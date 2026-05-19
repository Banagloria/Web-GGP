@php
    $logoRaw = trim((string) ($siteLogoUrl ?? ''));
    $logoSrc = $logoRaw !== '' ? \App\Support\PublicCmsUrl::imagePreviewSrc($logoRaw) : null;
    $imgClass = $imgClass ?? 'size-full object-contain';
@endphp
@if ($logoSrc)
    <img src="{{ $logoSrc }}" alt="" class="{{ $imgClass }} drop-shadow-md" width="64" height="64" loading="lazy" decoding="async" />
@else
    <span class="flex size-full items-center justify-center drop-shadow-md [&_svg]:size-[85%]">
        @include('partials.logo-mark')
    </span>
@endif
