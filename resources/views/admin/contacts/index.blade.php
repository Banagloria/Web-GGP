@extends('layouts.admin')

@section('title', 'Pesan Kontak')

@section('content')
    <div class="mb-6 flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
        <h1 class="text-xl font-bold text-church-fg sm:text-2xl">Pesan Kontak</h1>
        <div class="flex flex-wrap gap-2 text-sm">
 <a href="{{ route('dashboard.kontak.index') }}" class="public-btn-hover px-3 py-1.5 rounded-md border {{ !request('filter') ? 'bg-church-navy-mid text-white border-church-navy-mid' : 'border-slate-300' }}">Semua</a>
 <a href="{{ route('dashboard.kontak.index', ['filter' => 'unread']) }}" class="public-btn-hover px-3 py-1.5 rounded-md border {{ request('filter')==='unread' ? 'bg-church-navy-mid text-white border-church-navy-mid' : 'border-slate-300' }}">Belum dibaca</a>
        </div>
    </div>
    <div class="admin-data-table-wrap public-card-hover overflow-x-auto rounded-lg border border-white/10 bg-church-card">
        <table class="min-w-full text-sm">
            <thead><tr class="bg-church-navy-mid text-white text-left">
                <th class="px-4 py-3 sm:px-5">Nama</th>
                <th class="px-4 py-3 sm:px-5">Subjek</th>
                <th class="px-4 py-3 sm:px-5">Tanggal</th>
                <th class="w-24 px-4 py-3 sm:px-5">Aksi</th>
            </tr></thead>
            <tbody>
                @forelse ($items as $row)
                    <tr class="border-t border-white/10 {{ $loop->even ? 'bg-admin-surface-zebra' : '' }}">
                        <td class="px-4 py-3 sm:px-5 font-medium">{{ $row->name }} @if(!$row->read_at)<span class="text-xs text-red-600">Baru</span>@endif</td>
                        <td class="px-4 py-3 sm:px-5">{{ $row->subject }}</td>
                        <td class="px-4 py-3 sm:px-5">{{ $row->created_at->timezone(config('app.timezone'))->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 sm:px-5">
                            @include('admin.partials.table-actions', [
                                'showUrl' => route('dashboard.kontak.show', $row),
                                'showLabel' => 'Buka pesan',
                            ])
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-10 text-center text-slate-500 sm:px-5">Tidak ada pesan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4 overflow-x-auto pb-2">{{ $items->links() }}</div>
@endsection
