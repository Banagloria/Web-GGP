<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarriageRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarriageRegistrationController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = (int) $request->query('per_page', 10);
        if (! in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $query = MarriageRegistration::query()->orderByDesc('id');

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('groom_name', 'like', '%'.$search.'%')
                    ->orWhere('bride_name', 'like', '%'.$search.'%');
            });
        }

        if (($status = $request->query('status')) && $status !== 'semua' && $status !== '') {
            $query->where('status', $status);
        }

        $items = $query->paginate($perPage)->withQueryString();

        return view('admin.marriages.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.marriages.create', ['registration' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        MarriageRegistration::query()->create($data);

        return redirect()->route('dashboard.pendaftaran-pernikahan.index')->with('status', 'Data pernikahan ditambahkan.');
    }

    public function show(MarriageRegistration $marriage): View
    {
        return view('admin.marriages.show', ['registration' => $marriage]);
    }

    public function edit(MarriageRegistration $marriage): View
    {
        return view('admin.marriages.edit', ['registration' => $marriage]);
    }

    public function update(Request $request, MarriageRegistration $marriage): RedirectResponse
    {
        $marriage->update($this->validated($request));

        return redirect()->route('dashboard.pendaftaran-pernikahan.index')->with('status', 'Data pernikahan diperbarui.');
    }

    public function destroy(MarriageRegistration $marriage): RedirectResponse
    {
        $marriage->delete();

        return redirect()->route('dashboard.pendaftaran-pernikahan.index')->with('status', 'Data pernikahan dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'groom_name' => ['required', 'string', 'max:255'],
            'bride_name' => ['required', 'string', 'max:255'],
            'wedding_date' => ['nullable', 'date'],
            'phone' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'string', 'max:32'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);
    }
}
