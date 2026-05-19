@extends('layouts.admin')

@section('title', 'Galeri')

@section('content')
    <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <h1 class="text-xl font-bold text-church-fg sm:text-2xl">Galeri</h1>
    </div>

    @if (! empty($tableMissing))
        <div class="mb-4 rounded-lg border border-amber-500/40 bg-amber-950/40 px-4 py-3 text-sm text-amber-100">
            <p class="font-semibold">Database galeri belum siap</p>
            <p class="mt-1 text-amber-200/90">Jalankan di server: <code class="rounded bg-black/30 px-1.5 py-0.5 text-xs">php artisan migrate --force</code></p>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-500/40 bg-red-950/40 px-4 py-3 text-sm text-red-200">
            {{ $errors->first() }}
        </div>
    @endif

    <section class="public-card-hover mb-8 rounded-2xl border border-white/10 bg-church-card/80 p-4 sm:p-6">
        <h2 class="mb-3 font-serif text-lg font-semibold text-church-fg">Unggah foto</h2>
        <form
            id="gallery-upload-form"
            method="post"
            action="{{ route('dashboard.galeri.store') }}"
            enctype="multipart/form-data"
            class="space-y-4"
        >
            @csrf
            <label class="block">
                @include('admin.partials.form-label', ['text' => 'Pilih foto (bisa lebih dari satu)'])
                <input
                    type="file"
                    name="files[]"
                    multiple
                    accept="image/*"
                    required
                    class="mt-1 block w-full text-sm text-church-fg file:mr-3 file:rounded-lg file:border-0 file:bg-church-gold file:px-3 file:py-2 file:text-sm file:font-semibold file:text-church-navy"
                >
            </label>
            <p class="text-xs text-slate-400">Format JPG, PNG, GIF, WebP · ukuran bebas (batas server berlaku)</p>
            @include('admin.partials.btn', [
                'type' => 'submit',
                'variant' => 'primary',
                'icon' => 'fa-solid fa-upload',
                'label' => 'Unggah ke galeri',
                'extraClass' => 'shrink-0',
            ])
        </form>
    </section>

    @if ($items->isEmpty())
        <div class="rounded-2xl border border-dashed border-white/20 bg-church-card/60 px-4 py-14 text-center text-slate-400">
            <i class="fa-solid fa-images mb-3 text-4xl text-church-gold/35" aria-hidden="true"></i>
            <p>Belum ada foto di galeri. Unggah foto di atas.</p>
        </div>
    @else
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($items as $idx => $item)
                <article class="gallery-thumb-card public-card-hover group relative aspect-square overflow-hidden rounded-xl border border-white/10 bg-church-surface">
                    <button
                        type="button"
                        class="absolute inset-0 z-0 block h-full w-full cursor-zoom-in border-0 bg-transparent p-0 text-left outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-church-gold/50"
                        data-gallery-lightbox-open="{{ $idx }}"
                        aria-label="Lihat foto: {{ $item->caption ?: $item->original_name }}"
                    >
                        <img
                            src="{{ $item->url() }}"
                            alt=""
                            class="pointer-events-none h-full w-full object-cover transition duration-300 ease-out group-hover:scale-[1.03]"
                            loading="lazy"
                            decoding="async"
                        >
                    </button>
                    <div
                        class="pointer-events-none absolute inset-0 z-10 bg-black/0 transition-colors duration-200 group-hover:bg-black/30 group-focus-within:bg-black/30"
                        aria-hidden="true"
                    ></div>
                    <div
                        class="gallery-thumb-caption pointer-events-none absolute inset-x-0 bottom-0 z-[11] bg-gradient-to-t from-black/90 via-black/55 to-transparent p-2 pt-7 opacity-0 transition-opacity duration-200 group-hover:opacity-100 group-focus-within:opacity-100 [@media(hover:none)]:opacity-100"
                    >
                        <p class="line-clamp-2 text-xs font-medium text-white">
                            {{ $item->caption ?: $item->original_name }}
                        </p>
                        @unless ($item->is_public)
                            <span class="mt-1 inline-block rounded bg-slate-700/90 px-1.5 py-0.5 text-[0.65rem] text-slate-300">Non-publik</span>
                        @endunless
                    </div>
                    <div
                        class="gallery-card-actions pointer-events-none absolute right-1.5 top-1.5 z-20 flex gap-1 rounded-lg bg-black/65 p-0.5 opacity-0 shadow-lg backdrop-blur-sm transition-opacity duration-200 group-hover:pointer-events-auto group-hover:opacity-100 group-focus-within:pointer-events-auto group-focus-within:opacity-100 [@media(hover:none)]:pointer-events-auto [@media(hover:none)]:opacity-100"
                    >
                        <div class="admin-table-actions admin-table-actions--n3">
                            <button
                                type="button"
                                class="admin-btn-icon admin-btn-icon--gold"
                                title="Edit nama foto"
                                aria-label="Edit nama foto"
                                data-gallery-edit
                                data-update-url="{{ route('dashboard.galeri.update', $item) }}"
                                data-caption="{{ e($item->caption ?: ($item->original_name ?? '')) }}"
                            >
                                <i class="fa-solid fa-pen" aria-hidden="true"></i>
                            </button>
                            <form method="post" action="{{ route('dashboard.galeri.destroy', $item) }}" class="contents">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="admin-btn-icon admin-btn-icon--delete"
                                    title="Hapus foto"
                                    aria-label="Hapus foto"
                                    data-admin-confirm-submit
                                    data-confirm-title="Hapus foto?"
                                    data-confirm-message="Foto akan dihapus permanen dari galeri."
                                    data-confirm-label="Ya, hapus"
                                >
                                    <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif

    @include('admin.gallery.partials.upload-loading-modal')
    @include('admin.gallery.partials.edit-modal')

    @if ($items->isNotEmpty())
        @include('partials.gallery-lightbox', [
            'photos' => $items->map(fn ($item) => [
                'src' => $item->url(),
                'alt' => $item->caption ?: ($item->original_name ?: 'Foto galeri'),
            ]),
            'labels' => [
                'title' => 'Detail foto galeri',
            ],
        ])
    @endif
