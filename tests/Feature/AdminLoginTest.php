<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_login_dapat_ditampilkan(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
        $response->assertSee('Masuk admin', false);
    }

    public function test_admin_dapat_masuk_dan_diarahkan_ke_dashboard(): void
    {
        $user = User::factory()->admin()->create([
            'email' => 'pengurus@example.test',
            'password' => 'rahasia-aman',
        ]);

        $response = $this->post('/login', [
            'email' => 'pengurus@example.test',
            'password' => 'rahasia-aman',
        ]);

        $response->assertRedirect(route('dashboard.index', absolute: false));
        $this->assertAuthenticatedAs($user);
    }

    public function test_bukan_admin_tidak_boleh_masuk_panel(): void
    {
        User::factory()->create([
            'email' => 'jemaat@example.test',
            'password' => 'sandi-jemaat',
            'role' => 'user',
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'jemaat@example.test',
            'password' => 'sandi-jemaat',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
