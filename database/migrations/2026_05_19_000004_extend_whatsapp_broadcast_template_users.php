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
            if (! Schema::hasColumn('whatsapp_broadcast_template_users', 'recipient_name')) {
                $table->string('recipient_name', 200)->nullable()->after('user_id');
            }
            if (! Schema::hasColumn('whatsapp_broadcast_template_users', 'recipient_phone')) {
                $table->string('recipient_phone', 32)->nullable()->after('recipient_name');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('whatsapp_broadcast_template_users')) {
            return;
        }

        Schema::table('whatsapp_broadcast_template_users', function (Blueprint $table) {
            if (Schema::hasColumn('whatsapp_broadcast_template_users', 'recipient_name')) {
                $table->dropColumn('recipient_name');
            }
            if (Schema::hasColumn('whatsapp_broadcast_template_users', 'recipient_phone')) {
                $table->dropColumn('recipient_phone');
            }
        });
    }
};
