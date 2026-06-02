<?php

namespace App\Providers;

use App\Support\CmsIcon;
use App\Support\DeploySafeRuntime;
use App\Support\PublicNavIcon;
use App\Services\CmsPageService;
use App\Support\CmsPublicPageDefaults;
use App\Support\PublicCmsUrl;
use App\Models\User;
use App\Models\BaptismRegistration;
use App\Models\CongregationRegistration;
use App\Models\MarriageRegistration;
use App\Models\GalleryItem;
use App\Models\SiteSetting;
use App\Models\WorshipSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        DeploySafeRuntime::ensureBladeCompiledPathWritable();

        $this->app->booting(function () {
            DeploySafeRuntime::relaxDatabaseDriversIfNeeded();
            DeploySafeRuntime::ensureBladeCompiledPathWritable();
        });
    }

    public function boot(): void
    {
        try {
            Carbon::setLocale(config('app.locale', 'id'));
        } catch (Throwable) {
            Carbon::setLocale('en');
        }

        Paginator::defaultView('vendor.pagination.church-admin');

        Validator::replacer('required', function (string $message, string $attribute) {
            return 'Kolom '.$attribute.' wajib diisi.';
        });

        Route::bind('baptism', fn (string $v) => BaptismRegistration::query()->findOrFail($v));
        Route::bind('congregation', fn (string $v) => CongregationRegistration::query()->findOrFail($v));
        Route::bind('marriage', fn (string $v) => MarriageRegistration::query()->findOrFail($v));
        Route::bind('schedule', fn (string $v) => WorshipSchedule::query()->findOrFail($v));
        Route::bind('galleryItem', fn (string $v) => GalleryItem::query()->findOrFail($v));
        Route::bind('user', fn (string $v) => User::query()->panelUsers()->findOrFail($v));

        $layoutDefaults = [
            'churchPhone' => '081240311377',
            'churchEmail' => 'admin@ggpshalomtimika.my.id',
            'churchAddress' => 'Jalan Kelimutu, Timika, Papua',
            'churchNameLine1' => 'GEREJA GERAKAN PANTEKOSTA',
            'churchNameLine2' => 'Syalom Timika',
            'siteLogoUrl' => '',
            'socialFacebook' => '#',
            'socialTwitter' => '#',
            'socialInstagram' => '#',
            'socialYoutube' => '#',
        ];

        View::composer(['layouts.public', 'layouts.admin'], function ($view) use ($layoutDefaults) {
            try {
                $view->with([
                    'churchPhone' => SiteSetting::get('church_phone', $layoutDefaults['churchPhone']),
                    'churchEmail' => SiteSetting::get('church_email', $layoutDefaults['churchEmail']),
                    'churchAddress' => SiteSetting::get('church_address', $layoutDefaults['churchAddress']),
                    'churchNameLine1' => SiteSetting::get('church_name_line1', $layoutDefaults['churchNameLine1']),
                    'churchNameLine2' => SiteSetting::get('church_name_line2', $layoutDefaults['churchNameLine2']),
                    'siteLogoUrl' => SiteSetting::get('site_logo_url', '') ?? '',
                    'socialFacebook' => SiteSetting::get('social_facebook', $layoutDefaults['socialFacebook']),
                    'socialTwitter' => SiteSetting::get('social_twitter', $layoutDefaults['socialTwitter']),
                    'socialInstagram' => SiteSetting::get('social_instagram', $layoutDefaults['socialInstagram']),
                    'socialYoutube' => SiteSetting::get('social_youtube', $layoutDefaults['socialYoutube']),
                ]);
            } catch (Throwable) {
                $view->with($layoutDefaults);
            }
        });

        View::composer(['layouts.public', 'layouts.admin'], function ($view) {
            try {
                $cmsBeranda = CmsPageService::merged('beranda');
            } catch (Throwable) {
                $cmsBeranda = CmsPublicPageDefaults::defaultsFor('beranda');
            }

            $payload = ['cmsBeranda' => $cmsBeranda];

            if ($view->name() === 'layouts.public') {
                try {
                    $cmsPendaftaran = CmsPageService::merged('pendaftaran');
                } catch (Throwable) {
                    $cmsPendaftaran = CmsPublicPageDefaults::defaultsFor('pendaftaran');
                }
                $payload['cmsPublicNav'] = self::buildPublicNavItems($cmsBeranda, $cmsPendaftaran);
            }

            $view->with($payload);
        });

        View::composer(['partials.admin-nav-menu', 'layouts.admin'], function ($view) {
            $view->with([
                'cmsPendaftaranNav' => \App\Http\Controllers\Admin\RegistrationSubmissionController::navItemsFromCms(),
                'cmsPendaftaranAktifNav' => \App\Http\Controllers\Admin\RegistrationSubmissionController::navAcceptedItemsFromCms(),
            ]);
        });
    }

    /**
     * @param  array<string, mixed>  $cmsBeranda
     * @return list<array{url: string, label: string, active: bool, outline?: bool, icon: string}>
     */
    private static function buildPublicNavItems(array $cmsBeranda, array $cmsPendaftaran = []): array
    {
        $items = [];
        foreach ($cmsBeranda['nav'] ?? [] as $row) {
            $raw = $row['route'] ?? null;
            $label = $row['label'] ?? '';
            if (! is_string($raw) || trim($raw) === '') {
                continue;
            }
            $url = self::resolvePublicNavUrl($raw);
            if ($url === '#') {
                continue;
            }
            $storedIcon = trim((string) ($row['icon'] ?? ''));
            $item = [
                'url' => $url,
                'label' => $label,
                'active' => self::isPublicNavActive($raw, $url),
                'icon' => $storedIcon !== ''
                    ? CmsIcon::displayClasses($storedIcon, PublicNavIcon::forRouteRaw((string) $raw))
                    : PublicNavIcon::forRouteRaw((string) $raw),
            ];

            $navPath = PublicCmsUrl::normalizeNavPathForStorage((string) $raw);
            if ($navPath === '/pendaftaran' || $navPath === 'pendaftaran.index') {
                $children = [];
                foreach ($cmsPendaftaran['cards'] ?? [] as $card) {
                    if (! is_array($card)) {
                        continue;
                    }
                    $slug = PublicCmsUrl::formatPendaftaranCardSlugForInput($card['url'] ?? '');
                    if ($slug === '') {
                        continue;
                    }
                    $children[] = [
                        'url' => route('pendaftaran.show', $slug),
                        'label' => (string) ($card['title'] ?? $slug),
                        'active' => request()->routeIs('pendaftaran.show') && request()->route('slug') === $slug,
                        'icon' => CmsIcon::linkedCardIconClasses($card['icon'] ?? '', (string) ($card['url'] ?? '')),
                    ];
                }
                if ($children !== []) {
                    $item['children'] = $children;
                }
            }

            $items[] = $item;
        }

        $items = self::ensureSingleActivePublicNavItem($items);

        if (auth()->check()) {
            $items[] = [
                'url' => route('dashboard.index'),
                'label' => 'Dashboard',
                'active' => request()->routeIs('dashboard.*'),
                'outline' => false,
                'icon' => 'fa-solid fa-gauge-high',
            ];
        } else {
            $items[] = [
                'url' => route('login'),
                'label' => 'Login admin',
                'active' => request()->routeIs('login'),
                'outline' => true,
                'icon' => 'fa-solid fa-right-to-bracket',
            ];
        }

        return $items;
    }

    private static function resolvePublicNavUrl(string $raw): string
    {
        $raw = PublicCmsUrl::normalizeNavPathForStorage($raw);
        if ($raw === '' || $raw === '#') {
            return '#';
        }
        if (preg_match('#^https?://#i', $raw) || str_starts_with($raw, '/')) {
            return PublicCmsUrl::fromPathOrUrl($raw);
        }
        if (Route::has($raw)) {
            try {
                return route($raw);
            } catch (Throwable) {
                //
            }
        }

        return PublicCmsUrl::fromPathOrUrl($raw);
    }

    private static function isPublicNavActive(string $raw, string $resolvedUrl): bool
    {
        $raw = trim($raw);
        $linkPath = self::normalizePublicNavPath(
            PublicCmsUrl::normalizeNavPathForStorage($raw !== '' ? $raw : (parse_url($resolvedUrl, PHP_URL_PATH) ?: '/'))
        );

        if (preg_match('#^https?://#i', $raw)) {
            $linkPath = self::normalizePublicNavPath(parse_url($resolvedUrl, PHP_URL_PATH) ?: '/');
        }

        $routePatterns = self::publicNavRoutePatternsForPath($linkPath);
        if ($routePatterns !== null) {
            return request()->routeIs($routePatterns);
        }

        if ($raw !== '' && ! str_contains($raw, '/') && ! preg_match('#^https?://#i', $raw) && Route::has($raw)) {
            return match ($raw) {
                'home' => request()->routeIs('home'),
                'profil' => request()->routeIs('profil'),
                'struktur' => request()->routeIs('struktur'),
                'jadwal' => request()->routeIs('jadwal'),
                'pendaftaran.index' => request()->routeIs('pendaftaran.*'),
                'pendaftaran.show' => request()->routeIs('pendaftaran.show'),
                'informasi-kegiatan' => request()->routeIs('informasi-kegiatan') || request()->routeIs('informasi-kegiatan.show'),
                'kontak' => request()->routeIs('kontak'),
                'galeri' => request()->routeIs('galeri'),
                'album' => request()->routeIs('galeri'),
                default => false,
            };
        }

        $current = self::normalizePublicNavPath(request()->path());

        if ($current === '/') {
            return $linkPath === '/';
        }

        if ($linkPath === '/') {
            return false;
        }

        return $current === $linkPath || str_starts_with($current, $linkPath.'/');
    }

    /** @return list<string>|null */
    private static function publicNavRoutePatternsForPath(string $linkPath): ?array
    {
        return match ($linkPath) {
            '/' => ['home'],
            '/profil' => ['profil'],
            '/struktur' => ['struktur'],
            '/jadwal' => ['jadwal'],
            '/pendaftaran' => ['pendaftaran.*'],
            '/informasi-kegiatan' => ['informasi-kegiatan', 'informasi-kegiatan.show'],
            '/kontak' => ['kontak'],
            '/galeri' => ['galeri'],
            default => null,
        };
    }

    private static function normalizePublicNavPath(?string $path): string
    {
        $normalized = '/'.trim((string) $path, '/');

        return $normalized === '/' ? '/' : rtrim($normalized, '/');
    }

    /**
     * @param  list<array{url: string, label: string, active: bool, outline?: bool, icon: string, children?: list<array{url: string, label: string, active: bool, icon: string}>}>  $items
     * @return list<array{url: string, label: string, active: bool, outline?: bool, icon: string, children?: list<array{url: string, label: string, active: bool, icon: string}>}>
     */
    private static function ensureSingleActivePublicNavItem(array $items): array
    {
        $bestIndex = null;
        $bestScore = -1;

        foreach ($items as $index => $item) {
            $score = self::publicNavActiveScore($item);
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestIndex = $index;
            }
        }

        if ($bestIndex === null || $bestScore < 0) {
            return $items;
        }

        foreach ($items as $index => &$item) {
            if ($index === $bestIndex) {
                continue;
            }

            $item['active'] = false;

            if (! isset($item['children']) || ! is_array($item['children'])) {
                continue;
            }

            foreach ($item['children'] as &$child) {
                $child['active'] = false;
            }
            unset($child);
        }
        unset($item);

        return $items;
    }

    /**
     * @param  array{url: string, label: string, active: bool, children?: list<array{url: string, label: string, active: bool, icon: string}>}  $item
     */
    private static function publicNavActiveScore(array $item): int
    {
        if (! ($item['active'] ?? false)) {
            return -1;
        }

        $path = self::normalizePublicNavPath(parse_url($item['url'], PHP_URL_PATH) ?: '/');
        $current = self::normalizePublicNavPath(request()->path());
        $exact = $path === $current;

        return ($exact ? 1_000_000 : 0) + strlen($path);
    }
}
