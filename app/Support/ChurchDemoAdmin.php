<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Akun demo panel: dipakai seeder + pemulihan login terbatas (lihat shouldAutoProvision).
 */
final class ChurchDemoAdmin
{
    public const DEMO_EMAIL = 'admin@gmail.com';

    public const DEMO_PASSWORD = 'admin123';

    public static function shouldAutoProvision(): bool
    {
        if (app()->isProduction()) {
            return filter_var(env('CHURCH_DEMO_LOGIN_RECOVERY', false), FILTER_VALIDATE_BOOL);
        }

        // staging, local, development, testing, dsb.: bantu setup tanpa seed manual
        return true;
    }

    /**
     * @param  array{email: string, password: string}  $credentials
     */
    public static function credentialsMatchDemo(array $credentials): bool
    {
        return ($credentials['email'] ?? '') === self::DEMO_EMAIL
            && ($credentials['password'] ?? '') === self::DEMO_PASSWORD;
    }

    public static function provision(): void
    {
        User::query()->updateOrCreate(
            ['email' => self::DEMO_EMAIL],
            [
                'name' => 'Admin Gereja',
                'password' => Hash::make(self::DEMO_PASSWORD),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
