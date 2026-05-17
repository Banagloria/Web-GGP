@extends('layouts.admin')

@section('title', 'Edit Jadwal')

@section('content')
    <x-admin-edit-page
        :back-href="route('dashboard.jadwal-ibadah.index')"
        back-label="Daftar jadwal"
        icon="fa-solid fa-pen-to-square"
        title="Edit jadwal"
        :action="route('dashboard.jadwal-ibadah.update', $item)"
        form-id="schedule-form"
        :delete-url="route('dashboard.jadwal-ibadah.destroy', $item)"
        delete-title="Hapus jadwal?"
        delete-message="Jadwal ini akan dihapus permanen dari daftar mendatang dan selesai."
    >
        @include('admin.schedules._form', ['item' => $item, 'cms' => $cms, 'middleLabels' => $middleLabels])
    </x-admin-edit-page>
@endsection
