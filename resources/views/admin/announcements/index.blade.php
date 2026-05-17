@extends('layouts.admin')

@section('title', 'Pengumuman')

@section('content')
    <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <h1 class="text-xl font-bold text-church-fg sm:text-2xl">Pengumuman</h1>
        @include('admin.partials.btn', [
            'href' => route('dashboard.pengumuman.create'),
            'variant' => 'primary',
            'icon' => 'fa-solid fa-plus',
            'label' => 'Tambah',
            'extraClass' => 'w-full sm:w-auto',
        ])
    </div>
    <div class="admin-data-table-wrap public-card-hover overflow-x-auto rounded-lg border border-white/10 bg-church-card">
        <table class="min-w-full text-sm">
            <thead><tr class="bg-church-navy-mid text-white text-left">
                <th class="px-3 py-2">Judul</th>
                <th class="px-3 py-2">Tayang</th>
                <th class="px-3 py-2">Status</th>
                <th class="px-3 py-2 w-32">Aksi</th>
            </tr></thead>
            <tbody>
                @foreach ($items as $row)
                    <tr class="border-t border-white/10 {{ $loop->even ? 'bg-admin-surface-zebra' : '' }}">
                        <td class="px-3 py-2 font-medium">{{ $row->title }}</td>
                        <td class="px-3 py-2">{{ $row->published_at?->timezone(config('app.timezone'))->format('d/m/Y H:i') }}</td>
                        <td class="px-3 py-2">{{ $row->is_published ? 'Publish' : 'Draft' }}</td>
                        <td class="px-3 py-2">
                            @include('admin.partials.table-actions', [
                                'editUrl' => route('dashboard.pengumuman.edit', $row),
                                'deleteUrl' => route('dashboard.pengumuman.destroy', $row),
                                'deleteTitle' => 'Hapus pengumuman?',
                                'deleteMessage' => 'Pengumuman ini akan dihapus permanen.',
                            ])
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4 overflow-x-auto pb-2">{{ $items->links() }}</div>
@endsection
