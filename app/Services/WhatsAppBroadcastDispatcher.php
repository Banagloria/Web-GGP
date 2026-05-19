<?php

namespace App\Services;

use App\Models\User;
use App\Models\WhatsappBroadcastTemplate;
use App\Support\WhatsAppChatId;
use App\Support\WhatsAppNotificationSupport;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

final class WhatsAppBroadcastDispatcher
{
    /**
     * @param  array<string, string>  $replacements
     */
    public static function dispatch(string $triggerKey, array $replacements = []): void
    {
        if (! WhatsAppNotificationSupport::broadcastReady()) {
            return;
        }

        try {
            $templates = WhatsappBroadcastTemplate::query()
                ->where('trigger_key', $triggerKey)
                ->with(['users'])
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            if ($templates->isEmpty()) {
                return;
            }

            $waha = WahaApiService::make();
            $status = $waha->refreshConnectionStatus();
            if (! $status['connected']) {
                Log::warning('WhatsApp broadcast skipped: WAHA not connected.', [
                    'trigger' => $triggerKey,
                ]);

                return;
            }

            foreach ($templates as $template) {
                $text = WhatsAppNotificationDispatcher::renderMessage($template->message, $replacements);
                $chatIds = self::chatIdsForTemplate($template);

                foreach ($chatIds as $chatId) {
                    try {
                        $waha->sendText($chatId, $text);
                    } catch (Throwable $e) {
                        Log::error('WhatsApp broadcast send failed.', [
                            'trigger' => $triggerKey,
                            'template_id' => $template->id,
                            'chat_id' => $chatId,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            }
        } catch (Throwable $e) {
            Log::error('WhatsApp broadcast dispatch failed.', [
                'trigger' => $triggerKey,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @return list<string>
     */
    private static function chatIdsForTemplate(WhatsappBroadcastTemplate $template): array
    {
        $chatIds = [];

        if ($template->audience === WhatsAppBroadcastCatalog::AUDIENCE_ONE_BY_ONE) {
            foreach ($template->templateUsers()->with('user')->get() as $row) {
                $phone = $row->user?->phone ?? $row->recipient_phone;
                $chatId = WhatsAppChatId::fromPhone(is_string($phone) ? $phone : null);
                if ($chatId !== null && $chatId !== '') {
                    $chatIds[$chatId] = $chatId;
                }
            }

            return array_values($chatIds);
        }

        foreach (self::usersForAudience($template) as $user) {
            $chatId = WhatsAppChatId::fromPhone($user->phone);
            if ($chatId !== null && $chatId !== '') {
                $chatIds[$chatId] = $chatId;
            }
        }

        return array_values($chatIds);
    }

    /**
     * @return Collection<int, User>
     */
    private static function usersForAudience(WhatsappBroadcastTemplate $template): Collection
    {
        $query = User::query()
            ->when(User::phoneColumnReady(), fn ($q) => $q->whereNotNull('phone')->where('phone', '!=', ''));

        return match ($template->audience) {
            WhatsAppBroadcastCatalog::AUDIENCE_ALL_MEMBERS => $query
                ->where('role', User::ROLE_USER)
                ->get(),
            WhatsAppBroadcastCatalog::AUDIENCE_ALL_ADMINS => $query
                ->panelUsers()
                ->get(),
            WhatsAppBroadcastCatalog::AUDIENCE_ALL_MEMBERS_AND_ADMINS => $query
                ->whereIn('role', [User::ROLE_USER, User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN])
                ->get(),
            default => collect(),
        };
    }
}
