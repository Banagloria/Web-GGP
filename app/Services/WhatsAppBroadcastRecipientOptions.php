<?php

namespace App\Services;

use App\Models\RegistrationSubmission;
use App\Models\User;
use App\Support\PendaftaranCardCms;
use App\Support\PublicCmsUrl;
use App\Support\WhatsAppChatId;

final class WhatsAppBroadcastRecipientOptions
{
    /**
     * @return list<array{key: string, label: string, group: string}>
     */
    public static function options(): array
    {
        $seenAccountChatIds = [];
        $seenDataSubmissionIds = [];
        $options = [];

        if (User::phoneColumnReady()) {
            $users = User::query()
                ->whereNotNull('phone')
                ->where('phone', '!=', '')
                ->orderBy('name')
                ->get(['id', 'name', 'phone', 'role']);

            foreach ($users as $user) {
                $resolved = self::resolveUser($user);
                if ($resolved === null || isset($seenAccountChatIds[$resolved['chat_id']])) {
                    continue;
                }
                $seenAccountChatIds[$resolved['chat_id']] = true;
                $options[] = [
                    'key' => self::userKey($user->id),
                    'label' => $user->name.' - '.RegistrationSubmissionService::displayPhone($resolved['recipient_phone']),
                    'group' => 'Akun',
                ];
            }
        }

        foreach (self::acceptedDataEntries() as $entry) {
            if (isset($seenDataSubmissionIds[$entry['submission_id']])) {
                continue;
            }
            $seenDataSubmissionIds[$entry['submission_id']] = true;
            $options[] = [
                'key' => self::submissionKey($entry['submission_id']),
                'label' => $entry['recipient_name'].' - '.RegistrationSubmissionService::displayPhone($entry['recipient_phone']),
                'group' => $entry['group'],
            ];
        }

        return $options;
    }

    /**
     * @return list<string>
     */
    public static function allAcceptedDataChatIds(): array
    {
        $chatIds = [];
        foreach (self::acceptedDataEntries() as $entry) {
            $chatIds[$entry['chat_id']] = $entry['chat_id'];
        }

        return array_values($chatIds);
    }

    /**
     * Baris yang sama dengan tabel menu Data (per kartu CMS, status active).
     *
     * @return list<array{submission_id: int, type_slug: string, recipient_name: string, recipient_phone: string, chat_id: string, group: string}>
     */
    public static function acceptedDataEntries(): array
    {
        if (! class_exists(RegistrationSubmission::class) || ! \Illuminate\Support\Facades\Schema::hasTable('registration_submissions')) {
            return [];
        }

        try {
            $cms = CmsPageService::merged('pendaftaran');
        } catch (\Throwable) {
            $cms = ['cards' => []];
        }

        $entries = [];

        foreach (self::slugTitlesFromCms() as $slug => $title) {
            if (! self::slugHasPhoneField($slug, $cms)) {
                continue;
            }

            foreach (self::acceptedDataEntriesForSlug($slug, $cms, $title) as $entry) {
                $entries[] = $entry;
            }
        }

        return $entries;
    }

    /**
     * @return list<array{submission_id: int, type_slug: string, recipient_name: string, recipient_phone: string, chat_id: string, group: string}>
     */
    public static function acceptedDataEntriesForSlug(string $slug, ?array $cms = null, ?string $title = null): array
    {
        if (! class_exists(RegistrationSubmission::class) || ! \Illuminate\Support\Facades\Schema::hasTable('registration_submissions')) {
            return [];
        }

        if ($cms === null) {
            try {
                $cms = CmsPageService::merged('pendaftaran');
            } catch (\Throwable) {
                $cms = ['cards' => []];
            }
        }

        $title = $title ?? self::slugTitlesFromCms()[$slug] ?? $slug;
        $columns = RegistrationSubmissionService::listColumnsForSlug($slug, $cms);
        $entries = [];

        $submissions = RegistrationSubmission::query()
            ->where('type_slug', $slug)
            ->where('status', 'active')
            ->orderByDesc('id')
            ->get(['id', 'type_slug', 'payload']);

        foreach ($submissions as $submission) {
            $phone = RegistrationSubmissionService::phoneFromSubmission($submission, $columns);
            $chatId = WhatsAppChatId::fromPhone($phone);
            if ($chatId === null || $chatId === '') {
                continue;
            }

            $name = self::nameFromSubmission($submission, $columns);
            if ($name === '') {
                $name = 'Data #'.$submission->id;
            }

            $entries[] = [
                'submission_id' => $submission->id,
                'type_slug' => $slug,
                'recipient_name' => $name,
                'recipient_phone' => RegistrationSubmissionService::normalizePhoneText((string) $phone),
                'chat_id' => $chatId,
                'group' => $title,
            ];
        }

        return $entries;
    }

