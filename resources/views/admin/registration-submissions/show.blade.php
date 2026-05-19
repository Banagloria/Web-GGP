@extends(request()->boolean('modal') ? 'layouts.admin-embed' : 'layouts.admin')

@section('title', 'Detail — '.$title)

@section('content')
    @php
        $files = is_array($submission->files) ? $submission->files : [];
        $listKind = $listKind ?? \App\Http\Controllers\Admin\RegistrationSubmissionController::LIST_PENDING;
        $routes = $routes ?? \App\Http\Controllers\Admin\RegistrationSubmissionController::routesForSlug($slug, $listKind);
        $routeParams = ['slug' => $slug];
        $isPendingList = $listKind === \App\Http\Controllers\Admin\RegistrationSubmissionController::LIST_PENDING;
        $canReview = $isPendingList && $submission->status === 'submitted';
        $confirmPhone = \App\Services\RegistrationSubmissionService::phoneFromSubmission($submission, $columns);
    @endphp

    <x-admin-show-page
        :back-href="route($routes['index'], $routeParams)"
        back-label="Daftar data"
        icon="fa-solid fa-file-lines"
        :title="'Detail — '.$title"
        wide
        :delete-url="! $canReview ? route($routes['destroy'], array_merge($routeParams, [$submission])) : null"
        delete-title="Hapus data pendaftaran?"
        delete-message="Data ini akan dihapus permanen."
    >
        <x-slot name="actions">
            <div class="admin-page-actions">
                @if ($canReview)
                    @include('admin.partials.btn', [
                        'formAction' => route($routes['accept'], array_merge($routeParams, [$submission])),
                        'variant' => 'primary',
                        'icon' => 'fa-solid fa-check',
                        'label' => 'Terima',
                        'extraClass' => 'shrink-0 whitespace-nowrap',
                        'confirmSubmit' => true,
                        'confirmVariant' => 'accept',
                        'confirmTitle' => 'Terima pendaftaran?',
                        'confirmMessage' => 'Data akan dipindahkan ke halaman Data.',
                        'confirmLabel' => 'Ya, terima',
                        'confirmWhatsapp' => true,
                        'confirmPhone' => $confirmPhone,
                        'confirmWaDefault' => 'Selamat, pendaftaran Anda telah kami terima. Terima kasih.',
                    ])
                    @include('admin.partials.btn', [
                        'formAction' => route($routes['reject'], array_merge($routeParams, [$submission])),
                        'variant' => 'danger',
                        'icon' => 'fa-solid fa-xmark',
                        'label' => 'Tolak',
                        'extraClass' => 'shrink-0 whitespace-nowrap',
                        'confirmSubmit' => true,
                        'confirmVariant' => 'reject',
                        'confirmTitle' => 'Tolak pendaftaran?',
                        'confirmMessage' => 'Data akan dihapus permanen.',
                        'confirmLabel' => 'Ya, tolak',
                        'confirmWhatsapp' => true,
                        'confirmPhone' => $confirmPhone,
                        'confirmWaDefault' => 'Mohon maaf, pendaftaran Anda belum dapat kami terima saat ini.',
                    ])
                @endif
                @include('admin.partials.btn', [
                    'href' => route($routes['edit'], array_merge($routeParams, [$submission])),
                    'variant' => 'secondary',
                    'icon' => 'fa-solid fa-pen',
                    'label' => 'Edit',
                    'extraClass' => 'shrink-0 whitespace-nowrap',
                ])
                @include('admin.partials.btn', [
                    'href' => route($routes['index'], $routeParams),
                    'variant' => 'secondary',
                    'icon' => 'fa-solid fa-arrow-left',
                    'label' => 'Kembali',
                    'extraClass' => 'shrink-0 whitespace-nowrap',
                ])
                @if (! $canReview)
                    @include('admin.partials.btn', [
                        'formAction' => route($routes['destroy'], array_merge($routeParams, [$submission])),
                        'method' => 'DELETE',
                        'variant' => 'danger-solid',
                        'icon' => 'fa-solid fa-trash',
                        'label' => 'Hapus',
                        'extraClass' => 'shrink-0 whitespace-nowrap',
                        'confirmSubmit' => true,
                        'confirmVariant' => 'delete',
                        'confirmTitle' => 'Hapus data pendaftaran?',
                        'confirmMessage' => 'Data ini akan dihapus permanen.',
                        'confirmLabel' => 'Ya, hapus',
                    ])
                @endif
            </div>
        </x-slot>

        <x-admin-detail-item label="ID">#{{ $submission->id }}</x-admin-detail-item>
        <x-admin-detail-item label="Status">
            @if($submission->status === 'active')
                Diterima
            @elseif($submission->status === 'submitted')
                Diajukan
            @else
                {{ $submission->status }}
            @endif
        </x-admin-detail-item>

        @foreach ($columns as $col)
            <x-admin-detail-item :label="$col['label']">
                @if (isset($files[$col['name']]))
                    <a href="{{ $files[$col['name']] }}" target="_blank" rel="noopener noreferrer" class="text-church-gold hover:underline">
                        <i class="fa-solid fa-download mr-1 text-xs" aria-hidden="true"></i>
                        Unduh berkas
                    </a>
                @else
                    {{ $submission->payloadValue($col['name']) ?: '—' }}
                @endif
            </x-admin-detail-item>
        @endforeach

        <x-admin-detail-item label="Catatan" value-class="text-sm text-church-fg whitespace-pre-line font-normal">{{ $submission->notes ?: '—' }}</x-admin-detail-item>
        <x-admin-detail-item label="Dikirim">{{ $submission->created_at?->timezone(config('app.timezone'))->format('d/m/Y H:i') ?: '—' }}</x-admin-detail-item>
    </x-admin-show-page>
@endsection
