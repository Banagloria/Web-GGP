<div
    id="profile-photo-detail-modal"
    class="profile-photo-detail-modal fixed inset-0 z-[200] hidden items-center justify-center bg-black/90 p-4"
    role="dialog"
    aria-modal="true"
    aria-labelledby="profile-photo-detail-title"
    aria-hidden="true"
>
    <button
        type="button"
        id="profile-photo-detail-backdrop"
        class="absolute inset-0 cursor-pointer border-0 bg-transparent"
        aria-label="Tutup"
    ></button>

    <div class="profile-photo-detail-modal__panel relative z-10 w-full max-w-lg rounded-2xl border border-white/10 bg-church-card p-4 shadow-2xl sm:p-6">
        <div class="mb-4 flex items-start justify-between gap-3">
            <div class="min-w-0 text-left">
                <h2 id="profile-photo-detail-title" class="font-serif text-lg font-bold text-church-fg sm:text-xl">
                    Detail foto profil
                </h2>
                <p id="profile-photo-detail-name" class="mt-1 break-words text-sm font-semibold text-church-fg"></p>
                <p id="profile-photo-detail-email" class="mt-0.5 break-all text-xs text-slate-400 sm:text-sm"></p>
                <p id="profile-photo-detail-phone" class="mt-1 hidden break-all text-xs text-slate-300 sm:text-sm"></p>
            </div>
            <button
                type="button"
                id="profile-photo-detail-close"
                class="public-btn-hover flex size-9 shrink-0 items-center justify-center rounded-lg border border-white/15 bg-white/5 text-slate-300 transition hover:text-church-fg focus:outline-none focus-visible:ring-2 focus-visible:ring-church-gold/50"
                aria-label="Tutup"
            >
                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
            </button>
        </div>

        <div class="overflow-hidden rounded-lg border border-white/10 bg-church-surface/50">
            <img
                id="profile-photo-detail-img"
                src=""
                alt=""
                class="mx-auto max-h-[min(70vh,32rem)] w-full object-contain"
            >
        </div>

        <p class="mt-3 text-center text-xs text-slate-500">Klik di luar gambar atau tombol tutup untuk menutup.</p>
    </div>
</div>
