<?php

namespace App\Services;

use App\Models\CongregationRegistration;
use App\Models\RegistrationSubmission;
use App\Models\User;
final class WhatsAppBroadcastRecipientOptions
{
    /**
     * @return list<array{key: string, label: string, group: string}>
     */
    public static function options(): array
    {
        $seenPhones = [];
        $options = [];

        if (User::phoneColumnReady()) {
            $users = User::query()
                ->whereNotNull('phone')
                ->where('phone', '!=', '')
                ->orderBy('name')
                ->get(['id', 'name', 'phone', 'role']);

            foreach ($users as $user) {
                $phone = RegistrationSubmissionService::normalizePhoneText((string) $user->phone);
                if ($phone === '' || isset($seenPhones[$phone])) {
                    continue;
                }
                $seenPhones[$phone] = true;
                $options[] = [
                    'key' => self::userKey($user->id),
                    'label' => $user->name.' - '.$user->phone.' ('.$user->roleLabel().')',
                    'group' => 'Akun',
                ];
            }
        }

        if (class_exists(CongregationRegistration::class) && \Illuminate\Support\Facades\Schema::hasTable('congregation_registrations')) {
            $congregations = CongregationRegistration::query()
                ->whereNotNull('phone')
                ->where('phone', '!=', '')
                ->orderBy('full_name')
                ->get(['id', 'full_name', 'phone']);

            foreach ($congregations as $row) {
                $phone = RegistrationSubmissionService::normalizePhoneText((string) $row->phone);
                if ($phone === '' || isset($seenPhones[$phone])) {
                    continue;
                }
                $seenPhones[$phone] = true;
                $options[] = [
                    'key' => self::congregationKey($row->id),
                    'label' => $row->full_name.' - '.$row->phone.' (Jemaat)',
                    'group' => 'Data jemaat',
                ];
            }
        }

        if (class_exists(RegistrationSubmission::class) && \Illuminate\Support\Facades\Schema::hasTable('registration_submissions')) {
            $submissions = RegistrationSubmission::query()
                ->whereIn('status', ['active', 'submitted'])
                ->orderByDesc('id')
                ->get(['id', 'type_slug', 'payload']);

            foreach ($submissions as $submission) {
                $phone = self::phoneFromPayload($submission->payload ?? []);
                if ($phone === null || $phone === '' || isset($seenPhones[$phone])) {
                    continue;
                }
                $name = self::nameFromPayload($submission->payload ?? []);
                if ($name === '') {
                    $name = 'Pendaftaran #'.$submission->id;
                }
                $seenPhones[$phone] = true;
                $slugLabel = str_replace(['-', '_'], ' ', (string) $submission->type_slug);
                $options[] = [
                    'key' => self::submissionKey($submission->id),
                    'label' => $name.' - '.$phone.' ('.ucfirst($slugLabel).')',
                    'group' => 'Pendaftaran form',
                ];
            }
        }

        usort($options, fn (array $a, array $b): int => strcasecmp($a['label'], $b['label']));

        return $options;
    }

    /**
     * @return array{user_id: ?int, recipient_name: string, recipient_phone: string}|null
     */
    public static function resolve(string $key): ?array
    {
        if (preg_match('/^user:(\d+)$/', $key, $matches) === 1) {
            $user = User::query()->find((int) $matches[1]);
            if ($user === null || ! User::phoneColumnReady() || trim((string) $user->phone) === '') {
                return null;
            }

            return [
                'user_id' => $user->id,
                'recipient_name' => $user->name,
                'recipient_phone' => RegistrationSubmissionService::normalizePhoneText((string) $user->phone),
            ];
        }

        if (preg_match('/^congregation:(\d+)$/', $key, $matches) === 1) {
            $row = CongregationRegistration::query()->find((int) $matches[1]);
            if ($row === null || trim((string) $row->phone) === '') {
                return null;
            }

            return [
                'user_id' => null,
                'recipient_name' => $row->full_name,
                'recipient_phone' => RegistrationSubmissionService::normalizePhoneText((string) $row->phone),
            ];
        }

        if (preg_match('/^submission:(\d+)$/', $key, $matches) === 1) {
            $submission = RegistrationSubmission::query()->find((int) $matches[1]);
            if ($submission === null) {
                return null;
            }
            $phone = self::phoneFromPayload($submission->payload ?? []);
            if ($phone === null || $phone === '') {
                return null;
            }
            $name = self::nameFromPayload($submission->payload ?? []);
            if ($name === '') {
                $name = 'Pendaftaran #'.$submission->id;
            }

            return [
                'user_id' => null,
                'recipient_name' => $name,
                'recipient_phone' => $phone,
            ];
        }

        return null;
    }

    public static function keyFromTemplateUser(\App\Models\WhatsappBroadcastTemplateUser $row): ?string
    {
        if ($row->user_id !== null) {
            return self::userKey((int) $row->user_id);
        }

        $phone = $row->recipient_phone !== null && $row->recipient_phone !== ''
            ? RegistrationSubmissionService::normalizePhoneText((string) $row->recipient_phone)
            : '';

        if ($phone === '') {
            return null;
        }

        foreach (self::options() as $option) {
            $resolved = self::resolve($option['key']);
            if ($resolved !== null && $resolved['recipient_phone'] === $phone) {
                return $option['key'];
            }
        }

        return null;
    }

    public static function displayLabel(\App\Models\WhatsappBroadcastTemplateUser $row): string
    {
        if ($row->user !== null) {
            return $row->user->name.' - '.$row->user->phone;
        }

        $phone = $row->recipient_phone ?? '';
        $name = $row->recipient_name ?? 'Penerima';

        return $name.' - '.$phone;
    }

    public static function userKey(int $id): string
    {
        return 'user:'.$id;
    }

    public static function congregationKey(int $id): string
    {
        return 'congregation:'.$id;
    }

    public static function submissionKey(int $id): string
    {
        return 'submission:'.$id;
    }

    /**
     * @return list<string>
     */
    public static function validKeys(): array
    {
        return array_column(self::options(), 'key');
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private static function phoneFromPayload(array $payload): ?string
    {
        foreach ($payload as $key => $value) {
            if (! is_string($value) || trim($value) === '') {
                continue;
            }
            $keyLower = strtolower((string) $key);
            foreach (['phone', 'telepon', 'whatsapp', 'hp', 'nomor', 'no_telp', 'no_hp'] as $needle) {
                if (str_contains($keyLower, $needle)) {
                    $normalized = RegistrationSubmissionService::normalizePhoneText(trim($value));

                    return $normalized !== '' ? $normalized : null;
                }
            }
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private static function nameFromPayload(array $payload): string
    {
        foreach (['nama_lengkap', 'nama', 'name', 'full_name'] as $key) {
            $value = $payload[$key] ?? null;
            if (is_string($value) && trim($value) !== '') {
                return trim($value);
            }
        }

        foreach ($payload as $value) {
            if (is_string($value) && trim($value) !== '' && ! is_numeric(trim($value))) {
                return trim($value);
            }
        }

        return '';
    }
}
