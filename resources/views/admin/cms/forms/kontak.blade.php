@include('admin.cms.partials.breadcrumb-editor', ['pageKey' => 'kontak', 'data' => $data])

<div class="mt-6 space-y-4">
    <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(10rem,13rem)] sm:items-end">
        <div>
            <x-admin-field-label class="text-sm text-slate-300">Judul utama halaman</x-admin-field-label>
            <input name="h1" value="{{ old('h1', $data['h1'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
        </div>
        @include('admin.cms.partials.page-icon-field', ['pageKey' => 'kontak', 'iconKey' => 'h1', 'data' => $data])
    </div>
    <div class="rounded-lg border border-white/10 bg-church-surface/30 p-4 sm:p-5">
        <h2 class="mb-4 text-sm font-semibold text-church-fg">Blok formulir</h2>
        <div class="space-y-4">
            <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(9rem,11rem)] sm:items-end">
                <div>
                    <x-admin-field-label class="text-sm text-slate-300">Judul formulir</x-admin-field-label>
                    <input name="form_heading" value="{{ old('form_heading', $data['form_heading'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
                </div>
                @include('admin.cms.partials.page-icon-field', ['pageKey' => 'kontak', 'iconKey' => 'form_heading', 'data' => $data])
            </div>
            <div>
                <x-admin-field-label class="text-sm text-slate-300">Petunjuk di bawah judul formulir</x-admin-field-label>
                <textarea name="form_hint" rows="2" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-sm text-church-fg">{{ old('form_hint', $data['form_hint'] ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(9rem,11rem)] sm:items-end">
            <div>
                <x-admin-field-label class="text-sm text-slate-300">Label tombol kirim</x-admin-field-label>
                <input name="submit_label" value="{{ old('submit_label', $data['submit_label'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
            </div>
            @include('admin.cms.partials.page-icon-field', ['pageKey' => 'kontak', 'iconKey' => 'submit', 'data' => $data])
        </div>
        <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(9rem,11rem)] sm:items-end">
            <div>
                <x-admin-field-label class="text-sm text-slate-300">Pesan sukses setelah kirim</x-admin-field-label>
                <input name="success_message" value="{{ old('success_message', $data['success_message'] ?? '') }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-church-fg">
            </div>
            @include('admin.cms.partials.page-icon-field', ['pageKey' => 'kontak', 'iconKey' => 'status_success', 'data' => $data])
        </div>
    </div>
</div>

<div class="mt-8">
    <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
        <h2 class="text-sm font-semibold text-church-fg">Field formulir</h2>
 <button type="button" id="cms-kontak-add-field" class="public-btn-hover rounded-md border border-church-gold/40 bg-church-gold/10 px-3 py-1.5 text-xs font-semibold text-church-gold">Tambah field</button>
    </div>
    <div id="cms-kontak-fields" class="space-y-4">
        @php $ff = old('form_fields', $data['form_fields'] ?? []); @endphp
        @foreach ($ff as $i => $f)
            @include('admin.cms.forms._kontak_field_row', ['i' => $i, 'f' => $f])
        @endforeach
    </div>
</div>

<script>
(function () {
    var wrap = document.getElementById('cms-kontak-fields');
    var btn = document.getElementById('cms-kontak-add-field');
    if (!wrap || !btn) return;

    function nextIndex() {
        var max = -1;
        wrap.querySelectorAll('input[name^="form_fields["]').forEach(function (inp) {
            var m = inp.name.match(/^form_fields\[(\d+)\]/);
            if (m) max = Math.max(max, parseInt(m[1], 10));
        });
        return max + 1;
    }

    function bindRemove(row) {
        var rm = row.querySelector('[data-cms-kontak-remove]');
        if (rm) rm.addEventListener('click', function () {
            if (wrap.querySelectorAll('.cms-kontak-field-row').length <= 1) return;
            row.remove();
        });
    }

    wrap.querySelectorAll('.cms-kontak-field-row').forEach(bindRemove);

    btn.addEventListener('click', function () {
        var rows = wrap.querySelectorAll('.cms-kontak-field-row');
        var last = rows[rows.length - 1];
        if (!last) return;
        var ni = nextIndex();
        var clone = last.cloneNode(true);
        clone.querySelectorAll('[name]').forEach(function (el) {
            el.name = el.name.replace(/form_fields\[\d+\]/, 'form_fields[' + ni + ']');
            if (el.type === 'checkbox') el.checked = false;
            else el.value = '';
        });
        wrap.appendChild(clone);
        bindRemove(clone);
    });
})();
</script>
