<?php

namespace App\Support;

/**
 * Ikon CMS: selalu kelas Font Awesome (entity HTML lama dikonversi otomatis).
 */
final class CmsIcon
{
    /** @var array<string, string> */
    private const LEGACY_TO_FA = [
        '&#10013;' => 'fa-solid fa-cross',
        '†' => 'fa-solid fa-cross',
        '&#8226;' => 'fa-solid fa-circle',
        '•' => 'fa-solid fa-circle',
        '&#128226;' => 'fa-solid fa-bullhorn',
        '📢' => 'fa-solid fa-bullhorn',
        '&#128444;' => 'fa-solid fa-images',
        '🖼' => 'fa-solid fa-images',
        '&#128203;' => 'fa-solid fa-clipboard-list',
        '📋' => 'fa-solid fa-clipboard-list',
        '&#128100;' => 'fa-solid fa-user',
        '👤' => 'fa-solid fa-user',
        '&#128146;' => 'fa-solid fa-droplet',
        '💧' => 'fa-solid fa-droplet',
        '&#128141;' => 'fa-solid fa-ring',
        '💍' => 'fa-solid fa-ring',
    ];

    /** @var array<int, string> */
    private const CODEPOINT_TO_FA = [
        10013 => 'fa-solid fa-cross',
        8226 => 'fa-solid fa-circle',
        128226 => 'fa-solid fa-bullhorn',
        128444 => 'fa-solid fa-images',
        128203 => 'fa-solid fa-clipboard-list',
        128100 => 'fa-solid fa-user',
        128146 => 'fa-solid fa-droplet',
        128141 => 'fa-solid fa-ring',
    ];

    public static function isFontAwesome(?string $value): bool
    {
        $v = trim((string) ($value ?? ''));
        if ($v === '') {
            return false;
        }

        return (bool) preg_match(
            '/\b(fa-solid|fa-regular|fa-brands|fa-light|fa-thin|fa-duotone|fas|far|fab|fal|fat|fad)\b/i',
            $v
        ) || (bool) preg_match('/\bfa-[a-z0-9-]+\b/i', $v);
    }

