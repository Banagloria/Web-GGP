<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('whatsapp_broadcast_templates')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE whatsapp_broadcast_templates MODIFY audience VARCHAR(120) NOT NULL');
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('whatsapp_broadcast_templates')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE whatsapp_broadcast_templates MODIFY audience VARCHAR(40) NOT NULL');
        }
    }
};
