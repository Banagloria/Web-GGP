<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('worship_schedules', function (Blueprint $table) {
            $table->date('schedule_date')->nullable()->after('day_of_week');
        });

        foreach (DB::table('worship_schedules')->orderBy('id')->get() as $row) {
            $created = $row->created_at ? Carbon::parse($row->created_at) : Carbon::now();
            $startOfWeek = $created->copy()->startOfWeek(Carbon::SUNDAY);
            $scheduleDate = $startOfWeek->copy()->addDays((int) $row->day_of_week)->toDateString();

            $extras = json_decode((string) ($row->extra_columns ?? ''), true);
            if (! is_array($extras)) {
                $extras = [];
            }

            $middle = [];
            if (trim((string) $row->activity_name) !== '') {
                $middle[] = (string) $row->activity_name;
            }
            if ($row->location !== null && trim((string) $row->location) !== '') {
                $middle[] = (string) $row->location;
            }
            foreach ($extras as $v) {
                $middle[] = (string) $v;
            }

            DB::table('worship_schedules')->where('id', $row->id)->update([
                'schedule_date' => $scheduleDate,
                'extra_columns' => $middle === [] ? null : json_encode(array_values($middle)),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('worship_schedules', function (Blueprint $table) {
            $table->dropColumn('schedule_date');
        });
    }
};
