<?php

namespace App\Services;

use App\Models\WorshipSchedule;
use App\Support\CmsIcon;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

final class WorshipSchedulePartitionService
{
    public const ACTION_HEADER_DEFAULT = 'Aksi';

    public const ROWS_PER_PAGE = 6;

    public static function occurrenceStart(WorshipSchedule $row): Carbon
    {
        return self::occurrenceForDateTime($row, (string) $row->starts_at);
    }

    public static function occurrenceEnd(WorshipSchedule $row): Carbon
    {
        return self::occurrenceForDateTime($row, (string) ($row->ends_at ?? $row->starts_at));
    }

    /** @deprecated Use occurrenceStart() */
    public static function occurrenceStartThisWeek(WorshipSchedule $row): Carbon
    {
        return self::occurrenceStart($row);
    }

    /** @deprecated Use occurrenceEnd() */
    public static function occurrenceEndThisWeek(WorshipSchedule $row): Carbon
    {
        return self::occurrenceEnd($row);
    }

    public static function occurrenceForDateTime(WorshipSchedule $row, string $time): Carbon
    {
        $date = $row->schedule_date
            ? Carbon::parse($row->schedule_date)->startOfDay()
            : self::legacyDateFromDayOfWeek($row);

        $time = Str::of($time)->substr(0, 5)->toString();
        [$hour, $minute] = array_pad(explode(':', $time), 2, '0');

        return $date->copy()->setTime((int) $hour, (int) $minute, 0);
    }

    private static function legacyDateFromDayOfWeek(WorshipSchedule $row): Carbon
    {
        $startOfWeek = Carbon::now()->copy()->startOfWeek(Carbon::SUNDAY);

        return $startOfWeek->copy()->addDays((int) $row->day_of_week)->startOfDay();
    }

    public static function isCompleted(WorshipSchedule $row): bool
    {
        return Carbon::now()->greaterThanOrEqualTo(self::occurrenceEnd($row));
    }

    public static function isInProgress(WorshipSchedule $row): bool
    {
        $now = Carbon::now();

        return $now->greaterThanOrEqualTo(self::occurrenceStart($row))
            && $now->lessThan(self::occurrenceEnd($row));
    }

    public static function isActionColumn(string $header): bool
    {
        return str_contains(mb_strtolower(trim($header)), 'aksi');
    }

    /**
     * @param  Collection<int, WorshipSchedule>  $rows
     * @return array{upcoming: Collection<int, WorshipSchedule>, completed: Collection<int, WorshipSchedule>}
     */
    public static function partition(Collection $rows, bool $activeOnly = true): array
    {
        $filtered = $activeOnly
            ? $rows->filter(fn (WorshipSchedule $r) => $r->is_active)
            : $rows;

        $upcoming = $filtered
            ->filter(fn (WorshipSchedule $r) => ! self::isCompleted($r))
            ->sortByDesc(fn (WorshipSchedule $r) => $r->created_at?->timestamp ?? 0)
            ->values();

        $completed = $filtered
            ->filter(fn (WorshipSchedule $r) => self::isCompleted($r))
            ->sortByDesc(fn (WorshipSchedule $r) => self::occurrenceEnd($r)->timestamp)
            ->values();

        return [
            'upcoming' => $upcoming,
            'completed' => $completed,
        ];
    }

    public static function relativeTimeLabel(WorshipSchedule $row): string
    {
        $now = Carbon::now();
        $start = self::occurrenceStart($row);
        $end = self::occurrenceEnd($row);

        if ($now->lt($start)) {
            return 'Mulai '.$start->diffForHumans();
        }

        if ($now->lt($end)) {
            return 'Berlangsung · selesai '.$end->diffForHumans();
        }

        return 'Selesai '.$end->diffForHumans();
    }

    public static function timeDisplay(WorshipSchedule $row): string
    {
        $date = $row->schedule_date
            ? Carbon::parse($row->schedule_date)->locale('id')->translatedFormat('d/m/Y')
            : self::legacyDateFromDayOfWeek($row)->locale('id')->translatedFormat('d/m/Y');

        $start = Carbon::parse($row->starts_at)->format('H:i');
        $end = Carbon::parse($row->ends_at ?? $row->starts_at)->format('H:i');
        $range = $start === $end ? $start : $start.' – '.$end;

        return $date.' · '.$range;
    }

    /**
     * @param  array<string, mixed>  $cms
     * @return list<string>
     */
    public static function headersUpcoming(array $cms): array
    {
        return self::normalizeTableHeaders($cms['table_headers_upcoming'] ?? $cms['table_headers'] ?? null);
    }

    /**
     * @param  array<string, mixed>  $cms
     * @return list<string>
     */
    public static function headersCompleted(array $cms): array
    {
        return self::normalizeTableHeaders($cms['table_headers_completed'] ?? $cms['table_headers'] ?? null);
    }

