<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use App\Services\WhatsAppBroadcastCatalog;
use App\Services\WhatsAppBroadcastDispatcher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GalleryAdminController extends Controller
{
    public function index(): View
    {
        $tableMissing = ! GalleryItem::tableReady();
        $items = $tableMissing ? collect() : GalleryItem::orderedForDisplay();

        return view('admin.gallery.index', compact('items', 'tableMissing'));
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        if (! GalleryItem::tableReady()) {
            $message = 'Tabel galeri belum ada. Jalankan di server: php artisan migrate --force';

            return $this->storeResponse($request, false, $message);
        }

        $request->validate([
            'files' => ['required', 'array', 'min:1', 'max:20'],
            'files.*' => ['required', 'file', 'image'],
        ]);

        $maxSort = (int) GalleryItem::query()->max('sort_order');
        $uploaded = 0;
        $lastItem = null;

        foreach ($request->file('files', []) as $i => $file) {
            if (! $file->isValid()) {
                continue;
            }

            $path = $file->store('gallery', 'public');
            if (! $path || ! Storage::disk('public')->exists($path)) {
                continue;
            }

            $lastItem = GalleryItem::query()->create([
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime' => $file->getClientMimeType() ?: $file->getMimeType(),
                'is_public' => true,
                'sort_order' => $maxSort + $i + 1,
            ]);
            $uploaded++;
        }

        if ($uploaded === 0) {
            return $this->storeResponse(
                $request,
                false,
                'Foto tidak tersimpan. Periksa format file (gambar) dan symlink storage.'
            );
        }

        $status = $uploaded === 1 ? '1 foto diunggah.' : "{$uploaded} foto diunggah.";

        if ($lastItem !== null) {
            WhatsAppBroadcastDispatcher::dispatch(
                WhatsAppBroadcastCatalog::TRIGGER_GALERI,
                WhatsAppBroadcastCatalog::replacementsFromGalleryItem($lastItem, $uploaded),
            );
        }

        return $this->storeResponse($request, true, $status);
    }

    private function storeResponse(Request $request, bool $ok, string $message): RedirectResponse|JsonResponse
    {
        if ($request->expectsJson()) {
            if ($ok) {
                return response()->json([
                    'ok' => true,
                    'message' => $message,
                    'redirect' => route('dashboard.galeri.index'),
                ]);
            }

            return response()->json(['message' => $message], 422);
        }

        if ($ok) {
            return redirect()->route('dashboard.galeri.index')->with('status', $message);
        }

        return redirect()->route('dashboard.galeri.index')->withErrors(['files' => $message]);
    }

    public function update(Request $request, GalleryItem $galleryItem): RedirectResponse
    {
        if (! GalleryItem::tableReady()) {
            return redirect()
                ->route('dashboard.galeri.index')
                ->withErrors(['caption' => 'Tabel galeri belum ada. Jalankan di server: php artisan migrate --force']);
        }

        $data = $request->validate([
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        $galleryItem->update([
            'caption' => $data['caption'] !== null && $data['caption'] !== ''
                ? $data['caption']
                : null,
        ]);

        return redirect()
            ->route('dashboard.galeri.index')
            ->with('status', 'Nama foto diperbarui.');
    }

    public function destroy(GalleryItem $galleryItem): RedirectResponse
    {
        if (! GalleryItem::tableReady()) {
            return redirect()
                ->route('dashboard.galeri.index')
                ->withErrors(['files' => 'Tabel galeri belum ada. Jalankan di server: php artisan migrate --force']);
        }

        $galleryItem->deleteStoredFile();
        $galleryItem->delete();

        return redirect()->route('dashboard.galeri.index')->with('status', 'Foto dihapus dari galeri.');
    }
}