    /**
     * Akun pengurus (menu Manajemen akun) dengan nomor HP valid.
     *
     * @return list<array{user_id: int, recipient_name: string, recipient_phone: string, chat_id: string}>
     */
    public static function panelAccountEntries(): array
    {
        if (! User::phoneColumnReady()) {
            return [];
        }

        $entries = [];
        $seenChatIds = [];

        $users = User::query()
            ->panelUsers()
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->orderBy('name')
            ->get(['id', 'name', 'phone']);

        foreach ($users as $user) {
            $resolved = self::resolveUser($user);
            if ($resolved === null || isset($seenChatIds[$resolved['chat_id']])) {
                continue;
            }
            $seenChatIds[$resolved['chat_id']] = true;
            $entries[] = $resolved;
        }

        return $entries;
    }

    /**
     * @return list<string>
     */
    public static function chatIdsForPanelAccounts(): array
    {
        $chatIds = [];
        foreach (self::panelAccountEntries() as $entry) {
            $chatIds[$entry['chat_id']] = $entry['chat_id'];
        }

        return array_values($chatIds);
    }

    /**
     * @return list<string>
     */
    public static function chatIdsForSlug(string $slug): array
    {
        $chatIds = [];
        foreach (self::acceptedDataEntriesForSlug($slug) as $entry) {
            $chatIds[$entry['chat_id']] = $entry['chat_id'];
        }

        return array_values($chatIds);
    }

