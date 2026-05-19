<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserAccountController extends Controller
{
    public function index(): View
    {
        $items = User::query()->panelUsers()->orderBy('name')->paginate(15);

        return view('admin.users.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request, isCreate: true);

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'],
        ];

        if (User::phoneColumnReady()) {
            $payload['phone'] = $data['phone'];
        }

        User::query()->create($payload);

        return redirect()
            ->route('dashboard.akun.index')
            ->with('status', 'Akun pengurus ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validated($request, isCreate: false, user: $user);

        if ($user->isSuperAdmin() && $data['role'] !== User::ROLE_SUPER_ADMIN && $user->isLastSuperAdmin()) {
            return redirect()
                ->route('dashboard.akun.index')
                ->withErrors(['akun' => 'Tidak dapat mengubah peran super admin terakhir.']);
        }

        $user->name = $data['name'];
        $user->email = $data['email'];
        if (User::phoneColumnReady()) {
            $user->phone = $data['phone'] ?? null;
        }
        $user->role = $data['role'];

        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();

        return redirect()
            ->route('dashboard.akun.index')
            ->with('status', 'Akun pengurus diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()?->id === $user->id) {
            return redirect()
                ->route('dashboard.akun.index')
                ->withErrors(['akun' => 'Anda tidak dapat menghapus akun yang sedang digunakan.']);
        }

        if ($user->isLastSuperAdmin()) {
            return redirect()
                ->route('dashboard.akun.index')
                ->withErrors(['akun' => 'Tidak dapat menghapus super admin terakhir.']);
        }

        $user->delete();

        return redirect()
            ->route('dashboard.akun.index')
            ->with('status', 'Akun pengurus dihapus.');
    }

    /**
     * @return array{name: string, email: string, phone?: string|null, role: string, password?: string}
     */
    private function validated(Request $request, bool $isCreate, ?User $user = null): array
    {
        $passwordRules = $isCreate
            ? ['required', 'confirmed', Password::defaults()]
            : ['nullable', 'confirmed', Password::defaults()];

        $phoneRules = $isCreate
            ? ['required', 'string', 'max:50']
            : ['nullable', 'string', 'max:50'];

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'phone' => $phoneRules,
            'role' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN])],
            'password' => $passwordRules,
        ]);

        if (array_key_exists('phone', $data)) {
            $data['phone'] = trim((string) $data['phone']) !== '' ? trim((string) $data['phone']) : null;
        }

        if (! $isCreate && empty($data['password'])) {
            unset($data['password']);
        }

        return $data;
    }
}
