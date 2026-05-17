<?php

namespace App\Console\Commands;

use App\Models\CmsPageContent;
use App\Services\CmsPageService;
use App\Support\CmsPublicPageDefaults;
use Illuminate\Console\Command;
use Throwable;

class MigrateCmsIconsCommand extends Command
{
    protected $signature = 'cms:migrate-icons';

    protected $description = 'Konversi ikon entity HTML lama ke kelas Font Awesome di database CMS';

    public function handle(): int
    {
        $keys = ['beranda', 'pendaftaran'];

        foreach ($keys as $pageKey) {
            try {
                $stored = CmsPageContent::dataFor($pageKey);
            } catch (Throwable) {
                $this->warn("Lewati {$pageKey}: database tidak tersedia.");

                continue;
            }

            if (! is_array($stored)) {
                $this->line("{$pageKey}: belum ada data tersimpan.");

                continue;
            }

            $base = CmsPublicPageDefaults::defaultsFor($pageKey);
            $merged = array_replace_recursive($base, $stored);
            CmsPageService::save($pageKey, $merged);
            $this->info("{$pageKey}: ikon diperbarui ke Font Awesome.");
        }

        $this->info('Selesai.');

        return self::SUCCESS;
    }
}
