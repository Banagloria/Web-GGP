<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

final class PublicCmsUrl
{
    public const PENDAFTARAN_BASE_PATH = '/pendaftaran';

    /** @var array<string, string> Nama rute Laravel lama → path URL */
    private const LEGACY_ROUTE_TO_PATH = [
        'home' => '/',
        'profil' => '/profil',
        'struktur' => '/struktur',
        'jadwal' => '/jadwal',
        'pendaftaran.index' => '/pendaftaran',
        'informasi-kegiatan' => '/informasi-kegiatan',
        'kontak' => '/kontak',
        'album' => '/galeri',
        'galeri' => '/galeri',
    ];

    public static function fromPathOrUrl(string $raw): string
    {
        $raw = trim($raw);
        if ($raw === '' || $raw === '.') {
            return '#';
        }
        if (preg_match('#^https?://#i', $raw)) {
            return $raw;
        }
        if ($raw === '/' || $raw === 'home') {
            return url('/');
        }
        if (str_starts_with($raw, '/')) {
            return url($raw);
        }

        return url('/'.ltrim($raw, '/'));
    }

    /** Tampilan di form admin: path "/pendaftaran" bukan "pendaftaran.index". */
    public static function formatNavPathForInput(?string $stored): string
    {
        return self::normalizeNavPathForStorage($stored);
    }

    /**
     * Form kartu halaman pendaftaran: tampilkan hanya slug (mis. "baptisan"), bukan "/pendaftaran/baptisan".
     */
    public static function formatPendaftaranCardSlugForInput(?string $stored): string
    {
        $stored = trim((string) ($stored ?? ''));
        if ($stored === '') {
            return '';
        }
        if (preg_match('#^https?://#i', $stored)) {
            return $stored;
        }

        $prefix = self::PENDAFTARAN_BASE_PATH.'/';
        if (str_starts_with($stored, $prefix)) {
            return substr($stored, strlen($prefix));
        }
        if ($stored === self::PENDAFTARAN_BASE_PATH) {
            return '';
        }

        return ltrim($stored, '/');
    }

    /**
     * Simpan path penuh; input admin cukup slug di bawah /pendaftaran/.
     */
    public static function normalizePendaftaranCardUrlForStorage(?string $raw): string
    {
        $raw = trim((string) ($raw ?? ''));
        if ($raw === '' || $raw === '.') {
            return '#';
        }
        if (preg_match('#^https?://#i', $raw)) {
            return $raw;
        }
        if (str_starts_with($raw, '/')) {
            if ($raw === self::PENDAFTARAN_BASE_PATH) {
                return self::PENDAFTARAN_BASE_PATH;
            }

            return $raw;
        }

        $slug = trim($raw, '/');
        if ($slug === '') {
            return self::PENDAFTARAN_BASE_PATH;
        }

        return self::PENDAFTARAN_BASE_PATH.'/'.$slug;
    }

    /** Simpan path URL; konversi nama rute lama (mis. pendaftaran.index → /pendaftaran). */
    public static function normalizeNavPathForStorage(?string $raw): string
    {
        $raw = trim((string) ($raw ?? ''));
        if ($raw === '' || $raw === '.') {
            return '/';
        }
        if (preg_match('#^https?://#i', $raw)) {
            return $raw;
        }

        $legacy = self::legacyRouteToPath($raw);
        if ($legacy !== null) {
            return $legacy;
        }

        if (str_starts_with($raw, '/')) {
            return $raw;
        }

        return '/'.ltrim($raw, '/');
    }

    private static function legacyRouteToPath(string $raw): ?string
    {
        if (isset(self::LEGACY_ROUTE_TO_PATH[$raw])) {
            return self::LEGACY_ROUTE_TO_PATH[$raw];
        }

        if (str_contains($raw, '.') && str_ends_with($raw, '.index')) {
            $slug = substr($raw, 0, -strlen('.index'));

            return $slug !== '' ? '/'.$slug : '/';
        }

        return null;
    }

    /**
     * URL aman untuk atribut src pratinjau gambar di panel admin.
     */
    public static function imagePreviewSrc(?string $url): ?string
    {
        if ($url === null) {
            return null;
        }
        $url = trim($url);
        if ($url === '') {
            return null;
        }
        if (preg_match('#^https?://#i', $url)) {
            return $url;
        }
        if (str_starts_with($url, '/')) {
            return url($url);
        }

        return asset(ltrim($url, '/'));
    }

    /**
     * Path relatif ke disk `public` jika $url mengarah ke unggahan lokal (/storage/...).
     */
    public static function publicStorageRelativePath(?string $url): ?string
    {
        if ($url === null) {
            return null;
        }
        $url = trim($url);
        if ($url === '') {
            return null;
        }
        if (preg_match('#^https?://[^/]+(/storage/.+)$#i', $url, $m)) {
            $url = $m[1];
        }
        if (! str_starts_with($url, '/storage/')) {
            return null;
        }
        $rel = ltrim(substr($url, strlen('/storage/')), '/');
        if ($rel === '' || str_contains($rel, '..')) {
            return null;
        }

        return $rel;
    }

    /** Hapus file di storage publik bila URL adalah unggahan lokal. */
    public static function deletePublicStorageFileIfUrlIsLocal(?string $url): void
    {
        $rel = self::publicStorageRelativePath($url);
        if ($rel !== null) {
            Storage::disk('public')->delete($rel);
        }
    }
}
