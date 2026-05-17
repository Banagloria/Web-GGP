<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorshipSchedule extends Model
{
    protected $fillable = [
        'day_of_week',
        'schedule_date',
        'starts_at',
        'ends_at',
        'activity_name',
        'location',
        'extra_columns',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'schedule_date' => 'date',
            'is_active' => 'boolean',
            'extra_columns' => 'array',
        ];
    }
}
