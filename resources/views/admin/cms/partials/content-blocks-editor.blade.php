@php
    use App\Support\CmsContentBlocks;

    $blocksKey = $blocksKey ?? 'blocks';
    $htmlKey = $htmlKey ?? 'body';
    $idPrefix = $idPrefix ?? 'cms-content-blocks-';
    $legend = $legend ?? 'Isi halaman';
    $fieldsetClass = $fieldsetClass ?? 'public-card-hover mt-4 space-y-4 rounded-lg border border-white/10 bg-church-card p-6 sm:p-8';

    $storedBlocks = $data[$blocksKey] ?? null;
    $editorBlocks = CmsContentBlocks::editorBlocksFromStorage(
        is_array($storedBlocks) ? $storedBlocks : null,
        (string) ($data[$htmlKey] ?? '')
    );

    $defaultListBlock = static fn (string $type) => ['type' => $type, 'items' => ['']];
@endphp

<fieldset class="{{ $fieldsetClass }}">
    <x-admin-field-label as="legend">{{ $legend }}</x-admin-field-label>
    <div class="flex flex-wrap items-end justify-end gap-2">
        <label class="sr-only" for="{{ $idPrefix }}type-select">Jenis blok baru</label>
        <select
            id="{{ $idPrefix }}type-select"
            class="rounded-md border border-white/15 bg-church-surface px-3 py-1.5 text-xs text-church-fg"
        >
            @foreach (CmsContentBlocks::selectOptions() as $option)
                <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
            @endforeach
        </select>
        <button
            type="button"
            id="{{ $idPrefix }}add"
            class="public-btn-hover rounded-md border border-church-gold/40 bg-church-gold/10 px-3 py-1.5 text-xs font-semibold text-church-gold"
        >
            Tambah blok
        </button>
    </div>

    <div id="{{ $idPrefix }}wrap" class="space-y-4">
        @foreach ($editorBlocks as $i => $block)
            @include('admin.cms.partials._content_block_row', ['index' => $i, 'block' => $block, 'blocksKey' => $blocksKey])
        @endforeach
    </div>
</fieldset>

@foreach (CmsContentBlocks::selectOptions() as $option)
    @php
        $tplType = $option['value'];
        $tplBlock = $tplType === 'p'
            ? ['type' => 'p', 'text' => '']
            : (CmsContentBlocks::isHeading($tplType)
                ? ['type' => $tplType, 'text' => '']
                : $defaultListBlock($tplType));
    @endphp
    <template id="{{ $idPrefix }}template-{{ $tplType }}">
        @include('admin.cms.partials._content_block_row', ['index' => '__INDEX__', 'block' => $tplBlock, 'blocksKey' => $blocksKey])
    </template>
@endforeach

<script>
(function () {
    var blocksKey = @json($blocksKey);
    var idPrefix = @json($idPrefix);
    var wrap = document.getElementById(idPrefix + 'wrap');
    var addBtn = document.getElementById(idPrefix + 'add');
    var typeSelect = document.getElementById(idPrefix + 'type-select');
    if (!wrap || !addBtn || !typeSelect) return;

    var maxBlocks = 80;
    var formBoundKey = 'cmsBlocksBound' + idPrefix.replace(/[^a-zA-Z0-9]/g, '');

    function rows() {
        return wrap.querySelectorAll('.cms-content-block-row');
    }

    function reindexBlocks() {
        rows().forEach(function (row, blockIndex) {
            var typeInput = row.querySelector('[data-block-type-input]');

            if (typeInput) {
                typeInput.name = blocksKey + '[' + blockIndex + '][type]';
            }

            var textField = row.querySelector('textarea[name*="[text]"], input[name*="[text]"]');
            if (textField) {
                textField.name = blocksKey + '[' + blockIndex + '][text]';
            }

            var itemInputs = row.querySelectorAll('.cms-block-list-item input[type="text"]');
            itemInputs.forEach(function (input, itemIndex) {
                input.name = blocksKey + '[' + blockIndex + '][items][' + itemIndex + ']';
            });
        });
    }

    function bindFormSubmit() {
        var form = wrap.closest('form');
        if (!form || form.dataset[formBoundKey]) return;
        form.dataset[formBoundKey] = '1';
        form.addEventListener('submit', function () {
            reindexBlocks();
        });
    }

    function updateUi() {
        var n = rows().length;
        addBtn.disabled = n >= maxBlocks;
        addBtn.classList.toggle('opacity-40', n >= maxBlocks);
        addBtn.classList.toggle('pointer-events-none', n >= maxBlocks);
        rows().forEach(function (row) {
            var rm = row.querySelector('[data-cms-block-remove]');
            if (!rm) return;
            rm.disabled = n <= 1;
            rm.classList.toggle('opacity-40', n <= 1);
            rm.classList.toggle('pointer-events-none', n <= 1);
        });
    }

    function bindListItemControls(row) {
        var listWrap = row.querySelector('[data-block-list-items]');
        if (!listWrap) return;

        var addItemBtn = row.querySelector('[data-cms-block-list-item-add]');
        if (addItemBtn && !addItemBtn.dataset.bound) {
            addItemBtn.dataset.bound = '1';
            addItemBtn.addEventListener('click', function () {
                var items = listWrap.querySelectorAll('.cms-block-list-item');
                var last = items[items.length - 1];
                if (!last) return;
                var clone = last.cloneNode(true);
                var input = clone.querySelector('input');
                if (input) input.value = '';
                listWrap.appendChild(clone);
                reindexBlocks();
                bindListItemRemove(clone);
            });
        }

        listWrap.querySelectorAll('.cms-block-list-item').forEach(bindListItemRemove);
    }

    function bindListItemRemove(itemRow) {
        var btn = itemRow.querySelector('[data-cms-block-list-item-remove]');
        if (!btn || btn.dataset.bound) return;
        btn.dataset.bound = '1';
        btn.addEventListener('click', function () {
            var listWrap = itemRow.closest('[data-block-list-items]');
            if (!listWrap) return;
            var items = listWrap.querySelectorAll('.cms-block-list-item');
            if (items.length <= 1) {
                var input = itemRow.querySelector('input');
                if (input) input.value = '';
                return;
            }
            itemRow.remove();
            reindexBlocks();
        });
    }

    function bindBlockRow(row) {
        bindListItemControls(row);
        var rm = row.querySelector('[data-cms-block-remove]');
        if (rm && !rm.dataset.bound) {
            rm.dataset.bound = '1';
            rm.addEventListener('click', function () {
                if (rows().length <= 1) return;
                row.remove();
                reindexBlocks();
                updateUi();
            });
        }
    }

    rows().forEach(bindBlockRow);
    reindexBlocks();
    bindFormSubmit();

    addBtn.addEventListener('click', function () {
        if (addBtn.disabled) return;
        var type = typeSelect.value || 'p';
        var tpl = document.getElementById(idPrefix + 'template-' + type);
        if (!tpl || !tpl.content) return;
        var index = rows().length;
        var html = tpl.innerHTML.replace(/__INDEX__/g, String(index));
        var holder = document.createElement('div');
        holder.innerHTML = html.trim();
        var row = holder.firstElementChild;
        if (!row) return;
        wrap.appendChild(row);
        reindexBlocks();
        bindBlockRow(row);
        updateUi();
        var focusTarget = row.querySelector('textarea, input[type="text"]');
        if (focusTarget) focusTarget.focus();
    });

    updateUi();
})();
</script>
