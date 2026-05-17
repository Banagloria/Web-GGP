<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\CmsPageService;
use App\Services\RegistrationSubmissionService;
use App\Support\PendaftaranCardCms;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function index(): View
    {
        $cms = CmsPageService::merged('pendaftaran');

        return view('public.registrations.index', compact('cms'));
    }

    public function show(string $slug): View
    {
        $cms = CmsPageService::merged('pendaftaran');
        $resolved = PendaftaranCardCms::resolveBySlug($cms, $slug);
        abort_if($resolved === null, 404);

        return view('public.registrations.show', [
            'cms' => $cms,
            'slug' => $slug,
            'cardKey' => $resolved['cardKey'],
            'detail' => $resolved['detail'],
            'iconPrefix' => PendaftaranCardCms::iconPrefixForCardKey($resolved['cardKey']),
        ]);
    }

    public function store(Request $request, string $slug): RedirectResponse
    {
        $cms = CmsPageService::merged('pendaftaran');
        $resolved = PendaftaranCardCms::resolveBySlug($cms, $slug);
        abort_if($resolved === null, 404);

        RegistrationSubmissionService::validateAndStore($request, $slug, $cms);

        $title = (string) ($resolved['detail']['title'] ?? 'Pendaftaran');

        return redirect()
            ->route('pendaftaran.index')
            ->with('status', $title.' berhasil dikirim.');
    }

    /** @deprecated Legacy route aliases */
    public function congregation(): View
    {
        return $this->show('jemaat');
    }

    /** @deprecated */
    public function baptism(): View
    {
        return $this->show('baptisan');
    }

    /** @deprecated */
    public function marriage(): View
    {
        return $this->show('pernikahan');
    }

    /** @deprecated */
    public function storeCongregation(Request $request): RedirectResponse
    {
        return $this->store($request, 'jemaat');
    }

    /** @deprecated */
    public function storeBaptism(Request $request): RedirectResponse
    {
        return $this->store($request, 'baptisan');
    }

    /** @deprecated */
    public function storeMarriage(Request $request): RedirectResponse
    {
        return $this->store($request, 'pernikahan');
    }
}
