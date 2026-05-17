<script>
    (function () {
        document.addEventListener(
            'click',
            function (e) {
                document.querySelectorAll('details[data-nav-disclosure]').forEach(function (d) {
                    if (!d.open) {
                        return;
                    }
                    if (d.contains(e.target)) {
                        return;
                    }
                    d.removeAttribute('open');
                });
            },
            true
        );
        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape') {
                return;
            }
            document.querySelectorAll('details[data-nav-disclosure][open]').forEach(function (d) {
                d.removeAttribute('open');
            });
        });
    })();
</script>
