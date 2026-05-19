<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegistrationSubmission;
use App\Services\CmsPageService;
use App\Services\RegistrationSubmissionExcelExport;
use App\Services\RegistrationSubmissionPdfExport;
use App\Services\RegistrationReviewWhatsappService;
use App\Services\RegistrationSubmissionService;
use App\Support\PendaftaranCardCms;
use App\Support\PublicCmsUrl;
use App\Support\RegistrationSubmissionSupport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RegistrationSubmissionController extends Controller
{
    public const LIST_PENDING = 'pending';

    public const LIST_ACCEPTED = 'accepted';

    private const JEEMAAT_SLUG = 'jemaat';

    /**
     * @return array{index: string, show: string, edit: string, update: string, destroy: string, exportCsv: string, exportPdf: string, accept?: string, reject?: string}
     */
    public static function routesForSlug(string $slug, string $listKind = self::LIST_PENDING): array
    {
        if ($listKind === self::LIST_ACCEPTED) {
            return [
                'index' => 'dashboard.pendaftaran-aktif.index',
                'show' => 'dashboard.pendaftaran-aktif.show',
                'edit' => 'dashboard.pendaftaran-aktif.edit',
                'update' => 'dashboard.pendaftaran-aktif.update',
                'destroy' => 'dashboard.pendaftaran-aktif.destroy',
                'exportCsv' => 'dashboard.pendaftaran-aktif.export-csv',
                'exportPdf' => 'dashboard.pendaftaran-aktif.export-pdf',
            ];
        }

        return [
            'index' => 'dashboard.pendaftaran.index',
            'show' => 'dashboard.pendaftaran.show',
            'edit' => 'dashboard.pendaftaran.edit',
            'update' => 'dashboard.pendaftaran.update',
            'destroy' => 'dashboard.pendaftaran.destroy',
            'exportCsv' => 'dashboard.pendaftaran.export-csv',
            'exportPdf' => 'dashboard.pendaftaran.export-pdf',
            'accept' => 'dashboard.pendaftaran.accept',
            'reject' => 'dashboard.pendaftaran.reject',
        ];
    }

    /**
     * @return array{slug: string, cardKey: string, title: string, columns: list<array{name: string, label: string}>, searchColumn: string, cms: array}
     */
    private function resolveType(string $slug): array
    {
        $cms = CmsPageService::merged('pendaftaran');
        $resolved = PendaftaranCardCms::resolveBySlug($cms, $slug);
        abort_if($resolved === null, 404);

        $columns = RegistrationSubmissionService::listColumnsForSlug($slug, $cms);

        return [
            'slug' => $slug,
            'cardKey' => $resolved['cardKey'],
            'title' => (string) ($resolved['detail']['title'] ?? $resolved['card']['title'] ?? $slug),
            'columns' => $columns,
            'searchColumn' => RegistrationSubmissionService::searchColumnForSlug($slug, $cms),
            'cms' => $cms,
        ];
    }

    public function index(Request $request, string $slug): View
    {
        return $this->renderIndex($request, $slug, self::LIST_PENDING);
    }

    public function indexAccepted(Request $request, string $slug): View
    {
        return $this->renderIndex($request, $slug, self::LIST_ACCEPTED);
    }

    private function renderIndex(Request $request, string $slug, string $listKind): View
    {
        $type = $this->resolveType($slug);
        $perPage = (int) $request->query('per_page', 10);
        if (! in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $query = $this->buildFilteredQuery($request, $slug, $listKind, $type);
        $items = $query->paginate($perPage)->withQueryString();

        $defaultStatus = $listKind === self::LIST_ACCEPTED ? 'active' : 'submitted';
        $status = $request->query('status');
        if ($status === null || $status === '') {
            $status = $defaultStatus;
        }
        $routes = self::routesForSlug($type['slug'], $listKind);
        $pageTitle = $listKind === self::LIST_ACCEPTED
            ? $type['title'].' — Diterima'
            : $type['title'];

        return view('admin.registration-submissions.index', [
            'slug' => $type['slug'],
            'title' => $pageTitle,
            'columns' => $type['columns'],
            'items' => $items,
            'routes' => $routes,
            'listKind' => $listKind,
            'defaultStatus' => $defaultStatus,
        ]);
    }

    public function exportCsv(Request $request, string $slug): StreamedResponse
    {
        /** @var StreamedResponse */
        return $this->streamExport($request, $slug, self::LIST_PENDING, 'excel');
    }

    public function exportCsvAccepted(Request $request, string $slug): StreamedResponse
    {
        /** @var StreamedResponse */
        return $this->streamExport($request, $slug, self::LIST_ACCEPTED, 'excel');
    }

    public function exportPdf(Request $request, string $slug): Response
    {
        return $this->streamExport($request, $slug, self::LIST_PENDING, 'pdf');
    }

    public function exportPdfAccepted(Request $request, string $slug): Response
    {
        return $this->streamExport($request, $slug, self::LIST_ACCEPTED, 'pdf');
    }

    /**
     * @return StreamedResponse|Response
     */
    private function streamExport(Request $request, string $slug, string $listKind, string $format)
    {
        $type = $this->resolveType($slug);
        $query = $this->buildFilteredQuery($request, $slug, $listKind, $type);

        $prefix = $listKind === self::LIST_ACCEPTED ? 'pendaftaran-diterima' : 'pendaftaran';
        $timestamp = now()->format('Y-m-d_His');
        $pageTitle = $listKind === self::LIST_ACCEPTED
            ? $type['title'].' — Diterima'
            : $type['title'];

        if ($format === 'pdf') {
            return RegistrationSubmissionPdfExport::download(
                $query,
                $type['columns'],
                $prefix.'-'.$slug.'-'.$timestamp.'.pdf',
                $pageTitle,
            );
        }

        return RegistrationSubmissionExcelExport::download(
            $query,
            $type['columns'],
            $prefix.'-'.$slug.'-'.$timestamp.'.xls',
            $pageTitle,
        );
    }

    /**
     * @param  array{slug: string, cardKey: string, title: string, columns: list<array{name: string, label: string, type?: string}>, searchColumn: string, cms: array}  $type
     */
    private function buildFilteredQuery(Request $request, string $slug, string $listKind, array $type)
    {
        $query = RegistrationSubmissionSupport::query();
        if ($query === null) {
            abort(503, 'Tabel pendaftaran belum tersedia. Jalankan: php artisan migrate --force');
        }

        $query->where('type_slug', $slug)->orderByDesc('id');

        $defaultStatus = $listKind === self::LIST_ACCEPTED ? 'active' : 'submitted';
        $status = $request->query('status');
        if ($status === null || $status === '') {
            $status = $defaultStatus;
        }

        RegistrationSubmissionService::applySearchFilter(
            $query,
            (string) $request->query('q', ''),
            $type['columns'],
        );

        if ($status !== 'semua' && $status !== '') {
            $query->where('status', $status);
        }

        return $query;
    }

    public function show(string $slug, RegistrationSubmission $submission): View
    {
        return $this->renderShow($slug, $submission, self::LIST_PENDING);
    }

    public function showAccepted(string $slug, RegistrationSubmission $submission): View
    {
        return $this->renderShow($slug, $submission, self::LIST_ACCEPTED);
    }

    public function edit(string $slug, RegistrationSubmission $submission): View
    {
        return $this->renderEdit($slug, $submission, self::LIST_PENDING);
    }

    public function editAccepted(string $slug, RegistrationSubmission $submission): View
    {
        return $this->renderEdit($slug, $submission, self::LIST_ACCEPTED);
    }

    public function update(Request $request, string $slug, RegistrationSubmission $submission): RedirectResponse
    {
        return $this->processUpdate($request, $slug, $submission, self::LIST_PENDING);
    }

    public function updateAccepted(Request $request, string $slug, RegistrationSubmission $submission): RedirectResponse
    {
        return $this->processUpdate($request, $slug, $submission, self::LIST_ACCEPTED);
    }

    private function renderShow(string $slug, RegistrationSubmission $submission, string $listKind): View
    {
        $type = $this->resolveType($slug);
        abort_unless($submission->type_slug === $slug, 404);

        return view('admin.registration-submissions.show', [
            'slug' => $type['slug'],
            'title' => $type['title'],
            'columns' => $type['columns'],
            'submission' => $submission,
            'routes' => self::routesForSlug($type['slug'], $listKind),
            'listKind' => $listKind,
        ]);
    }

    private function renderEdit(string $slug, RegistrationSubmission $submission, string $listKind): View
    {
        $type = $this->resolveType($slug);
        abort_unless($submission->type_slug === $slug, 404);

        $resolved = PendaftaranCardCms::resolveBySlug($type['cms'], $slug);
        abort_if($resolved === null, 404);

        $pageTitle = $listKind === self::LIST_ACCEPTED
            ? $type['title'].' — Diterima'
            : $type['title'];

        return view('admin.registration-submissions.edit', [
            'slug' => $type['slug'],
            'title' => $pageTitle,
            'detail' => $resolved['detail'],
            'submission' => $submission,
            'routes' => self::routesForSlug($type['slug'], $listKind),
            'listKind' => $listKind,
        ]);
    }

    private function processUpdate(Request $request, string $slug, RegistrationSubmission $submission, string $listKind): RedirectResponse
    {
        $type = $this->resolveType($slug);
        abort_unless($submission->type_slug === $slug, 404);

        RegistrationSubmissionService::validateAndUpdate($request, $slug, $type['cms'], $submission);

        $routes = self::routesForSlug($type['slug'], $listKind);

        return redirect()
            ->route($routes['show'], ['slug' => $slug, 'submission' => $submission])
            ->with('status', 'Data pendaftaran berhasil diperbarui.');
    }

    public function accept(Request $request, string $slug, RegistrationSubmission $submission): RedirectResponse
    {
        abort_unless($submission->type_slug === $slug, 404);
        abort_unless($submission->status === 'submitted', 404);

        $type = $this->resolveType($slug);
        $waSent = $this->sendReviewWhatsapp($request, $submission, $type['columns']);

        $submission->update(['status' => 'active']);

        $status = 'Pendaftaran diterima dan dipindahkan ke daftar diterima.';
        if ($waSent) {
            $status .= ' Pesan WhatsApp terkirim.';
        }

        return redirect()
            ->route('dashboard.pendaftaran-aktif.index', ['slug' => $slug])
            ->with('status', $status);
    }

    public function reject(Request $request, string $slug, RegistrationSubmission $submission): RedirectResponse
    {
        abort_unless($submission->type_slug === $slug, 404);

        $type = $this->resolveType($slug);
        $waSent = $this->sendReviewWhatsapp($request, $submission, $type['columns']);

        $submission->delete();

        $status = 'Pendaftaran ditolak dan dihapus.';
        if ($waSent) {
            $status .= ' Pesan WhatsApp terkirim.';
        }

        return redirect()
            ->route('dashboard.pendaftaran.index', ['slug' => $slug])
            ->with('status', $status);
    }

    /**
     * @param  list<array{name: string, label: string, type?: string}>  $columns
     */
    private function sendReviewWhatsapp(Request $request, RegistrationSubmission $submission, array $columns): bool
    {
        $message = trim((string) $request->validate([
            'wa_message' => ['nullable', 'string', 'max:5000'],
        ])['wa_message'] ?? '');
        if ($message === '') {
            return false;
        }

        $phone = RegistrationSubmissionService::phoneFromSubmission($submission, $columns);

        return RegistrationReviewWhatsappService::send($phone, $message);
    }

    public function destroy(string $slug, RegistrationSubmission $submission): RedirectResponse
    {
        abort_unless($submission->type_slug === $slug, 404);
        $submission->delete();

        return redirect()
            ->route('dashboard.pendaftaran.index', ['slug' => $slug])
            ->with('status', 'Data pendaftaran dihapus.');
    }

    public function destroyAccepted(string $slug, RegistrationSubmission $submission): RedirectResponse
    {
        abort_unless($submission->type_slug === $slug, 404);
        $submission->delete();

        return redirect()
            ->route('dashboard.pendaftaran-aktif.index', ['slug' => $slug])
            ->with('status', 'Data pendaftaran dihapus.');
    }

    /**
     * @return list<array{slug: string, label: string, url: string}>
     */
    public static function navItemsFromCms(): array
    {
        return self::buildNavItemsFromCms(self::LIST_PENDING);
    }

    /**
     * @return list<array{slug: string, label: string, url: string}>
     */
    public static function navAcceptedItemsFromCms(): array
    {
        return self::buildNavItemsFromCms(self::LIST_ACCEPTED);
    }

    /**
     * @return list<array{slug: string, label: string, url: string}>
     */
    private static function buildNavItemsFromCms(string $listKind): array
    {
        try {
            $cms = CmsPageService::merged('pendaftaran');
        } catch (\Throwable) {
            $cms = ['cards' => []];
        }

        $items = [];
        foreach ($cms['cards'] ?? [] as $card) {
            if (! is_array($card)) {
                continue;
            }
            $slug = PublicCmsUrl::formatPendaftaranCardSlugForInput($card['url'] ?? '');
            if ($slug === '') {
                continue;
            }
            $items[] = self::navItemForSlug($slug, (string) ($card['title'] ?? $slug), $listKind);
        }

        if ($items === []) {
            $items[] = self::navItemForSlug(
                self::JEEMAAT_SLUG,
                'Pendaftaran jemaat',
                $listKind,
            );
        }

        return $items;
    }

    /**
     * @return array{slug: string, label: string, url: string}
     */
    private static function navItemForSlug(string $slug, string $label, string $listKind): array
    {
        if ($listKind === self::LIST_ACCEPTED) {
            return [
                'slug' => $slug,
                'label' => $label,
                'url' => route('dashboard.pendaftaran-aktif.index', ['slug' => $slug]),
            ];
        }

        return [
            'slug' => $slug,
            'label' => $label,
            'url' => route('dashboard.pendaftaran.index', ['slug' => $slug]),
        ];
    }
}
