@include('admin.cms.partials.breadcrumb-editor', ['pageKey' => 'profil', 'data' => $data])

<div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(10rem,13rem)] sm:items-end">
    <div class="min-w-0">
        <x-admin-field-label class="text-sm text-slate-300">Judul halaman</x-admin-field-label>
        <input name="title" value="{{ old('title', $data['title'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
    </div>
    <div class="min-w-0">
        @include('admin.cms.partials.page-icon-field', ['pageKey' => 'profil', 'iconKey' => 'h1', 'data' => $data, 'label' => 'Ikon judul (H1)'])
    </div>
</div>

@include('admin.cms.partials.content-blocks-editor', ['data' => $data])
