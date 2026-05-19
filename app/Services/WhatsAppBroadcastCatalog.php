<?php

namespace App\Services;

use App\Models\Announcement;
use App\Models\GalleryItem;
use App\Models\WorshipSchedule;
use Illuminate\Support\Facades\Storage;

final class WhatsAppBroadcastCatalog
{
    public const TRIGGER_PENGUMUMAN = 'pengumuman.create';

    public const TRIGGER_JADWAL = 'jadwal.create';

    public const TRIGGER_GALERI = 'galeri.create';

    public const AUDIENCE_ALL_MEMBERS = 'all_members';

    public const AUDIENCE_ALL_ADMINS = 'all_admins';

    public const AUDIENCE_ALL_MEMBERS_AND_ADMINS = 'all_members_and_admins';

    public const AUDIENCE_ONE_BY_ONE = 'one_by_one';

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
     * @return list<array{key: string, label: string}>
     */
    public static function audienceOptions(): array
    {
        return [
            ['key' => self::AUDIENCE_ALL_MEMBERS, 'label' => 'Semua jemaat'],
            ['key' => self::AUDIENCE_ALL_ADMINS, 'label' => 'Semua admin'],
            ['key' => self::AUDIENCE_ALL_MEMBERS_AND_ADMINS, 'label' => 'Semua jemaat dan admin'],
            ['key' => self::AUDIENCE_ONE_BY_ONE, 'label' => 'One by one'],
        ];
    }

    public static function isValidTrigger(string $key): bool
    {
        return in_array($key, array_column(self::triggerOptions(), 'key'), true);
    }

    public static function isValidAudience(string $key): bool
    {
        return in_array($key, array_column(self::audienceOptions(), 'key'), true);
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

        return $key;
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
