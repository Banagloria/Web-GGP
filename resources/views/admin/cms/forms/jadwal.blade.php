@include('admin.cms.partials.breadcrumb-editor', ['pageKey' => 'jadwal', 'data' => $data])

@php
    use App\Services\WorshipSchedulePartitionService;
    $upcomingHeaders = WorshipSchedulePartitionService::headersUpcoming($data);
    $upMiddle = WorshipSchedulePartitionService::middleHeaders($upcomingHeaders);
    $upWaktu = $upcomingHeaders[0] ?? 'Waktu';
    $upAksi = $upcomingHeaders[count($upcomingHeaders) - 1] ?? 'Aksi';
    $upIcons = WorshipSchedulePartitionService::columnIconsForTable($data, 'upcoming');
    $upAksiIdx = count($upcomingHeaders) - 1;
    $iconInputClass = 'text-xs text-slate-400';
@endphp

<div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(10rem,13rem)] sm:items-end">
    <div>
        <x-admin-field-label class="text-sm text-slate-300">Judul utama halaman</x-admin-field-label>
        <input name="h1" value="{{ old('h1', $data['h1'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
    </div>
    @include('admin.cms.partials.page-icon-field', ['pageKey' => 'jadwal', 'iconKey' => 'h1', 'data' => $data])
</div>

<div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
    <div class="rounded-lg border border-white/10 bg-church-surface/30 p-4 sm:p-5">
        <h3 class="mb-3 flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-slate-400">
            <i class="fa-solid fa-hourglass-half text-church-gold/80" aria-hidden="true"></i>
            Tabel mendatang
        </h3>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(9.5rem,11rem)] sm:items-end">
            <div class="min-w-0">
                <x-admin-field-label class="text-sm text-slate-300">Judul</x-admin-field-label>
                <input name="section_upcoming_title" value="{{ old('section_upcoming_title', $data['section_upcoming_title'] ?? 'Jadwal mendatang') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
            </div>
            @include('admin.cms.partials.page-icon-field', ['pageKey' => 'jadwal', 'iconKey' => 'section_upcoming', 'data' => $data, 'label' => 'Ikon'])
        </div>
    </div>

    <div class="rounded-lg border border-white/10 bg-church-surface/30 p-4 sm:p-5">
        <h3 class="mb-3 flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-slate-400">
            <i class="fa-solid fa-circle-check text-church-gold/80" aria-hidden="true"></i>
            Tabel selesai
        </h3>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(9.5rem,11rem)] sm:items-end">
            <div class="min-w-0">
                <x-admin-field-label class="text-sm text-slate-300">Judul</x-admin-field-label>
                <input name="section_completed_title" value="{{ old('section_completed_title', $data['section_completed_title'] ?? 'Jadwal selesai') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
            </div>
            @include('admin.cms.partials.page-icon-field', ['pageKey' => 'jadwal', 'iconKey' => 'section_completed', 'data' => $data, 'label' => 'Ikon'])
        </div>
    </div>
</div>


<fieldset id="cms-jadwal-upcoming-fieldset" class="mt-8 space-y-4 rounded-lg border border-white/10 bg-church-surface/30 p-4 sm:p-5">
    <x-admin-field-label as="legend">Kolom tabel jadwal</x-admin-field-label>
    <p class="text-xs text-slate-400">
        Kolom <strong>pertama</strong> (waktu) dan <strong>terakhir</strong> (aksi) tidak dapat dihapus; label keduanya bisa disesuaikan.
        Tabel <strong>selesai</strong> memakai kolom yang sama secara otomatis.
    </p>
    <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(10rem,13rem)] sm:items-end">
        <div>
            <x-admin-field-label class="text-xs text-slate-400">Kolom 1 — Waktu (tetap)</x-admin-field-label>
            <input
                data-waktu-header-input
                name="table_headers_upcoming[0]"
                value="{{ old('table_headers_upcoming.0', $upWaktu) }}"
                class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg"
            >
        </div>
        @include('admin.partials.fa-icon-input', [
            'name' => 'table_column_icons_upcoming[0]',
            'value' => old('table_column_icons_upcoming.0', $upIcons[0] ?? ''),
            'label' => 'Ikon kolom',
            'labelClass' => $iconInputClass,
            'previewDefault' => 'fa-regular fa-clock',
            'variant' => 'adjacent',
        ])
    </div>

    <div id="cms-jadwal-upcoming-middle" class="space-y-3">
