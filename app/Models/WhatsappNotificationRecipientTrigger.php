<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappNotificationRecipientTrigger extends Model
{
    protected $fillable = [
        'recipient_id',
        'trigger_key',
    ];

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(WhatsappNotificationRecipient::class, 'recipient_id');
    }
}
