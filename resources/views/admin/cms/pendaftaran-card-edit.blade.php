@extends(request()->boolean('modal') ? 'layouts.admin-embed' : 'layouts.admin')

@section('title', 'Detail kartu: '.$card['admin_label'])

@section('content')
    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300" role="alert">
            <p class="font-medium text-red-200">Perubahan belum tersimpan. Periksa isian berikut:</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <x-admin-edit-page
        :back-href="route('dashboard.setting.cms.edit', 'pendaftaran')"
        back-label="Setting pendaftaran"
        icon="fa-solid fa-pen-to-square"
        :title="'Detail kartu — '.$card['admin_label']"
        :action="route('dashboard.setting.pendaftaran.kartu.update', $cardKey)"
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
