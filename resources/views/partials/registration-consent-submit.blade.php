@props([
    'cms' => [],
    'pageKey' => 'pendaftaran',
    'submitIconKey' => 'form_jemaat_submit',
    'submitLabel' => 'Kirim pendaftaran',
    'consentText' => 'Dengan mengirim formulir ini, saya setuju data diproses oleh sekretariat jemaat.',
    'layout' => 'stack',
    'consentId' => 'reg-data-consent',
    'submitId' => 'reg-submit',
])

<div {{ $attributes->merge(['class' => 'reg-consent-footer mt-2 border-t border-white/10 pt-4']) }}>
    <div class="flex flex-row flex-wrap items-center justify-between gap-3">
        <label for="{{ $consentId }}" class="reg-consent-label group flex min-w-0 flex-1 cursor-pointer items-center gap-3">
            <span class="reg-consent-check-wrap relative flex size-5 shrink-0 items-center justify-center rounded-md border border-white/25 bg-church-surface/80 shadow-inner transition group-has-[:checked]:border-church-gold/60 group-has-[:checked]:bg-church-gold/20">
                <input
                    type="checkbox"
                    name="data_consent"
                    id="{{ $consentId }}"
                    value="1"
                    data-consent-checkbox
                    class="peer absolute inset-0 size-full cursor-pointer opacity-0"
                    @checked(old('data_consent'))
                >
                <i class="fa-solid fa-check text-[0.65rem] text-church-navy opacity-0 transition peer-checked:opacity-100" aria-hidden="true"></i>
            </span>
            <span class="min-w-0 text-sm leading-snug text-slate-300">
                <i class="fa-solid fa-shield-halved mr-1 text-church-gold/70" aria-hidden="true"></i>
                {{ $consentText }}
            </span>
        </label>

        <button
            id="{{ $submitId }}"
            type="submit"
            data-consent-submit
            disabled
            class="reg-submit-btn inline-flex w-full shrink-0 items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-church-gold via-church-gold to-church-gold-soft px-5 py-3 text-sm font-semibold text-church-navy focus-visible:ring-offset-2 focus-visible:ring-offset-church-card disabled:cursor-not-allowed disabled:opacity-40 sm:w-auto sm:px-6"
        >
            @include('partials.cms-page-icon', ['cms' => $cms, 'pageKey' => $pageKey, 'iconKey' => $submitIconKey, 'extraClasses' => ''])
            {{ $submitLabel }}
            <i class="fa-solid fa-paper-plane text-xs opacity-80" aria-hidden="true"></i>
        </button>
    </div>

    @error('data_consent')
        <p class="mt-2 flex items-start gap-1.5 text-xs text-red-400">
            <i class="fa-solid fa-circle-exclamation mt-0.5 shrink-0" aria-hidden="true"></i>
            <span>{{ $message }}</span>
        </p>
    @enderror
</div>

@once
    @push('scripts')
        <script>
            (function () {
                document.querySelectorAll('[data-registration-consent]').forEach(function (form) {
                    const consent = form.querySelector('[data-consent-checkbox]');
                    const submit = form.querySelector('[data-consent-submit]');
                    if (!consent || !submit) {
                        return;
                    }

                    function syncSubmitState() {
                        submit.disabled = !consent.checked;
                        submit.classList.toggle('reg-submit-btn--ready', consent.checked);
                    }

                    consent.addEventListener('change', syncSubmitState);
                    syncSubmitState();
                });
            })();
        </script>
    @endpush
@endonce