    /**
     * Form kartu punya field tipe telepon (tel) di Setting.
     */
    public static function slugHasPhoneField(string $slug, ?array $cms = null): bool
    {
        if ($cms === null) {
            try {
                $cms = CmsPageService::merged('pendaftaran');
            } catch (\Throwable) {
                return false;
            }
        }

        $resolved = PendaftaranCardCms::resolveBySlug($cms, $slug);
        if ($resolved === null) {
            return false;
        }

        foreach (PendaftaranCardCms::fieldsFromDetail($resolved['detail']) as $field) {
            $type = strtolower((string) ($field['type'] ?? ''));
            if (in_array($type, ['tel', 'phone', 'telepon'], true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Judul grup = judul kartu di Setting (sama menu Data sidebar).
     *
     * @return array<string, string> slug => judul kartu
     */
    public static function slugTitlesFromCms(): array
    {
        try {
            $cms = CmsPageService::merged('pendaftaran');
        } catch (\Throwable) {
            $cms = ['cards' => []];
        }

        $titles = [];
        foreach ($cms['cards'] ?? [] as $card) {
            if (! is_array($card)) {
                continue;
            }
            $slug = PublicCmsUrl::formatPendaftaranCardSlugForInput($card['url'] ?? '');
            if ($slug === '') {
                continue;
            }
            $titles[$slug] = (string) ($card['title'] ?? $slug);
        }

        return $titles;
    }

    /**
     * @deprecated Use slugTitlesFromCms() — hanya kartu CMS, tanpa slug yatim di database.
     *
     * @return array<string, string>
     */
    public static function dataSlugTitles(): array
    {
        return self::slugTitlesFromCms();
    }

    /**
     * @return array{user_id: ?int, recipient_name: string, recipient_phone: string, chat_id: string}|null
     */
    public static function resolve(string $key): ?array
    {
        if (preg_match('/^user:(\d+)$/', $key, $matches) === 1) {
            $user = User::query()->find((int) $matches[1]);

            return $user !== null ? self::resolveUser($user) : null;
        }

        if (preg_match('/^submission:(\d+)$/', $key, $matches) === 1) {
            return self::resolveSubmission((int) $matches[1]);
        }

        return null;
    }

    /**
     * @return array{user_id: null, recipient_name: string, recipient_phone: string, chat_id: string}|null
     */
    private static function resolveSubmission(int $submissionId): ?array
    {
        if (! class_exists(RegistrationSubmission::class) || ! \Illuminate\Support\Facades\Schema::hasTable('registration_submissions')) {
            return null;
        }

        $submission = RegistrationSubmission::query()
            ->where('status', 'active')
            ->find($submissionId);

        if ($submission === null) {
            return null;
        }

        $slug = (string) $submission->type_slug;
        $titles = self::slugTitlesFromCms();
        if ($slug === '' || ! isset($titles[$slug])) {
            return null;
        }

        try {
            $cms = CmsPageService::merged('pendaftaran');
        } catch (\Throwable) {
            $cms = ['cards' => []];
        }

        $columns = RegistrationSubmissionService::listColumnsForSlug($slug, $cms);
        $phone = RegistrationSubmissionService::phoneFromSubmission($submission, $columns);
        $chatId = WhatsAppChatId::fromPhone($phone);
        if ($chatId === null || $chatId === '') {
            return null;
        }

        $name = self::nameFromSubmission($submission, $columns);
        if ($name === '') {
            $name = 'Data #'.$submission->id;
        }

        return [
            'user_id' => null,
            'recipient_name' => $name,
            'recipient_phone' => RegistrationSubmissionService::normalizePhoneText((string) $phone),
            'chat_id' => $chatId,
        ];
    }

    public static function keyFromTemplateUser(\App\Models\WhatsappBroadcastTemplateUser $row): ?string
    {
        if ($row->user_id !== null) {
            return self::userKey((int) $row->user_id);
        }

        if (filled($row->chat_id)) {
            foreach (self::options() as $option) {
                $resolved = self::resolve($option['key']);
                if ($resolved !== null && $resolved['chat_id'] === $row->chat_id) {
                    return $option['key'];
                }
            }
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
            $phone = RegistrationSubmissionService::displayPhone(
                RegistrationSubmissionService::normalizePhoneText((string) $row->user->phone)
            );

            return $row->user->name.' - '.$phone;
        }

        $phone = RegistrationSubmissionService::displayPhone(
            RegistrationSubmissionService::normalizePhoneText((string) ($row->recipient_phone ?? ''))
        );
        $name = $row->recipient_name ?? 'Penerima';

        return $name.' - '.$phone;
    }

    public static function userKey(int $id): string
    {
        return 'user:'.$id;
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
     * @return array{user_id: int, recipient_name: string, recipient_phone: string, chat_id: string}|null
     */
    private static function resolveUser(User $user): ?array
    {
        if (! User::phoneColumnReady() || trim((string) $user->phone) === '') {
            return null;
        }

        $phone = RegistrationSubmissionService::normalizePhoneText((string) $user->phone);
        $chatId = WhatsAppChatId::fromPhone($phone);
        if ($chatId === null || $chatId === '') {
            return null;
        }

        return [
            'user_id' => $user->id,
            'recipient_name' => $user->name,
            'recipient_phone' => $phone,
            'chat_id' => $chatId,
        ];
    }

    /**
     * @param  list<array{name: string, label: string, type?: string}>  $columns
     */
    private static function nameFromSubmission(RegistrationSubmission $submission, array $columns): string
    {
        foreach ($columns as $column) {
            $fieldName = (string) ($column['name'] ?? '');
            if ($fieldName === '') {
                continue;
            }
            $value = $submission->payloadValue($fieldName);
            if (! is_string($value) || trim($value) === '') {
                continue;
            }
            if (RegistrationSubmissionService::valueLooksLikePhone($value)) {
                continue;
            }
            $labelLower = strtolower((string) ($column['label'] ?? ''));
            $nameLower = strtolower($fieldName);
            $looksLikeName = str_contains($labelLower, 'nama')
                || str_contains($nameLower, 'nama')
                || str_contains($nameLower, 'name');
            if ($looksLikeName || preg_match('/[a-zA-Z]/', $value) === 1) {
                return trim($value);
            }
        }

        $payload = is_array($submission->payload) ? $submission->payload : [];

        foreach (['nama_lengkap', 'nama', 'name', 'full_name', 'bride_name', 'groom_name'] as $key) {
            $value = $payload[$key] ?? null;
            if (is_string($value) && trim($value) !== '' && ! RegistrationSubmissionService::valueLooksLikePhone($value)) {
                return trim($value);
            }
        }

        return '';
    }
}
