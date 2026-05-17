<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Throwable;

class GalleryItem extends Model
{
    protected $fillable = [
        'path',
        'original_name',
        'caption',
        'mime',
        'is_public',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
        ];
    }

    public static function tableReady(): bool
    {
        try {
            return Schema::hasTable((new static)->getTable());
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeNewestFirst(Builder $query): Builder
    {
        return $query->orderByDesc('created_at')->orderByDesc('id');
    }

    /**
     * @return Collection<int, static>
     */
    public static function orderedForDisplay(): Collection
    {
        if (! static::tableReady()) {
            return collect();
        }

        try {
            return static::query()->newestFirst()->get();
        } catch (Throwable) {
            return collect();
        }
    }

    public function url(): string
    {
        return asset('storage/'.$this->path);
    }

    public function deleteStoredFile(): void
    {
        if ($this->path && Storage::disk('public')->exists($this->path)) {
            Storage::disk('public')->delete($this->path);
        }
    }
}
