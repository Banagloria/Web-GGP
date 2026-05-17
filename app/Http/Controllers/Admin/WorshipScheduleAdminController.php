<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorshipSchedule;
use App\Services\CmsPageService;
use App\Services\WorshipSchedulePartitionService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class WorshipScheduleAdminController extends Controller
{
    public function index(): View
    {
        $all = WorshipSchedule::query()->orderByDesc('created_at')->get();
        $cms = CmsPageService::merged('jadwal');
        $partitioned = WorshipSchedulePartitionService::partition($all, activeOnly: false);

        return view('admin.schedules.index', [
            'cms' => $cms,
            'upcoming' => $partitioned['upcoming'],
            'completed' => $partitioned['completed'],
        ]);
    }

    public function create(): View
    {
        $cms = CmsPageService::merged('jadwal');

        return view('admin.schedules.create', [
            'item' => null,
            'cms' => $cms,
            'middleLabels' => WorshipSchedulePartitionService::middleLabelsFromCms($cms),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $cms = CmsPageService::merged('jadwal');
        WorshipSchedule::query()->create($this->validated($request, $cms));

        return redirect()->route('dashboard.jadwal-ibadah.index')->with('status', 'Jadwal ditambahkan.');
    }

    public function edit(WorshipSchedule $schedule): View
    {
        $cms = CmsPageService::merged('jadwal');

        return view('admin.schedules.edit', [
            'item' => $schedule,
            'cms' => $cms,
            'middleLabels' => WorshipSchedulePartitionService::middleLabelsFromCms($cms),
        ]);
    }

    public function update(Request $request, WorshipSchedule $schedule): RedirectResponse
    {
        $cms = CmsPageService::merged('jadwal');
        $schedule->update($this->validated($request, $cms, $schedule));

        return redirect()->route('dashboard.jadwal-ibadah.index')->with('status', 'Jadwal diperbarui.');
    }

    public function destroy(WorshipSchedule $schedule): RedirectResponse
    {
        $schedule->delete();

        return redirect()->route('dashboard.jadwal-ibadah.index')->with('status', 'Jadwal dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, array $cms, ?WorshipSchedule $existing = null): array
    {
        $count = WorshipSchedulePartitionService::dynamicColumnCount($cms);
        $rules = [
            'schedule_date' => ['required', 'date'],
            'starts_at' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'ends_at' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'column_values' => ['required', 'array', 'size:'.$count],
            'column_values.*' => ['nullable', 'string', 'max:500'],
        ];

        $validated = $request->validate($rules);

        $startsAt = $validated['starts_at'];
        $endsAt = $validated['ends_at'];
        if (strlen($startsAt) === 5) {
            $startsAt .= ':00';
        }
        if (strlen($endsAt) === 5) {
            $endsAt .= ':00';
        }
        if (strcmp(substr($startsAt, 0, 5), substr($endsAt, 0, 5)) >= 0) {
            throw ValidationException::withMessages([
                'ends_at' => 'Jam selesai harus setelah jam mulai.',
            ]);
        }

        $columnData = WorshipSchedulePartitionService::persistColumnValues(
            $validated['column_values'],
            $existing
        );

        $scheduleDate = Carbon::parse($validated['schedule_date']);

        return array_merge($columnData, [
            'schedule_date' => $scheduleDate->toDateString(),
            'day_of_week' => $scheduleDate->dayOfWeek,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'is_active' => $existing?->is_active ?? true,
            'sort_order' => (int) ($existing?->sort_order ?? 0),
        ]);
    }
}
