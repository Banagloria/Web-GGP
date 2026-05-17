@extends(request()->boolean('modal') ? 'layouts.admin-embed' : 'layouts.admin')

@php
    $labels = [
        'beranda' => 'Beranda',
        'profil' => 'Profil — /profil',
        'struktur' => 'Struktur — /struktur',
        'jadwal' => 'Jadwal — /jadwal',
        'pendaftaran' => 'Pendaftaran — /pendaftaran',
        'informasi_kegiatan' => 'Informasi kegiatan — /informasi-kegiatan',
        'kontak' => 'Kontak — /kontak',
        'galeri' => 'Galeri — /galeri',
    ];
    $pageLabel = $labels[$pageKey] ?? $pageKey;
@endphp

@section('title', 'Sunting: '.$pageLabel)

@section('content')
    <x-admin-edit-page
        :back-href="route('dashboard.halaman.index')"
        back-label="Daftar halaman"
        icon="fa-solid fa-pen-to-square"
        :title="$pageLabel"
        :action="route('dashboard.halaman.cms.update', $pageKey)"
        form-id="cms-page-form"
        enctype="multipart/form-data"
        wide
        :card-wrapped="false"
        class="cms-page-form cms-page-form--{{ $pageKey }} w-full min-w-0 max-w-full overflow-x-clip"
    >

        <div class="space-y-10 sm:space-y-12">
            @include('admin.cms.forms.'.$pageKey, ['data' => $data])
        </div>
    </x-admin-edit-page>
@endsection
