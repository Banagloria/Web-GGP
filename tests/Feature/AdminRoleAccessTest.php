<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_dapat_mengakses_halaman_dan_akun(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin)
            ->get(route('dashboard.halaman.index'))
            ->assertOk();

        $this->actingAs($superAdmin)
            ->get(route('dashboard.akun.index'))
            ->assertOk();
    }

    public function test_admin_biasa_tidak_dapat_mengakses_halaman_dan_akun(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('dashboard.halaman.index'))
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('dashboard.akun.index'))
            ->assertForbidden();
    }

    public function test_admin_biasa_tetap_dapat_mengakses_dashboard_lain(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('dashboard.index'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('dashboard.profil-akun.edit'))
            ->assertOk();
    }

    public function test_admin_dan_super_admin_dapat_login_panel(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'admin@example.test',
            'password' => 'sandi-aman',
        ]);

        $this->post('/login', [
            'email' => 'admin@example.test',
            'password' => 'sandi-aman',
        ])->assertRedirect(route('dashboard.index', absolute: false));

        $this->assertAuthenticatedAs($admin);

        auth()->logout();

        $superAdmin = User::factory()->superAdmin()->create([
            'email' => 'super@example.test',
            'password' => 'sandi-aman',
        ]);

        $this->post('/login', [
            'email' => 'super@example.test',
            'password' => 'sandi-aman',
        ])->assertRedirect(route('dashboard.index', absolute: false));

        $this->assertAuthenticatedAs($superAdmin);
    }
}
