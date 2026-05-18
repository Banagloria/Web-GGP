<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dapat_membuka_halaman_profil(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('dashboard.profil-akun.edit'));

        $response->assertOk();
        $response->assertSee('Profil akun', false);
    }

    public function test_admin_dapat_memperbarui_nama_profil(): void
    {
        $admin = User::factory()->admin()->create([
            'name' => 'Admin Lama',
            'password' => 'rahasia-lama',
        ]);

        $response = $this->actingAs($admin)->put(route('dashboard.profil-akun.update'), [
            'name' => 'Admin Baru',
        ]);

        $response->assertRedirect(route('dashboard.profil-akun.edit'));
        $response->assertSessionHas('status');
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'name' => 'Admin Baru',
        ]);
    }

    public function test_update_password_gagal_tanpa_kata_sandi_saat_ini(): void
    {
        $admin = User::factory()->admin()->create([
            'password' => 'rahasia-lama',
        ]);

        $response = $this->from(route('dashboard.profil-akun.edit'))
            ->actingAs($admin)
            ->put(route('dashboard.profil-akun.update'), [
                'name' => $admin->name,
                'password' => 'rahasia-baru',
                'password_confirmation' => 'rahasia-baru',
            ]);

        $response->assertRedirect(route('dashboard.profil-akun.edit'));
        $response->assertSessionHasErrors('current_password');
    }

    public function test_admin_dapat_mengunggah_gambar_profil(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->put(route('dashboard.profil-akun.update'), [
            'name' => $admin->name,
            'profile_photo_file' => UploadedFile::fake()->create('profil.jpg', 100, 'image/jpeg'),
            'profile_photo_url_previous' => '',
        ]);

        $response->assertRedirect(route('dashboard.profil-akun.edit'));
        $admin->refresh();
        $this->assertNotNull($admin->profile_photo_url);
        $this->assertStringContainsString('/storage/', (string) $admin->profile_photo_url);
    }

    public function test_super_admin_dapat_mengelola_akun_pengurus_lain(): void
    {
        $admin = User::factory()->superAdmin()->create();
        $other = User::factory()->admin()->create([
            'email' => 'lain@example.test',
        ]);

        $this->actingAs($admin)
            ->get(route('dashboard.akun.index'))
            ->assertOk()
            ->assertSee($other->email, false);

        $this->actingAs($admin)
            ->get(route('dashboard.akun.create'))
            ->assertOk();

        $createResponse = $this->actingAs($admin)->post(route('dashboard.akun.store'), [
            'name' => 'Admin Ketiga',
            'email' => 'ketiga@example.test',
            'role' => User::ROLE_ADMIN,
            'password' => 'sandi-admin',
            'password_confirmation' => 'sandi-admin',
        ]);

        $createResponse->assertRedirect(route('dashboard.akun.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'ketiga@example.test',
            'role' => 'admin',
        ]);

        $created = User::query()->where('email', 'ketiga@example.test')->first();
        $this->assertNotNull($created);

        $this->actingAs($admin)
            ->put(route('dashboard.akun.update', $created), [
                'name' => 'Admin Ketiga Diubah',
                'email' => 'ketiga@example.test',
                'role' => User::ROLE_ADMIN,
            ])
            ->assertRedirect(route('dashboard.akun.index'));

        $this->assertDatabaseHas('users', [
            'id' => $created->id,
            'name' => 'Admin Ketiga Diubah',
        ]);
    }

    public function test_tidak_bisa_menghapus_diri_sendiri(): void
    {
        $admin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($admin)->delete(route('dashboard.akun.destroy', $admin));

        $response->assertRedirect(route('dashboard.akun.index'));
        $response->assertSessionHasErrors('akun');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_tidak_bisa_menghapus_super_admin_terakhir(): void
    {
        $admin = User::factory()->superAdmin()->create();
        $other = User::factory()->admin()->create();

        $other->delete();

        $response = $this->actingAs($admin)->delete(route('dashboard.akun.destroy', $admin));

        $response->assertRedirect(route('dashboard.akun.index'));
        $response->assertSessionHasErrors('akun');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_guest_dialihkan_ke_login(): void
    {
        $this->get(route('dashboard.profil-akun.edit'))->assertRedirect(route('login'));
        $this->get(route('dashboard.akun.index'))->assertRedirect(route('login'));
    }

    public function test_bukan_admin_tidak_bisa_akses_halaman_akun(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('dashboard.profil-akun.edit'))
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('dashboard.akun.index'))
            ->assertForbidden();
    }
}
