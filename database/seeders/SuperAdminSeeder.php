<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public const EMAIL = 'superadmin@gmail.com';

    public const PASSWORD = 'superadmin123';

    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => self::EMAIL],
            [
                'name' => 'Super Admin',
                'password' => Hash::make(self::PASSWORD),
                'role' => User::ROLE_SUPER_ADMIN,
                'email_verified_at' => now(),
            ]
        );
    }
}
