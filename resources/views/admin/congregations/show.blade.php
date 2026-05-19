@extends(request()->boolean('modal') ? 'layouts.admin-embed' : 'layouts.admin')

@section('title', 'Detail Jemaat')

@section('content')
    <x-admin-show-page
        :back-href="route('dashboard.pendaftaran.index', ['slug' => 'jemaat'])"
        back-label="Daftar jemaat"
        icon="fa-solid fa-user"
        title="Detail jemaat"
        :edit-url="route('dashboard.pendaftaran.edit', $registration)"
    >
        <x-admin-detail-item label="Nama">{{ $registration->full_name }}</x-admin-detail-item>
        <x-admin-detail-item label="Tempat / tanggal lahir">
            {{ $registration->birth_place }} / {{ $registration->birth_date?->timezone(config('app.timezone'))->format('d/m/Y') ?: '—' }}
        </x-admin-detail-item>
        <x-admin-detail-item label="Jenis kelamin">{{ $registration->gender ?: '—' }}</x-admin-detail-item>
        <x-admin-detail-item label="Alamat" value-class="text-sm text-church-fg whitespace-pre-line font-normal">{{ $registration->address ?: '—' }}</x-admin-detail-item>
        <x-admin-detail-item label="Telepon">{{ $registration->phone ?: '—' }}</x-admin-detail-item>
        <x-admin-detail-item label="Email">{{ $registration->email ?: '—' }}</x-admin-detail-item>
        <x-admin-detail-item label="Status">{{ $registration->status }}</x-admin-detail-item>
        <x-admin-detail-item label="Catatan" value-class="text-sm text-church-fg whitespace-pre-line font-normal">{{ $registration->notes ?: '—' }}</x-admin-detail-item>
    </x-admin-show-page>
@endsection
