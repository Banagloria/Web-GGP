<?php

namespace App\Services;

use App\Models\WhatsappWahaConfig;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Throwable;

final class WahaApiService
{
    public function __construct(
        private readonly WhatsappWahaConfig $config,
    ) {}

    public static function make(): self
    {
        return new self(WhatsappWahaConfig::current());
    }

    /**
     * @return array{connected: bool, api_ok: bool, status: string, message: string, checked_at: \Illuminate\Support\Carbon}
     */
    public function refreshConnectionStatus(): array
    {
        $session = $this->sessionName();

        try {
            $response = $this->http()->get($this->apiBase().'/sessions/'.$session);

            if ($response->status() === 401 || $response->status() === 403) {
                return $this->storeStatus(false, false, 'ERROR', 'API key WAHA tidak valid (HTTP '.$response->status().').');
            }

            if (! $response->successful()) {
                return $this->storeStatus(false, false, 'ERROR', 'Gagal memeriksa sesi: HTTP '.$response->status().'.');
            }

            $payload = $response->json();
            $status = strtoupper((string) (is_array($payload) ? ($payload['status'] ?? 'UNKNOWN') : 'UNKNOWN'));
            $connected = $status === 'WORKING';

            $message = match ($status) {
                'WORKING' => 'Terhubung ke WAHA.',
                'SCAN_QR_CODE' => 'API WAHA OK. Scan QR WhatsApp di dashboard WAHA untuk mengaktifkan sesi.',
                'STARTING' => 'API WAHA OK. Sesi sedang dimulai…',
                'FAILED', 'STOPPED' => 'API WAHA OK. Sesi WhatsApp tidak aktif ('.$status.'). Buka dashboard WAHA dan scan QR.',
                default => 'API WAHA OK. Status sesi: '.$status,
            };

            return $this->storeStatus($connected, true, $status, $message);
        } catch (Throwable $e) {
            return $this->storeStatus(false, false, 'ERROR', 'Tidak dapat terhubung ke WAHA: '.$e->getMessage());
        }
    }

    /**
     * Mulai ulang sesi jika berhenti atau gagal.
     */
    public function ensureSessionRunning(): void
    {
        $session = $this->sessionName();

        try {
            $response = $this->http()->get($this->apiBase().'/sessions/'.$session);

            if (! $response->successful()) {
                $this->http()->post($this->apiBase().'/sessions', [
                    'name' => $session,
                    'start' => true,
                ]);

                return;
            }

            $status = strtoupper((string) ($response->json('status') ?? ''));

            if (in_array($status, ['FAILED', 'STOPPED'], true)) {
                $this->http()->post($this->apiBase().'/sessions/'.$session.'/restart');

                return;
            }

            if ($status === 'SCAN_QR_CODE') {
                return;
            }

            if (! in_array($status, ['WORKING', 'STARTING'], true)) {
                $this->http()->post($this->apiBase().'/sessions/'.$session.'/start');
            }
        } catch (Throwable) {
            // refreshConnectionStatus akan memberi pesan error ke admin.
        }
    }

    public function sendText(string $chatId, string $text): void
    {
        if (trim($this->resolveApiHost()) === '') {
            throw new \RuntimeException('Host WAHA belum dikonfigurasi.');
        }

        $response = $this->http()
            ->timeout(20)
            ->post($this->apiBase().'/sendText', [
                'session' => $this->sessionName(),
                'chatId' => $chatId,
                'text' => $text,
            ]);

        if (! $response->successful()) {
            throw new RequestException($response);
        }
    }

    public function dashboardUrl(): string
    {
        $public = trim((string) ($this->config->host ?: config('waha.internal_url')));

        return rtrim($public, '/').'/dashboard/';
    }

    public function qrImageUrl(): string
    {
        return $this->apiBase().'/'.$this->sessionName().'/auth/qr?format=image';
    }

    /**
     * @return array{connected: bool, api_ok: bool, status: string, message: string, checked_at: \Illuminate\Support\Carbon}
     */
    private function storeStatus(bool $connected, bool $apiOk, string $status, string $message): array
    {
        $this->config->forceFill([
            'is_connected' => $connected,
            'last_connected_at' => $connected ? now() : $this->config->last_connected_at,
        ])->save();

        return [
            'connected' => $connected,
            'api_ok' => $apiOk,
            'status' => $status,
            'message' => $message,
            'checked_at' => now(),
        ];
    }

    private function apiBase(): string
    {
        return rtrim($this->resolveApiHost(), '/').'/api';
    }

    private function resolveApiHost(): string
    {
        $internal = trim((string) config('waha.internal_url', ''));

        if ($internal !== '') {
            return rtrim($internal, '/');
        }

        return rtrim(trim((string) $this->config->host), '/');
    }

    private function sessionName(): string
    {
        return trim((string) ($this->config->session ?: 'default'));
    }

    private function http(): \Illuminate\Http\Client\PendingRequest
    {
        return Http::timeout(12)->withHeaders($this->headers());
    }

    /**
     * @return array<string, string>
     */
    private function headers(): array
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $apiKey = (string) ($this->config->api_key ?? '');
        if ($apiKey === '') {
            $apiKey = trim((string) env('WAHA_API_KEY', ''));
        }

        if ($apiKey !== '') {
            $headers['X-Api-Key'] = $apiKey;
        }

        return $headers;
    }
}
