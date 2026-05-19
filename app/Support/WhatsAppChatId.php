<?php

namespace App\Support;

final class WhatsAppChatId
{
    public static function fromPhone(?string $phone): ?string
    {
        if ($phone === null || trim($phone) === '') {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $phone) ?? '';
        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        } elseif (! str_starts_with($digits, '62')) {
            $digits = '62'.ltrim($digits, '0');
        }

        if (strlen($digits) < 10 || strlen($digits) > 15) {
            return null;
        }

        return $digits.'@c.us';
    }

    public static function displayFromChatId(string $chatId): string
    {
        $digits = str_replace('@c.us', '', $chatId);

        if (str_starts_with($digits, '62') && strlen($digits) > 2) {
            return '0'.substr($digits, 2);
        }

        return $digits;
    }
}
