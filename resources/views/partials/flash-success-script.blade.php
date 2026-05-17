@once
    <script>
        (function () {
            if (window.__flashSuccessInit) {
                return;
            }
            window.__flashSuccessInit = true;

            var dismissMs = 1000;

            function dismiss(el) {
                el.setAttribute('data-flash-dismissing', '');
                window.setTimeout(function () {
                    el.remove();
                }, dismissMs);
            }

            function schedule(el) {
                if (el.dataset.flashScheduled === '1') {
                    return;
                }
                el.dataset.flashScheduled = '1';
                window.setTimeout(function () {
                    dismiss(el);
                }, 4000);
            }

            function init() {
                document.querySelectorAll('[data-flash-success]').forEach(schedule);
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
        })();
    </script>
@endonce
