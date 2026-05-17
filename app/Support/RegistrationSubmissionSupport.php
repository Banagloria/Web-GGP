<?php

namespace App\Support;

use App\Models\BaptismRegistration;
use App\Models\CongregationRegistration;
use App\Models\MarriageRegistration;
use App\Models\RegistrationSubmission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Throwable;

final class RegistrationSubmissionSupport
{
    public static function isReady(): bool
    {
        try {
            return Schema::hasTable('registration_submissions');
        } catch (Throwable) {
            return false;
        }
    }

    public static function query(): ?Builder
    {
        if (! self::isReady()) {
            return null;
        }

        return RegistrationSubmission::query();
    }

    public static function countSubmitted(?string $typeSlug = null): int
    {
        if (self::isReady()) {
            $query = RegistrationSubmission::query()->where('status', 'submitted');
            if ($typeSlug !== null) {
                $query->where('type_slug', $typeSlug);
            }

            return $query->count();
        }

        return self::legacyCountSubmitted($typeSlug);
    }

    private static function legacyCountSubmitted(?string $typeSlug): int
    {
        try {
            return match ($typeSlug) {
                'jemaat' => Schema::hasTable('congregation_registrations')
                    ? CongregationRegistration::query()->where('status', 'submitted')->count()
                    : 0,
                'baptisan' => Schema::hasTable('baptism_registrations')
                    ? BaptismRegistration::query()->where('status', 'submitted')->count()
                    : 0,
                'pernikahan' => Schema::hasTable('marriage_registrations')
                    ? MarriageRegistration::query()->where('status', 'submitted')->count()
                    : 0,
                null => self::legacyCountSubmitted('jemaat')
                    + self::legacyCountSubmitted('baptisan')
                    + self::legacyCountSubmitted('pernikahan'),
                default => 0,
            };
        } catch (Throwable) {
            return 0;
        }
    }
}
