<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('worship_schedules', function (Blueprint $table) {
            $table->json('extra_columns')->nullable()->after('location');
        });
    }

    public function down(): void
    {
        Schema::table('worship_schedules', function (Blueprint $table) {
            $table->dropColumn('extra_columns');
        });
    }
};
