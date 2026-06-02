<div
    id="wa-test-message-modal"
    class="fixed inset-0 z-[200] hidden items-end justify-center p-0 sm:items-center sm:p-4"
    role="dialog"
    aria-modal="true"
    aria-labelledby="wa-test-message-modal-title"
    aria-hidden="true"
>
    <button
        type="button"
        class="absolute inset-0 bg-black/60 backdrop-blur-sm"
        data-wa-test-message-cancel
        aria-label="Tutup"
    ></button>

    <div class="public-card-hover admin-confirm-panel relative z-10 w-full max-w-md overflow-hidden rounded-t-2xl border border-white/10 bg-church-card sm:rounded-2xl">
        <form method="post" id="wa-test-message-form" action="#" class="flex flex-col">
            @csrf
            <input type="hidden" name="_wa_test_template_id" id="wa-test-template-id" value="{{ old('_wa_test_template_id') }}">
            <div class="px-5 pb-2 pt-5 sm:px-6 sm:pt-6">
                <div class="flex items-start gap-3">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-emerald-500/15 text-emerald-400 ring-1 ring-emerald-500/25 sm:size-11">
                        <i class="fa-brands fa-whatsapp text-lg" aria-hidden="true"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <h2 id="wa-test-message-modal-title" class="font-serif text-base font-semibold text-church-fg sm:text-lg">
                            Kirim pesan uji
                        </h2>
                        <p class="mt-1 text-sm text-slate-400">Pilih kontak dari Manajemen akun yang memiliki nomor HP valid.</p>
                    </div>
                </div>

                @if ($testMessageContacts->isEmpty())
                    <p class="mt-4 rounded-lg border border-amber-500/30 bg-amber-500/10 px-3 py-2 text-sm text-amber-200">
                        Belum ada akun pengurus dengan nomor HP. Tambahkan nomor di menu Manajemen akun.
                    </p>
                @else
                    <label class="mt-4 block text-left">
                        <x-admin-field-label for="wa-test-message-user">Pilih kontak</x-admin-field-label>
                        <select
                            id="wa-test-message-user"
                            name="user_id"
                            required
                            class="admin-list-toolbar__select mt-1 w-full"
                        >
                            <option value="">— Pilih kontak —</option>
                            @foreach ($testMessageContacts as $contact)
                                <option value="{{ $contact['user_id'] }}" @selected((string) old('user_id') === (string) $contact['user_id'])>
                                    {{ $contact['recipient_name'] }} — {{ $contact['recipient_phone'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </label>
                @endif
            </div>
            <div class="flex shrink-0 flex-col gap-2 border-t border-white/10 px-5 py-4 sm:flex-row sm:justify-end sm:px-6 sm:py-5">
                <button
                    type="button"
                    class="admin-btn admin-btn--secondary min-h-[2.75rem] w-full sm:w-auto"
                    data-wa-test-message-cancel
                >
                    Batal
                </button>
                @if ($testMessageContacts->isNotEmpty())
                    @include('admin.partials.btn', [
                        'type' => 'submit',
                        'variant' => 'primary',
                        'icon' => 'fa-brands fa-whatsapp',
                        'label' => 'Kirim pesan',
                        'extraClass' => 'min-h-[2.75rem] w-full sm:w-auto',
                    ])
                @endif
            </div>
        </form>
    </div>
</div>
