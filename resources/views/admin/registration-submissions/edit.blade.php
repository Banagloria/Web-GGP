@extends(request()->boolean('modal') ? 'layouts.admin-embed' : 'layouts.admin')

@section('title', 'Edit — '.$title)

@section('content')
    @php
        $routes = $routes ?? \App\Http\Controllers\Admin\RegistrationSubmissionController::routesForSlug($slug, $listKind ?? \App\Http\Controllers\Admin\RegistrationSubmissionController::LIST_PENDING);
        $routeParams = ['slug' => $slug, 'submission' => $submission];
        $listKind = $listKind ?? \App\Http\Controllers\Admin\RegistrationSubmissionController::LIST_PENDING;
        $isPendingList = $listKind === \App\Http\Controllers\Admin\RegistrationSubmissionController::LIST_PENDING;
        $canDelete = ! ($isPendingList && $submission->status === 'submitted');
    @endphp

    <x-admin-edit-page
        :back-href="route($routes['index'], ['slug' => $slug])"
        back-label="Daftar data"
        icon="fa-solid fa-pen-to-square"
        :title="'Edit — '.$title"
        :action="route($routes['update'], $routeParams)"
        form-id="registration-submission-form"
        enctype="multipart/form-data"
        wide
        :delete-url="$canDelete ? route($routes['destroy'], $routeParams) : null"
        delete-title="Hapus data pendaftaran?"
        delete-message="Data ini akan dihapus permanen."
    >
        @include('admin.registration-submissions._form', [
            'submission' => $submission,
            'detail' => $detail,
        ])
    </x-admin-edit-page>
@endsection
