<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

trait RespondsToAdminModal
{
    protected function adminModalFinished(
        Request $request,
        string $message,
        RedirectResponse $redirect
    ): RedirectResponse|Response {
        if (! $request->boolean('modal')) {
            return $redirect;
        }

        return response()->view('admin.partials.modal-finished', [
            'message' => $message,
        ]);
    }
}
