@extends(request()->boolean('modal') ? 'layouts.admin-embed' : 'layouts.admin')

@section('title', 'Edit Baptisan')

@section('content')
    <x-admin-edit-page
        :back-href="route('dashboard.pendaftaran-baptisan.index')"
        back-label="Daftar baptisan"
        icon="fa-solid fa-pen-to-square"
        title="Edit baptisan"
        :action="route('dashboard.pendaftaran-baptisan.update', $registration)"
        form-id="baptism-form"
        :delete-url="route('dashboard.pendaftaran-baptisan.destroy', $registration)"
        delete-title="Hapus data baptisan?"
        delete-message="Data pendaftaran baptisan ini akan dihapus permanen."
    >
        @include('admin.baptisms._form', ['registration' => $registration])
    </x-admin-edit-page>
@endsection
