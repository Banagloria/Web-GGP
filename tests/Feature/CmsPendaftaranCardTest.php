<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\CmsPageService;
use App\Support\PublicCmsUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsPendaftaranCardTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_dapat_menambah_kartu_pendaftaran_baru(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $defaults = CmsPageService::merged('pendaftaran');

        $payload = [
            'breadcrumb_home' => $defaults['breadcrumb_home'],
            'breadcrumb_current' => $defaults['breadcrumb_current'],
            'h1' => $defaults['h1'],
            'cards' => array_merge($defaults['cards'], [
                [
                    'key' => 'c_test_baru',
                    'icon' => 'fa-solid fa-id-card',
                    'arrow_icon' => 'fa-solid fa-arrow-right',
                    'title' => 'Sidik jari',
                    'description' => 'Pendaftaran sidik jari jemaat.',
                    'cta_label' => 'Isi formulir',
                    'url' => 'sidik-jari',
                ],
            ]),
        ];

        $this->actingAs($superAdmin)
            ->put(route('dashboard.setting.cms.update', 'pendaftaran'), $payload)
            ->assertRedirect(route('dashboard.setting.cms.edit', 'pendaftaran'))
            ->assertSessionHas('status');

        $saved = CmsPageService::merged('pendaftaran');
        $slugs = array_map(
            static fn (array $card) => PublicCmsUrl::formatPendaftaranCardSlugForInput($card['url'] ?? ''),
            $saved['cards'] ?? []
        );

        $this->assertContains('sidik-jari', $slugs);
    }

    public function test_simpan_menerima_url_kartu_format_lama_dengan_path_penuh(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $defaults = CmsPageService::merged('pendaftaran');

        $this->actingAs($superAdmin)
            ->put(route('dashboard.setting.cms.update', 'pendaftaran'), [
                'breadcrumb_home' => $defaults['breadcrumb_home'],
                'breadcrumb_current' => $defaults['breadcrumb_current'],
                'h1' => $defaults['h1'],
                'cards' => $defaults['cards'],
            ])
            ->assertRedirect(route('dashboard.setting.cms.edit', 'pendaftaran'))
            ->assertSessionHas('status');
    }

    public function test_setelah_validasi_gagal_kartu_yang_dikirim_tetap_ditampilkan(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $defaults = CmsPageService::merged('pendaftaran');

        $cards = $defaults['cards'];
        $cards[] = [
            'key' => 'c_baru',
            'icon' => 'fa-solid fa-id-card',
            'arrow_icon' => 'fa-solid fa-arrow-right',
            'title' => 'Kartu baru',
            'description' => '',
            'cta_label' => '',
            'url' => 'kartu-baru',
        ];

        $response = $this->actingAs($superAdmin)
            ->from(route('dashboard.setting.cms.edit', 'pendaftaran'))
            ->put(route('dashboard.setting.cms.update', 'pendaftaran'), [
                'breadcrumb_home' => $defaults['breadcrumb_home'],
                'breadcrumb_current' => $defaults['breadcrumb_current'],
                'h1' => $defaults['h1'],
                'cards' => $cards,
            ]);

        $response
            ->assertRedirect(route('dashboard.setting.cms.edit', 'pendaftaran'))
            ->assertSessionHasErrors('cards.3.cta_label');

        $response = $this->actingAs($superAdmin)
            ->get(route('dashboard.setting.cms.edit', 'pendaftaran'));

        $response
            ->assertOk()
            ->assertSee('name="cards[3][title]"', false)
            ->assertSee('value="Kartu baru"', false)
            ->assertSee('data-cms-pendaftaran-cards-add', false);
    }

    public function test_simpan_tidak_menghapus_kartu_lama_jika_post_hanya_mengirim_sebagian(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $defaults = CmsPageService::merged('pendaftaran');
        $firstCard = $defaults['cards'][0];

        $this->actingAs($superAdmin)
            ->put(route('dashboard.setting.cms.update', 'pendaftaran'), [
                'breadcrumb_home' => $defaults['breadcrumb_home'],
                'breadcrumb_current' => $defaults['breadcrumb_current'],
                'h1' => $defaults['h1'],
                'cards' => [$firstCard],
            ])
            ->assertRedirect(route('dashboard.setting.cms.edit', 'pendaftaran'))
            ->assertSessionHas('status');

        $saved = CmsPageService::merged('pendaftaran');
        $this->assertCount(count($defaults['cards']), $saved['cards'] ?? []);
    }

    public function test_hapus_kartu_dari_form_tetap_menghapus_kartu_saat_disimpan(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $defaults = CmsPageService::merged('pendaftaran');
        $remaining = [$defaults['cards'][0]];

        $this->actingAs($superAdmin)
            ->put(route('dashboard.setting.cms.update', 'pendaftaran'), [
                'breadcrumb_home' => $defaults['breadcrumb_home'],
                'breadcrumb_current' => $defaults['breadcrumb_current'],
                'h1' => $defaults['h1'],
                'cards_row_count' => 1,
                'cards' => $remaining,
            ])
            ->assertRedirect(route('dashboard.setting.cms.edit', 'pendaftaran'))
            ->assertSessionHas('status');

        $saved = CmsPageService::merged('pendaftaran');
        $this->assertCount(1, $saved['cards'] ?? []);
    }

    public function test_kartu_tanpa_label_tombol_menampilkan_error_validasi(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $defaults = CmsPageService::merged('pendaftaran');

        $cards = $defaults['cards'];
        $cards[] = [
            'key' => 'c_kosong',
            'icon' => '',
            'arrow_icon' => 'fa-solid fa-arrow-right',
            'title' => 'Kartu tanpa tombol',
            'description' => '',
            'cta_label' => '',
            'url' => 'kartu-tanpa-tombol',
        ];

        $this->actingAs($superAdmin)
            ->from(route('dashboard.setting.cms.edit', 'pendaftaran'))
            ->put(route('dashboard.setting.cms.update', 'pendaftaran'), [
                'breadcrumb_home' => $defaults['breadcrumb_home'],
                'breadcrumb_current' => $defaults['breadcrumb_current'],
                'h1' => $defaults['h1'],
                'cards' => $cards,
            ])
            ->assertRedirect(route('dashboard.setting.cms.edit', 'pendaftaran'))
            ->assertSessionHasErrors('cards.3.cta_label');
    }
}