@foreach ($upMiddle as $i => $h)
    @php $idx = $i + 1; @endphp
    <div class="cms-jadwal-middle-row grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(10rem,13rem)_auto] sm:items-end">
        <div>
            <x-admin-field-label class="text-xs text-slate-400">Header kolom {{ $idx + 1 }}</x-admin-field-label>
            <input name="table_headers_upcoming[{{ $idx }}]" value="{{ old('table_headers_upcoming.'.$idx, $h) }}" data-header-input class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg">
        </div>
        @include('admin.partials.fa-icon-input', [
            'name' => "table_column_icons_upcoming[{$idx}]",
            'value' => old('table_column_icons_upcoming.'.$idx, $upIcons[$idx] ?? ''),
            'label' => 'Ikon kolom',
            'labelClass' => $iconInputClass,
            'previewDefault' => WorshipSchedulePartitionService::defaultColumnIconForIndex($idx, count($upcomingHeaders), $h),
            'variant' => 'adjacent',
            'hint' => '',
            'inputAttrs' => 'data-icon-input',
        ])
        <button
            type="button"
            data-cms-jadwal-remove
            class="public-btn-hover mb-0.5 inline-flex size-10 items-center justify-center self-end rounded-lg border border-red-500/30 bg-red-500/10 text-red-400 sm:mb-2"
            title="Hapus kolom"
            aria-label="Hapus kolom"
        >
            <i class="fa-solid fa-trash text-sm" aria-hidden="true"></i>
        </button>
    </div>
@endforeach
    </div>
 <button type="button" id="cms-jadwal-upcoming-add" class="public-btn-hover inline-flex items-center gap-2 rounded-md border border-church-gold/40 bg-church-gold/10 px-3 py-1.5 text-xs font-semibold text-church-gold">
        <i class="fa-solid fa-plus text-[0.65rem]" aria-hidden="true"></i>
        Tambah kolom tengah
    </button>
    <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(10rem,13rem)] sm:items-end">
        <div>
            <x-admin-field-label class="text-xs text-slate-400">Kolom terakhir — Aksi (tetap)</x-admin-field-label>
            <input data-aksi-header-input name="table_headers_upcoming[{{ $upAksiIdx }}]" value="{{ old('table_headers_upcoming.'.$upAksiIdx, $upAksi) }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg">
        </div>
        @include('admin.partials.fa-icon-input', [
            'name' => "table_column_icons_upcoming[{$upAksiIdx}]",
            'value' => old('table_column_icons_upcoming.'.$upAksiIdx, $upIcons[$upAksiIdx] ?? ''),
            'label' => 'Ikon kolom',
            'labelClass' => $iconInputClass,
            'previewDefault' => 'fa-solid fa-gear',
            'variant' => 'adjacent',
            'hint' => '',
            'inputAttrs' => 'data-aksi-icon-input',
        ])
    </div>
</fieldset>

<template id="cms-jadwal-middle-row-template">
    <div class="cms-jadwal-middle-row grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(10rem,13rem)_auto] sm:items-end">
        <div>
            <x-admin-field-label class="text-xs text-slate-400">Header kolom</x-admin-field-label>
            <input data-header-input class="mt-1 w-full rounded-md border border-white/15 bg-church-surface text-sm text-church-fg" value="">
        </div>
        <div class="cms-jadwal-icon-slot" data-cms-jadwal-icon-slot></div>
        <button
            type="button"
            data-cms-jadwal-remove
            class="public-btn-hover mb-0.5 inline-flex size-10 items-center justify-center self-end rounded-lg border border-red-500/30 bg-red-500/10 text-red-400 sm:mb-2"
            title="Hapus kolom"
            aria-label="Hapus kolom"
        >
            <i class="fa-solid fa-trash text-sm" aria-hidden="true"></i>
        </button>
    </div>
