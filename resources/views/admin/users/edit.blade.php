@extends('layouts.admin')

@section('title', 'Edit Akun Admin')

@section('content')
    <x-admin-edit-page
        :back-href="route('dashboard.akun.index')"
        back-label="Daftar akun"
        icon="fa-solid fa-user-pen"
        title="Edit akun admin"
        :action="route('dashboard.akun.update', $user)"
        form-id="user-account-form"
        submit-label="Simpan perubahan"
        :delete-url="auth()->id() !== $user->id ? route('dashboard.akun.destroy', $user) : null"
        delete-title="Hapus akun admin?"
        delete-message="Akun ini tidak dapat dipulihkan setelah dihapus."
    >
        @include('admin.users._form', ['user' => $user])
    </x-admin-edit-page>
@endsection
