@php
    $files = is_array($submission->files) ? $submission->files : [];
@endphp

<div class="space-y-8">
    @foreach ($detail['sections'] ?? [] as $section)
        <fieldset class="space-y-4">
            <x-admin-field-label as="legend">{{ $section['title'] ?? 'Data' }}</x-admin-field-label>
            @if (! empty($section['subtitle']))
                <p class="text-sm text-slate-400">{{ $section['subtitle'] }}</p>
            @endif

            @foreach ($section['groups'] ?? [] as $group)
                @if (($group['layout'] ?? 'stack') === 'grid')
                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach ($group['fields'] ?? [] as $field)
                            @include('admin.partials.registration-submission-field', [
                                'field' => $field,
                                'value' => \App\Services\RegistrationSubmissionService::editFieldValue($submission, $field),
                                'currentFileUrl' => isset($files[$field['name'] ?? '']) ? $files[$field['name']] : null,
                            ])
                        @endforeach
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($group['fields'] ?? [] as $field)
                            @include('admin.partials.registration-submission-field', [
                                'field' => $field,
                                'value' => \App\Services\RegistrationSubmissionService::editFieldValue($submission, $field),
                                'currentFileUrl' => isset($files[$field['name'] ?? '']) ? $files[$field['name']] : null,
                            ])
                        @endforeach
                    </div>
                @endif
            @endforeach
        </fieldset>
    @endforeach

    <fieldset class="space-y-4">
        <x-admin-field-label as="legend">Catatan internal</x-admin-field-label>
        <div>
            <x-admin-field-label>Catatan pengurus</x-admin-field-label>
            <textarea
                name="notes"
                rows="4"
                class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg shadow-sm min-h-[5.5rem] resize-y"
            >{{ old('notes', $submission->notes) }}</textarea>
            @error('notes')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
        </div>
    </fieldset>
</div>
