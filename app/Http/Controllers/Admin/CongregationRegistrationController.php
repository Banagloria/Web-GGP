<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CongregationRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CongregationRegistrationController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = (int) $request->query('per_page', 10);
        if (! in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $query = CongregationRegistration::query()->orderByDesc('id');

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where('full_name', 'like', '%'.$search.'%');
        }

        if (($status = $request->query('status')) && $status !== 'semua' && $status !== '') {
            $query->where('status', $status);
        }

        $items = $query->paginate($perPage)->withQueryString();

        return view('admin.congregations.index', compact('items'));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $query = CongregationRegistration::query()->orderByDesc('id');

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where('full_name', 'like', '%'.$search.'%');
        }

        if (($status = $request->query('status')) && $status !== 'semua' && $status !== '') {
            $query->where('status', $status);
        }

        $filename = 'pendaftaran-jemaat-'.now()->format('Y-m-d_His').'.csv';

        return response()->streamDownload(function () use ($query): void {
            $out = fopen('php://output', 'w');
            if ($out === false) {
                return;
            }
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['ID', 'Nama lengkap', 'Tanggal lahir', 'Tempat lahir', 'Jenis kelamin', 'Alamat', 'Telepon', 'Email', 'Status', 'Catatan']);
            foreach ($query->cursor() as $r) {
                fputcsv($out, [
                    $r->id,
                    $r->full_name,
                    $r->birth_date?->format('Y-m-d'),
                    $r->birth_place,
                    $r->gender,
                    $r->address,
                    $r->phone,
                    $r->email,
                    $r->status,
                    $r->notes,
                ]);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function create(): View
    {
        return view('admin.congregations.create', ['registration' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        CongregationRegistration::query()->create($data);

        return redirect()->route('dashboard.pendaftaran-jemaat.index')->with('status', 'Data jemaat ditambahkan.');
    }

    public function show(CongregationRegistration $congregation): View
    {
        return view('admin.congregations.show', ['registration' => $congregation]);
    }

    public function edit(CongregationRegistration $congregation): View
    {
        return view('admin.congregations.edit', ['registration' => $congregation]);
    }

    public function update(Request $request, CongregationRegistration $congregation): RedirectResponse
    {
        $congregation->update($this->validated($request));

        return redirect()->route('dashboard.pendaftaran-jemaat.index')->with('status', 'Data jemaat diperbarui.');
    }

    public function destroy(CongregationRegistration $congregation): RedirectResponse
    {
        $congregation->delete();

        return redirect()->route('dashboard.pendaftaran-jemaat.index')->with('status', 'Data jemaat dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'max:16'],
            'address' => ['nullable', 'string', 'max:2000'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'status' => ['required', 'string', 'max:32'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);
    }
}
