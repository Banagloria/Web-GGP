<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Throwable;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    /** @var list<string> */
    public const ADMIN_FORM_KEYS = [
        'church_name_line1',
        'church_name_line2',
        'site_logo_url',
        'church_phone',
        'church_email',
        'church_address',
        'footer_whatsapp_note',
        'social_facebook',
        'social_twitter',
        'social_instagram',
        'social_youtube',
        'hero_image_url',
        'hero_script_top',
        'hero_title_gold',
        'hero_title_white',
        'hero_script_bottom',
        'vision_title',
        'vision_body',
    ];

    /**
     * @return array<string, string|null>
     */
    public static function valuesForAdminForm(): array
    {
        $settings = [];
        foreach (self::ADMIN_FORM_KEYS as $key) {
            $settings[$key] = self::get($key, '');
        }

        return $settings;
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        try {
            return static::query()->where('key', $key)->value('value') ?? $default;
        } catch (Throwable) {
            return $default;
        }
    }

    public static function put(string $key, ?string $value): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
