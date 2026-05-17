@extends(request()->boolean('modal') ? 'layouts.admin-embed' : 'layouts.admin')

@section('title', 'Edit Jemaat')

@section('content')
    <x-admin-edit-page
        :back-href="route('dashboard.pendaftaran-jemaat.index')"
        back-label="Daftar jemaat"
        icon="fa-solid fa-pen-to-square"
        title="Edit jemaat"
        :action="route('dashboard.pendaftaran-jemaat.update', $registration)"
        form-id="congregation-form"
        :delete-url="route('dashboard.pendaftaran-jemaat.destroy', $registration)"
        delete-title="Hapus data jemaat?"
        delete-message="Data pendaftaran jemaat ini akan dihapus permanen."
    >
        @include('admin.congregations._form')
    </x-admin-edit-page>
@endsection
