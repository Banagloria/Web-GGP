@extends('layouts.admin')

@section('title', 'Profil Akun')

@push('head')
    <link rel="stylesheet" href="/css/admin-profile-photo.css?v=5">
@endpush

@php
    $profileInputClass = 'mt-1 w-full min-w-0 max-w-full rounded-md border border-white/15 bg-church-surface px-3 py-2.5 text-base text-church-fg shadow-sm focus:border-church-gold/50 focus:outline-none focus:ring-2 focus:ring-church-gold/25 sm:text-sm';
@endphp

@section('content')
    <x-admin-edit-page
        :back-href="route('dashboard.index')"
        back-label="Dashboard"
        icon="fa-solid fa-user"
        title="Profil akun"
        :action="route('dashboard.profil-akun.update')"
        method="post"
        form-id="profile-form"
        enctype="multipart/form-data"
        submit-label="Simpan profil"
        :card-wrapped="false"
    >
        {{-- Kotak foto profil — paling atas --}}
        @if (! ($profilePhotoReady ?? true))
            <p class="public-card-hover rounded-2xl border border-amber-500/30 bg-amber-500/10 px-4 py-3 text-sm text-amber-200">
                Unggah foto profil belum aktif di database server. Minta pengelola server menjalankan:
                <code class="mt-1 block break-all text-xs text-amber-100">php artisan church:ensure-profile-photo-column</code>
            </p>
        @else
            @include('admin.partials.profile-photo-upload', [
                'previewUrl' => old('profile_photo_url', $user->profile_photo_url ?? ''),
                'persistedUrl' => $user->profile_photo_url ?? '',
                'userName' => old('name', $user->name),
                'userEmail' => $user->email,
                'userPhone' => old('phone', $user->phone),
            ])
        @endif

        @if (! ($phoneColumnReady ?? true))
            <p class="public-card-hover rounded-2xl border border-amber-500/30 bg-amber-500/10 px-4 py-3 text-sm text-amber-200">
                Simpan nomor HP belum aktif di database server. Minta pengelola server menjalankan:
                <code class="mt-1 block break-all text-xs text-amber-100">php artisan church:ensure-phone-column</code>
            </p>
        @endif

        {{-- Data akun --}}
        <div class="public-card-hover w-full min-w-0 space-y-5 rounded-2xl border border-white/10 bg-church-card/80 p-4 sm:space-y-6 sm:p-6">
            <fieldset class="min-w-0 space-y-4">
                <x-admin-field-label as="legend">Identitas</x-admin-field-label>
                <div class="min-w-0">
                    <x-admin-field-label>Nama</x-admin-field-label>
                    <input name="name" value="{{ old('name', $user->name) }}" required class="{{ $profileInputClass }}">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="min-w-0">
                    <x-admin-field-label>Email</x-admin-field-label>
                    <input
                        type="email"
                        value="{{ $user->email }}"
                        disabled
                        class="mt-1 w-full min-w-0 max-w-full cursor-not-allowed rounded-md border border-white/10 bg-church-surface/60 px-3 py-2.5 text-base text-slate-400 sm:text-sm"
                    >
                    <p class="mt-1.5 text-xs leading-relaxed text-slate-400">Email tidak dapat diubah dari halaman profil.</p>
                </div>
            </fieldset>

            @if ($phoneColumnReady ?? true)
                <fieldset class="min-w-0 space-y-4">
                    <x-admin-field-label as="legend">Informasi kontak</x-admin-field-label>
                    <p class="text-sm leading-relaxed text-slate-400">Nomor ini ditampilkan untuk keperluan koordinasi antar pengurus gereja.</p>
                    <div class="min-w-0">
                        <x-admin-field-label>Nomor HP / WhatsApp</x-admin-field-label>
                        <input
                            type="tel"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            autocomplete="tel"
                            inputmode="tel"
                            placeholder="08xxxxxxxxxx"
                            class="{{ $profileInputClass }}"
                        >
                        @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </fieldset>
            @endif

            <fieldset class="min-w-0 space-y-4">
                <x-admin-field-label as="legend">Keamanan</x-admin-field-label>
                <p class="text-sm leading-relaxed text-slate-400">Isi kata sandi saat ini jika ingin mengubah kata sandi.</p>
                <div class="min-w-0">
                    <x-admin-field-label for="current_password">Kata sandi saat ini</x-admin-field-label>
                    <x-password-input
                        name="current_password"
                        id="current_password"
                        autocomplete="current-password"
                        :input-class="$profileInputClass"
                    />
                    @error('current_password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="min-w-0">
                    <x-admin-field-label for="password">Kata sandi baru</x-admin-field-label>
                    <x-password-input
                        name="password"
                        id="password"
                        autocomplete="new-password"
                        :input-class="$profileInputClass"
                    />
                    @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="min-w-0">
                    <x-admin-field-label for="password_confirmation">Konfirmasi kata sandi baru</x-admin-field-label>
                    <x-password-input
                        name="password_confirmation"
                        id="password_confirmation"
                        autocomplete="new-password"
                        :input-class="$profileInputClass"
                    />
                </div>
            </fieldset>
        </div>
    </x-admin-edit-page>

    @include('admin.partials.profile-upload-loading')
    @include('admin.partials.profile-photo-detail-modal')
@endsection

@push('scripts')
    <script>
        (function () {
            var form = document.getElementById('profile-form');
            var overlay = document.getElementById('profile-upload-loading');
            var titleEl = document.getElementById('profile-upload-loading-title');
            var descEl = document.getElementById('profile-upload-loading-desc');
            var fileInput = document.getElementById('profile-photo-file');
            var pickBtn = document.getElementById('profile-photo-pick-btn');
            var deleteBtn = document.getElementById('profile-photo-delete-btn');
            var deleteFlag = document.getElementById('profile-photo-delete-flag');
            var previewImg = document.getElementById('profile-photo-preview');
            var placeholderEl = document.getElementById('profile-photo-placeholder');
            var viewBtn = document.getElementById('profile-photo-view-btn');
            var hintEl = document.getElementById('profile-photo-hint');
            var nameInput = form.querySelector('input[name="name"]');
            var phoneInput = form.querySelector('input[name="phone"]');
            var displayName = document.getElementById('profile-photo-display-name');
            var displayEmail = document.getElementById('profile-photo-display-email');
            var displayPhone = document.getElementById('profile-photo-display-phone');
            var displayPhoneText = document.getElementById('profile-photo-display-phone-text');
            var detailModal = document.getElementById('profile-photo-detail-modal');
            var detailImg = document.getElementById('profile-photo-detail-img');
            var detailName = document.getElementById('profile-photo-detail-name');
            var detailEmail = document.getElementById('profile-photo-detail-email');
            var detailPhone = document.getElementById('profile-photo-detail-phone');
            var detailClose = document.getElementById('profile-photo-detail-close');
            var detailBackdrop = document.getElementById('profile-photo-detail-backdrop');
            var editorRoot = document.querySelector('[data-profile-photo-editor]');

            if (!form || !overlay) return;

            var submitting = false;
            var objectUrl = null;

            function showLoading(title, desc) {
                if (titleEl) titleEl.textContent = title;
                if (descEl) descEl.textContent = desc;
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }

            function lockForm() {
                form.querySelectorAll('button, input[type="submit"]').forEach(function (el) {
                    el.disabled = true;
                    el.setAttribute('aria-disabled', 'true');
                });
            }

            function submitForm(title, desc) {
                if (submitting) return;
                submitting = true;
                showLoading(title, desc);
                lockForm();
                form.submit();
            }

            function getDisplayName() {
                if (nameInput && nameInput.value.trim()) return nameInput.value.trim();
                if (displayName) return displayName.textContent.trim();
                if (editorRoot) return editorRoot.getAttribute('data-user-name') || '';
                return '';
            }

            function getDisplayEmail() {
                if (displayEmail) return displayEmail.textContent.trim();
                if (editorRoot) return editorRoot.getAttribute('data-user-email') || '';
                return '';
            }

            function syncDisplayName() {
                if (!nameInput || !displayName) return;
                displayName.textContent = nameInput.value.trim() || displayName.textContent;
            }

            function syncDisplayPhone() {
                if (!phoneInput) return;
                var phone = phoneInput.value.trim();
                if (displayPhoneText) displayPhoneText.textContent = phone;
                if (displayPhone) {
                    displayPhone.classList.toggle('hidden', phone === '');
                }
                if (editorRoot) {
                    editorRoot.setAttribute('data-user-phone', phone);
                }
            }

            function getDisplayPhone() {
                if (phoneInput && phoneInput.value.trim()) return phoneInput.value.trim();
                if (displayPhoneText && displayPhoneText.textContent.trim()) return displayPhoneText.textContent.trim();
                if (editorRoot) return editorRoot.getAttribute('data-user-phone') || '';
                return '';
            }

            function setPhotoViewEnabled(enabled) {
                if (!viewBtn) return;
                viewBtn.disabled = !enabled;
                viewBtn.classList.toggle('pointer-events-none', !enabled);
            }

            function openPhotoDetail() {
                if (!detailModal || !detailImg || !previewImg || !previewImg.src) return;
                var name = getDisplayName();
                detailImg.src = previewImg.src;
                detailImg.alt = name ? 'Foto profil ' + name : 'Foto profil';
                if (detailName) detailName.textContent = name;
                if (detailEmail) detailEmail.textContent = getDisplayEmail();
                var phone = getDisplayPhone();
                if (detailPhone) {
                    if (phone !== '') {
                        detailPhone.textContent = phone;
                        detailPhone.classList.remove('hidden');
                    } else {
                        detailPhone.textContent = '';
                        detailPhone.classList.add('hidden');
                    }
                }
                detailModal.classList.remove('hidden');
                detailModal.classList.add('flex');
                detailModal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
                if (detailClose) detailClose.focus();
            }

            function closePhotoDetail() {
                if (!detailModal) return;
                detailModal.classList.add('hidden');
                detailModal.classList.remove('flex');
                detailModal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                if (viewBtn) viewBtn.focus();
            }

            function setPreviewFromFile(file) {
                if (!file || !previewImg) return;
                if (objectUrl) URL.revokeObjectURL(objectUrl);
                objectUrl = URL.createObjectURL(file);
                previewImg.src = objectUrl;
                previewImg.alt = '';
                previewImg.classList.remove('hidden');
                previewImg.classList.add('profile-photo-editor__avatar--preview');
                if (placeholderEl) {
                    placeholderEl.classList.add('hidden');
                    placeholderEl.setAttribute('aria-hidden', 'true');
                }
                setPhotoViewEnabled(true);
                if (hintEl) hintEl.classList.remove('hidden');
                if (deleteFlag) deleteFlag.value = '';
                var emptyNote = document.querySelector('.profile-photo-editor__empty-note');
                if (emptyNote) emptyNote.classList.add('hidden');
                var viewHint = viewBtn && viewBtn.querySelector('.profile-photo-editor__view-hint');
                if (viewHint) viewHint.classList.remove('hidden');
            }

            if (nameInput) {
                nameInput.addEventListener('input', syncDisplayName);
            }

            if (phoneInput) {
                phoneInput.addEventListener('input', syncDisplayPhone);
            }

            syncDisplayPhone();

            if (viewBtn) {
                viewBtn.addEventListener('click', function () {
                    if (!previewImg || !previewImg.src || viewBtn.disabled) return;
                    openPhotoDetail();
                });
            }

            if (detailClose) detailClose.addEventListener('click', closePhotoDetail);
            if (detailBackdrop) detailBackdrop.addEventListener('click', closePhotoDetail);

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && detailModal && !detailModal.classList.contains('hidden')) {
                    closePhotoDetail();
                }
            });

            setPhotoViewEnabled(previewImg && previewImg.src && !previewImg.classList.contains('hidden'));

            if (pickBtn && fileInput) {
                pickBtn.addEventListener('click', function () {
                    fileInput.click();
                });
                fileInput.addEventListener('change', function () {
                    var file = fileInput.files && fileInput.files[0];
                    if (!file) return;
                    setPreviewFromFile(file);
                    submitForm('Mengunggah foto…', 'Menyimpan foto profil Anda.');
                });
            }

            if (deleteBtn && deleteFlag) {
                deleteBtn.addEventListener('click', function () {
                    var runDelete = function () {
                        deleteFlag.value = '1';
                        if (fileInput) fileInput.value = '';
                        submitForm('Menghapus foto…', 'Foto profil akan dihapus dari akun Anda.');
                    };

                    if (typeof window.adminConfirm === 'function') {
                        window.adminConfirm({
                            title: 'Hapus foto profil?',
                            message: 'Foto profil akan dihapus permanen dari akun ini.',
                            confirmLabel: 'Ya, hapus',
                        }).then(function (ok) {
                            if (ok) runDelete();
                        });
                    } else if (window.confirm('Hapus foto profil?')) {
                        runDelete();
                    }
                });
            }

            form.addEventListener('submit', function (e) {
                if (submitting) {
                    e.preventDefault();
                    return;
                }

                var isDelete = deleteFlag && deleteFlag.value === '1';
                var hasNewFile = fileInput && fileInput.files && fileInput.files.length > 0;
                var title = 'Menyimpan profil…';
                var desc = 'Mohon tunggu, jangan tutup halaman.';

                if (isDelete) {
                    title = 'Menghapus foto…';
                    desc = 'Foto profil akan dihapus dari akun Anda.';
                } else if (hasNewFile) {
                    title = 'Mengunggah foto…';
                    desc = 'Sedang menyimpan foto profil baru.';
                }

                submitting = true;
                showLoading(title, desc);
                lockForm();
            });

            window.addEventListener('pagehide', function () {
                if (objectUrl) URL.revokeObjectURL(objectUrl);
            });
        })();
    </script>
@endpush
