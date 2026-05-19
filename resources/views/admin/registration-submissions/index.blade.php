@extends('layouts.admin')

@section('title', $title)

@section('content')
    @php
        $listKind = $listKind ?? \App\Http\Controllers\Admin\RegistrationSubmissionController::LIST_PENDING;
        $routes = $routes ?? \App\Http\Controllers\Admin\RegistrationSubmissionController::routesForSlug($slug, $listKind);
        $routeParams = ['slug' => $slug];
        $isPendingList = $listKind === \App\Http\Controllers\Admin\RegistrationSubmissionController::LIST_PENDING;
        $defaultStatus = $defaultStatus ?? ($isPendingList ? 'submitted' : 'active');
    @endphp
    <div class="w-full min-w-0 max-w-full">
        <h1 class="mb-4 text-xl font-bold text-church-fg sm:text-2xl">{{ $title }}</h1>

        @include('admin.partials.registration-list-toolbar', [
            'slug' => $slug,
            'exportExcelUrl' => route($routes['exportCsv'], array_merge($routeParams, request()->query())),
            'exportPdfUrl' => route($routes['exportPdf'], array_merge($routeParams, request()->query())),
            'defaultStatus' => $defaultStatus,
        ])

        <div class="admin-data-table-wrap public-card-hover overflow-x-auto rounded-xl border border-white/10 bg-church-card">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-church-navy-mid text-left text-white">
                        <th class="whitespace-nowrap px-3 py-3 font-semibold sm:px-4">No</th>
                        @foreach ($columns as $col)
                            <th class="whitespace-nowrap px-3 py-3 font-semibold sm:px-4">{{ $col['label'] }}</th>
                        @endforeach
                        <th class="whitespace-nowrap px-3 py-3 font-semibold sm:px-4">Status</th>
                        <th class="min-w-[4.75rem] whitespace-nowrap px-3 py-3 font-semibold sm:min-w-[5.25rem] sm:px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $row)
                        @php($files = is_array($row->files) ? $row->files : [])
                        <tr class="{{ $loop->even ? 'bg-admin-surface-zebra' : 'bg-church-card' }} border-t border-white/10">
                            <td class="whitespace-nowrap px-3 py-2.5 sm:px-4">{{ $items->firstItem() + $loop->index }}</td>
                            @foreach ($columns as $col)
                                <td class="w-full max-w-none px-3 py-2.5 sm:max-w-[14rem] sm:px-4">
                                    @if (isset($files[$col['name']]))
                                        <a href="{{ $files[$col['name']] }}" target="_blank" rel="noopener" class="text-church-gold hover:underline">Berkas</a>
                                    @else
                                        <span class="block break-words sm:truncate" title="{{ \App\Services\RegistrationSubmissionService::displayCellValue($row, $col) }}">
                                            {{ \App\Services\RegistrationSubmissionService::displayCellValue($row, $col) ?: '—' }}
                                        </span>
                                    @endif
                                </td>
                            @endforeach
                            <td class="whitespace-nowrap px-3 py-2.5 sm:px-4">
                                @if ($row->status === 'active')
                                    <span class="inline-flex rounded-full bg-church-gold/20 px-2 py-0.5 text-xs font-semibold text-church-gold">Diterima</span>
                                @elseif ($row->status === 'submitted')
                                    <span class="inline-flex rounded-full bg-amber-500/15 px-2 py-0.5 text-xs font-semibold text-amber-300">Diajukan</span>
                                @else
                                    <span class="inline-flex rounded-full bg-white/10 px-2 py-0.5 text-xs font-semibold text-slate-300">
                                        {{ \App\Services\RegistrationSubmissionService::statusLabel($row->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 py-2.5 sm:px-4">
                                @include('admin.partials.registration-submission-actions', [
                                    'editUrl' => route($routes['edit'], array_merge($routeParams, [$row])),
                                    'showUrl' => route($routes['show'], array_merge($routeParams, [$row])),
                                    'deleteUrl' => $isPendingList ? null : route($routes['destroy'], array_merge($routeParams, [$row])),
                                    'acceptUrl' => $isPendingList && isset($routes['accept']) ? route($routes['accept'], array_merge($routeParams, [$row])) : null,
                                    'rejectUrl' => $isPendingList && isset($routes['reject']) ? route($routes['reject'], array_merge($routeParams, [$row])) : null,
                                    'canReview' => $isPendingList && $row->status === 'submitted',
                                    'deleteMessage' => 'Data pendaftaran ini akan dihapus permanen.',
                                    'confirmPhone' => \App\Services\RegistrationSubmissionService::phoneFromSubmission($row, $columns),
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) + 3 }}" class="px-4 py-10 text-center text-slate-400">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 overflow-x-auto pb-2">{{ $items->links() }}</div>
    </div>
@endsection
