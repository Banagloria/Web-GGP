<?php

use App\Models\CmsPageContent;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $row = CmsPageContent::query()->where('page_key', 'kontak')->first();
        if ($row === null || ! is_array($row->data)) {
            return;
        }

        $data = $row->data;
        if (! isset($data['form_fields']) || ! is_array($data['form_fields'])) {
            return;
        }

        foreach ($data['form_fields'] as $i => $field) {
            if (! is_array($field)) {
                continue;
            }
            $data['form_fields'][$i]['required'] = true;
        }

        $row->update(['data' => $data]);
    }

    public function down(): void
    {
        $row = CmsPageContent::query()->where('page_key', 'kontak')->first();
        if ($row === null || ! is_array($row->data)) {
            return;
        }

        $data = $row->data;
        if (! isset($data['form_fields']) || ! is_array($data['form_fields'])) {
            return;
        }

        $optional = ['email', 'phone', 'subject'];
        foreach ($data['form_fields'] as $i => $field) {
            if (! is_array($field)) {
                continue;
            }
            $name = $field['name'] ?? '';
            if (in_array($name, $optional, true)) {
                $data['form_fields'][$i]['required'] = false;
            }
        }

        $row->update(['data' => $data]);
    }
};
