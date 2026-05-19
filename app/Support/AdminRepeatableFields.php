<?php

namespace App\Support;

use Illuminate\Http\Request;

/**
 * Menghapus baris repeatable kosong dari payload form admin sebelum validasi Laravel.
 */
final class AdminRepeatableFields
{
    /**
     * Ganti kunci di request tanpa merge rekursif (indeks array benar-benar dihapus).
     *
     * @param  array<string, mixed>  $replacements
     */
    public static function replaceInRequest(Request $request, array $replacements): void
    {
        $payload = $request->all();

        foreach ($replacements as $key => $value) {
            $payload[$key] = $value;
        }

        $request->replace($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public static function replacePayloadKeys(array $payload, array $replacements): array
    {
        foreach ($replacements as $key => $value) {
            $payload[$key] = $value;
        }

        return $payload;
    }
    /**
     * Pertahankan baris jika minimal satu field berisi teks (bukan baris template kosong).
     *
     * @param  list<string>  $significantFields
     * @param  list<mixed>  $rows
     * @return list<array<string, mixed>>
     */
    public static function pruneTemplateRows(array $rows, array $significantFields): array
    {
        $kept = [];

        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }

            foreach ($significantFields as $field) {
                if (trim((string) ($row[$field] ?? '')) !== '') {
                    $kept[] = $row;

                    break;
                }
            }
        }

        return $kept;
    }

    /**
     * @param  list<mixed>  $items
     * @return list<string>
     */
    public static function pruneStringList(array $items): array
    {
        $kept = [];

        foreach ($items as $item) {
            $value = trim((string) $item);
            if ($value !== '') {
                $kept[] = $value;
            }
        }

        return $kept;
    }
}
