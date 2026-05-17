<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Contact;
use App\Services\CmsPageService;
use App\Support\CmsPublicPageDefaults;
use App\Support\PublicCmsUrl;
use App\Support\RegistrationSubmissionSupport;
use Illuminate\View\View;
use Throwable;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        try {
            $cmsPendaftaran = CmsPageService::merged('pendaftaran');
        } catch (Throwable) {
            $cmsPendaftaran = CmsPublicPageDefaults::defaultsFor('pendaftaran');
        }

        $registrationStats = [];
        foreach ($cmsPendaftaran['cards'] ?? [] as $card) {
            if (! is_array($card)) {
                continue;
            }
            $slug = PublicCmsUrl::formatPendaftaranCardSlugForInput($card['url'] ?? '');
            if ($slug === '') {
                continue;
            }
            $registrationStats[] = [
                'value' => RegistrationSubmissionSupport::countSubmitted($slug),
                'label' => (string) ($card['title'] ?? $slug),
                'hint' => 'Pendaftaran diajukan',
                'icon' => self::iconForSlug($slug),
                'href' => route('dashboard.pendaftaran-data.index', ['slug' => $slug, 'status' => 'submitted']),
                'valueClass' => 'text-church-gold',
                'iconWrapClass' => 'bg-church-gold/15 ring-church-gold/25',
                'iconClass' => 'size-5 text-church-gold sm:size-6',
            ];
        }

        return view('admin.dashboard', [
            'registrationStats' => $registrationStats,
            'announcementDrafts' => Announcement::query()->where('is_published', false)->count(),
            'unreadContacts' => Contact::query()->whereNull('read_at')->count(),
        ]);
    }

    private static function iconForSlug(string $slug): string
    {
        return match ($slug) {
            'baptisan' => 'beaker',
            'pernikahan' => 'heart',
            'jemaat' => 'users',
            default => 'clipboard',
        };
    }
}
