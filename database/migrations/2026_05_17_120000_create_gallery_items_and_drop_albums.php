<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('media')) {
            $paths = DB::table('media')->pluck('path');
            foreach ($paths as $path) {
                if ($path && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }

        if (Storage::disk('public')->exists('albums')) {
            Storage::disk('public')->deleteDirectory('albums');
        }

        Schema::dropIfExists('media');
        Schema::dropIfExists('albums');

        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('original_name')->nullable();
            $table->string('caption')->nullable();
            $table->string('mime', 128)->nullable();
            $table->boolean('is_public')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $this->migrateCmsAlbumToGaleri();
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_items');

        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('original_name')->nullable();
            $table->string('mime', 128)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $galeri = DB::table('cms_page_contents')->where('page_key', 'galeri')->first();
        if ($galeri) {
            $data = json_decode($galeri->data, true) ?? [];
            DB::table('cms_page_contents')->insert([
                'page_key' => 'album',
                'data' => json_encode([
                    'breadcrumb_home' => $data['breadcrumb_home'] ?? 'Beranda',
                    'breadcrumb_current' => $data['breadcrumb_current'] ?? 'Album',
                    'h1' => $data['h1'] ?? 'Album kegiatan',
                    'intro' => $data['intro'] ?? '',
                    'empty_no_album' => $data['empty_message'] ?? 'Belum ada album.',
                    'empty_no_photos' => $data['empty_message'] ?? 'Belum ada foto.',
                    'lightbox_title' => $data['lightbox_title'] ?? 'Galeri foto',
                    'lightbox_close_label' => $data['lightbox_close_label'] ?? 'Tutup',
                    'lightbox_prev_label' => $data['lightbox_prev_label'] ?? 'Foto sebelumnya',
                    'lightbox_next_label' => $data['lightbox_next_label'] ?? 'Foto berikutnya',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('cms_page_contents')->where('page_key', 'galeri')->delete();
        }
    }

    private function migrateCmsAlbumToGaleri(): void
    {
        if (! Schema::hasTable('cms_page_contents')) {
            return;
        }

        $albumRow = DB::table('cms_page_contents')->where('page_key', 'album')->first();
        if ($albumRow) {
            $data = json_decode($albumRow->data, true) ?? [];
            $galeriData = [
                'breadcrumb_home' => $data['breadcrumb_home'] ?? 'Beranda',
                'breadcrumb_current' => 'Galeri',
                'h1' => str_contains((string) ($data['h1'] ?? ''), 'Album')
                    ? str_replace('Album', 'Galeri', (string) $data['h1'])
                    : ($data['h1'] ?? 'Galeri foto'),
                'intro' => $data['intro'] ?? '',
                'empty_message' => $data['empty_no_photos'] ?? $data['empty_no_album'] ?? 'Belum ada foto di galeri.',
                'lightbox_title' => $data['lightbox_title'] ?? 'Galeri foto — tampilan besar',
                'lightbox_close_label' => $data['lightbox_close_label'] ?? 'Tutup',
                'lightbox_prev_label' => $data['lightbox_prev_label'] ?? 'Foto sebelumnya',
                'lightbox_next_label' => $data['lightbox_next_label'] ?? 'Foto berikutnya',
            ];

            DB::table('cms_page_contents')->where('page_key', 'album')->delete();
            DB::table('cms_page_contents')->updateOrInsert(
                ['page_key' => 'galeri'],
                ['data' => json_encode($galeriData), 'updated_at' => now(), 'created_at' => now()]
            );
        }

        $beranda = DB::table('cms_page_contents')->where('page_key', 'beranda')->first();
        if ($beranda) {
            $json = str_replace(
                ['/album', '"Album jemaat"', '"Album"'],
                ['/galeri', '"Galeri jemaat"', '"Galeri"'],
                (string) $beranda->data
            );
            DB::table('cms_page_contents')->where('page_key', 'beranda')->update([
                'data' => $json,
                'updated_at' => now(),
            ]);
        }
    }
};
