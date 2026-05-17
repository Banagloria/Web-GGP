<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaptismRegistration extends Model
{
    protected $fillable = [
        'full_name',
        'age',
        'gender',
        'baptism_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'baptism_date' => 'date',
        ];
    }
}
