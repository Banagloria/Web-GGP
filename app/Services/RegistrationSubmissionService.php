<?php

namespace App\Services;

use App\Models\RegistrationSubmission;
use App\Services\WhatsAppNotificationDispatcher;
use App\Support\PendaftaranCardCms;
use App\Support\RegistrationSubmissionSupport;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RegistrationSubmissionService
{
    /** Pemisah kolom untuk Excel (locale Indonesia/Eropa). */
    public const EXCEL_CSV_DELIMITER = ';';

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

        $submission = RegistrationSubmission::query()->create([
            'type_slug' => $slug,
            'card_key' => $cardKey,
            'status' => 'submitted',
            'payload' => $validated,
            'files' => $files !== [] ? $files : null,
        ]);

        $title = trim((string) ($detail['title'] ?? ($card['title'] ?? 'Pendaftaran')));
        $replacements = WhatsAppNotificationDispatcher::replacementsFromFormData($validated, [
            'judul' => $title,
            'slug' => $slug,
            'nama' => self::firstPayloadName($validated),
        ]);
        foreach ($files as $fieldName => $url) {
            $replacements[$fieldName] = $url;
        }

        WhatsAppNotificationDispatcher::dispatch('pendaftaran.'.$slug.'.submit', $replacements);

        return $submission;
    }

    /**
     * @return array<string, mixed>
     */
    public static function validateAndUpdate(
        Request $request,
        string $slug,
        array $cms,
        RegistrationSubmission $submission,
    ): RegistrationSubmission {
        abort_unless(
            RegistrationSubmissionSupport::isReady(),
            503,
            'Penyimpanan pendaftaran belum siap. Administrator perlu menjalankan migrasi database.'
        );

        $resolved = PendaftaranCardCms::resolveBySlug($cms, $slug);
        if ($resolved === null) {
            abort(404);
        }

        ['detail' => $detail] = $resolved;

        $rules = PendaftaranCardCms::validationRulesFromDetailForAdminUpdate($detail);
        $rules['notes'] = ['nullable', 'string', 'max:5000'];

        $validated = $request->validate($rules);
        $notes = $validated['notes'] ?? null;
        unset($validated['notes']);

        $existingFiles = is_array($submission->files) ? $submission->files : [];
        $files = $existingFiles;

        foreach (PendaftaranCardCms::fieldsFromDetail($detail) as $field) {
            $name = $field['name'] ?? '';
            if ($name === '' || ($field['type'] ?? '') !== 'file') {
                continue;
            }
            unset($validated[$name]);
            if (! $request->hasFile($name)) {
                continue;
            }
            $uploaded = $request->file($name);
            if (! $uploaded instanceof UploadedFile || ! $uploaded->isValid()) {
                continue;
            }
            $path = $uploaded->store('registrations/'.$slug, 'public');
            $files[$name] = Storage::url($path);
        }

        $submission->update([
            'payload' => $validated,
            'files' => $files !== [] ? $files : null,
            'notes' => $notes,
        ]);

        return $submission->fresh() ?? $submission;
    }

    /**
     * Nilai field untuk form edit admin.
     *
     * @param  array<string, mixed>  $field
     */
    public static function editFieldValue(RegistrationSubmission $submission, array $field): string
    {
        $name = (string) ($field['name'] ?? '');
        if ($name === '') {
            return '';
        }

        $value = $submission->payloadValue($name);
        if ($value === null || $value === '') {
            return '';
        }

        if (($field['type'] ?? '') === 'date') {
            try {
                return Carbon::parse((string) $value)->format('Y-m-d');
            } catch (\Throwable) {
                return (string) $value;
            }
        }

        if (is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE) ?: '';
        }

        return (string) $value;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private static function firstPayloadName(array $payload): string
    {
        foreach (['nama_lengkap', 'nama', 'name', 'full_name'] as $key) {
            $value = $payload[$key] ?? null;
            if (is_string($value) && trim($value) !== '') {
                return trim($value);
            }
        }

        foreach ($payload as $value) {
            if (is_string($value) && trim($value) !== '') {
                return trim($value);
            }
        }

        return '—';
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
                'type' => (string) ($field['type'] ?? 'text'),
            ];
        }

        return $columns;
    }

    public static function searchColumnForSlug(string $slug, array $cms): string
    {
        $columns = self::listColumnsForSlug($slug, $cms);

        return $columns[0]['name'] ?? 'id';
    }

    public static function statusLabel(string $status): string
    {
        return match ($status) {
            'submitted' => 'Diajukan',
            'active' => 'Diterima',
            'rejected' => 'Ditolak',
            'archived' => 'Arsip',
            default => $status,
        };
    }

    /**
     * @param  list<array{name: string, label: string, type?: string}>  $columns
     */
    public static function applySearchFilter(Builder $query, string $search, array $columns): void
    {
        $search = trim($search);
        if ($search === '') {
            return;
        }

        $like = '%'.addcslashes($search, '%_\\').'%';

        $query->where(function (Builder $q) use ($like, $search, $columns): void {
            $q->where('id', 'like', $like)
                ->orWhere('notes', 'like', $like)
                ->orWhere('status', 'like', $like)
                ->orWhere('files', 'like', $like);

            foreach (self::statusLabels() as $code => $label) {
                if (stripos($label, $search) !== false) {
                    $q->orWhere('status', $code);
                }
            }

            foreach ($columns as $col) {
                $name = $col['name'] ?? '';
                if ($name === '') {
                    continue;
                }
                $q->orWhere("payload->{$name}", 'like', $like);
            }
        });
    }

    /**
     * @return array<string, string>
     */
    public static function statusLabels(): array
    {
        return [
            'submitted' => 'Diajukan',
            'active' => 'Diterima',
            'rejected' => 'Ditolak',
            'archived' => 'Arsip',
        ];
    }

    /**
     * Nilai sel sama dengan yang ditampilkan di tabel admin (bukan kolom aksi).
     *
     * @param  array{name: string, label: string, type?: string}  $column
     */
    public static function displayCellValue(RegistrationSubmission $row, array $column): string
    {
        $name = $column['name'];
        $files = is_array($row->files) ? $row->files : [];

        if (isset($files[$name])) {
            return (string) $files[$name];
        }

        $value = $row->payloadValue($name);

        if (is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE) ?: '';
        }

        if ($value === null || $value === '') {
            return '';
        }

        if (($column['type'] ?? '') === 'date') {
            try {
                return Carbon::parse((string) $value)
                    ->timezone(config('app.timezone'))
                    ->format('d/m/Y');
            } catch (\Throwable) {
                return (string) $value;
            }
        }

        return (string) $value;
    }

    /**
     * Nilai untuk ekspor Excel — telepon sebagai teks format 628xxx.
     *
     * @param  array{name: string, label: string, type?: string}  $column
     */
    public static function exportCellValue(RegistrationSubmission $row, array $column): string
    {
        $value = self::displayCellValue($row, $column);

        if ($value === '' || ! self::isTextExportColumn($column)) {
            return $value;
        }

        return self::normalizePhoneText($value);
    }

    /**
     * @param  array{name: string, label: string, type?: string}  $column
     */
    public static function isTextExportColumn(array $column): bool
    {
        $type = strtolower((string) ($column['type'] ?? ''));
        if (in_array($type, ['tel', 'phone', 'telepon'], true)) {
            return true;
        }

        $name = strtolower((string) ($column['name'] ?? ''));
        $label = strtolower((string) ($column['label'] ?? ''));

        foreach (['phone', 'telepon', 'telpon', 'whatsapp', 'wa', 'hp', 'nomor', 'no_telp', 'no_hp'] as $needle) {
            if (str_contains($name, $needle) || str_contains($label, $needle)) {
                return true;
            }
        }

        return false;
    }

    public static function normalizePhoneText(string $value): string
    {
        $digits = preg_replace('/\D+/', '', $value) ?? '';
        if ($digits === '') {
            return $value;
        }

        if (str_starts_with($digits, '62')) {
            return $digits;
        }

        if (str_starts_with($digits, '0')) {
            return '62'.substr($digits, 1);
        }

        if (str_starts_with($digits, '8')) {
            return '62'.$digits;
        }

        return $digits;
    }

    /**
     * Field telepon dari pengaturan form (tipe tel/phone — tidak bergantung label/name).
     *
     * @param  array{name: string, label: string, type?: string}  $column
     */
    public static function isPhoneFieldType(array $column): bool
    {
        $type = strtolower((string) ($column['type'] ?? ''));

        return in_array($type, ['tel', 'phone', 'telepon'], true);
    }

    /**
     * Deteksi nomor HP Indonesia dari isi input (08…, 628…, +62…) — untuk form dinamis.
     */
    public static function valueLooksLikePhone(string $value): bool
    {
        $trimmed = trim($value);
        if ($trimmed === '' || str_contains($trimmed, '@')) {
            return false;
        }

        if (preg_match('/[a-zA-Z]/', $trimmed) === 1 && ! preg_match('/^\+?[\d\s().\-]+$/', $trimmed)) {
            return false;
        }

        $normalized = self::normalizePhoneText($trimmed);
        $digits = preg_replace('/\D+/', '', $normalized) ?? '';

        return preg_match('/^628\d{8,11}$/', $digits) === 1;
    }

    /**
     * @param  list<array{name: string, label: string, type?: string}>  $columns
     */
    public static function phoneFromSubmission(RegistrationSubmission $submission, array $columns = []): ?string
    {
        foreach ($columns as $column) {
            if (! self::isPhoneFieldType($column)) {
                continue;
            }
            $phone = self::normalizedPhoneFromValue(self::displayCellValue($submission, $column));
            if ($phone !== null) {
                return $phone;
            }
        }

        foreach ($columns as $column) {
            $phone = self::normalizedPhoneFromValue(self::displayCellValue($submission, $column));
            if ($phone !== null) {
                return $phone;
            }
        }

        $payload = is_array($submission->payload) ? $submission->payload : [];

        return self::phoneFromPayload($payload) ?? self::phoneFromPayloadByValue($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function phoneFromPayload(array $payload): ?string
    {
        foreach ($payload as $key => $value) {
            $value = self::scalarPayloadValue($value);
            if ($value === null) {
                continue;
            }
            $keyLower = strtolower((string) $key);
            foreach (['phone', 'telepon', 'telpon', 'whatsapp', 'wa', 'hp', 'nomor', 'no_telp', 'no_hp'] as $needle) {
                if (! str_contains($keyLower, $needle)) {
                    continue;
                }
                $phone = self::normalizedPhoneFromValue(trim($value));
                if ($phone !== null) {
                    return $phone;
                }
            }
        }

        return null;
    }

    /**
     * Scan semua nilai payload — cocok untuk field dinamis tanpa nama/label khusus.
     *
     * @param  array<string, mixed>  $payload
     */
    public static function phoneFromPayloadByValue(array $payload): ?string
    {
        foreach ($payload as $value) {
            $value = self::scalarPayloadValue($value);
            if ($value === null) {
                continue;
            }
            $phone = self::normalizedPhoneFromValue($value);
            if ($phone !== null) {
                return $phone;
            }
        }

        return null;
    }

    private static function normalizedPhoneFromValue(string $value): ?string
    {
        if ($value === '' || ! self::valueLooksLikePhone($value)) {
            return null;
        }

        $normalized = self::normalizePhoneText($value);
        $digits = preg_replace('/\D+/', '', $normalized) ?? '';

        return preg_match('/^628\d{8,11}$/', $digits) === 1 ? $normalized : null;
    }

    private static function scalarPayloadValue(mixed $value): ?string
    {
        if (is_int($value) || is_float($value)) {
            $value = (string) (int) $value;
        }
        if (! is_string($value)) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
    }

    public static function displayPhone(?string $phone): string
    {
        if ($phone === null || $phone === '') {
            return '';
        }

        if (str_starts_with($phone, '62') && strlen($phone) > 2) {
            return '0'.substr($phone, 2);
        }

        return $phone;
    }

    /**
     * @param  list<array{name: string, label: string, type?: string}>  $columns
     * @return list<string>
     */
    public static function exportHeaders(array $columns): array
    {
        return array_merge(
            ['No'],
            array_column($columns, 'label'),
        );
    }

    /**
     * @param  list<array{name: string, label: string, type?: string}>  $columns
     * @return list<string|int>
     */
    public static function exportRowValues(RegistrationSubmission $row, array $columns, int $rowNumber): array
    {
        $line = [$rowNumber];

        foreach ($columns as $column) {
            $line[] = self::exportCellValue($row, $column);
        }

        return $line;
    }

    /**
     * @param  resource  $handle
     * @param  list<string|int|float|null>  $fields
     */
    public static function writeExcelCsvRow($handle, array $fields): void
    {
        $normalized = array_map(static function ($value): string {
            if ($value === null) {
                return '';
            }

            return (string) $value;
        }, $fields);

        fputcsv($handle, $normalized, self::EXCEL_CSV_DELIMITER, '"', '\\');
    }

    /**
     * @param  resource  $handle
     */
    public static function writeExcelCsvPreamble($handle): void
    {
        fwrite($handle, "\xEF\xBB\xBF");
        fwrite($handle, 'sep='.self::EXCEL_CSV_DELIMITER."\r\n");
    }
}
