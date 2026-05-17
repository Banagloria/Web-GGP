@php
    /** @var array<string, mixed> $cms */
    /** @var string $pageKey */
    /** @var string $iconKey */
    $schema = \App\Support\CmsPageIconDefaults::schema($pageKey);
    $icons = $cms['page_icons'] ?? [];
    $default = $schema[$iconKey]['default'] ?? 'fa-solid fa-circle';
    $class = \App\Support\CmsIcon::toFontAwesome($icons[$iconKey] ?? '', $default);
    $extra = trim($extraClasses ?? '');
@endphp
<i class="{{ trim($class.' '.$extra) }}" aria-hidden="true"></i>
