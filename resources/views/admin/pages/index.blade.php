@extends('layouts.admin')

@section('title', 'Setting')

@section('content')
    <h1 class="mb-8 text-xl font-bold text-church-fg sm:mb-10 sm:text-2xl">Setting</h1>

    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300">
            {{ $errors->first() }}
        </div>
    @endif

    <ul class="public-card-hover divide-y divide-slate-200 rounded-lg border border-white/10 bg-church-card">
        @foreach ($entries as $row)
            <li class="flex flex-col gap-4 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <span class="min-w-0 break-words font-medium">
                    <span class="text-church-gold/90">{{ $row['n'] }}.</span>
                    {{ $row['label'] }}
                    <span class="text-slate-400 text-sm"> {{ $row['path'] }}</span>
                </span>
                @include('admin.partials.table-actions', [
                    'editUrl' => isset($row['route'])
                        ? route($row['route'])
                        : route('dashboard.setting.cms.edit', $row['key']),
                    'extraClass' => 'shrink-0 self-start sm:self-auto',
                ])
            </li>
        @endforeach
    </ul>
@endsection
