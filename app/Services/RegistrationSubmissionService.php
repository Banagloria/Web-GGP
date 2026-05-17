<?php

namespace App\Services;

use App\Models\RegistrationSubmission;
use App\Support\PendaftaranCardCms;
use App\Support\RegistrationSubmissionSupport;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RegistrationSubmissionService
{
    /**
     * @return array<string, mixed>
     */
    public static function validateAndStore(Request $request, string $slug, array $cms): RegistrationSubmission
    {
        abort_unless(
            RegistrationSubmissionSupport::isReady(),
            503,
            'Penyimpanan pendaftaran belum siap. Administrator perlu menjalankan migrasi database.'
        );

        $resolved = PendaftaranCardCms::resolveBySlug($cms, $slug);
        if ($resolved === null) {
            abort(404);
        }

        ['card' => $card, 'cardKey' => $cardKey, 'detail' => $detail] = $resolved;

        $rules = PendaftaranCardCms::validationRulesFromDetail($detail);
        $rules['data_consent'] = ['accepted'];

        $messages = [
            'data_consent.accepted' => 'Anda harus menyetujui pemrosesan data sebelum mengirim formulir.',
        ];

        $validated = $request->validate($rules, $messages);

        unset($validated['data_consent']);

        $files = [];
        foreach (PendaftaranCardCms::fieldsFromDetail($detail) as $field) {
            $name = $field['name'] ?? '';
            if ($name === '' || ($field['type'] ?? '') !== 'file') {
                continue;
            }
            if (! $request->hasFile($name)) {
                continue;
            }
            $uploaded = $request->file($name);
            if (! $uploaded instanceof UploadedFile || ! $uploaded->isValid()) {
                continue;
            }
            $path = $uploaded->store('registrations/'.$slug, 'public');
            $files[$name] = Storage::url($path);
            unset($validated[$name]);
        }

        return RegistrationSubmission::query()->create([
            'type_slug' => $slug,
            'card_key' => $cardKey,
            'status' => 'submitted',
            'payload' => $validated,
            'files' => $files !== [] ? $files : null,
        ]);
    }

    /**
     * @return list<array{name: string, label: string}>
     */
    public static function listColumnsForSlug(string $slug, array $cms): array
    {
        $resolved = PendaftaranCardCms::resolveBySlug($cms, $slug);
        if ($resolved === null) {
            return [];
        }

        $columns = [];
        foreach (PendaftaranCardCms::fieldsFromDetail($resolved['detail']) as $field) {
            $name = (string) ($field['name'] ?? '');
            if ($name === '') {
                continue;
            }
            $columns[] = [
                'name' => $name,
                'label' => (string) ($field['label'] ?? $name),
            ];
        }

        return $columns;
    }

    public static function searchColumnForSlug(string $slug, array $cms): string
    {
        $columns = self::listColumnsForSlug($slug, $cms);

        return $columns[0]['name'] ?? 'id';
    }
}
