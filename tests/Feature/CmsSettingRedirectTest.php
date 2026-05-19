<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsSettingRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_setting_page_key_tanpa_edit_mengarah_ke_halaman_edit(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin)
            ->get('/dashboard/setting/pendaftaran')
            ->assertRedirect('/dashboard/setting/pendaftaran/edit');

        $this->actingAs($superAdmin)
            ->get('/dashboard/setting/pendaftaran/edit')
            ->assertOk();
    }

    public function test_url_lama_halaman_mengarah_ke_setting_edit(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin)
            ->get('/dashboard/halaman/pendaftaran')
            ->assertRedirect('/dashboard/setting/pendaftaran/edit');
    }
}
