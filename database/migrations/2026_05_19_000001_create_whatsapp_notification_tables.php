<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_waha_configs', function (Blueprint $table) {
            $table->id();
            $table->string('host', 500)->nullable();
            $table->text('api_key')->nullable();
            $table->string('session', 120)->default('default');
            $table->boolean('is_connected')->default(false);
            $table->timestamp('last_connected_at')->nullable();
            $table->timestamps();
        });

        Schema::create('whatsapp_message_templates', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('trigger_key')->unique();
            $table->text('message');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('whatsapp_notification_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('chat_id', 64);
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_notification_recipients');
        Schema::dropIfExists('whatsapp_message_templates');
        Schema::dropIfExists('whatsapp_waha_configs');
    }
};
