@extends(request()->boolean('modal') ? 'layouts.admin-embed' : 'layouts.admin')

@section('title', 'Edit Pernikahan')

@section('content')
    <x-admin-edit-page
        :back-href="route('dashboard.pendaftaran-pernikahan.index')"
        back-label="Daftar pernikahan"
        icon="fa-solid fa-pen-to-square"
        title="Edit pernikahan"
        :action="route('dashboard.pendaftaran-pernikahan.update', $registration)"
        form-id="marriage-form"
        :delete-url="route('dashboard.pendaftaran-pernikahan.destroy', $registration)"
        delete-title="Hapus data pernikahan?"
        delete-message="Data pendaftaran pernikahan ini akan dihapus permanen."
    >
        @include('admin.marriages._form')
    </x-admin-edit-page>
@endsection
