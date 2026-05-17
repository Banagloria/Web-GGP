@extends(request()->boolean('modal') ? 'layouts.admin-embed' : 'layouts.admin')

@section('title', 'Edit Pengumuman')

@section('content')
    <x-admin-edit-page
        :back-href="route('dashboard.pengumuman.index')"
        back-label="Daftar pengumuman"
        icon="fa-solid fa-pen-to-square"
        title="Edit pengumuman"
        :action="route('dashboard.pengumuman.update', $announcement)"
        form-id="announcement-form"
        :delete-url="route('dashboard.pengumuman.destroy', $announcement)"
        delete-title="Hapus pengumuman?"
        delete-message="Pengumuman ini akan dihapus permanen."
    >
        @include('admin.announcements._form', ['announcement' => $announcement])
    </x-admin-edit-page>
@endsection
