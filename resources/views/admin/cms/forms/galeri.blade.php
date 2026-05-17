@include('admin.cms.partials.breadcrumb-editor', ['pageKey' => 'galeri', 'data' => $data])

<div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(10rem,13rem)] sm:items-end">
    <div>
        <x-admin-field-label class="text-sm text-slate-300">Judul utama halaman</x-admin-field-label>
        <input name="h1" value="{{ old('h1', $data['h1'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
    </div>
    @include('admin.cms.partials.page-icon-field', ['pageKey' => 'galeri', 'iconKey' => 'h1', 'data' => $data])
</div>

<div class="mt-4 grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(10rem,13rem)] sm:items-start">
    <div>
        <x-admin-field-label class="text-sm text-slate-300">Pengantar</x-admin-field-label>
        <textarea name="intro" rows="2" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">{{ old('intro', $data['intro'] ?? '') }}</textarea>
    </div>
    <div class="sm:pt-[1.375rem]">
        @include('admin.cms.partials.page-icon-field', ['pageKey' => 'galeri', 'iconKey' => 'intro', 'data' => $data])
    </div>
</div>

<div class="mt-4 grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(10rem,13rem)] sm:items-end">
    <div>
        <x-admin-field-label class="text-sm text-slate-300">Teks jika belum ada foto</x-admin-field-label>
        <input name="empty_message" value="{{ old('empty_message', $data['empty_message'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
    </div>
    @include('admin.cms.partials.page-icon-field', ['pageKey' => 'galeri', 'iconKey' => 'empty_message', 'data' => $data])
</div>

<div class="mt-6 space-y-4">
    <div>
        <x-admin-field-label class="text-sm text-slate-300">Judul dialog lightbox sr-only</x-admin-field-label>
        <input name="lightbox_title" value="{{ old('lightbox_title', $data['lightbox_title'] ?? '') }}" class="mt-1 w-full max-w-xl rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
    </div>
    <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(9rem,11rem)] sm:items-end">
        <div>
            <x-admin-field-label class="text-sm text-slate-300">Label tutup</x-admin-field-label>
            <input name="lightbox_close_label" value="{{ old('lightbox_close_label', $data['lightbox_close_label'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
        </div>
        @include('admin.cms.partials.page-icon-field', ['pageKey' => 'galeri', 'iconKey' => 'lightbox_close', 'data' => $data, 'label' => 'Ikon tutup'])
    </div>
    <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(9rem,11rem)] sm:items-end">
        <div>
            <x-admin-field-label class="text-sm text-slate-300">Label foto sebelumnya</x-admin-field-label>
            <input name="lightbox_prev_label" value="{{ old('lightbox_prev_label', $data['lightbox_prev_label'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
        </div>
        @include('admin.cms.partials.page-icon-field', ['pageKey' => 'galeri', 'iconKey' => 'lightbox_prev', 'data' => $data, 'label' => 'Ikon'])
    </div>
    <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(9rem,11rem)] sm:items-end">
        <div>
            <x-admin-field-label class="text-sm text-slate-300">Label foto berikutnya</x-admin-field-label>
            <input name="lightbox_next_label" value="{{ old('lightbox_next_label', $data['lightbox_next_label'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
        </div>
        @include('admin.cms.partials.page-icon-field', ['pageKey' => 'galeri', 'iconKey' => 'lightbox_next', 'data' => $data, 'label' => 'Ikon'])
    </div>
</div>
