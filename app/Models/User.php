<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\PublicCmsUrl;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;
use Throwable;

#[Fillable(['name', 'email', 'phone', 'password', 'role', 'profile_photo_url'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    public const ROLE_USER = 'user';

    public const ROLE_ADMIN = 'admin';

    public const ROLE_SUPER_ADMIN = 'super_admin';

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_SUPER_ADMIN], true);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            self::ROLE_SUPER_ADMIN => 'Super admin',
            self::ROLE_ADMIN => 'Admin',
            default => 'Pengguna',
        };
    }

    /**
     * @param  Builder<User>  $query
     * @return Builder<User>
     */
    public function scopePanelUsers(Builder $query): Builder
    {
        return $query->whereIn('role', [self::ROLE_ADMIN, self::ROLE_SUPER_ADMIN]);
    }

    /**
     * @param  Builder<User>  $query
     * @return Builder<User>
     */
    public function scopeAdmins(Builder $query): Builder
    {
        return $query->panelUsers();
    }

    public function isLastSuperAdmin(): bool
    {
        return static::query()->where('role', self::ROLE_SUPER_ADMIN)->count() === 1 && $this->isSuperAdmin();
    }

    private static ?bool $profilePhotoColumnReadyCache = null;

    private static ?bool $phoneColumnReadyCache = null;

    public static function phoneColumnReady(): bool
    {
        if (static::$phoneColumnReadyCache !== null) {
            return static::$phoneColumnReadyCache;
        }

        try {
            static::$phoneColumnReadyCache = Schema::hasColumn((new static)->getTable(), 'phone');
        } catch (Throwable) {
            static::$phoneColumnReadyCache = false;
        }

        return static::$phoneColumnReadyCache;
    }

    public static function profilePhotoColumnReady(): bool
    {
        if (static::$profilePhotoColumnReadyCache !== null) {
            return static::$profilePhotoColumnReadyCache;
        }

        try {
            static::$profilePhotoColumnReadyCache = Schema::hasColumn((new static)->getTable(), 'profile_photo_url');
        } catch (Throwable) {
            static::$profilePhotoColumnReadyCache = false;
        }

        return static::$profilePhotoColumnReadyCache;
    }

    public function profilePhotoSrc(): ?string
    {
        if (! static::profilePhotoColumnReady()) {
            return null;
        }

        $url = trim((string) ($this->profile_photo_url ?? ''));

        return $url !== '' ? PublicCmsUrl::imagePreviewSrc($url) : null;
    }

    public function avatarInitial(): string
    {
        return strtoupper(substr((string) $this->name, 0, 1)) ?: 'A';
    }

    protected static function booted(): void
    {
        static::saving(function (User $user): void {
            if (! static::phoneColumnReady()) {
                unset($user->attributes['phone']);
            }
        });
    }
}
