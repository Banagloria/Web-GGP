<?php

namespace App\Services;

use App\Models\Announcement;
use App\Models\GalleryItem;
use App\Models\User;
use App\Models\WorshipSchedule;
use Illuminate\Support\Facades\Storage;

final class WhatsAppBroadcastCatalog
{
    public const TRIGGER_PENGUMUMAN = 'pengumuman.create';

    public const TRIGGER_JADWAL = 'jadwal.create';

    public const TRIGGER_GALERI = 'galeri.create';

    public const AUDIENCE_ONE_BY_ONE = 'one_by_one';

    public const AUDIENCE_DATA_PREFIX = 'data:';

    /** Semua pengurus di menu Manajemen akun (admin / super admin) yang punya nomor HP. */
    public const AUDIENCE_PANEL_ACCOUNTS = 'accounts:panel';

    /** @deprecated Disimpan di database lama — tetap didukung saat kirim */
    public const AUDIENCE_ALL_MEMBERS = 'all_members';

    /** @deprecated */
    public const AUDIENCE_ALL_ADMINS = 'all_admins';

    /** @deprecated */
    public const AUDIENCE_ALL_MEMBERS_AND_ADMINS = 'all_members_and_admins';

    /** @deprecated */
    public const AUDIENCE_ALL_ACCEPTED_DATA = 'all_accepted_data';

    /**
     * @return list<array{key: string, label: string}>
     */
    public static function triggerOptions(): array
    {
        return [
            ['key' => self::TRIGGER_PENGUMUMAN, 'label' => 'Create pengumuman'],
            ['key' => self::TRIGGER_JADWAL, 'label' => 'Create jadwal'],
            ['key' => self::TRIGGER_GALERI, 'label' => 'Create galeri'],
        ];
    }

    /**
     * Opsi dari nama kartu menu Data (hanya form yang punya field tipe telepon).
     *
     * @return list<array{key: string, label: string}>
     */
    public static function audienceOptions(): array
    {
        $options = [];

        try {
            $cms = CmsPageService::merged('pendaftaran');
        } catch (\Throwable) {
            $cms = ['cards' => []];
        }

        foreach (WhatsAppBroadcastRecipientOptions::slugTitlesFromCms() as $slug => $title) {
            if (! WhatsAppBroadcastRecipientOptions::slugHasPhoneField($slug, $cms)) {
                continue;
            }

            $count = count(WhatsAppBroadcastRecipientOptions::acceptedDataEntriesForSlug($slug, $cms, $title));
            $label = $title;
            if ($count > 0) {
                $label .= ' ('.$count.' nomor HP)';
            }

            $options[] = [
                'key' => self::audienceDataKey($slug),
                'label' => $label,
            ];
        }

        if (User::phoneColumnReady()) {
            $panelCount = count(WhatsAppBroadcastRecipientOptions::chatIdsForPanelAccounts());
            $panelLabel = 'Manajemen akun';
            if ($panelCount > 0) {
                $panelLabel .= ' ('.$panelCount.' nomor HP)';
            }
            $options[] = [
                'key' => self::AUDIENCE_PANEL_ACCOUNTS,
                'label' => $panelLabel,
            ];
        }

        $options[] = [
            'key' => self::AUDIENCE_ONE_BY_ONE,
            'label' => 'Pilih satu per satu',
        ];

        return $options;
    }

    public static function audienceDataKey(string $slug): string
    {
        return self::AUDIENCE_DATA_PREFIX.$slug;
    }

    public static function audienceSlugFromKey(string $audience): ?string
    {
        if (! str_starts_with($audience, self::AUDIENCE_DATA_PREFIX)) {
            return null;
        }

        $slug = substr($audience, strlen(self::AUDIENCE_DATA_PREFIX));

        return $slug !== '' ? $slug : null;
    }

    /**
     * @return list<string>
     */
    public static function audienceOptionKeys(): array
    {
        return array_column(self::audienceOptions(), 'key');
    }

    public static function isValidTrigger(string $key): bool
    {
        return in_array($key, array_column(self::triggerOptions(), 'key'), true);
    }

    public static function isValidAudience(string $key): bool
    {
        if (in_array($key, self::audienceOptionKeys(), true)) {
            return true;
        }

        return in_array($key, [
            self::AUDIENCE_ALL_MEMBERS,
            self::AUDIENCE_ALL_ADMINS,
            self::AUDIENCE_ALL_MEMBERS_AND_ADMINS,
            self::AUDIENCE_ALL_ACCEPTED_DATA,
        ], true);
    }

