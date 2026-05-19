@extends('layouts.admin')

@section('title', 'Pengaturan Situs')

@section('content')
    <x-admin-edit-page
        :back-href="route('dashboard.setting.index')"
        back-label="Setting"
        icon="fa-solid fa-gear"
        title="Pengaturan situs"
        :action="route('dashboard.pengaturan.update')"
        form-id="settings-form"
        enctype="multipart/form-data"
        submit-label="Simpan pengaturan"
    >
        <div class="space-y-8">
            <fieldset class="space-y-4">
                <x-admin-field-label as="legend">Identitas</x-admin-field-label>
                <div>
                    <x-admin-field-label>Baris nama 1 — huruf kapital</x-admin-field-label>
                    <input name="church_name_line1" value="{{ old('church_name_line1', $settings['church_name_line1']) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
                </div>
                <div>
                    <x-admin-field-label>Baris nama 2</x-admin-field-label>
                    <input name="church_name_line2" value="{{ old('church_name_line2', $settings['church_name_line2']) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
                </div>
            </fieldset>
            <fieldset class="space-y-4">
                <x-admin-field-label as="legend">Kontak & alamat</x-admin-field-label>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <x-admin-field-label>Telepon</x-admin-field-label>
                        <input name="church_phone" value="{{ old('church_phone', $settings['church_phone']) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
                    </div>
                    <div>
                        <x-admin-field-label>Email</x-admin-field-label>
                        <input name="church_email" value="{{ old('church_email', $settings['church_email']) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
                    </div>
                </div>
                <div>
                    <x-admin-field-label>Alamat</x-admin-field-label>
                    <textarea name="church_address" rows="2" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">{{ old('church_address', $settings['church_address']) }}</textarea>
                </div>
                <div>
                    <x-admin-field-label>Catatan footer WA / Telepon</x-admin-field-label>
                    <input name="footer_whatsapp_note" value="{{ old('footer_whatsapp_note', $settings['footer_whatsapp_note']) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
                </div>
            </fieldset>
            <fieldset class="space-y-4">
                <x-admin-field-label as="legend">Sosial — tautan URL</x-admin-field-label>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div><x-admin-field-label class="text-sm font-medium">Facebook</x-admin-field-label><input name="social_facebook" value="{{ old('social_facebook', $settings['social_facebook']) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm"></div>
                    <div><x-admin-field-label class="text-sm font-medium">Twitter/X</x-admin-field-label><input name="social_twitter" value="{{ old('social_twitter', $settings['social_twitter']) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm"></div>
                    <div><x-admin-field-label class="text-sm font-medium">Instagram</x-admin-field-label><input name="social_instagram" value="{{ old('social_instagram', $settings['social_instagram']) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm"></div>
                    <div><x-admin-field-label class="text-sm font-medium">YouTube</x-admin-field-label><input name="social_youtube" value="{{ old('social_youtube', $settings['social_youtube']) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm"></div>
                </div>
            </fieldset>
            <fieldset class="space-y-4">
                <x-admin-field-label as="legend">Hero beranda</x-admin-field-label>
                @include('admin.partials.hero-image-upload', [
                    'previewUrl' => old('hero_image_url', $settings['hero_image_url'] ?? ''),
                    'persistedHeroUrl' => $settings['hero_image_url'] ?? '',
                    'showUrlField' => true,
                ])
                <div class="grid gap-4 sm:grid-cols-2">
                    <div><x-admin-field-label class="text-sm font-medium">Script atas</x-admin-field-label><input name="hero_script_top" value="{{ old('hero_script_top', $settings['hero_script_top']) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm"></div>
                    <div><x-admin-field-label class="text-sm font-medium">Script bawah</x-admin-field-label><input name="hero_script_bottom" value="{{ old('hero_script_bottom', $settings['hero_script_bottom']) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm"></div>
                </div>
                <div>
                    <x-admin-field-label class="text-sm font-medium">Judul emas — gaya serif</x-admin-field-label>
                    <input name="hero_title_gold" value="{{ old('hero_title_gold', $settings['hero_title_gold']) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
                </div>
                <div>
                    <x-admin-field-label class="text-sm font-medium">Judul putih</x-admin-field-label>
                    <input name="hero_title_white" value="{{ old('hero_title_white', $settings['hero_title_white']) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
                </div>
            </fieldset>
            <fieldset class="space-y-4">
                <x-admin-field-label as="legend">Visi beranda</x-admin-field-label>
                <div>
                    <x-admin-field-label>Judul</x-admin-field-label>
                    <input name="vision_title" value="{{ old('vision_title', $settings['vision_title']) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">
                </div>
                <div>
                    <x-admin-field-label>Isi</x-admin-field-label>
                    <textarea name="vision_body" rows="5" class="mt-1 w-full rounded-md border-slate-300 shadow-sm">{{ old('vision_body', $settings['vision_body']) }}</textarea>
                </div>
            </fieldset>
        </div>
    </x-admin-edit-page>
@endsection
