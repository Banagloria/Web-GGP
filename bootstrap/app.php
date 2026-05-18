<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function (Application $app): void {
            // Tanpa middleware `web` (tanpa sesi/CSRF): uji apakah inti routing + PHP OK di produksi.
            Route::get('/__ping', fn () => response('ok', 200, [
                'Content-Type' => 'text/plain; charset=UTF-8',
            ]));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Di belakang nginx/ssl terminasi: agar request()->secure() benar & URL route pakai https.
        $middleware->trustProxies(at: '*');

        $middleware->prependToGroup('web', \App\Http\Middleware\DeploySafeRuntimeMiddleware::class);

        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'super_admin' => \App\Http\Middleware\EnsureUserIsSuperAdmin::class,
        ]);
        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(fn () => route('dashboard.index'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
