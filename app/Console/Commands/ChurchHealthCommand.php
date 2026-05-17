<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

class ChurchHealthCommand extends Command
{
    protected $signature = 'church:health';

    protected $description = 'Cek koneksi DB dan tabel penting (diagnosa error 500)';

    public function handle(): int
    {
        $this->info('=== Gereja — cek kesehatan ===');

        try {
            DB::connection()->getPdo();
            $this->line('Database: OK (koneksi)');
        } catch (Throwable $e) {
            $this->error('Database: GAGAL — '.$e->getMessage());

            return self::FAILURE;
        }

        foreach (['migrations', 'sessions', 'users', 'site_settings', 'pages', 'gallery_items', 'cms_page_contents'] as $table) {
            try {
                $ok = Schema::hasTable($table);
                $this->line(sprintf('Tabel %-20s %s', $table, $ok ? 'ada' : 'BELUM ADA'));
            } catch (Throwable $e) {
                $this->error(sprintf('Tabel %-20s ERROR %s', $table, $e->getMessage()));

                return self::FAILURE;
            }
        }

        $this->newLine();
        $this->line('PHP intl: '.(extension_loaded('intl') ? 'OK (hindari error Number::format di beberapa fitur)' : 'BELUM ADA — sudo apt install php'.PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION.'-intl'));

        $manifest = public_path('build/manifest.json');
        if (is_file($manifest) && ! \App\Support\ViteAssets::manifestIsUsable()) {
            $this->warn('public/build/manifest.json ada tapi tidak valid/kosong — hapus berkas ini atau jalankan npm run build; bisa memicu HTTP 500 lewat @vite.');
        }

        $this->newLine();
        $base = base_path();
        foreach ([
            'storage/framework/views' => 'Blade (kompilasi view)',
            'storage/framework/sessions' => 'Sesi file',
            'storage/logs' => 'Log',
            'bootstrap/cache' => 'Cache bootstrap',
        ] as $rel => $label) {
            $path = $base.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $rel);
            $ok = is_dir($path) && is_writable($path);
            $this->line(sprintf('Tulis %-28s %s', $label, $ok ? 'OK' : 'GAGAL (chmod/chown)'));
            if (! $ok) {
                $this->warn('  → '.$path);
            }
        }

        $this->newLine();
        $this->comment('Jika ada tabel BELUM ADA: php artisan migrate --force');
        $this->comment('Darurat tanpa migrasi sesi: di .env set SESSION_DRIVER=file lalu php artisan config:clear');
        $this->comment('Atau: touch storage/framework/force-file-session (lihat komentar di public/index.php)');

        return self::SUCCESS;
    }
}
