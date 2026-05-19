<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WhatsappBroadcastTemplate extends Model
{
    protected $fillable = [
        'trigger_key',
        'audience',
        'message',
        'sort_order',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'whatsapp_broadcast_template_users', 'broadcast_template_id', 'user_id')
            ->withTimestamps();
    }

    public function templateUsers(): HasMany
    {
        return $this->hasMany(WhatsappBroadcastTemplateUser::class, 'broadcast_template_id');
    }

}
