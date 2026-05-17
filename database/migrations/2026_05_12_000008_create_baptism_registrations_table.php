<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('baptism_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->unsignedSmallInteger('age')->nullable();
            $table->string('gender', 16)->nullable();
            $table->date('baptism_date')->nullable();
            $table->string('status', 32)->default('submitted');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('baptism_registrations');
    }
};
