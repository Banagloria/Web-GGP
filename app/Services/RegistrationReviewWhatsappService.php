<?php

namespace App\Services;

use App\Support\WhatsAppChatId;
use App\Support\WhatsAppNotificationSupport;
use Illuminate\Support\Facades\Log;
use Throwable;

final class RegistrationReviewWhatsappService
{
    public static function send(?string $phone, string $message): bool
    {
        $message = trim($message);
        if ($message === '' || $phone === null || trim($phone) === '') {
            return false;
        }

        if (! WhatsAppNotificationSupport::isReady()) {
            return false;
        }

        try {
            $waha = WahaApiService::make();
            $status = $waha->refreshConnectionStatus();
            if (! $status['connected']) {
                Log::warning('Registration review WhatsApp skipped: WAHA not connected.');

                return false;
            }

            $chatId = WhatsAppChatId::fromPhone($phone);
            if ($chatId === null || $chatId === '') {
                return false;
            }

            $waha->sendText($chatId, $message);

            return true;
        } catch (Throwable $e) {
            Log::warning('Registration review WhatsApp send failed.', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
