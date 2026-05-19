<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_broadcast_templates', function (Blueprint $table) {
            $table->id();
            $table->string('trigger_key', 120);
            $table->string('audience', 40);
            $table->text('message');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('whatsapp_broadcast_template_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('broadcast_template_id')
                ->constrained('whatsapp_broadcast_templates')
                ->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['broadcast_template_id', 'user_id'], 'wa_broadcast_user_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_broadcast_template_users');
        Schema::dropIfExists('whatsapp_broadcast_templates');
    }
};
