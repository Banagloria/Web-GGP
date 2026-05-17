@extends(request()->boolean('modal') ? 'layouts.admin-embed' : 'layouts.admin')

@section('title', 'Detail Pesan')

@section('content')
    <x-admin-show-page
        :back-href="route('dashboard.kontak.index')"
        back-label="Daftar pesan"
        icon="fa-solid fa-envelope-open-text"
        title="Detail pesan"
        :delete-url="route('dashboard.kontak.destroy', $contact)"
        delete-title="Hapus pesan?"
        delete-message="Pesan kontak ini akan dihapus permanen."
    >
        <x-admin-detail-item label="Nama">{{ $contact->name }}</x-admin-detail-item>
        <x-admin-detail-item label="Email">{{ $contact->email ?: '—' }}</x-admin-detail-item>
        <x-admin-detail-item label="Telepon">{{ $contact->phone ?: '—' }}</x-admin-detail-item>
        <x-admin-detail-item label="Subjek">{{ $contact->subject ?: '—' }}</x-admin-detail-item>
        <x-admin-detail-item label="Pesan" value-class="text-sm text-church-fg whitespace-pre-line font-normal">{{ $contact->message }}</x-admin-detail-item>
    </x-admin-show-page>
@endsection
