<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardRouteMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_modul_admin_mengarah_ke_rute_dashboard_yang_benar(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/dashboard/halaman/pengumuman')
            ->assertRedirect('/dashboard/pengumuman');

        $this->actingAs($admin)
            ->get('/dashboard/pengumuman')
            ->assertOk();
    }

    public function test_simpan_pengaturan_menerima_post_tanpa_method_spoof(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin)
            ->post('/dashboard/pengaturan', [
                'church_name_line1' => 'BARIS 1',
                'church_name_line2' => 'Baris 2',
                'church_phone' => '08123456789',
                'church_email' => 'admin@example.test',
                'church_address' => 'Alamat gereja',
            ])
            ->assertRedirect(route('dashboard.setting.index'))
            ->assertSessionHas('status');
    }
}
