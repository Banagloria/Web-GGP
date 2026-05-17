@php
    $photos = $photos ?? collect();
    $labels = array_merge([
        'title' => 'Galeri foto — tampilan besar',
        'close' => 'Tutup',
        'prev' => 'Foto sebelumnya',
        'next' => 'Foto berikutnya',
    ], $labels ?? []);
@endphp

@if ($photos->isNotEmpty())
    <div
        id="gallery-lightbox"
        class="gallery-lightbox fixed inset-0 z-[200] hidden h-dvh max-h-dvh w-full items-center justify-center overflow-hidden bg-black/95 p-3 sm:p-4"
        role="dialog"
        aria-modal="true"
        aria-labelledby="gallery-lightbox-title"
        aria-hidden="true"
    >
        <p id="gallery-lightbox-title" class="sr-only">{{ $labels['title'] }}</p>

        <button
            type="button"
            id="gallery-lightbox-backdrop"
            class="absolute inset-0 z-0 cursor-pointer border-0 bg-transparent p-0"
            tabindex="-1"
            aria-label="{{ $labels['close'] }}"
        ></button>

        <div class="gallery-lightbox-panel pointer-events-none relative z-10 flex w-full max-w-4xl flex-col items-center">
            <img
                id="gallery-lightbox-img"
                src=""
                alt=""
                class="gallery-lightbox-img pointer-events-auto max-w-full object-contain shadow-2xl"
                width="1200"
                height="900"
            >

            <div class="gallery-lightbox-footer pointer-events-auto mt-1.5 w-full max-w-md shrink-0 sm:mt-2">
                <p id="gallery-lightbox-caption" class="line-clamp-2 px-1 text-center text-xs leading-snug text-white/90 sm:text-sm"></p>
                <div
                    id="gallery-lightbox-nav"
                    class="mt-1 flex items-center justify-between gap-2 px-1 sm:mt-1.5"
                >
                    <button
                        type="button"
                        class="public-btn-hover gallery-lb-nav gallery-lb-prev flex size-10 shrink-0 items-center justify-center rounded-full border border-white/25 bg-black/70 text-base text-white backdrop-blur-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-church-gold"
                        aria-label="{{ $labels['prev'] }}"
                    >
                        <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                    </button>
                    <p id="gallery-lightbox-count" class="min-w-[3.5rem] shrink-0 text-center text-xs tabular-nums text-white/70 sm:text-sm"></p>
                    <button
                        type="button"
                        class="public-btn-hover gallery-lb-nav gallery-lb-next flex size-10 shrink-0 items-center justify-center rounded-full border border-white/25 bg-black/70 text-base text-white backdrop-blur-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-church-gold"
                        aria-label="{{ $labels['next'] }}"
                    >
                        <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @once
        @push('scripts')
            <script>
                window.initGalleryLightbox = window.initGalleryLightbox || function (items) {
                    if (!items || !items.length) return;

                    var overlay = document.getElementById('gallery-lightbox');
                    var imgEl = document.getElementById('gallery-lightbox-img');
                    var capEl = document.getElementById('gallery-lightbox-caption');
                    var cntEl = document.getElementById('gallery-lightbox-count');
                    var footerNav = document.getElementById('gallery-lightbox-nav');
                    var btnBackdrop = document.getElementById('gallery-lightbox-backdrop');
                    if (!overlay || !imgEl || overlay.dataset.galleryLbReady === '1') {
                        if (overlay && overlay.dataset.galleryLbReady === '1') {
                            overlay.__galleryLbItems = items;
                        }
                        return;
                    }
                    overlay.dataset.galleryLbReady = '1';
                    overlay.__galleryLbItems = items;

                    var btnPrev = footerNav ? footerNav.querySelectorAll('.gallery-lb-prev') : [];
                    var btnNext = footerNav ? footerNav.querySelectorAll('.gallery-lb-next') : [];

                    var idx = 0;
                    var lastFocus = null;
                    var touchStartX = 0;
                    var touchStartY = 0;

                    function itemsList() {
                        return overlay.__galleryLbItems || items;
                    }

                    function setNavVisible(visible) {
                        if (!footerNav) return;
                        footerNav.classList.toggle('hidden', !visible);
                        footerNav.setAttribute('aria-hidden', visible ? 'false' : 'true');
                        btnPrev.forEach(function (btn) {
                            btn.disabled = !visible;
                        });
                        btnNext.forEach(function (btn) {
                            btn.disabled = !visible;
                        });
                    }

                    function showAt(i) {
                        var list = itemsList();
                        var n = list.length;
                        if (!n) return;
                        idx = ((i % n) + n) % n;
                        var cur = list[idx];
                        var countText = (idx + 1) + ' / ' + n;
                        imgEl.src = cur.src;
                        imgEl.alt = cur.alt || '';
                        if (capEl) capEl.textContent = cur.alt || '';
                        if (cntEl) cntEl.textContent = countText;
                        setNavVisible(n > 1);
                    }

                    function open(i) {
                        lastFocus = document.activeElement;
                        showAt(i);
                        overlay.classList.remove('hidden');
                        overlay.classList.add('flex');
                        overlay.setAttribute('aria-hidden', 'false');
                        document.body.style.overflow = 'hidden';
                    }

                    function close() {
                        overlay.classList.add('hidden');
                        overlay.classList.remove('flex');
                        overlay.setAttribute('aria-hidden', 'true');
                        document.body.style.overflow = '';
                        imgEl.removeAttribute('src');
                        if (lastFocus && typeof lastFocus.focus === 'function') lastFocus.focus();
                    }

                    document.querySelectorAll('[data-gallery-lightbox-open]').forEach(function (btn) {
                        btn.addEventListener('click', function (e) {
                            e.preventDefault();
                            var i = parseInt(btn.getAttribute('data-gallery-lightbox-open'), 10);
                            if (!isNaN(i)) open(i);
                        });
                    });

                    if (btnBackdrop) btnBackdrop.addEventListener('click', close);

                    function bindNav(btns, delta) {
                        btns.forEach(function (btn) {
                            btn.addEventListener('click', function (e) {
                                e.stopPropagation();
                                showAt(idx + delta);
                            });
                        });
                    }
                    bindNav(btnPrev, -1);
                    bindNav(btnNext, 1);

                    function onTouchStart(e) {
                        if (overlay.classList.contains('hidden') || itemsList().length <= 1) return;
                        touchStartX = e.changedTouches[0].screenX;
                        touchStartY = e.changedTouches[0].screenY;
                    }

                    function onTouchEnd(e) {
                        if (overlay.classList.contains('hidden') || itemsList().length <= 1) return;
                        var dx = e.changedTouches[0].screenX - touchStartX;
                        var dy = e.changedTouches[0].screenY - touchStartY;
                        if (Math.abs(dx) < 48 || Math.abs(dx) < Math.abs(dy)) return;
                        if (dx > 0) showAt(idx - 1);
                        else showAt(idx + 1);
                    }

                    overlay.addEventListener('touchstart', onTouchStart, { passive: true });
                    overlay.addEventListener('touchend', onTouchEnd, { passive: true });

                    document.addEventListener('keydown', function (e) {
                        if (overlay.classList.contains('hidden')) return;
                        if (e.key === 'Escape') { e.preventDefault(); close(); }
                        else if (itemsList().length > 1 && e.key === 'ArrowLeft') { e.preventDefault(); showAt(idx - 1); }
                        else if (itemsList().length > 1 && e.key === 'ArrowRight') { e.preventDefault(); showAt(idx + 1); }
                    });
                };
            </script>
        @endpush
    @endonce

    @push('scripts')
        <script>
            initGalleryLightbox(@json($photos->values(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE));
        </script>
    @endpush
@endif
