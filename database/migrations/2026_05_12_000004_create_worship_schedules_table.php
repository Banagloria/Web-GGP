<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('worship_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('day_of_week')->comment('0=Sunday');
            $table->time('starts_at');
            $table->string('activity_name');
            $table->string('location')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worship_schedules');
    }
};
