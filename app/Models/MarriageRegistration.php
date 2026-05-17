<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarriageRegistration extends Model
{
    protected $fillable = [
        'groom_name',
        'bride_name',
        'wedding_date',
        'phone',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'wedding_date' => 'date',
        ];
    }
}
