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
        data-confirm-panel
    >
        <div class="overflow-y-auto overscroll-contain px-5 pb-2 pt-5 sm:px-6 sm:pt-6">
            <div class="flex items-start gap-3 sm:gap-4">
                <span
                    id="admin-confirm-dialog-icon-wrap"
                    class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-red-500/15 text-red-400 ring-1 ring-red-500/25 sm:size-11"
                    aria-hidden="true"
                >
                    <i id="admin-confirm-dialog-icon" class="fa-solid fa-triangle-exclamation text-base sm:text-lg"></i>
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
                    <div id="admin-confirm-dialog-whatsapp-wrap" class="mt-4 hidden">
                        <label for="admin-confirm-dialog-whatsapp-message" class="block text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Pesan WhatsApp
                        </label>
                        <p id="admin-confirm-dialog-whatsapp-phone" class="mt-1 text-xs text-slate-500"></p>
                        <textarea
                            id="admin-confirm-dialog-whatsapp-message"
                            rows="3"
                            class="admin-list-toolbar__input mt-2 w-full resize-y"
                            placeholder="Tulis pesan untuk dikirim ke nomor HP pendaftar…"
                        ></textarea>
                        <p class="mt-1.5 text-xs text-slate-500">Kosongkan jika tidak ingin mengirim WhatsApp.</p>
                    </div>
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
                <i id="admin-confirm-dialog-ok-icon" class="fa-solid fa-trash text-xs" aria-hidden="true"></i>
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
                var iconWrap = document.getElementById('admin-confirm-dialog-icon-wrap');
                var iconEl = document.getElementById('admin-confirm-dialog-icon');
                var okBtn = document.getElementById('admin-confirm-dialog-ok');
                var okIcon = document.getElementById('admin-confirm-dialog-ok-icon');
                var okLabel = document.getElementById('admin-confirm-dialog-ok-label');
                var panel = dialog.querySelector('[data-confirm-panel]');
                var waWrap = document.getElementById('admin-confirm-dialog-whatsapp-wrap');
                var waPhoneEl = document.getElementById('admin-confirm-dialog-whatsapp-phone');
                var waMessageEl = document.getElementById('admin-confirm-dialog-whatsapp-message');
                var pending = null;
                var scrollLock = '';

                function optsFromTrigger(trigger) {
                    return {
                        title: trigger.dataset.confirmTitle || 'Konfirmasi',
                        message: trigger.dataset.confirmMessage || 'Lanjutkan?',
                        confirmLabel: trigger.dataset.confirmLabel || 'Lanjutkan',
                        variant: trigger.dataset.confirmVariant || 'delete',
                        showWhatsapp: trigger.dataset.confirmWhatsapp === '1',
                        phone: trigger.dataset.confirmPhone || '',
                        waDefaultMessage: trigger.dataset.confirmWaDefault || '',
                    };
                }

                function attachWaMessageToForm(form) {
                    if (!waMessageEl) {
                        return;
                    }
                    var msg = waMessageEl.value.trim();
                    var existing = form.querySelector('input[name="wa_message"]');
                    if (existing) {
                        existing.remove();
                    }
                    if (msg === '') {
                        return;
                    }
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'wa_message';
                    input.value = msg;
                    form.appendChild(input);
                }

                var variants = {
                    accept: {
                        iconWrap: 'flex size-10 shrink-0 items-center justify-center rounded-xl bg-church-gold/15 text-church-gold ring-1 ring-church-gold/30 sm:size-11',
                        icon: 'fa-solid fa-check text-base sm:text-lg',
                        okBtn: 'admin-btn admin-btn--primary min-h-[2.75rem] w-full sm:min-h-0 sm:w-auto',
                        okIcon: 'fa-solid fa-check text-xs',
                        panelRing: 'ring-church-gold/25',
                    },
                    reject: {
                        iconWrap: 'flex size-10 shrink-0 items-center justify-center rounded-xl bg-red-500/15 text-red-400 ring-1 ring-red-500/25 sm:size-11',
                        icon: 'fa-solid fa-xmark text-base sm:text-lg',
                        okBtn: 'admin-btn admin-btn--danger-solid min-h-[2.75rem] w-full sm:min-h-0 sm:w-auto',
                        okIcon: 'fa-solid fa-xmark text-xs',
                        panelRing: 'ring-red-500/20',
                    },
                    delete: {
                        iconWrap: 'flex size-10 shrink-0 items-center justify-center rounded-xl bg-red-500/15 text-red-400 ring-1 ring-red-500/25 sm:size-11',
                        icon: 'fa-solid fa-trash text-base sm:text-lg',
                        okBtn: 'admin-btn admin-btn--danger-solid min-h-[2.75rem] w-full sm:min-h-0 sm:w-auto',
                        okIcon: 'fa-solid fa-trash text-xs',
                        panelRing: 'ring-red-500/20',
                    },
                };

                function applyVariant(variant) {
                    var v = variants[variant] || variants.delete;
                    iconWrap.className = v.iconWrap;
                    iconEl.className = v.icon;
                    okBtn.className = v.okBtn;
                    okIcon.className = v.okIcon;
                    panel.classList.remove('ring-church-gold/20', 'ring-church-gold/25', 'ring-red-500/20');
                    panel.classList.add(v.panelRing);
                }

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
                    applyVariant(opts.variant || 'delete');
                    titleEl.textContent = opts.title || 'Konfirmasi';
                    messageEl.textContent = opts.message || 'Lanjutkan?';
                    okLabel.textContent = opts.confirmLabel || 'Lanjutkan';
                    var showWa = !!opts.showWhatsapp && waWrap && waMessageEl;
                    if (waWrap) {
                        waWrap.classList.toggle('hidden', !showWa);
                    }
                    if (showWa) {
                        waMessageEl.value = opts.waDefaultMessage || '';
                        if (waPhoneEl) {
                            if (opts.phone) {
                                waPhoneEl.textContent = 'Nomor tujuan: ' + opts.phone;
                                waPhoneEl.classList.remove('text-amber-300');
                            } else {
                                waPhoneEl.textContent = 'Nomor HP tidak ditemukan — pesan tidak akan terkirim.';
                                waPhoneEl.classList.add('text-amber-300');
                            }
                        }
                    }
                    dialog.classList.remove('hidden');
                    dialog.classList.add('flex');
                    dialog.setAttribute('aria-hidden', 'false');
                    var cancelBtn = dialog.querySelector('.admin-confirm-panel [data-admin-confirm-cancel]');
                    (showWa ? waMessageEl : (cancelBtn || okBtn)).focus();
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
                        title: o.title || 'Konfirmasi',
                        message: o.message || 'Lanjutkan?',
                        confirmLabel: o.confirmLabel || 'Lanjutkan',
                        variant: o.variant || 'delete',
                        showWhatsapp: !!o.showWhatsapp,
                        phone: o.phone || '',
                        waDefaultMessage: o.waDefaultMessage || '',
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
                    window.adminConfirm(optsFromTrigger(trigger)).then(function (ok) {
                        if (ok) {
                            attachWaMessageToForm(form);
                            form.dataset.adminConfirmBound = '1';
                            form.requestSubmit ? form.requestSubmit() : form.submit();
                        }
                    });
                }, true);
            })();
        </script>
    @endpush
@endonce
