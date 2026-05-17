<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marriage_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('groom_name');
            $table->string('bride_name');
            $table->date('wedding_date')->nullable();
            $table->string('phone')->nullable();
            $table->string('status', 32)->default('submitted');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marriage_registrations');
    }
};
