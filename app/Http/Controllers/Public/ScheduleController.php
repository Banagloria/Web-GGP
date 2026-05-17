<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\WorshipSchedule;
use App\Services\CmsPageService;
use App\Services\WorshipSchedulePartitionService;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Throwable;

class ScheduleController extends Controller
{
    public function index(): View
    {
        try {
            $all = WorshipSchedule::query()->where('is_active', true)->get();
        } catch (Throwable) {
            $all = new Collection;
        }

        $cms = CmsPageService::merged('jadwal');
        $partitioned = WorshipSchedulePartitionService::partition($all);

        return view('public.schedule', [
            'cms' => $cms,
            'upcoming' => $partitioned['upcoming'],
            'completed' => $partitioned['completed'],
        ]);
    }
}
