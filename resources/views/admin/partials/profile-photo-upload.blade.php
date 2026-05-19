@php
    $previewUrl = $previewUrl ?? '';
    $persistedUrl = $persistedUrl ?? '';
    $userName = $userName ?? '';
    $userEmail = $userEmail ?? '';
    $userPhone = trim((string) ($userPhone ?? ''));
    $photoPreviewSrc = $previewUrl !== '' ? \App\Support\PublicCmsUrl::imagePreviewSrc($previewUrl) : null;
    $hasPhoto = $photoPreviewSrc !== null;
@endphp

<div
    class="profile-photo-editor public-card-hover w-full min-w-0 rounded-2xl border border-white/10 bg-church-card/80 p-4 sm:p-6"
    data-profile-photo-editor
    data-has-photo="{{ $hasPhoto ? '1' : '0' }}"
    data-user-name="{{ $userName }}"
    data-user-email="{{ $userEmail }}"
    data-user-phone="{{ $userPhone }}"
>
    <x-admin-field-label as="legend" class="mb-4 block w-full text-left" icon="fa-solid fa-image">
        Gambar profil
    </x-admin-field-label>

    <input
        type="file"
        id="profile-photo-file"
        name="profile_photo_file"
        accept="image/png,image/jpeg,image/webp,image/*"
        class="sr-only"
        tabindex="-1"
    >

    <div class="profile-photo-editor__body">
        <button
            type="button"
            id="profile-photo-view-btn"
            class="profile-photo-editor__photo-trigger group {{ $hasPhoto ? '' : 'pointer-events-none' }}"
            @unless ($hasPhoto) disabled @endunless
            aria-label="Lihat detail foto profil"
        >
            <div class="profile-photo-editor__avatar-wrap">
                <div
                    id="profile-photo-placeholder"
                    class="profile-photo-editor__avatar profile-photo-editor__avatar-placeholder {{ $hasPhoto ? 'hidden' : '' }}"
                    aria-hidden="{{ $hasPhoto ? 'true' : 'false' }}"
                >
                    <i class="fa-solid fa-user profile-photo-editor__user-icon" aria-hidden="true"></i>
                </div>
                <img
                    id="profile-photo-preview"
                    src="{{ $hasPhoto ? $photoPreviewSrc : '' }}"
                    alt=""
                    width="112"
                    height="112"
                    class="profile-photo-editor__avatar profile-photo-editor__avatar--preview {{ $hasPhoto ? '' : 'hidden' }}"
                    loading="{{ $hasPhoto ? 'eager' : 'lazy' }}"
                    decoding="async"
                >
            </div>
            <span class="profile-photo-editor__view-hint mt-2 inline-flex items-center gap-1.5 text-xs text-church-gold/90 {{ $hasPhoto ? 'opacity-0 transition group-hover:opacity-100 group-focus-visible:opacity-100' : 'hidden' }}">
                <i class="fa-solid fa-magnifying-glass-plus text-[0.65rem]" aria-hidden="true"></i>
                Klik untuk lihat detail
            </span>
        </button>

        <div class="profile-photo-editor__identity w-full max-w-sm">
            <p id="profile-photo-display-name" class="break-words text-base font-semibold leading-snug text-church-fg sm:text-lg">
                {{ $userName }}
            </p>
            <p id="profile-photo-display-email" class="mt-0.5 break-all text-sm text-slate-400">
                {{ $userEmail }}
            </p>
            <p
                id="profile-photo-display-phone"
                class="mt-1 inline-flex items-center gap-1.5 break-all text-sm text-slate-300 {{ $userPhone === '' ? 'hidden' : '' }}"
            >
                <i class="fa-solid fa-phone text-[0.7rem] text-church-gold/80" aria-hidden="true"></i>
                <span id="profile-photo-display-phone-text">{{ $userPhone }}</span>
            </p>
        </div>

        <div class="profile-photo-editor__actions w-full space-y-3">
            @unless ($hasPhoto)
                <p class="profile-photo-editor__empty-note text-sm text-slate-300">
                    Belum ada foto profil.
                </p>
            @endunless

            <button
                type="button"
                id="profile-photo-pick-btn"
                class="public-btn-hover inline-flex w-full items-center justify-center gap-2 rounded-xl border border-church-gold/35 bg-church-gold/15 px-4 py-3 text-sm font-semibold text-church-gold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-church-gold/50"
            >
                <i class="fa-solid fa-camera" aria-hidden="true"></i>
                {{ $hasPhoto ? 'Ganti foto' : 'Unggah foto' }}
            </button>

            @if ($hasPhoto)
                <button
                    type="button"
                    id="profile-photo-delete-btn"
                    class="public-btn-hover inline-flex w-full items-center justify-center gap-2 rounded-xl border border-red-500/35 bg-red-500/10 px-4 py-2.5 text-sm font-medium text-red-300 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-red-400/50"
                >
                    <i class="fa-solid fa-trash text-xs" aria-hidden="true"></i>
                    Hapus foto
                </button>
            @endif

            <p id="profile-photo-hint" class="hidden text-xs leading-relaxed text-church-gold">
                Menyimpan foto profil…
            </p>

            <p class="text-xs leading-relaxed text-slate-400">PNG, JPG, atau WebP.</p>

            @error('profile_photo_file')
                <p class="text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <input type="hidden" name="profile_photo_delete" id="profile-photo-delete-flag" value="">
    <input type="hidden" name="profile_photo_url_previous" value="{{ $persistedUrl }}">
</div>
