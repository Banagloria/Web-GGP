<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('whatsapp_broadcast_template_users')) {
            return;
        }

        Schema::table('whatsapp_broadcast_template_users', function (Blueprint $table) {
            if (! Schema::hasColumn('whatsapp_broadcast_template_users', 'chat_id')) {
                $table->string('chat_id', 64)->nullable()->after('recipient_phone');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('whatsapp_broadcast_template_users')) {
            return;
        }

        Schema::table('whatsapp_broadcast_template_users', function (Blueprint $table) {
            if (Schema::hasColumn('whatsapp_broadcast_template_users', 'chat_id')) {
                $table->dropColumn('chat_id');
            }
        });
    }
};
