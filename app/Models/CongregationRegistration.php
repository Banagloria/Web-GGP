<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CongregationRegistration extends Model
{
    protected $fillable = [
        'full_name',
        'birth_date',
        'birth_place',
        'gender',
        'address',
        'phone',
        'email',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }
}
