@extends('layouts.admin')

@section('title', 'Tambah Jadwal')

@section('content')
    <x-admin-create-page
        :back-href="route('dashboard.jadwal-ibadah.index')"
        back-label="Daftar jadwal"
        icon="fa-solid fa-calendar-plus"
        title="Tambah jadwal"
        :action="route('dashboard.jadwal-ibadah.store')"
        form-id="schedule-form"
    >
        @include('admin.schedules._form', ['item' => null, 'cms' => $cms, 'middleLabels' => $middleLabels])
    </x-admin-create-page>
@endsection
