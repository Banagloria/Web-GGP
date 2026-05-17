<?php

use App\Models\CmsPageContent;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $row = CmsPageContent::query()->where('page_key', 'pendaftaran')->first();
        if ($row === null || ! is_array($row->data)) {
            return;
        }

        $data = $row->data;
        if (! array_key_exists('intro', $data)) {
            return;
        }

        $data['intro'] = '';
        $row->update(['data' => $data]);
    }

    public function down(): void
    {
        $row = CmsPageContent::query()->where('page_key', 'pendaftaran')->first();
        if ($row === null || ! is_array($row->data)) {
            return;
        }

        $data = $row->data;
        if (($data['intro'] ?? '') !== '') {
            return;
        }

        $data['intro'] = 'Pilih formulir yang sesuai. Data akan ditinjau oleh tim sekretariat.';
        $row->update(['data' => $data]);
    }
};
