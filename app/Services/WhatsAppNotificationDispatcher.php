<?php

namespace App\Services;

use App\Models\WhatsappMessageTemplate;
use App\Models\WhatsappNotificationRecipient;
use App\Support\WhatsAppNotificationSupport;
use Illuminate\Support\Facades\Log;
use Throwable;

final class WhatsAppNotificationDispatcher
{
    /**
     * @param  array<string, string>  $replacements
     */
    public static function dispatch(string $triggerKey, array $replacements = []): void
    {
        if (! WhatsAppNotificationSupport::isReady()) {
            return;
        }

        try {
            $template = WhatsappMessageTemplate::query()
                ->where('trigger_key', $triggerKey)
                ->first();

            if ($template === null) {
                return;
            }

            $recipients = WhatsappNotificationRecipient::query()
                ->whereHas('triggers', fn ($query) => $query->where('trigger_key', $triggerKey))
                ->with('user')
                ->get();
            if ($recipients->isEmpty()) {
                return;
            }

            $waha = WahaApiService::make();
            $status = $waha->refreshConnectionStatus();
            if (! $status['connected']) {
                Log::warning('WhatsApp notification skipped: WAHA not connected.', [
                    'trigger' => $triggerKey,
                    'status' => $status['status'] ?? null,
                ]);

                return;
            }

            $text = self::renderMessage($template->message, $replacements);

            foreach ($recipients as $recipient) {
                if ($recipient->chat_id === '') {
                    continue;
                }

                try {
                    $waha->sendText($recipient->chat_id, $text);
                } catch (Throwable $e) {
                    Log::error('WhatsApp notification send failed.', [
                        'trigger' => $triggerKey,
                        'chat_id' => $recipient->chat_id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        } catch (Throwable $e) {
            Log::error('WhatsApp notification dispatch failed.', [
                'trigger' => $triggerKey,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  array<string, string>  $extra
     * @return array<string, string>
     */
    public static function replacementsFromFormData(array $data, array $extra = []): array
    {
        $replacements = $extra;

        foreach ($data as $key => $value) {
            if (! is_string($key) || $key === '' || $key === 'data_consent') {
                continue;
            }

            $replacements[$key] = self::stringifyReplacementValue($value);
        }

        return $replacements;
    }

    /**
     * @param  array<string, string>  $replacements
     */
    public static function renderMessage(string $message, array $replacements = []): string
    {
        $output = $message;
        foreach ($replacements as $key => $value) {
            $output = str_replace('{'.$key.'}', $value, $output);
        }

        return $output;
    }

    private static function stringifyReplacementValue(mixed $value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_bool($value)) {
            return $value ? 'Ya' : 'Tidak';
        }

        if (is_scalar($value)) {
            return trim((string) $value);
        }

        if (is_array($value)) {
            $parts = [];
            foreach ($value as $item) {
                $text = self::stringifyReplacementValue($item);
                if ($text !== '') {
                    $parts[] = $text;
                }
            }

            return implode(', ', $parts);
        }

        return '';
    }
}
