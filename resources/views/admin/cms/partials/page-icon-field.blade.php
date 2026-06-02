@php
    /** @var string $pageKey */
    /** @var string $iconKey */
    /** @var array<string, mixed> $data */
    $schema = \App\Support\CmsPageIconDefaults::schema($pageKey);
    $meta = $schema[$iconKey] ?? null;
    if ($meta === null && $pageKey === 'pendaftaran') {
        $meta = \App\Support\CmsPageIconDefaults::pendaftaranDynamicIconMeta($iconKey);
    }
@endphp
@if ($meta !== null)
    @include('admin.partials.fa-icon-input', [
        'name' => 'page_icons['.$iconKey.']',
        'value' => old('page_icons.'.$iconKey, $data['page_icons'][$iconKey] ?? ''),
        'label' => $label ?? 'Ikon FA',
        'labelClass' => $labelClass ?? null,
        'previewDefault' => $meta['default'],
        'variant' => $variant ?? 'adjacent',
        'hint' => $hint ?? '',
        'placeholder' => $placeholder ?? null,
    ])
@endif
