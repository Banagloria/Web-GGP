@extends(request()->boolean('modal') ? 'layouts.admin-embed' : 'layouts.admin')

@section('title', 'Tambah Jemaat')

@section('content')
    <x-admin-create-page
        :back-href="route('dashboard.pendaftaran.index', ['slug' => 'jemaat'])"
        back-label="Daftar jemaat"
        icon="fa-solid fa-user-plus"
        title="Tambah jemaat"
        :action="route('dashboard.pendaftaran.store')"
    >
        @include('admin.congregations._form')
    </x-admin-create-page>
@endsection
