<div
    id="gallery-upload-loading"
    class="fixed inset-0 z-[300] hidden min-h-dvh w-full flex-col items-center justify-center bg-church-bg/92 px-4 backdrop-blur-md"
    role="alertdialog"
    aria-modal="true"
    aria-labelledby="gallery-upload-loading-title"
    aria-describedby="gallery-upload-loading-desc"
    aria-busy="true"
    aria-hidden="true"
>
    <div class="gallery-upload-modal w-full max-w-md rounded-2xl border border-white/10 bg-church-card px-6 py-8 text-center shadow-2xl shadow-black/40">
        <div class="relative mx-auto mb-6 size-20" aria-hidden="true">
            <span class="gallery-upload-modal__ring absolute inset-0 rounded-full border-2 border-church-gold/30"></span>
            <span class="gallery-upload-modal__spinner absolute inset-2 rounded-full border-[3px] border-church-gold/20 border-t-church-gold"></span>
            <span class="absolute inset-0 flex items-center justify-center text-2xl text-church-gold">
                <i class="fa-solid fa-cloud-arrow-up"></i>
            </span>
        </div>

        <p id="gallery-upload-loading-title" class="font-semibold text-church-fg">Mengunggah foto…</p>
        <p id="gallery-upload-loading-desc" class="mt-1 text-sm text-slate-400">
            Mohon tunggu sampai proses selesai. Jangan tutup halaman ini.
        </p>
        <p id="gallery-upload-file-count" class="mt-2 text-xs font-medium text-church-gold/90"></p>

        <div class="mt-5" aria-hidden="true">
            <div class="mb-2 flex items-center justify-between gap-3 text-xs text-slate-400">
                <span>Progres unggah</span>
                <span id="gallery-upload-percent" class="tabular-nums font-semibold text-church-gold">0%</span>
            </div>
            <div class="h-2.5 overflow-hidden rounded-full bg-church-surface ring-1 ring-white/10">
                <div
                    id="gallery-upload-progress"
                    class="gallery-upload-modal__progress-fill h-full w-0 rounded-full"
                    role="progressbar"
                    aria-valuemin="0"
                    aria-valuemax="100"
                    aria-valuenow="0"
                ></div>
            </div>
        </div>

        <p class="mt-4 flex items-center justify-center gap-1 text-sm text-slate-500" aria-live="polite">
            <span id="gallery-upload-status">Memproses</span>
            <span class="inline-flex gap-0.5" aria-hidden="true">
                <span class="gallery-upload-modal__dot inline-block size-1 rounded-full bg-church-gold"></span>
                <span class="gallery-upload-modal__dot inline-block size-1 rounded-full bg-church-gold"></span>
                <span class="gallery-upload-modal__dot inline-block size-1 rounded-full bg-church-gold"></span>
            </span>
        </p>
    </div>
</div>