    /**
     * @param  list<string>  $headers
     * @return list<string>
     */
    public static function middleHeaders(array $headers): array
    {
        if (count($headers) <= 2) {
            return [];
        }

        return array_values(array_slice($headers, 1, -1));
    }

    /**
     * @param  list<string>  $headers
     * @return list<string>
     */
    public static function publicHeaders(array $headers): array
    {
        if ($headers === []) {
            return [];
        }

        if (self::isActionColumn((string) end($headers))) {
            return array_values(array_slice($headers, 0, -1));
        }

        return $headers;
    }

    /**
     * @param  array<string, mixed>  $cms
     */
    public static function dynamicColumnCount(array $cms): int
    {
        $up = count(self::headersUpcoming($cms));
        $done = count(self::headersCompleted($cms));

        return max(0, max($up, $done) - 2);
    }

    /**
     * @param  array<string, mixed>  $cms
     * @return list<string>
     */
    public static function middleLabelsFromCms(array $cms): array
    {
        $up = self::middleHeaders(self::headersUpcoming($cms));
        $done = self::middleHeaders(self::headersCompleted($cms));
        $count = max(count($up), count($done));
        $labels = [];
        for ($i = 0; $i < $count; $i++) {
            $labels[] = $up[$i] ?? $done[$i] ?? ('Kolom '.($i + 1));
        }

        return $labels;
    }

    public static function middleCellValue(WorshipSchedule $row, int $middleIndex): string
    {
        $extras = $row->extra_columns;
        if (! is_array($extras)) {
            return '';
        }

        return (string) ($extras[$middleIndex] ?? '');
    }

    /**
     * @param  array<string, mixed>  $cms
     */
    public static function cellValue(WorshipSchedule $row, int $columnIndex, array $cms, array $headers): string
    {
        if ($columnIndex === 0) {
            return self::timeDisplay($row);
        }

        $lastIndex = count($headers) - 1;
        if ($lastIndex >= 0 && $columnIndex === $lastIndex && self::isActionColumn((string) $headers[$lastIndex])) {
            return '';
        }

        return self::middleCellValue($row, $columnIndex - 1);
    }

    /**
     * @param  list<string>  $headers
     * @return list<string>
     */
    public static function ensureWaktuFirst(array $headers): array
    {
        if ($headers === []) {
            return ['Waktu', self::ACTION_HEADER_DEFAULT];
        }

        $headers = array_values($headers);
        if (trim((string) ($headers[0] ?? '')) === '') {
            $headers[0] = 'Waktu';
        }

        return $headers;
    }

    /**
     * @param  list<string>  $headers
     * @return list<string>
     */
    public static function ensureAksiLast(array $headers): array
    {
        if ($headers === []) {
            return ['Waktu', self::ACTION_HEADER_DEFAULT];
        }

        $lastIndex = count($headers) - 1;
        if (self::isActionColumn((string) $headers[$lastIndex])) {
            return $headers;
        }

        foreach ($headers as $i => $h) {
            if (self::isActionColumn((string) $h) && $i !== $lastIndex) {
                $aksi = $headers[$i];
                $rest = array_values(array_filter(
                    $headers,
                    static fn ($_, int $j) => $j !== $i,
                    ARRAY_FILTER_USE_BOTH
                ));

                return array_merge($rest, [$aksi]);
            }
        }

        return array_merge($headers, [self::ACTION_HEADER_DEFAULT]);
    }

    /**
     * @param  mixed  $headers
     * @return list<string>
     */
    private static function normalizeTableHeaders(mixed $headers): array
    {
        $fallback = ['Waktu', 'Hari', 'Kegiatan', 'Lokasi', self::ACTION_HEADER_DEFAULT];

        if (! is_array($headers) || $headers === []) {
            return $fallback;
        }

        $normalized = array_values(array_map(
            static fn ($h) => trim((string) $h),
            $headers
        ));

        return self::ensureAksiLast(self::ensureWaktuFirst($normalized));
    }

    /**
     * @param  array<string, mixed>  $cms
     */
    public static function normalizeJadwalCms(array $cms): array
    {
        $legacy = $cms['table_headers'] ?? null;
        if (is_array($legacy) && $legacy !== []) {
            if (! isset($cms['table_headers_upcoming']) || ! is_array($cms['table_headers_upcoming'])) {
                $cms['table_headers_upcoming'] = $legacy;
            }
            if (! isset($cms['table_headers_completed']) || ! is_array($cms['table_headers_completed'])) {
                $cms['table_headers_completed'] = $legacy;
            }
        }

        $cms['table_headers_upcoming'] = self::normalizeTableHeaders($cms['table_headers_upcoming'] ?? null);
        $cms['table_headers_completed'] = $cms['table_headers_upcoming'];

        $cms['table_column_icons_upcoming'] = self::normalizeColumnIcons(
            $cms['table_column_icons_upcoming'] ?? null,
            $cms['table_headers_upcoming']
        );
        $cms['table_column_icons_completed'] = $cms['table_column_icons_upcoming'];

        unset($cms['column_bindings']);

        $cms['section_upcoming_title'] = $cms['section_upcoming_title'] ?? 'Jadwal mendatang';
        $cms['section_completed_title'] = $cms['section_completed_title'] ?? 'Jadwal selesai';
        $cms['show_next_label'] = $cms['show_next_label'] ?? 'Selanjutnya';

        return $cms;
    }

