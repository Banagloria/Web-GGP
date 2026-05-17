<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\User;
use App\Support\ChurchDemoAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        $churchNameLine2 = config('app.name');
        $siteLogoUrl = '';
        try {
            $churchNameLine2 = SiteSetting::get('church_name_line2', $churchNameLine2);
            $siteLogoUrl = (string) (SiteSetting::get('site_logo_url', '') ?? '');
        } catch (Throwable) {
            //
        }

        return view('auth.login', [
            'churchNameLine2' => $churchNameLine2,
            'siteLogoUrl' => $siteLogoUrl,
        ]);
    }

    public function store(Request $request)
    {
        $request->merge([
            'email' => Str::lower(trim((string) $request->input('email', ''))),
        ]);

        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (ChurchDemoAdmin::shouldAutoProvision() && ChurchDemoAdmin::credentialsMatchDemo($credentials)) {
            try {
                if (! User::query()->where('email', ChurchDemoAdmin::DEMO_EMAIL)->exists()) {
                    ChurchDemoAdmin::provision();
                }
            } catch (\Throwable) {
                // DB belum siap atau migrasi belum dijalankan
            }
        }

        $remember = $request->boolean('remember');
        $loggedIn = Auth::attempt($credentials, $remember);

        if (! $loggedIn && ChurchDemoAdmin::shouldAutoProvision() && ChurchDemoAdmin::credentialsMatchDemo($credentials)) {
            try {
                ChurchDemoAdmin::provision();
            } catch (\Throwable) {
                // DB belum siap atau migrasi belum dijalankan
            }
            $loggedIn = Auth::attempt($credentials, $remember);
        }

        if (! $loggedIn) {
            if (config('app.debug')) {
                $request->session()->flash(
                    'login_debug_hint',
                    'Mode debug: pastikan akun admin ada di database yang dipakai aplikasi ini (cek .env). Contoh: php artisan db:seed — atau php artisan church:ensure-admin'
                );
            }

            throw ValidationException::withMessages([
                'email' => __('Kredensial tidak cocok.'),
            ]);
        }

        $user = Auth::user();
        if (! $user instanceof User || ! $user->isAdmin()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => __('Akun ini tidak memiliki akses panel admin.'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard.index'));
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
