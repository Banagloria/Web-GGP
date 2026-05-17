@extends(request()->boolean('modal') ? 'layouts.admin-embed' : 'layouts.admin')

@section('title', 'Detail — '.$title)

@section('content')
    @php
        $files = is_array($submission->files) ? $submission->files : [];
    @endphp

    <x-admin-show-page
        :back-href="route('dashboard.pendaftaran-data.index', $slug)"
        back-label="Daftar data"
        icon="fa-solid fa-file-lines"
        :title="'Detail — '.$title"
        wide
        :delete-url="route('dashboard.pendaftaran-data.destroy', [$slug, $submission])"
        delete-title="Hapus data pendaftaran?"
        delete-message="Data ini akan dihapus permanen."
    >
        <x-admin-detail-item label="ID">#{{ $submission->id }}</x-admin-detail-item>
        <x-admin-detail-item label="Status">{{ $submission->status }}</x-admin-detail-item>

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
