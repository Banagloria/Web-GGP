<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

/**
 * Memulihkan akses login ketika database belum berisi user dari seeder
 * atau sandi admin tidak diketahui.
 */
class EnsureChurchAdminCommand extends Command
{
    protected $signature = 'church:ensure-admin
                            {--email=admin@gmail.com : Alamat email admin}
                            {--password=admin123 : Sandi (ganti setelah berhasil login)}';

    protected $description = 'Membuat atau memperbarui satu akun admin panel (email + sandi + role admin).';

    public function handle(): int
    {
        $email = (string) $this->option('email');
        $password = (string) $this->option('password');

        if (strlen($password) < 8) {
            $this->components->error('Sandi minimal 8 karakter (validasi Laravel). Contoh: admin12345');

            return self::FAILURE;
        }

        User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Admin Gereja',
                'password' => $password,
                'role' => User::ROLE_SUPER_ADMIN,
                'email_verified_at' => now(),
            ],
        );

        $this->components->info("Akun admin siap: {$email}");
        $this->components->warn('Segera ubah sandi setelah login jika perintah ini dijalankan di server produksi.');

        return self::SUCCESS;
    }
}
