<div
    id="gallery-edit-modal"
    class="fixed inset-0 z-[200] hidden items-end justify-center p-0 sm:items-center sm:p-4"
    role="dialog"
    aria-modal="true"
    aria-labelledby="gallery-edit-modal-title"
    aria-hidden="true"
>
    <button
        type="button"
        class="absolute inset-0 bg-black/60 backdrop-blur-sm"
        data-gallery-edit-cancel
        aria-label="Tutup"
    ></button>

    <div class="public-card-hover admin-confirm-panel relative z-10 w-full max-w-md overflow-hidden rounded-t-2xl border border-white/10 bg-church-card sm:rounded-2xl">
        <form method="post" id="gallery-edit-form" action="#" class="flex flex-col">
            @csrf
            @method('PATCH')
            <div class="px-5 pb-2 pt-5 sm:px-6 sm:pt-6">
                <div class="flex items-start gap-3">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-church-gold/15 text-church-gold ring-1 ring-church-gold/25 sm:size-11">
                        <i class="fa-solid fa-pen text-base" aria-hidden="true"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <h2 id="gallery-edit-modal-title" class="font-serif text-base font-semibold text-church-fg sm:text-lg">
                            Edit nama foto
                        </h2>
                        <p class="mt-1 text-sm text-slate-400">Nama ini tampil di galeri admin dan halaman publik.</p>
                    </div>
                </div>
                <label class="mt-4 block text-left">
                    @include('admin.partials.form-label', ['text' => 'Nama foto'])
                    <input
                        type="text"
                        name="caption"
                        id="gallery-edit-caption"
                        maxlength="255"
                        autocomplete="off"
                        class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-sm text-church-fg shadow-inner"
                        placeholder="Contoh: Ibadah Minggu"
                    >
                </label>
            </div>
            <div class="flex shrink-0 flex-col gap-2 border-t border-white/10 px-5 py-4 sm:flex-row sm:justify-end sm:px-6 sm:py-5">
                <button
                    type="button"
                    class="admin-btn admin-btn--secondary min-h-[2.75rem] w-full sm:w-auto"
                    data-gallery-edit-cancel
                >
                    Batal
                </button>
                @include('admin.partials.btn', [
                    'type' => 'submit',
                    'variant' => 'primary',
                    'icon' => 'fa-solid fa-check',
                    'label' => 'Simpan',
                    'extraClass' => 'min-h-[2.75rem] w-full sm:w-auto',
                ])
            </div>
        </form>
    </div>
</div>