    /**
     * @return list<string>
     */
    public static function columnValuesFromRow(WorshipSchedule $row): array
    {
        $extras = $row->extra_columns;

        return is_array($extras) ? array_values($extras) : [];
    }

    /**
     * @param  list<string>  $values
     * @return array{extra_columns: list<string>|null, activity_name: string, day_of_week: int}
     */
    public static function persistColumnValues(array $values, ?WorshipSchedule $existing = null): array
    {
        $trimmed = array_values(array_map(static fn ($v) => trim((string) $v), $values));

        return [
            'extra_columns' => $trimmed === [] ? null : $trimmed,
            'activity_name' => $trimmed[0] ?? ($existing?->activity_name ?? ''),
            'location' => isset($trimmed[1]) ? ($trimmed[1] !== '' ? $trimmed[1] : null) : $existing?->location,
            'day_of_week' => $existing?->day_of_week ?? 0,
        ];
    }

    /**
     * @deprecated Use columnIconClass() — kept for backward compatibility in views.
     */
    public static function tableIconKey(int $columnIndex): string
    {
        return match ($columnIndex) {
            0 => 'table_time',
            1 => 'table_day',
            2 => 'table_activity',
            3 => 'table_location',
            default => 'table_time',
        };
    }

    /**
     * @param  'upcoming'|'completed'  $table
     */
    public static function columnIconClass(array $cms, string $table, int $columnIndex): string
    {
        $icons = self::columnIconsForTable($cms, $table);

        return $icons[$columnIndex] ?? 'fa-solid fa-circle';
    }

    /**
     * @param  'upcoming'|'completed'  $table
     * @return list<string>
     */
    public static function columnIconsForTable(array $cms, string $table): array
    {
        $key = $table === 'completed' ? 'table_column_icons_completed' : 'table_column_icons_upcoming';
        $headers = $table === 'completed' ? self::headersCompleted($cms) : self::headersUpcoming($cms);
        $stored = $cms[$key] ?? [];
        if (! is_array($stored)) {
            $stored = [];
        }

        $icons = [];
        $count = count($headers);
        for ($i = 0; $i < $count; $i++) {
            $header = (string) ($headers[$i] ?? '');
            $raw = trim((string) ($stored[$i] ?? ''));
            $default = self::defaultColumnIconForIndex($i, $count, $header);
            $icons[$i] = $raw !== '' ? CmsIcon::toFontAwesome($raw, $default) : $default;
        }

        return $icons;
    }

    public static function defaultColumnIconForIndex(int $index, int $total, string $header): string
    {
        if ($index === 0) {
            return 'fa-regular fa-clock';
        }

        if ($index === $total - 1 && self::isActionColumn($header)) {
            return 'fa-solid fa-gear';
        }

        return match ($index) {
            1 => 'fa-solid fa-calendar-week',
            2 => 'fa-solid fa-book-bible',
            3 => 'fa-solid fa-location-dot',
            default => 'fa-solid fa-circle',
        };
    }

    /**
     * @param  list<string>  $headers
     * @return list<string>
     */
    public static function defaultColumnIconsForHeaders(array $headers): array
    {
        $count = count($headers);
        $icons = [];
        for ($i = 0; $i < $count; $i++) {
            $icons[] = self::defaultColumnIconForIndex($i, $count, (string) ($headers[$i] ?? ''));
        }

        return $icons;
    }

    /**
     * @param  mixed  $icons
     * @param  list<string>  $headers
     * @return list<string>
     */
    private static function normalizeColumnIcons(mixed $icons, array $headers): array
    {
        if (! is_array($icons)) {
            $icons = [];
        }

        $icons = array_values($icons);
        $result = [];
        $count = count($headers);

        for ($i = 0; $i < $count; $i++) {
            $header = (string) ($headers[$i] ?? '');
            $default = self::defaultColumnIconForIndex($i, $count, $header);
            $raw = trim((string) ($icons[$i] ?? ''));
            $result[$i] = $raw !== '' ? CmsIcon::toFontAwesome($raw, $default) : $default;
        }

        return $result;
    }
}
