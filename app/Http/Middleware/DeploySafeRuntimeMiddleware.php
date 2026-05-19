<?php

namespace App\Http\Middleware;

use App\Support\DeploySafeRuntime;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Dijalankan paling awal di grup web: ulangi relaksasi driver (setelah config cache, dll.).
 */
class DeploySafeRuntimeMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        DeploySafeRuntime::relaxDatabaseDriversIfNeeded();
        DeploySafeRuntime::ensureUserPhoneColumnIfNeeded();
        DeploySafeRuntime::ensureBladeCompiledPathWritable();

        return $next($request);
    }
}
