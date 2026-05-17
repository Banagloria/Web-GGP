<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Services\CmsPageService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Throwable;

class AnnouncementController extends Controller
{
    public function index(): View
    {
        try {
            $items = Announcement::query()
                ->where('is_published', true)
                ->orderByDesc('published_at')
                ->orderByDesc('id')
                ->paginate(10);
        } catch (Throwable) {
            $items = new LengthAwarePaginator([], 0, 10, null, [
                'path' => request()->url(),
                'pageName' => 'page',
            ]);
        }

        $cms = CmsPageService::merged('informasi_kegiatan');

        return view('public.announcements.index', compact('items', 'cms'));
    }

    public function show(string $slug): View
    {
        try {
            $announcement = Announcement::query()
                ->where('slug', $slug)
                ->where('is_published', true)
                ->firstOrFail();
        } catch (Throwable) {
            abort(404);
        }

        $cms = CmsPageService::merged('informasi_kegiatan');

        return view('public.announcements.show', compact('announcement', 'cms'));
    }
}
