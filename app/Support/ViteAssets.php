<?php

namespace App\Support;

/**
 * Cegah @vite memicu 500 bila manifest hilang / kosong / JSON rusak.
 */
final class ViteAssets
{
    public static function hotFileActive(): bool
    {
        return file_exists(public_path('hot'));
    }

    public static function manifestIsUsable(): bool
    {
        $path = public_path('build/manifest.json');
        if (! is_file($path)) {
            return false;
        }

        if (filesize($path) < 3) {
            return false;
        }

        try {
            $data = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);

            return is_array($data) && $data !== [];
        } catch (\JsonException) {
            return false;
        }
    }
}
