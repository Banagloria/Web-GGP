<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationSubmission extends Model
{
    protected $fillable = [
        'type_slug',
        'card_key',
        'status',
        'notes',
        'payload',
        'files',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'files' => 'array',
        ];
    }

    public function payloadValue(string $key, mixed $default = null): mixed
    {
        $payload = $this->payload ?? [];

        return is_array($payload) ? ($payload[$key] ?? $default) : $default;
    }
}
