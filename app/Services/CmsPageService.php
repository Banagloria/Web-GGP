<?php

namespace App\Services;

use App\Models\CmsPageContent;
use App\Models\SiteSetting;
use App\Support\CmsIcon;
use App\Support\CmsContentBlocks;
use App\Support\CmsPageIconDefaults;
use App\Support\CmsPublicPageDefaults;
use App\Support\PublicCmsUrl;
use App\Services\WorshipSchedulePartitionService;
use Throwable;

class CmsPageService
{
    /**
     * @return array<string, mixed>
     */
    public static function merged(string $pageKey): array
    {
        if (! in_array($pageKey, CmsPublicPageDefaults::PAGE_KEYS, true)) {
            return [];
        }

        $base = CmsPublicPageDefaults::defaultsFor($pageKey);
        $stored = null;
        try {
            $stored = CmsPageContent::dataFor($pageKey);
        } catch (Throwable) {
            $stored = null;
        }

        if (! is_array($stored)) {
            $merged = $base;
            if ($pageKey === 'beranda') {
                foreach (['church_name_line1', 'church_name_line2', 'site_logo_url'] as $k) {
                    try {
                        $fromSite = SiteSetting::get($k);
                        if ($fromSite !== null && trim((string) $fromSite) !== '') {
                            $merged[$k] = $fromSite;
                        }
                    } catch (Throwable) {
                        //
                    }
                }
            }

            $merged = self::normalizeIcons($pageKey, self::applyBerandaSiteSettings($pageKey, $merged));
            $merged = self::resolveBerandaFooterSocialLinks($pageKey, $merged, null);
            $merged = self::normalizeJadwalPage($pageKey, $merged);

            return self::ensureContentBlocks($pageKey, $merged);
        }

        $merged = array_replace_recursive($base, $stored);

        if ($pageKey === 'beranda') {
            foreach (['church_name_line1', 'church_name_line2', 'site_logo_url'] as $k) {
                if (! array_key_exists($k, $stored)) {
                    try {
                        $fromSite = SiteSetting::get($k);
                        if ($fromSite !== null && trim((string) $fromSite) !== '') {
                            $merged[$k] = $fromSite;
                        }
                    } catch (Throwable) {
                        //
                    }
                }
            }
        }

        $merged = self::normalizeIcons($pageKey, self::applyBerandaSiteSettings($pageKey, $merged));
        $merged = self::resolveBerandaFooterSocialLinks($pageKey, $merged, $stored);
        $merged = self::normalizeJadwalPage($pageKey, $merged);

        return self::ensureContentBlocks($pageKey, $merged);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function normalizeJadwalPage(string $pageKey, array $data): array
    {
        if ($pageKey !== 'jadwal') {
            return $data;
        }

        return WorshipSchedulePartitionService::normalizeJadwalCms($data);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function ensureContentBlocks(string $pageKey, array $data): array
    {
        if (in_array($pageKey, ['profil', 'struktur'], true)) {
            $blocks = $data['blocks'] ?? null;
            if (! is_array($blocks) || $blocks === []) {
                $data['blocks'] = CmsContentBlocks::editorBlocksFromStorage(null, (string) ($data['body'] ?? ''));
            } else {
                $data['blocks'] = CmsContentBlocks::normalize($blocks);
            }

            $blocks = $data['blocks'] ?? [];
            if (is_array($blocks) && $blocks !== []) {
                $data['body'] = CmsContentBlocks::toHtml($blocks);
            }
        }

        if ($pageKey === 'beranda') {
            $visionBlocks = $data['vision_blocks'] ?? null;
            if (! is_array($visionBlocks) || $visionBlocks === []) {
                $data['vision_blocks'] = CmsContentBlocks::editorBlocksFromStorage(
                    null,
                    (string) ($data['vision_body'] ?? '')
                );
            } else {
                $data['vision_blocks'] = CmsContentBlocks::normalize($visionBlocks);
            }

            $visionBlocks = $data['vision_blocks'] ?? [];
            if (is_array($visionBlocks) && $visionBlocks !== []) {
                $data['vision_body'] = CmsContentBlocks::toHtml($visionBlocks);
            }
        }

        return $data;
    }

    /**
     * Hero image URL: gunakan SiteSetting bila CMS kosong/null.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function applyBerandaSiteSettings(string $pageKey, array $data): array
    {
        if ($pageKey !== 'beranda') {
            return $data;
        }

        $url = $data['hero_image_url'] ?? null;
        if ($url === null || $url === '') {
            try {
                $data['hero_image_url'] = SiteSetting::get('hero_image_url');
            } catch (Throwable) {
                $data['hero_image_url'] = null;
            }
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function save(string $pageKey, array $data): void
    {
        CmsPageContent::put($pageKey, self::normalizeIcons($pageKey, $data));
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function normalizeIcons(string $pageKey, array $data): array
    {
        $data = CmsIcon::normalizePageData($pageKey, $data);
        $icons = CmsPageIconDefaults::normalizeStored($pageKey, $data['page_icons'] ?? null);
        if ($icons !== []) {
            $data['page_icons'] = $icons;
        } elseif (array_key_exists('page_icons', $data)) {
            unset($data['page_icons']);
        }

        if ($pageKey === 'beranda') {
            if (isset($data['nav']) && is_array($data['nav'])) {
                foreach ($data['nav'] as $i => $item) {
                    if (is_array($item)) {
                        $data['nav'][$i]['route'] = PublicCmsUrl::normalizeNavPathForStorage($item['route'] ?? '');
                    }
                }
            }
            if (isset($data['footer_quick_links']) && is_array($data['footer_quick_links'])) {
                foreach ($data['footer_quick_links'] as $i => $item) {
                    if (is_array($item)) {
                        $data['footer_quick_links'][$i]['route'] = PublicCmsUrl::normalizeNavPathForStorage($item['route'] ?? '');
                    }
                }
            }
            if (isset($data['footer_social_links']) && is_array($data['footer_social_links'])) {
                $data['footer_social_links'] = array_values(array_filter(
                    $data['footer_social_links'],
                    static fn ($item) => is_array($item) && trim((string) ($item['url'] ?? '')) !== ''
                ));
            }
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function resolveBerandaFooterSocialLinks(string $pageKey, array $data, ?array $stored): array
    {
        if ($pageKey !== 'beranda') {
            return $data;
        }

        $hasStoredSocial = is_array($stored) && array_key_exists('footer_social_links', $stored);
        $links = $data['footer_social_links'] ?? null;
        if ($hasStoredSocial && is_array($links)) {
            return $data;
        }

        $fromSettings = self::footerSocialLinksFromSiteSettings();
        if ($fromSettings !== []) {
            $data['footer_social_links'] = $fromSettings;
        }

        return $data;
    }

    /**
     * @return list<array{url: string, icon: string, label: string}>
     */
    private static function footerSocialLinksFromSiteSettings(): array
    {
        $map = [
            ['key' => 'social_facebook', 'icon' => 'fa-brands fa-facebook-f', 'label' => 'Facebook'],
            ['key' => 'social_twitter', 'icon' => 'fa-brands fa-x-twitter', 'label' => 'X'],
            ['key' => 'social_instagram', 'icon' => 'fa-brands fa-instagram', 'label' => 'Instagram'],
            ['key' => 'social_youtube', 'icon' => 'fa-brands fa-youtube', 'label' => 'YouTube'],
        ];

        $links = [];
        foreach ($map as $row) {
            try {
                $url = trim((string) SiteSetting::get($row['key'], ''));
            } catch (Throwable) {
                $url = '';
            }
            if ($url === '' || $url === '#') {
                continue;
            }
            $links[] = [
                'url' => $url,
                'icon' => $row['icon'],
                'label' => $row['label'],
            ];
        }

        return $links;
    }
}
