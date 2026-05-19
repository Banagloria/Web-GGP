<?php

namespace App\Support;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Throwable;

/**
 * Penyesuaian runtime agar deploy tidak HTTP 500 (driver DB tanpa tabel, Blade tidak bisa menulis storage).
 */
final class DeploySafeRuntime
{
    public static function ensureUserPhoneColumnIfNeeded(): void
    {
        try {
            if (! Schema::hasTable('users') || Schema::hasColumn('users', 'phone')) {
                return;
            }

            Schema::table('users', function (Blueprint $table) {
                $table->string('phone', 50)->nullable()->after('email');
            });
        } catch (Throwable) {
            // Biarkan controller menangani jika migrasi otomatis gagal.
        }
    }

    public static function relaxDatabaseDriversIfNeeded(): void
    {
        try {
            if (config('session.driver') === 'database') {
                if (! Schema::hasTable(config('session.table', 'sessions'))) {
                    config(['session.driver' => 'file']);
                }
            }
        } catch (Throwable) {
            config(['session.driver' => 'file']);
        }

        try {
            if (config('cache.default') === 'database' && ! Schema::hasTable('cache')) {
                config(['cache.default' => 'file']);
            }
        } catch (Throwable) {
            config(['cache.default' => 'file']);
        }

        try {
            if (config('queue.default') === 'database' && ! Schema::hasTable('jobs')) {
                config(['queue.default' => 'sync']);
            }
        } catch (Throwable) {
            config(['queue.default' => 'sync']);
        }
    }

    /**
     * Pastikan path kompilasi Blade valid dan bisa ditulis (hindari realpath=false atau permission buruk).
     */
    public static function ensureBladeCompiledPathWritable(): void
    {
        $compiled = config('view.compiled');
        if (! is_string($compiled) || $compiled === '') {
            $compiled = storage_path('framework/views');
        }

        if (! is_dir($compiled)) {
            @mkdir($compiled, 0775, true);
        }

        if (is_dir($compiled) && is_writable($compiled)) {
            config(['view.compiled' => $compiled]);

            return;
        }

        $fallback = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR)
            .DIRECTORY_SEPARATOR
            .'laravel-views-'
            .md5((string) base_path());

        if (! is_dir($fallback)) {
            @mkdir($fallback, 0775, true);
        }

        if (is_dir($fallback) && is_writable($fallback)) {
            config(['view.compiled' => $fallback]);
        }
    }
}
