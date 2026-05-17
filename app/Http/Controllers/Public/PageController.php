<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\CmsPageService;
use Illuminate\View\View;
use Throwable;

class PageController extends Controller
{
    public function profil(): View
    {
        return $this->pageFromCms('profil');
    }

    public function struktur(): View
    {
        return $this->pageFromCms('struktur');
    }

    private function pageFromCms(string $slug): View
    {
        $cms = CmsPageService::merged($slug);
        $page = new Page([
            'slug' => $slug,
            'title' => $cms['title'] ?? 'Halaman',
            'body' => $cms['body'] ?? '',
        ]);

        return view('public.page', compact('page', 'cms'));
    }

    /**
     * @deprecated Hanya profil & struktur lewat CMS; slug lain tidak dipakai.
     */
    public function show(string $slug): View
    {
        abort_unless(in_array($slug, ['profil', 'struktur'], true), 404);

        return $this->pageFromCms($slug);
    }
}
