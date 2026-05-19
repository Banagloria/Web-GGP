<?php

namespace App\Services;

use App\Support\PendaftaranCardCms;

final class WhatsAppTriggerCatalog
{
    /**
     * @return list<array{key: string, label: string}>
     */
    public static function options(): array
    {
        $options = [];

        $kontak = CmsPageService::merged('kontak');
        $kontakFormName = trim((string) ($kontak['h1'] ?? ''));
        $options[] = [
            'key' => 'kontak.submit',
            'label' => self::formButtonLabel($kontakFormName !== '' ? $kontakFormName : 'Kontak'),
        ];

        $pendaftaran = CmsPageService::merged('pendaftaran');
        $cardDetails = is_array($pendaftaran['card_details'] ?? null) ? $pendaftaran['card_details'] : [];

        foreach ($pendaftaran['cards'] ?? [] as $card) {
            if (! is_array($card)) {
                continue;
            }

            $cardKey = trim((string) ($card['key'] ?? ''));
            if ($cardKey === '') {
                continue;
            }

            $url = trim((string) ($card['url'] ?? ''));
            $slug = $url !== '' ? trim(basename($url), '/') : $cardKey;
            if ($slug === '') {
                $slug = $cardKey;
            }

            $detail = is_array($cardDetails[$cardKey] ?? null) ? $cardDetails[$cardKey] : [];
            $pageTitle = trim((string) ($detail['title'] ?? ($card['title'] ?? $cardKey)));

            $options[] = [
                'key' => 'pendaftaran.'.$slug.'.submit',
                'label' => self::formButtonLabel($pageTitle !== '' ? $pageTitle : $cardKey),
            ];
        }

        return $options;
    }

    public static function labelForKey(string $triggerKey): string
    {
        foreach (self::options() as $option) {
            if ($option['key'] === $triggerKey) {
                return $option['label'];
            }
        }

        return $triggerKey;
    }

    public static function isValidKey(string $triggerKey): bool
    {
        foreach (self::options() as $option) {
            if ($option['key'] === $triggerKey) {
                return true;
            }
        }

        return false;
    }

    private static function formButtonLabel(string $formName): string
    {
        $name = trim($formName);

        return 'Tombol Kirim Form '.($name !== '' ? $name : 'Formulir');
    }

    /**
     * Nama atribut input form publik yang bisa dipakai sebagai {nama_field}.
     *
     * @return list<string>
     */
    public static function fieldNamesForTrigger(string $triggerKey): array
    {
        if ($triggerKey === 'kontak.submit') {
            return self::fieldNamesFromKontak();
        }

        $slug = self::slugFromTriggerKey($triggerKey);
        if ($slug !== null) {
            return self::fieldNamesFromPendaftaranSlug($slug);
        }

        return [];
    }

    /**
     * @return array<string, list<string>>
     */
    public static function placeholderMap(): array
    {
        $map = [];

        foreach (self::options() as $option) {
            $map[$option['key']] = self::fieldNamesForTrigger($option['key']);
        }

        return $map;
    }

    /**
     * @return list<string>
     */
    private static function fieldNamesFromKontak(): array
    {
        $kontak = CmsPageService::merged('kontak');
        $names = [];

        foreach ($kontak['form_fields'] ?? [] as $field) {
            if (! is_array($field)) {
                continue;
            }

            $name = trim((string) ($field['name'] ?? ''));
            if ($name !== '') {
                $names[] = $name;
            }
        }

        return $names;
    }

    /**
     * @return list<string>
     */
    private static function fieldNamesFromPendaftaranSlug(string $slug): array
    {
        $cms = CmsPageService::merged('pendaftaran');
        $resolved = PendaftaranCardCms::resolveBySlug($cms, $slug);
        if ($resolved === null) {
            return [];
        }

        $names = [];
        foreach (PendaftaranCardCms::fieldsFromDetail($resolved['detail']) as $field) {
            $name = trim((string) ($field['name'] ?? ''));
            if ($name !== '') {
                $names[] = $name;
            }
        }

        return $names;
    }

    private static function slugFromTriggerKey(string $triggerKey): ?string
    {
        if (preg_match('/^pendaftaran\.(.+)\.submit$/', $triggerKey, $matches) !== 1) {
            return null;
        }

        $slug = trim($matches[1]);

        return $slug !== '' ? $slug : null;
    }

    /**
     * Contoh nilai untuk uji kirim / preview variabel di pesan.
     *
     * @return array<string, string>
     */
    public static function sampleReplacementsForTrigger(string $triggerKey): array
    {
        $samples = [];

        foreach (self::fieldNamesForTrigger($triggerKey) as $fieldName) {
            $samples[$fieldName] = '['.$fieldName.']';
        }

        $slug = self::slugFromTriggerKey($triggerKey);
        if ($slug !== null) {
            $cms = CmsPageService::merged('pendaftaran');
            $resolved = PendaftaranCardCms::resolveBySlug($cms, $slug);
            if ($resolved !== null) {
                $detail = $resolved['detail'];
                $card = $resolved['card'];
                $samples['judul'] = trim((string) ($detail['title'] ?? ($card['title'] ?? 'Pendaftaran')));
                $samples['slug'] = $slug;
            }
        }

        return $samples;
    }
}
