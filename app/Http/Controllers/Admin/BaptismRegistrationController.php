<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BaptismRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BaptismRegistrationController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = (int) $request->query('per_page', 10);
        if (! in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $query = BaptismRegistration::query()->orderByDesc('id');

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where('full_name', 'like', '%'.$search.'%');
        }

        if (($status = $request->query('status')) && $status !== 'semua' && $status !== '') {
            $query->where('status', $status);
        }

        $items = $query->paginate($perPage)->withQueryString();

        return view('admin.baptisms.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.baptisms.create', ['registration' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        BaptismRegistration::query()->create($data);

        return redirect()->route('dashboard.pendaftaran-baptisan.index')->with('status', 'Data baptisan ditambahkan.');
    }

    public function show(BaptismRegistration $baptism): View
    {
        return view('admin.baptisms.show', ['registration' => $baptism]);
    }

    public function edit(BaptismRegistration $baptism): View
    {
        return view('admin.baptisms.edit', ['registration' => $baptism]);
    }

    public function update(Request $request, BaptismRegistration $baptism): RedirectResponse
    {
        $baptism->update($this->validated($request));

        return redirect()->route('dashboard.pendaftaran-baptisan.index')->with('status', 'Data baptisan diperbarui.');
    }

    public function destroy(BaptismRegistration $baptism): RedirectResponse
    {
        $baptism->delete();

        return redirect()->route('dashboard.pendaftaran-baptisan.index')->with('status', 'Data baptisan dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'age' => ['nullable', 'integer', 'min:0', 'max:150'],
            'gender' => ['nullable', 'string', 'max:16'],
            'baptism_date' => ['nullable', 'date'],
            'status' => ['required', 'string', 'max:32'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);
    }
}
