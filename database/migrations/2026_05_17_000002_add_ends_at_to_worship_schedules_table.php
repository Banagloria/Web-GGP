<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('worship_schedules', function (Blueprint $table) {
            $table->time('ends_at')->nullable()->after('starts_at');
        });

        foreach (DB::table('worship_schedules')->orderBy('id')->get() as $row) {
            $start = substr((string) $row->starts_at, 0, 8);
            $parts = explode(':', $start);
            $hour = (int) ($parts[0] ?? 9);
            $minute = (int) ($parts[1] ?? 0);
            $endHour = min(23, $hour + 2);
            $endsAt = sprintf('%02d:%02d:00', $endHour, $minute);

            DB::table('worship_schedules')->where('id', $row->id)->update(['ends_at' => $endsAt]);
        }
    }

    public function down(): void
    {
        Schema::table('worship_schedules', function (Blueprint $table) {
            $table->dropColumn('ends_at');
        });
    }
};
