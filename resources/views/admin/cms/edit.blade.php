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
    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300" role="alert">
            <p class="font-medium text-red-200">Perubahan belum tersimpan. Periksa isian berikut:</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <x-admin-edit-page
        :back-href="route('dashboard.setting.index')"
        back-label="Setting"
        icon="fa-solid fa-pen-to-square"
        :title="$pageLabel"
        :action="route('dashboard.setting.cms.update', $pageKey)"
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