    public static function isLegacyHtml(?string $value): bool
    {
        $v = trim((string) ($value ?? ''));

        if ($v === '' || self::isFontAwesome($v)) {
            return false;
        }

        if (isset(self::LEGACY_TO_FA[$v])) {
            return true;
        }

        if (preg_match('/^&#\d+;$/', $v) || preg_match('/^&#x[0-9a-f]+;$/i', $v)) {
            return true;
        }

        $decoded = html_entity_decode($v, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return $decoded !== $v && (isset(self::LEGACY_TO_FA[$decoded]) || mb_strlen($decoded) === 1);
    }

    public static function normalizedClasses(?string $value): string
    {
        $v = trim((string) ($value ?? ''));
        $v = preg_replace('/\bfas\b/i', 'fa-solid', $v) ?? $v;
        $v = preg_replace('/\bfar\b/i', 'fa-regular', $v) ?? $v;
        $v = preg_replace('/\bfab\b/i', 'fa-brands', $v) ?? $v;

        return trim(preg_replace('/\s+/', ' ', $v) ?? $v);
    }

    /**
     * Ubah entity HTML / emoji lama menjadi kelas FA; sudah FA tetap dinormalisasi.
     */
    public static function toFontAwesome(?string $value, string $defaultFa = 'fa-solid fa-circle'): string
    {
        $v = trim((string) ($value ?? ''));
        if ($v === '') {
            return $defaultFa;
        }

        if (self::isFontAwesome($v)) {
            return self::normalizedClasses($v);
        }

        if (isset(self::LEGACY_TO_FA[$v])) {
            return self::LEGACY_TO_FA[$v];
        }

        $decoded = html_entity_decode($v, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        if ($decoded !== $v && isset(self::LEGACY_TO_FA[$decoded])) {
            return self::LEGACY_TO_FA[$decoded];
        }

        if (preg_match('/^&#(\d+);$/', $v, $m)) {
            $code = (int) $m[1];

            return self::CODEPOINT_TO_FA[$code] ?? $defaultFa;
        }

        if (preg_match('/^&#x([0-9a-f]+);$/i', $v, $m)) {
            $code = (int) hexdec($m[1]);

            return self::CODEPOINT_TO_FA[$code] ?? $defaultFa;
        }

        if ($decoded !== $v && mb_strlen($decoded) === 1) {
            $code = self::unicodeCodepoint($decoded);

            return self::CODEPOINT_TO_FA[$code] ?? $defaultFa;
        }

        if (self::isLegacyHtml($v)) {
            return $defaultFa;
        }

        return $defaultFa;
    }

    public static function displayClasses(?string $value, string $defaultFa): string
    {
        return self::toFontAwesome($value, $defaultFa);
    }

    public static function heroButtonIconDefault(string $style): string
    {
        return match ($style) {
            'primary' => 'fa-solid fa-hands-praying',
            'secondary' => 'fa-solid fa-calendar-days',
            default => 'fa-solid fa-envelope-open-text',
        };
    }

    /**
     * Ikon kartu tautan (Jelajahi, Pendaftaran, dll.): kosong / bullet / atau lingkaran placeholder CMS → ikon dari URL.
     */
    public static function linkedCardIconClasses(?string $iconValue, string $url): string
    {
        $fallback = PublicNavIcon::forRouteRaw($url);
        $resolved = self::toFontAwesome(trim((string) ($iconValue ?? '')), $fallback);

        return self::replacePlaceholderCircleWithFallback($resolved, $fallback);
    }

    /**
     * Lingkaran solid/outline dipakai sebagai default CMS lama — bukan pilihan desain nyata untuk kartu tautan.
     */
    private static function replacePlaceholderCircleWithFallback(string $resolved, string $fallback): string
    {
        $n = strtolower(self::normalizedClasses($resolved));

        return match ($n) {
            'fa-solid fa-circle', 'fa-regular fa-circle' => $fallback,
            default => self::normalizedClasses($resolved),
        };
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function normalizePageData(string $pageKey, array $data): array
    {
        if ($pageKey === 'beranda') {
            if (array_key_exists('vision_icon', $data)) {
                $data['vision_icon'] = self::toFontAwesome($data['vision_icon'], 'fa-solid fa-cross');
            }
            if (isset($data['hero_buttons']) && is_array($data['hero_buttons'])) {
                $pageIcons = is_array($data['page_icons'] ?? null) ? $data['page_icons'] : [];
                foreach ($data['hero_buttons'] as $i => $btn) {
                    if (! is_array($btn)) {
                        continue;
                    }
                    $style = $btn['style'] ?? 'primary';
                    $defaultFa = self::heroButtonIconDefault($style);
                    $legacyKey = match ($style) {
                        'primary' => 'hero_btn_primary',
                        'secondary' => 'hero_btn_secondary',
                        default => 'hero_btn_link',
                    };
                    $raw = trim((string) ($btn['icon'] ?? ''));
                    if ($raw === '' && isset($pageIcons[$legacyKey])) {
                        $legacy = trim((string) $pageIcons[$legacyKey]);
                        if ($legacy !== '') {
                            $raw = $legacy;
                        }
                    }
                    $data['hero_buttons'][$i]['icon'] = self::toFontAwesome($raw, $defaultFa);
                }
            }
            if (isset($data['sidebar_cards']) && is_array($data['sidebar_cards'])) {
                foreach ($data['sidebar_cards'] as $i => $card) {
                    if (! is_array($card)) {
                        continue;
                    }
                    $data['sidebar_cards'][$i]['icon'] = self::linkedCardIconClasses(
                        $card['icon'] ?? null,
                        (string) ($card['url'] ?? '')
                    );
                }
            }
            if (isset($data['nav']) && is_array($data['nav'])) {
                foreach ($data['nav'] as $i => $item) {
                    if (! is_array($item)) {
                        continue;
                    }
                    $data['nav'][$i]['icon'] = self::linkedCardIconClasses(
                        $item['icon'] ?? null,
                        (string) ($item['route'] ?? '')
                    );
                }
            }
        }

        if ($pageKey === 'pendaftaran' && isset($data['cards']) && is_array($data['cards'])) {
            $pageIcons = is_array($data['page_icons'] ?? null) ? $data['page_icons'] : [];
            $fallbackArrow = self::toFontAwesome(
                (string) ($pageIcons['index_card_arrow'] ?? ''),
                'fa-solid fa-arrow-right'
            );
            foreach ($data['cards'] as $i => $card) {
                if (! is_array($card)) {
                    continue;
                }
                $data['cards'][$i]['icon'] = self::linkedCardIconClasses(
                    $card['icon'] ?? null,
                    (string) ($card['url'] ?? '')
                );
                $data['cards'][$i]['arrow_icon'] = self::toFontAwesome(
                    (string) ($card['arrow_icon'] ?? ''),
                    $fallbackArrow
                );
            }
        }

        return $data;
    }

    private static function unicodeCodepoint(string $char): int
    {
        if (function_exists('mb_ord')) {
            return mb_ord($char, 'UTF-8');
        }

        $u = @unpack('N', mb_convert_encoding($char, 'UCS-4BE', 'UTF-8'));

        return is_array($u) ? ($u[1] ?? 0) : 0;
    }
}
