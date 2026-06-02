<?php

namespace App\Support;

use Illuminate\Support\Facades\Schema;
use Throwable;

final class WhatsAppNotificationSupport
{
    public static function isReady(): bool
    {
        try {
            return Schema::hasTable('whatsapp_waha_configs')
                && Schema::hasTable('whatsapp_message_templates')
                && Schema::hasTable('whatsapp_notification_recipients')
                && Schema::hasTable('whatsapp_notification_recipient_triggers');
        } catch (Throwable) {
            return false;
        }
    }

    public static function broadcastReady(): bool
    {
        if (! self::isReady()) {
            return false;
        }

        try {
            return Schema::hasTable('whatsapp_broadcast_templates')
                && Schema::hasTable('whatsapp_broadcast_template_users');
        } catch (Throwable) {
            return false;
        }
    }

    public static function broadcastRecipientColumnsReady(): bool
    {
        if (! self::broadcastReady()) {
            return false;
        }

        try {
            return Schema::hasColumn('whatsapp_broadcast_template_users', 'recipient_name')
                && Schema::hasColumn('whatsapp_broadcast_template_users', 'recipient_phone');
        } catch (Throwable) {
            return false;
        }
    }

    public static function broadcastChatIdColumnReady(): bool
    {
        if (! self::broadcastRecipientColumnsReady()) {
            return false;
        }

        try {
            return Schema::hasColumn('whatsapp_broadcast_template_users', 'chat_id');
        } catch (Throwable) {
            return false;
        }
    }
}
