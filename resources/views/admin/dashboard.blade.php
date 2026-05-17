@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    @php
        $registrationStats = $registrationStats ?? [];

        $activityStats = [
            [
                'value' => $announcementDrafts,
                'label' => 'Pengumuman draft',
                'hint' => 'Belum dipublikasikan',
                'icon' => 'bell',
                'href' => route('dashboard.pengumuman.index'),
                'valueClass' => 'text-orange-400',
                'iconWrapClass' => 'bg-orange-500/15 ring-orange-500/25',
                'iconClass' => 'size-5 text-orange-400 sm:size-6',
            ],
            [
                'value' => $unreadContacts,
                'label' => 'Pesan belum dibaca',
                'hint' => 'Kotak masuk kontak',
                'icon' => 'envelope',
                'href' => route('dashboard.kontak.index', ['filter' => 'unread']),
                'valueClass' => 'text-red-400',
                'iconWrapClass' => 'bg-red-500/15 ring-red-500/25',
                'iconClass' => 'size-5 text-red-400 sm:size-6',
            ],
        ];
    @endphp

    <div class="admin-dashboard-page mx-auto flex w-full min-w-0 max-w-6xl flex-col gap-4 pb-2 sm:gap-6 sm:pb-4 md:gap-8 lg:gap-10">
        <header class="min-w-0 shrink-0 space-y-1">
            <h1 class="text-lg font-bold tracking-tight text-church-fg sm:text-xl md:text-2xl">Dashboard</h1>
            <p class="max-w-2xl text-pretty text-xs leading-relaxed text-slate-400 sm:text-sm">Ringkasan pendaftaran dan aktivitas yang perlu ditinjau.</p>
        </header>

        <section class="flex min-w-0 flex-col gap-3 sm:gap-4" aria-labelledby="dashboard-registrations-heading">
            <h2 id="dashboard-registrations-heading" class="shrink-0 text-xs font-semibold uppercase tracking-wider text-slate-500">
                Pendaftaran
            </h2>
            <div class="grid grid-cols-1 gap-3 min-[480px]:grid-cols-2 sm:gap-4 lg:grid-cols-3 lg:gap-5">
                @foreach ($registrationStats as $stat)
                    @include('admin.partials.dashboard-stat-card', ['stat' => $stat])
                @endforeach
            </div>
        </section>

        <section class="flex min-w-0 flex-col gap-3 sm:gap-4" aria-labelledby="dashboard-activity-heading">
            <h2 id="dashboard-activity-heading" class="shrink-0 text-xs font-semibold uppercase tracking-wider text-slate-500">
                Konten &amp; pesan
            </h2>
            <div class="grid grid-cols-1 gap-3 min-[480px]:grid-cols-2 sm:gap-4">
                @foreach ($activityStats as $stat)
                    @include('admin.partials.dashboard-stat-card', ['stat' => $stat])
                @endforeach
            </div>
        </section>
    </div>
@endsection
