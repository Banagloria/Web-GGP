@extends(request()->boolean('modal') ? 'layouts.admin-embed' : 'layouts.admin')

@section('title', 'Detail Pernikahan')

@section('content')
    <x-admin-show-page
        :back-href="route('dashboard.pendaftaran-pernikahan.index')"
        back-label="Daftar pernikahan"
        icon="fa-solid fa-heart"
        title="Detail pernikahan"
        :edit-url="route('dashboard.pendaftaran-pernikahan.edit', $registration)"
    >
        <x-admin-detail-item label="Mempelai pria">{{ $registration->groom_name }}</x-admin-detail-item>
        <x-admin-detail-item label="Mempelai wanita">{{ $registration->bride_name }}</x-admin-detail-item>
        <x-admin-detail-item label="Tanggal rencana">{{ $registration->wedding_date?->timezone(config('app.timezone'))->format('d/m/Y') ?: '—' }}</x-admin-detail-item>
        <x-admin-detail-item label="Telepon">{{ $registration->phone ?: '—' }}</x-admin-detail-item>
        <x-admin-detail-item label="Status">{{ $registration->status }}</x-admin-detail-item>
        <x-admin-detail-item label="Catatan" value-class="text-sm text-church-fg whitespace-pre-line font-normal">{{ $registration->notes ?: '—' }}</x-admin-detail-item>
    </x-admin-show-page>
@endsection
