<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegistrationSubmission;
use App\Services\CmsPageService;
use App\Services\RegistrationSubmissionService;
use App\Support\PendaftaranCardCms;
use App\Support\PublicCmsUrl;
use App\Support\RegistrationSubmissionSupport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RegistrationSubmissionController extends Controller
{
    /**
     * @return array{slug: string, cardKey: string, title: string, columns: list<array{name: string, label: string}>, searchColumn: string}
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
        $type = $this->resolveType($slug);
        $perPage = (int) $request->query('per_page', 10);
        if (! in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $query = RegistrationSubmissionSupport::query();
        if ($query === null) {
            abort(503, 'Tabel pendaftaran belum tersedia. Jalankan: php artisan migrate --force');
        }

        $query->where('type_slug', $slug)->orderByDesc('id');

        if ($search = trim((string) $request->query('q', ''))) {
            $col = $type['searchColumn'];
            $query->where("payload->{$col}", 'like', '%'.$search.'%');
        }

        if (($status = $request->query('status')) && $status !== 'semua' && $status !== '') {
            $query->where('status', $status);
        }

        $items = $query->paginate($perPage)->withQueryString();

        return view('admin.registration-submissions.index', [
            'slug' => $type['slug'],
            'title' => $type['title'],
            'columns' => $type['columns'],
            'items' => $items,
        ]);
    }

    public function exportCsv(Request $request, string $slug): StreamedResponse
    {
        $type = $this->resolveType($slug);
        $query = RegistrationSubmissionSupport::query();
        if ($query === null) {
            abort(503, 'Tabel pendaftaran belum tersedia. Jalankan: php artisan migrate --force');
        }

        $query->where('type_slug', $slug)->orderByDesc('id');

        if ($search = trim((string) $request->query('q', ''))) {
            $col = $type['searchColumn'];
            $query->where("payload->{$col}", 'like', '%'.$search.'%');
        }

        if (($status = $request->query('status')) && $status !== 'semua' && $status !== '') {
            $query->where('status', $status);
        }

        $filename = 'pendaftaran-'.$slug.'-'.now()->format('Y-m-d_His').'.csv';
        $headers = array_merge(['ID', 'Status', 'Catatan', 'Dibuat'], array_column($type['columns'], 'label'));

        return response()->streamDownload(function () use ($query, $type, $headers): void {
            $out = fopen('php://output', 'w');
            if ($out === false) {
                return;
            }
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, $headers);
            foreach ($query->cursor() as $row) {
                $line = [
                    $row->id,
                    $row->status,
                    $row->notes,
                    $row->created_at?->timezone(config('app.timezone'))->format('Y-m-d H:i'),
                ];
                $files = is_array($row->files) ? $row->files : [];
                foreach ($type['columns'] as $col) {
                    $name = $col['name'];
                    if (isset($files[$name])) {
                        $line[] = $files[$name];
                    } else {
                        $val = $row->payloadValue($name);
                        $line[] = is_array($val) ? json_encode($val, JSON_UNESCAPED_UNICODE) : $val;
                    }
                }
                fputcsv($out, $line);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function show(string $slug, RegistrationSubmission $submission): View
    {
        $type = $this->resolveType($slug);
        abort_unless($submission->type_slug === $slug, 404);

        return view('admin.registration-submissions.show', [
            'slug' => $type['slug'],
            'title' => $type['title'],
            'columns' => $type['columns'],
            'submission' => $submission,
        ]);
    }

    public function destroy(string $slug, RegistrationSubmission $submission): RedirectResponse
    {
        abort_unless($submission->type_slug === $slug, 404);
        $submission->delete();

        return redirect()
            ->route('dashboard.pendaftaran-data.index', $slug)
            ->with('status', 'Data pendaftaran dihapus.');
    }

    /**
     * @return list<array{slug: string, label: string, url: string}>
     */
    public static function navItemsFromCms(): array
    {
        try {
            $cms = CmsPageService::merged('pendaftaran');
        } catch (\Throwable) {
            return [];
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
            $items[] = [
                'slug' => $slug,
                'label' => (string) ($card['title'] ?? $slug),
                'url' => route('dashboard.pendaftaran-data.index', $slug),
            ];
        }

        return $items;
    }
}
