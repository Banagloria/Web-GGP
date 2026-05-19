@extends('layouts.admin')

@section('title', 'Manajemen Akun')

@section('content')
    <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <h1 class="text-xl font-bold text-church-fg sm:text-2xl">Manajemen akun</h1>
        @include('admin.partials.btn', [
            'href' => route('dashboard.akun.create'),
            'variant' => 'primary',
            'icon' => 'fa-solid fa-plus',
            'label' => 'Tambah admin',
            'extraClass' => 'w-full sm:w-auto',
        ])
    </div>

    @if ($errors->has('akun'))
        <p class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300">{{ $errors->first('akun') }}</p>
    @endif

    <div class="admin-data-table-wrap public-card-hover overflow-x-auto rounded-lg border border-white/10 bg-church-card">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-church-navy-mid text-left text-white">
                    <th class="px-3 py-2">Nama</th>
                    <th class="px-3 py-2">Email</th>
                    <th class="px-3 py-2">No. HP</th>
                    <th class="px-3 py-2">Peran</th>
                    <th class="px-3 py-2">Dibuat</th>
                    <th class="px-3 py-2 w-32">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $row)
                    <tr class="border-t border-white/10 {{ $loop->even ? 'bg-admin-surface-zebra' : '' }}">
                        <td class="px-3 py-2 font-medium">
                            {{ $row->name }}
                            @if (auth()->id() === $row->id)
                                <span class="ml-1 text-xs text-church-gold">(Anda)</span>
                            @endif
                        </td>
                        <td class="px-3 py-2">{{ $row->email }}</td>
                        <td class="px-3 py-2">{{ \App\Models\User::phoneColumnReady() ? ($row->phone ?: '—') : '—' }}</td>
                        <td class="px-3 py-2">{{ $row->roleLabel() }}</td>
                        <td class="px-3 py-2">{{ $row->created_at?->timezone(config('app.timezone'))->format('d/m/Y H:i') }}</td>
                        <td class="px-3 py-2">
                            @include('admin.partials.table-actions', [
                                'editUrl' => route('dashboard.akun.edit', $row),
                                'deleteUrl' => auth()->id() !== $row->id ? route('dashboard.akun.destroy', $row) : null,
                                'deleteTitle' => 'Hapus akun admin?',
                                'deleteMessage' => 'Akun ini tidak dapat dipulihkan setelah dihapus.',
                            ])
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4 overflow-x-auto pb-2">{{ $items->links() }}</div>
@endsection
