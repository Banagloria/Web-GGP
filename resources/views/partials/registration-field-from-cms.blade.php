@php
    $selectOptions = [];
    foreach ($field['select_options'] ?? [] as $opt) {
        if (! is_array($opt)) {
            continue;
        }
        $selectOptions[(string) ($opt['value'] ?? '')] = (string) ($opt['label'] ?? '');
    }
@endphp

@include('partials.registration-field', [
    'name' => $field['name'],
    'label' => $field['label'],
    'icon' => $field['icon'] ?? 'fa-solid fa-pen',
    'type' => $field['type'] ?? 'text',
    'required' => ! empty($field['required']),
    'placeholder' => $field['placeholder'] ?? '',
    'rows' => (int) ($field['rows'] ?? 3),
    'selectOptions' => ($field['type'] ?? '') === 'select' ? $selectOptions : null,
])
