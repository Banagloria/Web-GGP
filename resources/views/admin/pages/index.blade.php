@extends('layouts.admin')

@section('title', 'Halaman')

@section('content')
    <h1 class="mb-8 text-xl font-bold text-church-fg sm:mb-10 sm:text-2xl">Halaman konten</h1>
    <ul class="public-card-hover divide-y divide-slate-200 rounded-lg border border-white/10 bg-church-card">
        @foreach ($entries as $row)
            <li class="flex flex-col gap-4 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <span class="min-w-0 break-words font-medium">
                    <span class="text-church-gold/90">{{ $row['n'] }}.</span>
                    {{ $row['label'] }}
                    <span class="text-slate-400 text-sm"> {{ $row['path'] }}</span>
                </span>
                @include('admin.partials.table-actions', [
                    'editUrl' => route('dashboard.halaman.cms.edit', $row['key']),
                    'extraClass' => 'shrink-0 self-start sm:self-auto',
                ])
            </li>
        @endforeach
    </ul>
@endsection