    public static function triggerLabel(string $key): string
    {
        foreach (self::triggerOptions() as $option) {
            if ($option['key'] === $key) {
                return $option['label'];
            }
        }

        return $key;
    }

    public static function audienceLabel(string $key): string
    {
        foreach (self::audienceOptions() as $option) {
            if ($option['key'] === $key) {
                return $option['label'];
            }
        }

        $slug = self::audienceSlugFromKey($key);
        if ($slug !== null) {
            $titles = WhatsAppBroadcastRecipientOptions::slugTitlesFromCms();

            return $titles[$slug] ?? $slug;
        }

        return match ($key) {
            self::AUDIENCE_PANEL_ACCOUNTS => 'Manajemen akun',
            self::AUDIENCE_ALL_MEMBERS => 'Semua jemaat',
            self::AUDIENCE_ALL_ADMINS => 'Semua admin',
            self::AUDIENCE_ALL_MEMBERS_AND_ADMINS => 'Semua jemaat dan admin',
            self::AUDIENCE_ALL_ACCEPTED_DATA => 'Semua data diterima',
            self::AUDIENCE_ONE_BY_ONE => 'Pilih satu per satu',
            default => $key,
        };
    }

    /**
     * @return list<string>
     */
    public static function fieldNamesForTrigger(string $triggerKey): array
    {
        return match ($triggerKey) {
            self::TRIGGER_PENGUMUMAN => ['title', 'body', 'published_at', 'slug', 'is_published'],
            self::TRIGGER_JADWAL => self::jadwalFieldNames(),
            self::TRIGGER_GALERI => ['original_name', 'path', 'mime', 'caption', 'jumlah_foto'],
            default => [],
        };
    }

    /**
     * @return array<string, list<string>>
     */
    public static function placeholderMap(): array
    {
        $map = [];
        foreach (self::triggerOptions() as $option) {
            $map[$option['key']] = self::fieldNamesForTrigger($option['key']);
        }

        return $map;
    }

    /**
     * @return array<string, string>
     */
    public static function replacementsFromAnnouncement(Announcement $announcement): array
    {
        return WhatsAppNotificationDispatcher::replacementsFromFormData([
            'title' => $announcement->title,
            'body' => $announcement->body,
            'published_at' => $announcement->published_at?->timezone(config('app.timezone'))->format('d/m/Y H:i') ?? '',
            'slug' => $announcement->slug,
            'is_published' => $announcement->is_published,
        ]);
    }

    /**
     * @return array<string, string>
     */
    public static function replacementsFromSchedule(WorshipSchedule $schedule): array
    {
        $data = [
            'schedule_date' => $schedule->schedule_date?->format('d/m/Y') ?? '',
            'starts_at' => substr((string) $schedule->starts_at, 0, 5),
            'ends_at' => substr((string) $schedule->ends_at, 0, 5),
            'activity_name' => $schedule->activity_name,
            'location' => $schedule->location ?? '',
        ];

        $extras = is_array($schedule->extra_columns) ? $schedule->extra_columns : [];
        foreach ($extras as $index => $value) {
            $data['kolom_'.($index + 1)] = (string) $value;
        }

        return WhatsAppNotificationDispatcher::replacementsFromFormData($data);
    }

    /**
     * @return array<string, string>
     */
    public static function replacementsFromGalleryItem(GalleryItem $item, int $photoCount = 1): array
    {
        $path = $item->path !== '' ? (string) url(Storage::url($item->path)) : '';

        return WhatsAppNotificationDispatcher::replacementsFromFormData([
            'original_name' => $item->original_name,
            'path' => $path,
            'mime' => $item->mime,
            'caption' => $item->caption ?? '',
            'jumlah_foto' => (string) $photoCount,
        ]);
    }

    /**
     * @return list<string>
     */
    private static function jadwalFieldNames(): array
    {
        $names = ['schedule_date', 'starts_at', 'ends_at', 'activity_name', 'location'];
        $cms = CmsPageService::merged('jadwal');
        $count = WorshipSchedulePartitionService::dynamicColumnCount($cms);
        for ($i = 0; $i < $count; $i++) {
            $names[] = 'kolom_'.($i + 1);
        }

        return $names;
    }
}
