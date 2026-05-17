<div
    id="admin-confirm-dialog"
    class="fixed inset-0 z-[200] hidden items-end justify-center p-0 sm:items-center sm:p-4"
    role="dialog"
    aria-modal="true"
    aria-labelledby="admin-confirm-dialog-title"
    aria-hidden="true"
>
    <button
        type="button"
        class="absolute inset-0 bg-black/60 backdrop-blur-sm"
        data-admin-confirm-cancel
        aria-label="Tutup"
    ></button>

    <div
        class="public-card-hover admin-confirm-panel relative z-10 flex w-full max-w-md max-h-[min(100dvh,32rem)] flex-col overflow-hidden rounded-t-2xl border border-white/10 bg-church-card ring-1 ring-church-gold/20 sm:max-h-[calc(100dvh-2rem)] sm:rounded-2xl"
    >
        <div class="overflow-y-auto overscroll-contain px-5 pb-2 pt-5 sm:px-6 sm:pt-6">
            <div class="flex items-start gap-3 sm:gap-4">
                <span
                    class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-red-500/15 text-red-400 ring-1 ring-red-500/25 sm:size-11"
                    aria-hidden="true"
                >
                    <i class="fa-solid fa-triangle-exclamation text-base sm:text-lg"></i>
                </span>
                <div class="min-w-0 flex-1 pt-0.5">
                    <h2
                        id="admin-confirm-dialog-title"
                        class="font-serif text-base font-semibold leading-snug text-church-fg sm:text-lg"
                    >
                        Konfirmasi
                    </h2>
                    <p
                        id="admin-confirm-dialog-message"
                        class="mt-1.5 break-words text-sm leading-relaxed text-slate-400"
                    ></p>
                </div>
            </div>
        </div>

        <div
            class="public-card-hover flex shrink-0 flex-col gap-2 border-t border-white/10 bg-church-card/95 px-5 py-4 pb-[max(1rem,env(safe-area-inset-bottom))] sm:flex-row sm:justify-end sm:gap-3 sm:px-6 sm:py-5 sm:pb-5"
        >
            <button
                type="button"
                class="admin-btn admin-btn--secondary min-h-[2.75rem] w-full sm:min-h-0 sm:w-auto"
                data-admin-confirm-cancel
            >
                <i class="fa-solid fa-xmark text-xs opacity-70" aria-hidden="true"></i>
                Batal
            </button>
            <button
                type="button"
                id="admin-confirm-dialog-ok"
                class="admin-btn admin-btn--danger-solid min-h-[2.75rem] w-full sm:min-h-0 sm:w-auto"
            >
                <i class="fa-solid fa-trash text-xs" aria-hidden="true"></i>
                <span id="admin-confirm-dialog-ok-label">Hapus</span>
            </button>
        </div>
    </div>
</div>

@once
    @push('scripts')
        <script>
            (function () {
                var dialog = document.getElementById('admin-confirm-dialog');
                if (!dialog) return;

                var titleEl = document.getElementById('admin-confirm-dialog-title');
                var messageEl = document.getElementById('admin-confirm-dialog-message');
                var okBtn = document.getElementById('admin-confirm-dialog-ok');
                var okLabel = document.getElementById('admin-confirm-dialog-ok-label');
                var pending = null;
                var scrollLock = '';

                function closeDialog() {
                    dialog.classList.add('hidden');
                    dialog.classList.remove('flex');
                    dialog.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = scrollLock;
                    pending = null;
                }

                function openDialog(opts) {
                    scrollLock = document.body.style.overflow;
                    document.body.style.overflow = 'hidden';
                    titleEl.textContent = opts.title || 'Konfirmasi';
                    messageEl.textContent = opts.message || 'Lanjutkan?';
                    okLabel.textContent = opts.confirmLabel || 'Hapus';
                    dialog.classList.remove('hidden');
                    dialog.classList.add('flex');
                    dialog.setAttribute('aria-hidden', 'false');
                    var cancelBtn = dialog.querySelector('.admin-confirm-panel [data-admin-confirm-cancel]');
                    (cancelBtn || okBtn).focus();
                }

                window.adminConfirm = function (opts) {
                    return new Promise(function (resolve) {
                        pending = resolve;
                        openDialog(opts || {});
                    });
                };

                window.adminConfirmAction = function (opts, action) {
                    if (typeof action !== 'function') {
                        return;
                    }
                    var o = opts || {};
                    if (typeof window.adminConfirm !== 'function') {
                        if (window.confirm(o.message || 'Lanjutkan?')) {
                            action();
                        }
                        return;
                    }
                    window.adminConfirm({
                        title: o.title || 'Hapus?',
                        message: o.message || 'Tindakan ini tidak dapat dibatalkan.',
                        confirmLabel: o.confirmLabel || 'Hapus',
                    }).then(function (ok) {
                        if (ok) {
                            action();
                        }
                    });
                };

                okBtn.addEventListener('click', function () {
                    if (pending) pending(true);
                    closeDialog();
                });

                dialog.querySelectorAll('[data-admin-confirm-cancel]').forEach(function (el) {
                    el.addEventListener('click', function () {
                        if (pending) pending(false);
                        closeDialog();
                    });
                });

                document.addEventListener('keydown', function (e) {
                    if (dialog.classList.contains('hidden')) return;
                    if (e.key === 'Escape') {
                        if (pending) pending(false);
                        closeDialog();
                    }
                });

                document.addEventListener('submit', function (e) {
                    var form = e.target;
                    if (!(form instanceof HTMLFormElement)) return;
                    if (form.dataset.adminConfirmBound === '1') return;
                    var trigger = form.querySelector('[data-admin-confirm-submit]');
                    if (!trigger) return;
                    e.preventDefault();
                    window.adminConfirm({
                        title: trigger.dataset.confirmTitle || 'Hapus data?',
                        message: trigger.dataset.confirmMessage || 'Tindakan ini tidak dapat dibatalkan.',
                        confirmLabel: trigger.dataset.confirmLabel || 'Hapus',
                    }).then(function (ok) {
                        if (ok) {
                            form.dataset.adminConfirmBound = '1';
                            form.requestSubmit ? form.requestSubmit() : form.submit();
                        }
                    });
                }, true);
            })();
        </script>
    @endpush
@endonce
