@extends(request()->boolean('modal') ? 'layouts.admin-embed' : 'layouts.admin')

@section('title', 'Detail Baptisan')

@section('content')
    <x-admin-show-page
        :back-href="route('dashboard.pendaftaran-baptisan.index')"
        back-label="Daftar baptisan"
        icon="fa-solid fa-water"
        title="Detail baptisan"
        :edit-url="route('dashboard.pendaftaran-baptisan.edit', $registration)"
    >
        <x-admin-detail-item label="Nama">{{ $registration->full_name }}</x-admin-detail-item>
        <x-admin-detail-item label="Usia">{{ $registration->age ?? '—' }}</x-admin-detail-item>
        <x-admin-detail-item label="Jenis kelamin">{{ $registration->gender ?: '—' }}</x-admin-detail-item>
        <x-admin-detail-item label="Tanggal baptis">{{ $registration->baptism_date?->timezone(config('app.timezone'))->format('d/m/Y') ?: '—' }}</x-admin-detail-item>
        <x-admin-detail-item label="Status">{{ $registration->status }}</x-admin-detail-item>
        <x-admin-detail-item label="Catatan" value-class="text-sm text-church-fg whitespace-pre-line font-normal">{{ $registration->notes ?: '—' }}</x-admin-detail-item>
    </x-admin-show-page>
@endsection
