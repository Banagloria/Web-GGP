@once
    @push('scripts')
        <script>
            (function () {
                function fixNestedFormsIn(mainForm) {
                    var nested = mainForm.querySelectorAll('form');
                    if (!nested.length) {
                        return;
                    }
                    nested.forEach(function (inner) {
                        if (!mainForm.contains(inner)) {
                            return;
                        }
                        mainForm.parentNode.insertBefore(inner, mainForm.nextSibling);
                    });
                }

                function bindMainForms() {
                    document.querySelectorAll('form[data-admin-main-form]').forEach(fixNestedFormsIn);
                }

                document.addEventListener('submit', function (e) {
                    var form = e.target;
                    if (!(form instanceof HTMLFormElement)) {
                        return;
                    }
                    if (!form.hasAttribute('data-admin-main-form')) {
                        return;
                    }
                    form.querySelectorAll('input, select, textarea').forEach(function (el) {
                        if (el.disabled && el.name) {
                            el.disabled = false;
                        }
                    });
                }, true);

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', bindMainForms);
                } else {
                    bindMainForms();
                }
            })();
        </script>
    @endpush
@endonce
