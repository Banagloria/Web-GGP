@extends('layouts.admin')

@section('title', 'Jadwal Ibadah')

@section('content')
    
    <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <h1 class="text-xl font-bold text-church-fg sm:text-2xl">Jadwal Ibadah</h1>
        @include('admin.partials.btn', [
            'href' => route('dashboard.jadwal-ibadah.create'),
            'variant' => 'primary',
            'icon' => 'fa-solid fa-plus',
            'label' => 'Tambah',
            'extraClass' => 'w-full sm:w-auto',
        ])
    </div>

    @include('partials.schedule-section', [
        'sectionKey' => 'admin-jadwal-upcoming',
        'sectionTitle' => $cms['section_upcoming_title'] ?? 'Jadwal mendatang',
        'headers' => \App\Services\WorshipSchedulePartitionService::headersUpcoming($cms),
        'rows' => $upcoming,
        'cms' => $cms,
        'showActions' => true,
    ])

    @include('partials.schedule-section', [
        'sectionKey' => 'admin-jadwal-completed',
        'sectionTitle' => $cms['section_completed_title'] ?? 'Jadwal selesai',
        'headers' => \App\Services\WorshipSchedulePartitionService::headersCompleted($cms),
        'rows' => $completed,
        'cms' => $cms,
        'showActions' => true,
    ])
@endsection
