<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'read_at',
        'extra',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
            'extra' => 'array',
        ];
    }
}
