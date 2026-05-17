<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\CmsPageService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $cms = CmsPageService::merged('beranda');

        return view('public.home', compact('cms'));
    }
}
