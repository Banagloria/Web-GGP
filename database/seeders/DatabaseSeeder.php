<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Backup data produksi (hasil: php artisan church:export-seed)
        $this->call(DatabaseBackupSeeder::class);

        // Data demo/dummy — nonaktifkan baris di atas jika ingin pakai ini:
        // $this->call(ChurchSeeder::class);
    }
}
