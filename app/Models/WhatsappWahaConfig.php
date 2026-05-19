<?php

namespace App\Models;

use App\Support\WhatsAppNotificationSupport;
use Illuminate\Database\Eloquent\Model;

class WhatsappWahaConfig extends Model
{
    protected $fillable = [
        'host',
        'api_key',
        'session',
        'is_connected',
        'last_connected_at',
    ];

    protected function casts(): array
    {
        return [
            'is_connected' => 'boolean',
            'last_connected_at' => 'datetime',
            'api_key' => 'encrypted',
        ];
    }

    public static function current(): self
    {
        if (! WhatsAppNotificationSupport::isReady()) {
            $config = new static([
                'session' => 'default',
                'is_connected' => false,
            ]);
            $config->exists = false;

            return $config;
        }

        return static::query()->firstOrCreate([], [
            'session' => 'default',
            'is_connected' => false,
        ]);
    }
}
