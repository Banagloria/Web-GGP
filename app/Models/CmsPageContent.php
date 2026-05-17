<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsPageContent extends Model
{
    protected $fillable = ['page_key', 'data'];

    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }

    public static function dataFor(string $pageKey): ?array
    {
        try {
            $row = static::query()->where('page_key', $pageKey)->first();
        } catch (\Throwable) {
            return null;
        }

        return $row?->data;
    }

    public static function put(string $pageKey, array $data): void
    {
        static::query()->updateOrCreate(
            ['page_key' => $pageKey],
            ['data' => $data]
        );
    }
}
