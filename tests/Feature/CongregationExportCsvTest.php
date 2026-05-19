<?php

namespace Tests\Feature;

use App\Models\CongregationRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CongregationExportCsvTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dapat_mengunduh_csv_pendaftaran_jemaat(): void
    {
        $admin = User::factory()->admin()->create();
        CongregationRegistration::query()->create([
            'full_name' => 'Export Test',
            'birth_date' => '2000-01-15',
            'birth_place' => 'Timika',
            'gender' => 'Laki-laki',
            'address' => 'Jl. Contoh',
            'phone' => '08120000000',
            'email' => 'export@test.local',
            'status' => 'submitted',
            'notes' => null,
        ]);

        $response = $this->actingAs($admin)->get(route('dashboard.pendaftaran.export-csv', 'jemaat'));

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('Export Test', $response->streamedContent());
        $this->assertStringContainsString("\xEF\xBB\xBF", $response->streamedContent());
    }

    public function test_bukan_admin_tidak_boleh_akses_ekspor_csv(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('dashboard.pendaftaran.export-csv', 'jemaat'));

        $response->assertForbidden();
    }
}
