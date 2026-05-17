@extends(request()->boolean('modal') ? 'layouts.admin-embed' : 'layouts.admin')

@section('title', 'Tambah Pernikahan')

@section('content')
    <x-admin-create-page
        :back-href="route('dashboard.pendaftaran-pernikahan.index')"
        back-label="Daftar pernikahan"
        icon="fa-solid fa-heart"
        title="Tambah pernikahan"
        :action="route('dashboard.pendaftaran-pernikahan.store')"
    >
        @include('admin.marriages._form')
    </x-admin-create-page>
@endsection
