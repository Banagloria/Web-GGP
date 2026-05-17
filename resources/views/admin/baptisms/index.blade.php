@extends('layouts.admin')

@section('title', 'Pendaftaran Baptisan')

@section('content')
    <div class="w-full min-w-0 max-w-full">
    <h1 class="mb-4 text-xl font-bold text-church-fg sm:text-2xl">Pendaftaran Baptisan</h1>

    <div class="mb-4 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div class="flex min-w-0 flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
            @include('admin.partials.btn', [
                'href' => route('dashboard.pendaftaran-baptisan.create'),
                'variant' => 'primary',
                'icon' => 'fa-solid fa-plus',
                'label' => 'Tambah Baptisan',
                'extraClass' => 'w-full sm:w-auto',
            ])
            <form method="get" class="flex flex-wrap items-center gap-2 text-sm">
                <label for="per_page" class="flex shrink-0 items-center gap-2 text-slate-400">
                    <i class="fa-solid fa-list-ol shrink-0 text-church-gold/80" aria-hidden="true"></i>
                    Tampilkan
                </label>
                <select id="per_page" name="per_page" onchange="this.form.submit()" class="min-w-0 shrink-0 rounded-md border-slate-300 text-sm">
                    @foreach ([10,25,50,100] as $n)
                        <option value="{{ $n }}" @selected((int)request('per_page', 10) === $n)>{{ $n }}</option>
                    @endforeach
                </select>
                <span class="text-slate-400">entri</span>
                <input type="hidden" name="q" value="{{ request('q') }}">
                <input type="hidden" name="status" value="{{ request('status') }}">
            </form>
        </div>
        <form method="get" class="flex min-w-0 flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-center">
            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari Baptisan..." class="w-full min-w-0 rounded-md border-slate-300 text-sm sm:min-w-[12rem] sm:max-w-xs sm:flex-1">
            <select name="status" class="w-full min-w-0 rounded-md border-slate-300 text-sm sm:w-auto sm:shrink-0" onchange="this.form.submit()">
                <option value="semua" @selected(request('status') === null || request('status') === 'semua' || request('status') === '')>Semua</option>
                @foreach (['submitted'=>'Diajukan','active'=>'Aktif','rejected'=>'Ditolak','archived'=>'Arsip'] as $val => $lab)
                    <option value="{{ $val }}" @selected(request('status') === $val)>{{ $lab }}</option>
                @endforeach
            </select>
            @include('admin.partials.btn', [
                'type' => 'submit',
                'variant' => 'neutral',
                'size' => 'sm',
                'label' => 'Cari',
                'extraClass' => 'w-full shrink-0 sm:w-auto',
            ])
        </form>
    </div>

    <div class="admin-data-table-wrap public-card-hover overflow-x-auto rounded-lg border border-white/10 bg-church-card">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-church-navy-mid text-white text-left">
                    <th class="px-3 py-3 font-semibold">No</th>
                    <th class="px-3 py-3 font-semibold">Nama Lengkap</th>
                    <th class="px-3 py-3 font-semibold">Usia</th>
                    <th class="px-3 py-3 font-semibold">Jenis Kelamin</th>
                    <th class="px-3 py-3 font-semibold">Tanggal Baptis</th>
                    <th class="px-3 py-3 font-semibold">Status</th>
                    <th class="px-3 py-3 font-semibold w-44">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $row)
                    <tr class="{{ $loop->even ? 'bg-admin-surface-zebra' : 'bg-church-card' }} border-t border-white/10">
                        <td class="px-3 py-2">{{ $items->firstItem() + $loop->index }}</td>
                        <td class="px-3 py-2 font-medium">{{ $row->full_name }}</td>
                        <td class="px-3 py-2">{{ $row->age }}</td>
                        <td class="px-3 py-2">{{ $row->gender }}</td>
                        <td class="px-3 py-2">{{ $row->baptism_date?->timezone(config('app.timezone'))->format('d/m/Y') }}</td>
                        <td class="px-3 py-2">
                            @if($row->status === 'active')
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-church-gold/20 text-church-gold">Aktif</span>
                            @else
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-white/10 text-slate-300">{{ $row->status }}</span>
                            @endif
                        </td>
                        <td class="px-3 py-2">
                            @include('admin.partials.table-actions', [
                                'showUrl' => route('dashboard.pendaftaran-baptisan.show', $row),
                                'editUrl' => route('dashboard.pendaftaran-baptisan.edit', $row),
                                'deleteUrl' => route('dashboard.pendaftaran-baptisan.destroy', $row),
                                'deleteMessage' => 'Data baptisan ini akan dihapus permanen.',
                            ])
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-3 py-8 text-center text-slate-500">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 overflow-x-auto pb-2">{{ $items->links() }}</div>
    </div>
@endsection
