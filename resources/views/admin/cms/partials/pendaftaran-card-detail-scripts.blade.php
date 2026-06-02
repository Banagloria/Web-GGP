@once
    @push('scripts')
        <script>
            (function () {
                function initPendaftaranCardDetailEditors() {
    function confirmRemove(opts, action) {
        if (typeof window.adminConfirmAction === 'function') {
            window.adminConfirmAction(opts, action);
            return;
        }
        if (window.confirm(opts.message || 'Hapus item ini?')) {
            action();
        }
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

    function syncFieldTypeUi(row) {
        var typeSel = row.querySelector('[data-pendaftaran-field-type]');
        if (!typeSel) {
            return;
        }
        var type = typeSel.value;
        var textareaBox = row.querySelector('[data-pendaftaran-field-textarea]');
        var selectBox = row.querySelector('[data-pendaftaran-field-select]');
        if (textareaBox) {
            textareaBox.classList.toggle('hidden', type !== 'textarea');
        }
        if (selectBox) {
            selectBox.classList.toggle('hidden', type !== 'select');
        }
    }

    function reindexSelectOptions(fieldRow, si, fi) {
        fieldRow.querySelectorAll('[data-pendaftaran-select-option]').forEach(function (optRow, oi) {
            optRow.querySelectorAll('input[name*="[select_options]"]').forEach(function (inp) {
                inp.name = inp.name.replace(
                    /\[select_options\]\[\d+\]/,
                    '[select_options][' + oi + ']'
                );
            });
        });
    }

    function reindexFieldRows(wrap) {
        var si = wrap.getAttribute('data-section-index');
        wrap.querySelectorAll('[data-pendaftaran-field-row]').forEach(function (row, fi) {
            row.querySelectorAll('[name^="sections["]').forEach(function (el) {
                el.name = el.name.replace(/sections\[\d+\]\[fields\]\[\d+\]/, 'sections[' + si + '][fields][' + fi + ']');
            });
            reindexSelectOptions(row, si, fi);
            reindexFaIcons(row);
            syncFieldTypeUi(row);
        });
        updateFieldAddRemoveUi(wrap);
    }

    function updateFieldAddRemoveUi(wrap) {
        var rows = wrap.querySelectorAll('[data-pendaftaran-field-row]');
        var addBtn = document.querySelector('[data-pendaftaran-field-add][data-section-index="' + wrap.getAttribute('data-section-index') + '"]');
        if (addBtn) {
            addBtn.disabled = rows.length >= 40;
            addBtn.classList.toggle('opacity-40', rows.length >= 40);
            addBtn.classList.toggle('pointer-events-none', rows.length >= 40);
        }
        rows.forEach(function (row) {
            var rm = row.querySelector('[data-pendaftaran-field-remove]');
            if (!rm) {
                return;
            }
            rm.disabled = rows.length <= 1;
            rm.classList.toggle('opacity-40', rows.length <= 1);
            rm.classList.toggle('pointer-events-none', rows.length <= 1);
        });
    }

    function clearFieldRow(row) {
        row.querySelectorAll('input[type="text"], input[type="number"]').forEach(function (inp) {
            if (!inp.name || inp.name.indexOf('[required]') === -1) {
                inp.value = '';
            }
        });
        row.querySelectorAll('textarea').forEach(function (el) {
            el.value = '';
        });
        var typeSel = row.querySelector('[data-pendaftaran-field-type]');
        if (typeSel) {
            typeSel.value = 'text';
        }
        var widthSel = row.querySelector('select[name$="[width]"]');
        if (widthSel) {
            widthSel.value = 'full';
        }
        var req = row.querySelector('input[type="checkbox"][name$="[required]"]');
        if (req) {
            req.checked = false;
        }
        var iconInp = row.querySelector('input[data-cms-fa-icon-input]');
        if (iconInp) {
            iconInp.value = '';
            iconInp.dispatchEvent(new Event('input', { bubbles: true }));
        }
        var optBox = row.querySelector('[data-pendaftaran-select-options]');
        if (optBox) {
            optBox.querySelectorAll('[data-pendaftaran-select-option]').forEach(function (opt, i) {
                if (i > 0) {
                    opt.remove();
                } else {
                    opt.querySelectorAll('input').forEach(function (inp) {
                        inp.value = '';
                    });
                }
            });
        }
        syncFieldTypeUi(row);
    }

    var sectionsWrap = document.getElementById('cms-pendaftaran-sections-wrap');
    var sectionAdd = document.getElementById('cms-pendaftaran-section-add');

    function syncSectionTitlePreview(row) {
        var titleInp = row.querySelector('[data-pendaftaran-section-title]');
        var preview = row.querySelector('[data-pendaftaran-section-title-preview]');
        if (!titleInp || !preview) {
            return;
        }
        preview.textContent = titleInp.value;
    }

    function reindexSections() {
        if (!sectionsWrap) {
            return;
        }
        sectionsWrap.querySelectorAll('[data-pendaftaran-section-row]').forEach(function (row, si) {
            row.querySelectorAll('[name^="sections["]').forEach(function (el) {
                el.name = el.name.replace(/^sections\[\d+\]/, 'sections[' + si + ']');
            });
            var fieldsWrap = row.querySelector('[data-pendaftaran-fields-wrap]');
            if (fieldsWrap) {
                fieldsWrap.setAttribute('data-section-index', String(si));
                reindexFieldRows(fieldsWrap);
            }
            var addFieldBtn = row.querySelector('[data-pendaftaran-field-add]');
            if (addFieldBtn) {
                addFieldBtn.setAttribute('data-section-index', String(si));
            }
            reindexFaIcons(row);
            syncSectionTitlePreview(row);
        });
        updateSectionUi();
    }

    function updateSectionUi() {
        if (!sectionsWrap || !sectionAdd) {
            return;
        }
        var rows = sectionsWrap.querySelectorAll('[data-pendaftaran-section-row]');
        sectionAdd.disabled = rows.length >= 20;
        sectionAdd.classList.toggle('opacity-40', rows.length >= 20);
        sectionAdd.classList.toggle('pointer-events-none', rows.length >= 20);
        rows.forEach(function (row) {
            var rm = row.querySelector('[data-pendaftaran-section-remove]');
            if (!rm) {
                return;
            }
            rm.disabled = rows.length <= 1;
            rm.classList.toggle('opacity-40', rows.length <= 1);
            rm.classList.toggle('pointer-events-none', rows.length <= 1);
        });
    }

    function assignNewSectionKey(row) {
        var keyInp = row.querySelector('input[name$="[key]"]');
        if (keyInp) {
            keyInp.value = 'bagian_' + Date.now();
        }
    }

    if (sectionsWrap) {
        sectionsWrap.addEventListener('click', function (e) {
            var rm = e.target.closest('[data-pendaftaran-section-remove]');
            if (!rm || !sectionsWrap.contains(rm) || rm.disabled) {
                return;
            }
            e.preventDefault();
            var sectionRow = rm.closest('[data-pendaftaran-section-row]');
            var titleInp = sectionRow.querySelector('[data-pendaftaran-section-title]');
            var sectionLabel = titleInp && titleInp.value.trim() !== '' ? titleInp.value.trim() : 'bagian ini';
            confirmRemove({
                title: 'Hapus bagian?',
                message: 'Bagian "' + sectionLabel + '" beserta semua input di dalamnya akan dihapus.',
                confirmLabel: 'Hapus',
            }, function () {
                sectionRow.remove();
                reindexSections();
            });
        });

        sectionsWrap.addEventListener('input', function (e) {
            if (e.target.matches('[data-pendaftaran-section-title]')) {
                syncSectionTitlePreview(e.target.closest('[data-pendaftaran-section-row]'));
            }
        });

        reindexSections();
    }

    if (sectionAdd && sectionsWrap) {
        sectionAdd.addEventListener('click', function () {
            if (sectionAdd.disabled) {
                return;
            }
            var tpl = document.getElementById('cms-pendaftaran-section-template');
            var clone = null;
            if (tpl && tpl.content && tpl.content.firstElementChild) {
                clone = tpl.content.firstElementChild.cloneNode(true);
            } else {
                var rows = sectionsWrap.querySelectorAll('[data-pendaftaran-section-row]');
                var last = rows[rows.length - 1];
                if (!last) {
                    return;
                }
                clone = last.cloneNode(true);
                clone.querySelectorAll('input[type="text"], input[type="number"], textarea').forEach(function (el) {
                    if (el.name && el.name.indexOf('[key]') !== -1) {
                        return;
                    }
                    el.value = '';
                });
                clone.querySelectorAll('input[data-cms-fa-icon-input]').forEach(function (el) {
                    el.value = '';
                    el.dispatchEvent(new Event('input', { bubbles: true }));
                });
                var fieldsWrap = clone.querySelector('[data-pendaftaran-fields-wrap]');
                if (fieldsWrap) {
                    fieldsWrap.querySelectorAll('[data-pendaftaran-field-row]').forEach(function (fieldRow, i) {
                        if (i > 0) {
                            fieldRow.remove();
                        } else {
                            clearFieldRow(fieldRow);
                        }
                    });
                }
            }
            sectionsWrap.appendChild(clone);
            assignNewSectionKey(clone);
            reindexSections();
            var titleInp = clone.querySelector('[data-pendaftaran-section-title]');
            if (titleInp) {
                titleInp.focus();
            }
        });
    }

    document.querySelectorAll('[data-pendaftaran-fields-wrap]').forEach(function (wrap) {
        reindexFieldRows(wrap);
    });

    document.addEventListener('change', function (e) {
        if (e.target.matches('[data-pendaftaran-field-type]')) {
            syncFieldTypeUi(e.target.closest('[data-pendaftaran-field-row]'));
        }
    });

    document.addEventListener('click', function (e) {
        var fieldRm = e.target.closest('[data-pendaftaran-field-remove]');
        if (fieldRm && !fieldRm.disabled) {
            e.preventDefault();
            var fieldRow = fieldRm.closest('[data-pendaftaran-field-row]');
            var fieldsWrap = fieldRow ? fieldRow.closest('[data-pendaftaran-fields-wrap]') : null;
            if (fieldsWrap) {
                var labelInp = fieldRow.querySelector('input[name$="[label]"]');
                var fieldLabel = labelInp && labelInp.value.trim() !== '' ? labelInp.value.trim() : 'input ini';
                confirmRemove({
                    title: 'Hapus input?',
                    message: 'Field "' + fieldLabel + '" akan dihapus dari formulir.',
                    confirmLabel: 'Hapus',
                }, function () {
                    fieldRow.remove();
                    reindexFieldRows(fieldsWrap);
                });
            }
            return;
        }

        var addBtn = e.target.closest('[data-pendaftaran-field-add]');
        if (addBtn && !addBtn.disabled) {
            var si = addBtn.getAttribute('data-section-index');
            var wrap = document.querySelector('[data-pendaftaran-fields-wrap][data-section-index="' + si + '"]');
            if (wrap) {
                var rows = wrap.querySelectorAll('[data-pendaftaran-field-row]');
                var last = rows[rows.length - 1];
                if (last) {
                    var clone = last.cloneNode(true);
                    wrap.appendChild(clone);
                    clearFieldRow(clone);
                    reindexFieldRows(wrap);
                }
            }
            return;
        }

        var addOpt = e.target.closest('[data-pendaftaran-select-option-add]');
        if (addOpt) {
            var fieldRow = addOpt.closest('[data-pendaftaran-field-row]');
            var box = fieldRow.querySelector('[data-pendaftaran-select-options]');
            var rows = box.querySelectorAll('[data-pendaftaran-select-option]');
            var last = rows[rows.length - 1];
            if (!last) {
                return;
            }
            var clone = last.cloneNode(true);
            clone.querySelectorAll('input').forEach(function (inp) {
                inp.value = '';
            });
            box.appendChild(clone);
            var wrap = fieldRow.closest('[data-pendaftaran-fields-wrap]');
            if (wrap) {
                reindexFieldRows(wrap);
            }
        }

        var rmOpt = e.target.closest('[data-pendaftaran-select-option-remove]');
        if (rmOpt) {
            e.preventDefault();
            var optRow = rmOpt.closest('[data-pendaftaran-select-option]');
            var box = optRow.parentElement;
            if (box.querySelectorAll('[data-pendaftaran-select-option]').length <= 1) {
                optRow.querySelectorAll('input').forEach(function (inp) {
                    inp.value = '';
                });
                return;
            }
            confirmRemove({
                title: 'Hapus opsi?',
                message: 'Opsi pilihan ini akan dihapus dari daftar.',
                confirmLabel: 'Hapus',
            }, function () {
                optRow.remove();
                var fieldRow = rmOpt.closest('[data-pendaftaran-field-row]');
                var fieldsWrap = fieldRow.closest('[data-pendaftaran-fields-wrap]');
                if (fieldsWrap) {
                    reindexFieldRows(fieldsWrap);
                }
            });
        }
    });

    var stepsWrap = document.getElementById('cms-pendaftaran-steps-wrap');
    var stepAdd = document.getElementById('cms-pendaftaran-step-add');

    function reindexSteps() {
        if (!stepsWrap) {
            return;
        }
        stepsWrap.querySelectorAll('[data-pendaftaran-step-row]').forEach(function (row, i) {
            row.querySelectorAll('input[name^="info_panel[steps]"]').forEach(function (inp) {
                inp.name = 'info_panel[steps][' + i + ']';
            });
        });
        updateStepUi();
    }

    function updateStepUi() {
        if (!stepsWrap || !stepAdd) {
            return;
        }
        var rows = stepsWrap.querySelectorAll('[data-pendaftaran-step-row]');
        stepAdd.disabled = rows.length >= 12;
        stepAdd.classList.toggle('opacity-40', rows.length >= 12);
        stepAdd.classList.toggle('pointer-events-none', rows.length >= 12);
        rows.forEach(function (row) {
            var rm = row.querySelector('[data-pendaftaran-step-remove]');
            if (!rm) {
                return;
            }
            rm.disabled = rows.length <= 1;
            rm.classList.toggle('opacity-40', rows.length <= 1);
            rm.classList.toggle('pointer-events-none', rows.length <= 1);
        });
    }

    if (stepsWrap) {
        stepsWrap.addEventListener('click', function (e) {
            var rm = e.target.closest('[data-pendaftaran-step-remove]');
            if (!rm || rm.disabled) {
                return;
            }
            e.preventDefault();
            var stepRow = rm.closest('[data-pendaftaran-step-row]');
            var stepText = stepRow.querySelector('input[name^="info_panel[steps]"]');
            var stepLabel = stepText && stepText.value.trim() !== '' ? stepText.value.trim() : 'langkah ini';
            confirmRemove({
                title: 'Hapus langkah?',
                message: 'Langkah "' + stepLabel + '" akan dihapus dari alur pendaftaran.',
                confirmLabel: 'Hapus',
            }, function () {
                stepRow.remove();
                reindexSteps();
            });
        });
        reindexSteps();
    }

    if (stepAdd && stepsWrap) {
        stepAdd.addEventListener('click', function () {
            if (stepAdd.disabled) {
                return;
            }
            var rows = stepsWrap.querySelectorAll('[data-pendaftaran-step-row]');
            var last = rows[rows.length - 1];
            var clone = last.cloneNode(true);
            clone.querySelector('input').value = '';
            stepsWrap.appendChild(clone);
            reindexSteps();
        });
    }

    var tipsWrap = document.getElementById('cms-pendaftaran-tips-wrap');
    var tipAdd = document.getElementById('cms-pendaftaran-tip-add');

    function reindexTips() {
        if (!tipsWrap) {
            return;
        }
        tipsWrap.querySelectorAll('[data-pendaftaran-tip-row]').forEach(function (row, i) {
            row.querySelectorAll('[name^="info_panel[tips]"]').forEach(function (el) {
                el.name = el.name.replace(/info_panel\[tips\]\[\d+\]/, 'info_panel[tips][' + i + ']');
            });
            reindexFaIcons(row);
        });
        updateTipUi();
    }

    function updateTipUi() {
        if (!tipsWrap || !tipAdd) {
            return;
        }
        var rows = tipsWrap.querySelectorAll('[data-pendaftaran-tip-row]');
        tipAdd.disabled = rows.length >= 12;
        tipAdd.classList.toggle('opacity-40', rows.length >= 12);
        tipAdd.classList.toggle('pointer-events-none', rows.length >= 12);
        rows.forEach(function (row) {
            var rm = row.querySelector('[data-pendaftaran-tip-remove]');
            if (!rm) {
                return;
            }
            rm.disabled = rows.length <= 1;
            rm.classList.toggle('opacity-40', rows.length <= 1);
            rm.classList.toggle('pointer-events-none', rows.length <= 1);
        });
    }

    if (tipsWrap) {
        tipsWrap.addEventListener('click', function (e) {
            var rm = e.target.closest('[data-pendaftaran-tip-remove]');
            if (!rm || rm.disabled) {
                return;
            }
            e.preventDefault();
            var tipRow = rm.closest('[data-pendaftaran-tip-row]');
            confirmRemove({
                title: 'Hapus tips?',
                message: 'Tips ini akan dihapus dari panel informasi.',
                confirmLabel: 'Hapus',
            }, function () {
                tipRow.remove();
                reindexTips();
            });
        });
        reindexTips();
    }

    if (tipAdd && tipsWrap) {
        tipAdd.addEventListener('click', function () {
            if (tipAdd.disabled) {
                return;
            }
            var rows = tipsWrap.querySelectorAll('[data-pendaftaran-tip-row]');
            var last = rows[rows.length - 1];
            var clone = last.cloneNode(true);
            clone.querySelectorAll('input[type="text"], textarea').forEach(function (el) {
                el.value = '';
            });
            var iconInp = clone.querySelector('input[data-cms-fa-icon-input]');
            if (iconInp) {
                iconInp.value = '';
                iconInp.dispatchEvent(new Event('input', { bubbles: true }));
            }
            tipsWrap.appendChild(clone);
            reindexTips();
        });
    }
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initPendaftaranCardDetailEditors);
                } else {
                    initPendaftaranCardDetailEditors();
                }
            })();
        </script>
    @endpush
@endonce
