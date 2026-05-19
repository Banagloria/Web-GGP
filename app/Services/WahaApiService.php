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
     * @return array{connected: bool, status: string, message: string, checked_at: \Illuminate\Support\Carbon}
     */
    public function refreshConnectionStatus(): array
    {
        $host = trim((string) $this->config->host);
        $session = trim((string) ($this->config->session ?: 'default'));

        if ($host === '') {
            return $this->storeStatus(false, 'STOPPED', 'Host WAHA belum diisi.');
        }

        try {
            $response = Http::timeout(12)
                ->withHeaders($this->headers())
                ->get($this->apiBase($host).'/sessions/'.$session);

            if (! $response->successful()) {
                return $this->storeStatus(false, 'ERROR', 'Gagal memeriksa sesi: HTTP '.$response->status());
            }

            $payload = $response->json();
            $status = strtoupper((string) (is_array($payload) ? ($payload['status'] ?? 'UNKNOWN') : 'UNKNOWN'));
            $connected = $status === 'WORKING';

            return $this->storeStatus(
                $connected,
                $status,
                $connected ? 'Terhubung ke WAHA.' : 'Status sesi: '.$status,
            );
        } catch (Throwable $e) {
            return $this->storeStatus(false, 'ERROR', 'Tidak dapat terhubung ke WAHA: '.$e->getMessage());
        }
    }

    public function sendText(string $chatId, string $text): void
    {
        $host = trim((string) $this->config->host);
        $session = trim((string) ($this->config->session ?: 'default'));
        $apiKey = (string) ($this->config->api_key ?? '');

        if ($host === '') {
            throw new \RuntimeException('Host WAHA belum dikonfigurasi.');
        }

        $response = Http::timeout(20)
            ->withHeaders($this->headers())
            ->post($this->apiBase($host).'/sendText', [
                'session' => $session,
                'chatId' => $chatId,
                'text' => $text,
            ]);

        if (! $response->successful()) {
            throw new RequestException($response);
        }
    }

    /**
     * @return array{connected: bool, status: string, message: string, checked_at: \Illuminate\Support\Carbon}
     */
    private function storeStatus(bool $connected, string $status, string $message): array
    {
        $this->config->forceFill([
            'is_connected' => $connected,
            'last_connected_at' => $connected ? now() : $this->config->last_connected_at,
        ])->save();

        return [
            'connected' => $connected,
            'status' => $status,
            'message' => $message,
            'checked_at' => now(),
        ];
    }

    private function apiBase(string $host): string
    {
        return rtrim($host, '/').'/api';
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
        if ($apiKey !== '') {
            $headers['X-Api-Key'] = $apiKey;
        }

        return $headers;
    }
}
