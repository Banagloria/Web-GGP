<?php

namespace Tests\Unit;

use App\Support\AdminRepeatableFields;
use PHPUnit\Framework\TestCase;

class AdminRepeatableFieldsTest extends TestCase
{
    public function test_prune_template_rows_menghapus_baris_kosong(): void
    {
        $rows = [
            ['name' => 'full_name', 'label' => 'Nama'],
            ['name' => '', 'label' => ''],
            ['name' => 'email', 'label' => 'Email'],
        ];

        $pruned = AdminRepeatableFields::pruneTemplateRows($rows, ['name']);

        $this->assertCount(2, $pruned);
        $this->assertSame('full_name', $pruned[0]['name']);
        $this->assertSame('email', $pruned[1]['name']);
    }

    public function test_prune_string_list_menghapus_string_kosong(): void
    {
        $this->assertSame(['Langkah 1'], AdminRepeatableFields::pruneStringList(['Langkah 1', '', '   ']));
    }
}
