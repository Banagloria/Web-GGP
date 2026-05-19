<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\User;
use App\Services\CmsPageService;
use App\Services\WorshipSchedulePartitionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardFormSubmitTest extends TestCase
{
    use RefreshDatabase;

    public function test_simpan_jadwal_baru_dengan_semua_field(): void
    {
        $admin = User::factory()->admin()->create();
        $cms = CmsPageService::merged('jadwal');
        $count = WorshipSchedulePartitionService::dynamicColumnCount($cms);

        $this->actingAs($admin)
            ->post(route('dashboard.jadwal-ibadah.store'), [
                'schedule_date' => '2026-05-19',
                'starts_at' => '09:00',
                'ends_at' => '11:00',
                'column_values' => array_fill(0, $count, 'Isi kolom'),
            ])
            ->assertRedirect(route('dashboard.jadwal-ibadah.index'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('worship_schedules', [
            'schedule_date' => '2026-05-19 00:00:00',
        ]);
    }

    public function test_form_utama_jadwal_tidak_mengandung_form_bersarang(): void
    {
        $admin = User::factory()->admin()->create();

        $html = $this->actingAs($admin)
            ->get(route('dashboard.jadwal-ibadah.create'))
            ->assertOk()
            ->getContent();

        $start = strpos($html, 'id="schedule-form"');
        $this->assertNotFalse($start);

        $formOpen = strrpos(substr($html, 0, $start), '<form');
        $formClose = strpos($html, '</form>', $start);
        $this->assertNotFalse($formOpen);
        $this->assertNotFalse($formClose);

        $mainForm = substr($html, $formOpen, $formClose - $formOpen);
        $this->assertStringContainsString('name="schedule_date"', $mainForm);
        $this->assertStringContainsString('type="submit"', $mainForm);
        $this->assertStringNotContainsString('<form', substr($mainForm, 5));
    }

    public function test_edit_pengumuman_field_dan_submit_dalam_form_yang_sama(): void
    {
        $admin = User::factory()->admin()->create();
        $item = Announcement::query()->create([
            'title' => 'Tes',
            'slug' => 'tes',
            'body' => 'Isi',
            'is_published' => false,
        ]);

        $html = $this->actingAs($admin)
            ->get(route('dashboard.pengumuman.edit', $item))
            ->assertOk()
            ->getContent();

        $start = strpos($html, 'id="announcement-form"');
        $this->assertNotFalse($start);

        $formOpen = strrpos(substr($html, 0, $start), '<form');
        $formClose = strpos($html, '</form>', $start);
        $mainForm = substr($html, $formOpen, $formClose - $formOpen);

        $this->assertStringContainsString('name="title"', $mainForm);
        $this->assertStringContainsString('name="is_published"', $mainForm);
        $this->assertStringContainsString('type="submit"', $mainForm);
        $this->assertStringNotContainsString('<form', substr($mainForm, 5));
    }

    public function test_simpan_pengumuman_dengan_judul_dan_status(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('dashboard.pengumuman.store'), [
                'title' => 'Pengumuman uji',
                'body' => 'Isi lengkap',
                'is_published' => '1',
            ])
            ->assertRedirect(route('dashboard.pengumuman.index'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('announcements', [
            'title' => 'Pengumuman uji',
            'is_published' => true,
        ]);
    }
}
