@extends(request()->boolean('modal') ? 'layouts.admin-embed' : 'layouts.admin')

@section('title', 'Tambah Baptisan')

@section('content')
    <x-admin-create-page
        :back-href="route('dashboard.pendaftaran-baptisan.index')"
        back-label="Daftar baptisan"
        icon="fa-solid fa-droplet"
        title="Tambah baptisan"
        :action="route('dashboard.pendaftaran-baptisan.store')"
    >
        @include('admin.baptisms._form', ['registration' => null])
    </x-admin-create-page>
@endsection
