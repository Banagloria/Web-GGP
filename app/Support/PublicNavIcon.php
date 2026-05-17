<?php

namespace App\Support;

final class PublicNavIcon
{
    /** Ikon Font Awesome (kelas lengkap) untuk URL jalur navigasi atau nama rute CMS. */
    public static function forRouteRaw(?string $raw): string
    {
        $raw = trim((string) ($raw ?? ''));
        if ($raw === '') {
            return 'fa-solid fa-link';
        }
        if (preg_match('#^https?://#i', $raw)) {
            return 'fa-solid fa-arrow-up-right-from-square';
        }

        $norm = PublicCmsUrl::normalizeNavPathForStorage($raw);
        if (preg_match('#^https?://#i', $norm)) {
            return 'fa-solid fa-arrow-up-right-from-square';
        }

        $path = '/' . trim((string) $norm, '/');
        if ($path === '/') {
            return 'fa-solid fa-house';
        }

        return match ($path) {
            '/profil' => 'fa-solid fa-circle-info',
            '/struktur' => 'fa-solid fa-sitemap',
            '/jadwal' => 'fa-solid fa-calendar-days',
            '/pendaftaran' => 'fa-solid fa-clipboard-list',
            '/informasi-kegiatan' => 'fa-solid fa-bullhorn',
            '/kontak' => 'fa-solid fa-envelope',
            '/galeri' => 'fa-solid fa-images',
            '/album' => 'fa-solid fa-images',
            default => self::matchPrefix($path),
        };
    }

    private static function matchPrefix(string $path): string
    {
        $rules = [
            '/informasi-kegiatan' => 'fa-solid fa-bullhorn',
            '/pendaftaran' => 'fa-solid fa-clipboard-list',
            '/galeri' => 'fa-solid fa-images',
            '/album' => 'fa-solid fa-images',
        ];
        foreach ($rules as $prefix => $icon) {
            if ($path === $prefix || str_starts_with($path, $prefix.'/')) {
                return $icon;
            }
        }

        return 'fa-solid fa-link';
    }
}
