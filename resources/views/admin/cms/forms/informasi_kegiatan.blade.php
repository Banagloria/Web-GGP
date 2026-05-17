@include('admin.cms.partials.breadcrumb-editor', [
    'pageKey' => 'informasi_kegiatan',
    'data' => $data,
    'homeIconKey' => 'index_breadcrumb_home',
    'sepIconKey' => 'index_breadcrumb_sep',
    'currentIconKey' => 'index_breadcrumb_current',
])

<div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(10rem,13rem)] sm:items-end">
    <div>
        <x-admin-field-label class="text-sm text-slate-300">Judul utama halaman</x-admin-field-label>
        <input name="h1" value="{{ old('h1', $data['h1'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
    </div>
    @include('admin.cms.partials.page-icon-field', ['pageKey' => 'informasi_kegiatan', 'iconKey' => 'index_h1', 'data' => $data])
</div>

<div class="mt-4 grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(10rem,13rem)] sm:items-end">
    <div>
        <x-admin-field-label class="text-sm text-slate-300">Teks jika belum ada pengumuman</x-admin-field-label>
        <input name="empty_message" value="{{ old('empty_message', $data['empty_message'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
    </div>
    @include('admin.cms.partials.page-icon-field', ['pageKey' => 'informasi_kegiatan', 'iconKey' => 'index_empty', 'data' => $data])
</div>

<div class="mt-4 grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(10rem,13rem)] sm:items-end">
    <div>
        <x-admin-field-label class="text-sm text-slate-300">Label tautan &quot;baca selengkapnya&quot;</x-admin-field-label>
        <input name="read_more_label" value="{{ old('read_more_label', $data['read_more_label'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
    </div>
    @include('admin.cms.partials.page-icon-field', ['pageKey' => 'informasi_kegiatan', 'iconKey' => 'index_card_arrow', 'data' => $data, 'label' => 'Ikon panah'])
</div>

<div class="mt-6 max-w-md">
    @include('admin.cms.partials.page-icon-field', ['pageKey' => 'informasi_kegiatan', 'iconKey' => 'index_card_date', 'data' => $data, 'label' => 'Ikon tanggal pada kartu daftar'])
</div>

<div class="mt-8 rounded-lg border border-white/10 bg-church-surface/30 p-4 sm:p-5">
    <h2 class="mb-3 text-sm font-semibold text-church-fg">Halaman detail pengumuman</h2>
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(10rem,13rem)] sm:items-end">
        <div>
            <x-admin-field-label class="text-sm text-slate-300">Judul halaman detail</x-admin-field-label>
            <input name="show_page_h1" value="{{ old('show_page_h1', $data['show_page_h1'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
        </div>
        @include('admin.cms.partials.page-icon-field', ['pageKey' => 'informasi_kegiatan', 'iconKey' => 'show_page_h1', 'data' => $data])
    </div>
    <div class="mt-4 grid gap-4 sm:grid-cols-3 sm:items-end">
        @include('admin.cms.partials.page-icon-field', ['pageKey' => 'informasi_kegiatan', 'iconKey' => 'show_back', 'data' => $data, 'label' => 'Tautan kembali'])
        @include('admin.cms.partials.page-icon-field', ['pageKey' => 'informasi_kegiatan', 'iconKey' => 'show_h1', 'data' => $data, 'label' => 'Judul artikel'])
        @include('admin.cms.partials.page-icon-field', ['pageKey' => 'informasi_kegiatan', 'iconKey' => 'show_date', 'data' => $data, 'label' => 'Tanggal'])
    </div>
</div>