</template>

<script>
(function () {
    var maxMiddle = 8;
    var wrap = document.getElementById('cms-jadwal-upcoming-middle');
    var fieldset = document.getElementById('cms-jadwal-upcoming-fieldset');

    function buildIconField(idx) {
        var el = document.createElement('div');
        el.className = 'cms-fa-icon-field cms-jadwal-dynamic-icon';
        el.setAttribute('data-cms-fa-icon-field', '');
        el.innerHTML =
            '<x-admin-field-label class="text-xs text-slate-400">Ikon kolom</x-admin-field-label>' +
            '<div class="mt-1 flex gap-2">' +
            '<input type="text" data-icon-input name="table_column_icons_upcoming[' + idx + ']" value="" autocomplete="off" spellcheck="false" data-cms-fa-icon-input class="min-w-0 flex-1 rounded-md border border-white/15 bg-church-surface px-3 py-2 font-mono text-sm text-church-fg">' +
            '<span class="flex size-10 shrink-0 items-center justify-center rounded-md border border-white/15 bg-church-surface/80 text-lg text-church-gold" data-cms-fa-icon-preview aria-hidden="true"><i class="fa-solid fa-circle opacity-30"></i></span>' +
            '</div>';
        return el;
    }

    function reindexMiddle() {
        if (!wrap || !fieldset) return;
        var rows = wrap.querySelectorAll('.cms-jadwal-middle-row');
        rows.forEach(function (row, i) {
            var idx = i + 1;
            var headerInput = row.querySelector('input[data-header-input]');
            var iconInput = row.querySelector('input[data-icon-input]');
            var label = row.querySelector('label');
            if (label) label.textContent = 'Header kolom ' + (idx + 1);
            if (headerInput) headerInput.name = 'table_headers_upcoming[' + idx + ']';
            if (iconInput) iconInput.name = 'table_column_icons_upcoming[' + idx + ']';
        });
        var aksiIdx = rows.length + 1;
        var aksiHeader = fieldset.querySelector('[data-aksi-header-input]');
        var aksiIconInput = fieldset.querySelector('[data-aksi-icon-input]');
        if (aksiHeader) aksiHeader.name = 'table_headers_upcoming[' + aksiIdx + ']';
        if (aksiIconInput) aksiIconInput.name = 'table_column_icons_upcoming[' + aksiIdx + ']';
    }

    function addMiddle() {
        var tpl = document.getElementById('cms-jadwal-middle-row-template');
        if (!wrap || !tpl) return;
        if (wrap.querySelectorAll('.cms-jadwal-middle-row').length >= maxMiddle) return;
        var node = tpl.content.cloneNode(true);
        var iconSlot = node.querySelector('[data-cms-jadwal-icon-slot]');
        if (iconSlot) {
            iconSlot.replaceWith(buildIconField(wrap.querySelectorAll('.cms-jadwal-middle-row').length + 1));
        }
        wrap.appendChild(node);
        reindexMiddle();
    }

    function removeMiddle(row) {
        if (!wrap || !row) return;
        row.remove();
        reindexMiddle();
    }

    document.getElementById('cms-jadwal-upcoming-add')?.addEventListener('click', addMiddle);

    document.addEventListener('click', function (e) {
        var removeBtn = e.target.closest('[data-cms-jadwal-remove]');
        if (!removeBtn || !wrap || !wrap.contains(removeBtn)) return;
        var row = removeBtn.closest('.cms-jadwal-middle-row');
        if (!row) return;
        e.preventDefault();
        var headerVal = row.querySelector('input[data-header-input]')?.value?.trim() || 'kolom ini';
        if (typeof window.adminConfirm !== 'function') {
            if (confirm('Hapus kolom "' + headerVal + '"?')) removeMiddle(row);
            return;
        }
        window.adminConfirm({
            title: 'Hapus kolom?',
            message: 'Kolom "' + headerVal + '" akan dihapus dari tabel mendatang dan selesai.',
            confirmLabel: 'Hapus kolom',
        }).then(function (ok) {
            if (ok) removeMiddle(row);
        });
    });
})();
</script>
