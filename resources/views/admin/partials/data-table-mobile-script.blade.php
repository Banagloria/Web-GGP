<script>
    (function () {
        var mobileQuery = window.matchMedia('(max-width: 767.98px)');

        function headerLabel(th) {
            return (th.textContent || '')
                .replace(/\s+/g, ' ')
                .trim();
        }

        function isActionsHeader(label, td) {
            if (/^(aksi|action|actions)$/i.test(label)) {
                return true;
            }

            return Boolean(td.querySelector('.admin-table-actions'));
        }

        function enhanceTable(wrap) {
            var table = wrap.querySelector('table');
            if (!table) {
                return;
            }

            var headers = Array.prototype.map.call(
                table.querySelectorAll('thead th'),
                headerLabel
            );

            Array.prototype.forEach.call(table.querySelectorAll('tbody tr'), function (tr) {
                var cells = Array.prototype.slice.call(tr.querySelectorAll(':scope > td'));
                if (cells.length === 0) {
                    return;
                }

                if (cells.length === 1 && cells[0].hasAttribute('colspan')) {
                    tr.classList.add('admin-data-table__empty');
                    cells[0].classList.add('admin-data-table__empty-cell');
                    cells[0].removeAttribute('data-label');
                    return;
                }

                tr.classList.remove('admin-data-table__empty');
                cells.forEach(function (td, index) {
                    var label = headers[index] || '';
                    if (label !== '') {
                        td.setAttribute('data-label', label);
                    } else {
                        td.removeAttribute('data-label');
                    }

                    td.classList.toggle(
                        'admin-data-table__cell--actions',
                        isActionsHeader(label, td)
                    );
                    td.classList.remove('admin-data-table__empty-cell');
                });
            });
        }

        function applyMode() {
            var wraps = document.querySelectorAll('.admin-data-table-wrap');
            Array.prototype.forEach.call(wraps, function (wrap) {
                if (mobileQuery.matches) {
                    enhanceTable(wrap);
                    wrap.classList.add('admin-data-table-wrap--cards');
                } else {
                    wrap.classList.remove('admin-data-table-wrap--cards');
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', applyMode);
        } else {
            applyMode();
        }

        if (typeof mobileQuery.addEventListener === 'function') {
            mobileQuery.addEventListener('change', applyMode);
        } else if (typeof mobileQuery.addListener === 'function') {
            mobileQuery.addListener(applyMode);
        }
    })();
</script>
