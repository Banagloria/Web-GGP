<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Throwable;

class EnsurePhoneColumnCommand extends Command
{
    protected $signature = 'church:ensure-phone-column';

    protected $description = 'Menambahkan kolom phone ke tabel users jika belum ada.';

    public function handle(): int
    {
        try {
            if (! Schema::hasTable('users')) {
                $this->components->error('Tabel users tidak ditemukan.');

                return self::FAILURE;
            }

            if (Schema::hasColumn('users', 'phone')) {
                $this->components->info('Kolom phone sudah ada.');

                return self::SUCCESS;
            }

            Schema::table('users', function (Blueprint $table) {
                $table->string('phone', 50)->nullable()->after('email');
            });

            $this->components->info('Kolom phone berhasil ditambahkan.');

            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->components->error('Gagal: '.$e->getMessage());

            return self::FAILURE;
        }
    }
}
