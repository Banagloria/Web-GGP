<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappBroadcastTemplateUser extends Model
{
    protected $fillable = [
        'broadcast_template_id',
        'user_id',
        'recipient_name',
        'recipient_phone',
        'chat_id',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(WhatsappBroadcastTemplate::class, 'broadcast_template_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
