<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Services\WhatsAppBroadcastCatalog;
use App\Services\WhatsAppBroadcastDispatcher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AnnouncementAdminController extends Controller
{
    public function index(): View
    {
        $items = Announcement::query()->orderByDesc('id')->paginate(15);

        return view('admin.announcements.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['is_published'] = true;
        $data['published_at'] = now();

        $announcement = Announcement::query()->create($data);

        WhatsAppBroadcastDispatcher::dispatch(
            WhatsAppBroadcastCatalog::TRIGGER_PENGUMUMAN,
            WhatsAppBroadcastCatalog::replacementsFromAnnouncement($announcement),
        );

        return redirect()->route('dashboard.pengumuman.index')->with('status', 'Pengumuman ditambahkan.');
    }

    public function edit(Announcement $announcement): View
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $data = $this->validated($request);
        if ($announcement->title !== $data['title']) {
            $data['slug'] = $this->uniqueSlug($data['title'], $announcement->id);
        }
        $data['is_published'] = true;
        if ($announcement->published_at === null) {
            $data['published_at'] = now();
        }
        $announcement->update($data);

        return redirect()->route('dashboard.pengumuman.index')->with('status', 'Pengumuman diperbarui.');
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        $announcement->delete();

        return redirect()->route('dashboard.pengumuman.index')->with('status', 'Pengumuman dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string', 'max:50000'],
        ], [], [
            'title' => 'judul',
            'body' => 'isi',
        ]);
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'pengumuman';
        $slug = $base;
        $i = 1;
        while (Announcement::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }
}
