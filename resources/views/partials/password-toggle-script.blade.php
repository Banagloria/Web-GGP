<script>
    (function () {
        function initPasswordToggles(root) {
            root = root || document;
            root.querySelectorAll('[data-password-toggle]').forEach(function (wrap) {
                if (wrap.dataset.passwordToggleInit) {
                    return;
                }
                wrap.dataset.passwordToggleInit = '1';
                var input = wrap.querySelector('[data-password-input]');
                var btn = wrap.querySelector('[data-password-toggle-btn]');
                if (!input || !btn) {
                    return;
                }
                var eye = btn.querySelector('.icon-eye');
                var eyeOff = btn.querySelector('.icon-eye-off');
                btn.addEventListener('click', function () {
                    var show = input.type === 'password';
                    input.type = show ? 'text' : 'password';
                    btn.setAttribute('aria-pressed', show ? 'true' : 'false');
                    btn.setAttribute('aria-label', show ? 'Sembunyikan sandi' : 'Tampilkan sandi');
                    if (eye && eyeOff) {
                        eye.classList.toggle('hidden', show);
                        eyeOff.classList.toggle('hidden', !show);
                    }
                });
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function () {
                initPasswordToggles(document);
            });
        } else {
            initPasswordToggles(document);
        }
    })();
</script>
