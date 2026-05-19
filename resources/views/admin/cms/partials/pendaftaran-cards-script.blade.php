@once
    @push('scripts')
        <script>
            (function () {
                function initCmsPendaftaranCards() {
                    var wrap = document.getElementById('cms-pendaftaran-cards-wrap');
                    var addBtn = document.querySelector('[data-cms-pendaftaran-cards-add]');
                    if (!wrap || !addBtn) {
                        return;
                    }
                    if (addBtn.dataset.cmsPendaftaranCardsBound === '1') {
                        return;
                    }
                    addBtn.dataset.cmsPendaftaranCardsBound = '1';

                    var maxRows = 12;

                    function rows() {
                        return wrap.querySelectorAll('.cms-pendaftaran-card-row');
                    }

                    function reindexFaIcons(row) {
                        row.querySelectorAll('input[data-cms-fa-icon-input]').forEach(function (inp) {
                            var id = 'fa-icon-' + inp.name.replace(/[^a-z0-9_-]/gi, '-');
                            inp.id = id;
                            var box = inp.closest('[data-cms-fa-icon-field]');
                            if (box) {
                                var lab = box.querySelector('label[for]');
                                if (lab) {
                                    lab.setAttribute('for', id);
                                }
                            }
                            inp.dispatchEvent(new Event('input', { bubbles: true }));
                        });
                    }

                    function syncRowCountField() {
                        var countInp = document.getElementById('cms-pendaftaran-cards-row-count');
                        if (countInp) {
                            countInp.value = String(rows().length);
                        }
                    }

                    function reindexCards() {
                        rows().forEach(function (row, i) {
                            row.querySelectorAll('[name^="cards["]').forEach(function (el) {
                                el.name = el.name.replace(/cards\[\d+\]/, 'cards[' + i + ']');
                            });
                            reindexFaIcons(row);
                        });
                        syncRowCountField();
                    }

                    function updateAddRemoveUi() {
                        var n = rows().length;
                        addBtn.disabled = n >= maxRows;
                        addBtn.classList.toggle('opacity-40', n >= maxRows);
                        addBtn.classList.toggle('pointer-events-none', n >= maxRows);
                        addBtn.setAttribute('aria-disabled', n >= maxRows ? 'true' : 'false');
                        rows().forEach(function (row) {
                            var rm = row.querySelector('[data-cms-pendaftaran-card-remove]');
                            if (!rm) {
                                return;
                            }
                            rm.disabled = n <= 1;
                            rm.classList.toggle('opacity-40', n <= 1);
                            rm.classList.toggle('pointer-events-none', n <= 1);
                        });
                    }

                    function clearCardRow(row) {
                        var keyInp = row.querySelector('input[name$="[key]"]');
                        if (keyInp) {
                            keyInp.value = 'c' + Date.now();
                        }
                        row.querySelectorAll('input:not([type="hidden"]), textarea').forEach(function (inp) {
                            if (inp.name && inp.name.endsWith('[key]')) {
                                return;
                            }
                            inp.value = '';
                        });
                        var ctaInp = row.querySelector('input[name$="[cta_label]"]');
                        if (ctaInp) {
                            ctaInp.value = 'Isi formulir';
                        }
                        var arrowInp = row.querySelector('input[data-cms-fa-icon-input][name$="[arrow_icon]"]');
                        if (arrowInp) {
                            arrowInp.value = 'fa-solid fa-arrow-right';
                            arrowInp.dispatchEvent(new Event('input', { bubbles: true }));
                        }
                        var titleHeading = row.querySelector('h3');
                        if (titleHeading) {
                            titleHeading.textContent = 'Kartu baru';
                        }
                        row.querySelectorAll('a[href*="/setting/pendaftaran/kartu/"]').forEach(function (link) {
                            link.remove();
                        });
                        var preview = row.querySelector('[data-cms-pendaftaran-slug-preview]');
                        if (preview) {
                            preview.textContent = '…';
                        }
                    }

                    wrap.addEventListener('input', function (e) {
                        var slugInp = e.target.closest('input[name$="[url]"]');
                        if (slugInp && wrap.contains(slugInp)) {
                            var row = slugInp.closest('.cms-pendaftaran-card-row');
                            var preview = row ? row.querySelector('[data-cms-pendaftaran-slug-preview]') : null;
                            if (preview) {
                                preview.textContent = slugInp.value.trim() || '…';
                            }
                            return;
                        }
                        var titleInp = e.target.closest('input[name$="[title]"]');
                        if (!titleInp || !wrap.contains(titleInp)) {
                            return;
                        }
                        var titleRow = titleInp.closest('.cms-pendaftaran-card-row');
                        var titleHeading = titleRow ? titleRow.querySelector('h3') : null;
                        if (titleHeading) {
                            titleHeading.textContent = titleInp.value.trim() || 'Kartu baru';
                        }
                    });

                    wrap.addEventListener('click', function (e) {
                        var t = e.target.closest('[data-cms-pendaftaran-card-remove]');
                        if (!t || !wrap.contains(t) || t.disabled) {
                            return;
                        }
                        e.preventDefault();
                        var row = t.closest('.cms-pendaftaran-card-row');
                        if (!row) {
                            return;
                        }
                        var cardTitle = row.querySelector('input[name$="[title]"]');
                        var titleLabel = cardTitle && cardTitle.value.trim() !== '' ? cardTitle.value.trim() : 'kartu ini';
                        var removeRow = function () {
                            row.remove();
                            reindexCards();
                            updateAddRemoveUi();
                        };
                        if (typeof window.adminConfirmAction === 'function') {
                            window.adminConfirmAction({
                                title: 'Hapus kartu pendaftaran?',
                                message: 'Kartu "' + titleLabel + '" akan dihapus. Simpan halaman agar perubahan permanen.',
                                confirmLabel: 'Hapus kartu',
                            }, removeRow);
                        } else if (window.confirm('Hapus kartu "' + titleLabel + '"?')) {
                            removeRow();
                        }
                    });

                    addBtn.addEventListener('click', function (e) {
                        e.preventDefault();
                        if (addBtn.disabled) {
                            return;
                        }
                        var list = rows();
                        if (list.length >= maxRows) {
                            return;
                        }
                        var last = list[list.length - 1];
                        if (!last) {
                            return;
                        }
                        var clone = last.cloneNode(true);
                        wrap.appendChild(clone);
                        reindexCards();
                        var newRow = rows()[rows().length - 1];
                        clearCardRow(newRow);
                        updateAddRemoveUi();
                        var titleField = newRow.querySelector('input[name$="[title]"]');
                        if (titleField) {
                            titleField.focus();
                            newRow.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        }
                    });

                    var form = wrap.closest('form');
                    if (form) {
                        form.addEventListener('submit', function () {
                            syncRowCountField();
                        });
                    }

                    updateAddRemoveUi();
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initCmsPendaftaranCards);
                } else {
                    initCmsPendaftaranCards();
                }
            })();
        </script>
    @endpush
@endonce
