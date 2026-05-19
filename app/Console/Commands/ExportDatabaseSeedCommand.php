<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class ExportDatabaseSeedCommand extends Command
{
    protected $signature = 'church:export-seed
                            {--output=DatabaseBackupSeeder.php : Nama file seeder di database/seeders/}';

    protected $description = 'Ekspor data aplikasi saat ini ke file seeder (backup via seed)';

    /** @var list<string> Urutan insert; tabel runtime (sessions, jobs, cache) diabaikan. */
    private const TABLES = [
        'users',
        'site_settings',
        'pages',
        'cms_page_contents',
        'worship_schedules',
        'announcements',
        'congregation_registrations',
        'baptism_registrations',
        'marriage_registrations',
        'registration_submissions',
        'contacts',
        'gallery_items',
        'whatsapp_waha_configs',
        'whatsapp_message_templates',
        'whatsapp_notification_recipients',
        'whatsapp_notification_recipient_triggers',
        'whatsapp_broadcast_templates',
        'whatsapp_broadcast_template_users',
    ];

    public function handle(): int
    {
        $filename = (string) $this->option('output');
        if (! Str::endsWith($filename, '.php')) {
            $filename .= '.php';
        }

        $path = database_path('seeders/'.$filename);

        try {
            DB::connection()->getPdo();
        } catch (Throwable $e) {
            $this->error('Database tidak terhubung: '.$e->getMessage());

            return self::FAILURE;
        }

        $payload = [];
        $totalRows = 0;

        foreach (self::TABLES as $table) {
            $rows = DB::table($table)->orderBy('id')->get()->map(fn ($row) => (array) $row)->all();
            $payload[$table] = $rows;
            $count = count($rows);
            $totalRows += $count;
            $this->line(sprintf('  %-30s %d baris', $table, $count));
        }

        $exportedAt = now()->timezone(config('app.timezone', 'UTC'))->format('Y-m-d H:i:s T');
        $className = Str::studly(pathinfo($filename, PATHINFO_FILENAME));

        $content = $this->buildSeederSource($className, $payload, $exportedAt);

        if (file_put_contents($path, $content) === false) {
            $this->error('Gagal menulis: '.$path);

            return self::FAILURE;
        }

        $this->newLine();
        $this->info("Backup seeder ditulis: {$path}");
        $this->line("Total: {$totalRows} baris dari ".count(self::TABLES).' tabel');
        $this->comment('Restore: php artisan db:seed --class='.$className);

        return self::SUCCESS;
    }

    /**
     * @param  array<string, list<array<string, mixed>>>  $payload
     */
    private function buildSeederSource(string $className, array $payload, string $exportedAt): string
    {
        $dataExport = var_export($payload, true);

        return <<<PHP
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Backup database otomatis — dihasilkan oleh: php artisan church:export-seed
 * Diekspor: {$exportedAt}
 *
 * Restore ke database kosong (setelah migrate):
 *   php artisan db:seed --class={$className}
 *
 * Atau tambahkan ke DatabaseSeeder::run() jika ingin selalu memakai backup ini.
 */
class {$className} extends Seeder
{
    /** @var array<string, list<array<string, mixed>>> */
    private const DATA = {$dataExport};

    /** @var list<string> */
    private const TABLES = [
        'whatsapp_broadcast_template_users',
        'whatsapp_broadcast_templates',
        'whatsapp_notification_recipient_triggers',
        'whatsapp_notification_recipients',
        'whatsapp_message_templates',
        'whatsapp_waha_configs',
        'gallery_items',
        'contacts',
        'registration_submissions',
        'marriage_registrations',
        'baptism_registrations',
        'congregation_registrations',
        'announcements',
        'worship_schedules',
        'cms_page_contents',
        'pages',
        'site_settings',
        'users',
    ];

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach (self::TABLES as \$table) {
            DB::table(\$table)->truncate();
        }

        foreach (self::DATA as \$table => \$rows) {
            foreach (array_chunk(\$rows, 100) as \$chunk) {
                if (\$chunk !== []) {
                    DB::table(\$table)->insert(\$chunk);
                }
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}

PHP;
    }
}
