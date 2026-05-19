<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\CmsPageService;
use App\Support\AdminRepeatableFields;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class AdminFormReplaceRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_replace_in_request_menghapus_indeks_field_kosong_bukan_merge_rekursif(): void
    {
        $request = Request::create('/test', 'PUT', [
            'form_fields' => [
                ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'width' => 'setengah'],
                ['name' => '', 'label' => '', 'type' => 'text', 'width' => 'setengah'],
            ],
        ]);

        AdminRepeatableFields::replaceInRequest($request, [
            'form_fields' => AdminRepeatableFields::pruneTemplateRows($request->input('form_fields', []), ['name']),
        ]);

        $fields = $request->input('form_fields');
        $this->assertIsArray($fields);
        $this->assertCount(1, $fields);
        $this->assertSame('email', $fields[0]['name']);
        $this->assertArrayNotHasKey(1, $fields);
    }

    public function test_simpan_kontak_dengan_dua_field_satu_kosong_berhasil(): void
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
            'required' => '0',
        ];

        $this->actingAs($superAdmin)
            ->put(route('dashboard.setting.cms.update', 'kontak'), array_merge($defaults, [
                'form_fields' => $fields,
            ]))
            ->assertRedirect(route('dashboard.setting.cms.edit', 'kontak'))
            ->assertSessionHas('status');
    }
}
