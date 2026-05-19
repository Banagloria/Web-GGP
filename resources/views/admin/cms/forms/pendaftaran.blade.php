@include('admin.cms.partials.breadcrumb-editor', [
    'pageKey' => 'pendaftaran',
    'data' => $data,
    'homeIconKey' => 'index_breadcrumb_home',
    'sepIconKey' => 'index_breadcrumb_sep',
    'currentIconKey' => 'index_breadcrumb_current',
])

<div class="mt-6 grid min-w-0 grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(9rem,11rem)] sm:items-end">
    <div class="min-w-0">
        <x-admin-field-label class="text-sm text-slate-300">Judul utama halaman</x-admin-field-label>
        <input name="h1" value="{{ old('h1', $data['h1'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
    </div>
    <div class="min-w-0">
        @include('admin.cms.partials.page-icon-field', ['pageKey' => 'pendaftaran', 'iconKey' => 'index_h1', 'data' => $data])
    </div>
</div>

@php
    $__pendaftaranCards = old('cards');
    if (! is_array($__pendaftaranCards)) {
        $__pendaftaranCards = $data['cards'] ?? [];
    }
    if (! is_array($__pendaftaranCards) || count($__pendaftaranCards) === 0) {
        $__pendaftaranCards = [
            ['key' => 'c0', 'title' => '', 'description' => '', 'icon' => '', 'cta_label' => 'Isi formulir', 'url' => ''],
        ];
    }
@endphp

<div class="mt-6 flex flex-wrap justify-end gap-2">
    <button
        type="button"
        data-cms-pendaftaran-cards-add
        class="public-btn-hover rounded-md border border-church-gold/40 bg-church-gold/10 px-3 py-1.5 text-xs font-semibold text-church-gold"
    >
        Tambah kartu pendaftaran
    </button>
</div>

<input type="hidden" name="cards_row_count" id="cms-pendaftaran-cards-row-count" value="{{ count($__pendaftaranCards) }}">

<div id="cms-pendaftaran-cards-wrap" class="mt-4 space-y-4">
@foreach ($__pendaftaranCards as $i => $card)
    @php($cardTitle = old('cards.'.$i.'.title', $card['title'] ?? 'Kartu '.($i + 1)))
    @php($cardSlug = old('cards.'.$i.'.url', \App\Support\PublicCmsUrl::formatPendaftaranCardSlugForInput($card['url'] ?? '')))
    <div class="cms-pendaftaran-card-row admin-panel min-w-0 space-y-4 rounded-lg border border-white/10 bg-church-surface/40 p-4 sm:p-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h3 class="min-w-0 text-sm font-semibold text-church-fg">{{ $cardTitle }}</h3>
            <div class="flex flex-wrap items-center gap-2">
                @if (! empty($card['key']))
                    @include('admin.partials.btn', [
                        'href' => route('dashboard.setting.pendaftaran.kartu.edit', $card['key']),
                        'variant' => 'secondary',
                        'size' => 'sm',
                        'icon' => 'fa-solid fa-file-lines',
                        'label' => 'Detail kartu',
                    ])
                @endif
                <button type="button" data-cms-pendaftaran-card-remove class="text-xs text-red-400 hover:underline">Hapus kartu</button>
            </div>
        </div>
        <input type="hidden" name="cards[{{ $i }}][key]" value="{{ old('cards.'.$i.'.key', $card['key'] ?? 'c'.$i) }}">
        <div class="grid min-w-0 grid-cols-1 gap-3 sm:grid-cols-2 sm:items-end">
            <div class="min-w-0">
                <x-admin-field-label class="text-xs text-slate-400">Judul</x-admin-field-label>
                <input name="cards[{{ $i }}][title]" value="{{ old('cards.'.$i.'.title', $card['title'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg">
            </div>
            <div class="min-w-0">
                <x-admin-field-label class="text-xs text-slate-400">Ikon kartu (FA)</x-admin-field-label>
                <input name="cards[{{ $i }}][icon]" value="{{ old('cards.'.$i.'.icon', $card['icon'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface font-mono text-sm text-church-fg">
            </div>
        </div>
        <div class="min-w-0">
            <x-admin-field-label class="text-xs text-slate-400">Deskripsi</x-admin-field-label>
            <textarea name="cards[{{ $i }}][description]" rows="2" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg">{{ old('cards.'.$i.'.description', $card['description'] ?? '') }}</textarea>
        </div>
        <div class="grid min-w-0 grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="min-w-0">
                <x-admin-field-label class="text-xs text-slate-400">Label tombol</x-admin-field-label>
                <input name="cards[{{ $i }}][cta_label]" value="{{ old('cards.'.$i.'.cta_label', $card['cta_label'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg">
            </div>
            <div class="min-w-0">
                <x-admin-field-label class="text-xs text-slate-400">Slug URL</x-admin-field-label>
                <input
                    type="text"
                    name="cards[{{ $i }}][url]"
                    value="{{ $cardSlug }}"
                    pattern="[a-z0-9]+(?:-[a-z0-9]+)*"
                    class="mt-1 w-full rounded-md border border-white/15 bg-church-surface font-mono text-sm text-church-fg"
                    placeholder="contoh: sidik"
                >
                <p class="mt-1 text-xs text-slate-500">Halaman publik: <span class="font-mono text-slate-400">/pendaftaran/<span data-cms-pendaftaran-slug-preview>{{ $cardSlug ?: '…' }}</span></span></p>
            </div>
        </div>
        <div class="min-w-0 max-w-md">
            @include('admin.partials.fa-icon-input', [
                'name' => 'cards['.$i.'][arrow_icon]',
                'value' => old('cards.'.$i.'.arrow_icon', $card['arrow_icon'] ?? ($data['page_icons']['index_card_arrow'] ?? 'fa-solid fa-arrow-right')),
                'label' => 'Panah kartu',
                'variant' => 'adjacent',
                'previewDefault' => 'fa-solid fa-arrow-right',
            ])
        </div>
    </div>
@endforeach
</div>

@include('admin.cms.partials.pendaftaran-cards-script')
