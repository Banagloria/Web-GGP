<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Throwable;

class EnsureProfilePhotoColumnCommand extends Command
{
    protected $signature = 'church:ensure-profile-photo-column';

    protected $description = 'Menambahkan kolom profile_photo_url ke tabel users jika belum ada.';

    public function handle(): int
    {
        try {
            if (! Schema::hasTable('users')) {
                $this->components->error('Tabel users tidak ditemukan.');

                return self::FAILURE;
            }

            if (Schema::hasColumn('users', 'profile_photo_url')) {
                $this->components->info('Kolom profile_photo_url sudah ada.');

                return self::SUCCESS;
            }

            Schema::table('users', function (Blueprint $table) {
                $table->string('profile_photo_url', 2000)->nullable()->after('email');
            });

            $this->components->info('Kolom profile_photo_url berhasil ditambahkan.');

            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->components->error('Gagal: '.$e->getMessage());

            return self::FAILURE;
        }
    }
}
