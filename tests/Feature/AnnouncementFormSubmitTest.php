<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnouncementFormSubmitTest extends TestCase
{
    use RefreshDatabase;

    public function test_simpan_pengumuman_baru_otomatis_publish(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('dashboard.pengumuman.store'), [
                'title' => 'Pengumuman Minggu',
                'body' => 'Isi pengumuman.',
            ])
            ->assertRedirect(route('dashboard.pengumuman.index'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('announcements', [
            'title' => 'Pengumuman Minggu',
            'is_published' => true,
        ]);
    }

    public function test_edit_pengumuman_mengirim_data_form_utama(): void
    {
        $admin = User::factory()->admin()->create();
        $item = Announcement::query()->create([
            'title' => 'Lama',
            'slug' => 'lama',
            'body' => 'Isi lama',
            'is_published' => false,
        ]);

        $this->actingAs($admin)
            ->put(route('dashboard.pengumuman.update', $item), [
                '_token' => csrf_token(),
                '_method' => 'PUT',
                'title' => 'Judul diperbarui',
                'body' => 'Isi baru',
            ])
            ->assertRedirect(route('dashboard.pengumuman.index'))
            ->assertSessionHas('status');

        $item->refresh();
        $this->assertSame('Judul diperbarui', $item->title);
        $this->assertTrue($item->is_published);
        $this->assertNotNull($item->published_at);
    }
}
