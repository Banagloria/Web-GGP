@php
    $defaultFa = $defaultFa ?? 'fa-solid fa-circle';
    $faClass = \App\Support\CmsIcon::toFontAwesome($value ?? '', $defaultFa);
@endphp
<i class="{{ $faClass }}" aria-hidden="true"></i>
