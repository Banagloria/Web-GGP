@extends('layouts.admin')

@section('title', 'Tambah Akun Admin')

@section('content')
    <x-admin-create-page
        :back-href="route('dashboard.akun.index')"
        back-label="Daftar akun"
        icon="fa-solid fa-user-plus"
        title="Tambah akun admin"
        :action="route('dashboard.akun.store')"
        submit-label="Simpan akun"
    >
        @include('admin.users._form', ['user' => null])
    </x-admin-create-page>
@endsection
