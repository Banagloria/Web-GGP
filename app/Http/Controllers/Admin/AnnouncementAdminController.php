<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
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

        Announcement::query()->create($data);

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
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string', 'max:50000'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['required', 'in:0,1'],
        ]);
        $data['is_published'] = (bool) (int) $data['is_published'];

        return $data;
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
