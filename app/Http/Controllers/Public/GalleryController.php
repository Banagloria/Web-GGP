<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use App\Services\CmsPageService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Throwable;

class GalleryController extends Controller
{
    private const PHOTOS_PER_PAGE = 7;

    public function index(): View
    {
        try {
            $items = GalleryItem::tableReady()
                ? GalleryItem::query()
                    ->where('is_public', true)
                    ->newestFirst()
                    ->get()
                : new Collection;
        } catch (Throwable) {
            $items = new Collection;
        }

        $allPhotos = $items->map(fn (GalleryItem $item) => [
            'src' => $item->url(),
            'alt' => $item->caption ?: ($item->original_name ?: 'Foto galeri'),
        ])->values();

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $photos = new LengthAwarePaginator(
            $allPhotos->forPage($currentPage, self::PHOTOS_PER_PAGE)->values(),
            $allPhotos->count(),
            self::PHOTOS_PER_PAGE,
            $currentPage,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
        $photos->withQueryString();

        $cms = CmsPageService::merged('galeri');

        return view('public.gallery.index', compact('photos', 'allPhotos', 'cms'));
    }
}
