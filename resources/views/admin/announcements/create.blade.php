@extends(request()->boolean('modal') ? 'layouts.admin-embed' : 'layouts.admin')

@section('title', 'Tambah Pengumuman')

@section('content')
    <x-admin-create-page
        :back-href="route('dashboard.pengumuman.index')"
        back-label="Daftar pengumuman"
        icon="fa-solid fa-bullhorn"
        title="Tambah pengumuman"
        :action="route('dashboard.pengumuman.store')"
    >
        @include('admin.announcements._form', ['announcement' => null])
    </x-admin-create-page>
@endsection
