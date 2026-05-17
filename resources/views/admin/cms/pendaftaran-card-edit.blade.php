@extends(request()->boolean('modal') ? 'layouts.admin-embed' : 'layouts.admin')

@section('title', 'Detail kartu: '.$card['admin_label'])

@section('content')
    <x-admin-edit-page
        :back-href="route('dashboard.halaman.cms.edit', 'pendaftaran')"
        back-label="Halaman pendaftaran"
        icon="fa-solid fa-pen-to-square"
        :title="'Detail kartu — '.$card['admin_label']"
        :action="route('dashboard.halaman.pendaftaran.kartu.update', $cardKey)"
        form-id="pendaftaran-card-form"
        wide
        :card-wrapped="false"
        submit-label="Simpan detail kartu"
        class="cms-page-form w-full min-w-0 max-w-full overflow-x-clip"
    >
        <x-slot:subtitle>
            Semua teks, ikon, label input, dan tombol pada halaman
            <span class="font-mono text-slate-300">/pendaftaran/{{ $card['slug'] }}</span>
            ·
            <a href="{{ route('pendaftaran.show', $card['slug']) }}" target="_blank" rel="noopener noreferrer" class="text-church-gold hover:underline">Lihat di situs ↗</a>
        </x-slot:subtitle>


        <div class="space-y-8 sm:space-y-10">
            @include('admin.cms.partials.pendaftaran-card-detail-fields', [
                'card' => $card,
                'cardKey' => $cardKey,
                'data' => $data,
                'detail' => $detail,
            ])
        </div>
    </x-admin-edit-page>
@endsection