@endsection

@push('scripts')
    <script>
        (function () {
            var form = document.getElementById('gallery-upload-form');
            var overlay = document.getElementById('gallery-upload-loading');
            var progressBar = document.getElementById('gallery-upload-progress');
            var percentEl = document.getElementById('gallery-upload-percent');
            var fileCountEl = document.getElementById('gallery-upload-file-count');
            var statusEl = document.getElementById('gallery-upload-status');
            var redirectUrl = @json(route('dashboard.galeri.index'));

            if (!form || !overlay) return;

            function setProgress(value) {
                var pct = Math.max(0, Math.min(100, Math.round(value)));
                if (progressBar) {
                    progressBar.style.width = pct + '%';
                    progressBar.setAttribute('aria-valuenow', String(pct));
                    progressBar.classList.toggle('gallery-upload-modal__progress-fill--active', pct > 0 && pct < 100);
                }
                if (percentEl) {
                    percentEl.textContent = pct + '%';
                }
            }

            function showOverlay(fileCount) {
                setProgress(0);
                if (fileCountEl) {
                    fileCountEl.textContent = fileCount === 1
                        ? '1 foto sedang diunggah'
                        : fileCount + ' foto sedang diunggah';
                }
                if (statusEl) {
                    statusEl.textContent = 'Mengirim ke server';
                }
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
                overlay.setAttribute('aria-hidden', 'false');
                overlay.setAttribute('aria-busy', 'true');
                document.body.style.overflow = 'hidden';
            }

            function hideOverlay() {
                overlay.classList.add('hidden');
                overlay.classList.remove('flex');
                overlay.setAttribute('aria-hidden', 'true');
                overlay.setAttribute('aria-busy', 'false');
                document.body.style.overflow = '';
            }

            form.addEventListener('submit', function (event) {
                var input = form.querySelector('input[type="file"]');
                if (!input || !input.files || input.files.length === 0) {
                    return;
                }

                event.preventDefault();
                showOverlay(input.files.length);

                var formData = new FormData(form);
                var xhr = new XMLHttpRequest();
                xhr.open('POST', form.action, true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader('Accept', 'application/json');

                xhr.upload.addEventListener('progress', function (e) {
                    if (e.lengthComputable) {
                        setProgress((e.loaded / e.total) * 100);
                        if (statusEl) {
                            statusEl.textContent = 'Mengunggah file';
                        }
                    } else if (progressBar && progressBar.classList) {
                        progressBar.classList.add('gallery-upload-modal__progress-fill--active');
                        if (percentEl) {
                            percentEl.textContent = '…';
                        }
                    }
                });

                xhr.addEventListener('load', function () {
                    var message = 'Unggahan gagal. Periksa format file dan coba lagi.';
                    var data = null;

                    try {
                        data = JSON.parse(xhr.responseText);
                    } catch (err) {
                        data = null;
                    }

                    if (xhr.status >= 200 && xhr.status < 300 && data && data.ok) {
                        setProgress(100);
                        if (statusEl) {
                            statusEl.textContent = 'Selesai, memuat ulang';
                        }
                        window.location.href = data.redirect || redirectUrl;
                        return;
                    }

                    hideOverlay();

                    if (data && data.errors) {
                        var lines = [];
                        Object.keys(data.errors).forEach(function (key) {
                            (data.errors[key] || []).forEach(function (line) {
                                lines.push(line);
                            });
                        });
                        if (lines.length) {
                            message = lines.join('\n');
                        }
                    } else if (data && data.message) {
                        message = data.message;
                    }

                    window.alert(message);
                });

                xhr.addEventListener('error', function () {
                    hideOverlay();
                    window.alert('Koneksi terputus saat mengunggah. Periksa jaringan lalu coba lagi.');
                });

                xhr.send(formData);
            });

            var editModal = document.getElementById('gallery-edit-modal');
            var editForm = document.getElementById('gallery-edit-form');
            var editCaption = document.getElementById('gallery-edit-caption');
            var editScrollLock = '';

            function openEditModal(updateUrl, caption) {
                if (!editModal || !editForm || !editCaption) return;
                editForm.action = updateUrl;
                editCaption.value = (caption || '').trim();
                editScrollLock = document.body.style.overflow;
                document.body.style.overflow = 'hidden';
                editModal.classList.remove('hidden');
                editModal.classList.add('flex');
                editModal.setAttribute('aria-hidden', 'false');
                window.setTimeout(function () {
                    editCaption.focus();
                    editCaption.select();
                }, 50);
            }

            function closeEditModal() {
                if (!editModal) return;
                editModal.classList.add('hidden');
                editModal.classList.remove('flex');
                editModal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = editScrollLock;
            }

            document.querySelectorAll('[data-gallery-edit]').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    openEditModal(
                        btn.getAttribute('data-update-url'),
                        btn.getAttribute('data-caption') || ''
                    );
                });
            });

            document.querySelectorAll('[data-gallery-edit-cancel]').forEach(function (btn) {
                btn.addEventListener('click', closeEditModal);
            });

            document.addEventListener('keydown', function (e) {
                if (!editModal || editModal.classList.contains('hidden')) return;
                if (e.key === 'Escape') {
                    e.preventDefault();
                    closeEditModal();
                }
            });
        })();
    </script>
@endpush
