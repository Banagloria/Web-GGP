<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappMessageTemplate extends Model
{
    protected $fillable = [
        'title',
        'trigger_key',
        'message',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }
}
