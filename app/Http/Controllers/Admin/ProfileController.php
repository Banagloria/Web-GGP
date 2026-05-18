<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\PublicCmsUrl;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        return view('admin.profile.edit', [
            'user' => $user,
            'profilePhotoReady' => User::profilePhotoColumnReady(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user()->fresh();

        $changingPassword = $request->filled('password');
        $uploadedPhotoUrl = null;

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'profile_photo_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
            'profile_photo_delete' => ['nullable', 'in:1'],
            'profile_photo_url_previous' => ['nullable', 'string', 'max:2000'],
        ];

        if ($changingPassword) {
            $rules['current_password'] = ['required', 'string'];
        }

        $data = $request->validate($rules);

        if ($changingPassword) {
            if (! Hash::check((string) $data['current_password'], (string) $user->password)) {
                throw ValidationException::withMessages([
                    'current_password' => __('Kata sandi saat ini tidak sesuai.'),
                ]);
            }
        }

        $user->name = $data['name'];

        if ($changingPassword) {
            $user->password = $data['password'];
        }

        $previousPhoto = trim((string) ($data['profile_photo_url_previous'] ?? ''));
        $deletePhoto = (($data['profile_photo_delete'] ?? '') === '1');
        $photoColumnReady = User::profilePhotoColumnReady();

        if ($request->hasFile('profile_photo_file') || $deletePhoto) {
            if (! $photoColumnReady) {
                return redirect()
                    ->route('dashboard.profil-akun.edit')
                    ->withErrors([
                        'profile_photo_file' => 'Kolom foto profil belum ada di database. Jalankan di server: php artisan church:ensure-profile-photo-column',
                    ])
                    ->withInput($request->except('profile_photo_file', 'password', 'current_password'));
            }

            try {
                if ($request->hasFile('profile_photo_file')) {
                    PublicCmsUrl::deletePublicStorageFileIfUrlIsLocal($previousPhoto);
                    Storage::disk('public')->makeDirectory('admin/profile-photos');
                    $path = $request->file('profile_photo_file')->store('admin/profile-photos', 'public');

                    if (! $path || ! Storage::disk('public')->exists($path)) {
                        throw ValidationException::withMessages([
                            'profile_photo_file' => 'Foto tidak tersimpan. Periksa izin folder storage dan jalankan: php artisan storage:link',
                        ]);
                    }

                    $uploadedPhotoUrl = Storage::disk('public')->url($path);
                    $user->profile_photo_url = $uploadedPhotoUrl;
                } elseif ($deletePhoto) {
                    PublicCmsUrl::deletePublicStorageFileIfUrlIsLocal($previousPhoto);
                    $user->profile_photo_url = null;
                }
            } catch (ValidationException $e) {
                throw $e;
            } catch (Throwable $e) {
                report($e);

                return redirect()
                    ->route('dashboard.profil-akun.edit')
                    ->withErrors([
                        'profile_photo_file' => 'Gagal mengunggah foto. Periksa storage publik dan symlink /storage.',
                    ])
                    ->withInput($request->except('profile_photo_file', 'password', 'current_password'));
            }
        }

        $user->save();

        if ($uploadedPhotoUrl !== null) {
            $user->refresh();
            if (trim((string) ($user->profile_photo_url ?? '')) === '') {
                User::query()->whereKey($user->id)->update(['profile_photo_url' => $uploadedPhotoUrl]);
                $user->refresh();
            }
        }

        $status = 'Profil berhasil diperbarui.';
        if ($deletePhoto && ! $request->hasFile('profile_photo_file')) {
            $status = 'Foto profil berhasil dihapus.';
        } elseif ($request->hasFile('profile_photo_file')) {
            $status = 'Foto profil berhasil diperbarui.';
        }

        if ($uploadedPhotoUrl !== null || $deletePhoto) {
            auth()->setUser($user->fresh());
        }

        return redirect()
            ->route('dashboard.profil-akun.edit')
            ->with('status', $status);
    }
}
