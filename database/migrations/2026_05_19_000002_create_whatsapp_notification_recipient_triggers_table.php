<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_notification_recipient_triggers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipient_id')
                ->constrained('whatsapp_notification_recipients')
                ->cascadeOnDelete();
            $table->string('trigger_key', 120);
            $table->timestamps();

            $table->unique(['recipient_id', 'trigger_key'], 'wa_recipient_trigger_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_notification_recipient_triggers');
    }
};
