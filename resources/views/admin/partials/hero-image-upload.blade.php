{{--
  Blok unggah gambar hero: foto, file, pratinjau, hapus.
  @var string $previewUrl  pratinjau dan input URL; bisa dari old()
  @var string $persistedHeroUrl  nilai tersimpan di DB untuk hapus file dan hidden previous
  @var bool $showUrlField
--}}
@php
    $previewUrl = $previewUrl ?? '';
    $persistedHeroUrl = $persistedHeroUrl ?? '';
    $showUrlField = $showUrlField ?? true;
    $heroPreviewSrc = \App\Support\PublicCmsUrl::imagePreviewSrc($previewUrl);
@endphp

<div class="space-y-4 rounded-lg border border-white/10 bg-church-surface/30 p-4">
    <div class="flex items-center gap-2">
        <span class="flex size-8 shrink-0 items-center justify-center rounded-md bg-sky-500/20 text-sky-400" aria-hidden="true">
            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </span>
        <span class="text-sm font-semibold text-church-fg">Foto</span>
    </div>

    <div>
        <input type="file" name="hero_image_file" accept="image/*" class="block w-full cursor-pointer rounded-lg border border-white/20 bg-church-surface px-3 py-2 text-sm text-slate-200 file:mr-4 file:rounded-md file:border-0 file:bg-slate-600 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-white ">
    </div>

    @if ($heroPreviewSrc)
        <div class="flex flex-wrap items-center gap-3">
            <img src="{{ $heroPreviewSrc }}" alt="" width="64" height="64" class="size-16 shrink-0 rounded-md border border-white/15 object-cover shadow-sm" loading="lazy" decoding="async">
        </div>
        <div>
 <button type="submit" name="hero_image_delete" value="1" class="public-btn-hover inline-flex items-center gap-2 rounded-lg border border-white/20 bg-white/10 px-3 py-2 text-sm font-medium text-slate-200 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-red-400/50" onclick="return confirm('Hapus foto ini dari hero beranda?');">
                <svg class="size-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Hapus
            </button>
        </div>
    @endif

    <input type="hidden" name="hero_image_url_previous" value="{{ $persistedHeroUrl }}">
</div>

@if ($showUrlField)
    <div class="mt-4">
        <x-admin-field-label class="text-sm text-slate-300">URL gambar — opsional</x-admin-field-label>
        <input name="hero_image_url" value="{{ $previewUrl }}" class="mt-1 w-full rounded-md border border-white/15 bg-church-surface px-3 py-2 text-sm text-church-fg">
    </div>
@endif
