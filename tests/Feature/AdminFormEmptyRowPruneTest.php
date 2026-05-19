<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\CmsPageService;
use App\Support\PendaftaranCardCms;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminFormEmptyRowPruneTest extends TestCase
{
    use RefreshDatabase;

    public function test_simpan_kontak_berhasil_walau_ada_baris_field_kosong_di_akhir(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $defaults = CmsPageService::merged('kontak');
        $fields = $defaults['form_fields'];
        $fields[] = [
            'name' => '',
            'type' => 'text',
            'width' => 'setengah',
            'label' => '',
            'placeholder' => '',
            'required' => false,
        ];

        $this->actingAs($superAdmin)
            ->put(route('dashboard.setting.cms.update', 'kontak'), array_merge($defaults, [
                'form_fields' => $fields,
            ]))
            ->assertRedirect(route('dashboard.setting.cms.edit', 'kontak'))
            ->assertSessionHas('status');
    }

    public function test_prune_detail_kartu_menghapus_baris_kosong_sebelum_validasi(): void
    {
        $detail = PendaftaranCardCms::detailFromCms(CmsPageService::merged('pendaftaran'), 'jemaat');
        $sections = [];

        foreach ($detail['sections'] as $section) {
            $fields = PendaftaranCardCms::sectionFieldsForAdmin($section);
            $fields[] = [
                'name' => '',
                'label' => '',
                'icon' => '',
                'type' => 'text',
                'width' => 'full',
                'required' => '0',
                'placeholder' => '',
            ];
            $section['fields'] = $fields;
            unset($section['groups']);
            $sections[] = $section;
        }

        $detail['sections'] = $sections;
        $detail['info_panel']['steps'][] = '';

        $pruned = PendaftaranCardCms::pruneDetailPayloadForValidation($detail);
        $validator = validator($pruned, PendaftaranCardCms::validationRulesForCard('jemaat'));

        $this->assertFalse(
            $validator->fails(),
            implode(' | ', $validator->errors()->all())
        );
    }
}
